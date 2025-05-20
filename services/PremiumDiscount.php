<?php

class PremiumDiscount {
    var $prefix = 'ETF-Pre-';

    var $premium_cyq1_meta_key = 'premium-q1-cy';
    var $premium_cyq2_meta_key = 'premium-q2-cy';
    var $premium_cyq3_meta_key = 'premium-q3-cy';
    var $premium_cyq4_meta_key = 'premium-q4-cy';
    var $premium_py_meta_key = 'premium-py';
    
    var $net_cyq1_meta_key = 'net-q1-cy';
    var $net_cyq2_meta_key = 'net-q2-cy';
    var $net_cyq3_meta_key = 'net-q3-cy';
    var $net_cyq4_meta_key = 'net-q4-cy';
    var $net_py_meta_key = 'net-py';

    var $discount_cyq1_meta_key = 'discount-q1-cy';
    var $discount_cyq2_meta_key = 'discount-q2-cy';
    var $discount_cyq3_meta_key = 'discount-q3-cy';
    var $discount_cyq4_meta_key = 'discount-q4-cy';
    var $discount_py_meta_key = 'discount-py';

    var $date_meta_key = 'premium-section-date-data';

    private $table_name = "etfs_premium_historical";

    function __construct() {}

    function init() {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $wp_table_name (
            ID INT UNIQUE AUTO_INCREMENT,
            FundID INT NOT NULL,
            Value varchar(255) NOT NULL DEFAULT '0',
            Timestamp DATETIME
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


    function create_entry($value, $id, $in_date = null){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;

        $date = date('Y-m-d H:i:s');
        if ($in_date !== null) {
            $date = $in_date;
        }

        $wpdb->insert(
            $wp_table_name,
            [ 
                'Value' => $value,
                'FundID' => $id,
                'Timestamp' => $date
            ]
        );
    }

    function update_entry($id, $value){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;

        $data = ['Value' => $value];
        $where = ['ID' => $id];

        $wpdb->update($wp_table_name, $data, $where);
    }

    function list_db_entries($id){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $entries = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $wp_table_name WHERE FundID = %d ORDER BY Timestamp ASC",
                $id
            ),
            ARRAY_A
        );

        return $entries;
    }

    function entry_exists($id, $timestamp): int|false {
        global $wpdb;

        $wp_table_name = $wpdb->prefix . $this->table_name;
        $entries = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $wp_table_name WHERE DATE(Timestamp) = DATE(%s) AND FundID = %d",
                $timestamp, $id
            ),
            ARRAY_A
        );
        
        return count($entries) > 0 ? $entries[0]["ID"] : false;
    }

    function format_entries(array $entries){
        $formated = array_map(function ($entry) {
            $unix = strtotime($entry["Timestamp"]) * 1000;
            return [
                $unix,
                (float) $entry["Value"]
            ];
        }, $entries);

        return $formated;
    }

    function list_entries($id) {
        $entires = $this->list_db_entries($id);
        $formated_entries = $this->format_entries($entires);

        return $formated_entries;
    }

    function update_table($id, float $premium_discount){
        date_default_timezone_set("America/Chicago");
        $timestamp = date("Y-m-d h:i:s A");
        $timestamp2 = date("m/d/Y");

        $exist = $this->entry_exists($id, $timestamp);
        if ($exist) {
            return false;
        }

        $this->create_entry($premium_discount, $id);

        $dateObj = DateTime::createFromFormat('m/d/Y', $timestamp2);
        $month = (int) $dateObj->format('m'); // Extract month as integer

        // Determine quarter
        $quarter = match (true) {
            $month >= 1 && $month <= 3  => 'Q1', // January - March
            $month >= 4 && $month <= 6  => 'Q2', // April - June
            $month >= 7 && $month <= 9  => 'Q3', // July - September
            $month >= 10 && $month <= 12 => 'Q4', // October - December
            default => 'Unknown'
        };

        $this->process_current_year_pd($id, $premium_discount, $quarter);

        update_post_meta($id, get_prefix() . $this->date_meta_key, $timestamp2);
    }

    function proccess_multiple_historical($entries, $updateExisting = false){
        $fails = [];
        for ($i=0; $i < count($entries); $i++) { 
            try {
                $entry = $entries[$i];

                if (
                    !isset($entry["ticker"]) ||
                    !isset($entry["premium_discount_perc"]) ||
                    !isset($entry["as_of_date"])
                ) {
                    $fails[] = [$i => "undefined"];
                    continue;
                }
            
                $status = $this->process_single_historical(
                    $entry["ticker"],
                    $entry["premium_discount_perc"],
                    $entry["as_of_date"],
                    $updateExisting
                );

                if(!$status){
                    $fails[] = [$i => $entry["ticker"] ?? "undefined"];
                }
            } catch (\Throwable $th) {
                $fails[] = [$i => $entry["ticker"] ?? "undefined"];
            }
        }

        return $fails;
    }

    function sync_count(){
        $query = new WP_Query(array('post_type' => 'etfs', 'posts_per_page' => 9999999));
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $entires = $this->list_db_entries($id);

            update_post_meta($id, get_prefix() . $this->premium_cyq1_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->premium_cyq2_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->premium_cyq3_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->premium_cyq4_meta_key, "-");
            
            update_post_meta($id, get_prefix() . $this->net_cyq1_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->net_cyq2_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->net_cyq3_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->net_cyq4_meta_key, "-");

            update_post_meta($id, get_prefix() . $this->discount_cyq1_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->discount_cyq2_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->discount_cyq3_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->discount_cyq4_meta_key, "-");

            update_post_meta($id, get_prefix() . $this->premium_py_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->net_py_meta_key, "-");
            update_post_meta($id, get_prefix() . $this->discount_py_meta_key, "-");

            for ($i=0; $i < count($entires); $i++) { 
                $entry = $entires[$i];

                $premium_discount = (float) $entry["Value"];
                $date = $entry["Timestamp"];

                $timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                $dateYear = $timestamp->format('Y');

                $currentYear = date('Y'); 
                $isCurrentYear = $dateYear == $currentYear ? true : false;

                if( $isCurrentYear ){
                    $month = (int) $timestamp->format('m'); // Extract month as integer
                    $quarter = match (true) {
                        $month >= 1 && $month <= 3  => 'Q1', // January - March
                        $month >= 4 && $month <= 6  => 'Q2', // April - June
                        $month >= 7 && $month <= 9  => 'Q3', // July - September
                        $month >= 10 && $month <= 12 => 'Q4', // October - December
                        default => 'Unknown'
                    };

                    $this->process_current_year_pd($id, $premium_discount, $quarter);
                } else {

                    $difference = (int) $currentYear - (int) $dateYear;

                    if ($difference !== 1) {
                        continue;
                    }

                    $current_premium_py = (int) get_post_meta($id, get_prefix() . $this->premium_py_meta_key, true) ?? 0;
                    if($premium_discount < 0){
                        update_post_meta($id, get_prefix() . $this->premium_py_meta_key, $current_premium_py + 1);
                    }
            
                    $current_net_py = (int) get_post_meta($id, get_prefix() . $this->net_py_meta_key, true) ?? 0;
                    if($premium_discount == 0){
                        update_post_meta($id, get_prefix() . $this->net_py_meta_key, $current_net_py + 1);
                    }
            
                    $current_discount_py = (int) get_post_meta($id, get_prefix() . $this->discount_py_meta_key, true) ?? 0;
                    if($premium_discount > 0){
                        update_post_meta($id, get_prefix() . $this->discount_py_meta_key, $current_discount_py + 1);
                    }
                }
            }
        }
    }

    function process_single_historical($fund, $premium_discount, string $date, $updateExisting = false){
        $post_to_update = custom_get_page_by_title($fund, OBJECT, 'etfs');
        if (!$post_to_update){
            return false;
        }

        $id = $post_to_update->ID;

        $timestamp = DateTime::createFromFormat('m/d/Y', $date);
        $formatted_timestamp = $timestamp->format('Y-m-d H:i:s');

        $exist = $this->entry_exists($id, $formatted_timestamp);
        if ($exist) {
            if( $updateExisting ){
                $this->update_entry($exist, $premium_discount);
                return true;
            }

            return false;
        }

        $currentYear = date('Y'); 
        $dateYear = date('Y', strtotime($date));
        $isCurrentYear = $dateYear == $currentYear;

        $this->create_entry($premium_discount, $id, $formatted_timestamp);

        if( $isCurrentYear ){
            $month = (int) $timestamp->format('m'); // Extract month as integer
            $quarter = match (true) {
                $month >= 1 && $month <= 3  => 'Q1', // January - March
                $month >= 4 && $month <= 6  => 'Q2', // April - June
                $month >= 7 && $month <= 9  => 'Q3', // July - September
                $month >= 10 && $month <= 12 => 'Q4', // October - December
                default => 'Unknown'
            };

            $this->process_current_year_pd($id, $premium_discount, $quarter);
            return true;
        }

        $current_premium_py = (int) get_post_meta($id, get_prefix() . $this->premium_py_meta_key, true) ?? 0;
        if($premium_discount < 0){
            update_post_meta($id, get_prefix() . $this->premium_py_meta_key, $current_premium_py + 1);
        }

        $current_net_py = (int) get_post_meta($id, get_prefix() . $this->net_py_meta_key, true) ?? 0;
        if($premium_discount == 0){
            update_post_meta($id, get_prefix() . $this->net_py_meta_key, $current_net_py + 1);
        }

        $current_discount_py = (int) get_post_meta($id, get_prefix() . $this->discount_py_meta_key, true) ?? 0;
        if($premium_discount > 0){
            update_post_meta($id, get_prefix() . $this->discount_py_meta_key, $current_discount_py + 1);
        }

        return true;
    }

    function process_current_year_pd($id, $premium_discount, $quarter){
        if($premium_discount < 0){
            if ($quarter == "Q1") {
                $current_premium_cy = (int) get_post_meta($id, get_prefix() . $this->premium_cyq1_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->premium_cyq1_meta_key, $current_premium_cy + 1);
            } else if ($quarter == "Q2") {
                $current_premium_cy = (int) get_post_meta($id, get_prefix() . $this->premium_cyq2_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->premium_cyq2_meta_key, $current_premium_cy + 1);
            } else if ($quarter == "Q3") {
                $current_premium_cy = (int) get_post_meta($id, get_prefix() . $this->premium_cyq3_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->premium_cyq3_meta_key, $current_premium_cy + 1);
            } else if ($quarter == "Q4") {
                $current_premium_cy = (int) get_post_meta($id, get_prefix() . $this->premium_cyq4_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->premium_cyq4_meta_key, $current_premium_cy + 1);
            }
        }

        if($premium_discount == 0){
            if ($quarter == "Q1") {
                $current_net_cy = (int) get_post_meta($id, get_prefix() . $this->net_cyq1_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->net_cyq1_meta_key, $current_net_cy + 1);
            } else if ($quarter == "Q2") {
                $current_net_cy = (int) get_post_meta($id, get_prefix() . $this->net_cyq2_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->net_cyq2_meta_key, $current_net_cy + 1);
            } else if ($quarter == "Q3") {
                $current_net_cy = (int) get_post_meta($id, get_prefix() . $this->net_cyq3_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->net_cyq3_meta_key, $current_net_cy + 1);
            } else if ($quarter == "Q4") {
                $current_net_cy = (int) get_post_meta($id, get_prefix() . $this->net_cyq4_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->net_cyq4_meta_key, $current_net_cy + 1);
            }
        }

        if($premium_discount > 0){
            if ($quarter == "Q1") {
                $current_discount_cy = (int) get_post_meta($id, get_prefix() . $this->discount_cyq1_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->discount_cyq1_meta_key, $current_discount_cy + 1);
            } else if ($quarter == "Q2") {
                $current_discount_cy = (int) get_post_meta($id, get_prefix() . $this->discount_cyq2_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->discount_cyq2_meta_key, $current_discount_cy + 1);
            } else if ($quarter == "Q3") {
                $current_discount_cy = (int) get_post_meta($id, get_prefix() . $this->discount_cyq3_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->discount_cyq3_meta_key, $current_discount_cy + 1);
            } else if ($quarter == "Q4") {
                $current_discount_cy = (int) get_post_meta($id, get_prefix() . $this->discount_cyq4_meta_key, true) ?? 0;

                update_post_meta($id, get_prefix() . $this->discount_cyq4_meta_key, $current_discount_cy + 1);
            }
        }
    }
}