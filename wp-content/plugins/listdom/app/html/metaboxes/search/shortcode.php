<?php
// no direct access
defined('ABSPATH') or die();
?>
<div class="lsd-metabox lsd-metabox-search-shortcode">
    <div class="lsd-shortcode"><?php echo '[listdom-search id="'.esc_html($post->ID).'"]'; ?></div>
    <p class="description"><?php esc_html_e("Either insert this shortcode into any page, widget or select this search form in the Listdom shortcodes.", 'listdom'); ?></p>
    <?php /* Security Nonce */ LSD_Form::nonce('lsd_search_cpt', '_lsdnonce'); ?>
</div>