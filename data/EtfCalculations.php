<?php

class EtfCalculations{

    function get_period_return($id,$current_nav){ // PERIOD_RETURN => ($ETF_CURRENT_NAV/$ETF_STARTING_NAV)-1
        $ans = ($current_nav/$starting_nav);
        $ans = $ans - 1;
        return $ans;
    }

    function get_remaining_buffer($id,$current_nav){ //Remaining Buffer (conditional)
        
        $starting_nav = floatval(get_post_meta( $id, "ETF-Pre-graph-json-data", true ));
        $total_buffer = floatval(get_post_meta( $id, "ETF-Pre-graph-json-data", true ));

        if($sp_period_return >= 0){
            return (-1) * $total_buffer;
        }elseif ($sp_period_return < 0 && $sp_period_return >= $total_buffer) {
            $temp_ = $sp_period_return - $total_buffer;
            $temp_ = $this->get_period_return($id,$current_nav) - $temp_;
            $_temp_ = ($current_nav/$starting_nav);
            return (-1) * $temp_ * $_temp_;
        }elseif ($sp_period_return < $total_buffer) {
            $temp_ = $sp_period_return - $total_buffer;
            $temp_neu = $this->get_period_return($id,$current_nav) - $temp_;
            $temp_ = 1 - $total_buffer;
            $_temp_ = ($current_nav/$starting_nav);
            $temp_deno = $temp_ * $_temp_;
            return (-1) * ($temp_neu/$temp_deno);
        }
    }
    
    function get_downside_buffer($id,$current_nav){ //ETF Downside to Buffer
        $starting_nav = floatval(get_post_meta( $id, "ETF-Pre-graph-json-data", true ));
        $total_buffer = floatval(get_post_meta( $id, "ETF-Pre-graph-json-data", true ));

        if($sp_period_return >= 0){
            $temp_ = ($starting_nav - $current_nav);
            return $temp_ / $current_nav;
        }else{
            return 'N/A';
        }
    }

    function get_current_sp($id,$ytd_sp_return){ // CURRENT_SP_LEVEL = S_P_YEAR_START_VALUE x (1+ YTD_SP_RETURN)
        $sp_year_start = floatval(get_post_meta( $id, "ETF-Pre-S_P_YEAR_START_VALUE", true ));
        $temp_ = 1 + $ytd_sp_return;
        return $sp_year_start * $temp_;
    }

    function get_spx_period_return($id,$ytd_sp_return){ // SPX_PERIOD_RETURN = (CURRENT_SP_LEVEL/S_P_REFERENCE_VALUE) -1
        $sp_ref_value = floatval(get_post_meta( $id, "S_P_REFERENCE_VALUE", true ));
        $temp_ = $this->get_current_sp($id,$ytd_sp_return);
        $_temp_ =  $temp_ / $sp_ref_value;
        return $_temp_ - 1;
    }

}

