<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Controllers_Profile')):

/**
 * Listdom API Profile Controller Class.
 *
 * @class LSD_API_Controllers_Profile
 * @version	1.0.0
 */
class LSD_API_Controllers_Profile extends LSD_API_Controller
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function get(WP_REST_Request $request)
    {
        // Get Current User
        $user = wp_get_current_user();

        // Invalid User
        if(is_wp_error($user)) return $user;

        // Response
        return $this->response(array(
            'data' => array(
                'success' => 1,
                'user' => LSD_API_Resources_User::get($user),
            ),
            'status' => 200
        ));
	}

    public function update(WP_REST_Request $request)
    {
        $vars = $request->get_params();

        $first_name = isset($vars['first_name']) ? $vars['first_name'] : NULL;
        $last_name = isset($vars['last_name']) ? $vars['last_name'] : NULL;
        $description = isset($vars['description']) ? $vars['description'] : NULL;
        $phone = isset($vars['phone']) ? $vars['phone'] : NULL;
        $mobile = isset($vars['mobile']) ? $vars['mobile'] : NULL;
        $fax = isset($vars['fax']) ? $vars['fax'] : NULL;
        $job_title = isset($vars['job_title']) ? $vars['job_title'] : NULL;
        $linkedin = isset($vars['linkedin']) ? $vars['linkedin'] : NULL;
        $twitter = isset($vars['twitter']) ? $vars['twitter'] : NULL;
        $facebook = isset($vars['facebook']) ? $vars['facebook'] : NULL;
        $pinterest = isset($vars['pinterest']) ? $vars['pinterest'] : NULL;

        // Current User ID
        $user_id = get_current_user_id();

        // Update User
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => trim($first_name.' '.$last_name),
            'description' => $description,
        ));

        // Update Meta Data
        update_user_meta($user_id, 'lsd_phone', $phone);
        update_user_meta($user_id, 'lsd_mobile', $mobile);
        update_user_meta($user_id, 'lsd_fax', $fax);
        update_user_meta($user_id, 'lsd_job_title', $job_title);
        update_user_meta($user_id, 'lsd_linkedin', $linkedin);
        update_user_meta($user_id, 'lsd_twitter', $twitter);
        update_user_meta($user_id, 'lsd_facebook', $facebook);
        update_user_meta($user_id, 'lsd_pinterest', $pinterest);

        // Trigger Action
        do_action('lsd_api_user_profile_updated', $user_id, $request);

        // Response
        return $this->response(array(
            'data' => array(
                'success' => 1,
                'user' => LSD_API_Resources_User::get($user_id),
            ),
            'status' => 200
        ));
    }
}

endif;