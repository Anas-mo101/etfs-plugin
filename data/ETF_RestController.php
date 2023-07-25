<?php

class ETFRestController extends WP_REST_Controller {
    var $base_route = 'etf-rest/v1';

    function __construct(){
        add_action( 'rest_api_init', array( $this, 'init_endpoints' ) );
    }

    public function init_endpoints() {
        register_rest_route( $this->base_route, '/sftp-cycle', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'start_sftp_cycle' ),
            'permission_callback' => '__return_true'
        ));
    }

    public function start_sftp_cycle( WP_REST_Request $request ) {

        $sftp = \ETFsSFTP\SFTP::getInstance();
        $res = $sftp->auto_cycle();

        return rest_ensure_response(array("status" => $res));
    }
}