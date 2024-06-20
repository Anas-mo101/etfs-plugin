<?php


class PostMeta
{
    var $utils;

    function __construct($incoming, $file, $connection = null, $files_map = null)
    {
        date_default_timezone_set("America/New_York");
        
        $this->utils = new PostMetaUtils($incoming, $file, $connection, $files_map);
    }

    function process_incoming(): bool
    {
        $process = $this->utils->get_process();

        $handlers = [
            "Holding" => new HoldingPostMeta(),
            "Nav" => new NavPostMeta(),
            "Ror" => new RorPostMeta(),
            "Ind" => new IndPostMeta(),
            "Sec" => new SecPostMeta(),
        ];

        if( ! isset( $handlers[$process] ) ){ 
            return false;
        }

        $meta = $handlers[$process];

        return $meta->process_incoming( $this->utils );
    }
}