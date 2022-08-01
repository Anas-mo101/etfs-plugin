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
                    Field_Name varchar(255) UNIQUE,
                    Field_ID varchar(255) NOT NULL DEFAULT ''
                ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function add_fund_field(){
        $field_name = sanitize_text_field( $_POST['field_name']);
        $field_id = preg_replace('/\s+/', '-', strtolower($field_name));

        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_fund_docs_db"; 
        $res = $wpdb->insert( 
            $wp_table_name, 
            array( 
                'Field_Name' => $field_name,
                'Field_ID' => $field_id,
            ) 
        );

        if($res === false){
            \ETFsNoticeHandler\Notice_Handler::add_error('Can not add field with dublicate name');
        }

        wp_send_json(array('update' => 'success'));
    }

    function delete_fund_field(){
        $field_id = sanitize_text_field($_POST['field_id']);

        if($field_id === ''){
            \ETFsNoticeHandler\Notice_Handler::add_error('Error upon field deletion, try again later.');
        }

        global $wpdb;
        $wp_table_name = $wpdb->prefix . "etfs_fund_docs_db"; 
        $res = $wpdb->delete( $wp_table_name, array( 'Field_ID' => $field_id ) );

        if($res === false){
            \ETFsNoticeHandler\Notice_Handler::add_error('Error upon field deletion, try again later.');
        }

        wp_send_json(array('update' => 'success'));
    }

    function create_custom_efts_fund_fields(){ 
        global $post;
        $feilds = self::get_all(); ?>
        <div class="form-wrap-1">
            <?php wp_nonce_field( 'my-custom-fields-pdf', 'my-custom-fields-pdf_wpnonce', false, true );
                foreach ($feilds as $feild) { ?>
                    <div class="form-field form">
                        <label for="<?php echo $this->prefix . $feild['Field_Name'] ?>"> <b> <?php echo $feild['Field_Name'] ?> </b> </label>
                        <div style="display: flex; justify-content: space-between; gap: 15px;">
                            <input type="url" name="<?php echo $this->prefix . $feild['Field_ID'] ?>" id="<?php echo $this->prefix . $feild['Field_ID'] ?>" value="<?php echo htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $feild[ 'Field_ID' ], true ) ) ?>" style="width: 100%;"  />
                            <button style="display: grid;width: 5%;align-items: center;justify-content: center;" type="button" class="<?php echo $this->prefix; ?>delete-field-doc" data-fieldid="<?php echo $feild['Field_ID']; ?>"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php } ?> 
        </div> 
        <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap:10px;">
            <div class="button button-primary button-large" onclick="document.getElementById(`ETF-Pre-popup-underlay-new-fund-field`).style.display = `flex`"> Add New Document </div>
        </div>            
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <div class="button button-primary button-large"> Update </div>
        </div>
        <?php 
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
            if ( isset( $_post[ 'ETF-Pre-' . $custom_field['Field_ID'] ] ) && trim( $_post[ 'ETF-Pre-' . $custom_field['Field_ID'] ] ) ) {
                $value = $_post[ 'ETF-Pre-' . $custom_field['Field_ID'] ];
                update_post_meta( $post_id, 'ETF-Pre-' . $custom_field[ 'Field_ID' ], $value );
            } else {
                delete_post_meta( $post_id, 'ETF-Pre-' . $custom_field[ 'Field_ID' ] );
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
                                <div id="<?php echo $this->prefix ?>new-fund-field-doc-status">  </div>
                            </div>            
                        </div>
                        <div id="<?php echo $this->prefix ?>popup-bottombar-container">
                            <button class="button button-primary button-large" type="buttton" id="<?php echo $this->prefix ?>add-field-submit-button"> Add Field </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="<?php echo $this->prefix ?>general-popup-underlay" id="<?php echo $this->prefix ?>popup-underlay-del-fund-field">
                <div style="width: 50%; height: 35%;" id="<?php echo $this->prefix ?>popup-container">
                    <div id="<?php echo $this->prefix ?>table-popup">
                        <div style="flex-direction: row-reverse;" id="<?php echo $this->prefix ?>popup-topbar-container">  
                            <button type="button" id="<?php echo $this->prefix ?>popup-close-button" onclick="closeForm()"> 
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </button>
                        </div>
                        <div style="overflow: auto;" id="<?php echo $this->prefix ?>popup-table-container">
                            <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="<?php echo $this->prefix ?>popup-table-inner-container">
                                <label for="<?php echo $this->prefix ?>new-fund-field-doc"><b> Delete this feild ? </b></label>
                            </div>            
                        </div>
                        <div id="<?php echo $this->prefix ?>popup-bottombar-container">
                            <button class="button button-primary button-large" type="buttton" id="<?php echo $this->prefix ?>del-field-submit-button"> Delete </button>
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
        global $post;
        if(!is_object($post)) return;
        $buttons = self::get_all(); ?>
        <style>
            @media only screen and (max-width: 1024px) {
                .button-dw-fund-doc a{
                    height: 56px;
                    min-height: unset!important;
                    font-size: 15px!important;
                    font-weight: 600!important;
                    align-items: center;
                    display: inline-grid;
                    padding: 0 30px 0 30px!important;
                }

                .button-dw-fund-doc{
                    height: 56px;
                }

                .button-dw-fund-doc svg{
                    width: 1em!important;
                    height: auto;
                    font-size: 17px;
                }
            }

            .button-dw-fund-doc a{
                width: 100%;
                min-height: 80px;
                background-color: #ffffff;
                border-radius: 0;
                color: #12223D;
                text-align: left;
                color: #12223D;
                font-family: "Avenir Next", Sans-serif;
                font-size: 17px;
                font-weight: bold;
                line-height: 50px;
                    align-items: center;
                display: inline-grid;
                padding: 0 40px 0 75px;
            }

            .button-dw-fund-doc svg{
                width: 30px;
            }

            .button-dw-fund-doc  .elementor-button-icon {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
        <?php foreach ( $buttons as $button ) { 
            if( get_post_meta( $post->ID, $this->prefix . $button[ 'Field_ID' ], true ) === '') continue; ?>
            
            <div class="button-dw-fund-doc">
                <a href="<?php echo esc_url( get_post_meta( $post->ID, $this->prefix . $button[ 'Field_ID' ], true ) ); ?>" download="Fact Sheet" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                    <span class="elementor-button-content-wrapper">
                        <span class="elementor-button-icon elementor-align-icon-right">
                            <svg xmlns="http://www.w3.org/2000/svg" id="Download_Arrow" width="34" height="34" viewBox="0 0 34 34">
                                <path id="Shape" d="M0,0H34V34H0Z" fill="none" fill-rule="evenodd"></path>
                                <rect id="Rectangle" width="2" height="20" rx="1" transform="translate(16.099 4)" fill="#63d5d3" opacity="0.3"></rect>
                                <path id="Path-94" d="M12.418-33.585a1.417,1.417,0,0,0-2,0,1.417,1.417,0,0,0,0,2l8.5,8.5a1.417,1.417,0,0,0,1.959.043l8.5-7.792a1.417,1.417,0,0,0,.087-2,1.417,1.417,0,0,0-2-.087l-7.5,6.875Z" transform="translate(-2.917 46.75)" fill="#63d5d3"></path>
                                <rect id="Rectangle-199-Copy" width="26" height="3" rx="1.5" transform="translate(4.099 27)" fill="#63d5d3" opacity="0.3"></rect>
                            </svg>			
                        </span>
                        <span class="elementor-button-text"> <?php echo htmlspecialchars( $button[ 'Field_Name' ] ); ?> </span>
                    </span>
                </a>
		    </div>
        <?php }
        return ob_get_clean();
    }
}