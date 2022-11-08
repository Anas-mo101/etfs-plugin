<?php 
$sub_adv_id = get_post_meta( get_the_ID(), "ETF-Pre-sub-advisor-name", true );
$sub_adv = get_post( $sub_adv_id );
$sub_adv_title; $sub_adv_content; $sub_adv_thumbnail;
$show_sub_adv_if_true = false; 

if($sub_adv_id !== 'none'){
    $sub_adv_title = $sub_adv->post_title; 
    $sub_adv_content = $sub_adv->post_content; 
    $sub_adv_thumbnail = get_the_post_thumbnail_url($sub_adv_id, 'full');
    $show_sub_adv_if_true = true;
}?>

<?php if($show_sub_adv_if_true === true){ ?>
<style>
    @media only screen and (max-width: 600px) {
        
        .cat-subadvisor{
            padding-top: 55px!important;
            margin: 0;
        }

        .title-subadvisor{
            padding-bottom: 15px!important;
            margin: 0!important;
        }
    
        .sub-feature-title, .sub-feature-desc{
            font-size: 14px!important; 
            line-height: 25px!important;
        }
        .spacer-img{
            display: none;
        }

        .sub-feature{
            display:none;
        }
    }

    @media only screen and (max-width: 1024px){
        .cat-subadvisor{
            text-align: left;
            color: #63d5d3;
            letter-spacing: 4px;
            padding-top: 55px!important;
        }
        .title-subadvisor{
            color: #12223D;
            margin: 0 0 37px 0;
        }
        .row-subadvisor{
            display: flex;
            margin-right: auto;
            margin-left: auto;
            position: relative;
            flex-direction: column-reverse;
        }
        
        .col-1-subadvisor{
            width: 100%;
        }
        
        .col-2-subadvisor{
            width: 100%;
        }
        
        .col-1-subadvisor-widget{
            padding: 0px;
        }
        .sub-feature-title{
            color: #0c233f;
            font-family: "Avenir Next", Sans-serif;
            font-size: 16px;
            font-weight: 600;
            line-height: 35px;
            margin-bottom: 10px;
            margin-top: 0;
        }
        
        .sub-feature-desc{
            color: #11223D;
            font-family: "Avenir Next", Sans-serif;
            font-size: 16px;
            font-weight: 400;
            line-height: 35px;
            margin-bottom: 35px;
        }
            

        .spacer-img{
            display: none;
        }
    }
    @media only screen and (min-width: 1025px) {
        .cat-subadvisor{
            text-align: left;
            color: #63d5d3;
            letter-spacing: 4px;
            margin-bottom: 18px;
        }

        .center-image {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        
        .title-subadvisor{
            color: #12223D;
            margin: 0;
        }
        
        .row-subadvisor{
            display: flex;
            margin-right: auto;
            margin-left: auto;
            position: relative;
        }
        
        .col-1-subadvisor{
            width: 56%;
        }
        
        .col-2-subadvisor{
            width: 44%;
        }
        
        .col-1-subadvisor-widget{
            padding: 0px 69px 0px 0px;
        }
        
        .sub-feature-title{
            color: #0c233f;
            font-family: "Avenir Next", Sans-serif;
            font-size: 16px;
            font-weight: 600;
            line-height: 35px;
            margin-bottom: 10px;
            margin-top: 0;
        }
        
        .sub-feature-desc{
            color: #11223D; font-family: "Avenir Next", Sans-serif; font-size: 16px;
            font-weight: 400; line-height: 35px;  margin-bottom: 35px;
        }
    }
</style>

<section>
    <div class="row-subadvisor">
        <div class="col-1-subadvisor">
            <div class="col-1-subadvisor-widget">
                <div class="pnopspace">
                    <p class="cat-subadvisor fade-in-animation dynamic-elementor-font-style-sub-heading-2">SUB-ADVISOR</p>
                    <p class="title-subadvisor fade-in-animation dynamic-elementor-font-style-heading-5"><?php echo $sub_adv_title ?></p>
                </div>
                <p class="sub-feature dynamic-elementor-font-style-body"> <?php echo $sub_adv_content ?> </p>   
                <a id="req-meet" href="/contact-us/">
                    <div class="fade-in-up-animation sub-adv" id="" style="justify-content: center; justify-items: baseline; display: flex; gap: 20px;">
                        <p class="dynamic-elementor-font-style-button-text" style="color: #63d5d3; padding-right: 26px; margin: auto 0px;">REQUEST A MEETING</p>
                        <div style="cursor: pointer">    
                            <div class="bi bi-arrow-right" style="color:#63d5d3;margin-bottom: 9px;font-size: 25px"></div>
                        </div>
                    </div>
                </a>
        </div>
    </div>
    <div style="position: relative;" class="col-2-subadvisor">
        <div class="center-image">
            <img class="fade-in-animation" style="vertical-align: middle; display: inline-block; width: 100%;" src="<?php echo $sub_adv_thumbnail ?>">
        </div>
    </div>
  </div>
</section>
<?php } ?>