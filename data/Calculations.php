<?php

class Calculations{

    var $starting_nav = null;
    var $total_buffer = null;
    var $sp_year_start = null;
    var $sp_ref_value = null;
    var $id;

    function __construct(){ }

    function init($id){
        $this->id = $id;
        if(get_post_meta( $this->id, "ETF-Pre-starting-nav-data", true ) !== ''){
            $this->starting_nav = floatval(get_post_meta( $this->id, "ETF-Pre-starting-nav-data", true ));
        }

        if(get_post_meta( $this->id, "ETF-Pre-total-buffer-data", true ) !== ''){
            $this->total_buffer = floatval(get_post_meta( $this->id, "ETF-Pre-total-buffer-data", true ));
        }

        if(get_post_meta( $this->id, "ETF-Pre-sp-start-data", true ) !== ''){
            $this->sp_year_start= floatval(get_post_meta( $this->id, "ETF-Pre-sp-start-data", true ));
        }

        if(get_post_meta( $this->id, "ETF-Pre-sp-ref-data", true ) !== ''){
            $this->sp_ref_value = floatval(get_post_meta( $this->id, "ETF-Pre-sp-ref-data", true ));
        }

        $this->get_period_return(true);
        $this->get_remaining_buffer();
        $this->get_downside_buffer();
        $this->get_spx_period_return();
        $this->get_remaining_outcome_period(true);

        update_post_meta($id,'ETF-Pre-current-outcome-period-date-data',date("m/d/y"));
        update_post_meta($id,'ETF-Pre-outcome-period-date-data',date("m/d/y"));
    }

    function calc_all(){
        $etfs = get_posts( array('post_type' => 'etfs', 'numberposts' => 999999 ) );
        foreach ($etfs as $etf) {
            $this->init($etf->ID);
        }
    }

    function get_period_return($flag){ // PERIOD_RETURN => ($ETF_CURRENT_NAV/$ETF_STARTING_NAV)-1
        if ($this->starting_nav === null || get_post_meta( $this->id, "ETF-Pre-na-v-data", true ) == '') return;
        $current_nav = floatval(get_post_meta( $this->id, "ETF-Pre-na-v-data", true ));

        $ans = ($current_nav/$this->starting_nav);
        $ans = $ans - 1;
        $ans = round($ans, 2);
        if($flag){
            update_post_meta($this->id,'ETF-Pre-etf-period-return-data', $ans . '%'); 
            update_post_meta($this->id,'ETF-Pre-current-period-return-data', $ans . '%'); 
        }else{
            return $ans; 
        }
    }

    function get_remaining_buffer(){ //Remaining Buffer (conditional)
        if ($this->starting_nav === null 
            || $this->total_buffer === null 
                || get_post_meta( $this->id, "?", true ) == ''
                    || get_post_meta( $this->id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $this->id, "ETF-Pre-na-v-data", true ));
        $sp_period_return = floatval(get_post_meta( $this->id, "", true ));

        if($sp_period_return >= 0){
            $ans = (-1) * $this->total_buffer;
            update_post_meta($this->id,'ETF-Pre-current-remaining-buffer-data', $ans);
        }elseif ($sp_period_return < 0 && $sp_period_return >= $this->total_buffer) {
            $temp_ = $sp_period_return - $this->total_buffer;
            $temp_ = $this->get_period_return(false) - $temp_;
            $_temp_ = ($current_nav/$this->starting_nav);
            $ans = (-1) * $temp_ * $_temp_;
            update_post_meta($this->id,'ETF-Pre-current-remaining-buffer-data', $ans);
        }elseif ($sp_period_return < $this->total_buffer) {
            $temp_ = $sp_period_return - $this->total_buffer;
            $temp_neu = $this->get_period_return(false) - $temp_;
            $temp_ = 1 - $this->total_buffer;
            $_temp_ = ($current_nav/$this->starting_nav);
            $temp_deno = $temp_ * $_temp_;
            $ans = (-1) * ($temp_neu/$temp_deno);
            update_post_meta($this->id,'ETF-Pre-current-remaining-buffer-data', $ans);
        }
    }
    
    function get_downside_buffer(){ //ETF Downside to Buffer
        if ($this->starting_nav === null 
            || get_post_meta( $this->id, "?", true ) == ''
                || get_post_meta( $this->id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $this->id, "ETF-Pre-na-v-data", true ));
        $sp_period_return = floatval(get_post_meta( $this->id, "", true ));

        if($sp_period_return >= 0){
            $temp_ = ($this->starting_nav - $current_nav);
            $ans = $temp_ / $current_nav;
            update_post_meta($this->id,'ETF-Pre-current-downside-buffer-data', $ans);
        }else{
            update_post_meta($this->id,'ETF-Pre-current-downside-buffer-data', 'N/A');
        }
    }

    function get_current_sp($flag){ // CURRENT_SP_LEVEL = S_P_YEAR_START_VALUE x (1+ YTD_SP_RETURN)
        if ($this->sp_year_start === null 
            || get_post_meta( $this->id, "ETF-Pre-ytd-sp-return-data", true ) == '') return;
        $ytd_sp_return = floatval(get_post_meta( $this->id, "ETF-Pre-ytd-sp-return-data", true ));

        $temp_ = 1 + $ytd_sp_return;
        $ans = $this->sp_year_start * $temp_;
        return $ans;
    }

    function get_spx_period_return(){ // SPX_PERIOD_RETURN = (CURRENT_SP_LEVEL/S_P_REFERENCE_VALUE) -1
        if ($this->sp_ref_value === null) return;
        $temp_ = $this->get_current_sp(false);

        $_temp_ =  $temp_ / $this->sp_ref_value;
        $ans = $_temp_ - 1;
        $ans = round($ans,2);
        update_post_meta($this->id,'ETF-Pre-current-spx-return-data', $ans . '%');
    }

    function get_remaining_outcome_period($flag,$title = null){
        $long_name = $title === null ? (new Pdf2Data())->get_etfs_full_pre(get_the_title($this->id)) : (new Pdf2Data())->get_etfs_full_pre($title);
        $now = time();
        $current_year = date("Y");
        $future = strtotime("1 " . $long_name . " " . $current_year);
        $timeleft = $future - $now;
        $daysleft = round((($timeleft/24)/60)/60);
        
        if($daysleft < 0){
            $current_year = $current_year + 1;
            $future = strtotime("1 " . $long_name . " " . $current_year);
            $timeleft = $future - $now;
            $daysleft = round((($timeleft/24)/60)/60);
        }

        if($flag){
            update_post_meta($this->id,'ETF-Pre-current-remaining-outcome-data', $daysleft);  
        }else{
            return $daysleft;
        } 
    }
}

