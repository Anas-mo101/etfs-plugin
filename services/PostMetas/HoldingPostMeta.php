<?php 



class HoldingPostMeta implements PostMetaInterface {

    public function process_incoming(PostMetaUtils $utils): bool
    {
        if (!$utils->meta || count($utils->meta) === 0) {
            return false;
        }

        if ($utils->selected_etfs !== null && $utils->files_map === null) {

            return $this->process_single( $utils );
            
        } 

        return $this->process_multiple( $utils );
    }

    public function process_multiple(PostMetaUtils $utils): bool{

        $query = new WP_Query(array('post_type' => 'etfs', 'posts_per_page' => 999999));
        while ($query->have_posts()) {
            $query->the_post();
            $post_id_to_update = get_the_ID();
            $post_name_to_update = get_the_title();

            $connection = (int) get_post_meta($post_id_to_update, "ETF-Pre-connection-id", true);
            if ($connection !== $utils->connectionId) {
                continue;
            }

            $require_holdings = array();
            foreach ($utils->meta as $holdings) {
                if ($holdings['Account'] === $post_name_to_update) {
                    $require_holdings[] = $holdings;
                }
            }

            $market_value = array_column($require_holdings, 'MarketValue');
            array_multisort($market_value, SORT_DESC, SORT_NUMERIC, $require_holdings);

            $display_holdings = array();
            foreach ($require_holdings as $hgs) {
                $hgs['Shares'] = number_format($hgs['Shares']);
                $hgs['MarketValue'] = big_number_format($hgs['MarketValue']);

                $display_holdings[] = $hgs;
            }

            write_xlsx_file($display_holdings, $post_id_to_update, $post_name_to_update);

            if (count($display_holdings) > 10) $display_holdings = array_slice($display_holdings, 0, 10);

            $new_holdings = json_encode($display_holdings);

            // update_post_meta($post_to_update->ID,'ETF-Pre-top-holding-update-date-data',date("m/d/Y", strtotime("-1 day")));
            if (isset($display_holdings[0]['Date']) && !empty($display_holdings[0]['Date'])) {
                $date = $display_holdings[0]['Date'];
                update_post_meta($post_id_to_update, 'ETF-Pre-top-holding-update-date-data', $date);
            }

            update_post_meta($post_id_to_update, 'ETF-Pre-top-holders-data', $new_holdings);
        }
        wp_reset_query();
        return true;
    }

    public function process_single(PostMetaUtils $utils): bool{
        $post_to_update = custom_get_page_by_title($utils->selected_etfs, OBJECT, 'etfs');
        if (!$post_to_update) return false;

        $connection = (int) get_post_meta($post_to_update->ID, "ETF-Pre-connection-id", true);

        if ($utils->connectionId !== null && $connection !== $utils->connectionId) {
            return false;
        }

        $require_holdings = array();
        foreach ($utils->meta as $holdings) {
            if ($holdings['Account'] === $utils->selected_etfs) {
                $require_holdings[] = $holdings;
            }
        }

        $market_value = array_column($require_holdings, 'MarketValue');
        array_multisort($market_value, SORT_DESC, SORT_NUMERIC, $require_holdings);

        $display_holdings = array();
        foreach ($require_holdings as $hgs) {
            $hgs['Shares'] = number_format($hgs['Shares']);
            $hgs['MarketValue'] = big_number_format($hgs['MarketValue']);

            $display_holdings[] = $hgs;
        }

        // create xlsx file using $display_holdings
        write_xlsx_file($display_holdings, $post_to_update->ID, $utils->selected_etfs);

        if (count($display_holdings) > 10) $display_holdings = array_slice($display_holdings, 0, 10);

        $new_holdings = json_encode($display_holdings);

        // update_post_meta($post_to_update->ID,'ETF-Pre-top-holding-update-date-data',date("m/d/Y", strtotime("-1 day")));
        if (isset($display_holdings[0]['Date']) && !empty($display_holdings[0]['Date'])) {
            $date = $display_holdings[0]['Date'];
            update_post_meta($post_to_update->ID, 'ETF-Pre-top-holding-update-date-data', $date);
        }

        update_post_meta($post_to_update->ID, 'ETF-Pre-top-holders-data', $new_holdings);
        return true;
    }
}