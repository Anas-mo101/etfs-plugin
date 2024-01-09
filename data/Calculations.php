<?php

class Calculations{

    var $starting_nav = null;
    var $total_buffer = null;
    var $sp_year_start = null;
    var $sp_ref_value = null;
    var $dist_value = null;
    var $id;

    function __construct(){ }

    function init($id){
        date_default_timezone_set("America/New_York");
        
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

        if(get_post_meta( $this->id, "ETF-Pre-distribution-ref-data", true ) !== ''){
            $this->dist_value = floatval(get_post_meta( $this->id, "ETF-Pre-distribution-ref-data", true ));
        }

        $this->get_period_return(true);
        $this->get_spx_period_return(true);
        $this->get_remaining_buffer();
        $this->get_downside_buffer();
        $this->get_remaining_outcome_period(true);
        $this->get_floor_of_buffer();

        update_post_meta($id,'ETF-Pre-current-outcome-period-date-data',date("m/d/Y", strtotime("-1 day")));
    }

    function calc_all(){
        $etfs = get_posts( array('post_type' => 'etfs', 'numberposts' => 999999 ) );
        foreach ($etfs as $etf) {
            $this->init($etf->ID);
        }
    }

    function get_period_return($flag){ // PERIOD_RETURN => ( DISTURBUTION + ( $ETF_CURRENT_NAV / $ETF_STARTING_NAV ) ) - 1
        if ($this->starting_nav === null || $this->dist_value == null || get_post_meta( $this->id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $this->id, "ETF-Pre-na-v-data", true ));

        $ans = $this->dist_value + ($current_nav / $this->starting_nav);
        $ans = $ans - 1;
        if($flag){
            $ans = $ans * 100;
		    $ans = round($ans, 2);
            update_post_meta($this->id,'ETF-Pre-current-period-return-data', $ans . '%'); 
        }else{
            return $ans; 
        }
    }
	
	function get_spx_period_return($flag){ // SPX_PERIOD_RETURN = (CURRENT_SP_LEVEL/S_P_REFERENCE_VALUE) -1
        if ($this->sp_ref_value === null) return;
        $temp_ = $this->get_current_sp();
        $_temp_ =  $temp_ / $this->sp_ref_value;
        $ans = $_temp_ - 1;
        if($flag){
            $ans = $ans * 100;
            $ans = round($ans,2);
            update_post_meta($this->id,'ETF-Pre-current-spx-return-data', $ans . '%');
        }else{
            return $ans;
        }
    }

    function get_remaining_buffer(){ //Remaining Buffer (conditional)
        if ($this->starting_nav === null 
            || $this->total_buffer === null 
                || get_post_meta( $this->id, "ETF-Pre-current-spx-return-data", true ) == ''
                    || get_post_meta( $this->id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $this->id, "ETF-Pre-na-v-data", true ));
        $sp_period_return = $this->get_spx_period_return(false); //floatval(get_post_meta( $this->id, "ETF-Pre-current-spx-return-data", true ));
        

        if($sp_period_return >= 0){
            $ans = (-1) * $this->total_buffer;
            $ans = $ans * 100;
			$ans = round($ans,2);
            update_post_meta($this->id,'ETF-Pre-current-remaining-buffer-data', $ans);
        }elseif ($sp_period_return < 0 && $sp_period_return >= $this->total_buffer) {
            $temp_ = $sp_period_return - $this->total_buffer;
            $temp_ = $this->get_period_return(false) - $temp_;
            $_temp_ = ($this->starting_nav/$current_nav);
            $ans = (-1) * $temp_ * $_temp_;
            $ans = $ans * 100;
			$ans = round($ans,2);
            update_post_meta($this->id,'ETF-Pre-current-remaining-buffer-data', $ans);
        }elseif ($sp_period_return < $this->total_buffer) {
            $temp_ = $sp_period_return - $this->total_buffer;
            $temp_neu = $this->get_period_return(false) - $temp_;
            $temp_ = 1 - $this->total_buffer;
            $_temp_ = ($this->starting_nav/$current_nav);
            $temp_deno = $temp_ * $_temp_;
            $ans = (-1) * ($temp_neu/$temp_deno);
            $ans = $ans * 100;
			$ans = round($ans,2);
            update_post_meta($this->id,'ETF-Pre-current-remaining-buffer-data', $ans);
        }
    }
    
    function get_downside_buffer(){ //ETF Downside to Buffer
        if ($this->starting_nav === null 
            || get_post_meta( $this->id, "ETF-Pre-current-spx-return-data", true ) == ''
                || get_post_meta( $this->id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $this->id, "ETF-Pre-na-v-data", true ));
        $sp_period_return = $this->get_spx_period_return(false);

        if($sp_period_return >= 0){
            $temp_ = ($this->starting_nav - $current_nav);
            $ans = $temp_ / $current_nav;
            $ans = $ans * 100;
			$ans = round($ans,2);
            update_post_meta($this->id,'ETF-Pre-current-downside-buffer-data', $ans . '%');
        }else{
            update_post_meta($this->id,'ETF-Pre-current-downside-buffer-data', 'N/A');
        }
    }

    function get_current_sp(){ // CURRENT_SP_LEVEL = S_P_YEAR_START_VALUE x (1+ YTD_SP_RETURN)
        if ($this->sp_year_start === null 
            || get_post_meta( $this->id, "ETF-Pre-ytd-sp-return-data", true ) == '') return;
            
        $ytd_sp_return = floatval(get_post_meta( $this->id, "ETF-Pre-ytd-sp-return-data", true ));
        $decimal = $ytd_sp_return / 100;

        $temp_ = 1 + $decimal;
        $ans = $this->sp_year_start * $temp_;
        return $ans;
    }

    //$S&P Downside to Floor of Buffer = ($S_P_Reference_Value * (1+$Total_Buffer) - $CURRENT_SP_LEVEL) / $CURRENT_SP_LEVEL
    function get_floor_of_buffer(){
        if ($this->sp_ref_value === null  || $this->total_buffer === null ) return;

        $current_sp = $this->get_current_sp();

        $temp_ = $this->sp_ref_value * (1 + $this->total_buffer);
        $temp = $temp_ - $current_sp;
        $ans = $temp / $current_sp;
        if ($ans < 0) {
            $ans = $ans * 100;
            $ans = round($ans, 2);  
            $ans = $ans . '%';
        }else{
            $ans = 'N/A';
        }
        
        update_post_meta($this->id,'ETF-Pre-floor-of-buffer-data', $ans);  
    }
    
    function get_remaining_outcome_period($flag,$title = null){
        if (get_post_meta( $this->id, "ETF-Pre-period-end-date-data", true ) == '') return;

        $period_start_date = explode("-", get_post_meta( $this->id, "ETF-Pre-period-end-date-data", true ));

        if(!isset($period_start_date[2]) || !isset($period_start_date[1]) || !isset($period_start_date[0])) return;

        $period_start_day = $period_start_date[2];
        $period_start_month = (int) $period_start_date[1];
        $period_start_year = $period_start_date[0];

        $period_start_month =  $period_start_month === 0 ? 1 : $period_start_month;
        $monthName = date('F', mktime(0, 0, 0, $period_start_month, 10));

        $now = time();
        $current_year = date("Y");
        $future = strtotime($period_start_day . " " . $monthName . " " . $period_start_year);
        $timeleft = $future - $now;
        $daysleft = round((($timeleft/24)/60)/60);
        
        if($daysleft <= 0){
            $long_name = $title === null ? (new Pdf2Data())->get_etfs_full_pre(get_the_title($this->id)) : (new Pdf2Data())->get_etfs_full_pre($title);
            $period_start_year = (int)$period_start_year + 1;
            $updatedDate = $period_start_year . "-" . $long_name . "-1";
            update_post_meta($this->id,'ETF-Pre-period-end-date-data', $updatedDate);

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

