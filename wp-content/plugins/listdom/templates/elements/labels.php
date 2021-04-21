<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Element_Labels $this */

$labels = wp_get_post_terms($post_id, LSD_Base::TAX_LABEL);
if(!is_array($labels) or (is_array($labels) and !count($labels))) return '';
?>
<ul class="lsd-labels-list">
    <?php foreach($labels as $label): ?>
    <li class="lsd-labels-list-item"><a <?php echo LSD_Element_Labels::styles($label->term_id); ?> href="<?php echo esc_url(get_term_link($label->term_id, LSD_Base::TAX_LABEL)); ?>"><?php echo esc_html($label->name); ?></a></li>
    <?php endforeach; ?>
</ul>