<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Element_Availability $this */

$availability = get_post_meta($post_id, 'lsd_ava', true);
if(!is_array($availability) or (is_array($availability) and !count($availability))) return '';

$day = $this->day ? $this->day : current_time('N');
?>
<?php 
	/** One Day **/ if($this->oneday): 
	$today = isset($availability[$day]) ? $availability[$day] : array(); 
	$isoffday = (isset($today['off']) and $today['off']);
?>
<div class="lsd-ava-one-day<?php if($isoffday) echo " lsd-ava-one-day-off" ?>">
    <i class="lsd-icon far fa-calendar-alt" aria-hidden="true"></i>
    <span class="lsd-ava-hour"><?php echo ($isoffday ? esc_html__('Off', 'listdom') : esc_html($today['hours'])); ?></span>
</div>
<?php /** Weekly **/ else: ?>
<div class="lsd-ava-week">
    <?php foreach(LSD_Main::get_weekdays() as $weekday): $daycode = $weekday['code']; ?>
    <div class="lsd-ava-weekday<?php if(isset($availability[$daycode]) and isset($availability[$daycode]['off']) and $availability[$daycode]['off']) echo ' lsd-ava-offday'; ?>">
		<div class="lsd-ava-weekday-wrapper">
			<div class="lsd-row">
				<div class="lsd-col-4 lsd-ava-weekday-column">
					<?php echo esc_html($weekday['label']); ?>
				</div>
				<div class="lsd-col-8">
					<div class="lsd-ava-hours-column">
						<?php if(isset($availability[$daycode]) and isset($availability[$daycode]['off']) and $availability[$daycode]['off']): ?>
							<?php esc_html_e('Off', 'listdom'); ?>
						<?php elseif(isset($availability[$daycode]) and isset($availability[$daycode]['hours'])): ?>
							<span <?php echo lsd_schema()->openingHours(); ?>><?php echo esc_html($availability[$daycode]['hours']); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif;