<?php
// no direct access
defined('ABSPATH') or die();

$settings = LSD_Options::settings();
?>
<div class="lsd-settings-wrap">
    <form id="lsd_settings_form">
        <div class="lsd-accordion-title lsd-accordion-active">
            <div class="lsd-form-row">
                <div class="lsd-col-11">
                    <h3><?php esc_html_e('General', 'listdom'); ?></h3>
                </div>
                <div class="lsd-col-1 lsd-accordion-icons">
                    <i class="lsd-icon fa fa-plus"></i>
                    <i class="lsd-icon fa fa-minus"></i>
                </div>
            </div>
        </div>
        <div class="lsd-settings-form-group lsd-accordion-panel lsd-accordion-open">
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Date Picker Format', 'listdom'),
                    'for' => 'lsd_settings_datepicker_format',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::select(array(
                        'id' => 'lsd_settings_datepicker_format',
                        'name' => 'lsd[datepicker_format]',
                        'options' => array(
                            'yyyy-mm-dd' => esc_html(current_time('Y-m-d').' '.esc_html__('(Y-m-d)', 'mec')),
                            'dd-mm-yyyy' => esc_html(current_time('d-m-Y').' '.esc_html__('(d-m-Y)', 'mec')),
                            'yyyy/mm/dd' => esc_html(current_time('Y/m/d').' '.esc_html__('(Y/m/d)', 'mec')),
                            'dd/mm/yyyy' => esc_html(current_time('d/m/Y').' '.esc_html__('(d/m/Y)', 'mec')),
                            'yyyy.mm.dd' => esc_html(current_time('Y.m.d').' '.esc_html__('(Y.m.d)', 'mec')),
                            'dd.mm.yyyy' => esc_html(current_time('d.m.Y').' '.esc_html__('(d.m.Y)', 'mec')),
                        ),
                        'value' => (isset($settings['datepicker_format']) ? $settings['datepicker_format'] : 'yyyy-mm-dd')
                    )); ?>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Time Picker Format', 'listdom'),
                    'for' => 'lsd_settings_timepicker_format',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::select(array(
                        'id' => 'lsd_settings_timepicker_format',
                        'name' => 'lsd[timepicker_format]',
                        'options' => array(
                            24 => esc_html__('24 Hours', 'listdom'),
                            12 => esc_html__('12 Hours (AM / PM)', 'listdom'),
                        ),
                        'value' => (isset($settings['timepicker_format']) ? $settings['timepicker_format'] : 24)
                    )); ?>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Listing Link Status', 'listdom'),
                    'for' => 'lsd_settings_listing_link_status',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::switcher(array(
                        'id' => 'lsd_settings_listing_link_status',
                        'name' => 'lsd[listing_link_status]',
                        'value' => (isset($settings['listing_link_status']) ? $settings['listing_link_status'] : '1'),
                    )); ?>
                    <p class="description"><?php esc_html_e("You can disable listing link if needed.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Currency Position', 'listdom'),
                    'for' => 'lsd_settings_currency_position',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::select(array(
                        'id' => 'lsd_settings_currency_position',
                        'name' => 'lsd[currency_position]',
                        'value' => (isset($settings['currency_position']) ? $settings['currency_position'] : 'before'),
                        'options' => array(
                            'before' => esc_html__('Before ($100)', 'listdom'),
                            'before_ws' => esc_html__('Before With Space ($ 100)', 'listdom'),
                            'after' => esc_html__('After (100$)', 'listdom'),
                            'after_ws' => esc_html__('After With Space (100 $)', 'listdom'),
                        ),
                    )); ?>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Default Currency', 'listdom'),
                    'for' => 'lsd_settings_default_currency',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::currency(array(
                        'id' => 'lsd_settings_default_currency',
                        'name' => 'lsd[default_currency]',
                        'value' => (isset($settings['default_currency']) ? $settings['default_currency'] : '')
                    )); ?>
                    <p class="description"><?php esc_html_e("It sets as currency while adding a new listing!", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('No Listing Message', 'listdom'),
                    'for' => 'lsd_settings_no_listings_message',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::textarea(array(
                        'id' => 'lsd_settings_no_listings_message',
                        'name' => 'lsd[no_listings_message]',
                        'rows' => 7,
                        'placeholder' => esc_html__('No Listing Found!', 'listdom'),
                        'value' => (isset($settings['no_listings_message']) ? $settings['no_listings_message'] : '')
                    )); ?>
                    <p class="description"><?php esc_html_e("It will show instead of search results when nothing found. You can insert HTML and shortcodes.", 'listdom'); ?></p>
                </div>
            </div>
        </div>

        <div class="lsd-accordion-title">
            <div class="lsd-form-row">
                <div class="lsd-col-11">
                    <h3><?php esc_html_e('Map Module', 'listdom'); ?></h3>
                </div>
                <div class="lsd-col-1 lsd-accordion-icons">
                    <i class="lsd-icon fa fa-plus"></i>
                    <i class="lsd-icon fa fa-minus"></i>
                </div>
            </div>
        </div>
        <div class="lsd-settings-form-group lsd-accordion-panel">
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Default Map Provider', 'listdom'),
                    'for' => 'lsd_settings_map_provider',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::providers(array(
                        'id' => 'lsd_settings_map_provider',
                        'name' => 'lsd[map_provider]',
                        'value' => (isset($settings['map_provider']) ? $settings['map_provider'] : 'leaflet')
                    )); ?>
                    <p class="description"><?php esc_html_e("You can change the map provider in certain shortcodes!", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
                <div class="lsd-form-row">
                    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                        'title' => esc_html__('Google Maps API key', 'listdom'),
                        'for' => 'lsd_settings_googlemaps_api_key',
                    )); ?></div>
                    <div class="lsd-col-10">
                        <?php echo LSD_Form::text(array(
                            'id' => 'lsd_settings_googlemaps_api_key',
                            'name' => 'lsd[googlemaps_api_key]',
                            'value' => (isset($settings['googlemaps_api_key']) ? $settings['googlemaps_api_key'] : '')
                        )); ?>
                        <p class="description"><?php esc_html_e("Google API Key is mandatory otherwise Google Maps doesn't work. You can get a free key from Google.", 'listdom'); ?></p>
                    </div>
                </div>
            </div>

            <?php if($this->isPro()): ?>
            <div class="lsd-map-provider-dependency lsd-map-provider-dependency-leaflet">
                <div class="lsd-form-row">
                    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                        'title' => esc_html__('Mapbox Access Token', 'listdom'),
                        'for' => 'lsd_settings_mapbox_access_token',
                    )); ?></div>
                    <div class="lsd-col-10">
                        <?php echo LSD_Form::text(array(
                            'id' => 'lsd_settings_mapbox_access_token',
                            'name' => 'lsd[mapbox_access_token]',
                            'value' => (isset($settings['mapbox_access_token']) ? $settings['mapbox_access_token'] : '')
                        )); ?>
                        <p class="description"><?php echo sprintf(esc_html__("If you want to use mapbox tiles you can get one from %s website otherwise OSM tiles will load in the maps.", 'listdom'), '<a href="https://mapbox.com" target="_blank">mapbox.com</a>'); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Default Zoom Level', 'listdom'),
                    'for' => 'lsd_settings_map_backend_zl',
                )); ?></div>
                <div class="lsd-col-1">
                    <?php echo LSD_Form::select(array(
                        'id' => 'lsd_settings_map_backend_zl',
                        'name' => 'lsd[map_backend_zl]',
                        'value' => (isset($settings['map_backend_zl']) ? $settings['map_backend_zl'] : ''),
                        'options' => array('4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10, '11' => 11, '12' => 12, '13' => 13, '14' => 14)
                    )); ?>
                </div>
                <div class="lsd-col-9">
                    <p class="description"><?php esc_html_e("It's for Google Maps in Add/Edit Map Objects menu.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('GPS Zoom Level', 'listdom'),
                    'for' => 'lsd_settings_map_gps_zl',
                )); ?></div>
                <div class="lsd-col-10 lsd-col-inline">
                    <p class="description"><?php echo sprintf(__("Change map zoom level to %s after detecting the geo point by GPS if current zoom level was less or equal to %s", 'listdom'), LSD_Form::select(array(
                        'id' => 'lsd_settings_map_gps_zl',
                        'name' => 'lsd[map_gps_zl]',
                        'class' => 'lsd-d-inline',
                        'value' => (isset($settings['map_gps_zl']) ? $settings['map_gps_zl'] : ''),
                        'options' => array('4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10, '11' => 11, '12' => 12, '13' => 13, '14' => 14)
                    )), LSD_Form::select(array(
                        'id' => 'lsd_settings_map_gps_zl_current',
                        'name' => 'lsd[map_gps_zl_current]',
                        'value' => (isset($settings['map_gps_zl_current']) ? $settings['map_gps_zl_current'] : ''),
                        'options' => array('4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10, '11' => 11, '12' => 12, '13' => 13, '14' => 14)
                    ))); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Default Latitude', 'listdom'),
                    'for' => 'lsd_settings_map_backend_lt',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_map_backend_lt',
                        'name' => 'lsd[map_backend_lt]',
                        'value' => (isset($settings['map_backend_lt']) ? $settings['map_backend_lt'] : ''),
                        'placeholder' => esc_html__("It's for Google Maps in Add/Edit Map Objects menu.", 'listdom')
                    )); ?>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Default Longitude', 'listdom'),
                    'for' => 'lsd_settings_map_backend_ln',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_map_backend_ln',
                        'name' => 'lsd[map_backend_ln]',
                        'value' => (isset($settings['map_backend_ln']) ? $settings['map_backend_ln'] : ''),
                        'placeholder' => esc_html__("It's for Google Maps in Add/Edit Listing menu.", 'listdom')
                    )); ?>
                </div>
            </div>
            <div class="lsd-form-row lsd-form-row-shape-display-options lsd-mt-5">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Shape Display Options', 'listdom'),
                    'for' => 'lsd_settings_map_shape_options_fill_color',
                )); ?></div>
                <div class="lsd-col-10">
                    <div class="lsd-form-row">
                        <div class="lsd-col-3">
                            <?php echo LSD_Form::label(array(
                                'title' => esc_html__('Fill Color', 'listdom'),
                                'for' => 'lsd_settings_map_shape_options_fill_color',
                            )); ?>
                            <?php echo LSD_Form::colorpicker(array(
                                'id' => 'lsd_settings_map_shape_options_fill_color',
                                'name' => 'lsd[map_shape_fill_color]',
                                'default' => '#1e90ff',
                                'value' => (isset($settings['map_shape_fill_color']) ? $settings['map_shape_fill_color'] : '#1e90ff'),
                            )); ?>
                        </div>
                        <div class="lsd-col-2">
                            <?php echo LSD_Form::label(array(
                                'title' => esc_html__('Fill Opacity', 'listdom'),
                                'for' => 'lsd_settings_map_shape_options_fill_opacity',
                            )); ?>
                            <?php echo LSD_Form::select(array(
                                'id' => 'lsd_settings_map_shape_options_fill_opacity',
                                'name' => 'lsd[map_shape_fill_opacity]',
                                'options' => array('0.3'=>'0.3', '0.4'=>'0.4', '0.5'=>'0.5', '0.6'=>'0.6', '0.7'=>'0.7', '0.8'=>'0.8'),
                                'value' => (isset($settings['map_shape_fill_opacity']) ? $settings['map_shape_fill_opacity'] : '0.3'),
                            )); ?>
                        </div>
                        <div class="lsd-col-3 lsd-pl-3">
                            <?php echo LSD_Form::label(array(
                                'title' => esc_html__('Border Color', 'listdom'),
                                'for' => 'lsd_settings_map_shape_options_stroke_color',
                            )); ?>
                            <?php echo LSD_Form::colorpicker(array(
                                'id' => 'lsd_settings_map_shape_options_stroke_color',
                                'name' => 'lsd[map_shape_stroke_color]',
                                'default' => '#1e74c7',
                                'value' => (isset($settings['map_shape_stroke_color']) ? $settings['map_shape_stroke_color'] : '#1e74c7'),
                            )); ?>
                        </div>
                        <div class="lsd-col-2">
                            <?php echo LSD_Form::label(array(
                                'title' => esc_html__('Border Opacity', 'listdom'),
                                'for' => 'lsd_settings_map_shape_options_stroke_opacity',
                            )); ?>
                            <?php echo LSD_Form::select(array(
                                'id' => 'lsd_settings_map_shape_options_stroke_opacity',
                                'name' => 'lsd[map_shape_stroke_opacity]',
                                'options' => array('0.3'=>'0.3', '0.4'=>'0.4', '0.5'=>'0.5', '0.6'=>'0.6', '0.7'=>'0.7', '0.8'=>'0.8'),
                                'value' => (isset($settings['map_shape_stroke_opacity']) ? $settings['map_shape_stroke_opacity'] : '0.8'),
                            )); ?>
                        </div>
                        <div class="lsd-col-2 lsd-pl-3">
                            <?php echo LSD_Form::label(array(
                                'title' => esc_html__('Border Weight', 'listdom'),
                                'for' => 'lsd_settings_map_shape_options_stroke_weight',
                            )); ?>
                            <?php echo LSD_Form::select(array(
                                'id' => 'lsd_settings_map_shape_options_stroke_weight',
                                'name' => 'lsd[map_shape_stroke_weight]',
                                'options' => array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'),
                                'value' => (isset($settings['map_shape_stroke_weight']) ? $settings['map_shape_stroke_weight'] : '2'),
                            )); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lsd-accordion-title">
            <div class="lsd-form-row">
                <div class="lsd-col-11">
                    <h3><?php esc_html_e('Frontend Submission', 'listdom'); ?></h3>
                </div>
                <div class="lsd-col-1 lsd-accordion-icons">
                    <i class="lsd-icon fa fa-plus"></i>
                    <i class="lsd-icon fa fa-minus"></i>
                </div>
            </div>
        </div>
        <div class="lsd-settings-form-group lsd-accordion-panel">
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Page', 'listdom'),
                    'for' => 'lsd_settings_submission_page',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::pages(array(
                        'id' => 'lsd_settings_submission_page',
                        'value' => (isset($settings['submission_page']) ? $settings['submission_page'] : NULL),
                        'name' => 'lsd[submission_page]',
                        'show_empty' => true,
                    )); ?>
                    <p class="description"><?php echo sprintf(esc_html__("Put %s shortcode into the page.", 'listdom'), '<code>[listdom-dashboard]</code>'); ?></p>
                </div>
            </div>

            <?php foreach(array(
                LSD_Base::TAX_LOCATION => esc_html__('Locations'),
                LSD_Base::TAX_FEATURE => esc_html__('Features'),
            ) as $tax => $label): ?>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__($label, 'listdom'),
                    'for' => 'lsd_settings_tax_'.$tax.'_method',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::select(array(
                        'id' => 'lsd_settings_tax_'.$tax.'_method',
                        'options' => array(
                            'checkboxes' => esc_html__('Checkboxes', 'listdom'),
                            'dropdown' => esc_html__('Dropdown', 'listdom'),
                        ),
                        'value' => (isset($settings['submission_tax_'.$tax.'_method']) ? $settings['submission_tax_'.$tax.'_method'] : 'checkboxes'),
                        'name' => 'lsd[submission_tax_'.$tax.'_method]',
                    )); ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Gallery Method', 'listdom'),
                    'for' => 'lsd_settings_submission_gallery_method',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::select(array(
                        'id' => 'lsd_settings_submission_gallery_method',
                        'options' => array(
                            'wp' => esc_html__('WordPress Media', 'listdom'),
                            'uploader' => esc_html__('Simple Uploader', 'listdom'),
                        ),
                        'value' => (isset($settings['submission_gallery_method']) ? $settings['submission_gallery_method'] : 'wp'),
                        'name' => 'lsd[submission_gallery_method]',
                    )); ?>
                </div>
            </div>

            <div class="lsd-form-row lsd-mt-4">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Guest', 'listdom'),
                    'for' => 'lsd_settings_submission_guest',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::switcher(array(
                        'id' => 'lsd_settings_submission_guest',
                        'value' => (isset($settings['submission_guest']) ? $settings['submission_guest'] : 0),
                        'name' => 'lsd[submission_guest]',
                    )); ?>
                    <p class="description"><?php esc_html_e("Enable listing submission for guest users!", 'listdom'); ?></p>
                </div>
            </div>

            <?php $dashboard = new LSD_Dashboard(); foreach($dashboard->modules() as $module): ?>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => $module['label'],
                )); ?></div>
                <div class="lsd-col-4">
                    <div class="lsd-radio-toggle">
                        <input type="radio" name="lsd[submission_module][<?php echo esc_attr($module['key']); ?>]" value="1" id="lsd_settings_submission_module_<?php echo esc_attr($module['key']); ?>_1" <?php echo ((isset($settings['submission_module']) and isset($settings['submission_module'][$module['key']]) and $settings['submission_module'][$module['key']] == 1) ? 'checked="checked"' : ''); ?>>
                        <label for="lsd_settings_submission_module_<?php echo esc_attr($module['key']); ?>_1"><?php esc_html_e('Enabled', 'listdom'); ?></label>
                        <input type="radio" name="lsd[submission_module][<?php echo esc_attr($module['key']); ?>]" value="2" id="lsd_settings_submission_module_<?php echo esc_attr($module['key']); ?>_2" <?php echo ((isset($settings['submission_module']) and isset($settings['submission_module'][$module['key']]) and $settings['submission_module'][$module['key']] == 2) ? 'checked="checked"' : ''); ?>>
                        <label for="lsd_settings_submission_module_<?php echo esc_attr($module['key']); ?>_2"><?php esc_html_e('Editor + Admin', 'listdom'); ?></label>
                        <input type="radio" name="lsd[submission_module][<?php echo esc_attr($module['key']); ?>]" value="0" id="lsd_settings_submission_module_<?php echo esc_attr($module['key']); ?>_0" <?php echo ((isset($settings['submission_module']) and isset($settings['submission_module'][$module['key']]) and !$settings['submission_module'][$module['key']]) ? 'checked="checked"' : ''); ?>>
                        <label for="lsd_settings_submission_module_<?php echo esc_attr($module['key']); ?>_0"><?php esc_html_e('Disabled', 'listdom'); ?></label>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="lsd-accordion-title">
            <div class="lsd-form-row">
                <div class="lsd-col-11">
                    <h3><?php esc_html_e('Display Options', 'listdom'); ?></h3>
                </div>
                <div class="lsd-col-1 lsd-accordion-icons">
                    <i class="lsd-icon fa fa-plus"></i>
                    <i class="lsd-icon fa fa-minus"></i>
                </div>
            </div>
        </div>
        <div class="lsd-settings-form-group lsd-accordion-panel">
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Main Color', 'listdom'),
                    'for' => 'lsd_settings_display_options_main_color',
                )); ?></div>
                <div class="lsd-col-10">
                    <div class="lsd-color-palette">
                        <?php echo LSD_Form::colorpalette(array(
                            'for' => '#lsd_settings_display_options_main_color',
                            'value' => (isset($settings['dply_main_color']) ? $settings['dply_main_color'] : '#2b93ff')
                        )); ?>
                        <p class="description"><?php esc_html_e("Choose from one of predefined colors in order to match it with your theme and acheive your desired look.", 'listdom'); ?></p>
                    </div>
                    <h3><?php esc_html_e('Or', 'listdom'); ?></h3>
                    <div>
                        <p class="description lsd-mb-2"><?php esc_html_e("Manually select your desired color.", 'listdom'); ?></p>
                        <?php echo LSD_Form::colorpicker(array(
                            'id' => 'lsd_settings_display_options_main_color',
                            'name' => 'lsd[dply_main_color]',
                            'default' => '#2b93ff',
                            'value' => (isset($settings['dply_main_color']) ? $settings['dply_main_color'] : '#2b93ff')
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="lsd-form-row lsd-mt-4 lsd-mb-4">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Secondary Color', 'listdom'),
                    'for' => 'lsd_settings_display_options_secondary_color',
                )); ?></div>
                <div class="lsd-col-10">
                    <div class="lsd-color-palette">
                        <?php echo LSD_Form::colorpalette(array(
                            'for' => '#lsd_settings_display_options_secondary_color',
                            'value' => (isset($settings['dply_secondary_color']) ? $settings['dply_secondary_color'] : '#f43d3d')
                        )); ?>
                        <p class="description"><?php esc_html_e("Choose secondary color from one of predefined colors.", 'listdom'); ?></p>
                    </div>
                    <h3><?php esc_html_e('Or', 'listdom'); ?></h3>
                    <div>
                        <p class="description lsd-mb-2"><?php esc_html_e("Manually select your desired color.", 'listdom'); ?></p>
                        <?php echo LSD_Form::colorpicker(array(
                            'id' => 'lsd_settings_display_options_secondary_color',
                            'name' => 'lsd[dply_secondary_color]',
                            'default' => '#f43d3d',
                            'value' => (isset($settings['dply_secondary_color']) ? $settings['dply_secondary_color'] : '#f43d3d')
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Main Font', 'listdom'),
                    'for' => 'lsd_settings_display_options_main_font',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::fontpicker(array(
                        'id' => 'lsd_settings_display_options_main_font',
                        'name' => 'lsd[dply_main_font]',
                        'value' => (isset($settings['dply_main_font']) ? $settings['dply_main_font'] : 'lato')
                    )); ?>
                    <p class="description"><?php esc_html_e("Use your theme / desired font if you don't like the default font.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Font Awesome Status', 'listdom'),
                    'for' => 'lsd_settings_fontawesome_status',
                )); ?></div>
                <div class="lsd-col-10">
                    <?php echo LSD_Form::switcher(array(
                        'id' => 'lsd_settings_fontawesome_status',
                        'value' => (isset($settings['fontawesome_status']) ? $settings['fontawesome_status'] : 1),
                        'name' => 'lsd[fontawesome_status]',
                    )); ?>
                    <p class="description"><?php esc_html_e("Disable Listdom icon file (Font-awesome) if it's loaded by another third party!", 'listdom'); ?></p>
                </div>
            </div>
        </div>

        <div class="lsd-accordion-title">
            <div class="lsd-form-row">
                <div class="lsd-col-11">
                    <h3><?php esc_html_e('Google recaptcha', 'listdom'); ?></h3>
                </div>
                <div class="lsd-col-1 lsd-accordion-icons">
                    <i class="lsd-icon fa fa-plus"></i>
                    <i class="lsd-icon fa fa-minus"></i>
                </div>
            </div>
        </div>
        <div class="lsd-settings-form-group lsd-accordion-panel">
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                        'title' => esc_html__('Status', 'listdom'),
                        'for' => 'lsd_settings_grecaptcha_status',
                    )); ?></div>
                <div class="lsd-col-10">
                    <?php echo LSD_Form::switcher(array(
                        'id' => 'lsd_settings_grecaptcha_status',
                        'value' => (isset($settings['grecaptcha_status']) ? $settings['grecaptcha_status'] : false),
                        'name' => 'lsd[grecaptcha_status]',
                        'toggle' => '#lsd_settings_grecaptcha_options'
                    )); ?>
                    <p class="description"><?php esc_html_e("Protect Listdom forms against spammer robots using Google recaptcha feature.", 'listdom'); ?></p>
                </div>
            </div>
            <div id="lsd_settings_grecaptcha_options" <?php echo ((isset($settings['grecaptcha_status']) and $settings['grecaptcha_status']) ? '' : 'style="display: none;"'); ?>>
                <div class="lsd-form-row">
                    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                        'title' => esc_html__('Site Key', 'listdom'),
                        'for' => 'lsd_settings_grecaptcha_sitekey',
                    )); ?></div>
                    <div class="lsd-col-4">
                        <?php echo LSD_Form::text(array(
                            'id' => 'lsd_settings_grecaptcha_sitekey',
                            'value' => (isset($settings['grecaptcha_sitekey']) ? $settings['grecaptcha_sitekey'] : ''),
                            'name' => 'lsd[grecaptcha_sitekey]'
                        )); ?>
                    </div>
                </div>
                <div class="lsd-form-row">
                    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                        'title' => esc_html__('Secret Key', 'listdom'),
                        'for' => 'lsd_settings_grecaptcha_secretkey',
                    )); ?></div>
                    <div class="lsd-col-4">
                        <?php echo LSD_Form::text(array(
                            'id' => 'lsd_settings_grecaptcha_secretkey',
                            'value' => (isset($settings['grecaptcha_secretkey']) ? $settings['grecaptcha_secretkey'] : ''),
                            'name' => 'lsd[grecaptcha_secretkey]'
                        )); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="lsd-accordion-title">
            <div class="lsd-form-row">
                <div class="lsd-col-11">
                    <h3><?php esc_html_e('Integrations', 'listdom'); ?></h3>
                </div>
                <div class="lsd-col-1 lsd-accordion-icons">
                    <i class="lsd-icon fa fa-plus"></i>
                    <i class="lsd-icon fa fa-minus"></i>
                </div>
            </div>
        </div>
        <div class="lsd-settings-form-group lsd-accordion-panel">
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Block Editor', 'listdom'),
                    'for' => 'lsd_settings_blockeditor_status',
                )); ?></div>
                <div class="lsd-col-10">
                    <?php echo LSD_Form::switcher(array(
                        'id' => 'lsd_settings_blockeditor_status',
                        'value' => (isset($settings['blockeditor_status']) ? $settings['blockeditor_status'] : false),
                        'name' => 'lsd[blockeditor_status]',
                    )); ?>
                    <p class="description"><?php esc_html_e("Enable block editor for listings", 'listdom'); ?></p>
                </div>
            </div>
        </div>

        <?php
            // Third Party Options
            do_action('lsd_settings_form_general', $settings);
        ?>

        <div class="lsd-spacer-10"></div>
        <div class="lsd-form-row">
			<div class="lsd-col-12">
				<?php LSD_Form::nonce('lsd_settings_form'); ?>
				<?php echo LSD_Form::submit(array(
					'label' => esc_html__('Save', 'listdom'),
					'id' => 'lsd_settings_save_button',
                    'class' => 'button button-hero button-primary',
				)); ?>
			</div>
        </div>
    </form>
</div>
<script>
jQuery('#lsd_settings_form').on('submit', function(event)
{
    event.preventDefault();

    // Add loading Class to the button
    jQuery("#lsd_settings_save_button").addClass('loading').html('<i class="lsd-icon fa fa-spinner fa-pulse fa-fw"></i>');

    var settings = jQuery("#lsd_settings_form").serialize();
    jQuery.ajax(
    {
        type: "POST",
        url: ajaxurl,
        data: "action=lsd_save_settings&" + settings,
        success: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_settings_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        },
        error: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_settings_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        }
    });
});
</script>