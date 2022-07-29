<?php
namespace ETFsFundDocs;

class FundDocuments{

    var $prefix = 'ETF-Pre-';

    function __construct(){
        $this->init_fund_fields();
        add_action( 'admin_menu', array($this, 'set_fund_box' ) );

        add_shortcode('render-fund-docs-buttons', array($this, 'render_fund_buttons'));

        add_action( 'wp_ajax_add_new_fund_field', array($this, 'add_fund_field'));
        add_action( 'wp_ajax_del_new_fund_field', array($this, 'delete_fund_field'));
    }

    function init_fund_fields(){
        global $wpdb;
        $plugin_name_db_version = '1.0';
        $wp_table_name = $wpdb->prefix . "etfs_fund_docs_db"; 
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $wp_table_name (
                    Field_Name varchar(255) UNIQUE
                ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function add_fund_field(){
        $field_name = $_POST['field_name'];

        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_fund_docs_db"; 
        $wpdb->insert( 
            $wp_table_name, 
            array( 
                'Field_Name' => $field_name, 
            ) 
        );

        // handle dublicate namings

        wp_send_json(array('update' => 'success'));
    }

    function delete_fund_field(){
        $field_name = $_POST['field_name'];

        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_fund_docs_db"; 
        $wpdb->insert( 
            $wp_table_name, 
            array( 
                'Field_Name' => $field_name, 
            ) 
        );

        wp_send_json(array('update' => 'success'));
    }

    function create_custom_efts_fund_fields(){ 
        global $post;
        $feilds = self::get_all(); ?>
        <div style="display: flex; justify-content: flex-end;">
            <div class="button button-primary button-large" onclick="document.getElementById(`ETF-Pre-popup-underlay-new-fund-field`).style.display = `flex`"> Add New Document </div>
        </div>
        <div class="form-wrap-1">
            <?php wp_nonce_field( 'my-custom-fields-pdf', 'my-custom-fields-pdf_wpnonce', false, true );
                foreach ($feilds as $feild) { ?>
                    <div class="form-field form">
                        <label for="<?php echo $this->prefix . $feild['Field_Name'] ?>"> <b> <?php echo $feild['Field_Name'] ?> </b> </label>
                        <input type="url" name="<?php echo $this->prefix . $feild['Field_Name'] ?>" id="<?php echo $this->prefix . $feild['Field_Name'] ?>" value="<?php echo htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $feild[ 'Field_Name' ], true ) ) ?>" style="width: 100%;"  />
                    </div>
                <?php } ?> 
        </div> <?php 
    } 

    public static function get_all(){
        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_fund_docs_db"; 
        $config = $wpdb->get_results( "SELECT * FROM $wp_table_name", ARRAY_A);
        return $config;
    }

    public static function save_feilds($_post,$post_id){
        $feilds = self::get_all();
        foreach ( $feilds as $custom_field ) {
            if ( isset( $_post[ 'ETF-Pre-' . $custom_field['Field_Name'] ] ) && trim( $_post[ 'ETF-Pre-' . $custom_field['Field_Name'] ] ) ) {
                $value = $_post[ 'ETF-Pre-' . $custom_field['Field_Name'] ];
                update_post_meta( $post_id, 'ETF-Pre-' . $custom_field[ 'Field_Name' ], $value );
            } else {
                delete_post_meta( $post_id, 'ETF-Pre-' . $custom_field[ 'Field_Name' ] );
            }
        }
    }

    // Display the new Custom Fields (Fund Documents)
    function display_custom_fields_pdf() {
        global $post;  
        if ( $post->post_type == "etfs" ){
            $this->create_custom_efts_fund_fields(); ?>
            <div class="<?php echo $this->prefix ?>general-popup-underlay" id="<?php echo $this->prefix ?>popup-underlay-new-fund-field">
                <div style="width: 50%; height: 35%;" id="<?php echo $this->prefix ?>popup-container">
                    <div id="<?php echo $this->prefix ?>table-popup">
                        <div id="<?php echo $this->prefix ?>popup-topbar-container">  
                            <div style="font-weight: bold;" id="<?php echo $this->prefix ?>popup-title-container"> <h2 id="<?php echo $this->prefix ?>popup-title"> Add New Fund Document Field </h2> </div>
                            <button type="button" id="<?php echo $this->prefix ?>popup-close-button" onclick="closeForm()"> 
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </button>
                        </div>
                        <div style="overflow: auto;" id="<?php echo $this->prefix ?>popup-table-container">
                            <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="<?php echo $this->prefix ?>popup-table-inner-container">
                                <label for="<?php echo $this->prefix ?>new-fund-field-doc"><b> Fund document Name </b></label>
                                <input type="text" id="<?php echo $this->prefix ?>new-fund-field-doc" style="width: 100%;" />
                            </div>            
                        </div>
                        <div id="<?php echo $this->prefix ?>popup-bottombar-container">
                            <button class="button button-primary button-large" type="buttton" id="<?php echo $this->prefix ?>add-field-submit-button"> Add Field </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } 
    }

    // Create custom field (Fund Documents)
    function set_fund_box(){
        if ( function_exists( 'add_meta_box' ) ) {
            add_meta_box( 'my-custom-fields-pdf', 
            '<span> Fund Documents </span>',
            array($this, 'display_custom_fields_pdf' ), 
            'etfs', 
            'normal', 
            'high' );
        }
    }

    function render_fund_buttons() {
        ob_start();

        return ob_get_clean();
    }
}