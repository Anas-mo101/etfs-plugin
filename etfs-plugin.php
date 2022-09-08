<?php

/**
 * Plugin Name:       ETFs
 * Description:       Manages Trueshares ETFs 
 * Version:           0.9.7
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


if( ! defined('ABSPATH') ){ die; }

if ( !class_exists('ETFPlugin') ) {

    class ETFPlugin{

        var $postTypes = array("etfs");

        var $prefix = "ETF-Pre-";

        var $customFields;

        var $etfs_list;

        var $unstructured_etfs_list;

        var $etfs_structured;

        var $etfs_custom_pdf_fields;

        var $FundDocs;

        function __construct($in_feilds,$in_etf,$in_uns_etfs,$etfs_structured,$pdf_fields){
            
            $this->customFields = $in_feilds;
            $this->etfs_list = $in_etf;
            $this->unstructured_etfs_list = $in_uns_etfs;
            $this->etfs_structured = $etfs_structured; 
            $this->custom_pdf_fields = $pdf_fields;

            add_action('init', array($this, 'etfs_post_init') );

            add_action( 'after_setup_theme', array($this,'insert_uns_category'));

            add_action( 'admin_menu', array($this, 'createCustomFields' ) );
            add_action('admin_menu', array($this,'sub_menu_callback'));

            add_action( 'save_post', array($this, 'save_custom_fields' ), 1, 2 );
            add_action( 'do_meta_boxes', array($this, 'removeDefaultCustomFields' ), 10, 3 );

            add_shortcode('render-etf-page', array($this, 'render_product_page'));
            add_shortcode('render-top-holders-table', array($this, 'render_top_holders'));
            add_shortcode('render-subadvisor-section', array($this, 'render_subadvisor'));
            add_shortcode('render-toptenmobile', array($this, 'render_toptenmobile'));
            add_shortcode('render-nav-graph', array($this, 'render_graph')); 
            add_shortcode('render-etf-content', array($this, 'render_content_cuz_elementor_dumb')); 
            add_shortcode('render-frontpage-box-content', array($this, 'render_frontpage_etfs'));

            add_action( 'wp_ajax_gsd', array($this, 'fetch_etf_data'));
            add_action( 'wp_ajax_etfconfig', array($this, 'set_sftp_config'));
            add_action( 'wp_ajax_scansftpdir', array($this, 'get_sftp_dir'));
            add_action( 'wp_ajax_etfupdatefile', array($this, 'set_sftp_file'));
            add_action( 'wp_ajax_fplayout', array($this, 'fp_layout'));
            add_action('wp_head', array($this,'hide_unstructional_etfs_section'));

            add_filter( 'script_loader_tag', array($this,'mind_defer_scripts') , 10, 3 );
            add_action( 'admin_enqueue_scripts', array($this, 'etfs_admin_edit_scripts') );
            add_action( 'wp_enqueue_scripts', array($this, 'etfs_template_scripts') );

            add_filter('mime_types', array($this, 'etfs_mime_types'));

            new \ETFsFundDocs\FundDocuments();
            new \ETFsDisDetail\DisturbutionDetail();
            \ETFsNoticeHandler\Notice_Handler::init();
        }

        function etfs_mime_types($mime){
            // add to config.php -> define('ALLOW_UNFILTERED_UPLOADS', true); 
            $mime['xlsm'] = 'application/vnd.ms-excel.sheet.macroEnabled.12'; 
            return $mime;
        }
        
        function activiate(){
            $this->etfs_post_type();
            $sftp = \ETFsSFTP\SFTP::getInstance();
            $sftp->sftp_db_init();
            flush_rewrite_rules();
        }

        function deactivate(){
            if ( wp_next_scheduled ( 'get_sftp_data' )){
                wp_clear_scheduled_hook('get_sftp_data');
            } 
            flush_rewrite_rules();
        }

        function etfs_post_init(){
            $this->etfs_post_type();
            $this->add_etfs_if_not_yet_added();

            if ( ! has_action( 'get_sftp_data' ) ) {
                add_action( 'get_sftp_data', '\ETFsSFTP\do_sftp_cycle', 10, 2 );
            }
        }

        function fp_layout(){
            $layout_setup_string = json_encode($_POST['etfs']);

            if(get_option('front-page-box-layout')){
                update_option('front-page-box-layout', $layout_setup_string);
            }else{
                add_option('front-page-box-layout', $layout_setup_string);
            }

            $res = array('update' => 'update success');
            wp_send_json($res);
        }

        function fetch_etf_data(){
            $etf_name = sanitize_text_field( $_POST['etfName'] );
            $etf_full_name = sanitize_text_field( $_POST['eftFullName'] );

            // nav
            $res_nav = null;
            if(isset($_POST['gsURL']) && $_POST['gsURL'] !== ''){
                $url = sanitize_url( $_POST['gsURL']);
                $columns; $data;
                $url_state = $_POST['gsURLstate'];
                if($url_state === "true"){
                    $columns = (new GoogleSheetProvider())->getColumns($url);
                    $data = (new GoogleSheetProvider())->getDataFromUrl($url, $columns);
                }else{
                    $columns = (new CsvProvider())->load_and_fetch_headers($url);
                    $data = (new CsvProvider())->load_and_fetch($url, $columns);
                }

                if( $data || count($data) > 0){
                    $post_meta = new PostMeta($data,'Nav'); 
                    $post_meta->set_selected($etf_name);
                    $res_nav = $post_meta->process_incoming();
                }
            }

            // holdings
            $res_holdings = null;
            if(isset($_POST['hlURL']) && $_POST['hlURL'] !== ''){
                $url_h = sanitize_url($_POST['hlURL']);
                $columns_h;
                $data_h;   
                $url_h_state = $_POST['hlURLstate']; 
                if($url_h_state === "true"){
                    $columns_h = (new GoogleSheetProvider())->getColumns($url_h);
                    $data_h = (new GoogleSheetProvider())->getDataFromUrl($url_h,$columns_h);
                }else{
                    $columns_h = (new CsvProvider())->load_and_fetch_headers($url_h);
                    $data_h = (new CsvProvider())->load_and_fetch($url_h,$columns_h);
                }

                if( $data_h || count($data_h) > 0){
                    $post_meta = new PostMeta($data_h,'Holding'); 
                    $post_meta->set_selected($etf_name);
                    $res_holdings = $post_meta->process_incoming();
                }
            }

            // ror
            $res_ror = null;
            if(isset($_POST['monthlyRorURL']) && $_POST['monthlyRorURL'] !== ''){
                $url_r = sanitize_url($_POST['monthlyRorURL']);
                $columns_r; $data_r;   
                $url_r_state = $_POST['monthlyRorstate']; 
                if($url_r_state === "true"){
                    $columns_r = (new GoogleSheetProvider())->getColumns($url_r);
                    $data_r = (new GoogleSheetProvider())->getDataFromUrl($url_r,$columns_r);
                }else{
                    $columns_r = (new CsvProvider())->load_and_fetch_headers($url_r);
                    $data_r = (new CsvProvider())->load_and_fetch($url_r,$columns_r);
                }


                if( $data_r || count($data_r) > 0){
                    $post_meta = new PostMeta($data_r,'Ror'); 
                    $post_meta->set_selected($etf_name);
                    $res_ror = $post_meta->process_incoming();
                }
            }


            $res_dist = null;
            if(isset($_POST['distMemoURL']) && $_POST['distMemoURL'] !== ''){
                $url_dist_memo = sanitize_url($_POST['distMemoURL']);

                $XLSMParser = new \ETFsXSLMParser\XSLMParser($url_dist_memo);
                $data = $XLSMParser->process_single_data($etf_name);

                if($data != false){
                    $post_meta = new PostMeta($data,'Dist');
                    $post_meta->set_selected($etf_name);
                    $res_dist = $post_meta->process_incoming();
                }
            }

            $post_to_update = get_page_by_title( $etf_name, OBJECT, 'etfs' );
            (new Calculations())->init($post_to_update->ID);
            
            $_res = Array( 
                'nav' => $res_nav, 
                'holdings' =>  $res_holdings, 
                'monthly_ror' => $res_ror, 
                'dist_memo' => $res_dist
            );

			wp_send_json($_res);
        }

        function set_sftp_config(){
            $sftp = \ETFsSFTP\SFTP::getInstance();
            $res = $sftp->set_config($_POST);

            if($res['cycle'] === 'First SFTP Cycle Is Successful'){
                if (! wp_next_scheduled ( 'get_sftp_data' )){
                    $cron_res = wp_schedule_event( time(), $_POST['freq'], 'get_sftp_data' );
                    error_log($cron_res);
                }else{
                    wp_clear_scheduled_hook( 'get_sftp_data' );
                    $cron_res = wp_schedule_event( time(), $_POST['freq'], 'get_sftp_data' );
                    error_log($cron_res);
                }
            }
            wp_send_json($res);
        }

        function get_sftp_dir(){
            $sftp = \ETFsSFTP\SFTP::getInstance();
            $sftp_res = $sftp->get_dir_conntent();
            $res = array('files' => $sftp_res);
            wp_send_json($res);
        }

        function set_sftp_file(){
            $sftp = \ETFsSFTP\SFTP::getInstance();
            $sftp_res = $sftp->set_files_name($_POST);
            $res = array('update' => $sftp_res);
            wp_send_json($res);
        } 

        function run_sftp_cycle(){
            $sftp = \ETFsSFTP\SFTP::getInstance();
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
              'supports' => array('title', 'editor','custom-fields','thumbnail')
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
                $sftp = \ETFsSFTP\SFTP::getInstance();
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
                    array($this, 'display_custom_fields' ), $postType, 'normal', 'high' );
                }
            }
        }

        // Create custom field (Fund Documents)
        function customFieldsFunds(){
            if ( function_exists( 'add_meta_box' ) ) {
                foreach ( $this->postTypes as $postType ) {
                    add_meta_box( 'my-custom-fields-pdf', 
                    '<span> Fund Documents </span>
                    <div style="display: flex; justify-content: flex-end;">
                        <div class="button button-primary button-large" onclick="document.getElementById(`ETF-Pre-popup-underlay-new-fund-field`).style.display = `flex`"> Add New Document </div>
                    </div>',
                    array($this, 'display_custom_fields' ), 
                    $postType, 
                    'normal', 
                    'high' );
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
                wp_enqueue_style( 'settingStyling', $dir . 'admin/css/SettingStyling.css' );
                wp_enqueue_script('settingsConfig', $dir . 'admin/js/settingsConfig.js' );
                wp_enqueue_script('sortableJS', 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js');
                wp_enqueue_script('jQuerySortableJS', 'https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js');
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
        function display_custom_fields() {
            global $post;  
            if ( $post->post_type == "etfs" ){
                require_once 'assets/preview-table-response.php'; 
                create_custom_efts_fields($this->customFields,$this->prefix,'2','my-custom-fields'); ?>
                <div style="display: flex; gap: 30px;"> 
                    <div>
                        <button id="etf-sheet-sync-button" type="button" class="button button-primary button-large"> Get ETF info from linked files </button>
                        <button id="etf-manual-edit-button" type="button" class="button button-primary button-large"> Edit </button>
                    </div>
                    <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>loadinganimation" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(__FILE__ ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                        <p class="<?php echo $this->prefix ?>status-states"  style="display: none; color: green; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-success"> Data Saved successfully </p>
                        <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed"> Error Occured </p>
                        <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed-url"> Enter Valid URL </p>
                        <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>fetch-load">  </div>
                    </div>
                </div>
            <?php } 
        }

        /**
        * Save the new Custom Fields values
        */
        function save_custom_fields( $post_id, $post ) {
            if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) ) return;
            if ( !isset( $_POST[ 'my-custom-fields-pdf_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields-pdf_wpnonce' ], 'my-custom-fields-pdf' ) ) return;
            if ( !current_user_can( 'edit_post', $post_id ) ) return;
            if ( ! in_array( $post->post_type, $this->postTypes ) ) return;

            foreach ( $this->customFields as $customField ) {
                if ( current_user_can( $customField['capability'], $post_id ) ) {
                    if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
                        $value = $_POST[ $this->prefix . $customField['name'] ];
                        if($customField['name'] === 'fund-footer-desc-data' ||
                             $customField['name'] === 'preformance-section-desc-data' || 
                                $customField['name'] === 'fund-header-textarea-data'){
                            $value = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>", $value);
                        }
                        update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $value );
                    } else {
                        delete_post_meta( $post_id, $this->prefix . $customField[ 'name' ] );
                    }
                }
            }

            \ETFsFundDocs\FundDocuments::save_feilds($_POST,$post_id);

            (new Calculations())->init($post_id);
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

        function render_toptenmobile() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/toptenmobile.php');
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
    $etfPlugin = new ETFPlugin($custom_fields,$etfs_all,$etfs_unstructured,$etfs_structured,$custom_pdf_fields);
    register_activation_hook( __FILE__, array($etfPlugin, 'activiate') );
    register_deactivation_hook( __FILE__, array($etfPlugin, 'deactivate') );
}
    



