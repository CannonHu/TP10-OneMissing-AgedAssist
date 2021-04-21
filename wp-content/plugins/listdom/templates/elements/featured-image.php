<?php
// no direct access
defined('ABSPATH') or die();

echo LSD_Kses::element(get_the_post_thumbnail($post_id, $size, (string) lsd_schema()->prop('contentUrl')));