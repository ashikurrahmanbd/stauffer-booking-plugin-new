<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue frontend assets
 */

add_action('wp_enqueue_scripts', 'stauffer_booking_enqueue_assets', 99);
function stauffer_booking_enqueue_assets() {

	 //plugin settings
    $settings = stauffer_booking_get_settings();

	//booking confirm text from the backend option
	$button_step_2_text = $settings['button_step_2_text'] ?? 'Confirm Boooking';

	wp_enqueue_style(
		'stauffer-booking-style',
		STAUFFER_BOOKING_URL . 'assets/css/style.css',
		array(),
		filemtime( STAUFFER_BOOKING_PATH . 'assets/css/style.css' )
	);

	wp_enqueue_script(
		'stauffer-booking-script',
		STAUFFER_BOOKING_URL . 'assets/js/script.js',
		array( 'jquery' ),
		filemtime( STAUFFER_BOOKING_PATH . 'assets/js/script.js' ),
		true
	);

	//Localize Script
	wp_localize_script(
		'stauffer-booking-script',
		'stauffer_ajax',
		array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('stauffer_booking_nonce'),
			'button_step_2_text' => $button_step_2_text,

			//currency + coupon texts used by the summary
			'currency'              => $settings['currency'],
			'coupon_button_text'    => $settings['coupon_button_text'],
			'coupon_invalid_text'   => $settings['coupon_invalid_text'],
			'coupon_expired_text'   => $settings['coupon_expired_text'],
			'coupon_applied_text'   => $settings['coupon_applied_text'],
			'coupon_subtotal_text'  => $settings['coupon_subtotal_text'],
			'coupon_discount_text'  => $settings['coupon_discount_text'],
		)
	);


}

//Admin style and script css
add_action('admin_enqueue_scripts', 'stauffer_booking_admin_enqueue_assets');
function stauffer_booking_admin_enqueue_assets() {

	wp_enqueue_style(
		'stauffer-booking-admin-style',
		STAUFFER_BOOKING_URL . 'assets/css/admin.css',
		array(),
		filemtime( STAUFFER_BOOKING_PATH . 'assets/css/admin.css' )
	);

	wp_enqueue_script(
		'stauffer-booking-admin-script',
		STAUFFER_BOOKING_URL . 'assets/js/admin.js',
		array( 'jquery' ),
		filemtime( STAUFFER_BOOKING_PATH . 'assets/js/admin.js' ),
		true
	);


}