<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins_Grid $this */

// Featured Image Sizes
$sizes = array(
    2 => array(560, 430),
    3 => array(325, 250),
    4 => array(280, 215),
);

$ids = $this->listings;
?>
<?php $i = 1; foreach($ids as $id): $listing = new LSD_Entity_Listing($id); ?>

    <?php if($this->columns and $i == 1): ?>
        <div class="lsd-row">
    <?php endif; ?>

    <div class="lsd-col-<?php echo (12 / $this->columns); ?>">
        <div class="lsd-listing<?php if(!$this->display_image) echo " lsd-listing-no-image"; ?>" <?php echo lsd_schema()->scope()->type(NULL, $listing->get_data_category()); ?>>
							
			<?php if($this->display_image): ?>
			<div class="lsd-listing-image <?php echo esc_attr($listing->image_class_wrapper()); ?>">
				<?php echo LSD_Kses::element($listing->get_cover_image($sizes[$this->columns])); ?>
			</div>
			<?php endif; ?>
			
            <div class="lsd-listing-body">
				
				<?php if($this->display_labels): ?>
				<div class="lsd-listing-labels">
					<?php echo LSD_Kses::element($listing->get_labels()); ?>
				</div>
				<?php endif; ?>
				
				<?php echo LSD_Kses::element($listing->get_rate_stars()); ?>
				
				<?php echo LSD_Kses::element($listing->get_favorite_button()); ?>
				
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

    <?php if($this->columns and $i == $this->columns): ?>
        </div>
    <?php $i = 0; endif; ?>

    <?php $i++; endforeach; ?>
<?php /** Close the unclosed Row **/ if($this->columns and ($i - 1) > 0 and ($i - 1) != $this->columns) echo '</div>';