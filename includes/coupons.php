<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Coupon storage + validation.
 *
 * Coupons live in a single option row ( stauffer_booking_coupons ) following the
 * same pattern as the plugin settings. Each coupon is an array:
 *
 *  array(
 *      'id'      => 'uniqid string',
 *      'code'    => 'WELCOME10',   uppercase, unique
 *      'type'    => 'percentage' | 'fixed',
 *      'amount'  => 10,            float
 *      'expires' => '2026-12-31',  Y-m-d, empty string = never expires
 *  )
 */

/** Read all stored coupons */
function stauffer_booking_get_coupons() {

	$coupons = get_option( 'stauffer_booking_coupons', array() );

	if ( ! is_array( $coupons ) ) {
		return array();
	}

	return $coupons;
}

/** Write the full coupon list back */
function stauffer_booking_save_coupons( $coupons ) {
	update_option( 'stauffer_booking_coupons', array_values( $coupons ) );
}

/** Find one coupon by its id */
function stauffer_booking_get_coupon_by_id( $id ) {

	foreach ( stauffer_booking_get_coupons() as $coupon ) {

		if ( $coupon['id'] === $id ) {
			return $coupon;
		}
	}

	return null;
}

/** Find one coupon by its code ( case insensitive ) */
function stauffer_booking_get_coupon_by_code( $code ) {

	$code = strtoupper( trim( $code ) );

	foreach ( stauffer_booking_get_coupons() as $coupon ) {

		if ( strtoupper( $coupon['code'] ) === $code ) {
			return $coupon;
		}
	}

	return null;
}

/**
 * Is the coupon past its expiration date?
 * Empty expiration means the coupon never expires.
 * The expiration day itself is still valid ( expires at end of that day ).
 */
function stauffer_booking_is_coupon_expired( $coupon ) {

	if ( empty( $coupon['expires'] ) ) {
		return false;
	}

	$today = current_time( 'Y-m-d' );

	return $coupon['expires'] < $today;
}

/**
 * Work out the discount for a subtotal.
 * Never returns more than the subtotal, so the total can not go negative.
 */
function stauffer_booking_calculate_discount( $coupon, $subtotal ) {

	$subtotal = (float) $subtotal;
	$amount   = (float) $coupon['amount'];

	if ( 'percentage' === $coupon['type'] ) {
		$discount = ( $subtotal * $amount ) / 100;
	} else {
		$discount = $amount;
	}

	if ( $discount > $subtotal ) {
		$discount = $subtotal;
	}

	if ( $discount < 0 ) {
		$discount = 0;
	}

	return round( $discount, 2 );
}

/**
 * AJAX: validate a coupon code typed on the frontend.
 *
 * The coupon list is never printed into the page, so codes can not be read out
 * of the page source. The browser asks the server, the server answers.
 */
add_action( 'wp_ajax_stauffer_validate_coupon', 'stauffer_validate_coupon_callback' );
add_action( 'wp_ajax_nopriv_stauffer_validate_coupon', 'stauffer_validate_coupon_callback' );

function stauffer_validate_coupon_callback() {

	check_ajax_referer( 'stauffer_booking_nonce', 'nonce' );

	$settings = stauffer_booking_get_settings();

	$code     = sanitize_text_field( $_POST['coupon_code'] ?? '' );
	$subtotal = (float) ( $_POST['subtotal'] ?? 0 );

	if ( '' === trim( $code ) ) {
		wp_send_json_error( array(
			'message' => $settings['coupon_invalid_text'],
		) );
	}

	$coupon = stauffer_booking_get_coupon_by_code( $code );

	if ( ! $coupon ) {
		wp_send_json_error( array(
			'message' => $settings['coupon_invalid_text'],
		) );
	}

	if ( stauffer_booking_is_coupon_expired( $coupon ) ) {
		wp_send_json_error( array(
			'message' => $settings['coupon_expired_text'],
		) );
	}

	$discount = stauffer_booking_calculate_discount( $coupon, $subtotal );

	wp_send_json_success( array(
		'code'     => $coupon['code'],
		'type'     => $coupon['type'],
		'amount'   => (float) $coupon['amount'],
		'discount' => $discount,
		'message'  => $settings['coupon_applied_text'],
	) );
}
