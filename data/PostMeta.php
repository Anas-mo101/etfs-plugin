<?php

class PostMeta{

    var $post_id;
    var $incoming_meta = array();
    var $file_name;
    var $files_map;

    var $selected_etfs = null;

    function __construct($incoming,$file,$files_map = null){
        $this->incoming_meta = $incoming;
        $this->file_name = $file;
        $this->files_map = $files_map;
    }

    function set_selected($etf_name){
        $this->selected_etfs = $etf_name;
    }

    function process_incoming(){
        $process = $this->files_map !== null ? array_search($this->file_name,$this->files_map,true) : $this->file_name;
        switch ($process) {
            case 'Holding':
                return $this->process_holdings();
            case 'Nav':
                return $this->process_daily_nav();
            case 'Ror':
                return $this->process_ror();
            case 'Dist':
                return $this->process_dist();
            default: return 'PostMeta: file not supported';
        }
    }

    // =============================   NAV  =========================================

    private function save_nav_single($selected_meta){
        $nav_meta_keys = array(
            'ETF-Pre-na-v-data' => 'NAV',
            'ETF-Pre-current-etf-return-data' => 'NAV',
            'ETF-Pre-net-assets-data' => 'Net Assets',
            'ETF-Pre-shares-out-standig-data' => 'Shares Outstanding',
            'ETF-Pre-discount-percentage-data' => 'Premium/Discount Percentage',
            'ETF-Pre-closing-price-data' => 'Market Price',
            'ETF-Pre-thirty-day-median-data' => 'Median 30 Day Spread Percentage',
        );

        $post_to_update = get_page_by_title( $selected_meta['Fund Ticker'], OBJECT, 'etfs' );
        if(! $post_to_update) return;

        $previous_graph_data = get_post_meta( $post_to_update->ID, "ETF-Pre-graph-json-data", true );
        $previous_graph_data_arr = $previous_graph_data !== '' ? json_decode($previous_graph_data, true) : array();
        $previous_graph_data_arr = is_array($previous_graph_data_arr) ? $previous_graph_data_arr : array();

        $current_time_in_millisecond = microtime(true);
        $current_time_in_microsecond = floor($current_time_in_millisecond * 1000);
        $now_date = date("Y-m-d",$current_time_in_millisecond);

        $previous_graph_data_arr_latest_timestamp = end($previous_graph_data_arr);
        $previous_graph_data_arr_latest_timestamp = gettype($previous_graph_data_arr_latest_timestamp) === 'array' ? $previous_graph_data_arr_latest_timestamp : false;
        $previous_graph_data_arr_latest_timestamp = $previous_graph_data_arr_latest_timestamp !== false ? $previous_graph_data_arr_latest_timestamp[0] : false;
        $pre_date = date("Y-m-d",$previous_graph_data_arr_latest_timestamp/1000);

        if($pre_date !== $now_date){
            $new_graph_nav = array($current_time_in_microsecond, floatval($selected_meta['NAV']));
            $previous_graph_data_arr[] = $new_graph_nav;
            $updated_graph_data = json_encode($previous_graph_data_arr);
            update_post_meta($post_to_update->ID,'ETF-Pre-graph-json-data',$updated_graph_data);
            update_post_meta($post_to_update->ID,'ETF-Pre-graph-json-date-data',date("m/d/y"));
        }

        update_post_meta($post_to_update->ID,'ETF-Pre-rate-date-data',date("m/d/y"));
        update_post_meta($post_to_update->ID,'ETF-Pre-fund-pricing-date-data',date("m/d/y"));
        foreach ($nav_meta_keys as $key => $value) {
            if(isset($selected_meta[$value])){
                update_post_meta($post_to_update->ID,$key,$selected_meta[$value]);
            }
        }
    }

    private function process_daily_nav(){ 

        if(!$this->incoming_meta || count($this->incoming_meta) === 0) return false;
        
        if($this->selected_etfs !== null && $this->files_map === null){

            foreach ($this->incoming_meta as $meta) {
                if($meta['Fund Ticker'] == $this->selected_etfs){
                    $this->save_nav_single($meta);
                    return true;
                }
            }

        }else{
            foreach ($this->incoming_meta as $meta) {
                $this->save_nav_single($meta);
            }
            return true;
        }
        return false;
    }

    // ========================================= Holding =====================================================

    private function process_holdings(){
        if(!$this->incoming_meta || count($this->incoming_meta) === 0){
            return false;
        }

        $market_value = array_column($this->incoming_meta, 'MarketValue');
        array_multisort($market_value, SORT_DESC, SORT_NUMERIC, $this->incoming_meta);

        $holding_ = array();
        for ($i=0; $i < 10; $i++) { 
            $holding_[] = $this->incoming_meta[$i];
        }

        $new_holdings = json_encode($holding_);

        if($this->selected_etfs !== null && $this->files_map === null){
            $post_to_update = get_page_by_title( $this->selected_etfs , OBJECT, 'etfs' );
            if(! $post_to_update) return false;

            update_post_meta($post_to_update->ID,'ETF-Pre-top-holding-update-date-data',date("m/d/y"));
            update_post_meta($post_to_update->ID,'ETF-Pre-top-holders-data',$new_holdings);

            return true;
        }else{
            $query = new WP_Query(array( 'post_type' => 'etfs', 'posts_per_page' => 999999 ));
            while ($query->have_posts()) {
                $query->the_post();
                $post_id_to_update = get_the_ID();
    
                update_post_meta($post_id_to_update,'ETF-Pre-top-holding-update-date-data',date("m/d/y"));
                update_post_meta($post_id_to_update,'ETF-Pre-top-holders-data',$new_holdings);
            }
            wp_reset_query();
            return true;
        }
        return false;
    }

    // ============================== ROR ======================================= // 

    private function save_ror_single($etf_name,$meta){
        $post_to_update = get_page_by_title( $etf_name, OBJECT, 'etfs' );
        if(! $post_to_update) return;

        update_post_meta($post_to_update->ID,'ETF-Pre-rate-date-fund-details-data', date("m/d/y"));
        update_post_meta($post_to_update->ID,'ETF-Pre-sec-yeild-data', $meta['sec_yeild']);
        // update_post_meta($post_to_update->ID,'ETF-Pre-ytd-sp-return-data', $meta['ytd_sp_return']);

        update_post_meta($post_to_update->ID,'ETF-Pre-pref-date-data', date("m/d/y"));

        update_post_meta($post_to_update->ID,'ETF-Pre-perf-nav-inception-data', $meta['fund_nav']['inception']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-nav-year-data', $meta['fund_nav']['one_year']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-nav-six-data', $meta['fund_nav']['six_months']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-nav-three-data', $meta['fund_nav']['three_months']);

        update_post_meta($post_to_update->ID,'ETF-Pre-perf-market-inception-data', $meta['market_price']['inception']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-market-year-data', $meta['market_price']['one_year']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-market-six-data', $meta['market_price']['six_months']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-market-three-data', $meta['market_price']['three_months']);

        update_post_meta($post_to_update->ID,'ETF-Pre-perf-sp-inception-data', $meta['sp']['inception']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-sp-year-data', $meta['sp']['one_year']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-sp-six-data', $meta['sp']['six_months']);
        update_post_meta($post_to_update->ID,'ETF-Pre-perf-sp-three-data', $meta['sp']['three_months']);
    }

    private function find_ror_record($ref){

        $etf_name = (new Pdf2Data())->get_etfs_full_pre($this->selected_etfs); // get etf fund name
        error_log($etf_name);
        if(! $etf_name) return false; 

        $pattern = '/'.$etf_name.'/U'; // use fund name as reference to search data array
        foreach ($this->incoming_meta as $key => $value) { // loop through input array data

            preg_match($pattern, $value['Fund Name'], $matches); // look for match
            error_log(print_r($matches,true));

            if($matches || count($matches) > 0){
                $nav_arr = $this->incoming_meta[$key];
                $mkt_arr = $this->incoming_meta[$key+1];
                $sp_arr = $this->incoming_meta[$key+2];

                $data_array_market = array('three_months' => $mkt_arr['3 Month'], 'six_months' => $mkt_arr['6 Month'], 'one_year' => $mkt_arr['1 Year'], 'inception' => $mkt_arr['Since Inception Cumulative']);
                $data_array_nav = array('three_months' => $nav_arr['3 Month'], 'six_months' => $nav_arr['6 Month'], 'one_year' => $nav_arr['1 Year'], 'inception' => $nav_arr['Since Inception Cumulative']);
                $data_array_sp = array('three_months' => $sp_arr['3 Month'], 'six_months' => $sp_arr['6 Month'], 'one_year' => $sp_arr['1 Year'], 'inception' => $sp_arr['Since Inception Cumulative']);
                $data_array = array('sec_yeild' => '-', 'market_price' =>  $data_array_market, 'fund_nav' => $data_array_nav, 'sp' => $data_array_sp);
                
                // testing V
                error_log('market ====>');
                error_log('3 months: ' . $data_array_market['three_months']);
                error_log('6 months: ' . $data_array_market['six_months']);
                error_log('1 year: ' . $data_array_market['one_year']);
                error_log('incepention: ' . $data_array_market['inception']);
                error_log('nav ====>');
                error_log('3 months: ' . $data_array_nav['three_months']);
                error_log('6 months: ' . $data_array_nav['six_months']);
                error_log('1 year: ' . $data_array_nav['one_year']);
                error_log('incepention: ' . $data_array_nav['inception']);
                error_log('sp ====>');
                error_log('3 months: ' . $data_array_sp['three_months']);
                error_log('6 months: ' . $data_array_sp['six_months']);
                error_log('1 year: ' . $data_array_sp['one_year']);
                error_log('incepention: ' . $data_array_sp['inception']);
                // to be removed ^ 

                return $data_array;
            } 
        }
        return false;
    }


    private function process_ror(){
        if(!$this->incoming_meta || count($this->incoming_meta) === 0){
            return false;
        }

        if($this->selected_etfs !== null && $this->files_map === null){
            $data = $this->find_ror_record($this->selected_etfs);
            if($data === false) return false;

            $this->save_ror_single($this->selected_etfs,$data);
            return true;
        }else{
            $query = new WP_Query(array( 'post_type' => 'etfs' , 'posts_per_page' => 9999999 ));
            // loop through etfs 
            while ($query->have_posts()) {
                $query->the_post();
                $etf_title = get_the_title(); // get etf ticker name

                error_log('look for record');

                $data = $this->find_ror_record($etf_title);
                if($data === false) continue; // if etf not found in record look for next etf

                error_log('found record and save');

                $this->save_ror_single($etf_title,$data);
            }
            return true;
        }
    }

    // ===========================  Dist  ===========================================

    private function save_dist_single($meta,$etf_name){
        $post_to_update = get_page_by_title( $etf_name, OBJECT, 'etfs' );
        if(! $post_to_update) return;
        update_post_meta($post_to_update->ID,'ETF-Pre-ex-date-data', $meta['ex_date']);
        update_post_meta($post_to_update->ID,'ETF-Pre-rec-date-data', $meta['rec_date']);
        update_post_meta($post_to_update->ID,'ETF-Pre-pay-date-data', $meta['pay_date']);
        update_post_meta($post_to_update->ID,'ETF-Pre-dis-rate-share-data', $meta['dis_rate_share']);
    }

    private function process_dist(){
        if(!$this->incoming_meta || count($this->incoming_meta) === 0){
            return false;
        }

        if($this->selected_etfs !== null && $this->files_map === null){
            $this->save_dist_single($this->incoming_meta,$this->selected_etfs);
            return true;
        }else{
            foreach ($this->incoming_meta as $meta) {
                $this->save_dist_single($meta);
            }
            return true;
        }
        return false;
    } 
}

