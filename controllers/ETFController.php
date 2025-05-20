<?php

// base url = /wp-json/etf-rest/v1/

class ETFRestController extends WP_REST_Controller
{
    var $base_route = '/etf-rest/v1';

    function __construct()
    {
        add_action('rest_api_init', array($this, 'init_endpoints'));
    }

    public function init_endpoints()
    {
        register_rest_route($this->base_route, '/sftp-cycle', array(
            'methods'  => 'GET',
            'callback' => array($this, 'start_sftp_cycle'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/fetch/data', array(
            'methods'  => 'POST',
            'callback' => array($this, 'fetch_etf_data'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/add/connection', array(
            'methods'  => 'POST',
            'callback' => array($this, 'add_connection'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/remove/connection', array(
            'methods'  => 'POST',
            'callback' => array($this, 'remove_connection'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/list/dir', array(
            'methods'  => 'POST',
            'callback' => array($this, 'get_sftp_dir'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/set/file', array(
            'methods'  => 'POST',
            'callback' => array($this, 'set_sftp_file'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/set/layout', array(
            'methods'  => 'POST',
            'callback' => array($this, 'fp_layout'),
            'permission_callback' => '__return_true'
        ));

        // ==============================

        register_rest_route($this->base_route, '/update/charts', array(
            'methods'  => 'POST',
            'callback' => array($this, 'divz_charts_update'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/get/charts', array(
            'methods'  => 'POST',
            'callback' => array($this, 'get_divz_charts'),
            'permission_callback' => '__return_true'
        ));

        // ==============================

        register_rest_route($this->base_route, '/add/dist', array(
            'methods'  => 'POST',
            'callback' => array($this, 'add_disturbion_row'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/remove/dist', array(
            'methods'  => 'POST',
            'callback' => array($this, 'delete_disturbion_row'),
            'permission_callback' => '__return_true'
        ));

        // ====================================

        register_rest_route($this->base_route, '/add/funddoc', array(
            'methods'  => 'POST',
            'callback' => array($this, 'add_fund_doc'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/remove/funddoc', array(
            'methods'  => 'POST',
            'callback' => array($this, 'remove_fund_doc'),
            'permission_callback' => '__return_true'
        ));

        // =========================

        register_rest_route($this->base_route, '/table/create', array(
            'methods'  => 'POST',
            'callback' => array($this, 'create_dynamic_table'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/table/list', array(
            'methods'  => 'GET',
            'callback' => array($this, 'list_dynamic_table'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/table/remove', array(
            'methods'  => 'POST',
            'callback' => array($this, 'remove_dynamic_table'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/table/update', array(
            'methods'  => 'POST',
            'callback' => array($this, 'update_dynamic_table'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/premium/historical', array(
            'methods'  => 'POST',
            'callback' => array($this, 'process_premium_historical'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/premium/list', array(
            'methods'  => 'POST',
            'callback' => array($this, 'get_premium_historical'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route($this->base_route, '/premium/sync', array(
            'methods'  => 'GET',
            'callback' => array($this, 'sync_premium_count'),
            'permission_callback' => '__return_true'
        ));
    }

    public function process_premium_historical(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);
            $query = $request->get_query_params();

            $update = false;
            if (isset($query["update"])) {
                $update = true;
            }

            $pd = new \PremiumDiscount();
            $response = $pd->proccess_multiple_historical($body, $update);
            
            return rest_ensure_response($response);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function sync_premium_count(WP_REST_Request $request)
    {
        try {
            $pd = new \PremiumDiscount();
            $pd->sync_count();
            
            return rest_ensure_response([
                "status" => "success"
            ]);
        } catch (\Throwable $th) {
            return new \WP_REST_Response($th->getMessage(), 400);
        }
    }

    public function get_premium_historical(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["fund"])) {
                return new \WP_REST_Response(array("status" => "no fund provided"), 400);
            }

            $post_to_update = custom_get_page_by_title($body["fund"], OBJECT, 'etfs');
            if (!$post_to_update){
                return new \WP_REST_Response(array("status" => "no fund found"), 400);
            }

            $id = $post_to_update->ID; 

            $pd = new \PremiumDiscount();
            $response = $pd->list_entries($id);
            
            return rest_ensure_response($response);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function add_fund_doc(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["field_name"]) || $body["field_name"] === "") {
                return new \WP_REST_Response(array("status" => "no field_name provided"), 400);
            }

            $fd = new \ETFsFundDocs\FundDocuments();

            $res = $fd->add_fund_field($body["field_name"]);

            return rest_ensure_response($res);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function remove_fund_doc(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["field_id"]) || $body["field_id"] === "") {
                return new \WP_REST_Response(array("status" => "no field_id provided"), 400);
            }

            $fd = new \ETFsFundDocs\FundDocuments();

            $res = $fd->delete_fund_field($body["field_id"]);

            return rest_ensure_response($res);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function add_connection(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["name"]) || $body["name"] === "") {
                return new \WP_REST_Response(array("status" => "no name provided"), 400);
            }

            $connections_services = new \ConnectionServices();

            $connections_services->create_sftp_connection( $body["name"] );

            return rest_ensure_response(array("status" => "success"));
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function remove_connection(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["id"]) || $body["id"] === "") {
                return rest_ensure_response(array("status" => "no id provided"));
            }

            $connections_services = new \ConnectionServices();

            $connections_services->sftp_remove_connection((int) $body["id"]);

            return rest_ensure_response(array("status" => "success"));
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function add_disturbion_row(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["etfName"]) || $body["etfName"] === "") {
                return rest_ensure_response(array("status" => "no etfName provided"));
            }

            $etfName = $body["etfName"];

            $dist = new \ETFsDisDetail\DisturbutionDetail();

            $res = $dist->add_disturbion_row($etfName);

            return rest_ensure_response($res);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }


    public function delete_disturbion_row(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["etfName"]) || $body["etfName"] === "") {
                return rest_ensure_response(array("status" => "no etfName provided"));
            }

            if (!isset($body["index"]) || $body["index"] === "") {
                return rest_ensure_response(array("status" => "no index provided"));
            }

            $etfName = $body["etfName"];
            $index = $body["index"];

            $dist = new \ETFsDisDetail\DisturbutionDetail();

            $res = $dist->delete_disturbion_row($etfName, $index);

            return rest_ensure_response($res);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }


    public function start_sftp_cycle(WP_REST_Request $request)
    {
        try {
            $connections_services = new \ConnectionServices();
            $connections = $connections_services->list_connections();
            $sftp = \ETFsSFTP\SFTP::getInstance();

            for ($i = 0; $i < count($connections); $i++) {
                try {
                    $connection = $connections[$i];

                    $sftp->auto_cycle((int) $connection["id"], true);
                } catch (\Throwable $th) {
                    error_log("connection id " . $connection["id"] . " failed: " . $th->getMessage());
                }
            }
            return rest_ensure_response(array("status" => "success"));
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function fetch_etf_data(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);
            $etf_name = sanitize_text_field($body['etfName']);

            // nav
            $res_nav = null;
            if (isset($body['gsURL']) && $body['gsURL'] !== '') {
                $url = esc_url($body['gsURL']);
                $columns = (new CsvProvider())->load_and_fetch_headers($url);
                $data = (new CsvProvider())->load_and_fetch($url, $columns);

                if ($data || count($data) > 0) {
                    $post_meta = new PostMeta($data, 'Nav');
                    $post_meta->utils->set_selected($etf_name);
                    $res_nav = $post_meta->process_incoming();
                }
            }

            // holdings
            $res_holdings = null;
            if (isset($body['hlURL']) && $body['hlURL'] !== '') {
                $url_h = esc_url($body['hlURL']);
                $columns_h = (new CsvProvider())->load_and_fetch_headers($url_h);
                $data_h = (new CsvProvider())->load_and_fetch($url_h, $columns_h);

                if ($data_h || count($data_h) > 0) {
                    $post_meta = new PostMeta($data_h, 'Holding');
                    $post_meta->utils->set_selected($etf_name);
                    $res_holdings = $post_meta->process_incoming();
                }
            }

            // ror
            $res_ror = null;
            if (isset($body['monthlyRorURL']) && $body['monthlyRorURL'] !== '') {
                $url_r = esc_url($body['monthlyRorURL']);
                $columns_r = (new CsvProvider())->load_and_fetch_headers($url_r);
                $data_r = (new CsvProvider())->load_and_fetch($url_r, $columns_r);

                if ($data_r || count($data_r) > 0) {
                    $post_meta = new PostMeta($data_r, 'Ror');
                    $post_meta->utils->set_selected($etf_name);
                    $res_ror = $post_meta->process_incoming();
                }
            }

            $res_dist = null;
            if (isset($body['distMemoURL']) && $body['distMemoURL'] !== '') {
                $url_dist_memo = esc_url($body['distMemoURL']);

                $XLSMParser = new \ETFsXSLMParser\XSLMParser($url_dist_memo);
                $data = $XLSMParser->process_single_data($etf_name);

                if ($data != false) {
                    $post_meta = new PostMeta($data, 'Dist');
                    $post_meta->utils->set_selected($etf_name);
                    $res_dist = $post_meta->process_incoming();
                }
            }

            $post_to_update = custom_get_page_by_title($etf_name, OBJECT, 'etfs');
            (new Calculations())->init($post_to_update->ID);

            $_res = array(
                'nav' => $res_nav,
                'holdings' =>  $res_holdings,
                'monthly_ror' => $res_ror,
                'dist_memo' => $res_dist
            );

            wp_send_json($_res);

            return rest_ensure_response(array());
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function get_sftp_dir(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);
            $ext = null;

            if (!isset($body["id"]) || $body["id"] === "") {
                return rest_ensure_response(array("status" => "no id provided"));
            }

            if (isset($body["ext"]) || $body["ext"] !== "") {
                $ext = $body["ext"];
            }

            $sftp = \ETFsSFTP\SFTP::getInstance();
            $sftp_res = $sftp->get_dir_conntent($body["id"], $ext);
            $res = array('files' => $sftp_res);

            return rest_ensure_response($res);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function set_sftp_file(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["id"]) || $body["id"] === "") {
                return rest_ensure_response(array("status" => $body));
            }

            $connections_services = new \ConnectionServices();
            $sftp = \ETFsSFTP\SFTP::getInstance();

            $res = $connections_services->update_config_db((int) $body["id"], $body);

            if (isset($body["automate"])) {
                if ($body["automate"] === true) {
                    $cycle = $sftp->auto_cycle((int) $body["id"]);

                    return rest_ensure_response([
                        'update' => 'success',
                        'cycle' => $cycle
                    ]);
                }
            }

            return rest_ensure_response($res);
        } catch (\Throwable $th) {
            return new \WP_REST_Response([
                'update' => 'failed',
                'cycle' => $th->getMessage()
            ], 400);
        }
    }

    public function fp_layout(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            $layout_setup_string = json_encode($body['etfs']);
            $structured_title = sanitize_text_field($body['structured_title']);
            $structured_subtitle = sanitize_text_field($body['structured_subtitle']);

            if (get_option('front-page-box-layout')) {
                update_option('front-page-box-layout', $layout_setup_string);
            } else {
                add_option('front-page-box-layout', $layout_setup_string);
            }

            if (get_option('frontbox-structured-title')) {
                update_option('frontbox-structured-title', $structured_title);
            } else {
                add_option('frontbox-structured-title', $structured_title);
            }

            if (get_option('frontbox-structured-subtitle')) {
                update_option('frontbox-structured-subtitle', $structured_subtitle);
            } else {
                add_option('frontbox-structured-subtitle', $structured_subtitle);
            }

            $res = array('update' => 'update success');

            return rest_ensure_response($res);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function divz_charts_update(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            $divz_no_stocks = sanitize_text_field($body['divz_no_stocks']);
            $sp_no_stocks = sanitize_text_field($body['sp_no_stocks']);

            $divz_ps = sanitize_text_field($body['divz_ps']);
            $sp_ps = sanitize_text_field($body['sp_ps']);

            $divz_pe = sanitize_text_field($body['divz_pe']);
            $sp_pe = sanitize_text_field($body['sp_pe']);

            $divz_pb = sanitize_text_field($body['divz_pb']);
            $sp_pb = sanitize_text_field($body['sp_pb']);

            $divz_avg = sanitize_text_field($body['divz_avg']);
            $sp_avg = sanitize_text_field($body['sp_avg']);

            $divz_values_josn = array(
                'no_stocks' => array(
                    'divz' => $divz_no_stocks,
                    'sp' => $sp_no_stocks
                ),
                'ps' => array(
                    'divz' => $divz_ps,
                    'sp' => $sp_ps
                ),
                'pe' => array(
                    'divz' => $divz_pe,
                    'sp' => $sp_pe
                ),
                'pb' => array(
                    'divz' => $divz_pb,
                    'sp' => $sp_pb
                ),
                'avg' => array(
                    'divz' => $divz_avg,
                    'sp' => $sp_avg
                ),
            );

            $divz_values = json_encode($divz_values_josn);

            if (get_option('divz-chart-values')) {
                update_option('divz-chart-values', $divz_values);
            } else {
                add_option('divz-chart-values', $divz_values);
            }

            return rest_ensure_response(array('update' => 'success'));
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function get_divz_charts(WP_REST_Request $request)
    {
        try {
            $divz_values_opt = get_option('divz-chart-values');
            $divz_values = json_decode($divz_values_opt, true);

            return rest_ensure_response(array('update' => 'success', 'data' => $divz_values));
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    /// ============================

    public function list_dynamic_table(WP_REST_Request $request)
    {
        try {
            $dynamic_tables = new \DynamicProductsTable();
            $tables = $dynamic_tables->list_tables();

            return rest_ensure_response(array(
                'data' => $tables
            ));
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function remove_dynamic_table(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["id"]) || $body["id"] === "") {
                return new \WP_REST_Response(array("status" => "no id provided"), 400);
            }

            $dynamic_tables = new \DynamicProductsTable();
            $dynamic_tables->remove_dynamice_table( (int) $body["id"] );

            return rest_ensure_response(array( "deletion" => "success" ));
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function update_dynamic_table(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["id"]) || $body["id"] === "") {
                return new \WP_REST_Response(array("status" => "no id provided"), 400);
            }

            $args = [];

            if (isset($body["name"]) || $body["name"] != "") {
                $args["Name"] = $body["name"];
            }

            if (isset($body["connectionId"]) || $body["connectionId"] != "") {
                $args["ConnectionId"] = $body["connectionId"];
            }

            if (isset($body["filename"]) || $body["filename"] != "") {
                $args["FileName"] = $body["filename"];
            }

            if (isset($body["order"]) || $body["order"] != "") {
                $args["Torder"] = $body["order"];
            }

            $dynamic_tables = new \DynamicProductsTable();
            $dynamic_tables->update_table( (int) $body["id"], $args );

            $sftp = new \ETFsSFTP\SFTP();
            $sftp->auto_cycle( (int) $body["connectionId"] );

            return rest_ensure_response(["update" => "success"]);
        } catch (\Throwable $th) {
            return new \WP_REST_Response(null, 400);
        }
    }

    public function create_dynamic_table(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body(), true);

            if (!isset($body["name"]) || $body["name"] === "") {
                return new \WP_REST_Response(array("status" => "no name provided"), 400);
            }

            if (!isset($body["connectionId"]) || $body["connectionId"] === "") {
                return new \WP_REST_Response(array("status" => "no connectionId provided"), 400);
            }

            if (!isset($body["fileName"]) || $body["fileName"] === "") {
                return new \WP_REST_Response(array("status" => "no fileName provided"), 400);
            }

            if (!isset($body["order"]) || $body["order"] === "") {
                return new \WP_REST_Response(array("status" => "no order provided"), 400);
            }

            $data = "[]";

            $dynamic_tables = new \DynamicProductsTable();
            $dynamic_tables->create_dynamice_table(
                $body["name"],
                $body["connectionId"],
                $body["fileName"],
                $body["order"],
                $data,
            );

            if($body["name"] !== "default"){
                $sftp = new \ETFsSFTP\SFTP();
                $sftp->auto_cycle( (int) $body["connectionId"] );
            }

            return rest_ensure_response(['create' => 'success']);
        } catch (\Throwable $th) {
            return new \WP_REST_Response($th->getMessage(), 400);
        }
    }
}
