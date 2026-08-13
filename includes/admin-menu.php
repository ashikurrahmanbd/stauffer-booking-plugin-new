<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stauffer_booking_admin_menu() {

	add_menu_page(
		'Stauffer Booking',
		'Stauffer Booking',
		'manage_options',
		'stauffer-booking',
		'stauffer_booking_form_customization_page',
		'dashicons-calendar-alt',
		26
	);

	add_submenu_page(
		'stauffer-booking',
		'Booking Form Customization',
		'Booking Form',
		'manage_options',
		'stauffer-booking',
		'stauffer_booking_form_customization_page'
	);

	add_submenu_page(
		'stauffer-booking',
		'Settings',
		'Settings',
		'manage_options',
		'stauffer-booking-settings',
		'stauffer_booking_settings_page'
	);
}
add_action( 'admin_menu', 'stauffer_booking_admin_menu' );


// Default settings values
function stauffer_booking_get_default_settings() {
	return array(

		// General
		'recipient_email' => get_option( 'admin_email' ),

		// Top step navigation
		'step_1_title'       => 'Cleaning Information',
		'step_2_title'       => 'Person Information',
		'step_3_title'       => 'Confirmed Booking',
		'button_step_1_text' => 'Fill Personal Information',
		'button_step_2_text' => 'Confirm Booking',

		// Cleaning frequency section
		'section_frequency_title'          => 'How often should we clean?',
		'once_fee'                         => 50,
		'every_week_fee'                   => 0,
		'every_two_weeks_fee'              => 0,
		'every_four_weeks_fee'             => 0,

		'once_text'                        => 'Once',
		'every_week_text'                  => 'Every Week',
		'every_week_label_text'            => 'Recommended',
		'every_week_description_text'      => 'You will receive the same cleaning service.',
		'every_two_weeks_text'             => 'Every 2 Weeks',
		'every_two_weeks_description_text' => 'You will receive the same cleaning service.',
		'every_four_weeks_text'            => 'Every 4 Weeks',
		'more_often_text'                  => 'More Often',

		// Cleaning hours section
		'section_hours_title'      => 'How much time would you need?',
		'hourly_rate'              => 50,
		'currency'                 => 'CHF',
		'section_hours_hours_text' => 'Hours',

		// Extra service section
		'section_extras_title'              => 'What extras would you be interested in?',
		'extra_service_rate'                => 50,
		'window_text_label'                 => 'Window',
		'iron_text_label'                   => 'Iron',
		'wash_dry_text_label'               => 'Wash & Dry',
		'oven_inside_text_label'            => 'Oven Inside',
		'cabinets_inside_text_label'        => 'Cabinets Inside',
		'refrigerator_inside_text_label'    => 'Refrigerator Inside',

		// Booking date
		'section_date_title' => 'Choose a date for booking your service',
		'date_label'         => 'Select Date',

		// Personal information
		'personal_info_title' => 'Your Personal Information',

		// Booking summary
		'summary_title'   => 'Booking Summery',
		'success_message' => 'Thank you, your booking has been confirmed',

		// Coupon section
		'coupon_placeholder_text' => 'Coupon Code',
		'coupon_button_text'      => 'Apply Coupon',
		'coupon_invalid_text'     => 'Coupon code is not correct.',
		'coupon_expired_text'     => 'Coupon code has been expired.',
		'coupon_applied_text'     => 'Coupon applied successfully.',
		'coupon_remove_text'      => 'Remove',
		'coupon_subtotal_text'    => 'Subtotal',
		'coupon_discount_text'    => 'Discount',
		'coupon_email_label_text' => 'Applied Coupon',
	);
}

//stauffer initialize settings
function stauffer_booking_get_settings() {
	return wp_parse_args(
		get_option( 'stauffer_booking_settings', array() ),
		stauffer_booking_get_default_settings()
	);
}


/**
 * Stauffer Booking form customization callback page 
*/
function stauffer_booking_form_customization_page() {

	if ( isset( $_POST['stauffer_booking_save_settings'] ) ) {

		check_admin_referer( 'stauffer_booking_save_settings_action' );

		$settings = array(

			'recipient_email'           => sanitize_email( $_POST['recipient_email'] ?? '' ),
			
			
		
			//top step navigation
			'step_1_title'            => sanitize_text_field( $_POST['step_1_title'] ?? 'Cleaning Information' ),
			'step_2_title'            => sanitize_text_field( $_POST['step_2_title'] ?? 'Person Information' ),
			'step_3_title'            => sanitize_text_field( $_POST['step_3_title'] ?? 'Confirmed Booking' ),

			'button_step_1_text'      => sanitize_text_field( $_POST['button_step_1_text'] ?? 'Fill Personal Information' ),
			'button_step_2_text'      => sanitize_text_field( $_POST['button_step_2_text'] ?? 'Confirm Booking' ),

			//Cleaning Frequency section default fields values

			'section_frequency_title' 		=> sanitize_text_field( $_POST['section_frequency_title'] ?? 'How often should we clean?' ),
			'once_fee'                  	=> absint( $_POST['once_fee'] ?? 50 ),
			'every_week_fee'            	=> absint( $_POST['every_week_fee'] ?? 0 ),
			'every_two_weeks_fee'        	=> absint( $_POST['every_two_weeks_fee'] ?? 0 ),
			'every_four_weeks_fee'        	=> absint( $_POST['every_four_weeks_fee'] ?? 0 ),

			'once_text'								=> sanitize_text_field( $_POST['once_text'] ?? 'Once' ),
			'every_week_text'						=> sanitize_text_field( $_POST['every_week_text'] ?? 'Every Week' ),
			'every_week_label_text'					=> sanitize_text_field( $_POST['every_week_label_text'] ?? 'Recommended' ),
			'every_week_description_text'			=> sanitize_text_field( $_POST['every_week_description_text'] ?? 'You will receive the same cleaning service.' ),
			'every_two_weeks_text'					=> sanitize_text_field( $_POST['every_two_weeks_text'] ?? 'Every 2 Weeks' ),
			'every_two_weeks_description_text'		=> sanitize_text_field( $_POST['every_two_weeks_description_text'] ?? 'You will receive the same cleaning service.' ),
			'every_four_weeks_text'					=> sanitize_text_field( $_POST['every_four_weeks_text'] ?? 'Every 4 Weeks' ),
			'more_often_text'						=> sanitize_text_field( $_POST['more_often_text'] ?? 'More Often' ),

			// end of cleaning tab fields

			//start hours section fields sanitize
			
			'section_hours_title'     	=> sanitize_text_field( $_POST['section_hours_title'] ?? 'How much time would you need?' ),

			'hourly_rate'               => absint( $_POST['hourly_rate'] ?? 50 ),
			
			'currency'                  => sanitize_text_field( $_POST['currency'] ?? 'CHF' ),

			'section_hours_hours_text'  => sanitize_text_field( $_POST['section_hours_hours_text'] ?? 'Hours' ),

		
			//end hours section default field values

			//Extra service section fields sanitize
			'section_extras_title'  => sanitize_text_field( $_POST['section_extras_title'] ?? 'What extras would you be interested in?' ),
			'extra_service_rate'   	=> absint( $_POST['extra_service_rate'] ?? 50 ),
			'window_text_label'		=> sanitize_text_field( $_POST['window_text_label'] ?? 'Window' ),
			'iron_text_label'		=> sanitize_text_field( $_POST['iron_text_label'] ?? 'Iron' ),
			'wash_dry_text_label'	=> sanitize_text_field( $_POST['wash_dry_text_label'] ?? 'Wash & Dry' ),
			'oven_inside_text_label'		=> sanitize_text_field( $_POST['oven_inside_text_label'] ?? 'Oven Inside' ),
			'cabinets_inside_text_label'		=> sanitize_text_field( $_POST['cabinets_inside_text_label'] ?? 'Cabinets Inside' ),
			'refrigerator_inside_text_label'		=> sanitize_text_field( $_POST['refrigerator_inside_text_label'] ?? 'Refrigerator Inside' ),
			
			//Booking Date
			'section_date_title'      => sanitize_text_field( $_POST['section_date_title'] ?? 'Choose a date for booking your service' ),
			'date_label'              => sanitize_text_field( $_POST['date_label'] ?? 'Select Date' ),

			//Personal Information
			'personal_info_title'     => sanitize_text_field( $_POST['personal_info_title'] ?? 'Your Personal Information' ),

			//Booking Summery
			'summary_title'           => sanitize_text_field( $_POST['summary_title'] ?? 'Booking Summery' ),

			'success_message'         => sanitize_text_field( $_POST['success_message'] ?? 'Thank you, your booking has been confirmed' ),

			//Coupon section texts
			'coupon_placeholder_text' => sanitize_text_field( $_POST['coupon_placeholder_text'] ?? 'Coupon Code' ),
			'coupon_button_text'      => sanitize_text_field( $_POST['coupon_button_text'] ?? 'Apply Coupon' ),
			'coupon_invalid_text'     => sanitize_text_field( $_POST['coupon_invalid_text'] ?? 'Coupon code is not correct.' ),
			'coupon_expired_text'     => sanitize_text_field( $_POST['coupon_expired_text'] ?? 'Coupon code has been expired.' ),
			'coupon_applied_text'     => sanitize_text_field( $_POST['coupon_applied_text'] ?? 'Coupon applied successfully.' ),
			'coupon_remove_text'      => sanitize_text_field( $_POST['coupon_remove_text'] ?? 'Remove' ),
			'coupon_subtotal_text'    => sanitize_text_field( $_POST['coupon_subtotal_text'] ?? 'Subtotal' ),
			'coupon_discount_text'    => sanitize_text_field( $_POST['coupon_discount_text'] ?? 'Discount' ),
			'coupon_email_label_text' => sanitize_text_field( $_POST['coupon_email_label_text'] ?? 'Applied Coupon' ),




		);

		update_option( 'stauffer_booking_settings', $settings );

		echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
	}

	$settings = stauffer_booking_get_settings();

	

	?>

	<div class="wrap">

		<h1>Booking Form Customization</h1>

		<h2 class="nav-tab-wrapper stauffer-booking-tabs">
			<a href="#step-navigation" class="nav-tab">Step Navigation</a>
			<a href="#cleaning-frequency" class="nav-tab">Cleaning Frequency</a>
			<a href="#cleaning-hours" class="nav-tab">Cleaning Hours</a>
			<a href="#extra-services" class="nav-tab">Extra Services</a>
			<a href="#booking-date" class="nav-tab">Booking Date</a>
			<a href="#personal-information" class="nav-tab">Personal Information</a>
			<a href="#booking-summary" class="nav-tab">Booking Summary</a>
		</h2>

		<form method="post">
			<?php wp_nonce_field( 'stauffer_booking_save_settings_action' ); ?>

			<div id="step-navigation" class="stauffer-tab-content">
				<h2>Step Navigation</h2>

				<table class="form-table">
					<tr>
						<th><label for="step_1_title">Step 1 Title</label></th>
						<td>
							<input type="text" id="step_1_title" name="step_1_title" class="regular-text"
								value="<?php echo esc_attr( $settings['step_1_title'] ?? 'Cleaning Information' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="step_2_title">Step 2 Title</label></th>
						<td>
							<input type="text" id="step_2_title" name="step_2_title" class="regular-text"
								value="<?php echo esc_attr( $settings['step_2_title'] ?? 'Person Information' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="step_3_title">Step 3 Title</label></th>
						<td>
							<input type="text" id="step_3_title" name="step_3_title" class="regular-text"
								value="<?php echo esc_attr( $settings['step_3_title'] ?? 'Confirmed Booking' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="button_step_1_text">Step 1 Button Text</label></th>
						<td>
							<input type="text" id="button_step_1_text" name="button_step_1_text" class="regular-text"
								value="<?php echo esc_attr( $settings['button_step_1_text'] ?? 'Fill Personal Information' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="button_step_2_text">Step 2 Button Text</label></th>
						<td>
							<input type="text" id="button_step_2_text" name="button_step_2_text" class="regular-text"
								value="<?php echo esc_attr( $settings['button_step_2_text'] ?? 'Confirm Booking' ); ?>">
						</td>
					</tr>

				</table>
			</div>

			<div id="cleaning-frequency" class="stauffer-tab-content">
				<h2>Cleaning Frequency</h2>

				<table class="form-table">
					<tr>
						<th><label for="section_frequency_title">Frequency Section Title</label></th>
						<td>
							<input type="text" id="section_frequency_title" name="section_frequency_title" class="regular-text"
								value="<?php echo esc_attr( $settings['section_frequency_title'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="once_fee">Once Booking Extra Fee</label></th>
						<td>
							<input type="number" id="once_fee" name="once_fee"
								value="<?php echo esc_attr( $settings['once_fee'] ?? 50 ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_week_fee">Every Week Fee</label></th>
						<td>
							<input type="number" id="every_week_fee" name="every_week_fee"
								value="<?php echo esc_attr( $settings['every_week_fee'] ?? 0 ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_two_weeks_fee">Every Two Weeks Fee</label></th>
						<td>
							<input type="number" id="every_two_weeks_fee" name="every_two_weeks_fee"
								value="<?php echo esc_attr( $settings['every_two_weeks_fee'] ?? 0 ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_four_weeks_fee">Every Four Weeks Fee</label></th>
						<td>
							<input type="number" id="every_four_weeks_fee" name="every_four_weeks_fee"
								value="<?php echo esc_attr( $settings['every_four_weeks_fee'] ?? 0 ); ?>">
						</td>
					</tr>

					<!-- Freequency name chaning fields -->

					
					<tr>
						<th><label for="once_text">Every Four Weeks Fee</label></th>
						<td>
							<input type="text" id="once_text" name="once_text"
								value="<?php echo esc_attr( $settings['once_text'] ?? 'Once' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_week_text">Every Four Weeks Fee</label></th>
						<td>
							<input type="text" id="every_week_text" name="every_week_text"
								value="<?php echo esc_attr( $settings['every_week_text'] ?? 'Every Week' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_week_label_text">Every Week Label Text</label></th>
						<td>
							<input type="text" id="every_week_label_text" name="every_week_label_text"
								value="<?php echo esc_attr( $settings['every_week_label_text'] ?? 'Recommended' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_week_description_text">Every Week Description Text</label></th>
						<td>
							<input type="text"
								id="every_week_description_text"
								name="every_week_description_text"
								class="regular-text"
								value="<?php echo esc_attr( $settings['every_week_description_text'] ?? 'You will receive the same cleaning service.' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_two_weeks_text">Every Two Weeks Text</label></th>
						<td>
							<input type="text"
								id="every_two_weeks_text"
								name="every_two_weeks_text"
								class="regular-text"
								value="<?php echo esc_attr( $settings['every_two_weeks_text'] ?? 'Every 2 Weeks' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_two_weeks_description_text">Every Two Weeks Description</label></th>
						<td>
							<input type="text"
								id="every_two_weeks_description_text"
								name="every_two_weeks_description_text"
								class="regular-text"
								value="<?php echo esc_attr( $settings['every_two_weeks_description_text'] ?? 'You will receive the same cleaning service.' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="every_four_weeks_text">Every Four Weeks Text</label></th>
						<td>
							<input type="text"
								id="every_four_weeks_text"
								name="every_four_weeks_text"
								class="regular-text"
								value="<?php echo esc_attr( $settings['every_four_weeks_text'] ?? 'Every 4 Weeks' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="more_often_text">More Often Text</label></th>
						<td>
							<input type="text"
								id="more_often_text"
								name="more_often_text"
								class="regular-text"
								value="<?php echo esc_attr( $settings['more_often_text'] ?? 'More Often' ); ?>">
						</td>
					</tr>


				</table>
			</div>

			<div id="cleaning-hours" class="stauffer-tab-content">
				<h2>Cleaning Hours</h2>

				<table class="form-table">
					<tr>
						<th><label for="section_hours_title">Hours Section Title</label></th>
						<td>
							<input type="text" id="section_hours_title" name="section_hours_title" class="regular-text"
								value="<?php echo esc_attr( $settings['section_hours_title'] ); ?>">
						</td>
					</tr>

					

					<tr>
						<th><label for="hourly_rate">Hourly Rate</label></th>
						<td>
							<input type="number" id="hourly_rate" name="hourly_rate"
								value="<?php echo esc_attr( $settings['hourly_rate'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="currency">Currency</label></th>
						<td>
							<input type="text" id="currency" name="currency"
								value="<?php echo esc_attr( $settings['currency'] ); ?>">
						</td>
					</tr>
				</table>
			</div>

			<div id="extra-services" class="stauffer-tab-content">
				<h2>Extra Services</h2>

				<table class="form-table">
					<tr>
						<th><label for="section_extras_title">Extra Services Section Title</label></th>
						<td>
							<input type="text" id="section_extras_title" name="section_extras_title" class="regular-text"
								value="<?php echo esc_attr( $settings['section_extras_title'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="extra_service_rate">Extra Service Hourly Rate</label></th>
						<td>
							<input type="number" id="extra_service_rate" name="extra_service_rate"
								value="<?php echo esc_attr( $settings['extra_service_rate'] ?? 50 ); ?>">
						</td>
					</tr>


					<tr>
						<th><label for="window_text_label">Window Label</label></th>
						<td>
							<input type="text" id="window_text_label" name="window_text_label" class="regular-text"
								value="<?php echo esc_attr( $settings['window_text_label'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="iron_text_label">Iron Label</label></th>
						<td>
							<input type="text" id="iron_text_label" name="iron_text_label" class="regular-text"
								value="<?php echo esc_attr( $settings['iron_text_label'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="wash_dry_text_label">Wash & Dry Label</label></th>
						<td>
							<input type="text" id="wash_dry_text_label" name="wash_dry_text_label" class="regular-text"
								value="<?php echo esc_attr( $settings['wash_dry_text_label'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="oven_inside_text_label">Oven Inside Label</label></th>
						<td>
							<input type="text" id="oven_inside_text_label" name="oven_inside_text_label" class="regular-text"
								value="<?php echo esc_attr( $settings['oven_inside_text_label'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="cabinets_inside_text_label">Cabinets Inside Label</label></th>
						<td>
							<input type="text" id="cabinets_inside_text_label" name="cabinets_inside_text_label" class="regular-text"
								value="<?php echo esc_attr( $settings['cabinets_inside_text_label'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="refrigerator_inside_text_label">Refrigerator Inside Label</label></th>
						<td>
							<input type="text" id="refrigerator_inside_text_label" name="refrigerator_inside_text_label" class="regular-text"
								value="<?php echo esc_attr( $settings['refrigerator_inside_text_label'] ); ?>">
						</td>
					</tr>

					
				</table>
			</div>

			<div id="booking-date" class="stauffer-tab-content">
				<h2>Booking Date</h2>

				<table class="form-table">
					<tr>
						<th><label for="section_date_title">Date Section Title</label></th>
						<td>
							<input type="text" id="section_date_title" name="section_date_title" class="regular-text"
								value="<?php echo esc_attr( $settings['section_date_title'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="date_label">Date Label</label></th>
						<td>
							<input type="text" id="date_label" name="date_label" class="regular-text"
								value="<?php echo esc_attr( $settings['date_label'] ?? 'Select Date' ); ?>">
						</td>
					</tr>
				</table>
			</div>

			<div id="personal-information" class="stauffer-tab-content">
				<h2>Personal Information</h2>

				<table class="form-table">
					<tr>
						<th><label for="personal_info_title">Personal Information Title</label></th>
						<td>
							<input type="text" id="personal_info_title" name="personal_info_title" class="regular-text"
								value="<?php echo esc_attr( $settings['personal_info_title'] ?? 'Your Personal Information' ); ?>">
						</td>
					</tr>

					
				</table>
			</div>

			<div id="booking-summary" class="stauffer-tab-content">
				<h2>Booking Summary</h2>

				<table class="form-table">
					<tr>
						<th><label for="summary_title">Summary Title</label></th>
						<td>
							<input type="text" id="summary_title" name="summary_title" class="regular-text"
								value="<?php echo esc_attr( $settings['summary_title'] ?? 'Booking Summery' ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="success_message">Thank You Message</label></th>
						<td>
							<input type="text" id="success_message" name="success_message" class="regular-text"
								value="<?php echo esc_attr( $settings['success_message'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="recipient_email">Booking Recipient Email</label></th>
						<td>
							<input type="email" id="recipient_email" name="recipient_email" class="regular-text"
								value="<?php echo esc_attr( $settings['recipient_email'] ); ?>">
						</td>
					</tr>
				</table>

				<h2>Coupon Texts</h2>

				<p class="description">
					All frontend text used by the coupon feature. Manage the coupon codes themselves
					under <a href="<?php echo esc_url( admin_url( 'admin.php?page=stauffer-booking-coupons' ) ); ?>">Stauffer Coupons</a>.
				</p>

				<table class="form-table">

					<tr>
						<th><label for="coupon_placeholder_text">Coupon Input Placeholder</label></th>
						<td>
							<input type="text" id="coupon_placeholder_text" name="coupon_placeholder_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_placeholder_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_button_text">Apply Coupon Button Text</label></th>
						<td>
							<input type="text" id="coupon_button_text" name="coupon_button_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_button_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_invalid_text">Invalid Coupon Message</label></th>
						<td>
							<input type="text" id="coupon_invalid_text" name="coupon_invalid_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_invalid_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_expired_text">Expired Coupon Message</label></th>
						<td>
							<input type="text" id="coupon_expired_text" name="coupon_expired_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_expired_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_applied_text">Coupon Applied Message</label></th>
						<td>
							<input type="text" id="coupon_applied_text" name="coupon_applied_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_applied_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_remove_text">Remove Coupon Text</label></th>
						<td>
							<input type="text" id="coupon_remove_text" name="coupon_remove_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_remove_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_subtotal_text">Subtotal Label</label></th>
						<td>
							<input type="text" id="coupon_subtotal_text" name="coupon_subtotal_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_subtotal_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_discount_text">Discount Label</label></th>
						<td>
							<input type="text" id="coupon_discount_text" name="coupon_discount_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_discount_text'] ); ?>">
						</td>
					</tr>

					<tr>
						<th><label for="coupon_email_label_text">Applied Coupon Label (Email)</label></th>
						<td>
							<input type="text" id="coupon_email_label_text" name="coupon_email_label_text" class="regular-text"
								value="<?php echo esc_attr( $settings['coupon_email_label_text'] ); ?>">
						</td>
					</tr>

				</table>
			</div>

			<p>
				<button type="submit" name="stauffer_booking_save_settings" class="button button-primary">
					Save Settings
				</button>
			</p>

		</form>
	</div>

	<?php
}

//Stauffe Booking Settings Page call back
function stauffer_booking_settings_page(){
	?>

	<div class="wrap">
		<h1>Settings</h1>
	</div>
	
	<?php
}