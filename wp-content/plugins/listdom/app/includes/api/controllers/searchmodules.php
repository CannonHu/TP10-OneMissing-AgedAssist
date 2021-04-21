<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Controllers_SearchModules')):

/**
 * Listdom API Search Modules Controller Class.
 *
 * @class LSD_API_Controllers_SearchModules
 * @version	1.0.0
 */
class LSD_API_Controllers_SearchModules extends LSD_API_Controller
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function perform(WP_REST_Request $request)
    {
        $searches = get_posts(array(
            'post_type' => LSD_Base::PTYPE_SEARCH,
            'posts_per_page' => '-1'
        ));

        $ids = array();
        foreach($searches as $search) $ids[] = $search->ID;

        // Response
        return $this->response(array(
            'data' => array(
                'success' => 1,
                'searches' => LSD_API_Resources_SearchModule::collection($ids),
            ),
            'status' => 200
        ));
	}

    public function get(WP_REST_Request $request)
    {
        $id = $request->get_param('id');

        // Search
        $search = get_post($id);

        // Not Found!
        if(!$search or ($search and isset($search->post_type) and $search->post_type !== LSD_Base::PTYPE_SEARCH)) return $this->response(array(
            'data' => new WP_Error('404', esc_html__('Search module not found!', 'listdom')),
            'status' => 404,
        ));

        // Response
        return $this->response(array(
            'data' => array(
                'success' => 1,
                'searches' => LSD_API_Resources_SearchModule::get($id),
            ),
            'status' => 200
        ));
    }
}

endif;