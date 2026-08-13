<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Coupons admin screens.
 *
 * Adds a separate top level "Stauffer Coupons" menu with two submenus:
 *  - All Coupons   ( list, edit link, delete )
 *  - Add New Coupon ( also handles editing an existing coupon )
 */

function stauffer_booking_coupons_admin_menu() {

	add_menu_page(
		'Stauffer Coupons',
		'Stauffer Coupons',
		'manage_options',
		'stauffer-booking-coupons',
		'stauffer_booking_all_coupons_page',
		'dashicons-tickets-alt',
		27
	);

	add_submenu_page(
		'stauffer-booking-coupons',
		'All Coupons',
		'All Coupons',
		'manage_options',
		'stauffer-booking-coupons',
		'stauffer_booking_all_coupons_page'
	);

	add_submenu_page(
		'stauffer-booking-coupons',
		'Add New Coupon',
		'Add New Coupon',
		'manage_options',
		'stauffer-booking-add-coupon',
		'stauffer_booking_add_coupon_page'
	);
}
add_action( 'admin_menu', 'stauffer_booking_coupons_admin_menu' );


/** Shared helper: url of the All Coupons screen, with an optional notice flag */
function stauffer_booking_coupons_list_url( $notice = '' ) {

	$url = admin_url( 'admin.php?page=stauffer-booking-coupons' );

	if ( $notice ) {
		$url = add_query_arg( 'stauffer_notice', $notice, $url );
	}

	return $url;
}

/** Human readable discount value, e.g. "10%" or "20 CHF" */
function stauffer_booking_format_coupon_value( $coupon ) {

	$settings = stauffer_booking_get_settings();

	if ( 'percentage' === $coupon['type'] ) {
		return esc_html( $coupon['amount'] . '%' );
	}

	return esc_html( $coupon['amount'] . ' ' . $settings['currency'] );
}


/**
 * All Coupons page
 */
function stauffer_booking_all_coupons_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Handle delete
	if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['coupon_id'] ) ) {

		$coupon_id = sanitize_text_field( wp_unslash( $_GET['coupon_id'] ) );

		check_admin_referer( 'stauffer_delete_coupon_' . $coupon_id );

		$coupons = stauffer_booking_get_coupons();

		$coupons = array_filter( $coupons, function( $coupon ) use ( $coupon_id ) {
			return $coupon['id'] !== $coupon_id;
		} );

		stauffer_booking_save_coupons( $coupons );

		wp_safe_redirect( stauffer_booking_coupons_list_url( 'deleted' ) );
		exit;
	}

	$coupons = stauffer_booking_get_coupons();

	$notice = isset( $_GET['stauffer_notice'] ) ? sanitize_text_field( wp_unslash( $_GET['stauffer_notice'] ) ) : '';

	?>

	<div class="wrap">

		<h1 class="wp-heading-inline">All Coupons</h1>

		<a href="<?php echo esc_url( admin_url( 'admin.php?page=stauffer-booking-add-coupon' ) ); ?>" class="page-title-action">
			Add New Coupon
		</a>

		<hr class="wp-header-end">

		<?php if ( 'created' === $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p>Coupon created successfully.</p></div>
		<?php elseif ( 'updated' === $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p>Coupon updated successfully.</p></div>
		<?php elseif ( 'deleted' === $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p>Coupon deleted successfully.</p></div>
		<?php endif; ?>

		<table class="wp-list-table widefat fixed striped stauffer-coupons-table">

			<thead>
				<tr>
					<th scope="col">Coupon Code</th>
					<th scope="col">Discount Type</th>
					<th scope="col">Discount Value</th>
					<th scope="col">Expiration Date</th>
					<th scope="col">Status</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>

			<tbody>

				<?php if ( empty( $coupons ) ) : ?>

					<tr>
						<td colspan="6">
							No coupons found.
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=stauffer-booking-add-coupon' ) ); ?>">
								Create your first coupon
							</a>.
						</td>
					</tr>

				<?php else : ?>

					<?php foreach ( $coupons as $coupon ) : ?>

						<?php
						$edit_url = add_query_arg(
							array(
								'page'      => 'stauffer-booking-add-coupon',
								'coupon_id' => $coupon['id'],
							),
							admin_url( 'admin.php' )
						);

						$delete_url = wp_nonce_url(
							add_query_arg(
								array(
									'page'      => 'stauffer-booking-coupons',
									'action'    => 'delete',
									'coupon_id' => $coupon['id'],
								),
								admin_url( 'admin.php' )
							),
							'stauffer_delete_coupon_' . $coupon['id']
						);

						$is_expired = stauffer_booking_is_coupon_expired( $coupon );
						?>

						<tr>
							<td><strong><?php echo esc_html( $coupon['code'] ); ?></strong></td>

							<td>
								<?php echo 'percentage' === $coupon['type'] ? 'Percentage (%)' : 'Fixed / Flat'; ?>
							</td>

							<td><?php echo stauffer_booking_format_coupon_value( $coupon ); ?></td>

							<td>
								<?php echo $coupon['expires'] ? esc_html( $coupon['expires'] ) : 'Never expires'; ?>
							</td>

							<td>
								<?php if ( $is_expired ) : ?>
									<span class="stauffer-coupon-status expired">Expired</span>
								<?php else : ?>
									<span class="stauffer-coupon-status active">Active</span>
								<?php endif; ?>
							</td>

							<td>
								<a href="<?php echo esc_url( $edit_url ); ?>">Edit</a>
								|
								<a href="<?php echo esc_url( $delete_url ); ?>"
									class="stauffer-delete-coupon"
									onclick="return confirm('Are you sure you want to delete this coupon?');">
									Delete
								</a>
							</td>
						</tr>

					<?php endforeach; ?>

				<?php endif; ?>

			</tbody>

		</table>

	</div>

	<?php
}


/**
 * Add New Coupon page. Doubles as the edit screen when ?coupon_id= is present.
 */
function stauffer_booking_add_coupon_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$error = '';

	$editing_id = isset( $_GET['coupon_id'] ) ? sanitize_text_field( wp_unslash( $_GET['coupon_id'] ) ) : '';

	// Values shown in the form
	$form = array(
		'code'    => '',
		'type'    => 'percentage',
		'amount'  => '',
		'expires' => '',
	);

	if ( $editing_id ) {

		$existing = stauffer_booking_get_coupon_by_id( $editing_id );

		if ( $existing ) {
			$form = array(
				'code'    => $existing['code'],
				'type'    => $existing['type'],
				'amount'  => $existing['amount'],
				'expires' => $existing['expires'],
			);
		} else {
			$editing_id = '';
		}
	}

	// Handle save
	if ( isset( $_POST['stauffer_save_coupon'] ) ) {

		check_admin_referer( 'stauffer_save_coupon_action' );

		$posted_id = sanitize_text_field( wp_unslash( $_POST['coupon_id'] ?? '' ) );

		$code    = strtoupper( sanitize_text_field( wp_unslash( $_POST['coupon_code'] ?? '' ) ) );
		$type    = sanitize_text_field( wp_unslash( $_POST['discount_type'] ?? 'percentage' ) );
		$amount  = (float) ( $_POST['discount_amount'] ?? 0 );
		$expires = sanitize_text_field( wp_unslash( $_POST['expiration_date'] ?? '' ) );

		if ( ! in_array( $type, array( 'percentage', 'fixed' ), true ) ) {
			$type = 'percentage';
		}

		// Keep whatever the user typed on screen if validation fails
		$form = array(
			'code'    => $code,
			'type'    => $type,
			'amount'  => $amount,
			'expires' => $expires,
		);

		$editing_id = $posted_id;

		if ( '' === trim( $code ) ) {

			$error = 'Please enter a coupon code.';

		} elseif ( $amount <= 0 ) {

			$error = 'Discount amount must be greater than zero.';

		} elseif ( 'percentage' === $type && $amount > 100 ) {

			$error = 'A percentage discount can not be greater than 100.';

		} else {

			// Reject a duplicate code, ignoring the coupon currently being edited
			$duplicate = stauffer_booking_get_coupon_by_code( $code );

			if ( $duplicate && $duplicate['id'] !== $posted_id ) {

				$error = 'A coupon with this code already exists.';

			} else {

				$coupons = stauffer_booking_get_coupons();

				if ( $posted_id ) {

					foreach ( $coupons as $index => $coupon ) {

						if ( $coupon['id'] === $posted_id ) {

							$coupons[ $index ] = array(
								'id'      => $posted_id,
								'code'    => $code,
								'type'    => $type,
								'amount'  => $amount,
								'expires' => $expires,
							);

							break;
						}
					}

					stauffer_booking_save_coupons( $coupons );

					wp_safe_redirect( stauffer_booking_coupons_list_url( 'updated' ) );
					exit;
				}

				$coupons[] = array(
					'id'      => uniqid( 'coupon_', true ),
					'code'    => $code,
					'type'    => $type,
					'amount'  => $amount,
					'expires' => $expires,
				);

				stauffer_booking_save_coupons( $coupons );

				wp_safe_redirect( stauffer_booking_coupons_list_url( 'created' ) );
				exit;
			}
		}
	}

	?>

	<div class="wrap">

		<h1><?php echo $editing_id ? 'Edit Coupon' : 'Add New Coupon'; ?></h1>

		<?php if ( $error ) : ?>
			<div class="notice notice-error is-dismissible"><p><?php echo esc_html( $error ); ?></p></div>
		<?php endif; ?>

		<form method="post">

			<?php wp_nonce_field( 'stauffer_save_coupon_action' ); ?>

			<input type="hidden" name="coupon_id" value="<?php echo esc_attr( $editing_id ); ?>">

			<table class="form-table">

				<tr>
					<th><label for="coupon_code">Coupon Code</label></th>
					<td>
						<input type="text" id="coupon_code" name="coupon_code" class="regular-text"
							value="<?php echo esc_attr( $form['code'] ); ?>" required>
						<p class="description">For example WELCOME10. Codes are not case sensitive.</p>
					</td>
				</tr>

				<tr>
					<th><label for="discount_type">Discount Type</label></th>
					<td>
						<select id="discount_type" name="discount_type">
							<option value="percentage" <?php selected( $form['type'], 'percentage' ); ?>>
								Percentage (%)
							</option>
							<option value="fixed" <?php selected( $form['type'], 'fixed' ); ?>>
								Fixed / Flat Discount
							</option>
						</select>
					</td>
				</tr>

				<tr>
					<th><label for="discount_amount">Discount Amount</label></th>
					<td>
						<input type="number" id="discount_amount" name="discount_amount"
							step="0.01" min="0"
							value="<?php echo esc_attr( $form['amount'] ); ?>" required>
						<p class="description">
							For a percentage coupon enter 10 for 10%. For a fixed coupon enter 20 for
							20 <?php echo esc_html( stauffer_booking_get_settings()['currency'] ); ?>.
						</p>
					</td>
				</tr>

				<tr>
					<th><label for="expiration_date">Expiration Date</label></th>
					<td>
						<input type="date" id="expiration_date" name="expiration_date"
							value="<?php echo esc_attr( $form['expires'] ); ?>">
						<p class="description">Leave empty if the coupon should never expire. The coupon stays valid through the whole expiration day.</p>
					</td>
				</tr>

			</table>

			<p>
				<button type="submit" name="stauffer_save_coupon" class="button button-primary">
					<?php echo $editing_id ? 'Update Coupon' : 'Add Coupon'; ?>
				</button>

				<a href="<?php echo esc_url( stauffer_booking_coupons_list_url() ); ?>" class="button">
					Cancel
				</a>
			</p>

		</form>

	</div>

	<?php
}
