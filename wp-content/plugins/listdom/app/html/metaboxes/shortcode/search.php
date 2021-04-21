<?php
// no direct access
defined('ABSPATH') or die();

// Search Options
$search = get_post_meta($post->ID, 'lsd_search', true);
?>
<div class="lsd-metabox lsd-metabox-search">
    <div>
        <div><?php echo LSD_Form::label(array(
            'title' => esc_html__('Search Form', 'listdom'),
            'for' => 'lsd_search_shortcode',
        )); ?></div>
        <div>
            <?php echo LSD_Form::searches(array(
                'id' => 'lsd_search_shortcode',
                'name' => 'lsd[search][shortcode]',
                'show_empty' => true,
                'value' => (isset($search['shortcode']) ? $search['shortcode'] : '')
            )); ?>
            <p class="description"><?php esc_html_e("Include a search form to the skin. Search Form is disabled by default.", 'listdom'); ?></p>
        </div>
    </div>
    <div>
        <div><?php echo LSD_Form::label(array(
            'title' => esc_html__('Search Position', 'listdom'),
            'for' => 'lsd_search_position',
        )); ?></div>
        <div>
            <?php echo LSD_Form::select(array(
                'id' => 'lsd_search_position',
                'name' => 'lsd[search][position]',
                'options' => array('top'=>esc_html__('Show on top', 'listdom'), 'before_listview'=>esc_html__('Show before list view', 'listdom')),
                'value' => (isset($search['position']) ? $search['position'] : 'top')
            )); ?>
        </div>
    </div>
</div>