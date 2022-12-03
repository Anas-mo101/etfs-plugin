<?php
namespace ETFsSFTP;

class SFTP{ 
    var $single_inseration = false;
    var $sftp;
    var $_config;
    private $table_name = "etfs_sftp_config_db"; 
    private static $instance = null;

    private function __construct(){ }

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new SFTP();
        }
        return self::$instance;
    }

    function sftp_db_init(){
        global $wpdb;
        $plugin_name_db_version = '1.0';
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db"; 
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $wp_table_name (
                    id varchar(12) UNIQUE DEFAULT '',
                    Automate char(1) NOT NULL DEFAULT 'f',
                    Active_Cycle char(1) NOT NULL DEFAULT 'f',
                    Host varchar(255) NOT NULL DEFAULT '',
                    User varchar(255) NOT NULL DEFAULT '',
                    Pass varchar(255) NOT NULL DEFAULT '',
                    Port varchar(255) NOT NULL DEFAULT '',
                    Timing varchar(255) NOT NULL DEFAULT '',
                    Nav varchar(255) NOT NULL DEFAULT '',
                    Holding varchar(255) NOT NULL DEFAULT '',
                    Ror varchar(255) NOT NULL DEFAULT '',
                    Ind varchar(255) NOT NULL DEFAULT '',
                    Sec varchar(255) NOT NULL DEFAULT '',
                    Last_Cycle_Timestamp DATETIME
                ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        $this->sftp_db_insert_init();
    }

    function sftp_db_insert_init(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db"; 
        $wpdb->insert( 
            $wp_table_name, 
            array( 
                'Id' => 'sftp_main_db', 
                'Automate' => 'f', 
                'Active_Cycle' => 'f',
                'Host' => '*', 
                'User' => '*',
                'Pass' => '*', 
                'Port' => '*', 
                'Timing' => 'hourly',
                'Nav' => '*', 
                'Holding' => '*', 
                'Ror' => '*',
                'Ind' => '*',
                'Sec' => '*',
                'Last_Cycle_Timestamp' => NULL
            ) 
        );
    }

    function sftp_db_remove_init(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db"; 

        $wpdb->delete( $wp_table_name, array( 'Id' => 'sftp_main_db') );

    }

    function get_config_db(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db"; 
        $id = 'sftp_main_db';
        $config = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wp_table_name WHERE Id = %d", $id), ARRAY_A );
        return $config;
    }

    function update_config_db(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db";

        // check for null before update
        $data = [ 
            'Automate' => $this->_config["Automate"], 
            'Host' => $this->_config["Host"], 
            'User' => $this->_config["User"], 
            'Pass' => $this->_config["Pass"], 
            'Port' => $this->_config["Port"],
            'Timing' => $this->_config["Timing"]
        ];
        $where = [ 'Id' => 'sftp_main_db' ]; // NULL value in WHERE clause.
        $wpdb->update( $wp_table_name, $data, $where ); // Also works in this case.
    }

    function update_config_db_files(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db";
        
        // check for null before update
        $data = [ 
            'Nav' => $this->_config["Nav"], 
            'Holding' => $this->_config["Holding"], 
            'Ror' => $this->_config["Ror"], 
            'Ind' => $this->_config["Ind"],
            'Sec' => $this->_config["Sec"]
        ];
        $where = [ 'Id' => 'sftp_main_db' ]; 
        $wpdb->update( $wp_table_name, $data, $where ); 
    }

    function cycle_timestamp(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db";

        date_default_timezone_set("America/Chicago");
        $current_time = date("Y-m-d h:i:s A");

        $data = ['Last_Cycle_Timestamp' => $current_time ];

        $where = [ 'Id' => 'sftp_main_db' ]; 
        $wpdb->update( $wp_table_name, $data, $where ); 
    }

    function force_turn_off(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db";
        $data = ['Automate' => 'f' ];
        $where = [ 'Id' => 'sftp_main_db' ]; 
        $wpdb->update( $wp_table_name, $data, $where ); 
    }

    function cycle_state($state){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_sftp_config_db";
        $data = ['Active_Cycle' => $state ];
        $where = [ 'Id' => 'sftp_main_db' ]; 
        $wpdb->update( $wp_table_name, $data, $where ); 
    }

    function set_config($args){
        $this->get_config();
        $_cycle_res = 'cycle state unchanged';
        $pre_auto = (! isset( $this->_config['Automate'])) ? 'f' : $this->_config['Automate'];


        // // validate $args
        if(!isset($args['state']) || (trim($args['state']) === '') ||
            !isset($args['host']) || (trim($args['host']) === '') ||
                !isset($args['user']) || (trim($args['user']) === '') ||
                    !isset($args['pass']) || (trim($args['pass']) === '') ||
                        !isset($args['port']) || (trim($args['port']) === '') ||
                            !isset($args['freq']) || (trim($args['freq']) === '')) {
            $res = Array('update' => 'null entries','cycle' => 'invalid entry');
            return $res;
        }

        // update $_config
        $this->_config["Automate"] = ($args["state"] === 'true') ? 't' : 'f';
        $this->_config["Host"] = $args["host"];
        $this->_config["Port"] = $args["port"];
        $this->_config["Pass"] = $args["pass"];
        $this->_config["User"] = $args["user"];
        $this->_config["Timing"] = $args["freq"];

        // update config db
        $this->update_config_db();

        // in case $this->_config["Automate"] turns from false to true.
        // start the sftp cycle when turned on then follow timing/schedule.
        
        if($this->_config["Automate"] === "t" && $pre_auto !== $this->_config["Automate"]){
            $_cycle_res = $this->auto_cycle();
        }elseif($this->_config["Automate"] === "f" && $pre_auto !== $this->_config["Automate"]){
            $_cycle_res = 'sftp off';
        }

        // return response according to cycle state
        $res = Array('update' => 'success', 'cycle' => $_cycle_res);
        return $res;
    }

    public function get_config(){
        if(!$this->_config || is_null($this->_config) || count($this->_config) == 0 ){
            $this->_config = $this->get_config_db();
        }
        return $this->_config;
    }

    function connect($auto = false){
        $this->get_config();

        if($auto === true){
            if($this->_config["Automate"] === 'f'){
                return "SFTP is Off";
            }
        }
        
        // libssh2 php extention -> https://www.libssh2.org/download/
        // ssh2 php extention -> https://pecl.php.net/package/ssh2/ 
        if ( ! extension_loaded( 'ssh2' ) ) return "The ssh2 PHP extension is not available";

        $connection = ssh2_connect($this->_config["Host"], $this->_config["Port"]);
        if (!$connection) return "Connection Failed";

        $auth = @ssh2_auth_password($connection, $this->_config["User"], $this->_config["Pass"]);
        if (!$auth) return "Authentication Failed";

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
        $contents = '';
        while (!feof($stream)) { $contents .= fread($stream, 8192); }        
        file_put_contents($local_save_file_path, $contents);
        @fclose($stream);
        return $local_save_file_path;
    }

    function disconnect(){
        $this->sftp = null; 
        unset($this->sftp);
    }

    function auto_cycle($auto = false){
        $this->cycle_state('t');

        // connect to sftp server
        if(($con_res = $this->connect($auto)) !== true){
            // $this->force_turn_off();
            $this->cycle_state('f');
            return $con_res;
        }

        // set file names to look for
        $file_set_name = array('Nav' => 'nav', 'Holding' => 'holding','Ror' => 'ror', 'Ind' => 'ind', 'Sec' => 'sec' );
        foreach ($file_set_name as $key => $value )  {
            if(isset($this->_config["$key"]) && $this->_config["$key"] !== '*' ){
                $file_set_name["$key"] = $this->_config[$key];
            }else{
                unset($file_set_name["$key"]);
            }
        }
        
        $files_required = array();
        foreach ($file_set_name as $key => $value) {
            if(! is_null($value) && $value !== '*'){
                $files_required[] = $value;
            }
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
            $this->force_turn_off();
            $this->cycle_state('f');
            return "No Required Files Available, Do Allocate Correct File Naming Below";
        }
        
        // save file on our server
        $files_unprocessed_and_available_localy = array();
        foreach($files_required_and_available_remotely as $name=>$path){
            if($new_local_path = $this->save_file($path,$name)){
                $files_unprocessed_and_available_localy[] = array($name => $new_local_path);
                unset($files_required_and_available_remotely["$name"]);
            }
        }

        // separate by file types -> extract & save data from each file at a time
        $save_meta_status = array();
        foreach($files_unprocessed_and_available_localy as $file_available){
            foreach ($file_available as $name => $path) {
                $path_info = pathinfo($name);
                if($path_info['extension'] === "csv"){
                    $columns = (new \CsvProvider())->load_and_fetch_headers($path);
                    $data = (new \CsvProvider())->load_and_fetch($path, $columns);
                    if(! $data || count($data) == 0){
                        $save_meta_status[] = array($name => 'failed to fetch data');
                    }else{
                        // update db with new data
                        $post_meta = new \PostMeta($data,$name,$file_set_name); 
                        $res = $post_meta->process_incoming();
                        $save_meta_status[] = array($name => $res); 
                    }
                    //update meta using file name
                }else{
                    $save_meta_status[] = array($name => 'not supported');
                }
            }
        }

        (new \Calculations())->calc_all();

        // disconnect to sftp server
        $this->disconnect();
        $this->cycle_timestamp();
        $this->cycle_state('f');
        error_log('First SFTP Cycle Is Successful');
        return "First SFTP Cycle Is Successful";
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

    function set_files_name($args){

        // validate $args
        if(isset($args['nav']) || (trim($args['nav']) !== '') ) {
            $this->_config["Nav"] = $args["nav"];
        }

        if(isset($args['holding']) || (trim($args['holding']) !== '') ) {
            $this->_config["Holding"] = $args["holding"];
        }

        if(isset($args['ror']) || (trim($args['ror']) !== '') ) {
            $this->_config["Ror"] = $args["ror"];
        }

        // if(isset($args['dist']) || (trim($args['dist']) !== '') ) {
        //     $this->_config["Dist"] = $args["dist"];
        // }

        if(isset($args['sec']) || (trim($args['sec']) !== '') ) {
            $this->_config["Sec"] = $args["sec"];
        }

        if(isset($args['ind']) || (trim($args['ind']) !== '') ) {
            $this->_config["Ind"] = $args["ind"];
        }

        // update config db
        $this->update_config_db_files();

        return 'success';
    }
}

function do_sftp_cycle(){
    $sftp = SFTP::getInstance();
    $sftp->auto_cycle(true);
}