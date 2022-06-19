<?php 
$sub_adv_id = get_post_meta( get_the_ID(), "ETF-Pre-sub-advisor-name", true );
$sub_adv = get_post( $sub_adv_id );
$sub_adv_title = $sub_adv->post_title; 
$sub_adv_content = $sub_adv->post_content; 
$sub_adv_thumbnail = get_the_post_thumbnail_url($sub_adv_id, 'full');
?>
​
<style>
​
    @media only screen and (max-width: 600px) {
        
        .cat-subadvisor{
            font-size: 12px!important;
            padding-top: 55px!important;
            margin: 0;
        }
​
        .title-subadvisor{
            font-size: 44px!important;
            padding-bottom: 15px!important;
            margin: 0!important;
        }
    
        .sub-feature-title,   .sub-feature-desc{
            font-size: 14px!important;
            line-height: 25px!important;
        }
​
        .spacer-img{
            display: none;
        }
​
        .sub-feature{
            display:none;
        }
    }
​
    @media only screen and (max-width: 1024px){
​
        .cat-subadvisor{
            text-align: left;
            color: #63d5d3;
            font-family: "Avenir Next", Sans-serif;
            font-size: 20px;
            font-weight: 500;
            line-height: 27px;
            letter-spacing: 4px;
            padding-top: 55px!important;
        }
​
        .title-subadvisor{
            color: #12223D;
            font-family: "Avenir Next", Sans-serif;
            font-size: 40px;
            font-weight: 600;
            line-height: 56px;
            margin: 0 0 37px 0;
        }
​
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
​
        .sub-feature-title{
            color: #0c233f;
            font-family: "Avenir Next", Sans-serif;
            font-size: 18px;
            font-weight: 600;
            line-height: 35px;
            margin-bottom: 10px;
            margin-top: 0;
        }
        
        .sub-feature-desc{
            color: #11223D;
            font-family: "Avenir Next", Sans-serif;
            font-size: 18px;
            font-weight: 400;
            line-height: 35px;
            margin-bottom: 35px;
        }
                
        #req-meet{
            display: none;
                visibility: 0;
            
        }
​
        .spacer-img{
            display: none;
        }
    }
​
    @media only screen and (min-width: 1025px) {
​
        .cat-subadvisor{
            text-align: left;
            color: #63d5d3;
            font-family: "Avenir Next", Sans-serif;
            font-size: 20px;
            font-weight: 500;
            line-height: 0;
            letter-spacing: 4px;
            margin-bottom: 18px;
        }
        
        .title-subadvisor{
            color: #12223D;
            font-family: "Avenir Next", Sans-serif;
            font-size: 40px;
            font-weight: 600;
            line-height: 56px;
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
            font-size: 20px;
            font-weight: 600;
            line-height: 35px;
            margin-bottom: 10px;
            margin-top: 0;
        }
        
        .sub-feature-desc{
            color: #11223D;
            font-family: "Avenir Next", Sans-serif;
            font-size: 20px;
            font-weight: 400;
            line-height: 35px;
            margin-bottom: 35px;
        }
    }
</style>
​
<section>
    <div class="row-subadvisor">
        <div class="col-1-subadvisor">
            <div class="col-1-subadvisor-widget">
                <div class="pnopspace">
                    <p class="cat-subadvisor">SUB-ADVISOR</p>
                    <p class="title-subadvisor"><?php echo $sub_adv_title ?></p>
                </div>
                <p class="sub-feature"><?php echo $sub_adv_content ?></p>   
                <a id="req-meet" href="/contact-us/">
                    <div id="banner-arrow" style="justify-content: normal; justify-items: baseline; display: flex;">
                        <p style="color: #0C233F; padding-right: 26px;font-family: 'Avenir Next' , sans-serif;margin: auto 0;font-size: 20px;letter-spacing: 4px;font-weight: 500; line-height: 27px;">REQUEST A MEETING</p>
                        <div style="cursor: pointer">    
                            <div class="bi bi-arrow-right" style="color: #63D5D3;margin-bottom: 12px;font-size: 30px"></div>
                        </div>
                    </div>
                </a>
        </div>
    </div>
​
    <div class="col-2-subadvisor">
        <div class="spacer-img" style="padding-top: 100px;" >
        </div>
        <img style="vertical-align: middle; display: inline-block; width: 100%;" src="<?php echo $sub_adv_thumbnail ?>">
    </div>
  </div>
</section>