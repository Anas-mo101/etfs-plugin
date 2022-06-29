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
            $this->etfs_structured;
            $sftp = SFTP::getInstance();
            $config = $sftp->get_config();
            $local_save_dir = wp_get_upload_dir();

            $_nav_data = null;
            if($config['Nav'] !== "*"){
                $local_save_file_path = $local_save_dir["path"] .'/'. $config['Nav'];
                $columns = (new CsvProvider())->load_and_fetch_headers($local_save_file_path);
                $_nav_data = (new CsvProvider())->load_and_fetch($local_save_file_path, $columns);
            }
            
            $now = time();

            foreach ($this->etfs_structured as $etf) {
                $market_value = '';
                if (is_array($_nav_data) && $config['Nav'] !== "*") {
                    foreach ($_nav_data as $_nav_data_record) {
                        if($_nav_data_record['Fund Ticker'] === $etf){
                            $market_value = $_nav_data_record['Market Price'];
                            break;
                        }
                    }
                }

                $long_name = (new Pdf2Data())->get_etfs_full_pre($etf); // save it as meta instead
                $current_year = date("Y");
                $future = strtotime("1 " . $long_name . " " . $current_year);
                $timeleft = $future - $now;
                $daysleft = round((($timeleft/24)/60)/60);
                
                if($daysleft < 0){
                    $current_year = $current_year + 1;
                    $future = strtotime("1 " . $long_name . " " . $current_year);
                    $timeleft = $future - $now;
                    $daysleft = round((($timeleft/24)/60)/60);
                } ?> 

                <tr>
                    <td class="table-ts-in pb bg-dark"><a href="/etfs/<?php echo strtolower($etf)?>/"> <?php echo $etf?> </a></td>
                    <td class="table-ts-in pb"><a style= "color:#12223D;" href="/etfs/<?php echo strtolower($etf)?>/" > <?php echo $etf?> </a></td>
                    <td class="table-ts-in pb"> <?php echo $long_name ?> </td>
                    <td class="table-ts-in pb"> <?php echo $market_value !== '' ? $market_value : '-'; ?> </td>
                    <td class="table-ts-in pb"> - </td>  
                    <td class="table-ts-in pb">S&P 500</td>
                    <td class="table-ts-in pb"> - </td>
                    <td class="table-ts-in pb"> - </td>
                    <td class="table-ts-in pb"> - </td>
                    <td class="table-ts-in pb"> - </td>
                    <td class="table-ts-in pb"> - </td>
                    <td class="table-ts-in pb"> <?php echo $daysleft ?> days</td>
                    <td class="text-center table-ts-in pb p-link">
                        <a href="/wp-content/uploads/<?php echo $etf?>_Prospectus.pdf" class="bt-download-prospectus" download>
                    </td>
                </tr>
            <?php } ?>
    </table>               
</div>