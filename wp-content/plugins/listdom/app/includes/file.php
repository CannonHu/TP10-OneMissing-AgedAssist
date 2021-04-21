<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_File')):

/**
 * Listdom File Class.
 *
 * @class LSD_File
 * @version	1.0.0
 */
class LSD_File extends LSD_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function read($path)
    {
        return file_get_contents($path);
    }

    public static function exists($path)
    {
        return file_exists($path);
    }

    public static function write($path, $content)
    {
        return file_put_contents($path, $content);
    }

    public static function append($path, $content)
    {
        return file_put_contents($path, $content, FILE_APPEND);
    }

    public static function delete($path)
    {
        unlink($path);
    }

    public static function download($url)
    {
        $request = wp_remote_get($url);
        $type = wp_remote_retrieve_header($request, 'content-type');

        if(!$type) return false;

        return wp_remote_retrieve_body($request);
    }

    public static function upload($file)
    {
        // Include the function
        if(!function_exists('wp_handle_upload')) require_once ABSPATH.'wp-admin/includes/file.php';

        $uploaded = wp_handle_upload($file, array('test_form' => false));
        if($uploaded and !isset($uploaded['error']))
        {
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

            return $attachment_id;
        }

        return 0;
    }
}

endif;