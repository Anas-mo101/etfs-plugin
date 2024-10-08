<?php

namespace ETFsSFTP;


class SFTP
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SFTP();
        }
        return self::$instance;
    }

    function connect(int $id, $auto = false)
    {
        $connections_services = new \ConnectionServices();
        $data = $connections_services->get_config_db($id);

        if ($auto === true && $data["Automate"] === 'f') {
            return "SFTP is Off";
        }

        // libssh2 php extention -> https://www.libssh2.org/download/
        // ssh2 php extention -> https://pecl.php.net/package/ssh2/ 
        if (!extension_loaded('ssh2')) return "The ssh2 PHP extension is not available";

        $connection = ssh2_connect($data["Host"], $data["Port"]);
        if (!$connection) return "Connection Failed";

        $auth = @ssh2_auth_password($connection, $data["User"], $data["Pass"]);
        if (!$auth) return "Authentication Failed";

        $sftp = ssh2_sftp($connection);

        return $sftp;
    }

    private function scan_filesystem($remote_file, $sftp)
    {
        $dir = "ssh2.sftp://$sftp$remote_file";
        $remote_files_path = array();

        $is_directory = is_dir($dir);
        if (!$is_directory) {
            return [];
        }

        $dh = opendir($dir);
        if (!$dh) {
            return [];
        }

        while (($file = readdir($dh)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $filePath = $remote_file . $file;
            $fullPath = $dir . '/' . $file;

            $filetype = filetype($fullPath);

            if ($filetype == "dir") {
                $subDirFiles = $this->scan_filesystem($filePath . '/', $sftp);
                foreach ($subDirFiles as $subFile) {
                    $remote_files_path[] = $subFile;
                }
            } else {
                $remote_files_path[] = $filePath;
            }
        }

        closedir($dh);

        return $remote_files_path;
    }

    private function save_file($remote_file, $file_name)
    {
        $local_save_dir = wp_get_upload_dir();
        $local_save_file_path = $local_save_dir["path"] . '/' . $file_name;
        $stream = @fopen($remote_file, 'r');
        if (!$stream) return false;
        $contents = '';
        while (!feof($stream)) {
            $contents .= fread($stream, 8192);
        }
        file_put_contents($local_save_file_path, $contents);
        @fclose($stream);
        return $local_save_file_path;
    }

    function disconnect($sftp)
    {
        $sftp = null;
        unset($sftp);
    }

    function auto_cycle(int $id, $auto = false)
    {
        $connections_services = new \ConnectionServices();

        $connections_services->cycle_state('t', $id);
        $data = $connections_services->get_config_db($id);

        // connect to sftp server
        $sftp = $this->connect($id, $auto);
        if ($sftp === false || gettype($sftp) === "string") {
            $connections_services->cycle_state('f', $id);
            return $sftp;
        }

        // set file names to look for
        $file_set_name = array('Nav' => 'nav', 'Holding' => 'holding', 'Ror' => 'ror', 'Ind' => 'ind', 'Sec' => 'sec');
        foreach ($file_set_name as $key => $value) {
            if (isset($data["$key"]) && $data["$key"] !== '*') {
                $file_set_name["$key"] = $data[$key];
            } else {
                unset($file_set_name["$key"]);
            }
        }

        $files_required = array();
        foreach ($file_set_name as $key => $value) {
            if (!is_null($value) && $value !== '*') {
                $files_required[] = $value;
            }
        }

        // scan file sftp dir & find required files
        $files_path = $this->scan_filesystem('/', $sftp);
        $files_name = array();

        foreach ($files_path as $key => $file_path) {
            $pattern = '/[^\/]+$/U';
            preg_match($pattern, $file_path, $matches);
            if (in_array($matches[0], $files_required)) {
                $files_name[] = $matches[0];
            } else {
                unset($files_path["$key"]);
            }
        }

        $files_required_and_available_remotely = array_combine($files_name, $files_path);
        if (!$files_required_and_available_remotely || count($files_required_and_available_remotely) === 0) {
            $connections_services->force_turn_off($id);
            $connections_services->cycle_state('f', $id);

            $this->disconnect($sftp);
            return [
                "status" => "No Required Files Available, Do Allocate Correct File Naming Below",
                "remote_files" => $files_path
            ];
        }

        // save file on our server
        $dir = "ssh2.sftp://$sftp";
        $files_unprocessed_and_available_localy = array();
        foreach ($files_required_and_available_remotely as $name => $path) {
            $p = $dir . $path;
            $new_local_path = $this->save_file($p, $name);

            if ($new_local_path) {
                $files_unprocessed_and_available_localy[] = array($name => $new_local_path);
            }
        }

        if (count($files_unprocessed_and_available_localy) === 0) {
            $connections_services->force_turn_off($id);
            $connections_services->cycle_state('f', $id);
            $this->disconnect($sftp);

            return [
                "status" => "No Files Available Localy",
                "remote_files" => $files_required_and_available_remotely
            ];
        }

        // separate by file types -> extract & save data from each file at a time
        $save_meta_status = array();
        foreach ($files_unprocessed_and_available_localy as $file_available) {
            $name = key($file_available);
            $path = $file_available[$name];

            $path_info = pathinfo($name);
            if ($path_info['extension'] === "csv") {

                $columns = (new \CsvProvider())->load_and_fetch_headers($path);
                $data = (new \CsvProvider())->load_and_fetch($path, $columns);

                if (!$data || count($data) == 0) {
                    $save_meta_status[] = array($name => 'failed to fetch data');
                } else {
                    $post_meta = new \PostMeta($data, $name, $id, $file_set_name);
                    $res = $post_meta->process_incoming();

                    $dynamic = new \DynamicProductsTable();
                    $dynamic->update_tables_by_file_name($id, $name, json_encode($data));

                    $save_meta_status[] = [$name => $res];
                }
            } else {
                $save_meta_status[] = array($name => 'not supported');
            }
        }

        (new \Calculations())->calc_all();

        write_product_xlsx_file();

        $this->disconnect($sftp);
        $connections_services->cycle_timestamp($id);
        $connections_services->cycle_state('f', $id);
        error_log('First SFTP Cycle Is Successful');
        return $save_meta_status;
    }

    function get_dir_conntent(int $id, string $extension = null)
    {
        // connect to sftp server
        $sftp = $this->connect($id);
        if ($sftp === false || gettype($sftp) === "string") {
            return $sftp;
        }

        // scan file sftp dir & find required files
        $files_path = $this->scan_filesystem('/', $sftp);
        $files_name = array();
        foreach ($files_path as $key => $file_path) {
            $pattern = '/[^\/]+$/U';
            preg_match($pattern, $file_path, $matches);
            $file_name = $matches[0];

            // If an extension is provided, filter by it
            if ($extension === null || pathinfo($file_name, PATHINFO_EXTENSION) === $extension) {
                $files_name[] = $file_name;
            }
        }

        $this->disconnect($sftp);
        return $files_name;
    }
}

function do_sftp_cycle()
{
    $sftp = SFTP::getInstance();
    $connections_services = new \ConnectionServices();

    $connections = $connections_services->list_connections();

    for ($i = 0; $i < count($connections); $i++) {
        try {
            $connection = $connections[$i];
            $sftp->auto_cycle((int) $connection["id"], true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
