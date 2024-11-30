<?php

/**
 * Plugin Name:       ETFs
 * Description:       Manages Trueshares ETFs 
 * Version:           2.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Anmo
 */

/**
 * Description 
 * This plugin was made specifically for TrueShares to manage 
 * their ETFs effortlessly, the goal is automate the process
 * of gathering data from multiple files types.
 */

if (!defined('ABSPATH')) {
    die;
}


class ETFPlugin
{
    function __construct()
    {
        new \ETFsFundDocs\FundDocuments();

        $dist = new \ETFsDisDetail\DisturbutionDetail();
        $dist->init();

        new ETFRestController();
        new ETFShortCodes();
        new CustomFeilds();

        add_action('init', array($this, 'etfs_post_init') );
        add_action('admin_menu', array($this,'sub_menu_callback'));

        add_action('admin_enqueue_scripts', array($this, 'etfs_admin_edit_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'etfs_template_scripts'));
        add_filter('script_loader_tag', array($this, 'mind_defer_scripts'), 10, 3);
        add_action('wp_head', array($this, 'hide_unstructional_etfs_section'));
        add_action('after_setup_theme', array($this, 'insert_uns_category'));
    }

    function etfs_post_type()
    {
        register_post_type('etfs', array(
            'labels' => array(
                'name' => _x('ETFs', 'post type general name'),
                'singular_name' => _x('ETFs', 'post type singular name'),
                'add_new' => _x('Add New', 'ETFs'),
                'add_new_item' => __('Add New ETF'),
                'edit_item' => __('Edit ETF'),
                'new_item' => __('New ETF'),
                'view_item' => __('View ETF'),
                'search_items' => __('Search ETF'),
                'not_found' =>  __('No ETFs found'),
                'not_found_in_trash' => __('No ETF found in Trash'),
                'parent_item_colon' => '',
                'menu_name' => 'ETFs'
            ),
            'public' => true,
            'publicly_queryable' => true,
            'post_status' =>  'publish',
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'etfs', 'with_front' => false),
            'capability_type' => 'post',
            'hierarchical' => false,
            'taxonomies'  => array('category'),
            'supports' => array('title', 'editor', 'custom-fields', 'thumbnail')
        ));

        register_post_type('subadvisors', array(
            'labels' => array(
                'name' => _x('Sub-Advisors', 'post type general name'),
                'singular_name' => _x('Sub-Advisors', 'post type singular name'),
                'add_new' => _x('Add New', 'Sub-Advisor'),
                'add_new_item' => __('Add New Sub-Advisor'),
                'edit_item' => __('Edit Sub-Advisor'),
                'new_item' => __('New Sub-Advisor'),
                'view_item' => __('View Sub-Advisor'),
                'search_items' => __('Search Sub-Advisor'),
                'not_found' =>  __('No Sub-Advisors found'),
                'not_found_in_trash' => __('No Sub-Advisors found in Trash'),
                'parent_item_colon' => '',
                'menu_name' => 'Sub-Advisors'
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'subadvisors'),
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'thumbnail')
        ));
    }

    function sub_menu_callback()
    {
        add_submenu_page(
            'edit.php?post_type=etfs', //$parent_slug
            'Settings',  //$page_title
            'Settings',        //$menu_title
            'manage_options',           //$capability
            'etfs_general_settings', //$menu_slug
            function () { // anonymous callback function
                include 'view/settings.php';
            }
        );

        add_submenu_page(
            'edit.php?post_type=etfs', //$parent_slug
            'Products Table',  //$page_title
            'Products Table',        //$menu_title
            'manage_options',           //$capability
            'products_table', //$menu_slug
            function () { // anonymous callback function
                include 'view/products_table.php';
            }
        );
    }

    function seed_etfs()
    {
        //Define the category
        require_once 'utils/keys.php';

        foreach (get_etfs_all() as $etf_name) {
            if (!get_page_by_path($etf_name, OBJECT, 'etfs'))
                wp_insert_post(array(
                    'post_type'       => "etfs",
                    'post_title'      => $etf_name,
                    'post_name'       => $etf_name,
                    'post_status'     => "publish",
                    'comment_status'  => "closed",
                    'ping_status'     => "closed",
                ));
        }
    }

    function etfs_post_init()
    {
        $this->etfs_post_type();
        $this->seed_etfs();

        if (!has_action('get_sftp_data')) {
            add_action('get_sftp_data', '\ETFsSFTP\do_sftp_cycle', 10, 2);
        }
    }

    function insert_uns_category()
    {
        if (!term_exists('brand')) {
            wp_insert_term(
                'Unstructured ETFs',
                'category',
                array(
                    'slug' => 'unstructured-etfs'
                )
            );
        }
    }

    function etfs_admin_edit_scripts($hook)
    {
        global $post;
        $dir = plugin_dir_url(__FILE__);
        if ($hook == 'post-new.php' || $hook == 'post.php') {
            if ("etfs" === $post->post_type) {
                wp_enqueue_script('singles-fund-scripts', $dir . 'admin/js/singles.js');
                wp_enqueue_style('singles-styling', $dir . 'admin/css/singles.css');

                wp_enqueue_script('highcharts', 'https://code.highcharts.com/highcharts.js');
                wp_enqueue_script('exporting', 'https://code.highcharts.com/modules/exporting.js');
                wp_enqueue_script('export-data', 'https://code.highcharts.com/modules/export-data.js');
            }
        } elseif ($hook == 'etfs_page_etfs_general_settings') {
            wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
            wp_enqueue_script('sortableJS', 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js');

            wp_enqueue_script('setting-scripts', $dir . 'admin/js/settings.js');
            wp_enqueue_style('setting-styling', $dir . 'admin/css/settings.css');
        } elseif ($hook == 'etfs_page_products_table') {
            wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
            wp_enqueue_script('sortableJS', 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js');

            wp_enqueue_script('setting-scripts', $dir . 'admin/js/products.js');
            wp_enqueue_style('setting-styling', $dir . 'admin/css/settings.css');
        }
    }

    function etfs_template_scripts($hook)
    {
        global $post;
        if (is_single() &&  "etfs" === $post->post_type) {
            wp_enqueue_script('highstock', 'https://code.highcharts.com/stock/highstock.js');
            wp_enqueue_script('data', 'https://code.highcharts.com/stock/modules/data.js');
            wp_enqueue_script('accessibility', 'https://code.highcharts.com/stock/modules/accessibility.js');
        }
    }

    function mind_defer_scripts($tag, $handle, $src)
    {
        $defer = array('highstock', 'data', 'accessibility');
        if (in_array($handle, $defer)) {
            return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
        }
        return $tag;
    }

    function hide_unstructional_etfs_section()
    {
        if (in_category('Unstructured ETFs')) {
            echo '<style> .unstructured-etfs{ display: none !important; } </style>';
        }
    }

    function activiate()
    {
        $this->etfs_post_type();
        $connections_services = new \ConnectionServices();
        $connections_services->sftp_db_init();

        $dynamic_tables = new \DynamicProductsTable();
        $dynamic_tables->init();

        flush_rewrite_rules();
    }

    function deactivate()
    {
        if ( wp_next_scheduled ( 'get_sftp_data' )){
            wp_clear_scheduled_hook('get_sftp_data');
        }
        flush_rewrite_rules();
    }
}


if (class_exists('ETFPlugin')) {
    include 'alt_autoload.php';
    $etfPlugin = new ETFPlugin();
    register_activation_hook(__FILE__, array($etfPlugin, 'activiate'));
    register_deactivation_hook(__FILE__, array($etfPlugin, 'deactivate'));
}
