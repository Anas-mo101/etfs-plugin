<?php
/**
 * @package ETFPlugin
 */

/**
 * Plugin Name:       ETFs
 * Description:       Manages Trueshares ETFs 
 * Version:           0.9.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Anmo
 * 
 */

/**
 * Description 
 * This plugin was made specifically for TrueShares to manage 
 * their ETFs effortlessly, the goal is automate the process
 * of gathering data from multiple files types then store it to
 * be presented by on the website. That was accomplished by 4 
 * step solution.
 *  -> add files link
 *  -> prview data
 *  -> save 
 *  -> update
 * 
 * Version 0.0.5 update
 * -> Automate 100% of process
 * -> No human interaction required
 * -> Values stored can be edited when required             /\
 * -> Implementation of SFTP to get required files         //\\
 * -> Automatically receive and proccess files            //  \\ 
 * -> Extract required data and store it                 //    \\
 * -> Repeat this process X times in a day              //      \\   
 * 
 * 
 */


if( ! defined('ABSPATH') ){
    die;
}

if ( !class_exists('EtfPlugin') ) {

    class ETFPlugin{

        var $postTypes = array("etfs");

        var $prefix = "ETF-Pre-";

        var $customFields;

        var $etfs_list;

        var $unstructured_etfs_list;

        var $etfs_structured;

        var $automation = false;

        function __construct($in_feilds,$in_etf,$in_uns_etfs,$etfs_structured){
            
            $this->customFields = $in_feilds;
            $this->etfs_list = $in_etf;
            $this->unstructured_etfs_list = $in_uns_etfs;
            $this->etfs_structured = $etfs_structured; 

            add_action('init', array($this, 'etfs_post_init') );

            add_action( 'after_setup_theme', array($this,'insert_uns_category'));

            add_action( 'admin_menu', array($this, 'createCustomFields' ) );
            add_action( 'admin_menu', array($this, 'customFieldsFunds' ) );
            add_action('admin_menu', array($this,'sub_menu_callback'));

            add_action( 'save_post', array($this, 'saveCustomFields' ), 1, 2 );
            add_action( 'do_meta_boxes', array($this, 'removeDefaultCustomFields' ), 10, 3 );

            add_shortcode('render-etf-page', array($this, 'render_product_page'));
            add_shortcode('render-top-holders-table', array($this, 'render_top_holders'));
            add_shortcode('render-subadvisor-section', array($this, 'render_subadvisor'));
            add_shortcode('render-nav-graph', array($this, 'render_graph')); 
            add_shortcode('render-etf-content', array($this, 'render_content_cuz_elementor_dumb')); 
            add_shortcode('render-frontpage-box-content', array($this, 'render_frontpage_etfs'));

            add_action( 'wp_ajax_gsd', array($this, 'fetch_etf_data'));
            add_action( 'wp_ajax_etfconfig', array($this, 'set_sftp_config'));
            add_action( 'wp_ajax_scansftpdir', array($this, 'get_sftp_dir'));
            add_action( 'wp_ajax_etfupdatefile', array($this, 'set_sftp_file'));

            add_action('wp_head', array($this,'hide_unstructional_etfs_section'));

            add_action( 'get_sftp_data', array($this, 'run_sftp_cycle'));

            add_filter( 'script_loader_tag', array($this,'mind_defer_scripts') , 10, 3 );
            add_action( 'admin_enqueue_scripts', array($this, 'etfs_admin_edit_scripts') );
            add_action( 'wp_enqueue_scripts', array($this, 'etfs_template_scripts') );
        }

        function activiate(){
            $this->etfs_post_type();
            $sftp = SFTP::getInstance();
            $sftp->sftp_db_init();
            flush_rewrite_rules();
        }

        function deactivate(){
            wp_clear_scheduled_hook( 'get_sftp_data' );
            flush_rewrite_rules();
        }

        function etfs_post_init(){
            $this->etfs_post_type();
            $this->add_etfs_if_not_yet_added();
        }

        function fetch_etf_data(){
            $url = $_POST['gsURL'];
            $url_h = $_POST['hlURL'];
            $url_monlthy_ror = $_POST['monthlyRorURL'];
            $url_dist_memo = $_POST['distMemoURL'];
            $etf_name = $_POST['etfName'];
            $etf_full_name = $_POST['eftFullName'];

            $url_state = $_POST['gsURLstate'];
            $url_h_state = $_POST['hlURLstate'];

            $responesData = null;
            $columns;
            $data;
            if($url_state === "true"){
                $columns = (new GoogleSheetProvider())->getColumns($url);
                $data = (new GoogleSheetProvider())->getDataFromUrl($url, $columns);
            }else{
                $columns = (new CsvProvider())->load_and_fetch_headers($url);
                $data = (new CsvProvider())->load_and_fetch($url, $columns);
            }
            $responesData = Array('headers' => $columns, 'body' =>  $data);

            $responesData_h = null;
            $columns_h;
            $data_h;
            if($url_h_state === "true"){
                $columns_h = (new GoogleSheetProvider())->getColumns($url_h);
                $data_h = (new GoogleSheetProvider())->getDataFromUrl($url_h,$columns_h);
            }else{
                $columns_h = (new CsvProvider())->load_and_fetch_headers($url_h);
                $data_h = (new CsvProvider())->load_and_fetch($url_h,$columns_h);
            }
            $responesData_h = Array('headers' => $columns_h, 'body' =>  $data_h);
            $monthly_ror_pdf_data = (new Pdf2Data())->get_monthly_fund_data($url_monlthy_ror,$etf_name,$etf_full_name,false);
            $dist_memo_pdf_data = (new Pdf2Data())->get_distrubation_memo_data($url_dist_memo,$etf_name,$etf_full_name,false);
            $csv_res = Array( 'nav' => $responesData, 'holdings' =>  $responesData_h, 'monthly_ror' => $monthly_ror_pdf_data, 'dist_memo' => $dist_memo_pdf_data);
			wp_send_json($csv_res);
        }

        function set_sftp_config(){
            $sftp = SFTP::getInstance();
            $res = $sftp->set_config($_POST);

            if($res['cycle'] === 'first sftp cycle is successfull'){
                if (! wp_next_scheduled ( 'get_sftp_data' ))  wp_schedule_event( time(), $_POST['freq'], 'get_sftp_data' );
                $this->automation = true;
            }elseif ($res["cycle"] === "blocked") {
                $this->automation = false;
                wp_clear_scheduled_hook( 'get_sftp_data' );
            }

            wp_send_json($res);
        }

        function get_sftp_dir(){
            $sftp = SFTP::getInstance();
            $sftp_res = $sftp->get_dir_conntent();
            $res = array('files' => $sftp_res);
            wp_send_json($res);
        }

        function set_sftp_file(){
            $sftp = SFTP::getInstance();
            $sftp_res = $sftp->set_files_name($_POST);
            $res = array('update' => $sftp_res);
            wp_send_json($res);
        } 

        function run_sftp_cycle(){
            $sftp = SFTP::getInstance();
            $sftp->auto_cycle();
        }

        /**
         * registers etf post type
         */
        function etfs_post_type() {
            register_post_type('etfs',array(
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
              'rewrite' => array('slug'=>'etfs'),
              'capability_type' => 'post',
              'hierarchical' => false,
              'taxonomies'  => array( 'category' ),
              'supports' => array('title', 'editor','custom-fields')
            ));

            register_post_type('subadvisors',array(
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
              'rewrite' => array('slug'=>'subadvisors'),
              'capability_type' => 'post',
              'hierarchical' => false,
              'supports' => array('title','editor','thumbnail')
            ));
            
        }

        function sub_menu_callback(){
            add_submenu_page(
                'edit.php?post_type=etfs', //$parent_slug
                'Settings',  //$page_title
                'Settings',        //$menu_title
                'manage_options',           //$capability
                'etfs_general_settings',//$menu_slug
                function () { // anonymous callback function
                    include 'assets/etfs-settings.php';
                }
            );
        }

        /**
         * creates 16 etfs
         */
        function add_etfs_if_not_yet_added() {
            //Define the category
            foreach($this->etfs_list as $etf_name) {
              if (!get_page_by_path($etf_name,OBJECT,'etfs'))
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

        function insert_uns_category() {
            if(!term_exists('brand')) {
                wp_insert_term(
                    'Unstructured ETFs',
                    'category',
                    array(
                        'slug' => 'unstructured-etfs'
                    )
                );
            }
        }

        function hide_unstructional_etfs_section(){
            if(in_category( 'Unstructured ETFs' )){
                echo '<style> .unstructured-etfs{ display: none !important; } </style>';
            }
        }

        /**
        * Remove the default Custom Fields meta box
        */
        function removeDefaultCustomFields( $type, $context, $post ) {
            foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
                foreach ( $this->postTypes as $postType ) {
                    remove_meta_box( 'postcustom', $postType, $context );
                }
            }
        }

        /**
        * Create the new Custom Fields meta box
        */
        function createCustomFields() {
            if ( function_exists( 'add_meta_box' ) ) {
                $sftp = SFTP::getInstance();
                $config = $sftp->get_config();
                $state = $config['Automate'] === 't' ? 'SFTP enabled' : 'SFTP disabled';
                $color = $config['Automate'] === 't' ? 'green' : 'red';
                $cycle_state = $config['Active_Cycle'] === 't' ? '(The SFTP Cycle is being updated. Please do not make any changes to ETF pages at this time)' : '';
                $cycle_color = $config['Active_Cycle'] === 't' ? 'red' : 'black';
                foreach ( $this->postTypes as $postType ) {
                    add_meta_box( 'my-custom-fields',  
                    '<span style="color: ' . $cycle_color . ';"> ETF Settings ' . $cycle_state . '</span>  
                    <div style="display: flex; justify-content: flex-end;">
                        <span style="display: flex;">
                            <h4 style="margin: auto 15px;">  ' . $state . ' </h4>
                            <span style="margin: auto 0;">
                                <div style="background-color: ' . $color . ';border: solid 1px;border-radius: 50%;width: 15px;margin: auto 0px;height: 15px;"></div> 
                            </span>
                        </span>
                    </div>',
                    array($this, 'displayCustomFields' ), $postType, 'normal', 'high' );
                }
            }
        }

        // Create custom field (Fund Documents)
        function customFieldsFunds(){
            if ( function_exists( 'add_meta_box' ) ) {
                foreach ( $this->postTypes as $postType ) {
                    add_meta_box( 'my-custom-fields-pdf', 'Fund Documents', array($this, 'displayCustomFieldsPdf' ), $postType, 'normal', 'high' );
                }
            }
        }

        function etfs_admin_edit_scripts( $hook ) {
            global $post;
            $dir = plugin_dir_url( __FILE__ );
            if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
                if ( "etfs" === $post->post_type ) {    
                    wp_enqueue_script('PreviewRen', $dir . 'admin/js/PreviewRen.js' );
                    wp_enqueue_script('ReqFuncs', $dir . 'admin/js/ReqFuncs.js' );
                    wp_enqueue_script('fileSelector', $dir . 'admin/js/fileSelector.js' );
                    wp_enqueue_script('highcharts', 'https://code.highcharts.com/highcharts.js' );
                    wp_enqueue_script('exporting', 'https://code.highcharts.com/modules/exporting.js' );
                    wp_enqueue_script('export-data', 'https://code.highcharts.com/modules/export-data.js' );
                    wp_enqueue_style( 'PreviewStyling', $dir . 'admin/css/PreviewStyling.css' );
                }
            }elseif ( $hook == 'etfs_page_etfs_general_settings') {
                wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );
                wp_enqueue_style( 'SettingStyling', $dir . 'admin/css/SettingStyling.css' );
                wp_enqueue_script('settingsConfig', $dir . 'admin/js/settingsConfig.js' );
            }
        }

        function write_log($log) {
            if (true === WP_DEBUG) {
                if (is_array($log) || is_object($log)) {
                    error_log(print_r($log, true));
                } else {
                    error_log($log);
                }
            }
        }

        function etfs_template_scripts( $hook ) {
            global $post;
            if ( is_single() &&  "etfs" === $post->post_type){
                wp_enqueue_script('highstock', 'https://code.highcharts.com/stock/highstock.js' );
                wp_enqueue_script('data', 'https://code.highcharts.com/stock/modules/data.js' );
                wp_enqueue_script('accessibility', 'https://code.highcharts.com/stock/modules/accessibility.js' );
            }
        }

        function mind_defer_scripts( $tag, $handle, $src ) {
            $defer = array('highstock', 'data', 'accessibility');

            if ( in_array( $handle, $defer ) ) {
               return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
            }
              
            return $tag;
        } 

        /**
        * Display the new Custom Fields meta box
        */
        function displayCustomFields() {
            global $post;  
            if ( $post->post_type == "etfs" ){
                require_once 'assets/preview-table-response.php';
            } 
            require_once 'assets/fields-display.php'; 
        }

        // Display the new Custom Fields (Fund Documents)
        function displayCustomFieldsPdf() {
            global $post;  
            if ( $post->post_type == "etfs" ){
                require_once 'assets/fund-fields-display.php';
            } 
        }

        /**
        * Save the new Custom Fields values
        */
        function saveCustomFields( $post_id, $post ) {
            if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
                return;
            if ( !isset( $_POST[ 'my-custom-fields-pdf_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields-pdf_wpnonce' ], 'my-custom-fields-pdf' ) )
                return;
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
            if ( ! in_array( $post->post_type, $this->postTypes ) )
                return;
            foreach ( $this->customFields as $customField ) {
                if ( current_user_can( $customField['capability'], $post_id ) ) {
                    if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
                        $value = $_POST[ $this->prefix . $customField['name'] ];
                        update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $value );
                    } else {
                        delete_post_meta( $post_id, $this->prefix . $customField[ 'name' ] );
                    }
                }
            }
        }
        
        // call shortcode [render-etf-page] to render product table
        function render_product_page() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/etf-product-page.php');
            return ob_get_clean();
        }
        
        // call shortcode [render-top-holders-table] to render product table
        function render_top_holders() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/toptenshortcode.php');
            return ob_get_clean();
        }

        function render_subadvisor() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/subadvisorshortcode.php');
            return ob_get_clean();
        }

        function render_graph() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/nav-graph.php');
            return ob_get_clean();
        }

        function render_content_cuz_elementor_dumb() {
            ob_start();
            global $post;
            echo $post->post_content; 
            return ob_get_clean();
        }

        function render_frontpage_etfs() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/frontpage-etfs-boxes.php');
            return ob_get_clean();
        }
    }
}

if(class_exists('ETFPlugin')){
    include 'alt_autoload.php';
    $etfPlugin = new ETFPlugin($custom_fields,$etfs_all,$etfs_unstructured,$etfs_structured);

    register_activation_hook( __FILE__, array($etfPlugin, 'activiate') );
    register_deactivation_hook( __FILE__, array($etfPlugin, 'deactivate') );
}
    



