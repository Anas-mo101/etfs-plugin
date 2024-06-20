<?php



class RorPostMeta implements PostMetaInterface {

    public function process_incoming(PostMetaUtils $utils): bool
    {
        if (!$utils->meta || count($utils->meta) === 0) {
            return false;
        }

        $this->save_available_benchmarks($utils->meta);

        if ( $utils->selected_etfs !== null && $utils->files_map === null) {
            $data = $this->find_ror_record($utils->selected_etfs, $utils->meta);
            if ($data === false) return false;

            $single_utils = clone $utils;
            $single_utils->meta = $data;

            $this->process_single( $single_utils );

            return true;
        } else {
            return $this->process_multiple( $utils );
        }
    }

    public function process_multiple( PostMetaUtils $utils ): bool{
        $query = new WP_Query(array('post_type' => 'etfs', 'posts_per_page' => 9999999));
        // loop through etfs 
        while ($query->have_posts()) {
            $query->the_post();
            $etf_title = get_the_title(); // get etf ticker name

            $data = $this->find_ror_record($etf_title, $utils->meta);
            if ($data === false) continue; // if etf not found in record look for next etf

            $single_utils = clone $utils;

            $single_utils->meta = $data;
            $single_utils->set_selected($etf_title);

            $this->process_single( $single_utils );
        }
        return true;
    }

    public function process_single(PostMetaUtils $utils): bool
    {
        $post_to_update = custom_get_page_by_title($utils->selected_etfs, OBJECT, 'etfs');
        if (!$post_to_update) return false;

        $connection = (int) get_post_meta($post_to_update->ID, "ETF-Pre-connection-id", true);
        if ($utils->connectionId !== null && $connection !== $utils->connectionId) {
            return false;
        }

        update_post_meta($post_to_update->ID, 'ETF-Pre-pref-date-data', $utils->meta['date']);

        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-nav-inception-data', $utils->meta['fund_nav']['inception']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-nav-five-year-data', $utils->meta['fund_nav']['five_year']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-nav-year-data', $utils->meta['fund_nav']['one_year']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-nav-six-data', $utils->meta['fund_nav']['six_months']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-nav-three-data', $utils->meta['fund_nav']['three_months']);

        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-market-inception-data', $utils->meta['market_price']['inception']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-market-year-data', $utils->meta['market_price']['one_year']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-market-five-year-data', $utils->meta['market_price']['five_year']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-market-six-data', $utils->meta['market_price']['six_months']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-market-three-data', $utils->meta['market_price']['three_months']);

        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-sp-inception-data', $utils->meta['sp']['inception']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-sp-year-data', $utils->meta['sp']['one_year']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-sp-five-year-data', $utils->meta['sp']['five_year']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-sp-six-data', $utils->meta['sp']['six_months']);
        update_post_meta($post_to_update->ID, 'ETF-Pre-perf-sp-three-data', $utils->meta['sp']['three_months']);

        return true;
    }

    private function find_ror_record($ref, $meta)
    {
        $post_to_update = custom_get_page_by_title($ref, OBJECT, 'etfs');

        $pattern = '/' . $ref . '/U'; // use fund name as reference to search data array
        foreach ($meta as $key => $value) { // loop through input array data

            preg_match($pattern, $value['Fund Ticker'], $matches); // look for match

            if ($matches || count($matches) > 0) {

                $nav_arr = array();
                $mkt_arr = array();
                $sp_arr = array();

                $first = false;
                $second = false;

                if (str_contains($meta[$key]['Fund Ticker'], 'NAV')) {
                    $nav_arr = $meta[$key];
                    $first = true;
                } elseif (str_contains($meta[$key + 1]['Fund Ticker'], 'NAV')) {
                    $nav_arr = $meta[$key + 1];
                    $second = true;
                } else {
                    $nav_arr = $meta[$key + 2];
                }

                if (str_contains($meta[$key]['Fund Ticker'], 'MKT')) {
                    $mkt_arr = $meta[$key];
                    $first = true;
                } elseif (str_contains($meta[$key + 1]['Fund Ticker'], 'MKT')) {
                    $mkt_arr = $meta[$key + 1];
                    $second = true;
                } else {
                    $mkt_arr = $meta[$key + 2];
                }


                $post_categories = wp_get_post_categories($post_to_update->ID);
                $cats = array();
                $is_not_structured = false;
                foreach ($post_categories as $c) {
                    $cat = get_category($c);
                    if ($cat->name == 'Unstructured ETFs') {
                        $is_not_structured = true;
                    }
                }

                // Use date from sheet instead of manually entered inception date
                $date_from_preformance_record = $meta[$key]['Date'];
                $incepention_date = get_post_meta($post_to_update->ID, 'ETF-Pre-inception-date-data', true) ? get_post_meta($post_to_update->ID, 'ETF-Pre-inception-date-data', true) : date('Y-m-d');

                $target = date_create($date_from_preformance_record);
                $origin = date_create($incepention_date);
                $interval = date_diff($origin, $target);
                $diff = $interval->format('%y');

                $date_inc = (int) $diff >= 1 ? 'Since Inception Annualized' : 'Since Inception Cumulative';

                $data_array_sp = array();
                if ($is_not_structured) {
                    $data_array_sp = $this->get_ror_benchmark_record($post_to_update->ID, $date_inc, $meta);
                } else {
                    $sp_arr = array();

                    if ($first == false) {
                        $sp_arr = $meta[$key];
                    } elseif ($second == false) {
                        $sp_arr = $meta[$key + 1];
                    } else {
                        $sp_arr = $meta[$key + 2];
                    }

                    $data_array_sp = array(
                        'three_months' => $sp_arr['3 Month'],
                        'six_months' => $sp_arr['6 Month'],
                        'one_year' => $sp_arr['1 Year'],
                        'five_year' => $sp_arr['5 Year'],
                        'inception' => $sp_arr[$date_inc]
                    );
                }

                if (empty($nav_arr) || empty($mkt_arr)) {
                    return false;
                }

                $data_array_nav = array('three_months' => $nav_arr['3 Month'], 'six_months' => $nav_arr['6 Month'], 'one_year' => $nav_arr['1 Year'], 'five_year' => $nav_arr['5 Year'], 'inception' => $nav_arr[$date_inc]);
                $data_array_market = array('three_months' => $mkt_arr['3 Month'], 'six_months' => $mkt_arr['6 Month'], 'one_year' => $mkt_arr['1 Year'], 'five_year' => $mkt_arr['5 Year'], 'inception' => $mkt_arr[$date_inc]);

                $data_array = array('date' => $nav_arr['Date'], 'sec_yeild' => '', 'market_price' =>  $data_array_market, 'fund_nav' => $data_array_nav, 'sp' => $data_array_sp);

                return $data_array;
            }
        }
        return false;
    }


    private function get_ror_benchmark_record($id, $date_inc, $meta)
    {
        $benchmark = get_post_meta($id, 'ETF-Pre-preformance-benchmark-selection-data', true);

        $benchmark_value = explode(' - ', $benchmark);

        $benchmark_length = count($benchmark_value);

        $null_arr = array('three_months' => '-',  'six_months' => '-', 'one_year' => '-', 'five_year' => '-', 'inception' => '-');

        if (!is_array($benchmark_value) || $benchmark_length > 2 || $benchmark_length <= 0) return $null_arr;

        foreach ($meta as $record => $values) {
            if ($values['Fund Name'] === $benchmark_value[1]) {
                $i = $record;
                while ($i < count($meta)) {
                    if ($meta[$i]['Fund Name'] === $benchmark_value[0]) {
                        return array(
                            'three_months' => $meta[$i]['3 Month'] ?? '-',
                            'six_months' => $meta[$i]['6 Month'] ?? '-',
                            'one_year' => $meta[$i]['1 Year'] ?? '-',
                            'five_year' => $meta[$i]['5 Year'] ?? '-',
                            'inception' => $meta[$i][$date_inc] ?? '-'
                        );
                    }
                    $i++;
                }
            }
        }

        return $null_arr;
    }

    private function save_available_benchmarks($data)
    {
        $benchmarks = array();

        for ($i = 0; $i < count($data); $i++) {
            if (!str_contains($data[$i]['Fund Name'], 'ETF')) {

                $loop = true;
                $j = 1;

                while ($loop == true) {
                    if (!isset($data[$i - $j])) $loop = false;

                    if (str_contains($data[$i - $j]['Fund Name'], 'ETF')) {
                        $benchmarks[] = $data[$i]['Fund Name'] . ' - ' . $data[$i - $j]['Fund Name'];
                        $loop = false;
                    }
                    $j++;
                }
            }
        }

        $benchmarks = array_filter($benchmarks, function ($value) {
            return !str_contains($value, 'Structured Outcome');
        });

        $benchmarks = array_values($benchmarks);

        $benchmarks_json = json_encode($benchmarks);

        if (get_option('etfs-pre-available-benchmarks')) {
            update_option('etfs-pre-available-benchmarks', $benchmarks_json);
        } else {
            add_option('etfs-pre-available-benchmarks', $benchmarks_json);
        }
    }

}