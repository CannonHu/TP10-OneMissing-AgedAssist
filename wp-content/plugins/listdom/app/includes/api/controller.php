<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Controller')):

/**
 * Listdom API Controller Class.
 *
 * @class LSD_API_Controller
 * @version	1.0.0
 */
class LSD_API_Controller extends LSD_API
{
    /**
     * @var LSD_API_Validation
     */
    protected $validate;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        // Validation
        $this->validate = new LSD_API_Validation();
	}

    public function getUserToken($user_id)
    {
        $token = $this->str_random(40);
        update_user_meta($user_id, 'lsd_token', $token);

        return $token;
	}

    public function revokeUserToken($user_id)
    {
        delete_user_meta($user_id, 'lsd_token');
	}

    public function getLoginKey($user_id)
    {
        $token = $this->str_random(40);
        update_user_meta($user_id, 'lsd_login', $token);

        return $token;
    }

    public function revokeLoginKey($user_id)
    {
        delete_user_meta($user_id, 'lsd_login');
    }

    public function perform(WP_REST_Request $request)
    {
        $response = new WP_REST_Response(array('success' => 1));
        $response->set_status(200);

        return $response;
    }

    public function permission(WP_REST_Request $request)
    {
        // Validate API Token
        if(!$this->validate->APIToken($request, $request->get_header('lsd-token'))) return new WP_Error('invalid_api_token', esc_html__('Invalid API Token!', 'listdom'));

        // Validate User Token
        if(!$this->validate->UserToken($request, $request->get_header('lsd-user'))) return new WP_Error('invalid_user_token', esc_html__('Invalid User Token!', 'listdom'));

        return true;
	}

    public function guest(WP_REST_Request $request)
    {
        // Validate API Token
        if(!$this->validate->APIToken($request, $request->get_header('lsd-token'))) return new WP_Error('invalid_api_token', esc_html__('Invalid API Token!', 'listdom'));

        // Set Current User if Token Provided
        $this->validate->UserToken($request, $request->get_header('lsd-user'));

        return true;
    }

    public function response(Array $response)
    {
        $data = isset($response['data']) ? $response['data'] : array();
        $status = isset($response['status']) ? $response['status'] : 200;

        $wp = new WP_REST_Response($data);
        $wp->set_status($status);

        return $wp;
	}

    public function iframe($body)
    {
        $class = 'lsd-iframe-page';

        // Generate output
        ob_start();
        include lsd_template('iframe.php');
        return ob_get_clean();
    }
}

endif;