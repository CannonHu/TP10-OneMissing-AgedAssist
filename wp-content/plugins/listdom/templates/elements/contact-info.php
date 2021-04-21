<?php
// no direct access
defined('ABSPATH') or die();

$email = get_post_meta($post_id, 'lsd_email', true);
$phone = get_post_meta($post_id, 'lsd_phone', true);
$website = get_post_meta($post_id, 'lsd_website', true);

// No data
if(!$email and !$phone and !$website) return '';
?>
<div class="lsd-contact-info">
    <ul>

        <?php if($phone): ?>
        <li>
			<strong><i class="lsd-icon fas fa-phone-alt"></i></strong>
			<span <?php echo lsd_schema()->telephone(); ?>>
				<a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
			</span>
		</li>
        <?php endif; ?>

        <?php if($email): ?>
        <li>
			<strong><i class="lsd-icon fa fa-envelope"></i></strong>
			<span <?php echo lsd_schema()->email(); ?>>
				<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
			</span>
		</li>
        <?php endif; ?>

        <?php if($website): ?>
        <li>
            <strong><i class="lsd-icon fas fa-link"></i></strong>
            <span>
                <a href="<?php echo esc_url($website); ?>"><?php echo esc_html($website); ?></a>
            </span>
        </li>
        <?php endif; ?>

    </ul>
</div>