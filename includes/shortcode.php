<?php

if ( !defined( 'ABSPATH' ) ) exit;

/** Full Short code for the Booking Form */
add_shortcode('stauffer_booking_form', 'stauffer_booking_form_shortcode_callback');
function stauffer_booking_form_shortcode_callback($atts){

    //plugin settings
    $settings = stauffer_booking_get_settings();
    
    $thankyou_message = $settings['success_message']  ?? 'Thank you, Your booking has been Confirmed';;

	//steps titles
	$step_1_title = $settings['step_1_title'] ?? 'Cleaning Information';
	$step_2_title = $settings['step_2_title'] ?? 'Person Information';
	$step_3_title = $settings['step_3_title'] ?? 'Confirmed Booking';
	$step_1_button_title = $settings['button_step_1_text'] ?? 'Fill Personal Information';
	$step_2_button_title = $settings['button_step_2_text'] ?? 'Confirm Booking';


	//cleaning frequency type section all texts
	$frequency_section_title 			= $settings['section_frequency_title'] ?? 'How often should we clean?';
	$once_text 							= $settings['once_text'] ?? 'Once';
	$every_week_text 					= $settings['every_week_text'] ?? 'Every Week';
	$every_week_label_text 				= $settings['every_week_label_text'] ?? 'Recommended';
	$every_week_description_text 		= $settings['every_week_description_text'] ?? 'You will receive the same cleaning service.';
	$every_two_weeks_text 				= $settings['every_two_weeks_text'] ?? 'Every 2 Weeks';
	$every_two_weeks_description_text 	= $settings['every_two_weeks_description_text'] ?? 'You will receive the same cleaning service.';
	$every_four_weeks_text 				= $settings['every_four_weeks_text'] ?? 'Every 4 Weeks';
	$more_often_text 					= $settings['more_often_text'] ?? 'More Often';

	//hours section all texts from the otpions
	$hours_section_title = $settings['section_hours_title'] ?? 'How much time would you need?';
	
	
	//extra services section
    $extra_services_section_title = $settings['section_extras_title'] ?? 'What extras would you be interested in?';
	$extra_services_section_window_label = $settings['window_text_label'] ?? 'Window';
	$extra_services_section_iron_label = $settings['iron_text_label'] ?? 'Iron';
	$extra_services_section_wash_dry_label = $settings['wash_dry_text_label'] ?? 'Wash & Dry';
	$extra_services_section_oven_inside_label = $settings['oven_inside_text_label']  ?? 'Oven Inside';
	$extra_services_section_cabinets_inside_label = $settings['cabinets_inside_text_label']  ?? 'Cabinets Inside';
	$extra_services_section_refrigerator_inside_label = $settings['refrigerator_inside_text_label']  ?? 'Refrigerator Inside';


	//section section datas
	$date_selection_section_title = $settings['section_date_title']  ?? 'Choose a date for booking your service';
	$date_selection_section_select_date_label = $settings['date_label'] ?? 'Select Date';

	//section personal information
	$personal_information_section_title = $settings['personal_info_title'] ?? 'Your Personal Information';


	//booking summery
	$booking_summery_section_title = $settings['summary_title'] ?? 'Booking Summery';


	ob_start();
	?>
	
	<div class="stauffer-booking-form-wrapper">
		<form action="" class="stauffer-booking-form">

			<div class="stauffer-form-fields">

				<!-- Multistep navigation -->
				<div class="stauffer-form-steps">
					<span class="step-1 active">
						<span class="step-number">1</span>
						<span class="step-info"><?php echo esc_html( $step_1_title ); ?></span>
					</span>
					<span class="step-2">
						<span class="step-number">2</span>
						<span class="step-info"><?php echo esc_html( $step_2_title ); ?></span>
					</span>
					<span class="step-3">
						<span class="step-number">3</span>
						<span class="step-info"><?php echo esc_html( $step_3_title ); ?></span>
					</span>
				</div>
				<!-- End of multi step navigator -->

				<div id="step-1" class="cleaning-information">
					<!-- Cleaning frequency -->
					<div class="cleaning-frequency">

						<h3 class="field-title">
                            <?php echo esc_html( $frequency_section_title ); ?>
                        </h3>

						<div class="cleaning-frequency-options">

							<label class="frequency-option">
								<input type="radio" name="cleaning_frequency" value="once">
								<div class="option-content">
									<span class="option-title"><?php echo esc_html( $once_text ); ?></span>
								</div>
							</label>

							<label class="frequency-option">
								<input type="radio" name="cleaning_frequency" value="weekly" checked>
								<div class="option-content">
									<span class="recommended-badge"><?php echo esc_html( $every_week_label_text ); ?></span>

									<span class="option-title"><?php echo esc_html( $every_week_text ); ?></span>

									<span class="option-description">
										<?php echo esc_html( $every_week_description_text ); ?>
									</span>
								</div>
							</label>

							<label class="frequency-option">
								<input type="radio" name="cleaning_frequency" value="every-2-weeks">
								<div class="option-content">
									<span class="option-title"><?php echo esc_html( $every_two_weeks_text ); ?></span>

									<span class="option-description">
										<?php echo esc_html( $every_two_weeks_description_text ); ?>
									</span>
								</div>
							</label>

							<label class="frequency-option">
								<input type="radio" name="cleaning_frequency" value="every-4-weeks">
								<div class="option-content">
									<span class="option-title"><?php echo esc_html( $every_four_weeks_text ); ?></span>
								</div>
							</label>

							<label class="frequency-option">
								<input type="radio" name="cleaning_frequency" value="more-often">
								<div class="option-content">
									<span class="option-title"><?php echo esc_html( $more_often_text );  ?></span>
								</div>
							</label>

						</div>
					</div>

					<!-- cleaning hours -->
					<div class="cleaning-hours">
						<h3 class="field-title">
                            <?php echo esc_html( $hours_section_title ); ?>
                        </h3>

						<div class="time-duration-selector">

							<button type="button" class="duration-btn service-duration-minus">
								−
							</button>

							<div class="duration-display">
								<span class="service-duration-value">2</span>
								<!-- Studen means Hours -->
								<span class="duration-unit"> Stunden</span> 
							</div>

							<button type="button" class="duration-btn service-duration-plus">
								+
							</button>

							<input
								type="hidden"
								name="cleaning_hours"
								id="cleaning_hours"
								value="2"
							>

						</div>
						
						<!-- recommendation description for cleaning hours english text was  -->
						<div class="duration-recommendation">
							Empfohlen für: 1 Schlafzimmer, 1 Badezimmer
							<span class="info-icon">ⓘ</span>
						</div>

					</div>
					<!-- End of cleaning hours -->

					<!-- Newly Added: More Often weekly schedule section -->
					<div class="more-often-schedule" style="display:none;">

						<h3 class="field-title">
							<!-- plan Your Weekly Cleanings -->
							Planen Sie Ihre Reinigungen
						</h3>

						<div class="more-often-content">

							<div class="weekly-cleaning-count">
								<button type="button" class="weekly-count-minus">−</button>

								<span class="weekly-count-value">2</span>

								<button type="button" class="weekly-count-plus">+</button>

								<!-- times a week. Ex. 6 times a week -->
								<span class="weekly-count-label">Mal pro Woche</span>

								<input type="hidden" name="weekly_cleaning_count" id="weekly_cleaning_count" value="2">
							</div>

							<div class="weekly-schedule-rows">

								<!-- JS will create rows here -->

							</div>

						</div>

					</div>
					<!-- End Newly Added: More Often weekly schedule section -->


					<!-- Extra Addon Service for cleaning -->

					<div class="cleaning-extra-services">

						<h3 class="field-title">
                            <?php echo esc_html( $extra_services_section_title ); ?>
                        </h3>

						<div class="extra-services-options">

							<label class="extra-service-option">

								<input type="checkbox" name="extra_services[]" value="window" data-minutes="60">

								<div class="service-card">
									
									<span class="service-title">
										<img
											src="<?php echo esc_url( STAUFFER_BOOKING_URL . 'assets/images/window-cleaning.svg' ); ?>"
											alt=""
											class="service-icon"
										>
										<!-- window title -->
										<?php echo esc_html( $extra_services_section_window_label ); ?>
									</span>

									<div class="extra-service-duration">
										<span class="duration-badge">+1 H</span>
									</div>

								</div>
							</label>

							<label class="extra-service-option">
								<input type="checkbox" name="extra_services[]" value="iron"  data-minutes="30">

								<div class="service-card">
									<span class="service-title">
										<img
											src="<?php echo esc_url( STAUFFER_BOOKING_URL . 'assets/images/iron.svg' ); ?>"
											alt=""
											class="service-icon"
										>
										<!-- iron title -->
										<?php echo esc_html( $extra_services_section_iron_label ); ?>
									</span>

									<div class="extra-service-duration">
										<span class="duration-badge">+30 Min</span>
									</div>

								</div>
							</label>

							<label class="extra-service-option">
								<input type="checkbox" name="extra_services[]" value="wash_dry"  data-minutes="60">

								<div class="service-card">
									<span class="service-title">

										<img
											src="<?php echo esc_url( STAUFFER_BOOKING_URL . 'assets/images/wash.svg' ); ?>"
											alt=""
											class="service-icon"
										>
										<!-- wash and Dry -->
										<?php echo esc_html( $extra_services_section_wash_dry_label ); ?>
									</span>

									<div class="extra-service-duration">
										<span class="duration-badge">+1 H</span>
									</div>

								</div>
							</label>

							<label class="extra-service-option">
								<input type="checkbox" name="extra_services[]" value="oven" data-minutes="30">

								<div class="service-card">
									<span class="service-title">
										<img
											src="<?php echo esc_url( STAUFFER_BOOKING_URL . 'assets/images/oven.svg' ); ?>"
											alt=""
											class="service-icon"
										>
										<!-- oven inside -->
										<?php echo esc_html( $extra_services_section_oven_inside_label ); ?>
									</span>

									<div class="extra-service-duration">
										<span class="duration-badge">+30 Min</span>
									</div>

								</div>
							</label>

							<label class="extra-service-option">
								<input type="checkbox" name="extra_services[]" value="cabinets" data-minutes="30">

								<div class="service-card">
									<span class="service-title">
										<img
											src="<?php echo esc_url( STAUFFER_BOOKING_URL . 'assets/images/cabinets.svg' ); ?>"
											alt=""
											class="service-icon"
										>
										<!-- cabinets inside -->
										<?php echo esc_html( $extra_services_section_cabinets_inside_label ); ?>
									</span>

									<div class="extra-service-duration">
										<span class="duration-badge">+30 Min</span>
									</div>


								</div>
							</label>

							<label class="extra-service-option">
								<input type="checkbox" name="extra_services[]" value="refrigerator" data-minutes="30">

								<div class="service-card">
									<span class="service-title">
										<img
											src="<?php echo esc_url( STAUFFER_BOOKING_URL . 'assets/images/refregerator.svg' ); ?>"
											alt=""
											class="service-icon"
										>
										<!-- Refrigerator inside -->
										<?php echo esc_html( $extra_services_section_refrigerator_inside_label ); ?>
									</span>

									<div class="extra-service-duration">
										<span class="duration-badge">+30 Min</span>
									</div>


								</div>
							</label>

						</div>

					</div>

					<!-- End of extra services section -->


					<!-- Choose Date for the booking-->

					<div class="cleaning-date-time">

						<h3 class="field-title">
							<?php echo esc_html( $date_selection_section_title ); ?>
						</h3>

						<div class="booking-datetime-wrapper">

							<!-- Date Selection -->
							<div class="booking-date-section">

								<label class="booking-label">
									<!-- Select Date Label -->
									<?php echo esc_html( $date_selection_section_select_date_label ); ?>
								</label>

								<input
									type="date"
									class="booking-date-input"
									name="booking_date"
									min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>"
									required
								>

								<span class="booking-date-error"></span>

							</div>

						</div>

					</div>


				</div>
				<!-- End of Step 1 -->

				<!-- Step 2 -->
				<div id="step-2" class="step-personal-information">

					<div class="customer-details-wrapper">

						<h3 class="field-title"><?php echo esc_html( $personal_information_section_title ); ?></h3>

						<div class="stauffer-customer-details">

							<!-- First Name -->
							<div class="customer-field">
								<input
									type="text"
									name="first_name"
									placeholder="Vorname"
									required
								>
							</div>

							<!-- Last Name -->
							<div class="customer-field">
								<input
									type="text"
									name="last_name"
									placeholder="Nachname"
									required
								>
							</div>

							<!-- Phone Number -->
							<div class="customer-field customer-field-full">
								<input
									type="tel"
									name="phone_number"
									placeholder="Telefonnummer"
									required
								>
							</div>

							<!-- Email -->
							<div class="customer-field customer-field-full">
								<input
									type="email"
									name="email"
									placeholder="E-Mail"
									required
								>
							</div>

							<!-- Home Address -->
							<div class="customer-field customer-field-full">
								<input
									type="text"
									name="home_address"
									placeholder="Heimatadresse"
									autocomplete="street-address"
									required
								>
							</div>

						</div>

					</div>

				</div>
				<!-- End of Step 2 -->

				<!-- Step 3 -->
				<div id="step-3" class="step-confirm-booking">
					
				</div>
				<!-- End of Step 3 -->

				<!-- Form Processing action ( step processing ) -->
				<div class="stauffer-booking-action">
					<button class="stfr-step-btn">
						<!-- Fill personal information -->
						<?php echo esc_html($step_1_button_title); ?>
					</button>
				</div>
				<!-- End of form processing action -->

				

				
			</div>
			<!-- end of form fields -->

			<!-- Rightside Summery -->

			<div class="form-summery">
				<h3 class="field-title">
					<!-- Booking summery -->
					<?php echo esc_html( $booking_summery_section_title ); ?>
				</h3>

				<div class="form-summery-content">
					<div class="cleaning-booking-summery">

						<div class="cleaning-frequency-selection">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/stf-assets/images/repeat.svg' ); ?>" alt="">
							<!-- Every Week -->
							<span class="summary-frequency">Wöchentlich</span>
						</div>

						<div class="cleaning-extra-service-selection">
							<span>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/stf-assets/images/extra-service.svg' ); ?>" alt="">
								<!-- Extra services -->
								Zusätzliche Dienstleistungen
							</span>

							<ul class="cleaning-extra-services-list">
								<!-- No Extra service selected -->
								<li>Keine Zusatzleistungen ausgewählt</li>
							</ul>
						</div>

						<div class="cleaning-hours-selection">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/stf-assets/images/cleaning-time.svg' ); ?>" alt="">
							<span class="cleaning-hours-value">2 Stunden</span>
						</div>

						<div class="cleaning-service-booking-date-time-selection">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/stf-assets/images/date-time.svg' ); ?>" alt="">
							<span class="cleaning-service-booking-date-time">Kein Datum und keine Uhrzeit ausgewählt</span>
						</div>

					</div>

					<div class="cleanig-booking-total">
						<span class="total-title">Bruttogesamt</span>
						<span class="total-amount">0 <span class="total-amount-currenty">CHF</span></span>
					</div>
				</div>
			</div>

			<!-- End of Rightside summery -->

		</form>

		<div class="form-booking-confirmation">
			<h2><?php echo esc_html( $thankyou_message ); ?></h2>
			<a class="button" href="/">Zurück zur Startseite</a>
		</div>

	</div>

	<?php 
	return ob_get_clean();

}
/** End of the Shortcode */

/** Changing Default Wordpress Title and email after the email submission */

// Change WordPress email sender name
add_filter( 'wp_mail_from_name', 'stauffer_booking_mail_from_name' );
function stauffer_booking_mail_from_name( $name ) {
	return 'Stauffer Reinigungen';
}

// Change WordPress email sender address
add_filter( 'wp_mail_from', 'stauffer_booking_mail_from_email' );
function stauffer_booking_mail_from_email( $email ) {
	return 'noreply@staufferreinigungen.ch';
}

/** End changing */

/** Start code of ajax submission for the Booking */
add_action('wp_ajax_stauffer_submit_booking', 'stauffer_submit_booking_callback');
add_action('wp_ajax_nopriv_stauffer_submit_booking', 'stauffer_submit_booking_callback');

function stauffer_submit_booking_callback() {

    //plugin settings
    $settings = stauffer_booking_get_settings();

    check_ajax_referer('stauffer_booking_nonce', 'nonce');

    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $phone      = sanitize_text_field($_POST['phone_number'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $address    = sanitize_text_field($_POST['home_address'] ?? '');

    $frequency  = sanitize_text_field($_POST['frequency'] ?? '');
    $hours      = sanitize_text_field($_POST['hours'] ?? '');
	$weekly_schedule = sanitize_textarea_field($_POST['weekly_schedule'] ?? '');
    $date_time  = sanitize_text_field($_POST['date_time'] ?? '');
    $extras     = sanitize_textarea_field($_POST['extras'] ?? '');
    $total      = sanitize_text_field($_POST['total'] ?? '');

    if (empty($first_name) || empty($phone) || empty($email)) {
        wp_send_json_error('Please fill all required fields.');
    }

    $to = $settings['recipient_email'];
    $subject = 'Anfrage für eine neue Reinigungsbuchung';

	$message = '
		
		<body style="margin:0; padding:10px; background:#f4f6f8; font-family:Arial, sans-serif; color:#222;">

			<div style="max-width:650px; margin:30px auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden;">

				<div style="background:#0c2850; color:#ffffff; padding:10px;">
					<h2 style="margin:0; font-size:22px;">Anfrage für eine neue Reinigungsbuchung</h2>
				</div>

				<div style="padding:10px;">

					<h3 style="margin-top:0; color:#0c2850;">Kundeninformationen</h3>

					<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
						<tr>
							<td><strong>Name</strong></td>
							<td>' . esc_html($first_name . ' ' . $last_name) . '</td>
						</tr>
						<tr>
							<td><strong>Telefon</strong></td>
							<td>' . esc_html($phone) . '</td>
						</tr>
						<tr>
							<td><strong>E-mail</strong></td>
							<td>' . esc_html($email) . '</td>
						</tr>
						<tr>
							<td><strong>Adresse</strong></td>
							<td>' . esc_html($address) . '</td>
						</tr>
					</table>

					<hr style="border:none; border-top:1px solid #e5e7eb; margin:25px 0;">

					<h3 style="color:#0c2850;">Buchungsinformationen</h3>

					<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
						<tr>
							<td><strong>Buchungshäufigkeit</strong></td>
							<td>' . esc_html($frequency) . '</td>
						</tr>
						<tr>
							<td><strong>Gesamtreinigungszeit</strong></td>
							<td>' . esc_html($hours) . '</td>
						</tr>

						' . ( !empty($weekly_schedule) ? '
						<tr>
							<td><strong>Wochenplan</strong></td>
							<td>' . nl2br(esc_html($weekly_schedule)) . '</td>
						</tr>
						' : '' ) . '

						<tr>
							<td><strong>Ausgewähltes Datum</strong></td>
							<td>' . esc_html($date_time) . '</td>
						</tr>
						<tr>
							<td><strong>Zusatzleistungen</strong></td>
							<td>' . nl2br(esc_html($extras)) . '</td>
						</tr>
					</table>

					<div style="margin-top:25px; background:#0c2850; color:#ffffff; padding:15px; border-radius:6px; font-size:16px;text-align:center;">
						<strong>GESAMTSUMME:</strong> ' . esc_html($total) . '
					</div>

				</div>
			</div>

		</body>
	';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
		'From: Stauffer Reinigungen <noreply@staufferreinigungen.ch>',
        'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>',
    );

    $sent = wp_mail($to, $subject, $message, $headers);

    if ($sent) {
        wp_send_json_success('Booking request submitted successfully.');
    }

    wp_send_json_error('Email could not be sent.');
}

/** End code of ajax submission */