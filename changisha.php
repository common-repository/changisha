<?php

/**
 * Plugin Name: Changisha
 * Plugin URI: https://eneokazi.com/changisha
 * Description: MPESA donation plugin built for WordPress.
 * Version: 1.0.0
 * Author: Eneokazi Ltd
 * Author URI: https://eneokazi.com/
 * Text Domain: changisha
 * Domain Path: /i18n/languages/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Changisha
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'CHANGISHA_PLUGIN_FILE' ) ) {
	define( 'CHANGISHA_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'Changisha', false ) ) {
	include_once dirname( CHANGISHA_PLUGIN_FILE ) . '/includes/class-changisha.php';
}

/**
 * Returns the main instance of Changisha.
 *
 * @since  1.0.0
 * @return Changisha
 */
function changisha() {
	return Changisha::get_instance();
}
add_action( 'plugins_loaded', 'changisha' );

/**
 * Installs Changisha Widget.
 *
 * @since 1.0.0
 */
function changisha_widget_install() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'changisha_transactions';

	$charset_collate = $wpdb->get_charset_collate();
			
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		keyword varchar(60) NOT NULL,
		request_id varchar(40) NOT NULL,
		checkout_id varchar(40) NULL,
		receipt_no varchar(20) NULL,
		amount varchar(40) NULL,
		phonenumber varchar(20) NOT NULL,
		dateposted datetime NOT NULL,
		result_code tinyint(1) unsigned NULL,
		result_desc varchar(150) NULL,
		status tinyint(1) unsigned DEFAULT '0' NOT NULL,
		deleted tinyint(1) unsigned DEFAULT '0' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );

	$options = [
		'db_version' => '1.0.0',
		'ca_version' => '1.0.0',
		'merchant_title' => '',
		'credentials_endpoint' => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
		'payments_endpoint' => 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
		'pass_key' => '',
		'consumer_key' => '',
		'consumer_secret' => '',
		'shortcode' => '',
	];
			
	add_option( 'changisha_options', $options );

}

register_activation_hook( CHANGISHA_PLUGIN_FILE , 'changisha_widget_install' );
