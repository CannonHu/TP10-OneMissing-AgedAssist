<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins_Carousel $this */

$ids = $this->listings;
?>
<div class="lsd-skin-<?php echo esc_attr($this->id); ?>-carousel lsd-owl-carousel">
    <?php foreach($ids as $id): $listing = new LSD_Entity_Listing($id); ?>
    <div class="lsd-listing" <?php echo lsd_schema()->scope()->type(NULL, $listing->get_data_category()); ?>>
		<div>
			<div class="lsd-listing-image <?php echo esc_attr($listing->image_class_wrapper()); ?>">
				<?php echo LSD_Kses::element($listing->get_cover_image()); ?>

				<?php if($this->display_labels): ?>
				<div class="lsd-listing-labels">
					<?php echo LSD_Kses::element($listing->get_labels()); ?>
				</div>
				<?php endif; ?>

				<div class="lsd-listing-favorite">
					<?php echo LSD_Kses::element($listing->get_favorite_button()); ?>
				</div>

				<div class="lsd-listing-availability">
					<?php echo LSD_Kses::element($listing->get_availability(true)); ?>
				</div>
			</div>

			<div class="lsd-listing-body">

				<div class="lsd-listing-top-bar lsd-row">
					<div class="lsd-col-8">
						<div class="lsd-listing-category">
							<?php echo LSD_Kses::element($listing->get_categories(true, false, 'text')); ?>
						</div>
					</div>
					<div class="lsd-col-4">
						<div class="lsd-listing-price-class">
							<?php echo LSD_Kses::element($listing->get_price_class()); ?>
						</div>
					</div>
				</div>

				<h3 class="lsd-listing-title" <?php echo lsd_schema()->name(); ?>>
                    <?php echo LSD_Kses::element($this->get_title_tag($listing)); ?>
                    <?php echo ($listing->is_claimed() ? '<i class="lsd-icon fas fa-check-square" title="'.esc_attr__('Verified', 'listdom').'"></i>' : ''); ?>
				</h3>

				<p class="lsd-listing-content" <?php echo lsd_schema()->description(); ?>>
					<?php echo LSD_Kses::element($listing->get_excerpt(10, false)); ?>
				</p>

				<div class="lsd-listing-bottom-bar">
					<?php echo LSD_Kses::element($listing->get_rate_stars('summary')); ?>
					
					<?php if($address = $listing->get_address(true)): ?>
					<div class="lsd-listing-address" <?php echo lsd_schema()->address(); ?>>
						<?php echo LSD_Kses::element($address); ?>
					</div>
					<?php endif; ?>
				</div>

			</div>
		</div>
    </div>
    <?php endforeach; ?>
</div>