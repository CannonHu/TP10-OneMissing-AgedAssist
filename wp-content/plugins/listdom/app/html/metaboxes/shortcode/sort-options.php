<?php
// no direct access
defined('ABSPATH') or die();

// Sorts
$sorts = get_post_meta($post->ID, 'lsd_sorts', true);

// Apply default values
if(!is_array($sorts) or (is_array($sorts) and !count($sorts))) $sorts = LSD_Options::defaults('sorts');

// Available Options
$base_options = $this->get_available_sort_options();

if(!isset($sorts['options'])) $options = $base_options;
else
{
    $options = $sorts['options'];
    foreach($base_options as $k=>$b) if(!isset($options[$k])) $options[$k] = $b;
}
?>
<div class="lsd-metabox lsd-metabox-sort-options">
    <div class="lsd-mt-4">
        <div class="lsd-form-row">
            <div class="lsd-col-8">
                <label for="lsd_sort_options_status"><?php esc_html_e('Display', 'listdom'); ?></label>
            </div>
            <div class="lsd-col-4 lsd-text-right">
                <?php echo LSD_Form::switcher(array(
                    'id' => 'lsd_sort_options_status',
                    'toggle' => '#lsd_sort_options_toggle',
                    'name' => 'lsd[sorts][display]',
                    'value' => (isset($sorts['display']) ? $sorts['display'] : '1')
                )); ?>
            </div>
        </div>
    </div>
    <div class="lsd-sortable <?php echo ((isset($sorts['display']) and $sorts['display']) ? '' : 'lsd-util-hide'); ?>" id="lsd_sort_options_toggle">
        <?php foreach($options as $key=>$option): $base = (isset($base_options[$key])) ? $base_options[$key] : array(); $status = isset($option['status']) ? $option['status'] : $base['status']; ?>
        <div class="lsd-metabox-sort-option lsd-mt-4" id="lsd-sort-options-<?php echo esc_attr($key); ?>">
            <div class="lsd-form-row">
                <div class="lsd-col-1 lsd-cursor-move lsd-text-left">
                    <i class="lsd-icon fas fa-arrows-alt"></i>
                </div>
                <div class="lsd-col-9">
                    <strong><?php echo esc_html($base['name']); ?></strong>
                </div>
                <div class="lsd-col-1 lsd-cursor-pointer lsd-text-right lsd-sort-option-toggle" data-key="<?php echo esc_attr($key); ?>">
                    <i class="lsd-icon fa fa-<?php echo ($status ? 'check' : 'minus-circle'); ?>"></i>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-12">
                    <input type="hidden" name="lsd[sorts][options][<?php echo esc_attr($key); ?>][status]" value="<?php echo esc_attr($status); ?>" id="lsd-sort-options-<?php echo esc_attr($key); ?>-status">
                    <input type="text" name="lsd[sorts][options][<?php echo esc_attr($key); ?>][name]" placeholder="<?php esc_attr_e('Name', 'listdom'); ?>" value="<?php echo (isset($option['name']) ? $option['name'] : $base['name']); ?>" <?php echo ($status ? '' : 'disabled="disabled"'); ?>>
                    <select name="lsd[sorts][options][<?php echo esc_attr($key); ?>][order]" title="<?php esc_attr_e('Default Order', 'listdom'); ?>" <?php echo ($status ? '' : 'disabled="disabled"'); ?>>
                        <option value="DESC" <?php echo ((isset($option['order']) ? $option['order'] : $base['order']) == 'DESC' ? 'selected="selected"' : ''); ?>><?php echo esc_html__('Descending', 'listdom'); ?></option>
                        <option value="ASC" <?php echo ((isset($option['order']) ? $option['order'] : $base['order']) == 'ASC' ? 'selected="selected"' : ''); ?>><?php echo esc_html__('Ascending', 'listdom'); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>