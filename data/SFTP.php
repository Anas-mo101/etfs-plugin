<?php

// $object1 = SFTP::getInstance();

class SFTP{ 

    var $sftp;
    var $cooked_josn;
    var $_config = 'etfs-config.json';
    var $config_path;
    private static $instance = null;

    private function __construct(){
        $this->init_config();
        $this->load_config();
    }

    public static function getInstance()
    {
      if (self::$instance == null){
        self::$instance = new SFTP();
      }
      return self::$instance;
    }

    private function init_config(){
        // check for etfs-config.josn
        $this->config_path = trailingslashit( dirname( __FILE__ ) );
        if (file_exists($this->config_path . $this->_config)) {
            // check for correct content
            return true;
        }

        // create one if does not exist
        $_temp_file = fopen($this->config_path . $this->_config, "w");
        $_temp_config = '{"auto": "false", "host": "null", "username": "null", "password": "null", "port": "null", "timing": "null", "files" : { "nav" : "", "holding" : "", "ror" : "", "dist" : "" } }';
        fwrite($_temp_file, $_temp_config);
        fclose($_temp_file);
    }

    private function load_config(){
        if (($raw_json = @file_get_contents($this->config_path . $this->_config)) === false) {
            $error = error_get_last();
            return false;
        }
        $this->cooked_josn = json_decode(trim($raw_json), true);
    }

    function set_config($args){
        $_cycle_res = 'ongoing';
        $pre_auto = $this->cooked_josn["auto"];

        // // validate $args
        if(!isset($args['state']) || (trim($args['state']) === '') ||
            !isset($args['host']) || (trim($args['host']) === '') ||
                !isset($args['user']) || (trim($args['user']) === '') ||
                    !isset($args['pass']) || (trim($args['pass']) === '') ||
                        !isset($args['port']) || (trim($args['port']) === '') ||
                            !isset($args['freq']) || (trim($args['freq']) === '')) {
            $res = Array('update' => 'null entries','cycle' => 'interrupted');
            return $res;
        }

        // update $cooked_josn
        $this->cooked_josn["auto"] = $args["state"];
        $this->cooked_josn["host"] = $args["host"];
        $this->cooked_josn["port"] = $args["port"];
        $this->cooked_josn["password"] = $args["pass"];
        $this->cooked_josn["username"] = $args["user"];
        $this->cooked_josn["timing"] = $args["freq"];
        
        // update etfs-config.json
        $file = fopen($this->config_path . $this->_config,'w');
        $raw_json = json_encode($this->cooked_josn);
        fwrite($file, $raw_json);
        fclose($file);

        // in case $this->cooked_josn["auto"] turns from false to true 
        // start the sftp cycle when turned on then follow timing/schedule
        if($this->cooked_josn["auto"] === "true" && $pre_auto !== $this->cooked_josn["auto"]){
            $_cycle_res = $this->auto_cycle();
        }elseif($this->cooked_josn["auto"] === "false" && $pre_auto !== $this->cooked_josn["auto"]){
            $_cycle_res = 'blocked';
        }

        // return response according to cycle state
        $res = Array('update' => 'success', 'cycle' => $_cycle_res);
        return $res;
    }

    public function get_config(){
        return $this->cooked_josn;
    }

    function connect(){
        
        // libssh2 php extention -> https://www.libssh2.org/download/
        // ssh2 php extention -> https://pecl.php.net/package/ssh2/ (1.3.1 for php 8.0 on local bitnami server)
        if ( ! extension_loaded( 'ssh2' ) ) return "The ssh2 PHP extension is not available";

        $connection = ssh2_connect($this->cooked_josn["host"], $this->cooked_josn["port"]);
        if (!$connection) return "failed";

        $auth = @ssh2_auth_password($connection, $this->cooked_josn["username"], $this->cooked_josn["password"]);
        if (!$auth) return "authentication failed";

        $this->sftp = ssh2_sftp($connection);

        return true;
    }

    private function scan_filesystem($remote_file) {
        $sftp = $this->sftp;
        $dir = "ssh2.sftp://$sftp$remote_file";
        $tempArray = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $filetype = filetype($dir . $file);
                    if($filetype == "dir") {
                        $tmp = $this->scan_filesystem($remote_file . $file . "/");
                        foreach($tmp as $t) {
                            $tempArray[] = $file . "/" . $t;
                        }
                    } else {
                        $tempArray[] = $dir . "/" . $file;
                    }
                }
                closedir($dh);
            }
        }
       return $tempArray;
    }

    private function save_file($remote_file,$file_name)
    {
        $local_save_dir = wp_get_upload_dir();
        $local_save_file_path = $local_save_dir["path"] .'/'. $file_name;
        $stream = @fopen($remote_file, 'r');
        if (! $stream) return false;
        $contents = fread($stream, filesize($remote_file));
        file_put_contents($local_save_file_path, $contents);
        @fclose($stream);
        return $local_save_file_path;
    }

    function disconnect(){
        $this->sftp = null; 
        unset($this->sftp);
    }

    function auto_cycle(){
        // set file names to look for
        // $files_required = array('TrueMarkWeb.40YR.YR_DailyNAV.csv' => false, 'TrueMarkWeb.40YR.YR_Holdings.csv' => false);
        $files_required = array();
        foreach ($this->cooked_josn["files"] as $key => $value) {
            if(! is_null($value) && $value !== ''){
                $files_required[] = $value;
            }
        }
        
        // init cycle schedule @ ETFPlugin 

        // connect to sftp server
        if(($con_res = $this->connect()) !== true){
            return $con_res;
        }

        // scan file sftp dir & find required files
        $files_path = $this->scan_filesystem('/download');
        $files_name = array();
        foreach($files_path as $key=>$file_path){
            $pattern = '/[^\/]+$/U';
            preg_match($pattern, $file_path, $matches);
            if(in_array($matches[0],$files_required)){
                $files_name[] = $matches[0];
            }else{
                unset($files_path["$key"]);
            }
        }

        $files_required_and_available_remotely = array_combine($files_name,$files_path);
        

        if (!$files_required_and_available_remotely || count($files_required_and_available_remotely) === 0) {
            return "no required files available";
        }
        
        // save file on our server
        $files_unprocessed_and_available_localy = array();
        foreach($files_required_and_available_remotely as $name=>$path){
            if($new_local_path = $this->save_file($path,$name)){
                $files_unprocessed_and_available_localy[] = array($name => $new_local_path);
                unset($files_required_and_available_remotely["$name"]);
            }
        }

        // separate by file types -> extract & save data from file 
        $save_meta_status = array();
        foreach($files_unprocessed_and_available_localy as $file_available){
            foreach ($file_available as $name => $path) {
                $path_info = pathinfo($name);
                if($path_info['extension'] === "csv"){
                    $columns = (new CsvProvider())->load_and_fetch_headers($path);
                    $data = (new CsvProvider())->load_and_fetch($path, $columns);

                    if(! $data || count($data) == 0){
                        $save_meta_status[] = array($name => 'failed to fetch data');
                    }else{
                        // update db with new data
                        $post_meta = new PostMeta($data,$name,$this->cooked_josn['files']); // pass  instead of name
                        $res = $post_meta->process_incoming();
                        $save_meta_status[] = array($name => $res); 
                    }
                    //update meta using file name
                }elseif($path_info['extension'] === "pdf"){
                    // instead of using names to update each ETFs indiviaully
                    // use all etfs data avaiable to update etfs accordingly
                    $data = array();
                    $process = array_search($name,$this->cooked_josn['files'],true);
                    switch ($process) {
                        case 'ror':
                            // do ror processing
                            $data = (new Pdf2Data())->get_all_monthly_fund_data($path);
                            break;
                        case 'dist':
                            // do dist memo processing
                            $data = (new Pdf2Data())->get_all_distrubation_memo_data($path);
                            break;
                        default: break; // unwanted file names
                    }

                    if(! $data || count($data) == 0){
                        $save_meta_status[] = array($name => 'failed to fetch data');
                    }else{
                        // update db with new data
                        $post_meta = new PostMeta($data,$name,$this->cooked_josn['files']);
                        $res = $post_meta->process_incoming();
                        $save_meta_status[] = array($name => $res); 
                    }
                }else{
                    $save_meta_status[] = array($name => 'not supported');
                }
            }
        }

        // disconnect to sftp server
        $this->disconnect();
        return "first sftp cycle is successfull";
    }

    function get_dir_conntent(){
        if(($con_res = $this->connect()) !== true){
            return $con_res;
        }

        // scan file sftp dir & find required files
        $files_path = $this->scan_filesystem('/download');
        $files_name = array();
        foreach($files_path as $key=>$file_path){
            $pattern = '/[^\/]+$/U';
            preg_match($pattern, $file_path, $matches);
            $files_name[] = $matches[0];
        }

        $this->disconnect();
        return $files_name;
    }

    function set_files_name($args){ // not updating

        // validate $args
        if(isset($args['nav']) || (trim($args['nav']) !== '') ) {
            $this->cooked_josn["files"]["nav"] = $args["nav"];
        }

        if(isset($args['holding']) || (trim($args['holding']) !== '') ) {
            $this->cooked_josn["files"]["holding"] = $args["holding"];
        }

        if(isset($args['ror']) || (trim($args['ror']) !== '') ) {
            $this->cooked_josn["files"]["ror"] = $args["ror"];
        }

        if(isset($args['dist']) || (trim($args['dist']) !== '') ) {
            $this->cooked_josn["files"]["dist"] = $args["dist"];
        }

        // update etfs-config.json
        $file = fopen($this->config_path . $this->_config,'w');
        $raw_json = json_encode($this->cooked_josn);
        fwrite($file, $raw_json);
        fclose($file);

        return 'success';
    }
}