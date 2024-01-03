<?php ?>
<div  id ="table-pd" class="table-responsive">
    <table class="table-ts" style="border-collapse: separate; border-spacing: 0 17px; margin: 0;  overflow-x: auto;">
        <tr>
            <th style="text-align: center; border:none;"class="dynamic-elementor-font-style-body-bold">Ticker</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Name</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Series</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Fund Price</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Period Returns</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Index</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Index Period Returns</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Est. Upside Market Participation Rate</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Remaining Buffer</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">ETF Downside to Buffer</th>
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">S&P Downside to Floor of Buffer</th>
            <th  style="border:none;" class="dynamic-elementor-font-style-body-bold">Remaining Outcome Period</th>
            <th style="text-align: center; border: none;" class="dynamic-elementor-font-style-body-bold">Prospectus</th>
        </tr>
        
        <?php
            
            foreach ($this->etfs_structured as $etf) {
                $post_to_diplay = get_page_by_title( $etf, OBJECT, 'etfs' );
                $long_name = (new Pdf2Data())->get_etfs_full_pre($etf);
                $daysleft = (int) get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-remaining-outcome-data', true); 
                
                ?>  <tr>
                    <td class="table-ts-in pb bg-dark"><a href="/etfs/<?php echo strtolower($etf)?>/"> <?php echo $etf?> </a></td>
                    <td class="table-ts-in pb"><a style= "color:#12223D;" href="/etfs/<?php echo strtolower($etf)?>/" > <?php echo $etf?> </a></td>
                    <td class="table-ts-in pb"> <?php echo $long_name ?> </td>
                    <td class="table-ts-in pb"> $<?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-na-v-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-period-return-data', true); ?> </td>  
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-product-index-data', true); ?>  </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-spx-return-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-product-participation-rate-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-remaining-buffer-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-downside-buffer-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-floor-of-buffer-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo ($daysleft > 1) ? $daysleft . ' days' : $daysleft . ' day'; ?> </td>
                    <td class="text-center table-ts-in pb p-link">
						<a href="<?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-pdf-prospectus', true); ?>" class="bt-download-prospectus" download>
                    </td>
                </tr>
            <?php } ?>
    </table>               
</div>

<?php

$option_key = "structured_outcome_etfs_product_table";

if( $download_url = get_option($option_key)){ ?>

    <style>
        #download_button_DIV_1 {
            block-size: 61px;
            border-block-end-color: rgb(51, 51, 51);
            border-block-start-color: rgb(51, 51, 51);
            border-bottom-color: rgb(51, 51, 51);
            border-inline-end-color: rgb(51, 51, 51);
            border-inline-start-color: rgb(51, 51, 51);
            border-left-color: rgb(51, 51, 51);
            border-right-color: rgb(51, 51, 51);
            border-top-color: rgb(51, 51, 51);
            box-sizing: border-box;
            caret-color: rgb(51, 51, 51);
            color: rgb(51, 51, 51);
            column-rule-color: rgb(51, 51, 51);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 16px;
            height: 61px;
            inline-size: 1575px;
            line-height: 24px;
            outline-color: rgb(51, 51, 51);
            perspective-origin: 787.5px 30.5px;
            text-align: left;
            text-decoration: none solid rgb(51, 51, 51);
            text-decoration-color: rgb(51, 51, 51);
            text-emphasis-color: rgb(51, 51, 51);
            text-size-adjust: 100%;
            transform-origin: 787.5px 30.5px;
            width: 1575px;
            border: 0px none rgb(51, 51, 51);
            border-top: 0px none rgb(51, 51, 51);
            border-right: 0px none rgb(51, 51, 51);
            border-bottom: 0px none rgb(51, 51, 51);
            border-left: 0px none rgb(51, 51, 51);
            border-color: rgb(51, 51, 51);
            font: 16px / 24px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            outline: rgb(51, 51, 51) none 0px;
        }/*#download_button_DIV_1*/

        #download_button_A_2 {
            background-color: rgb(12, 35, 63);
            block-size: 61px;
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            display: inline-block;
            fill: rgb(99, 213, 211);
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            height: 61px;
            inline-size: 411.781px;
            letter-spacing: 4px;
            line-height: 24px;
            max-block-size: 84px;
            max-height: 84px;
            outline-color: rgb(99, 213, 211);
            padding-block-end: 12px;
            padding-block-start: 12px;
            padding-bottom: 12px;
            padding-inline-end: 25px;
            padding-inline-start: 25px;
            padding-left: 25px;
            padding-right: 25px;
            padding-top: 12px;
            perspective-origin: 205.891px 30.5px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-decoration-line: none;
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform-origin: 205.891px 30.5px;
            transition-duration: 0.3s;
            width: 411.781px;
            background: rgb(12, 35, 63) none repeat scroll 0% 0% / auto padding-box border-box;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
            padding: 12px 25px;
            transition: all 0.3s ease 0s;
        }/*#download_button_A_2*/

        #download_button_SPAN_3 {
            align-items: center;
            block-size: 37px;
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            display: flex;
            fill: rgb(99, 213, 211);
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            height: 37px;
            inline-size: 361.781px;
            justify-content: center;
            letter-spacing: 4px;
            line-height: 24px;
            outline-color: rgb(99, 213, 211);
            perspective-origin: 180.891px 18.5px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform-origin: 180.891px 18.5px;
            width: 361.781px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
        }/*#download_button_SPAN_3*/

        #download_button_SPAN_4 {
            block-size: 37px;
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            display: block;
            fill: rgb(99, 213, 211);
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            height: 37px;
            inline-size: 30px;
            letter-spacing: 4px;
            line-height: 24px;
            margin-inline-start: 16px;
            margin-left: 16px;
            min-block-size: auto;
            min-height: auto;
            min-inline-size: auto;
            min-width: auto;
            order: 15;
            outline-color: rgb(99, 213, 211);
            perspective-origin: 15px 18.5px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform-origin: 15px 18.5px;
            width: 30px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            margin: 0px 0px 0px 16px;
            outline: rgb(99, 213, 211) none 0px;
        }/*#download_button_SPAN_4*/

        #download_button_svg_5 {
            block-size: 30px;
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            fill: rgb(99, 213, 211);
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            height: 30px;
            inline-size: 30px;
            letter-spacing: 4px;
            line-height: 24px;
            outline-color: rgb(99, 213, 211);
            overflow-clip-margin: content-box;
            overflow-x: hidden;
            overflow-y: hidden;
            perspective-origin: 15px 15px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform-origin: 15px 15px;
            width: 30px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
            overflow: hidden;
        }/*#download_button_svg_5*/

        #download_button_path_6 {
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            d: path("M 0 0 H 34 V 34 H 0 Z");
            fill: none;
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 4px;
            line-height: 24px;
            outline-color: rgb(99, 213, 211);
            perspective-origin: 0px 0px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform-origin: 0px 0px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
        }/*#download_button_path_6*/

        #download_button_rect_7 {
            block-size: 20px;
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            fill: rgb(99, 213, 211);
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            height: 20px;
            inline-size: 2px;
            letter-spacing: 4px;
            line-height: 24px;
            opacity: 0.3;
            outline-color: rgb(99, 213, 211);
            perspective-origin: 0px 0px;
            rx: 1px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform: matrix(1, 0, 0, 1, 16.099, 4);
            transform-origin: 0px 0px;
            width: 2px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
        }/*#download_button_rect_7*/

        #download_button_path_8 {
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            d: path("M 9.501 13.165 A 1.417 1.417 0 0 0 7.501 13.165 A 1.417 1.417 0 0 0 7.501 15.165 L 16.001 23.665 A 1.417 1.417 0 0 0 17.96 23.708 L 26.46 15.916 A 1.417 1.417 0 0 0 26.547 13.916 A 1.417 1.417 0 0 0 24.547 13.829 L 17.047 20.704 Z");
            fill: rgb(99, 213, 211);
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 4px;
            line-height: 24px;
            outline-color: rgb(99, 213, 211);
            perspective-origin: 0px 0px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform-origin: 0px 0px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
        }/*#download_button_path_8*/

        #download_button_rect_9 {
            block-size: 3px;
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            fill: rgb(99, 213, 211);
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            height: 3px;
            inline-size: 26px;
            letter-spacing: 4px;
            line-height: 24px;
            opacity: 0.3;
            outline-color: rgb(99, 213, 211);
            perspective-origin: 0px 0px;
            rx: 1.5px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform: matrix(1, 0, 0, 1, 4.099, 27);
            transform-origin: 0px 0px;
            width: 26px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
        }/*#download_button_rect_9*/

        #download_button_SPAN_10 {
            block-size: 24px;
            border-block-end-color: rgb(99, 213, 211);
            border-block-start-color: rgb(99, 213, 211);
            border-bottom-color: rgb(99, 213, 211);
            border-inline-end-color: rgb(99, 213, 211);
            border-inline-start-color: rgb(99, 213, 211);
            border-left-color: rgb(99, 213, 211);
            border-right-color: rgb(99, 213, 211);
            border-top-color: rgb(99, 213, 211);
            box-sizing: border-box;
            caret-color: rgb(99, 213, 211);
            color: rgb(99, 213, 211);
            column-rule-color: rgb(99, 213, 211);
            cursor: pointer;
            display: block;
            fill: rgb(99, 213, 211);
            flex-grow: 1;
            font-family: "Avenir Next", sans-serif;
            font-size: 16px;
            font-weight: 600;
            height: 24px;
            inline-size: 315.781px;
            letter-spacing: 4px;
            line-height: 24px;
            min-block-size: auto;
            min-height: auto;
            min-inline-size: auto;
            min-width: auto;
            order: 10;
            outline-color: rgb(99, 213, 211);
            perspective-origin: 157.891px 12px;
            text-align: center;
            text-decoration: none solid rgb(99, 213, 211);
            text-decoration-color: rgb(99, 213, 211);
            text-emphasis-color: rgb(99, 213, 211);
            text-size-adjust: 100%;
            text-transform: uppercase;
            transform-origin: 157.891px 12px;
            width: 315.781px;
            border: 0px none rgb(99, 213, 211);
            border-top: 0px none rgb(99, 213, 211);
            border-right: 0px none rgb(99, 213, 211);
            border-bottom: 0px none rgb(99, 213, 211);
            border-left: 0px none rgb(99, 213, 211);
            border-color: rgb(99, 213, 211);
            flex: 1 1 auto;
            font: 600 16px / 24px "Avenir Next", sans-serif;
            outline: rgb(99, 213, 211) none 0px;
        }/*#download_button_SPAN_10*/
    </style>
    
    <div id="download_button_DIV_1">
        <a href="<?= $download_url ?>" id="download_button_A_2">
            <span id="download_button_SPAN_3">
                <span id="download_button_SPAN_4">
                    <svg id="download_button_svg_5">
                        <path id="download_button_path_6"> </path>
                        <rect id="download_button_rect_7"> </rect>
                        <path id="download_button_path_8"> </path>
                        <rect id="download_button_rect_9"> </rect>
                    </svg> 
                </span>
                <span id="download_button_SPAN_10">DOWNLOAD PRODUCTS</span>
            </span>
        </a>
    </div>
    
<?php } ?>