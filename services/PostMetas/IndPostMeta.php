<?php


class IndPostMeta implements PostMetaInterface {

    public function process_incoming(PostMetaUtils $utils): bool{
        if (!$utils->meta || count($utils->meta) === 0) {
            return false;
        }

        if (!isset($utils->meta[0]['YTD Return'])) {
            return false;
        }

        if ($utils->selected_etfs !== null && $utils->files_map === null) {
            return $this->process_single( $utils );
        }

        return $this->process_multiple( $utils );
    }

    public function process_multiple(PostMetaUtils $utils): bool{
        $data = $utils->meta[0]['YTD Return'];

        $query = new WP_Query(array('post_type' => 'etfs', 'posts_per_page' => 9999999));
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $connection = (int) get_post_meta($post_id, "ETF-Pre-connection-id", true);
            if ($utils->connectionId !== null && $connection !== $utils->connectionId) {
                continue;
            }

            update_post_meta($post_id, 'ETF-Pre-ytd-sp-return-data', $data);
        }
        return true;
    }

    public function process_single(PostMetaUtils $utils): bool{
        $data = $utils->meta[0]['YTD Return'];

        $post_to_update = custom_get_page_by_title($utils->selected_etfs, OBJECT, 'etfs');

        $connection = (int) get_post_meta($post_to_update->ID, "ETF-Pre-connection-id", true);
        if ($utils->connectionId !== null && $connection !== $utils->connectionId) {
            return false;
        }

        update_post_meta($post_to_update->ID, 'ETF-Pre-ytd-sp-return-data', $data);
        return true;
    }
}