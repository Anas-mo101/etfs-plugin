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

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
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
        $_temp_config = '{"auto": "false", "host": "null", "username": "null", "password": "null", "port": "null", "timing": "null" }';
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
        // // validate $args
        if(!isset($args['state']) || (trim($args['state']) === '') ||
            !isset($args['host']) || (trim($args['host']) === '') ||
                !isset($args['user']) || (trim($args['user']) === '') ||
                    !isset($args['pass']) || (trim($args['pass']) === '') ||
                        !isset($args['port']) || (trim($args['port']) === '') ||
                            !isset($args['freq']) || (trim($args['freq']) === '')) {
            $res = Array('update' => 'null entries');
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

        $connection_res = $this->connect();

        // return response according to state
        $res = Array('update' => 'success', 'connection' => $connection_res);
        return $res;
    }

    function get_config(){
        return $this->cooked_josn;
    }

    function connect(){
        if($this->cooked_josn["auto"] === false) return "blocked";

        $connection = ssh2_connect($this->cooked_josn["host"], $this->cooked_josn["port"]);
        if (!$connection) return "failed";

        $auth = @ssh2_auth_password($connection, $this->cooked_josn["username"], $this->cooked_josn["password"]);
        if (!$auth) return "authentication failed";

        $this->sftp = ssh2_sftp($connection);
        return "success";
    }

    function get_file($url){
        $stream = fopen('ssh2.sftp://' . intval($this->sftp) . $url, 'r');
        if (! $stream) {return false;}else{ return $stream;}
    }

    function disconnect(){
        $this->sftp = null; 
        unset($this->sftp);
    }
}