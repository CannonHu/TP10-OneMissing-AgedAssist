<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Element_Share')):

/**
 * Listdom Share Element Class.
 *
 * @class LSD_Element_Share
 * @version	1.0.0
 */
class LSD_Element_Share extends LSD_Element
{
    public $key = 'share';
    public $label;
    public $layout;
    public $args;

    /**
	 * Constructor method
     * @param array $args
     * @param string $layout
	 */
	public function __construct($layout = 'full', $args = array())
    {
        // Call the parent constructor
        parent::__construct();

        $this->label = esc_html__('Share', 'listdom');
        $this->layout = $layout;
        $this->args = $args;
	}

	public function get($post_id = NULL)
    {
        if(is_null($post_id))
        {
            global $post;
            $post_id = $post->ID;
        }

        // Generate output
        ob_start();
        include lsd_template('elements/share.php');
        return ob_get_clean();
    }
}

endif;