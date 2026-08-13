<?php
/**
 * Plugin Name: Stauffer Booking
 * Plugin URI: https://stauffer-reinigungen.ch
 * Description: A custom cleaning service booking plugin for Stauffer Reinigungen with multi-step booking, dynamic pricing, AJAX submission, and email notifications.
 * Version: 1.0.0
 * Author: Ashikur Rahman
 * Author URI: https://ashikurrahman.pro
 * Text Domain: stauffer-booking
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STAUFFER_BOOKING_VERSION', '1.0.0' );
define( 'STAUFFER_BOOKING_PATH', plugin_dir_path( __FILE__ ) );
define( 'STAUFFER_BOOKING_URL', plugin_dir_url( __FILE__ ) );

$assets_file = STAUFFER_BOOKING_PATH . 'includes/assets.php';
$shortcode_file = STAUFFER_BOOKING_PATH . 'includes/shortcode.php';
$admin_menu_file = STAUFFER_BOOKING_PATH . 'includes/admin-menu.php';
$coupons_file = STAUFFER_BOOKING_PATH . 'includes/coupons.php';
$coupons_admin_file = STAUFFER_BOOKING_PATH . 'includes/coupons-admin.php';

if( file_exists( $assets_file ) ){
    require_once STAUFFER_BOOKING_PATH . 'includes/assets.php';
}

if( file_exists( $shortcode_file ) ){
    require_once STAUFFER_BOOKING_PATH . 'includes/shortcode.php';
}

if( file_exists( $admin_menu_file ) ){
    require_once STAUFFER_BOOKING_PATH . 'includes/admin-menu.php';
}

if( file_exists( $coupons_file ) ){
    require_once STAUFFER_BOOKING_PATH . 'includes/coupons.php';
}

if( file_exists( $coupons_admin_file ) ){
    require_once STAUFFER_BOOKING_PATH . 'includes/coupons-admin.php';
}



