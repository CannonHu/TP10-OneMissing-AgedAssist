<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Element_Categories')):

/**
 * Listdom Categories Element Class.
 *
 * @class LSD_Element_Categories
 * @version	1.0.0
 */
class LSD_Element_Categories extends LSD_Element
{
    public $key = 'categories';
    public $label;
    public $show_color;
    public $color_method;
    public $multiple_categories;

    /**
	 * Constructor method
     * @param boolean $show_color
     * @param boolean $multiple_categories
     * @param string $color_method
	 */
	public function __construct($show_color = true, $multiple_categories = false, $color_method = 'bg')
    {
        // Call the parent constructor
        parent::__construct();

        $this->label = esc_html__('Categories', 'listdom');
        $this->show_color = $show_color;
        $this->multiple_categories = $multiple_categories;
        $this->color_method = $color_method;
	}

    public function get($post_id = NULL)
    {
        if(is_null($post_id))
        {
            global $post;
            $post_id = $post->ID;
        }

        // Entity
        $entity = new LSD_Entity_Listing($post_id);

        if(!$this->multiple_categories)
        {
            $category = $entity->get_data_category();
            if(!$category) return '';
        }
        else
        {
            // Get All Categories
            $categories = wp_get_post_terms($post_id, LSD_Base::TAX_CATEGORY, array());
            if(!count($categories)) return '';
        }

        // Generate output
        ob_start();
        include lsd_template('elements/categories.php');
        return ob_get_clean();
    }

    public static function styles($category_id, $method = 'bg')
    {
        $color = get_term_meta($category_id, 'lsd_color', true);

        if($method == 'text') return 'style="color: '.esc_attr($color).';"';
        else
        {
            $text = LSD_Base::get_text_color($color);
            return 'style="background-color: '.esc_attr($color).'; color: '.esc_attr($text).';"';
        }
    }
}

endif;