<?php

$fp_options_layout_raw = get_option('front-page-box-layout');
$fp_options_layout_ = json_decode($fp_options_layout_raw, true);

function set_box_($id,$type,$desc){
    $post_ = get_post( $id );
    $post_title_ = "";
    $post_link_ = "";
    $post_content_ = ""; 
    $sub_id = "";
    $sub_name = "";
    $post_full_name = "";

    if($id != 0){
        $post_title_ = $post_->post_title;
        $post_link_ = get_permalink($id);
        $post_content_ = $post_->post_content; 
        $sub_id = get_post_meta( $id, 'ETF-Pre-sub-advisor-name', true );
        $sub_name = get_the_title( $sub_id );
        $post_full_name = get_post_meta( $id, 'ETF-Pre-etf-full-name', true ) ? get_post_meta( $id, 'ETF-Pre-etf-full-name', true ) . ' ETF' : '';
    }else{
        $fp_structured_title = get_option('frontbox-structured-title');
        $fp_structured_subtitle = get_option('frontbox-structured-subtitle');

        $post_title_ = $fp_structured_title ? $fp_structured_title : '';
        $post_link_ = './products';
        $sub_id = get_post_meta( custom_get_page_by_title( 'JANZ', OBJECT, 'etfs' )->ID, 'ETF-Pre-sub-advisor-name', true );
        $sub_name = get_the_title( $sub_id );
        $post_full_name = $fp_structured_subtitle ? $fp_structured_subtitle : '';
    } ?> 
        
    <div class="trueshare-item-box">
        <div class="trueshare-box-titles">
            <div class="trueshare-box-title-one dynamic-elementor-font-style-sub-heading-3">
                <?php echo $type; ?>
            </div>
            <div class="trueshare-box-title-two dynamic-elementor-font-style-heading-4">
                <?php echo $post_title_; ?>
            </div>
            <div class="trueshare-box-title-three dynamic-elementor-font-style-sub-heading-3">
                <?php echo $post_full_name; ?>
            </div>
        </div>
        <div style="margin-bottom: 20px; height: 45%;">
            <p class="dynamic-elementor-font-style-body"> <?php echo $desc; ?> </p>
        </div>
        <?php if($sub_id !== 'none'){ ?>
            <div>
                <p class="trueshare-box-subadv dynamic-elementor-font-style-sub-heading-3"> SUBADVISOR: </p>
                <p class="trueshare-box-subadvname dynamic-elementor-font-style-sub-heading-3"> 
                    <?php echo $sub_name; ?>
                </p>
            </div>
        <?php } ?>
        <div class="trueshare-learn-more dynamic-elementor-font-style-button-text" onclick="location.href='<?php echo $post_link_; ?>'">
            <p> LEARN MORE </p>
        </div>
    </div> <?php
}

?>
<style>

.trueshare-item-box{background-color: white;padding: 44px 55px 42px 50px;margin: 20px;width: 655px;height: auto;box-shadow: 0px 3px 30px #00000012;}
.trueshare-box-title-one{margin-bottom: -5px;color: #949494;}
.trueshare-box-title-two{margin-bottom: 0px;color: #0c233f;}
.trueshare-box-title-three, .trueshare-box-subadvname{color: #0c233f;}
.trueshare-box-body{font-size: 17px;line-height: 35px;color: #0c233f;height: 50%}
.trueshare-box-subadv{margin-bottom: 2px;color: #9c9c9c;}
.trueshare-learn-more{font-size: 17px;color: #63d5d3;cursor: pointer;font-weight: 600;margin-bottom: -10px;}
.trueshare-box-first-row{display: flex;animation: slide-to-left 1s ease-in-out}
.trueshare-box-sec-row{display: flex;justify-content: flex-end;animation: 1s slide-to-right ease-in-out;animation-delay: 200ms}
.trueshare-box-third-row{margin: 0 auto;display: flex;justify-content: center;animation: slide-to-left 1s ease-in-out;animation-delay: 300ms}
.trueshare-box-titles{margin-bottom: 10px}
.trueshare-main-box-container{margin: 0 auto;width: 1600px}

@media screen and (max-width: 1600px){
    .trueshare-main-box-container{width: auto;padding: 0 7em}
    .trueshare-item-box{padding: 42px 50px;margin: 10px;width: auto}
    .trueshare-box-body{height: auto}
}

@media screen and (max-width: 991px){
    .trueshare-item-box{padding: 30px;margin: 10px;width: auto}
    .trueshare-box-title-one{margin-bottom: -3px;}
    .trueshare-box-title-two{margin-bottom: -3px;}
    .trueshare-box-body{font-size: 14px;line-height: 20px}
    .trueshare-box-subadv{margin-bottom: 0px;}
    .trueshare-learn-more{margin-bottom: -10px}
    .trueshare-box-titles{margin-bottom: 10px}
    .trueshare-main-box-container{padding: unset;width: auto}
    .trueshare-box-first-row,.trueshare-box-sec-row{display: block}
    .trueshare-box-third-row{display: block;justify-content: left}
}

@media screen and (max-width: 480px){

    .trueshare-item-box{padding: 20px;margin: 20px;width: auto}
    .trueshare-box-title-one{margin-bottom: -2px;}
    .trueshare-box-title-two{margin-bottom: 2px;}
    .trueshare-box-body{line-height: 20px}
    .trueshare-box-subadv{margin-bottom: 2px;}
    .trueshare-learn-more{margin-bottom: -10px}
    .trueshare-box-first-row,.trueshare-box-sec-row,.trueshare-box-third-row{display: block}
    .trueshare-main-box-container{padding: 0 0.5em;width: auto}
}
</style>

<div class="trueshare-main-box-container">
    <?php 
        if(is_array($fp_options_layout_)){

            foreach ($fp_options_layout_ as $key => $value) {
                if ($fp_options_layout_[$key]['display'] === 'false') {
                    unset($fp_options_layout_[$key]);
                }
            }

            $fp_options_layout_ = array_values(array_filter($fp_options_layout_));
            
            $fp_count = 0;
            $count = count($fp_options_layout_);
            $odd_ = $count%2 === 1 ? true : false; 
            $num_of_rows = ceil($count/2);
            $one_or_two = 'sec';
            while($num_of_rows--){  

                if($count == 1 || $count - $fp_count == 1){ ?>
                    <div class="trueshare-box-third-row">
                        <?php 
                            if(array_key_exists($fp_count,$fp_options_layout_)){
                                set_box_(
                                    $fp_options_layout_[$fp_count]['id'],
                                    $fp_options_layout_[$fp_count]['type'],
                                    $fp_options_layout_[$fp_count]['details'],
                                );
                                $fp_count++;
                                $num_of_rows = 0;
                            }
                        ?>
                    </div> <?php 
                }elseif($count - $fp_count >= 1){ ?>
                    <div class="trueshare-box-<?php echo $one_or_two = ($one_or_two === 'first') ? 'sec' : 'first' ?>-row">
                        <?php 
                            $c = 2;
                            while ($c--) {
                                if(array_key_exists($fp_count,$fp_options_layout_)){
                                    set_box_(
                                        $fp_options_layout_[$fp_count]['id'],
                                        $fp_options_layout_[$fp_count]['type'],
                                        $fp_options_layout_[$fp_count]['details'],
                                    );
                                }else{
                                    $c++;
                                }
                                $fp_count++;
                            }
                        ?>
                    </div> <?php
                }
            }
        }
    ?> 
</div>