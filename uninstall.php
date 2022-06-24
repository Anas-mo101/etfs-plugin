<?php
/**
 * @package ETFPlugin
 */

if( ! defined('WP_UNINSTALL_PLUGIN') ){
    die;
}

// Clear db stored data

$etf = get_posts( array('post_type' => 'etfs', 'numberposts' => -1 ) );
$subadvisors = get_posts( array('post_type' => 'subadvisors', 'numberposts' => -1 ) );

foreach($etf as $data){
    wp_delete_post($data->ID, true);
}

foreach($subadvisors as $data){
    wp_delete_post($data->ID, true);
}

global $wpdb;
$wp_table_name = $wpdb->prefix . "etfs_sftp_config_db"; 
$wpdb->query( "DROP TABLE IF EXISTS $wp_table_name" );
delete_option("my_plugin_db_version");


