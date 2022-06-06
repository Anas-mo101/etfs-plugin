<?php
/**
 * @package ETFPlugin
 */

/**
 * Plugin Name:       ETFs
 * Description:       Manages Trueshares ETFs 
 * Version:           0.0.3
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
 * -> Values stored can be edited when required  
 * -> Implementation of SFTP to get required files 
 * -> Automatically receive and proccess files
 * -> Extract required data and store it 
 * -> Repeat this process X times in a day
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

        function __construct($in_feilds,$in_etf,$in_uns_etfs){
            
            $this->customFields = $in_feilds;
            $this->etfs_list = $in_etf;
            $this->unstructured_etfs_list = $in_uns_etfs;

            add_action('init', array($this, 'etfs_post_init') );
            add_action( 'admin_menu', array($this, 'createCustomFields' ) );
            add_action( 'save_post', array($this, 'saveCustomFields' ), 1, 2 );
            add_action( 'do_meta_boxes', array($this, 'removeDefaultCustomFields' ), 10, 3 );

            add_shortcode('render-etf-page', array($this, 'renderProductPage'));
            add_shortcode('render-top-holders-table', array($this, 'renderTopHolders'));

            add_action( 'wp_ajax_gsd', array($this, 'fetch_etf_data') );
        }

        function activiate(){
            $this->etfs_post_init();
            flush_rewrite_rules();
        }

        function deactivate(){
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

            $columns = (new GoogleSheetProvider())->getColumns($url);
            $data = (new GoogleSheetProvider())->getDataFromUrl($url, $columns);
            $responesData = Array( 'headers' => $columns, 'body' =>  $data );

            $columns_h = (new GoogleSheetProvider())->getColumns($url_h);
            $data_h = (new GoogleSheetProvider())->getDataFromUrl($url_h, $columns_h);
            $responesData_h = Array( 'headers' => $columns_h, 'body' =>  $data_h );

            $monthly_ror_pdf_data = (new Pdf2Data())->get_monthly_fund_data($url_monlthy_ror,$etf_name,false);

            $dist_memo_pdf_data = (new Pdf2Data())->get_distrubation_memo_data($url_dist_memo,$etf_name,false);

            $csv_res = Array( 'nav' => $responesData, 'holdings' =>  $responesData_h, 'monthly_ror' => $monthly_ror_pdf_data, 'dist_memo' => $dist_memo_pdf_data);

			wp_send_json($csv_res);
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
              'supports' => array('title','editor','custom-fields')
            ));
        }

        /**
         * creates 16 etfs
         */
        function add_etfs_if_not_yet_added() {
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
                foreach ( $this->postTypes as $postType ) {
                    add_meta_box( 'my-custom-fields', 'ETFs Settings', array($this, 'displayCustomFields' ), $postType, 'normal', 'high' );
                }
            }
        }

        /**
         * Adds needed scrpits (php, js & css) to be improved using wp way
         */
        function includeETFRequiredScripts(){
            echo '<link rel="stylesheet" href="' . plugin_dir_url( __FILE__ ). 'admin/css/PreviewStyling.css">';
            include 'assets/preview-table-response.php';
            echo '<script src="https://code.highcharts.com/highcharts.js"></script>';
            echo '<script src="https://code.highcharts.com/modules/exporting.js"></script>';
            echo '<script src="https://code.highcharts.com/modules/export-data.js"></script>';
            echo '<script defer src=" ' . plugin_dir_url( __FILE__ ). 'admin/js/PreviewRen.js"> </script>';
            echo '<script defer src=" ' . plugin_dir_url( __FILE__ ). 'admin/js/ReqFuncs.js"> </script>';
        }

        /**
        * Display the new Custom Fields meta box
        */
        function displayCustomFields() {
            global $post;  
            if ( $post->post_type == "etfs" ){
                $this->includeETFRequiredScripts();
            } 
            ?>
            
            <div class="form-wrap">
                <?php wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
                foreach ( $this->customFields as $customField ) {
                    // Check scope
                    $scope = $customField[ 'scope' ];
                    $output = false;
                    foreach ( $scope as $scopeItem ) {
                        switch ( $scopeItem ) {
                            default: {
                                if ( $post->post_type == $scopeItem )
                                    $output = true;
                                break;
                            }
                        }
                        if ( $output ) break;
                    }
                    // Check capability
                    if ( !current_user_can( $customField['capability'], $post->ID ) )
                        $output = false;
                    // Output if allowed
                    if ( $output ) { ?>
                        <div class="form-field form-required">
                            <?php
                            switch ( $customField[ 'type' ] ) {
                                case "text": {
                                    // Plain text field
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                                    echo '<input required type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
                                    break;
                                }
                                case "url": {
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                                    echo '<input required type="url" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
                                    break;
                                }
                                case "select": {
                                    // Plain text field
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>
                                         <select name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '">
                                            <option value="A"> Subadvsior A </option>
                                            <option value="B"> Subadvsior B </option>
                                            <option value="C"> Subadvsior C </option>
                                            <option value="D"> Subadvsior D </option>
                                        </select>';
                                    break;
                                }
                                case "hidden": {
                                    echo '<input required type="hidden" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
                                    break;
                                }
                            }
                            ?>
                            
                            <?php if ( $customField[ 'description' ] ) echo '<p>' . $customField[ 'description' ] . '</p>'; ?>
                        </div>
                    <?php
                    }
                } ?>
                <div style="display: flex; gap: 30px;"> 
                    <div>
                        <button id="etf-sheet-sync-button" type="button" class="button button-primary button-large"> Preview Data </button></div>
                        <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>loadinganimation" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url( __FILE__ ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                        <p class="<?php echo $this->prefix ?>status-states"  style="display: none; color: green; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-success"> Preview Success </p>
                        <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed"> Error Occured </p>
                        <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed-url"> Enter Valid URL </p>
                        <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>fetch-load">  </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
        * Save the new Custom Fields values
        */
        function saveCustomFields( $post_id, $post ) {
            if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
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
        function renderProductPage() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/etf-product-page.php');
            return ob_get_clean();
        }
        
        // call shortcode [render-top-holders-table] to render product table
        function renderTopHolders() {
            ob_start();
            include( WP_PLUGIN_DIR . '/etfs-plugin/shortcodes/toptenshortcode.php');
            return ob_get_clean();
        }
    }
}

if(class_exists('ETFPlugin')){
    include 'alt_autoload.php';
    require_once 'assets/class-atts.php';
    require_once 'data/GoogleSheetProvider.php';
    require_once 'data/Pdf2Data.php';
    $etfPlugin = new ETFPlugin($custom_fields,$etfs_all,$etfs_unstructured);
}
    
register_activation_hook( __FILE__, array($etfPlugin, 'activiate') );

register_deactivation_hook( __FILE__, array($etfPlugin, 'deactivate') );


