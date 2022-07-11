<?php

class Calculations{

    var $starting_nav = null;
    var $total_buffer = null;
    var $sp_year_start = null;
    var $sp_ref_value = null;

    function __construct($id){
        if(get_post_meta( $id, "ETF-Pre-starting-nav-data", true ) !== ''){
            $this->starting_nav = floatval(get_post_meta( $id, "ETF-Pre-starting-nav-data", true ));
        }

        if(get_post_meta( $id, "ETF-Pre-total-buffer-data", true ) !== ''){
            $this->total_buffer = floatval(get_post_meta( $id, "ETF-Pre-total-buffer-data", true ));
        }

        if(get_post_meta( $id, "ETF-Pre-sp-start-data", true ) !== ''){
            $this->sp_year_start= floatval(get_post_meta( $id, "ETF-Pre-sp-start-data", true ));
        }

        if(get_post_meta( $id, "ETF-Pre-sp-ref-data", true ) !== ''){
            $this->sp_ref_value = floatval(get_post_meta( $id, "ETF-Pre-sp-ref-data", true ));
        }

        $this->get_period_return($id);
        $this->get_remaining_buffer($id);
        $this->get_downside_buffer($id);
        $this->get_current_sp($id);
        $this->get_spx_period_return($id);
    }

    function get_period_return($id){ // PERIOD_RETURN => ($ETF_CURRENT_NAV/$ETF_STARTING_NAV)-1
        if ($this->starting_nav === null || get_post_meta( $id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $id, "ETF-Pre-na-v-data", true ));

        $ans = ($current_nav/$this->starting_nav);
        $ans = $ans - 1;
        return $ans;
    }

    function get_remaining_buffer($id){ //Remaining Buffer (conditional)
        if ($this->starting_nav === null 
            || $this->total_buffer === null 
                || get_post_meta( $id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $id, "ETF-Pre-na-v-data", true ));

        if($sp_period_return >= 0){
            return (-1) * $this->total_buffer;
        }elseif ($sp_period_return < 0 && $sp_period_return >= $this->total_buffer) {
            $temp_ = $sp_period_return - $this->total_buffer;
            $temp_ = $this->get_period_return($id) - $temp_;
            $_temp_ = ($current_nav/$this->starting_nav);
            return (-1) * $temp_ * $_temp_;
        }elseif ($sp_period_return < $this->total_buffer) {
            $temp_ = $sp_period_return - $this->total_buffer;
            $temp_neu = $this->get_period_return($id) - $temp_;
            $temp_ = 1 - $this->total_buffer;
            $_temp_ = ($current_nav/$this->starting_nav);
            $temp_deno = $temp_ * $_temp_;
            return (-1) * ($temp_neu/$temp_deno);
        }
    }
    
    function get_downside_buffer($id){ //ETF Downside to Buffer
        if ($this->starting_nav === null || get_post_meta( $id, "ETF-Pre-na-v-data", true ) == '') return;

        $current_nav = floatval(get_post_meta( $id, "ETF-Pre-na-v-data", true ));

        if($sp_period_return >= 0){
            $temp_ = ($this->starting_nav - $current_nav);
            return $temp_ / $current_nav;
        }else{
            return 'N/A';
        }
    }

    function get_current_sp($id){ // CURRENT_SP_LEVEL = S_P_YEAR_START_VALUE x (1+ YTD_SP_RETURN)
        if ($this->sp_year_start === null || get_post_meta( $id, "?", true ) == '') return;
        $ytd_sp_return = floatval(get_post_meta( $id, "?", true ));
        $temp_ = 1 + $ytd_sp_return;
        return $this->sp_year_start * $temp_;
    }

    function get_spx_period_return($id,$ytd_sp_return){ // SPX_PERIOD_RETURN = (CURRENT_SP_LEVEL/S_P_REFERENCE_VALUE) -1
        if ($this->sp_ref_value === null || get_post_meta( $id, "?", true ) == '') return;
        $ytd_sp_return = floatval(get_post_meta( $id, "", true ));

        $temp_ = $this->get_current_sp($id);
        $_temp_ =  $temp_ / $this->sp_ref_value;
        return $_temp_ - 1;
    }
}

