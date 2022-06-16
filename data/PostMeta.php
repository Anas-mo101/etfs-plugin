<?php

//update_post_meta( post-id, meta-key , new-meta );


// should be feed the array result of fetch_etf_data()
// and be able to update meta according to tha array
class PostMeta{

    var $post_id;
    var $incoming_meta = array();
    var $file_name;
    var $files_map;

    function __construct($incoming,$file,$files_map){
        $this->incoming_meta = $incoming;
        $this->file_name = $file;
        $this->files_map = $files_map;
    }

    function process_incoming(){

        $process = array_search($this->file_name,$this->files_map,true);

        switch ($process) {
            case 'holding':
                return $this->process_holdings();
            case 'nav':
                return $this->process_daily_nav();
            case 'ror':
                return $this->process_ror();
            case 'dist':
                return $this->process_dist();
            default: return 'PostMeta: file not supported';
        }
    }

    private function process_daily_nav(){
        $nav_meta_keys = array(
            'ETF-Pre-na-v-data' => 'NAV',
            'ETF-Pre-net-assets-data' => 'Net Assets',
            'ETF-Pre-shares-out-standig-data' => 'Shares Outstanding',
            'ETF-Pre-discount-percentage-data' => 'Premium/Discount',
            'ETF-Pre-closing-price-data' => 'Rate Date',
            'ETF-Pre-thirty-day-median-data' => 'Median 30 Day Spread Percentage',
        );

        if(!$this->incoming_meta || count($this->incoming_meta) === 0){
            return 'null data';
        }

        foreach ($this->incoming_meta as $meta) {
            $post_to_update = get_page_by_title( $meta['Fund Ticker'], OBJECT, 'etfs' );
            if(! $post_to_update) continue;

            update_post_meta($post_to_update->id,'ETF-Pre-fund-pricing-date-data',date("d/m/y"));

            foreach ($nav_meta_keys as $key => $value) {
                if(isset($meta[$value])){
                    update_post_meta($post_to_update->id,$key,$meta[$value]);
                }
            }
        }
        return 'success';
    }

    private function process_holdings(){

        if(!$this->incoming_meta || count($this->incoming_meta) === 0){
            return 'null data';
        }

        $query = new WP_Query(array( 'post_type' => 'etfs', 'post_status' => 'publish' ));
        while ($query->have_posts()) {
            $query->the_post();
            $post_id_to_update = get_the_ID();

            if (!$this->incoming_meta || count($this->incoming_meta) === 0) {
                return 'null data';
            }

            $holding_ = array();
            for ($i=0; $i < 4; $i++) { 
                $holding_[] = $this->incoming_meta[$i]; // sort
            }

            $new_holdings = json_encode($holding_);

            update_post_meta($post_id_to_update,'ETF-Pre-top-holding-update-date-data',date("d/m/y"));
            update_post_meta($post_id_to_update,'ETF-Pre-top-holders-data',$new_holdings);
        }
        wp_reset_query();
        return 'success';
    }

    private function process_ror(){
        if(!$this->incoming_meta || count($this->incoming_meta) === 0){
            return 'null data';
        }



    }

    private function process_dist(){
        if(!$this->incoming_meta || count($this->incoming_meta) === 0){
            return 'null data';
        }

        

    }
}