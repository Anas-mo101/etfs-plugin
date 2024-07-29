<?php

class NavPostMeta implements PostMetaInterface
{

    public function process_incoming(PostMetaUtils $utils): bool
    {
        if (!$utils->meta || count($utils->meta) === 0) return false;

        if ($utils->selected_etfs !== null && $utils->files_map === null) {
            return $this->process_single($utils);
        } 
        
        return $this->process_multiple($utils);
    }

    public function process_multiple(PostMetaUtils $utils): bool
    {
        $query = new WP_Query(array('post_type' => 'etfs', 'posts_per_page' => 9999999));
        // loop through etfs 
        while ($query->have_posts()) {
            $query->the_post();
            $etf_title = get_the_title(); // get etf ticker name
            $single_utils = clone $utils;
            $single_utils->set_selected($etf_title);

            $this->process_single( $single_utils );
        }
        return true;
    }

    public function process_single(PostMetaUtils $utils): bool
    {

        foreach ($utils->meta as $data) {

            if ($data['Fund Ticker'] !== $utils->selected_etfs) {
                continue;
            }

            $post_to_update = custom_get_page_by_title($data['Fund Ticker'], OBJECT, 'etfs');
            if (!$post_to_update) return false;

            $connection = (int) get_post_meta($post_to_update->ID, "ETF-Pre-connection-id", true);
            if ($utils->connectionId !== null && $connection !== $utils->connectionId) {
                return false;
            }

            $previous_graph_data = get_post_meta($post_to_update->ID, "ETF-Pre-graph-json-data", true);
            $previous_graph_data_arr = $previous_graph_data !== '' ? json_decode($previous_graph_data, true) : array();
            $previous_graph_data_arr = is_array($previous_graph_data_arr) ? $previous_graph_data_arr : array();

            $current_time_in_millisecond = microtime(true);
            $current_time_in_microsecond = floor($current_time_in_millisecond * 1000);
            $now_date = date("Y-m-d", $current_time_in_millisecond);

            $previous_graph_data_arr_latest_timestamp = end($previous_graph_data_arr);
            $previous_graph_data_arr_latest_timestamp = gettype($previous_graph_data_arr_latest_timestamp) === 'array' ? $previous_graph_data_arr_latest_timestamp : false;
            $previous_graph_data_arr_latest_timestamp = $previous_graph_data_arr_latest_timestamp !== false ? $previous_graph_data_arr_latest_timestamp[0] : false;
            $pre_date = date("Y-m-d", $previous_graph_data_arr_latest_timestamp / 1000);

            if ($pre_date !== $now_date) {
                $new_graph_nav = array($current_time_in_microsecond, floatval($data['NAV']));
                $previous_graph_data_arr[] = $new_graph_nav;
                $updated_graph_data = json_encode($previous_graph_data_arr);
                update_post_meta($post_to_update->ID, 'ETF-Pre-graph-json-data', $updated_graph_data);
                update_post_meta($post_to_update->ID, 'ETF-Pre-graph-json-date-data', date("m/d/Y", strtotime("-1 day")));
            }

            $fdate = dateFormatingMDYYYY($data['Rate Date']);

            update_post_meta($post_to_update->ID, 'ETF-Pre-rate-date-data', $fdate);
            update_post_meta($post_to_update->ID, 'ETF-Pre-fund-pricing-date-data', $fdate);

            update_post_meta($post_to_update->ID, 'ETF-Pre-net-assets-data', big_number_format($data['Net Assets']));

            update_post_meta($post_to_update->ID, 'ETF-Pre-shares-out-standig-data', number_format($data['Shares Outstanding']));

            update_post_meta($post_to_update->ID, 'ETF-Pre-na-v-data', sprintf('%.2f', $data['NAV']));
            update_post_meta($post_to_update->ID, 'ETF-Pre-closing-price-data', sprintf('%.2f', $data['Market Price']));

            $nav_meta_keys = array(
                'ETF-Pre-current-etf-return-data' => 'NAV',
                'ETF-Pre-discount-percentage-data' => 'Premium/Discount Percentage',
                'ETF-Pre-thirty-day-median-data' => 'Median 30 Day Spread Percentage',
            );

            foreach ($nav_meta_keys as $key => $value) {
                if (isset($data[$value])) {
                    update_post_meta($post_to_update->ID, $key, $data[$value]);
                }
            }

            return true;
        }

        return false;
    }
}