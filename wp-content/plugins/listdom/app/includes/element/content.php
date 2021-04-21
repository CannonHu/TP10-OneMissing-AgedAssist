<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Element_Content')):

/**
 * Listdom Content Element Class.
 *
 * @class LSD_Element_Content
 * @version	1.0.0
 */
class LSD_Element_Content extends LSD_Element
{
    public $key = 'content';
    public $label;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->label = esc_html__('Listing Content', 'listdom');
	}

    public function get($content)
    {
        return $content;
    }

    public function excerpt($post_id, $limit = 15, $read_more = false)
    {
        // Post Excerpt
        $content = get_the_excerpt($post_id);

        // Post Content
        if(trim($content) == 0)
        {
            $content = apply_filters('the_content', get_post_field('post_content', $post_id));
            $content = str_replace(']]>', ']]&gt;', $content);
        }

        $words = explode(' ', strip_tags($content));
        $excerpt = array_slice($words, 0, $limit);

        return implode(' ', $excerpt).(count($words) > $limit ? ' ...' : '').((count($words) > $limit and $read_more) ? ' <a href="'.get_the_permalink($post_id).'" class="lsd-excerpt-read-more lsd-color-m-txt">['.esc_html__('More', 'listdom').']</a>' : '');
    }
}

endif;