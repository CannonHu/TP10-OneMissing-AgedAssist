<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Element_Categories $this */

if(!$this->multiple_categories) echo '<a href="'.esc_url(get_term_link($category->term_id)).'" '.($this->show_color ? LSD_Element_Categories::styles($category->term_id, $this->color_method) : '').' '.lsd_schema()->category().'>'.esc_html($category->name).'</a>';
else
{
    foreach($categories as $category)
    {
        echo '<a href="'.esc_url(get_term_link($category->term_id)).'" '.($this->show_color ? LSD_Element_Categories::styles($category->term_id, $this->color_method) : '').' '.lsd_schema()->category().'>'.esc_html($category->name).'</a>';
    }
}