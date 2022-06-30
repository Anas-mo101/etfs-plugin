<?php 

function create_custom_efts_fields($fields_to_create, $etfs_prefix, $wrap_no='', $nonce_val=''){ 
    global $post; ?>
    <div class="form-wrap-<?php echo $wrap_no;?>">
        <?php wp_nonce_field( $nonce_val, $nonce_val.'_wpnonce', false, true );
        foreach ( $fields_to_create as $customField ) {
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
            if ( $output ) {  ?>
                <div class="form-field form">
                    <?php
                    switch ( $customField[ 'type' ] ) {
                        case "text": {
                            // Plain text field
                            echo '<label for="' . $etfs_prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                            echo '<input type="text" name="' . $etfs_prefix . $customField[ 'name' ] . '" id="' . $etfs_prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ], true ) ) . '" style="width: 100%;" />';
                            break;
                        }
                        case "url": {
                            echo '<label for="' . $etfs_prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                            echo '<input type="url" name="' . $etfs_prefix . $customField[ 'name' ] . '" id="' . $etfs_prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ], true ) ) . '" style="width: 100%;"  />';
                            break;
                        }
                        case "g_url": {
                            $toggle_val = get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ] . '-toggle-data', true );
                            $toggle_input_visiblity_a = 'style="display: none;"';
                            $toggle_input_visiblity_b = '';
                            $toggle_input_visiblity_button = 'Upload file';
                            $toggle_input_visiblity_state = 'google';
                            if($toggle_val === "false"){
                                $toggle_input_visiblity_b = 'style="display: none;"';
                                $toggle_input_visiblity_a = '';
                                $toggle_input_visiblity_button = 'Google Sheet';
                                $toggle_input_visiblity_state = 'upload';
                            }
                            echo '<label for="' . $etfs_prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                            echo '<div style="display: flex; gap: 10px;"> <input '. $toggle_input_visiblity_b .' type="url" name="' . $etfs_prefix . $customField[ 'name' ] . '" id="' . $etfs_prefix . $customField[ 'name' ] . '-google-link" value="' . htmlspecialchars( get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ], true ) ) . '" style="width: 100%;"  />';
                            echo '<input readonly '. $toggle_input_visiblity_a .' type="url" name="' . $etfs_prefix . $customField[ 'name' ] . '-" id="' . $etfs_prefix . $customField[ 'name' ] . '-upload-link" value="' . htmlspecialchars( get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ], true ) ) . '" />';
                            if ( isset( $_POST['image_attachment_id'] ) ) : update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) ); endif;  wp_enqueue_media(); 
                            ?> <button <?php echo $toggle_input_visiblity_a ?> id="<?php echo $etfs_prefix . $customField[ 'name' ] ?>-file-upload" type="button" onclick="mediaFileSelector('<?php echo $customField[ 'name' ] ?>',true,['application/csv', 'text/csv'])" class="button button-primary button-large"> Select file </button>
                            <p style="margin: auto 0;">OR</p>
                            <button data-state="<?php echo $toggle_input_visiblity_state ?>" id="<?php echo $etfs_prefix . $customField[ 'name' ]?>-toggle-file-option" type="button" onclick="toggle_between_gs_and_up('<?php echo $customField[ 'name' ]?>')" class="button button-primary button-large"> <?php echo $toggle_input_visiblity_button ?> </button>
                            </div> <?php
                            break;
                        }
                        case "pdf_url": { 
                            echo '<label for="' . $etfs_prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                            echo '<div style="display: flex; gap: 10px;"> <input readonly type="url" name="' . $etfs_prefix . $customField[ 'name' ] . '" id="' . $etfs_prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ], true ) ) . '" />';
                            ?> <form style="display: flex; gap: 10px;" method='post'>
                                <button id="<?php echo $etfs_prefix?>pdf_upload" type="button" onclick="mediaFileSelector('<?php echo $customField[ 'name' ] ?>',false,'application/pdf')" class="button button-primary button-large"> Select file </button>
                            </form> </div> <?php
                            break;
                        }
                        case "select": {
                            $query = new WP_Query(array( 'post_type' => 'subadvisors','numberposts' => -1));
                            $select = get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ], true);
                            echo '<label for="' . $etfs_prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>
                                <select name="' . $etfs_prefix . $customField[ 'name' ] . '" id="' . $etfs_prefix . $customField[ 'name' ] . '">';
                                    $_select = ($select == 'none') ? 'selected' : '';
                                    echo '<option ' .  $_select . ' value="none"> none </option>';
                                    while ($query->have_posts()) {
                                        $query->the_post();
                                        $post_title = get_the_title();
                                        $post_id = get_the_ID();
                                        $_select = ($select == $post_id) ? 'selected' : '';
                                        echo '<option ' . $_select . ' value="' . $post_id . '"> '. $post_title .' </option>';
                                    }
                                echo '</select>';
                            wp_reset_query();
                            break;
                        }
                        case "hidden": {
                            echo '<input type="hidden" name="' . $etfs_prefix . $customField[ 'name' ] . '" id="' . $etfs_prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $etfs_prefix . $customField[ 'name' ], true ) ) . '" />';
                            break;
                        }
                    } 
                    if ( $customField[ 'description' ] ) echo '<p>' . $customField[ 'description' ] . '</p>'; ?>
                </div>
            <?php
            }
        } ?>
    </div>
<?php } ?>



