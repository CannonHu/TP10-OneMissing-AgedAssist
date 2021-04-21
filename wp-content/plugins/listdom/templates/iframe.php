<?php
// no direct access
defined('ABSPATH') or die();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class($class); ?>>
	<?php echo LSD_Kses::page($body); ?>
    <?php wp_footer(); ?>
</body>
</html>