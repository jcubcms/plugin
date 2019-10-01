<?php

// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

$setting_prefix = 'page_takeover_';

$settings = array(
    'background',
    'title',
    'title_font',
    'title_size',
    'title_color',
    'subtitle',
    'subtitle_font',
    'subtitle_size',
    'subtitle_color',
    'button',
    'button_url',
    'button_rel',
    'button_font',
    'button_size',
    'button_background',
    'button_color',
    'decline',
    'decline_font',
    'decline_size',
    'decline_color',
    'trigger_type',
    'trigger',
    'status',
);

foreach ( $settings as $setting ) {
    delete_option( $setting_prefix . $setting );    
}
