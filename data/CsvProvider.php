<?php

class CsvProvider
{
    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

    function load_and_fetch_headers($url){
        $result = array();
        if (($handle = fopen($url, "r")) == FALSE) { return false; }
        while (($data = fgetcsv($handle)) !== FALSE) {
            $result = $data; 
            break;
        }
        fclose($handle);
        return $result;
    }

    function load_and_fetch($url,$columns){
        $result = array();
        $_temp_null = null;
        if (($handle = fopen($url, "r")) == FALSE) { return false; }
        while (($data = fgetcsv($handle)) !== FALSE) {
            if(is_null($_temp_null)){ 
                $_temp_null = 0;
                continue;
            }
            $current_row = array_combine($columns,$data);  // append array to main array
            $_new_len = sizeof($result) + 1;
            $_temp = array($_new_len => $current_row);
            $result = array_merge($result, $_temp);
        }
        fclose($handle);
        return $result;
    }
}