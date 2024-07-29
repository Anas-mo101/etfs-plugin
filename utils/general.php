<?php

function get_etfs_full_pre($etf_name)
{
    switch ($etf_name) {
        case 'JANZ':
            return 'January';
        case 'FEBZ':
            return 'February';
        case 'MARZ':
            return 'March';
        case 'APRZ':
            return 'April';
        case 'MAYZ':
            return 'May';
        case 'JUNZ':
            return 'June';
        case 'JULZ':
            return 'July';
        case 'AUGZ':
            return 'August';
        case 'SEPZ':
            return 'September';
        case 'OCTZ':
            return 'October';
        case 'NOVZ':
            return 'November';
        case 'DECZ':
            return 'December';
        case 'LRNZ':
            return 'AI & Deep Learning';
        case 'FLDZ':
            return 'RiverNorth Patriot';
        case 'SPCZ':
            return 'RiverNorth Enhanced Pre-Merger';
        case 'ECOZ':
            return 'ESG Active Opportunities';
        case 'DIVZ':
            return 'Low Volatility Equity';
        default:
            return FALSE;
    }
}

function custom_get_page_by_title($page_title, $output = OBJECT, $post_type = "etfs")
{
    $query = new WP_Query(
        array(
            "post_type" => $post_type,
            "title" => $page_title,
            "post_status" => "all",
            "posts_per_page" => 1,
            "no_found_rows" => true,
            "ignore_sticky_posts" => true,
            "update_post_term_cache" => false,
            "update_post_meta_cache" => false,
            "orderby" => "date",
            "order" => "ASC",
        )
    );

    if (!empty($query->post)) {
        $_post = $query->post;

        if (ARRAY_A === $output) {
            return $_post->to_array();
        } elseif (ARRAY_N === $output) {
            return array_values($_post->to_array());
        }

        return $_post;
    }

    return null;
}

function big_number_format($n, $precision = 2)
{
    $n = (float) $n;
    if ($n < 1000000) {
        return number_format($n, 2);
    } else if ($n < 1000000000) {
        return number_format($n / 1000000, $precision) . 'M';
    } else {
        return number_format($n / 1000000000, $precision) . 'B';
    }
}

function write_product_xlsx_file()
{
    $etfs_structured = array('JANZ', 'FEBZ', 'MARZ', 'APRZ', 'MAYZ', 'JUNZ', 'JULZ', 'AUGZ', 'SEPZ', 'OCTZ', 'NOVZ', 'DECZ');

    $xlsx_data = array();

    foreach ($etfs_structured as $etf) {
        $post_to_diplay = custom_get_page_by_title($etf, OBJECT, 'etfs');
        $long_name = get_etfs_full_pre($etf);
        $daysleft = (int) get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-remaining-outcome-data', true);

        $ticker = $etf;
        $name = $etf;
        $series    = $long_name;
        $fund_price    = get_post_meta($post_to_diplay->ID, 'ETF-Pre-na-v-data', true);
        $period_return    = get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-period-return-data', true);
        $index    = get_post_meta($post_to_diplay->ID, 'ETF-Pre-product-index-data', true);
        $index_period_reuturn = get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-spx-return-data', true);
        $upside_market    = get_post_meta($post_to_diplay->ID, 'ETF-Pre-product-participation-rate-data', true);
        $remaining_buffer    = get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-remaining-buffer-data', true);
        $downside_buffer    = get_post_meta($post_to_diplay->ID, 'ETF-Pre-current-downside-buffer-data', true);
        $downside_floor    = get_post_meta($post_to_diplay->ID, 'ETF-Pre-floor-of-buffer-data', true);
        $remaining_outcome    = ($daysleft > 1) ? $daysleft . ' days' : $daysleft . ' day';

        $xlsx_data[] = array(
            $ticker,
            $name,
            $series,
            $fund_price,
            $period_return,
            $index,
            $index_period_reuturn,
            $upside_market,
            $remaining_buffer,
            $downside_buffer,
            $downside_floor,
            $remaining_outcome,
        );
    }

    $writer = new XLSXWriter();

    $writer->writeSheetHeader(
        'Sheet1',
        array(
            'Ticker' => 'string',
            'Name' => 'string',
            'Series' => 'string',
            'Fund Price' => 'string',
            'Period Returns' => 'string',
            'Index' => 'string',
            'Index Period Returns' => 'string',
            'Est. Upside Market Participation Rate' => 'string',
            'Remaining Buffer' => 'string',
            'ETF Downside to Buffer' => 'string',
            'S&P Downside to Floor of Buffer' => 'string',
            'Remaining Outcome Period' => 'string',
        ),
        $styles = array(
            'font-style' => 'bold',
            'font' => 'Calibri',
            'font-size' => 12,
            'widths' => [30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30]
        )
    );

    $writer->writeSheet($xlsx_data, 'Sheet1');
    $upload_dir = wp_upload_dir();

    if (!empty($upload_dir['basedir'])) {

        $file_name = 'Structured_Outcome_ETFs.xlsx';
        $file_path = $upload_dir['basedir'] . '/' . $file_name;
        $file_url = $upload_dir['baseurl'] . '/' . $file_name;

        if (!file_exists($file_path)) {
            $writer->writeToFile($file_path);
        } else {
            if (!unlink($file_path)) {
                error_log("$file_name cannot be deleted due to an error");
            } else {
                error_log($file_path);
                $writer->writeToFile($file_path);
            }
        }

        $option_key = "structured_outcome_etfs_product_table";

        if (get_option($option_key)) {
            update_option($option_key, $file_url);
        } else {
            add_option($option_key, $file_url);
        }
    }
}

function write_xlsx_file($in, $post_id, $selected_etfs, $etf_name = null)
{

    $xlsx_holding = array();

    for ($i = 0; $i < count($in); $i++) {
        $xlsx_holding[] = array(
            $in[$i]['Weightings'],
            $in[$i]['SecurityName'],
            $in[$i]['StockTicker'],
            $in[$i]['CUSIP'],
            $in[$i]['Shares'],
            $in[$i]['MarketValue']
        );
    }

    $writer = new XLSXWriter();

    $writer->writeSheetHeader(
        'Sheet1',
        array(
            '% Of Net Assets' => 'string',
            'Name' => 'string',
            'Ticker' => 'string',
            'CUSIP' => 'string',
            'Share Held' => 'string',
            'Market Value' => 'string',
        ),
        $styles = array(
            'font-style' => 'bold',
            'font' => 'Calibri',
            'font-size' => 12,
            'widths' => [30, 40, 30, 30, 20, 20]
        )
    );

    $writer->writeSheet($xlsx_holding, 'Sheet1');
    $upload_dir = wp_upload_dir();

    if (!empty($upload_dir['basedir'])) {

        $file_name = ($etf_name ?? $selected_etfs) . '_USBanksHoldings.xlsx';
        $file_path = $upload_dir['basedir'] . '/' . $file_name;
        $file_url = $upload_dir['baseurl'] . '/' . $file_name;

        if (!file_exists($file_path)) {
            $writer->writeToFile($file_path);
        } else {
            if (!unlink($file_path)) {
                error_log("$file_name cannot be deleted due to an error");
            } else {
                error_log($file_path);
                $writer->writeToFile($file_path);
            }
        }

        update_post_meta($post_id, 'ETF-Pre-top-holders-button-download-data', $file_url);
    }
}


function dateFormatingMDYYYY($dateString){
    // Array of possible date formats
    $formats = ['m/d/Y', 'm-d-Y', 'Y/m/d', 'Y-m-d', 'n/j/Y', 'n-j-Y', 'Y/n/j', 'Y-n-j'];
    
    $date = false;
    foreach ($formats as $format) {
        // Try to create a DateTime object using the current format
        $date = DateTime::createFromFormat($format, $dateString);
        // Check if the date is parsed correctly by comparing the formatted output with the original input
        // This comparison handles leading zeros as well
        if ($date && $date->format($format) === $dateString) {
            break;
        }
    }
    
    if ($date) {
        // Format the date to M/D/YYYY, without leading zeros for month and day
        $formattedDate = $date->format('n/j/Y');
        // Output the formatted date
        return $formattedDate;
    }

    return $dateString;
}
