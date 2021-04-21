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
				
				<?php echo LSD_Kses::element($listing->get_rate_stars()); ?>
			</div>
			<div class="lsd-listing-body">
				<h3 class="lsd-listing-title" <?php echo lsd_schema()->name(); ?>>
                    <?php echo LSD_Kses::element($this->get_title_tag($listing)); ?>
				</h3>
				<div class="lsd-listing-address" <?php echo lsd_schema()->address(); ?>>
					<?php echo LSD_Kses::element($listing->get_address(false)); ?>
				</div>
				
				<div class="lsd-listing-contact-info">
					<?php echo LSD_Kses::element($listing->get_contact_info()); ?>
				</div>
				
				<div class="lsd-listing-bottom-bar">
					<?php if($this->display_share_buttons): ?>
						<div class="lsd-listing-share">
							<?php echo LSD_Kses::element($listing->get_share_buttons('archive')); ?>
						</div>
					<?php endif; ?>
					
					<div class="lsd-listing-price" <?php echo lsd_schema()->priceRange(); ?>>
						<?php echo LSD_Kses::element($listing->get_price()); ?>
					</div>
					
				</div>
				
			</div>
		</div>
    </div>
    <?php endforeach; ?>
</div>