<?php

function default_product_page(){
    ?> 
    <p style="margin-top: 20px;margin-bottom: 0px;"> Data as of <?= get_post_meta(custom_get_page_by_title('JANZ', OBJECT, 'etfs')->ID, 'ETF-Pre-rate-date-data', true) ?> </p>
    <h1 style="margin-top: 5px;margin-bottom: 0px;"> Structured Outcome </h1>
    <div id="table-pd" class="table-responsive">
    <table class="table-ts" style="border-collapse: separate; border-spacing: 0 17px; margin: 0;  overflow-x: auto;">
        <tr>
            <th style="text-align: center; border:none;" class="dynamic-elementor-font-style-body-bold">Ticker</th>
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
            <th style="border:none;" class="dynamic-elementor-font-style-body-bold">Remaining Outcome Period</th>
            <th style="text-align: center; border: none;" class="dynamic-elementor-font-style-body-bold">Prospectus</th>
        </tr>

        <?php

        foreach (get_etfs_structured() as $etf) {
            $post_to_diplay = custom_get_page_by_title($etf, OBJECT, 'etfs');
            $long_name = get_etfs_full_pre($etf);
            $daysleft = (int) get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-remaining-outcome-data', true);

        ?> <tr>
                <td class="table-ts-in pb bg-dark"><a href="/etfs/<?php echo strtolower($etf) ?>/"> <?php echo $etf ?> </a></td>
                <td class="table-ts-in pb"><a style="color:#12223D;" href="/etfs/<?php echo strtolower($etf) ?>/"> <?php echo $etf ?> </a></td>
                <td class="table-ts-in pb"> <?php echo $long_name ?> </td>
                <td class="table-ts-in pb"> $<?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-na-v-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-period-return-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-product-index-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-spx-return-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-product-participation-rate-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-remaining-buffer-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-downside-buffer-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-floor-of-buffer-data', true); ?> </td>
                <td class="table-ts-in pb"> <?php echo ($daysleft > 1) ? $daysleft . ' days' : $daysleft . ' day'; ?> </td>
                <td class="text-center table-ts-in pb p-link">
                    <a href="<?php echo get_post_meta($post_to_diplay->ID, 'ETF-Pre-pdf-prospectus', true); ?>" class="bt-download-prospectus" download>
                </td>
            </tr>
        <?php } ?>
    </table>
    </div> <?php
    $option_key = "structured_outcome_etfs_product_table";
    if ($download_url = get_option($option_key)) {  ?>
        <style>
            <?php require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'admin/css/button.css'; ?>
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
                    <span class="download_button_SPAN_10">DOWNLOAD PRODUCTS</span>
                </span>
            </a>
        </div>
    <?php }
}


$dynamic = new DynamicProductsTable();
$tables = $dynamic->list_tables();

for ($i = 0; $i < count($tables); $i++) {
    $table = $tables[$i];

    if ($table["Name"] === "default") {
        default_product_page();
        continue;
    }

    if (!isset($table["TableData"]) || $table["TableData"] === null || empty($table["TableData"])) {
        $table["TableData"] = "[]";
    }

    $data = json_decode($table["TableData"], true); ?>

    <br/>
    <h1 style="margin-top: 20px;margin-bottom: 0px;"> <?= $table["Name"] ?> </h1>
    <div class="table-responsive">
        <table  class="table-ts" style="border-collapse: separate; border-spacing: 0 17px; margin: 0; overflow-x: auto;">
            <thead>
                <tr>
                    <?php
                        if (!empty($data)) {
                            $firstRow = $data[0];
                            foreach ($firstRow as $columnName => $value) {
                                ?> <th style="border:none;" class="dynamic-elementor-font-style-body-bold"> <?= htmlspecialchars($columnName) ?> </th> <?php
                            }
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($data as $row) {
                        echo "<tr>";
                        foreach ($row as $cell) {
                            ?> <td class="table-ts-in pb">  <?= htmlspecialchars($cell) ?> </td> <?php
                        }
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
