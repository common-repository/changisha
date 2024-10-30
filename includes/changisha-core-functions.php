<?php
/**
 * Changisha Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package Changisha\Functions
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filters all data used in admin and frontend.
 */
add_filter( 'query_vars', 'changisha_query_vars' );

/**
 * Enqueues all scripts and styles.
 *
 * @since 1.0.0
 */
function changisha_enqueue_scripts() {
	
	wp_enqueue_script( 'validate', plugin_dir_url( CHANGISHA_PLUGIN_FILE ) . 'vendors/assets/scripts/validate.min.js', array(), '1.19.1', true );
	wp_enqueue_script( 'changisha', plugin_dir_url( CHANGISHA_PLUGIN_FILE ) . 'assets/scripts/changisha.js', array( 'jquery' ), '1.0.0', true );

	wp_enqueue_style( 'changisha', plugin_dir_url( CHANGISHA_PLUGIN_FILE ) . 'assets/styles/changisha.css', false, '1.0.0', 'all' );

}

/**
 * Adds the changisha rewrite rules to WordPress.
 *
 * @since 1.0.0
 */
function changisha_rewrite_rules() {

	add_rewrite_rule( '^/donation/?([^/]*)/?', 'index.php?changisha_donation_action=1', 'top' );
	add_rewrite_rule( '^/callback/?([^/]*)/?', 'index.php?changisha_callback_action=1', 'top' );

}

/**
 * Makes sure WordPress knows about all custom actions.
 *
 * @since 1.0.0
 */
function changisha_query_vars( $query_vars ) {

	$query_vars[] = 'changisha_donation_action';
	$query_vars[] = 'changisha_callback_action';
	return $query_vars;

}

/**
 * Accepts a call to the donations action.
 *
 * @since 1.0.0
 */
function changisha_donations_action() {

	if ( get_query_var( 'changisha_donation_action' ) ) {
		changisha_donations_request();
	}

}

/**
 * Starts donation requests.
 *
 * @since 1.0.0
 */
function changisha_donations_request() {

	if ( isset( $_POST['changishaName'] ) && isset( $_POST['changishaAmount'] ) && isset( $_POST['changishaPhoneNumber'] ) ) {

		$options = get_option( 'changisha_options' );

		$credentials = base64_encode($options['consumer_key'] . ':' . $options['consumer_secret']);

		$response = wp_remote_get( $options['credentials_endpoint'], array( 'headers' => array( 'Authorization' => 'Basic ' . $credentials ) ) );

		$token_array = json_decode( wp_remote_retrieve_body( $response ) );

		if ( is_array( $token_array ) && array_key_exists( "access_token", $token_array ) ) {

			$access_token = $token_array->access_token;

			$curl_post_data = array(
				'BusinessShortCode' => $options['shortcode'],
				'Password' => base64_encode( $options['shortcode'] . $options['pass_key'] . date( 'Ymdhis' ) ),
				'Timestamp' => date( 'Ymdhis' ),
				'TransactionType' => 'CustomerPayBillOnline',
				'Amount' => (int) sanitize_text_field( $_POST['changishaAmount'] ),
				'PartyA' => "254" . substr( sanitize_text_field( $_POST['changishaPhoneNumber'] ), -9 ),
				'PartyB' => $options['shortcode'],
				'PhoneNumber' => "254" . substr( sanitize_text_field( $_POST['changishaPhoneNumber'] ), -9 ),
				'CallBackURL' => site_url() . '/index.php?changisha_callback_action=1',
				'AccountReference' => "254" . substr( sanitize_text_field( $_POST['changishaPhoneNumber'] ), -9 ),
				'TransactionDesc' => 'CustomerPayBillOnline');

			$data_string = json_encode( $curl_post_data );

			$response = wp_remote_post( $options['payments_endpoint'], array( 'timeout' => 60, 'headers' => array( 'Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $access_token ), 'body' => $data_string ) );

			$response_array = json_decode( wp_remote_retrieve_body( $response ) );

			if ( array_key_exists( "ResponseCode", $response_array ) && $response_array->ResponseCode == 0 ) {
					
				changisha_insert_transaction( $response_array->MerchantRequestID, sanitize_text_field( $_POST['changishaName'] ), "254" . substr( sanitize_text_field( $_POST['changishaPhoneNumber'] ), -9 ) );			
					
				echo json_encode( array( "status" => "1", "message" => "Donation request accepted for processing, check your mobile phone for an MPESA prompt and enter your MPESA PIN." ) );

			} else {

				echo json_encode( array( "status" => "2", "message" => "Your donation request has failed. Please, try again." ) );	
			}

		} else {	

			echo json_encode( array( "status" => "3", "message" => "Your donation request cannot be instatiated. Please, check your internet connection then try again." ) );

		}

	} else {

		echo json_encode( array( "status" => "2", "message" => "All fields are required. Please, try again." ) );

	}

	exit();
}

/**
 * Inserts donation transactions.
 *
 * @since 1.0.0
 */
function changisha_insert_transaction( $request_id, $keyword, $phonenumber ) {

	global $wpdb; 

	$table_name = $wpdb->prefix . 'changisha_transactions';

	$wpdb->insert( $table_name, array( 'request_id' => $request_id, 'keyword' => $keyword, 'phonenumber' => $phonenumber, 'dateposted' => date("Y-m-d h:i:s") ) );

}

/**
 * Accepts a call to the callback action.
 *
 * @since 1.0.0
 */
function changisha_callbacks_action() {

	if ( get_query_var( 'changisha_callback_action' ) ) {
		changisha_callback_handler();
	}

}

/**
 * Handles donation transaction callbacks.
 *
 * @since 1.0.0
 */
function changisha_callback_handler() {

	$callbackJSONData = file_get_contents( 'php://input' );

	$callbackData = json_decode( $callbackJSONData );
		
	$request_id = $callbackData->Body->stkCallback->MerchantRequestID;

	$checkout_id = $callbackData->Body->stkCallback->CheckoutRequestID;

	$rescode = $callbackData->Body->stkCallback->ResultCode;

	$resdesc = $callbackData->Body->stkCallback->ResultDesc;

	$receipt_no = $callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;;

	$amount = $callbackData->stkCallback->Body->CallbackMetadata->Item[0]->Value;

	changisha_update_transaction( $request_id, $checkout_id, $rescode, $resdesc, $receipt_no, $amount );

}

/**
 * Updates donation transactions.
 *
 * @since 1.0.0
 */
function changisha_update_transaction( $request_id, $checkout_id, $rescode, $resdesc, $receipt_no, $amount ) {

	global $wpdb;

	$table_name = $wpdb->prefix . 'changisha_transactions';

	$wpdb->update( $table_name, array( 'checkout_id' => $checkout_id, 'result_code' => $rescode, 'result_desc' => $resdesc, 'receipt_no' => $receipt_no, 'amount' => $amount, 'status' => '1' ), array( 'request_id' => $request_id ), array( '%s', '%s', '%s', '%s', '%s', '%s' ), array( '%s' ) );

}

/**
 * Registers all widgets.
 *
 * @since 1.0.0
 */
function changisha_register_widgets() {

	register_widget( 'changisha_widget' );

}

/**
 * Creates all settings.
 *
 * @since 1.0.0
 */
function changisha_admin_init_settings() {

	register_setting( 'changisha_settings', 'changisha_options', 'changisha_validate_options' );

	add_settings_section( 'changisha_main_section', 'MPesa API Integration Settings', 'changisha_settings_callback', 'changisha_settings_section' );

	add_settings_field( 'merchant_title', 'Merchant Title', 'changisha_display_text_field', 'changisha_settings_section', 'changisha_main_section', array( 'name' => 'merchant_title', 'type' => 'text' ) );

	add_settings_field( 'credentials_endpoint', 'Credentials Endpoint URL<br />(Sandbox / Production)', 'changisha_display_text_field', 'changisha_settings_section', 'changisha_main_section', array( 'name' => 'credentials_endpoint', 'type' => 'url' ) );

	add_settings_field( 'payments_endpoint', 'Payments Endpoint URL<br />(Sandbox / Production)', 'changisha_display_text_field', 'changisha_settings_section', 'changisha_main_section', array( 'name' => 'payments_endpoint', 'type' => 'url' ) );

	add_settings_field( 'pass_key', 'Pass Key', 'changisha_display_text_field', 'changisha_settings_section', 'changisha_main_section', array( 'name' => 'pass_key', 'type' => 'password' ) );

	add_settings_field( 'consumer_key', 'Consumer Key', 'changisha_display_text_field', 'changisha_settings_section', 'changisha_main_section', array( 'name' => 'consumer_key', 'type' => 'password' ) );

	add_settings_field( 'consumer_secret', 'Consumer Secret', 'changisha_display_text_field', 'changisha_settings_section', 'changisha_main_section', array( 'name' => 'consumer_secret', 'type' => 'password' ) );

	add_settings_field( 'shortcode', 'Shortcode', 'changisha_display_text_field', 'changisha_settings_section', 'changisha_main_section', array( 'name' => 'shortcode', 'type' => 'number' ) );

}

/**
 * Validates options.
 *
 * @since 1.0.0
 */
function changisha_validate_options( $input ) {

	$input['ca_version'] = CHANGISHA_PG_VERSION;
	$input['db_version'] = CHANGISHA_DB_VERSION;
	return $input;

}
