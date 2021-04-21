<?php
// no direct access
defined('ABSPATH') or die();

// Listdom Skins
$skins = new LSD_Skins();

// Display Options
$options = get_post_meta($post->ID, 'lsd_display', true);
?>
<div class="lsd-metabox lsd-metabox-display-options">
    <div class="lsd-form-row">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title' => esc_html__('Skin', 'listdom'),
            'for' => 'lsd_display_options_skin',
        )); ?></div>
        <div class="lsd-col-6">
            <?php echo LSD_Form::select(array(
                'id' => 'lsd_display_options_skin',
                'name' => 'lsd[display][skin]',
                'options' => $skins->get_skins(),
                'value' => (isset($options['skin']) ? $options['skin'] : '')
            )); ?>
            <p class="description"><?php esc_html_e("Listdom supports different skins for showing listings.", 'listdom'); ?></p>
        </div>
    </div>
    <div id="lsd_skin_display_options_container">
        <?php foreach($skins->get_skins() as $skin=>$label): ?>
        <div class="lsd-skin-display-options" id="lsd_skin_display_options_<?php echo esc_attr($skin); ?>">
            <?php $this->include_html_file('metaboxes/shortcode/display-options/'.$skin.'.php', array('parameters'=>array('options'=>$options))); ?>
            <?php
                // Action for Third Party Plugins
                do_action('lsd_shortcode_display_options', $skin, $options);
            ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>