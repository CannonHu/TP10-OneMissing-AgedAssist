<?php
// no direct access
defined('ABSPATH') or die();

// Display Per Listing
$displ = get_post_meta($post->ID, 'lsd_displ', true);

$style = isset($displ['style']) ? $displ['style'] : $this->details_page_options['general']['style'];
$elements = isset($displ['elements']) ? $displ['elements'] : $this->details_page_options['elements'];
?>
<div class="lsd-metabox lsd-metabox-display">
    <div class="lsd-form-row">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title'=>esc_html__('Style', 'listdom'),
            'for'=>'lsd_displ_style',
        )); ?></div>
        <div class="lsd-col-8"><?php echo LSD_Form::select(array(
            'id'=>'lsd_displ_style',
            'name'=>'lsd[displ][style]',
            'value'=>$style,
            'options'=>array(
                'style1'=>esc_html__('Style 1', 'listdom'),
                'style2'=>esc_html__('Style 2', 'listdom'),
            ),
        )); ?></div>
    </div>
    <div class="lsd-form-row">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title'=>esc_html__('Elements', 'listdom'),
        )); ?></div>
        <div class="lsd-col-8">
            <ul>
                <?php foreach($this->details_page_options['elements'] as $key=>$element): $elm = LSD_Element::instance($key); if(!$elm) continue; ?>
                <li>
                    <span><?php echo LSD_Form::switcher(array(
                        'id'=>'lsd_displ_elements_'.$key,
                        'name'=>'lsd[displ][elements]['.$key.'][enabled]',
                        'value'=>((isset($elements[$key]['enabled']) and $elements[$key]['enabled']) ? 1 : 0),
                    )); ?></span>
                    <span class="lsd-pl-3"><?php echo LSD_Form::label(array(
                        'title'=>$elm->label,
                        'for'=>'lsd_displ_elements_'.$key,
                    )); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>