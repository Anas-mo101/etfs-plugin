<?php
/**
 * @package ETFPlugin
 */

if( ! defined('WP_UNINSTALL_PLUGIN') ){
    die;
}

// Clear db stored data

$etf = get_posts( array('post_type' => 'etfs', 'numberposts' => -1 ) );

foreach($etf as $data){
    wp_delete_post($data->ID, true);
}