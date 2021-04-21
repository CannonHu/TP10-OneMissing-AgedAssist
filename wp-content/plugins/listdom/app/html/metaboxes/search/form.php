<?php
// no direct access
defined('ABSPATH') or die();

$form = get_post_meta($post->ID, 'lsd_form', true);
if(!is_array($form)) $form = array();
?>
<div class="lsd-metabox lsd-search-form-metabox">
    <div class="lsd-row">
        <div class="lsd-col-12">
            <?php echo LSD_Form::label(array(
                'title' => esc_html__('Style', 'listdom'),
                'for' => 'lsd_search_form_style',
            )); ?>
            <?php echo LSD_Form::select(array(
                'id' => 'lsd_search_form_style',
                'name' => 'lsd[form][style]',
                'value' => (isset($form['style']) and $form['style']) ? $form['style'] : NULL,
                'options' => array('default' => esc_html__('Default', 'listdom'), 'sidebar' => esc_html__('Sidebar', 'listdom'), 'float' => esc_html__('Float', 'listdom')),
                'class' => 'widefat',
            )); ?>
        </div>
    </div>
    <div class="lsd-row">
        <div class="lsd-col-12">
            <?php echo LSD_Form::label(array(
                'title' => esc_html__('Results Page', 'listdom'),
                'for' => 'lsd_search_form_results_page',
            )); ?>
            <?php echo LSD_Form::pages(array(
                'id' => 'lsd_search_form_results_page',
                'name' => 'lsd[form][page]',
                'value' => (isset($form['page']) and $form['page']) ? $form['page'] : NULL,
                'class' => 'widefat',
                'show_empty' => true,
            )); ?>
            <p class="description"><?php echo esc_html__("If you want to include it in a skin you don't need to select results page and shortcode. You can leave them empty!", 'listdom'); ?></p>
        </div>
    </div>
    <div class="lsd-row">
        <div class="lsd-col-12">
            <?php echo LSD_Form::label(array(
                'title' => esc_html__('Target Shortcode', 'listdom'),
                'for' => 'lsd_search_form_shortcode',
            )); ?>
            <?php echo LSD_Form::shortcodes(array(
                'id' => 'lsd_search_form_shortcode',
                'name' => 'lsd[form][shortcode]',
                'value' => (isset($form['shortcode']) and $form['shortcode']) ? $form['shortcode'] : NULL,
                'class' => 'widefat',
                'only_archive_skins' => true,
                'show_empty' => true,
            )); ?>
            <p class="description"><?php echo sprintf(esc_html__('The search widget send the query to %s option so you should select the page that you want to see results there! Also if there are multiple shortcode / widgets in your selected page, then you should specify %s too otherwise all the shortcodes / widgets will be filtered!', 'listdom'), '<strong>'.esc_html__('Results Page', 'listdom').'</strong>', '<strong>'.esc_html__('Target Shortcode', 'listdom').'</strong>'); ?></p>
        </div>
    </div>
    <div class="lsd-row">
        <div class="lsd-col-12 lsd-search-form-criteria-row">
            <?php echo LSD_Form::label(array(
                'title' => esc_html__('Display Criteria', 'listdom'),
                'for' => 'lsd_search_form_criteria',
            )); ?>
            <?php echo LSD_Form::switcher(array(
                'id' => 'lsd_search_form_criteria',
                'name' => 'lsd[form][criteria]',
                'value' => (isset($form['criteria']) and $form['criteria']) ? $form['criteria'] : 0,
            )); ?>
        </div>
    </div>
</div>