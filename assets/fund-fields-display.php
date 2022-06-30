<?php ?>
<div class="form-wrap-2">
    <?php wp_nonce_field( 'my-custom-fields-pdf', 'my-custom-fields-pdf_wpnonce', false, true );
    foreach ( $this->customFields as $customField ) {
        //Check scope
        $scope1 = $customField[ 'scope' ];
        $output1 = false;
        foreach ( $scope1 as $scopeItem1 ) {
            switch ( $scopeItem1 ) {
                default: {
                    if ( $post->post_type == $scopeItem1 )
                     $output1 = true;
                    break;
                 }
             }
             if ( $output1 ) break;
         }
        //Check capability
         if ( !current_user_can( $customField['capability'], $post->ID ) )
             $output1 = false;
        //Output if allowed
        if ( $output1 ) {  ?>
            <div class="form-field form-required">
                <?php
                switch ( $customField[ 'type' ] ) {
                    case "pdf_url_fd": { 
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

                }
                ?>
                
            </div>
        <?php
        }
     }?>
<?php ?>