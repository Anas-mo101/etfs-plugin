<?php

class PostMetaUtils{
    var $meta = array();
    var $file_name;
    var $files_map;
    var $connectionId = null;
    var $selected_etfs = null;

    function __construct($incoming, $file, $connection = null, $files_map = null)
    {
        $this->meta = $incoming;
        $this->file_name = $file;
        $this->files_map = $files_map;
        $this->connectionId = $connection;
    }

    function set_selected($etf_name)
    {
        $this->selected_etfs = $etf_name;
    }

    function get_process(){
        $process = $this->files_map !== null ? array_search($this->file_name, $this->files_map, true) : $this->file_name;

        return $process;
    }
}