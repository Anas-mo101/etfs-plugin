<?php

?>

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>

<style>

    .swiper {
        width: 100%;
        height: 100%;
        padding-top: 60px;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;

        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
      
    .button-prev{
        top: 2%;
        right: 35%!important;
        left: unset!important;
        font-size: 20px;
        color: #6ec1e4;
        position: absolute;
    }

    .swiper-pagination {
        top: 2% !important;
        right: 22% !important;
        width: auto !important;
        left: unset;
    }
      
    .button-next{
        top: 2%;
        right: 15%!important;
        left: unset!important;
        font-size: 20px;
        color: #6ec1e4;
        position: absolute;
    }

    .container-table-mobile{
        width: 100%;
    }

</style>

<body class="slide10">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">



<?php $top_holdings_string_json = get_post_meta( get_the_ID(), "ETF-Pre-top-holders-data", true );
$top_holdings_array = json_decode($top_holdings_string_json, true);
if(empty($top_holdings_array)){ 
    echo '</body> <div class="table-horizontal-row-null">  <p class="table-horizontal-row-text"> No Records </p> </div>';
}else{
    foreach ($top_holdings_array as $key => $holding) {  ?>
        <!-- Swiper -->
        <div class="swiper-slide">
            <div class="container-table-mobile">
                <table class="tb-mb-prfr" style="border-collapse: separate; border-spacing: 0 9px; margin: 0; display: table;">
                <tr>
                    <td class="table-ts-title2-mb pb dynamic-elementor-font-style-body-bold" style=" min-width: 150px; padding-left: 30px;" width="50%">% of Net Assets</td>
                    <td class="table-ts-in-mb pb dynamic-elementor-font-style-body" width="50%" style="padding-right: 30px;"><?php echo $holding["Weightings"]; ?></td>
                </tr>
                <tr>
                    <td class="table-ts-title2-mb pb dynamic-elementor-font-style-body-bold" style=" min-width: 150px; padding-left: 30px; " width="50%">Name</td>
                    <td class="table-ts-in-mb pb dynamic-elementor-font-style-body" width="50%" style="padding-right: 30px;"> <?php echo $holding["SecurityName"]; ?> </td>
                </tr>
                <tr>
                    <td class="table-ts-title2-mb pb dynamic-elementor-font-style-body-bold" style=" min-width: 150px; padding-left: 30px;" width="50%">Ticker</td>
                    <td class="table-ts-in-mb pb dynamic-elementor-font-style-body" width="50%" style="padding-right: 30px;"><?php echo $holding["StockTicker"]; ?> </td>
                </tr>
                    <td class="table-ts-title2-mb pb dynamic-elementor-font-style-body-bold" style=" min-width: 150px; padding-left: 30px;" width="50%">CUSIP</td>
                    <td class="table-ts-in-mb pb dynamic-elementor-font-style-body" width="50%" style="padding-right: 30px;"><?php echo $holding["CUSIP"]; ?> </td>
                </tr>
                <tr>
                    <td class="table-ts-title2-mb pb dynamic-elementor-font-style-body-bold" style=" min-width: 150px; padding-left: 30px;" width="50%">Shares Held</td>
                    <td class="table-ts-in-mb pb dynamic-elementor-font-style-body" width="50%" style="padding-right: 30px;"><?php echo $holding["Shares"]; ?></td>
                </tr>
                <tr>
                    <td class="table-ts-title2-mb pb dynamic-elementor-font-style-body-bold" style=" min-width: 150px; padding-left: 30px;" width="50%">Market Value</td>
                    <td class="table-ts-in-mb pb dynamic-elementor-font-style-body" width="50%" style="padding-right: 30px;"> <?php echo $holding["MarketValue"]; ?></td>
                </tr>
                </table>
            </div>
        </div>
    <?php } ?>

	</div>
        <div class="button-next">
            <i aria-hidden="true" class="eicon-chevron-right"></i>
        </div>
        <div class="button-prev">
            <i aria-hidden="true" class="eicon-chevron-left"></i>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" ></script>

    <!-- Initialize Swiper -->
    <script defer>
    var swiper = new Swiper(".mySwiper", {
        pagination: {
            el: ".swiper-pagination",
            type: "fraction",
        },
        navigation: {
            nextEl: ".button-next",
            prevEl: ".button-prev",
        },
    });
    </script>
  </body> <?php
}