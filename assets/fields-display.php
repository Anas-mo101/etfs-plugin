<?php?>
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
                        echo '<input required type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" style="width: 100%;" />';
                        break;
                    }
                    case "url": {
                        echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                        echo '<input required type="url" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" style="width: 100%;"  />';
                        break;
                    }
                    case "g_url": {
                        $toggle_val = get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ] . '-toggle-data', true );
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

                        echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                        echo '<div style="display: flex; gap: 10px;"> <input '. $toggle_input_visiblity_b .' required type="url" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '-google-link" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" style="width: 100%;"  />';
                        echo '<input readonly '. $toggle_input_visiblity_a .' required type="url" name="' . $this->prefix . $customField[ 'name' ] . '-" id="' . $this->prefix . $customField[ 'name' ] . '-upload-link" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
                        if ( isset( $_POST['image_attachment_id'] ) ) : update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) ); endif;  wp_enqueue_media(); 
                        ?> <button <?php echo $toggle_input_visiblity_a ?> id="<?php echo $this->prefix . $customField[ 'name' ] ?>-file-upload" type="button" onclick="mediaFileSelector('<?php echo $customField[ 'name' ] ?>',true,['application/csv', 'text/csv'])" class="button button-primary button-large"> Select file </button>
                        <p style="margin: auto 0;">OR</p>
                        <button data-state="<?php echo $toggle_input_visiblity_state ?>" id="<?php echo $this->prefix . $customField[ 'name' ]?>-toggle-file-option" type="button" onclick="toggle_between_gs_and_up('<?php echo $customField[ 'name' ]?>')" class="button button-primary button-large"> <?php echo $toggle_input_visiblity_button ?> </button>
                        </div> <?php
                        break;
                    }
                    case "pdf_url": { 
                        echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                        echo '<div style="display: flex; gap: 10px;"> <input readonly required type="url" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
                        if ( isset( $_POST['image_attachment_id'] ) ) : update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) ); endif;
                        wp_enqueue_media(); 
                        ?> <form style="display: flex; gap: 10px;" method='post'>
                            <button id="<?php echo $this->prefix?>pdf_upload" type="button" onclick="mediaFileSelector('<?php echo $customField[ 'name' ] ?>',false,'application/pdf')" class="button button-primary button-large"> Select file </button>
                            <input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
                        </form> </div> <?php
                        break;
                    }
                    case "select": {
                        // Plain text field
                        $query = new WP_Query(array( 'post_type' => 'subadvisors','numberposts' => -1));
                        $select = get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true);
                        echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>
                            <select name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '">';
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
            <button id="etf-sheet-sync-button" type="button" class="button button-primary button-large"> Preview </button></div>
            <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>loadinganimation" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname( __FILE__ ) ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
            <p class="<?php echo $this->prefix ?>status-states"  style="display: none; color: green; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-success"> Preview Success </p>
            <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed"> Error Occured </p>
            <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed-url"> Enter Valid URL </p>
            <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>fetch-load">  </div>
        </div>
    </div>
</div>