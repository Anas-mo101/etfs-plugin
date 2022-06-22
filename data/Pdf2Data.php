<?php

class Pdf2Data {


    // post file link to convert
    function convert_pdf($pdf_file){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api2.online-convert.com/jobs',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "input": [{
                "type": "remote",
                "source": "' . $pdf_file . '"
            }],
            "conversion": [{
                "target": "txt",
                "options": {
                    "ocr": true,
                    "language": "eng"
                }
            }]
        }',
        CURLOPT_HTTPHEADER => array(
            'x-oc-api-key: 5d5ca62ed0a627f3eb6de24d3534f8fd',
            'Content-Type: text/plain'
        ),
        ));

        $response = curl_exec($curl); 
        curl_close($curl);

        $response = json_decode($response, true);
        sleep(10);

        if( !isset($response['id']) ){
            $this->write_log($response);
            return false;
        }
        
        $res = $response['id'];

        return $res;
    }

    
    // get file to download
    function get_converted_file($file_id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api2.online-convert.com/jobs/' . $file_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-oc-api-key: 5d5ca62ed0a627f3eb6de24d3534f8fd'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);

        if( !isset($response['output'][0]['uri']) ){
            $this->write_log($response);
            return false;
        }

        $res = $response['output'][0]['uri'];
        
        return $res;
    }

    // instead of downloading file, get txt file contents
    function get_file_data($url) {
        $converted_file_string = file_get_contents($url);
        return $converted_file_string;
    } 

    function pdf_convertion_api_call($pdf_file_link){
        $file_id = $this->convert_pdf($pdf_file_link);
        if($file_id == false){
            return false;
        }
        $url = $this->get_converted_file($file_id);
        if($url == false){
            return false;
        }
        return $this->get_file_data($url);
    }
    
    function get_monthly_fund_data($pdf_file_link,$etf_name,$api_on){
        $etf_pre = $this->get_etfs_full_pre($etf_name);
        $pdf_data_array;

        if($api_on){
            // Using API 
            $text = $this-> pdf_convertion_api_call($pdf_file_link);
            $pattern = '/TrueShares Structured Outcome ' . $etf_pre . ' ([+-]?(?=\\.\\d|\\d)(?:\\d+)?(?:\\.?\\d*))(?:[eE]([+-]?\\d+))?/i';
            preg_match($pattern, $text, $matches);
            $pdf_data_array = $matches;
            // not completed 
            return $pdf_data_array;
        }

        // Using PDFParser Library 
        $text = $this->convert_pdf_to_text($pdf_file_link);

        $pattern = '/(?:'.$etf_pre.' ETF)(.*)(?:ETF)/U';
        preg_match($pattern, $text, $matches);

        if(!$matches && count($matches) == 0){
            $pdf_data_array = array('fetch failed' => 'Data not found for '. $etf_name .' ETF');
            return $pdf_data_array;
        }

        $unordered_pdf_data_array = explode(' ',trim($matches[1]));
        $sp_incp_fixed = $this->parser_value_fix($matches[1]);
        $pdf_data_array_market = array('three_months' => $unordered_pdf_data_array[4], 'six_months' => $unordered_pdf_data_array[7], 'one_year' => $unordered_pdf_data_array[3], 'inception' => $unordered_pdf_data_array[8]);
        $pdf_data_array_nav = array('three_months' => $unordered_pdf_data_array[21], 'six_months' => $unordered_pdf_data_array[20], 'one_year' => $unordered_pdf_data_array[17], 'inception' => $unordered_pdf_data_array[18]);
        $pdf_data_array_sp = array('three_months' => $unordered_pdf_data_array[28], 'six_months' => $unordered_pdf_data_array[27], 'one_year' => $unordered_pdf_data_array[33], 'inception' => $sp_incp_fixed);
        $pdf_data_array = array('sec_yeild' => $unordered_pdf_data_array[11], 'market_price' =>  $pdf_data_array_market, 'fund_nav' => $pdf_data_array_nav, 'sp' => $pdf_data_array_sp);
        return $pdf_data_array;
    }

    function get_all_monthly_fund_data($pdf_file_link){
        $query = new WP_Query(array( 'post_type' => 'etfs', 'post_status' => 'publish' ));
        $text = $this->convert_pdf_to_text($pdf_file_link);
        $pdf_data = array();

        while ($query->have_posts()) {
            $query->the_post();
            $etf_title = get_the_title();
            $etf_pre = $this->get_etfs_full_pre($etf_title);

            if(! $etf_pre) continue;

            $pattern = '/(?:'.$etf_pre.' ETF)(.*)(?:ETF)/U';
            preg_match($pattern, $text, $matches);

            if(!$matches && count($matches) == 0){
                $pdf_data_array = array('fetch failed' => 'Data not found for '. $etf_pre .' ETF');
                $pdf_data[] = $pdf_data_array;
                continue;
            }

            $unordered_pdf_data_array = explode(' ',trim($matches[1]));
            $sp_incp_fixed = $this->parser_value_fix($matches[1]);
            $pdf_data_array_market = array('three_months' => $unordered_pdf_data_array[4], 'six_months' => $unordered_pdf_data_array[7], 'one_year' => $unordered_pdf_data_array[3], 'inception' => $unordered_pdf_data_array[8]);
            $pdf_data_array_nav = array('three_months' => $unordered_pdf_data_array[21], 'six_months' => $unordered_pdf_data_array[20], 'one_year' => $unordered_pdf_data_array[17], 'inception' => $unordered_pdf_data_array[18]);
            $pdf_data_array_sp = array('three_months' => $unordered_pdf_data_array[28], 'six_months' => $unordered_pdf_data_array[27], 'one_year' => $unordered_pdf_data_array[33], 'inception' => $sp_incp_fixed);
            $pdf_data_array = array('sec_yeild' => $unordered_pdf_data_array[11], 'market_price' =>  $pdf_data_array_market, 'fund_nav' => $pdf_data_array_nav, 'sp' => $pdf_data_array_sp);
            $pdf_data[] = array($etf_title => $pdf_data_array); 
        }   
        wp_reset_query();
        return $pdf_data;
    }

    function get_distrubation_memo_data($pdf_file_link,$etf_name,$api_on){
        $etf_pre = false;
        $pdf_data_array;

        if(! is_null($etf_name)){
            $etf_pre = $this->get_etfs_full_pre($etf_name);
        }

        if($api_on){
            // Using API 
            $text = $this-> pdf_convertion_api_call($pdf_file_link);
            $pattern = '/TrueShares Structured Outcome ' . $etf_pre . ' ([+-]?(?=\\.\\d|\\d)(?:\\d+)?(?:\\.?\\d*))(?:[eE]([+-]?\\d+))?/i';
            preg_match($pattern, $text, $matches);
            $pdf_data_array = $matches;
            // not completed due restrications on api usage
            return $pdf_data_array;
        }

        $text = $this->convert_pdf_to_text($pdf_file_link);
        $pattern = '/(?:Record Date:)(.*)(?:Ordinary Income Rate)/U';
        preg_match($pattern, $text, $matches);

        if(!$matches && count($matches) == 0){
            $pdf_data_array = array('fetch failed' => 'Data not found for '. $etf_name .' ETF');
            return $pdf_data_array;
        }

        $unordered_pdf_data_array = explode(' ',trim($matches[1]));
        $ex_date = $unordered_pdf_data_array[0] . ' ' . $unordered_pdf_data_array[1] . $unordered_pdf_data_array[2];
        $rec_date = $unordered_pdf_data_array[5] . ' ' . $unordered_pdf_data_array[6] . $unordered_pdf_data_array[7];
        $pay_date = $unordered_pdf_data_array[15] . ' ' . $unordered_pdf_data_array[16] . $unordered_pdf_data_array[17];

        $pattern = '/(?:TrueShares Structured Outcome \(' . $etf_pre . '\) ETF)(.*)(?:TrueShares)/U';
        preg_match($pattern, $text, $matches);

        if(!$matches && count($matches) == 0){
            $pdf_data_array = array('fetch failed' => 'Data not found for '. $etf_name .' ETF');
            return $pdf_data_array;
        }

        $unordered_pdf_data_array = explode(' ',trim($matches[1]));
        $pdf_data_array = array('ex_date' => $ex_date, 'rec_date' => $rec_date, 'pay_date' => $pay_date, 'dis_rate_share' => $unordered_pdf_data_array[2]);

        return $pdf_data_array;
    }

    function get_all_distrubation_memo_data($pdf_file_link){
        $query = new WP_Query(array( 'post_type' => 'etfs', 'post_status' => 'publish' ));
        $text = $this->convert_pdf_to_text($pdf_file_link);
        $pdf_data = array();

        $pattern = '/(?:Record Date:)(.*)(?:Ordinary Income Rate)/U';
        preg_match($pattern, $text, $matches);

        $ex_date = 'No Data';
        $rec_date = 'No Data';
        $pay_date = 'No Data';
        if($matches && count($matches) > 0){
            $unordered_pdf_data_array = explode(' ',trim($matches[1]));
            $ex_date = $unordered_pdf_data_array[0] . ' ' . $unordered_pdf_data_array[1] . $unordered_pdf_data_array[2];
            $rec_date = $unordered_pdf_data_array[5] . ' ' . $unordered_pdf_data_array[6] . $unordered_pdf_data_array[7];
            $pay_date = $unordered_pdf_data_array[15] . ' ' . $unordered_pdf_data_array[16] . $unordered_pdf_data_array[17];
        }

        while ($query->have_posts()) {
            $query->the_post();
            $etf_title = get_the_title();
            $etf_pre = $this->get_etfs_full_pre($etf_title);

            if(! $etf_pre) continue;

            $pattern = '/(?:TrueShares Structured Outcome \(' . $etf_pre . '\) ETF)(.*)(?:TrueShares)/U';
            preg_match($pattern, $text, $matches);

            if(!$matches && count($matches) == 0){
                $pdf_data_array = array('fetch failed' => 'Data not found for '. $etf_title .' ETF');
                $pdf_data[] = $pdf_data_array;
                continue;
            }
            $unordered_pdf_data_array = explode(' ',trim($matches[1]));
            $pdf_data_array = array('etf_name' => $etf_title ,'ex_date' => $ex_date, 'rec_date' => $rec_date, 'pay_date' => $pay_date,'dis_rate_share' => $unordered_pdf_data_array[2]);
            $pdf_data[] = $pdf_data_array;
        }   
        wp_reset_query();
        return $pdf_data;
    }


    // get month based on etf 
    function get_etfs_full_pre($etf_name){
        switch ($etf_name) {
            case 'JANZ':
                return 'January';
            case 'FEBZ':
                return 'Febrary';
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
                return 'Novamber';
            case 'DECZ':
                return 'December';
            case 'LRNZ':
                return 'AI & Deep Learning';
            default:
                return 'Other';
        }
    }

    function convert_pdf_to_text($file_url){

        $parser = new \Smalot\PdfParser\Parser(); 

        // Parse pdf file using Parser library 
        $pdf = $parser->parseFile($file_url); 
        
        // Extract text from PDF 
        $text = $pdf->getText(); 

        $text = trim(preg_replace('/\s+/', ' ', $text));

        // Display text content 
        return $text;
    }

    //this is a parser issue
    function parser_value_fix($text){
        
        $pattern = '/(?:S&P 500|S&P 500 TR) ([+-]?(?=\.\d|\d)(?:\d+)?(?:\.?\d*))(?:[eE]([+-]?\d+))?/';
        preg_match($pattern, $text, $matches);
        $sp_incp_raw_value = $matches[1];
        $pos = strpos($sp_incp_raw_value, '.') + 3;
        $sp_incp_fixed = substr($sp_incp_raw_value, 0, $pos);

        return $sp_incp_fixed;
    }
}