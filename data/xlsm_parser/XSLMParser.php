<?php
namespace ETFsXSLMParser;
use Shuchkin\SimpleXLSX;

class XSLMParser{

    var $file_to_parse;
    var $parsed_data;
    var $processed_data = array();

    function __construct($file_to_parse){
        $this->file_to_parse = $file_to_parse;
        
        if ( $xlsx = SimpleXLSX::parseData( file_get_contents($this->file_to_parse)) ) {
            $this->parsed_data = $xlsx->rows();
        } else {
            $this->parsed_data = false;
        }
    }

    function process_single_data($etf_name){
        if($this->parsed_data === false) return false;

        $pattern = '/'.$etf_name.'/U'; 
        
        for ($i=51; $i < count($this->parsed_data); $i++) { 
            preg_match($pattern, $this->parsed_data[$i][2], $matches); 
            if($matches || count($matches) > 0){
                $dis_rate_share_data = $this->parsed_data[$i][10];
                $rec_date_data = explode(' ', $this->parsed_data[42][3])[0];
                $ex_date_data = explode(' ', $this->parsed_data[43][3])[0];
                $pay_date_data = explode(' ', $this->parsed_data[45][3])[0];
        
                return array(
                    'ex_date' => $ex_date_data,
                    'rec_date' => $rec_date_data,
                    'pay_date' => $pay_date_data,
                    'dis_rate_share' => $dis_rate_share_data,
                );
            }
        }
        return false;
    }

    function process_all_data(){
        $data = array();
        if($this->parsed_data === false) return $data;
        
        $rec_date_data = explode(' ', $this->parsed_data[42][3])[0];
        $ex_date_data = explode(' ', $this->parsed_data[43][3])[0];
        $pay_date_data = explode(' ', $this->parsed_data[45][3])[0];
        
        $query = new \WP_Query(array( 'post_type' => 'etfs' , 'posts_per_page' => 9999999 ));
        while ($query->have_posts()) {
            $query->the_post();
            $etf_title = get_the_title();
            $pattern = '/'.$etf_title.'/U';
            $dis_rate_share_data = '';

            for ($i=51; $i < count($this->parsed_data); $i++) { 
                preg_match($pattern, $this->parsed_data[$i][2], $matches); 
                if(!$matches || count($matches) == 0) continue;

                $dis_rate_share_data = $this->parsed_data[$i][10];
            }

            $data[] = array(
                $etf_title => array(
                    'ex_date' => $ex_date_data,
                    'rec_date' => $rec_date_data,
                    'pay_date' => $pay_date_data,
                    'dis_rate_share' => $dis_rate_share_data,
                )
            );
        }
        return $data;
    }
}