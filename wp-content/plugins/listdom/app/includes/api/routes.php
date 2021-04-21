<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Routes')):

/**
 * Listdom API Routes Class.
 *
 * @class LSD_API_Routes
 * @version	1.0.0
 */
class LSD_API_Routes extends LSD_API
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}
    
    public function init()
    {
        add_action('rest_api_init', array($this, 'register'));
    }

    public function register()
    {
        // Validation Library
        $validation = new LSD_API_Validation();

        // I18n Controller
        $i18n = new LSD_API_Controllers_I18n();

        register_rest_route($this->namespace, 'languages', array(
            'methods'  => 'GET',
            'callback' => array($i18n, 'languages'),
            'permission_callback' => array($i18n, 'guest'),
        ));

        // Register Controller
        $register = new LSD_API_Controllers_Register();

        register_rest_route($this->namespace, 'register', array(
            'methods'  => 'POST',
            'callback' => array($register, 'perform'),
            'permission_callback' => array($register, 'guest'),
        ));

        // Login Controller
        $login = new LSD_API_Controllers_Login();

        register_rest_route($this->namespace, 'login', array(
            'methods'  => 'POST',
            'callback' => array($login, 'perform'),
            'permission_callback' => array($login, 'guest'),
        ));

        register_rest_route($this->namespace, 'login/key', array(
            'methods'  => 'POST',
            'callback' => array($login, 'key'),
            'permission_callback' => array($login, 'permission'),
        ));

        register_rest_route($this->namespace, 'login/redirect/(?P<key>[a-zA-Z0-9-]+)', array(
            'methods'  => 'GET',
            'callback' => array($login, 'redirect'),
            'permission_callback' => '__return_true',
        ));

        // Password Controller
        $password = new LSD_API_Controllers_Password();

        register_rest_route($this->namespace, 'forgot', array(
            'methods'  => 'POST',
            'callback' => array($password, 'forgot'),
            'permission_callback' => array($password, 'guest'),
        ));

        register_rest_route($this->namespace, 'password', array(
            'methods'  => 'POST',
            'callback' => array($password, 'update'),
            'permission_callback' => array($password, 'permission'),
        ));

        // Logout Controller
        $logout = new LSD_API_Controllers_Logout();

        register_rest_route($this->namespace, 'logout', array(
            'methods'  => 'POST',
            'callback' => array($logout, 'perform'),
            'permission_callback' => array($logout, 'permission'),
        ));

        // Profile Controller
        $profile = new LSD_API_Controllers_Profile();

        // Get Profile
        register_rest_route($this->namespace, 'profile', array(
            'methods'  => 'GET',
            'callback' => array($profile, 'get'),
            'permission_callback' => array($profile, 'permission'),
        ));

        // Update Profile
        register_rest_route($this->namespace, 'profile', array(
            'methods'  => 'PUT',
            'callback' => array($profile, 'update'),
            'permission_callback' => array($profile, 'permission'),
        ));

        // Taxonomies Controller
        $taxonomies = new LSD_API_Controllers_Taxonomies();

        // Get Taxonomies
        register_rest_route($this->namespace, 'taxonomies', array(
            'methods'  => 'GET',
            'callback' => array($taxonomies, 'get'),
            'permission_callback' => array($taxonomies, 'guest'),
        ));

        // Get Terms
        register_rest_route($this->namespace, 'taxonomies/(?P<taxonomy>[a-zA-Z0-9-]+)', array(
            'methods'  => 'GET',
            'callback' => array($taxonomies, 'terms'),
            'permission_callback' => array($taxonomies, 'guest'),
        ));

        // Images Controller
        $images = new LSD_API_Controllers_Images();

        // Upload Image
        register_rest_route($this->namespace, 'images', array(
            'methods'  => 'POST',
            'callback' => array($images, 'upload'),
            'permission_callback' => array($taxonomies, 'permission'),
        ));

        // Get Image
        register_rest_route($this->namespace, 'images/(?P<id>\d+)', array(
            'methods'  => 'GET',
            'callback' => array($images, 'get'),
            'permission_callback' => array($taxonomies, 'guest'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Search Modules Controller
        $sm = new LSD_API_Controllers_SearchModules();

        // Get All Search Modules
        register_rest_route($this->namespace, 'search-modules', array(
            'methods'  => 'GET',
            'callback' => array($sm, 'perform'),
            'permission_callback' => array($sm, 'guest'),
        ));

        // Get Search Module
        register_rest_route($this->namespace, 'search-modules/(?P<id>\d+)', array(
            'methods'  => 'GET',
            'callback' => array($sm, 'get'),
            'permission_callback' => array($sm, 'guest'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Listings Controller
        $listings = new LSD_API_Controllers_Listings();

        // Create Listing
        register_rest_route($this->namespace, 'listings', array(
            'methods'  => 'POST',
            'callback' => array($listings, 'create'),
            'permission_callback' => array($listings, 'permission'),
        ));

        // Edit Listing
        register_rest_route($this->namespace, 'listings', array(
            'methods'  => 'PUT',
            'callback' => array($listings, 'edit'),
            'permission_callback' => array($listings, 'permission'),
        ));

        // Trash Listing
        register_rest_route($this->namespace, 'listings/(?P<id>\d+)/trash', array(
            'methods'  => 'DELETE',
            'callback' => array($listings, 'trash'),
            'permission_callback' => array($listings, 'permission'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Delete Listing
        register_rest_route($this->namespace, 'listings/(?P<id>\d+)', array(
            'methods'  => 'DELETE',
            'callback' => array($listings, 'delete'),
            'permission_callback' => array($listings, 'permission'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Get Listing
        register_rest_route($this->namespace, 'listings/(?P<id>\d+)', array(
            'methods'  => 'GET',
            'callback' => array($listings, 'get'),
            'permission_callback' => array($listings, 'guest'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Contact Listing
        register_rest_route($this->namespace, 'listings/(?P<id>\d+)/contact', array(
            'methods'  => 'POST',
            'callback' => array($listings, 'contact'),
            'permission_callback' => array($listings, 'guest'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Report Abuse
        register_rest_route($this->namespace, 'listings/(?P<id>\d+)/abuse', array(
            'methods'  => 'POST',
            'callback' => array($listings, 'abuse'),
            'permission_callback' => array($listings, 'guest'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Listing Fields
        register_rest_route($this->namespace, 'listings/fields', array(
            'methods'  => 'GET',
            'callback' => array($listings, 'fields'),
            'permission_callback' => array($listings, 'guest'),
        ));

        // Map Controller
        $map = new LSD_API_Controllers_Map();

        // Listing Map
        register_rest_route($this->namespace, 'listings/(?P<id>\d+)/map', array(
            'methods'  => 'GET',
            'callback' => array($map, 'map'),
            'permission_callback' => array($map, 'guest'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Upsert Map
        register_rest_route($this->namespace, 'listings/(?P<id>\d+)/map-upsert', array(
            'methods'  => 'GET',
            'callback' => array($map, 'upsert'),
            'permission_callback' => array($map, 'permission'),
            'args' => array(
                'id' => array(
                    'validate_callback' => array($validation, 'numeric')
                ),
            )
        ));

        // Search Controller
        $search = new LSD_API_Controllers_Search();

        // Current User Listings
        register_rest_route($this->namespace, 'my-listings', array(
            'methods'  => 'GET',
            'callback' => array($search, 'my'),
            'permission_callback' => array($search, 'permission'),
        ));

        // Search Listings
        register_rest_route($this->namespace, 'search', array(
            'methods'  => 'GET',
            'callback' => array($search, 'search'),
            'permission_callback' => array($search, 'guest'),
        ));

        // Addons Controller
        $addons = new LSD_API_Controllers_Addons();

        // Search Listings
        register_rest_route($this->namespace, 'addons', array(
            'methods'  => 'GET',
            'callback' => array($addons, 'get'),
            'permission_callback' => array($addons, 'guest'),
        ));
    }
}

endif;