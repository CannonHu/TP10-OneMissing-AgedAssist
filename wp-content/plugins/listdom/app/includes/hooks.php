<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Hooks')):

/**
 * Listdom General Hooks Class.
 *
 * @class LSD_Hooks
 * @version	1.0.0
 */
class LSD_Hooks extends LSD_Base
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
        // Register Actions
        $this->actions();

        // Register Filters
        $this->filters();
    }

    public function actions()
    {
        add_action('admin_init', array($this, 'redirect_after_activation'));
        add_action('admin_notices', array('LSD_Flash', 'show'));

        // Iframe
        add_action('wp', array($this, 'raw'));

        // Uploader
        add_action('wp_ajax_lsd_uploader', array($this, 'upload'));
    }

    public function filters()
    {
        add_filter('lsd_skins_atts', array($this, 'apply_search'));
        add_filter('ajax_query_attachments_args', array($this, 'protect_wp_media'));
    }

    /**
     * Redirect the user to Listdom Dashboard after plugin activation
     * @return bool
     */
    public function redirect_after_activation()
    {
        // No need to redirect
        if(!get_option('lsd_activation_redirect', false)) return true;

        // Delete the option to don't do it again
        delete_option('lsd_activation_redirect');

        // Redirect to Listdom Dashboard
        wp_redirect(admin_url('/admin.php?page=listdom'));
        exit;
    }

    public function apply_search($atts)
    {
        // Get Search Form Options
        $sf = $this->get_sf();

        // There is no Search Options
        if(!$sf) return $atts;

        // Target Shortcode
        $shortcode = (isset($_GET['sf-shortcode']) and trim($_GET['sf-shortcode'])) ? sanitize_text_field($_GET['sf-shortcode']) : NULL;

        // Validate the Shortcode
        if($shortcode and (!isset($atts['id']) or (isset($atts['id']) and $atts['id'] != $shortcode))) return $atts;

        // Set the Filter Array
        if(!isset($atts['lsd_filter'])) $atts['lsd_filter'] = array();

        // Keyword
        if(isset($sf['s']) and trim($sf['s']) != '')
        {
            $atts['lsd_filter']['s'] = $sf['s'];
        }

        // Category
        if(isset($sf[LSD_Base::TAX_CATEGORY]) and (is_array($sf[LSD_Base::TAX_CATEGORY]) or (!is_array($sf[LSD_Base::TAX_CATEGORY]) and trim($sf[LSD_Base::TAX_CATEGORY]) != '')))
        {
            $atts['lsd_filter'][LSD_Base::TAX_CATEGORY] = is_array($sf[LSD_Base::TAX_CATEGORY]) ? $sf[LSD_Base::TAX_CATEGORY] : array($sf[LSD_Base::TAX_CATEGORY]);
        }

        // Location
        if(isset($sf[LSD_Base::TAX_LOCATION]) and (is_array($sf[LSD_Base::TAX_LOCATION]) or (!is_array($sf[LSD_Base::TAX_LOCATION]) and trim($sf[LSD_Base::TAX_LOCATION]) != '')))
        {
            $atts['lsd_filter'][LSD_Base::TAX_LOCATION] = is_array($sf[LSD_Base::TAX_LOCATION]) ? $sf[LSD_Base::TAX_LOCATION] : array($sf[LSD_Base::TAX_LOCATION]);
        }

        // Tag
        if(isset($sf[LSD_Base::TAX_TAG]) and (is_array($sf[LSD_Base::TAX_TAG]) or (!is_array($sf[LSD_Base::TAX_TAG]) and trim($sf[LSD_Base::TAX_TAG]) != '')))
        {
            if(is_array($sf[LSD_Base::TAX_TAG]))
            {
                $atts['lsd_filter'][LSD_Base::TAX_TAG] = $sf[LSD_Base::TAX_TAG];
            }
            else
            {
                $term = get_term($sf[LSD_Base::TAX_TAG]);
                $atts['lsd_filter'][LSD_Base::TAX_TAG] = ($term and isset($term->name)) ? $term->name : '';
            }
        }

        // Feature
        if(isset($sf[LSD_Base::TAX_FEATURE]) and (is_array($sf[LSD_Base::TAX_FEATURE]) or (!is_array($sf[LSD_Base::TAX_FEATURE]) and trim($sf[LSD_Base::TAX_FEATURE]) != '')))
        {
            $atts['lsd_filter'][LSD_Base::TAX_FEATURE] = is_array($sf[LSD_Base::TAX_FEATURE]) ? $sf[LSD_Base::TAX_FEATURE] : array($sf[LSD_Base::TAX_FEATURE]);
        }

        // Label
        if(isset($sf[LSD_Base::TAX_LABEL]) and (is_array($sf[LSD_Base::TAX_LABEL]) or (!is_array($sf[LSD_Base::TAX_LABEL]) and trim($sf[LSD_Base::TAX_LABEL]) != '')))
        {
            $atts['lsd_filter'][LSD_Base::TAX_LABEL] = is_array($sf[LSD_Base::TAX_LABEL]) ? $sf[LSD_Base::TAX_LABEL] : array($sf[LSD_Base::TAX_LABEL]);
        }

        // Attributes
        if(isset($sf['attributes']) and is_array($sf['attributes']) and count($sf['attributes']))
        {
            $atts['lsd_filter']['attributes'] = $sf['attributes'];
        }

        // Radius
        if(isset($sf['circle']) and is_array($sf['circle']) and count($sf['circle']))
        {
            $atts['lsd_filter']['circle'] = $sf['circle'];
        }

        // Inquiry Period
        if(isset($sf['period']) and is_array($sf['period']) and count($sf['period']))
        {
            // Inquiry Key
            if(!isset($atts['lsd_filter']['inquiry'])) $atts['lsd_filter']['inquiry'] = array();

            $atts['lsd_filter']['inquiry']['period'] = $sf['period'];
        }

        // Inquiry Capacity
        if((isset($sf['adults']) and trim($sf['adults'])) or (isset($sf['children']) and trim($sf['children'])))
        {
            // Inquiry Key
            if(!isset($atts['lsd_filter']['inquiry'])) $atts['lsd_filter']['inquiry'] = array();

            if(isset($sf['adults']) and trim($sf['adults'])) $atts['lsd_filter']['inquiry']['adults'] = $sf['adults'];
            if(isset($sf['children']) and trim($sf['children'])) $atts['lsd_filter']['inquiry']['children'] = $sf['children'];
        }

        return $atts;
    }

    public function protect_wp_media($query)
    {
        $user_id = get_current_user_id();
        if($user_id and !current_user_can('administrator') and !current_user_can('editor')) $query['author'] = $user_id;

        return $query;
    }

    public function raw()
    {
        $ep = LSD_Endpoints::is();
        if($ep === 'raw')
        {
            echo (new LSD_Endpoints_Raw())->output();
            exit;
        }
    }

    public function upload()
    {
        // User is not allowed to upload files
        if(!current_user_can('upload_files')) $this->response(array('success' => 0, 'message' => esc_html__('You are not allowed to upload files!', 'listdom')));

        // Nonce is not set!
        if(!isset($_POST['_wpnonce'])) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is missing!', 'listdom')));

        // Unique Key
        $key = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '';

        // Nonce is not valid!
        if(!wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), 'lsd_uploader_'.$key)) $this->response(array('success' => 0, 'message' => esc_html__('Security nonce is not valid!', 'listdom')));

        // Include the function
        if(!function_exists('wp_handle_upload')) require_once ABSPATH.'wp-admin/includes/file.php';

        $files = (isset($_FILES['files']) and is_array($_FILES['files'])) ? $_FILES['files'] : array();

        // No files
        if(!count($files)) $this->response(array('success' => 0, 'message' => esc_html__('Please upload a file!', 'listdom')));

        // Allowed Extensions
        $allowed = array('jpeg', 'jpg', 'png', 'pdf', 'zip', 'gif');

        $success = 0;
        $data = array();

        $count = count($files['name']);
        for($i = 0; $i < $count; $i++)
        {
            $file = array(
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            );

            $ex = explode('.', $file['name']);
            $extension = end($ex);

            // Invalid Extension
            if(!in_array(strtolower($extension), $allowed)) continue;

            $uploaded = wp_handle_upload($file, array('test_form' => false));

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

        $message = $success ? esc_html__('The files are uploaded!', 'listdom') : esc_html__('An error occurred!', 'listdom');
        $this->response(array('success'=>$success, 'message'=>$message, 'data'=>$data));
    }
}

endif;