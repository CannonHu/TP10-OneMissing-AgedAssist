<?php
// no direct access
defined('ABSPATH') or die();

$terms = wp_get_post_terms($post_id, LSD_Base::TAX_FEATURE, array());
if(!count($terms)) return '';

$text = '';
foreach($terms as $term)
{
    $itemprop = get_term_meta($term->term_id, 'lsd_itemprop', true);
    $text .= '<span'.($itemprop ? ' '.lsd_schema()->prop(esc_url($itemprop)) : '').'>'.esc_html($term->name).'</span> '.esc_html($this->separator).' ';
}
?>
<div><?php echo trim($text, ' '.$this->separator); ?></div>