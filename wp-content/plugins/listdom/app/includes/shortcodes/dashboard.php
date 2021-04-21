<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Shortcodes_Dashboard')):

/**
 * Listdom Listdom Dashboard Shortcode Class.
 *
 * @class LSD_Shortcodes_Dashboard
 * @version	1.0.0
 */
class LSD_Shortcodes_Dashboard extends LSD_Shortcodes
{
    public $atts = array();
    public $page;
    public $url;
    public $mode;
    public $alert;
    public $settings;
    public $guest_status;
    public $listings;
    public $post;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function init()
    {
        // WP Libraries
        if(!function_exists('wp_terms_checklist')) include ABSPATH . 'wp-admin/includes/template.php';

        // Settings
        $this->settings = LSD_Options::settings();

        // Shortcode
        add_shortcode('listdom-dashboard', array($this, 'output'));

        // Save Listing
        add_action('wp_ajax_lsd_dashboard_listing_save', array($this, 'save'));
        add_action('wp_ajax_nopriv_lsd_dashboard_listing_save', array($this, 'save'));

        // Upload Featured Image
        add_action('wp_ajax_lsd_dashboard_listing_upload_featured_image', array($this, 'upload'));
        add_action('wp_ajax_nopriv_lsd_dashboard_listing_upload_featured_image', array($this, 'upload'));

        // Delete Listing
        add_action('wp_ajax_lsd_dashboard_listing_delete', array($this, 'delete'));
        add_action('wp_ajax_nopriv_lsd_dashboard_listing_delete', array($this, 'delete'));

        // Upload Gallery
        add_action('wp_ajax_lsd_dashboard_listing_upload_gallery', array($this, 'gallery'));
        add_action('wp_ajax_nopriv_lsd_dashboard_listing_upload_gallery', array($this, 'gallery'));
	}

    public function output($atts = array())
    {
        if($this->isLite())
        {
            return $this->alert($this->missFeatureMessage(esc_html__('Dashboard', 'listdom')), 'error');
        }

        // Include WordPress Media
        LSD_Assets::media();

        // Shortcode attributes
        $this->atts = is_array($atts) ? $atts : array();

        // Dashboard Page
        global $post;
        $this->page = $post;

        // Dashboard URL
        $this->url = get_permalink($this->page);

        // Mode
        $this->mode = isset($_GET['mode']) ? sanitize_text_field($_GET['mode']) : 'manage';

        // Guest Status
        $this->guest_status = (isset($this->settings['submission_guest']) and $this->settings['submission_guest']) ? true : false;

        // Payload
        LSD_Payload::set('dashboard', $this);

        // Dashboard
        if($this->mode == 'manage') return $this->manage();
        // Form
        elseif($this->mode == 'form') return $this->form();
        // Other Modes
        else return apply_filters('lsd_dashboard_modes', $this->alert(esc_html__('Not found!', 'listdom'), 'error'), $this);
	}

    public function manage()
    {
        if(!get_current_user_id() and !$this->guest_status)
        {
            return $this->alert(sprintf(esc_html__("Unfortunately you don't have permission to view this page. Please %s first.", 'listdom'), '<a href="'.wp_login_url($this->current_url()).'">'.esc_html__('login', 'listdom').'</a>'), 'error');
        }

        // Get Listings
        $query = array(
            'post_type' => LSD_Base::PTYPE_LISTING,
            'posts_per_page' => '-1',
            'post_status' => array('publish', 'pending', 'draft', 'trash', LSD_Base::STATUS_HOLD, LSD_Base::STATUS_EXPIRED),
        );

        // Filter by Author
        if(!current_user_can('edit_others_posts')) $query['author'] = get_current_user_id();

        // Apply Filters
        $query = apply_filters('lsd_dashboard_manage_query', $query);

        // Search Listings
        $this->listings = get_current_user_id() ? get_posts($query) : array();

        // Dashboard
        ob_start();
        include lsd_template('dashboard/manage.php');
        return ob_get_clean();
	}

    public function item($listing)
    {
        include lsd_template('dashboard/item.php');
	}

    public function form()
    {
        if(!current_user_can('edit_posts') and !$this->guest_status)
        {
            return $this->alert(esc_html__("Unfortunately you don't have permission to create or edit listings.", 'listdom'), 'error');
        }

        $id = isset($_GET['id']) ? ((int) sanitize_text_field($_GET['id'])) : 0;

        // Selected post is not a listing
        if($id > 0 and get_post_type($id) != LSD_Base::PTYPE_LISTING)
        {
            return $this->alert(esc_html__("Sorry! Selected post is not a listing.", 'listdom'), 'error');
        }

        // Show a warning to current user if modification of post is not possible for him/her
        if($id > 0 and !current_user_can('edit_post', $id))
        {
            return $this->alert(esc_html__("Sorry! You don't have access to modify this listing.", 'listdom'), 'error');
        }

        // Get Post Data
        $this->post = get_post($id);

        if($id <= 0)
        {
            $this->post = new stdClass();
            $this->post->ID = 0;
        }

        // Dashboard
        ob_start();
        include lsd_template('dashboard/form.php');
        return ob_get_clean();
    }

    public function is_enabled($module)
    {
        $enabled = true;

        // Module is disabled
        if(isset($this->settings['submission_module']) and isset($this->settings['submission_module'][$module]) and !$this->settings['submission_module'][$module]) $enabled = false;

        // Module is enabled only for admin and editor
        if(isset($this->settings['submission_module']) and isset($this->settings['submission_module'][$module]) and $this->settings['submission_module'][$module] == 2 and !current_user_can('edit_others_pages')) $enabled = false;

        // Apply Filters
        return apply_filters('lsd_dashboard_modules_status', $enabled, $module);
    }

    public function menus()
    {
        // Default Menus
        $menus = array(
            'manage' => array('label' => esc_html__('Dashboard', 'listdom'), 'id' => 'lsd_dashboard_menus_manage', 'url' => $this->url, 'icon' => 'fas fa-tachometer-alt'),
        );

        // Add Listing Menu
        if(current_user_can('edit_posts') or $this->guest_status) $menus['form'] = array('label' => esc_html__('Add Listing', 'listdom'), 'id' => 'lsd_dashboard_menus_form', 'url' => $this->add_qs_var('mode', 'form', $this->url), 'icon' => 'far fa-plus-square');

        // Logout Menu
        if(get_current_user_id()) $menus['logout'] = array('label' => esc_html__('Logout', 'listdom'), 'id' => 'lsd_dashboard_menus_logout', 'url' => wp_logout_url(), 'icon' => 'fas fa-sign-out-alt');

        // Apply Filters
        $menus = apply_filters('lsd_dashboard_menus', $menus, $this);

        // Current Page
        $current = isset($_GET['mode']) ? sanitize_text_field($_GET['mode']) : 'manage';

        $output = '<ul class="lsd-dashboard-menus">';
        foreach($menus as $key => $menu)
        {
            $target = isset($menu['target']) ? $menu['target'] : '_self';
            $icon = isset($menu['icon']) ? $menu['icon'] : 'fas fa-tachometer-alt';
            $id = isset($menu['id']) ? $menu['id'] : 'lsd_dashboard_menus_'.$key;

            $output .= '<li id="'.esc_attr($id).'" '.($current == $key ? 'class="lsd-active"' : '').'><i class="lsd-icon '.esc_attr($icon).'"></i><a href="'.esc_url($menu['url']).'" target="'.esc_attr($target).'">'.esc_html($menu['label']).'</a></li>';
        }

        $output .= '</ul>';
        return $output;
    }

	protected function get_form_link($listing_id = NULL)
    {
        $url = $this->add_qs_var('mode', 'form', $this->url);

        // Edit Mode
        if($listing_id) $url = $this->add_qs_var('id', $listing_id, $url);

        return $url;
    }

    public function save()
    {
        // Nonce is not set!
        if(!isset($_POST['_wpnonce'])) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is missing!', 'listdom')));

        // Nonce is not valid!
        if(!wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), 'lsd_dashboard')) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is not valid!', 'listdom')));

        $g_recaptcha_response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : NULL;
        if(!LSD_Main::grecaptcha_check($g_recaptcha_response)) $this->response(array('success' => 0, 'message' => esc_html__("Google recaptcha is invalid.", 'listdom')));

        // Additional Validations
        $valid = apply_filters('lsd_dashboard_validate_request', true);
        if($valid !== true) $this->response(array('success' => 0, 'message' => $valid));

        $id = isset($_POST['id']) ? ((int) sanitize_text_field($_POST['id'])) : 0;
        $lsd = isset($_POST['lsd']) ? $_POST['lsd'] : array();
        $tax = (isset($_POST['tax_input']) and is_array($_POST['tax_input'])) ? $_POST['tax_input'] : array();

        $post_title = isset($lsd['title']) ? sanitize_text_field($lsd['title']) : '';
        $post_content = isset($lsd['content']) ? $lsd['content'] : '';

        if(!trim($post_title)) $this->response(array('success' => 0, 'message' => esc_html__('Please fill listing title field!', 'listdom')));

        $guest_email = isset($lsd['guest_email']) ? $lsd['guest_email'] : '';
        if(!get_current_user_id() and !trim($guest_email)) $this->response(array('success' => 0, 'message' => esc_html__('Please insert your email!', 'listdom')));

        // Trigger Event
        do_action('lsd_dashboard_validation', $lsd, $id);

        // Post Status
        $status = apply_filters('lsd_dashboard_listing_status', 'pending', $lsd);
        if(current_user_can('publish_posts')) $status = 'publish';

        // Create New Listing
        if($id <= 0)
        {
            $post = array('post_title'=>$post_title, 'post_content'=>$post_content, 'post_type'=>LSD_Base::PTYPE_LISTING, 'post_status'=>$status);
            $id = wp_insert_post($post);
        }

        wp_update_post(array('ID'=>$id, 'post_title'=>$post_title, 'post_content'=>$post_content));

        // Tags
        $tags = isset($_POST['tags']) ? sanitize_text_field($_POST['tags']) : '';
        wp_set_post_terms($id, $tags, LSD_Base::TAX_TAG);

        // Locations
        $locations = (isset($tax[LSD_Base::TAX_LOCATION]) and is_array($tax[LSD_Base::TAX_LOCATION])) ? $tax[LSD_Base::TAX_LOCATION] : array();
        wp_set_post_terms($id, $locations, LSD_Base::TAX_LOCATION);

        // Features
        $features = (isset($tax[LSD_Base::TAX_FEATURE]) and is_array($tax[LSD_Base::TAX_FEATURE])) ? $tax[LSD_Base::TAX_FEATURE] : array();
        wp_set_post_terms($id, LSD_Taxonomies::name($features, LSD_Base::TAX_FEATURE), LSD_Base::TAX_FEATURE);

        // Labels
        $labels = (isset($tax[LSD_Base::TAX_LABEL]) and is_array($tax[LSD_Base::TAX_LABEL])) ? $tax[LSD_Base::TAX_LABEL] : array();
        wp_set_post_terms($id, LSD_Taxonomies::name($labels, LSD_Base::TAX_LABEL), LSD_Base::TAX_LABEL);

        // Featured Image
        $featured_image = isset($lsd['featured_image']) ? sanitize_text_field($lsd['featured_image']) : '';
        set_post_thumbnail($id, $featured_image);

        // Gallery
        if(isset($lsd['_gallery']) and is_array($lsd['_gallery']))
        {
            $lsd['gallery'] = $lsd['_gallery'];
            unset($lsd['_gallery']);
        }

        // Embeds
        if(isset($lsd['_embeds']) and is_array($lsd['_embeds']))
        {
            $lsd['embeds'] = $lsd['_embeds'];
            unset($lsd['_embeds']);
        }

        // Publish Listing
        if($status == 'publish' and get_post_status($id) != 'published') wp_publish_post($id);

        // Sanitization
        array_walk_recursive($lsd, 'sanitize_text_field');

        // Save the Data
        $entity = new LSD_Entity_Listing($id);
        $entity->save($lsd, true);

        $message = '';
        if($status == 'pending') $message = esc_html__('The listing has been submitted. It will be reviewed as soon as possible.', 'listdom');
        elseif($status == 'publish') $message = sprintf(esc_html__('The listing has been published. %s', 'listdom'), '<a href="'.get_permalink($id).'" target="_blank">'.esc_html__('View Listing', 'listdom').'</a>');

        // Trigger Event
        do_action('lsd_dashboard_save', $id, $lsd);

        // Response
        $this->response(array('success' => 1, 'message' => $message, 'data' => array('id' => $id)));
    }

    public function upload()
    {
        // User is not allowed to upload files
        if(!current_user_can('upload_files')) $this->response(array('success' => 0, 'message' => esc_html__('You are not allowed to upload files!', 'listdom')));

        // Nonce is not set!
        if(!isset($_POST['_wpnonce'])) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is missing!', 'listdom')));

        // Nonce is not valid!
        if(!wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), 'lsd_dashboard')) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is not valid!', 'listdom')));

        // Include the function
        if(!function_exists('wp_handle_upload')) require_once ABSPATH.'wp-admin/includes/file.php';

        $image = isset($_FILES['file']) ? $_FILES['file'] : NULL;

        // No file
        if(!$image) $this->response(array('success' => 0, 'message' => esc_html__('Please upload an image!', 'listdom')));

        $allowed = array('jpeg', 'jpg', 'png');

        $ex = explode('.', $image['name']);
        $extension = end($ex);

        // Invalid Extension
        if(!in_array(strtolower($extension), $allowed)) $this->response(array('success' => 0, 'message' => esc_html__('Only JPG and PNG images are allowed!', 'listdom')));

        $uploaded = wp_handle_upload($image, array('test_form' => false));

        $success = 0;
        $data = array();

        if($uploaded and !isset($uploaded['error']))
        {
            $success = 1;
            $message = esc_html__('The image is uploaded!', 'listdom');

            $attachment = array(
                'post_mime_type' => $uploaded['type'],
                'post_title' => '',
                'post_content' => '',
                'post_status' => 'inherit'
            );

            // Add as Attachment
            $attachment_id = wp_insert_attachment($attachment, $uploaded['file']);

            // Update Metadata
            require_once ABSPATH.'wp-admin/includes/image.php';
            wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $uploaded['file']));

            $data['attachment_id'] = $attachment_id;
            $data['url'] = $uploaded['url'];
        }
        else
        {
            $message = $uploaded['error'];
        }

        $this->response(array('success'=>$success, 'message'=>$message, 'data'=>$data));
    }

    public function delete()
    {
        // Nonce is not set!
        if(!isset($_POST['_lsdnonce'])) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is missing!', 'listdom')));

        // Nonce is not valid!
        if(!wp_verify_nonce(sanitize_text_field($_POST['_lsdnonce']), 'lsd_dashboard')) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is not valid!', 'listdom')));

        $id = isset($_POST['id']) ? ((int) sanitize_text_field($_POST['id'])) : 0;
        $listing = get_post($id);

        // Listing not Found!
        if(!isset($listing->ID)) $this->response(array('success' => 0));

        // Current User Cannot Remove Listing of Others
        if($listing->post_author != get_current_user_id() and !current_user_can('delete_others_posts')) $this->response(array('success' => 0));

        // Delete The Post
        wp_delete_post($id);

        // Response
        $this->response(array('success' => 1));
    }

    public function gallery()
    {
        // User is not allowed to upload files
        if(!current_user_can('upload_files')) $this->response(array('success' => 0, 'message' => esc_html__('You are not allowed to upload files!', 'listdom')));

        // Nonce is not set!
        if(!isset($_POST['_wpnonce'])) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is missing!', 'listdom')));

        // Nonce is not valid!
        if(!wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), 'lsd_dashboard')) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is not valid!', 'listdom')));

        // Include the function
        if(!function_exists('wp_handle_upload')) require_once ABSPATH.'wp-admin/includes/file.php';

        $images = (isset($_FILES['files']) and is_array($_FILES['files'])) ? $_FILES['files'] : array();

        // No images
        if(!count($images)) $this->response(array('success' => 0, 'message' => esc_html__('Please upload an image!', 'listdom')));

        // Allowed Extensions
        $allowed = array('jpeg', 'jpg', 'png');

        $success = 0;
        $data = array();

        $count = count($images['name']);
        for($i = 0; $i < $count; $i++)
        {
            $image = array(
                'name' => $images['name'][$i],
                'type' => $images['type'][$i],
                'tmp_name' => $images['tmp_name'][$i],
                'error' => $images['error'][$i],
                'size' => $images['size'][$i],
            );

            $ex = explode('.', $image['name']);
            $extension = end($ex);

            // Invalid Extension
            if(!in_array(strtolower($extension), $allowed)) continue;

            $uploaded = wp_handle_upload($image, array('test_form' => false));

            if($uploaded and !isset($uploaded['error']))
            {
                $success = 1;
                $attachment = array(
                    'post_mime_type' => $uploaded['type'],
                    'post_title' => '',
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                // Add as Attachment
                $attachment_id = wp_insert_attachment($attachment, $uploaded['file']);

                // Update Metadata
                require_once ABSPATH.'wp-admin/includes/image.php';
                wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $uploaded['file']));

                $data[] = array(
                    'id' => $attachment_id,
                    'url' => $uploaded['url']
                );
            }
        }

        $message = $success ? esc_html__('The images are uploaded!', 'listdom') : esc_html__('An error occurred!', 'listdom');
        $this->response(array('success'=>$success, 'message'=>$message, 'data'=>$data));
    }
}

endif;