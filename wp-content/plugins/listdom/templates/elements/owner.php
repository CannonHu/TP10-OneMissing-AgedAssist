<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Element_Owner $this */

$owner_id = get_post_field('post_author', $post_id);
$display_tel = (!isset($this->args['display_tel']) or (isset($this->args['display_tel']) and $this->args['display_tel'])) ? true : false;
$display_email = (!isset($this->args['display_email']) or (isset($this->args['display_email']) and $this->args['display_email'])) ? true : false;
$display_mobile = (!isset($this->args['display_mobile']) or (isset($this->args['display_mobile']) and $this->args['display_mobile'])) ? true : false;
$display_website = (isset($this->args['display_website']) and $this->args['display_website']) ? true : false;
$display_fax = (!isset($this->args['display_fax']) or (isset($this->args['display_fax']) and $this->args['display_fax'])) ? true : false;
?>
<?php if($this->layout == 'image-name'): $size = isset($this->args['size']) ? $this->args['size'] : 48; ?>
<div class="lsd-owner-image-name">
    <div class="lsd-owner-image" title="<?php echo esc_attr(get_the_author_meta('display_name', $owner_id)); ?>">
        <?php echo get_avatar($owner_id, $size); ?>
    </div>
</div>
<?php elseif($this->layout == 'details'): $user = get_user_meta($owner_id); ?>
<div class="lsd-owner-details" <?php echo lsd_schema()->scope()->type('http://schema.org/Person', NULL); ?>>
	<div class="lsd-owner-details-wrapper">
		<div class="lsd-owner-information">
			<div class="lsd-owner-first-part">	
				<div class="lsd-owner-image-wrapper">
					<?php echo get_avatar($owner_id, 250); ?>
				</div>
				<div class="lsd-owner-information-part-1">
					<h4 class="lsd-owner-name" <?php echo lsd_schema()->name(); ?>><?php echo esc_html(get_the_author_meta('display_name', $owner_id)); ?></h4>

					<?php if(isset($user['lsd_job_title'][0]) and trim($user['lsd_job_title'][0])): ?>
					<div class="lsd-owner-job-title" <?php echo lsd_schema()->jobTitle(); ?> ><?php echo esc_html($user['lsd_job_title'][0]); ?></div>
					<?php endif; ?>

					<?php if(isset($user['description'][0]) and trim($user['description'][0])): ?>
					<div class="lsd-owner-biography" <?php echo lsd_schema()->description(); ?> ><?php echo esc_html($user['description'][0]); ?></div>
					<?php endif; ?>
				</div>
			
				<?php
					$SN = new LSD_Socials();
					$networks = LSD_Options::socials();

					$socials = '';
					foreach($networks as $network=>$values)
					{
						$obj = $SN->get($network, $values);

						// Social Network is not Enabled
						if(!$obj or ($obj and !$obj->option('profile'))) continue;

						$link = (isset($user['lsd_'.$obj->key()][0]) and trim($user['lsd_'.$obj->key()][0])) ? $user['lsd_'.$obj->key()][0] : '';

						// Social Network is not filled
						if(trim($link) == '') continue;

						$socials .= '<li>'.$obj->owner($link).'</li>';
					}
				?>
				<?php if(trim($socials) != ''): ?>
				<div class="lsd-owner-social-networks">
					<ul><?php echo LSD_Kses::element($socials); ?></ul>
				</div>
				<?php endif; ?>
			</div>
			
			<div class="lsd-owner-information-part-2">
				<?php if($display_tel and isset($user['lsd_phone'][0]) and trim($user['lsd_phone'][0])): ?>
				<div class="lsd-owner-phone" title="<?php esc_attr_e('Phone', 'listdom'); ?>" <?php echo lsd_schema()->telephone(); ?>>
					<i class="lsd-icon fas fa-phone-alt"></i> 
					<a href="tel:<?php echo esc_html($user['lsd_phone'][0]); ?>"><?php echo esc_html($user['lsd_phone'][0]); ?></a>
				</div>
				<?php endif; ?>

                <?php if($display_email): ?>
				<div class="lsd-owner-email" title="<?php esc_attr_e('Email', 'listdom'); ?>" <?php echo lsd_schema()->email(); ?>>
					<i class="lsd-icon fa fa-envelope"></i> 
					<a href="mailto:<?php echo esc_html(get_the_author_meta('email', $owner_id)); ?>"><?php echo esc_html(get_the_author_meta('email', $owner_id)); ?></a>
				</div>
                <?php endif; ?>

				<?php if($display_mobile and isset($user['lsd_mobile'][0]) and trim($user['lsd_mobile'][0])): ?>
				<div class="lsd-owner-mobile" title="<?php esc_attr_e('Mobile', 'listdom'); ?>" <?php echo lsd_schema()->telephone(); ?>>
					<i class="lsd-icon fa fa-mobile"></i> 
					<a href="tel:<?php echo esc_html($user['lsd_mobile'][0]); ?>"><?php echo esc_html($user['lsd_mobile'][0]); ?></a>
				</div>
				<?php endif; ?>

                <?php if($display_website and isset($user['lsd_website'][0]) and trim($user['lsd_website'][0])): ?>
                <div class="lsd-owner-website" title="<?php esc_attr_e('Website', 'listdom'); ?>">
                    <i class="lsd-icon fas fa-link"></i>
                    <a href="<?php echo esc_url($user['lsd_website'][0]); ?>"><?php echo esc_html($user['lsd_website'][0]); ?></a>
                </div>
                <?php endif; ?>

				<?php if($display_fax and isset($user['lsd_fax'][0]) and trim($user['lsd_fax'][0])): ?>
				<div class="lsd-owner-fax" title="<?php esc_attr_e('Fax', 'listdom'); ?>" <?php echo lsd_schema()->faxNumber(); ?> ><i class="lsd-icon fa fa-fax"></i> <?php echo esc_html($user['lsd_fax'][0]); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<div class="lsd-owner-contact-form-wrapper">
	<form class="lsd-owner-contact-form" id="lsd_owner_contact_form_<?php echo esc_attr($post_id); ?>" data-id="<?php echo esc_attr($post_id); ?>">
		
		<div class="lsd-owner-contact-form-name-email-phone-wrapper">
			<div class="lsd-owner-contact-form-row lsd-owner-contact-form-row-name">
				<input class="lsd-form-control-input" type="text" name="lsd_name" placeholder="<?php esc_attr_e('Your Name', 'listdom') ?>" required>
				<i class="lsd-icon fa fa-user"></i>
			</div>
			<div class="lsd-owner-contact-form-row lsd-owner-contact-form-row-email">
				<input class="lsd-form-control-input" type="email" name="lsd_email" placeholder="<?php esc_attr_e('Your Email', 'listdom') ?>" required>
				<i class="lsd-icon fa fa-envelope"></i>
			</div>
			<div class="lsd-owner-contact-form-row lsd-owner-contact-form-row-phone">
				<input class="lsd-form-control-input" type="tel" name="lsd_phone" placeholder="<?php esc_attr_e('Your Phone', 'listdom') ?>" required>
				<i class="lsd-icon fas fa-phone-alt"></i>
			</div>
		</div>
		
		<div class="lsd-owner-contact-form-row">
			<textarea class="lsd-form-control-textarea" name="lsd_message" placeholder="<?php esc_attr_e("I'm interested in ....", 'listdom') ?>" required></textarea>
		</div>
		
		<div class="lsd-owner-contact-form-row lsd-owner-contact-form-third-row">
			<?php echo LSD_Main::grecaptcha_field(); ?>
			<button class="lsd-form-submit lsd-color-m-bg <?php echo esc_attr($this->get_text_class()); ?>" type="submit"><?php esc_html_e('Send', 'listdom'); ?></button>

			<?php wp_nonce_field('lsd_contact_'.$post_id); ?>
			<input type="hidden" name="lsd_post_id" value="<?php echo esc_attr($post_id); ?>">
			<input type="hidden" name="action" value="lsd_owner_contact">
		</div>
	</form>

    <div class="lsd-owner-contact-form-alert"></div>
</div>
<?php endif;