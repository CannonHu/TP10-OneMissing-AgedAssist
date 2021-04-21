<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins $this */

// Get Sort Options
$options = (isset($this->sorts['options']) and is_array($this->sorts['options'])) ? $this->sorts['options'] : array();

// Filter Enabled Options
$enableds = array();
foreach($options as $key=>$option)
{
    $status = isset($option['status']) ? $option['status'] : 0;
    if(!$status) continue;

    $enableds[$key] = $option;
}

// No Enabled Option
if(!count($enableds)) return '';
?>
<div class="lsd-row">
	<div class="lsd-col-12">
		<div class="lsd-view-sortbar-wrapper">
			<ul class="lsd-sortbar-list">
				<?php foreach($enableds as $key=>$option): ?>
				<li data-orderby="<?php echo esc_attr($key); ?>" data-order="<?php echo ($this->orderby == $key ? ($this->order == 'DESC' ? 'ASC' : 'DESC') : (isset($option['sort']) ? esc_attr($option['sort']) : 'DESC')); ?>" class="<?php echo ($this->orderby == $key ? 'lsd-active' : ''); ?>">
					<?php echo esc_html($option['name']); ?>
					<?php if($this->orderby == $key): ?>
					<i class="lsd-icon fas fa-sort-amount-<?php echo ($this->order == 'DESC' ? 'down' : 'up'); ?>" aria-hidden="true"></i>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
            <div class="lsd-sortbar-dropdown">
				<span>
                    <?php echo esc_html__('Sort By', 'listdom') ?>
                    <i class="lsd-icon fas fa-caret-right"></i>
				</span>
                <select>
                    <?php foreach($enableds as $key=>$option): ?>
                    <option value="<?php echo esc_attr($key); ?>" data-order="ASC" <?php echo (($this->orderby == $key and $this->order == 'ASC') ? 'selected="selected"' : ''); ?>><?php echo $option['name']; ?> &#8593;</option>
                    <option value="<?php echo esc_attr($key); ?>" data-order="DESC" <?php echo (($this->orderby == $key and $this->order == 'DESC') ? 'selected="selected"' : ''); ?>><?php echo $option['name']; ?> &#8595;</option>
                    <?php endforeach; ?>
                </select>
            </div>
		</div>
	</div>
</div>