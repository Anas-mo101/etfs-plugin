<?php  ?>
<h3 style="margin: 10px 0 30px 0;">Frontpage ETFs Layout</h3>
<div class="drop">
    <div class="drop_container" id="drop-items">
    <?php
        $fp_options_layout_raw = get_option('front-page-box-layout');
        $fp_options_layout_ = json_decode($fp_options_layout_raw, true);

        $listed_etfs = array();
        $category_query_args = array(
            'post_type' => 'etfs',
            'posts_per_page' => 999999 
        );
        $unst_posts = new WP_Query( $category_query_args );
        if ( $unst_posts->have_posts() ) {
            $_temp_sort = array(
                array(
                'id' => '0',
                'order' => '*',
                'display' => 'false',
                'title' => 'Structured ETFs',
                'desc' => 'Janz to Decz'
                )
            );
            while ( $unst_posts->have_posts() ) {
                $unst_posts->the_post(); 
                $categories = get_the_category();
                if ( empty( $categories ) ) { continue; }
                foreach ($categories as $cate) {
                    if ( ! isset( $cate->slug ) || $cate->slug !== 'unstructured-etfs') {
                        continue;
                    }
                } 
                $_temp =  array(
                    'id' => get_the_ID(),
                    'order' => '*',
                    'display' => 'false',
                    'title' => get_the_title(),
                    'desc' => substr(get_post_meta( get_the_ID(), 'ETF-Pre-etf-full-name', true ), 0, 40)
                );
                
                $_temp_sort[] = $_temp;
            }

            $_display_sort = array();
            if(is_array($fp_options_layout_)){
                foreach ($_temp_sort as $t_s_) {
                    $_temp_ = null;
                    foreach ($fp_options_layout_ as $element) {
                        if($t_s_['id'] == $element['id']){
                            $t_s_['order'] = $element['order'];
                            $t_s_['display'] = $element['display'];
                            $_temp_ = $t_s_;
                        }
                    } 
                    if($_temp_ === null){ continue; }
                    $_display_sort[] = $_temp_;
                }

                foreach ($_temp_sort as $b) {   // adds new uns etfs
                    if (array_search($b['id'], array_column($fp_options_layout_, 'id')) === FALSE) {
                        $_display_sort[] = $b;
                    }      
                }
            }else{
                $_display_sort =  $_temp_sort;
            }

            $order = array_column($_display_sort, 'order');
            array_multisort($order, SORT_ASC, SORT_NUMERIC, $_display_sort);

            foreach ($_display_sort as $element) { ?>
                <div id="<?php echo $element['id']; ?>" class="drop_card">
                    <div class="drop_data">
                        <div>
                            <h4 class="drop_name feilds-label-style"> <a style="text-decoration: none; color: black;" target="_blank" href="<?php echo esc_url( admin_url( 'post.php?post=' . $element['id'] . '&action=edit' ) ) ?>"> <?php echo $element['title']; ?> </a> </h4>
                            <p style="margin: 0;" class="drop_profession"> <?php echo $element['desc']; ?> </p>
                        </div>
                    </div>
                    <div>
                        <input type="checkbox" class="fp-toggle-button" <?php echo ($element['display'] === 'true') ? 'checked' : ''; ?> id="vis-<?php echo $element['id']; ?>">
                    </div>
                </div>
            <?php }

        }else{ ?>
            <div class="drop_card">
                <div class="drop_data">
                    <div>
                        <h4 class="drop_name feilds-label-style"> No Un-Structured ETFs </h4> 
                        <p style="margin: 0;" class="drop_profession"> <a target="_blank" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=etfs' ) ) ?>"> Add ETFs </a> </p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div class="row-margin ">
    <div class="btn-row-margin">
        <a class="btn btn-success btn-lg fp-save-button">Save</a>
        <a class="fp-cancel-button btn btn-danger btn-lg">Cancel</a>
        <a class="btn btn-primary btn-lg fp-edit-button">Edit</a>
        <div class="btn fp-status-states" style="display: none; margin: auto 0;" id="ETFs-Pre-loadinganimation-2" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname( __FILE__ ) ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
        <p style="margin: auto 0; cursor: auto;" class="btn" id="ETF-Pre-creds-state-2">  </p>
    </div>
</div>