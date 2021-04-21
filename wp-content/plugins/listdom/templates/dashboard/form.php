<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Shortcodes_Dashboard $this */

// Entity
$entity = new LSD_Entity_Listing($this->post->ID);

// Category
$category = $entity->get_data_category();

// Objects
$postType = new LSD_PTypes_Listing();
$attributes = new LSD_Taxonomies_Attribute();

// Add JS codes to footer
$assets = new LSD_Assets();
$assets->footer('<script>
jQuery(document).ready(function()
{
    jQuery("#lsd_dashboard").listdomDashboardForm(
    {
        ajax_url: "'.admin_url('admin-ajax.php', NULL).'",
        page: '.json_encode($this->page).',
        nonce: "'.wp_create_nonce('lsd_dashboard').'"
    });
});
</script>');
?>
<div class="lsd-dashboard lsd-dashboard-form" id="lsd_dashboard">

    <div class="lsd-row">
        <div class="lsd-col-2">
            <?php echo LSD_Kses::element($this->menus()); ?>
        </div>
        <div class="lsd-col-10">

            <div class="lsd-util-hidden" id="lsd_dashboard_form_message"></div>
            <form class="lsd-dashboard-form" id="lsd_dashboard_form" enctype="multipart/form-data">
                <div class="lsd-row">
                    <div class="lsd-col-8">
						<div class="lsd-dashboard-form-left-col-wrapper">
							<div class="lsd-dashboard-title">
								<input type="text" name="lsd[title]" required value="<?php echo isset($this->post->post_title) ? esc_attr($this->post->post_title) : ''; ?>" placeholder="<?php esc_attr_e('Title', 'listdom'); ?>">
							</div>

							<div class="lsd-dashboard-editor">
								<?php wp_editor((isset($this->post->post_content) ? $this->post->post_content : ''), 'lsd_dashboard_content', array('textarea_name'=>'lsd[content]')); ?>
							</div>

							<?php if($this->is_enabled('address')): ?>
							<div class="lsd-dashboard-right-box lsd-dashboard-address">
								<h4><?php esc_html_e('Address / Map', 'listdom'); ?></h4>
								<div>
									<?php $postType->metabox_address($this->post); ?>
								</div>
							</div>
							<?php endif; ?>

							<div class="lsd-dashboard-right-box lsd-dashboard-details">
								<h4><?php esc_html_e('Details', 'listdom'); ?></h4>
								<div>
									<?php $postType->metabox_details($this->post); ?>
								</div>
							</div>

							<?php if($this->is_enabled('attributes')): ?>
							<div class="lsd-dashboard-right-box lsd-dashboard-attributes">
								<h4><?php esc_html_e('Attributes', 'listdom'); ?></h4>
								<div>
									<?php $attributes->metabox_attributes($this->post); ?>
								</div>
							</div>
							<?php endif; ?>

							<?php do_action('lsd_dashboard_after_attributes', $this->post, $this); ?>

							<?php if(!get_current_user_id()): ?>
							<div class="lsd-dashboard-right-box lsd-dashboard-message">
								<h4><?php esc_html_e('To Reviewer', 'listdom'); ?></h4>

								<div class="lsd-dashboard-guest-email">
									<label for="lsd_guest_email"><?php esc_html_e('Email', 'listdom'); ?> <span class="lsd-required">*</span></label>
									<input type="email" id="lsd_guest_email" name="lsd[guest_email]" required value="<?php echo esc_attr(get_post_meta($this->post->ID, 'lsd_guest_email', true)); ?>" placeholder="<?php esc_attr_e('Your Email', 'listdom'); ?>">
								</div>

								<div class="lsd-dashboard-guest-message">
									<label for="lsd_guest_message"><?php esc_html_e('Message', 'listdom'); ?></label>
									<textarea id="lsd_guest_message" name="lsd[guest_message]" placeholder="<?php esc_attr_e('Message to Reviewer', 'listdom'); ?>" rows="7"><?php echo esc_textarea(stripslashes(get_post_meta($this->post->ID, 'lsd_guest_message', true))); ?></textarea>
								</div>
							</div>
							<?php endif; ?>
						</div>
                    </div>
                    <div class="lsd-col-4">

                        <div class="lsd-dashboard-submit">
                            <input type="hidden" name="id" value="<?php echo esc_attr($this->post->ID); ?>" id="lsd_dashboard_id">
                            <input type="hidden" name="action" value="lsd_dashboard_listing_save">

                            <?php LSD_Form::nonce('lsd_dashboard'); ?>
                            <?php /* Security Nonce */ LSD_Form::nonce('lsd_listing_cpt', '_lsdnonce'); ?>

                            <button type="submit" class="lsd-color-m-bg <?php echo esc_attr($this->get_text_class()); ?>">
                                <?php esc_html_e('Save', 'listdom'); ?>
                            </button>

                            <?php do_action('lsd_dashboard_after_submit_button', $this); ?>

                            <div class="lsd-dashboard-grecaptcha">
                                <?php echo LSD_Main::grecaptcha_field(); ?>
                            </div>
                        </div>

                        <div class="lsd-dashboard-box lsd-dashboard-category">
                            <h4><?php esc_html_e('Category', 'listdom'); ?></h4>
                            <div>
                                <?php
                                    echo LSD_Dashboard_Terms::category(array(
                                        'taxonomy' => LSD_Base::TAX_CATEGORY,
                                        'hide_empty' => 0,
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'selected' => ($category and isset($category->term_id)) ? $category->term_id : NULL,
                                        'hierarchical' => 0,
                                        'id' => 'lsd_listing_category',
                                        'name' => 'lsd[listing_category]'
                                    ));

                                    // Additional Categories
                                    do_action('lsd_after_primary_category', $this->post, $this);
                                ?>
                            </div>
                        </div>

                        <?php if($this->is_enabled('locations')): ?>
                        <div class="lsd-dashboard-box lsd-dashboard-locations">
                            <h4><?php esc_html_e('Locations', 'listdom'); ?></h4>
                            <?php
                            echo LSD_Dashboard_Terms::locations(array(
                                'taxonomy' => LSD_Base::TAX_LOCATION,
                                'parent' => 0,
                                'level' => 0,
                                'hide_empty' => 0,
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'post_id' => $this->post->ID,
                                'name' => 'tax_input[listdom-location]'
                            ));
                            ?>
                        </div>
                        <?php endif; ?>

                        <?php if($this->is_enabled('tags')): ?>
                        <div class="lsd-dashboard-box lsd-dashboard-tags">
                            <h4><?php esc_html_e('Tags', 'listdom'); ?></h4>
                            <?php
                            $terms = wp_get_post_terms($this->post->ID, LSD_Base::TAX_TAG);

                            $tags = '';
                            if(is_array($terms) and count($terms)) foreach($terms as $term) $tags .= $term->name.',';
                            ?>
                            <textarea name="tags" id="lsd_dashboard_tags" rows="3" placeholder="<?php esc_attr_e('Tag1,Tag2,Tag3', 'listdom'); ?>"><?php echo esc_textarea(stripslashes(trim($tags, ', '))); ?></textarea>
                        </div>
                        <?php endif; ?>

                        <?php if($this->is_enabled('features')): ?>
                        <div class="lsd-dashboard-box lsd-dashboard-features">
                            <h4><?php esc_html_e('Features', 'listdom'); ?></h4>
                            <?php
                            echo LSD_Dashboard_Terms::features(array(
                                'taxonomy' => LSD_Base::TAX_FEATURE,
                                'parent' => 0,
                                'level' => 0,
                                'hide_empty' => 0,
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'post_id' => $this->post->ID,
                                'name' => 'tax_input[listdom-feature]'
                            ));
                            ?>
                        </div>
                        <?php endif; ?>

                        <?php if($this->is_enabled('labels')): ?>
                        <div class="lsd-dashboard-box lsd-dashboard-labels" id="lsd-dashboard-labels">
                            <h4><?php esc_html_e('Labels', 'listdom'); ?></h4>
                            <?php
                            echo LSD_Dashboard_Terms::labels(array(
                                'taxonomy' => LSD_Base::TAX_LABEL,
                                'parent' => 0,
                                'level' => 0,
                                'hide_empty' => 0,
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'post_id' => $this->post->ID,
                                'name' => 'tax_input[listdom-label]'
                            ));
                            ?>
                        </div>
                        <?php endif; ?>

                        <?php if($this->is_enabled('image') and current_user_can('upload_files')): ?>
                        <div class="lsd-dashboard-box lsd-dashboard-featured-image">
                            <h4><?php esc_html_e('Featured Image', 'listdom'); ?></h4>
                            <div>
                                <?php
                                $attachment_id = get_post_thumbnail_id($this->post->ID);

                                $featured_image = wp_get_attachment_image_src($attachment_id, 'medium');
                                if(isset($featured_image[0])) $featured_image = $featured_image[0];
                                ?>
                                <span id="lsd_dashboard_featured_image_preview"><?php echo (trim($featured_image) ? '<img src="'.esc_url($featured_image).'" />' : ''); ?></span>
                                <input type="hidden" id="lsd_featured_image" name="lsd[featured_image]" value="<?php echo esc_attr($attachment_id); ?>">
                                <input type="file" id="lsd_featured_image_file">

                                <div class="lsd-dashboard-feature-image-remove-wrapper">
                                    <span id="lsd_featured_image_remove_button" class="lsd-remove-image-button lsd-color-m-bg <?php echo esc_attr($this->get_text_class()); ?> <?php echo (trim($featured_image) ? '' : 'lsd-util-hide'); ?>">
                                        <?php esc_html_e('Remove Image', 'listdom'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </form>

        </div>
    </div>

</div>