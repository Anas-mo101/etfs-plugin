<?php

class CsvProvider
{

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
        if (($handle = fopen($url, "r")) == FALSE) return false; 
        $result = $this->load_stream_and_fetch($handle);
        fclose($handle);
        return $result;
    }

    function load_stream_and_fetch($stream){
        $result = array();
        $_temp_null = null;
        while (($data = fgetcsv($stream)) !== FALSE) {
            if(is_null($_temp_null)){ 
                $_temp_null = 0;
                continue;
            }
            $current_row = array_combine($columns,$data);  
            $_new_len = sizeof($result) + 1;
            $_temp = array($_new_len => $current_row);
            $result = array_merge($result, $_temp);
        }
        return $result;
    }

}