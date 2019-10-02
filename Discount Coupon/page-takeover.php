<?php 
/**
 * Plugin Name:       Discount Coupon 
 * Plugin URI:        http://localhost/wordpress/wp-admin/admin.php?page=page-takeover
 * Description:       Create a full-screen pop up, in which a new user discount code is available.
 * Version:           1.1.0
 * Author:            Deepika Bansal
 */

// called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

// constants
define( 'PAGE_TAKEOVER_VERSION', '1.1.0' );
define( 'PAGE_TAKEOVER_URL', plugin_dir_url( __FILE__ ) );
define( 'PAGE_TAKEOVER_BASENAME', plugin_basename( __FILE__ ) );
define( 'PAGE_TAKEOVER_DIR_NAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'PAGE_TAKEOVER_ABS', dirname(__FILE__) );
define( 'PAGE_TAKOVER_OPTION_PREFIX', 'page_takeover_' );

// includes
include PAGE_TAKEOVER_ABS . '/includes/general.php';
include PAGE_TAKEOVER_ABS . '/includes/admin.php';
