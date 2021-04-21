<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins_Carousel $this */

$ids = $this->listings;
?>
<div class="lsd-skin-<?php echo esc_attr($this->id); ?>-carousel lsd-owl-carousel">
    <?php foreach($ids as $id): $listing = new LSD_Entity_Listing($id); ?>
    <div class="lsd-listing" <?php echo lsd_schema()->scope()->type(NULL, $listing->get_data_category()); ?>>
			
        <div class="lsd-listing-image <?php echo esc_attr($listing->image_class_wrapper()); ?>">
            <?php echo LSD_Kses::element($listing->get_cover_image()); ?>
			
			<div class="lsd-listing-data-wrapper">
				
				<h3 class="lsd-listing-title" <?php echo lsd_schema()->name(); ?>>
                    <?php echo LSD_Kses::element($this->get_title_tag($listing)); ?>
				</h3>
				
				<?php if($address = $listing->get_address(false)): ?>
				<div class="lsd-listing-address" <?php echo lsd_schema()->address(); ?>>
					<?php echo LSD_Kses::element($address); ?>
				</div>
				<?php endif; ?>
				
				<?php echo LSD_Kses::element($listing->get_rate_stars()); ?>
				
				<p class="lsd-listing-content" <?php echo lsd_schema()->description(); ?>>
                    <?php echo LSD_Kses::element($listing->get_excerpt(12, true)); ?>
                </p>
				
			</div>
        </div>
		
    </div>
    <?php endforeach; ?>
</div>