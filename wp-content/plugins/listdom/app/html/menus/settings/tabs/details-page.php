<?php
// no direct access
defined('ABSPATH') or die();

$details_page = LSD_Options::details_page();

$templates = array();
$templates[''] = esc_html__('Default Layout', 'listdom');
foreach(get_page_templates() as $label=>$tpl) $templates[$tpl] = $label;
?>
<div class="lsd-settings-wrap" id="lsd_settings_details_page_wp">
    <form id="lsd_settings_form">
        <div class="lsd-form-row">
            <div class="lsd-col-4 lsd-pr-4">
                <h3><?php esc_html_e('General', 'listdom'); ?></h3>
                <div class="lsd-form-row">
                    <div class="lsd-col-6"><?php echo LSD_Form::label(array(
                        'title'=>esc_html__('Style', 'listdom'),
                        'for'=>'lsd_details_page_style',
                    )); ?></div>
                    <div class="lsd-col-6"><?php echo LSD_Form::select(array(
                        'id'=>'lsd_details_page_style',
                        'name'=>'lsd[general][style]',
                        'value'=>(isset($details_page['general']['style']) ? $details_page['general']['style'] : 'style1'),
                        'options'=>array(
                            'style1'=>esc_html__('Style 1', 'listdom'),
                            'style2'=>esc_html__('Style 2', 'listdom'),
                        ),
                    )); ?></div>
                </div>
                <div class="lsd-form-row lsd-style-dependency lsd-style-dependency-style1">
                    <div class="lsd-col-12">
                        <p class="description">
                            <?php esc_html_e('You can change the order and configuration of elements using the right side panel.', 'listdom'); ?>
                        </p>
                    </div>
                </div>
                <div class="lsd-form-row lsd-style-dependency lsd-style-dependency-style2">
                    <div class="lsd-col-12">
                        <p class="description">
                            <?php esc_html_e("You can disable / enable the elements and change their configuration using the right side panel. Order of elements won't be considered and they will appear on their own position if enabled.", 'listdom'); ?>
                        </p>
                    </div>
                </div>
                <div class="lsd-form-row">
                    <div class="lsd-col-6"><?php echo LSD_Form::label(array(
                        'title'=>esc_html__('Theme Layout', 'listdom'),
                        'for'=>'lsd_theme_template',
                    )); ?></div>
                    <div class="lsd-col-6"><?php echo LSD_Form::select(array(
                        'id'=>'lsd_theme_template',
                        'name'=>'lsd[general][theme_template]',
                        'value'=>(isset($details_page['general']['theme_template']) ? $details_page['general']['theme_template'] : ''),
                        'options'=>$templates
                    )); ?></div>
                </div>
                <div class="lsd-form-row">
                    <div class="lsd-col-12">
                        <p class="description">
                            <?php echo sprintf(esc_html__("Some themes supports multiple layouts for %s so you can select your desired layout for listing details pages. If you're not sure about this option, just select default. Also don't select an archive layout if your theme supports archive layouts as well.", 'listdom'), '<strong>'.esc_html__('single pages', 'listdom').'</strong>'); ?>
                        </p>
                    </div>
                </div>
                <div class="lsd-form-row">
                    <?php if($this->isLite()): echo LSD_Base::alert($this->missFeatureMessage(esc_html__('Display Options Per Listing', 'listdom')), 'warning'); ?>
                    <?php else: ?>
                    <div class="lsd-col-6"><?php echo LSD_Form::label(array(
                        'title'=>esc_html__('Display Options Per Listing', 'listdom'),
                        'for'=>'lsd_displ',
                    )); ?></div>
                    <div class="lsd-col-6"><?php echo LSD_Form::select(array(
                        'id'=>'lsd_displ',
                        'name'=>'lsd[general][displ]',
                        'options' => array(
                            0 => esc_html__('Disabled', 'listdom'),
                            'admin' => esc_html__('Admin Only', 'listdom'),
                            'owner' => esc_html__('Admin & Listing Owner', 'listdom'),
                        ),
                        'value'=>(isset($details_page['general']['displ']) ? $details_page['general']['displ'] : 0),
                    )); ?></div>
                    <div class="lsd-col-12">
                        <p class="description">
                            <?php echo esc_html__("You're able to disable / enable elements per listing or select a certain layout for each listing. You can select who can access to the display options here!", 'listdom'); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="lsd-form-row">
                    <div class="lsd-col-6"><?php echo LSD_Form::label(array(
                        'title'=>esc_html__('Comments', 'listdom'),
                        'for'=>'lsd_comments',
                    )); ?></div>
                    <div class="lsd-col-6"><?php echo LSD_Form::switcher(array(
                        'id'=>'lsd_comments',
                        'name'=>'lsd[general][comments]',
                        'value'=>(isset($details_page['general']['comments']) ? $details_page['general']['comments'] : 0),
                    )); ?></div>
                </div>
            </div>
            <div class="lsd-col-8">
                <h3><?php esc_html_e('Elements', 'listdom'); ?></h3>
                <div class="lsd-form-row">
                    <div class="lsd-col-12">
                        <ul class="lsd-elements lsd-sortable">
                            <?php foreach($details_page['elements'] as $key=>$element): $elm = LSD_Element::instance($key); if(!is_object($elm)) continue; ?>
                                <li id="lsd_element_<?php echo esc_attr($key); ?>" class="<?php echo (!$element['enabled'] ? 'lsd-element-disabled' : ''); ?> <?php echo ((in_array($key, array('attributes', 'embed')) and $this->isLite()) ? 'lsd-element-need-pro' : ''); ?>">
                                    <?php echo LSD_Kses::form($elm->form($element)); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="lsd-spacer-10"></div>
        <div class="lsd-form-row">
			<div class="lsd-col-12">
				<?php LSD_Form::nonce('lsd_settings_form'); ?>
				<?php echo LSD_Form::submit(array(
					'label' => esc_html__('Save', 'listdom'),
					'id' => 'lsd_settings_save_button',
                    'class' => 'button button-hero button-primary'
				)); ?>
			</div>
        </div>
    </form>
</div>
<script>
jQuery('#lsd_settings_form').on('submit', function(event)
{
    event.preventDefault();

    // Add loading Class to the button
    jQuery("#lsd_settings_save_button").addClass('loading').html('<i class="lsd-icon fa fa-spinner fa-pulse fa-fw"></i>');

    var settings = jQuery("#lsd_settings_form").serialize();
    jQuery.ajax(
    {
        type: "POST",
        url: ajaxurl,
        data: "action=lsd_save_details_page&" + settings,
        success: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_settings_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        },
        error: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_settings_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        }
    });
});
</script>