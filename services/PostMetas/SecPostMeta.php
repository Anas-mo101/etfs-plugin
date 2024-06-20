<?php

class SecPostMeta {

    public function process_incoming(PostMetaUtils $utils): bool {
        if (!$utils->meta || count($utils->meta) === 0) return false;

        if ( $utils->selected_etfs === null && $utils->files_map !== null) {
          
            return $this->process_multiple( $utils );

        }

        return false;
    }

    public function process_multiple( PostMetaUtils $utils ): bool {
        $query = new WP_Query(array('post_type' => 'etfs', 'posts_per_page' => 999999));
        while ($query->have_posts()) {
            $query->the_post();
            $post_id_to_update = get_the_ID();
            $post_name_to_update = get_the_title();

            $connection = (int) get_post_meta($post_id_to_update, "ETF-Pre-connection-id", true);
            if ($utils->connectionId !== null && $connection !== $utils->connectionId) {
                continue;
            }

            foreach ($utils->meta as $data) {
                if ($data['Fund Ticker'] === $post_name_to_update) {
                    update_post_meta($post_id_to_update, 'ETF-Pre-sec-yeild-data', $data['30 Day SEC Yield - Unsubsidized']);
                    update_post_meta($post_id_to_update, 'ETF-Pre-rate-date-fund-details-data', $data['Date']);
                }
            }
        }
        wp_reset_query();
        return true;
    }

    public function process_single( PostMetaUtils $utils ): bool {
       return true;
    }
}