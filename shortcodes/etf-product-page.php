<?php ?>
<div  id ="table-pd" class="table-responsive">
    <table class="table-ts" style="border-collapse: separate; border-spacing: 0 17px; margin: 0;  overflow-x: auto;">
        <tr>
            <th style="text-align: center;"class="table-ts-title2-product">Ticker</th>
            <th class="table-ts-title2-product">Name</th>
            <th class="table-ts-title2-product">Series</th>
            <th class="table-ts-title2-product">Fund Price</th>
            <th class="table-ts-title2-product">Period Returns</th>
            <th class="table-ts-title2-product">Index</th>
            <th class="table-ts-title2-product">Index Period Returns</th>
            <th class="table-ts-title2-product">Est. Upside Market Participation Rate</th>
            <th class="table-ts-title2-product">Remaining Buffer</th>
            <th class="table-ts-title2-product">ETF Downside to Buffer</th>
            <th class="table-ts-title2-product">S&P Downside to Floor of Buffer</th>
            <th class="table-ts-title2-product">Remaining Outcome Period</th>
            <th style="text-align: center;" class="table-ts-title2-product">Prospectus</th>
        </tr>
        
        <?php
            
            foreach ($this->etfs_structured as $etf) {

                $post_to_diplay = get_page_by_title( $etf, OBJECT, 'etfs' );
                $long_name = (new Pdf2Data())->get_etfs_full_pre($etf);
                $daysleft = (new Calculations())->get_remaining_outcome_period(false,$etf); ?> 

                <tr>
                    <td class="table-ts-in pb bg-dark"><a href="/etfs/<?php echo strtolower($etf)?>/"> <?php echo $etf?> </a></td>
                    <td class="table-ts-in pb"><a style= "color:#12223D;" href="/etfs/<?php echo strtolower($etf)?>/" > <?php echo $etf?> </a></td>
                    <td class="table-ts-in pb"> <?php echo $long_name ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-na-v-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-etf-return-data', true); ?> </td>  
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-product-index-data', true); ?>  </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-spx-return-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-product-participation-rate-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-remaining-buffer-data', true); ?> </td>
                    <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID,'ETF-Pre-current-downside-buffer-data', true); ?> </td>
                    <td class="table-ts-in pb"> - </td>
                    <td class="table-ts-in pb"> <?php echo ($daysleft > 1) ? $daysleft . ' days' : $daysleft . ' day'; ?> </td>
                    <td class="text-center table-ts-in pb p-link">
                        <a href="/wp-content/uploads/<?php echo $etf?>_Prospectus.pdf" class="bt-download-prospectus" download>
                    </td>
                </tr>
            <?php } ?>
    </table>               
</div>