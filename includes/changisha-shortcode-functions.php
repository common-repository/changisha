<?php
/**
 * Changisha Shortcode Functions
 *
 * General shortcode functions available on both the front-end and admin.
 *
 * @package Changisha\Functions
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders the donation form widget on a post or page.
 *
 * @since 1.0.0
 */
function changisha_generate_shortcode() {

	$output = '<div class="changisha-form-box"><form id="changishaForm" action="' . site_url() . '/index.php?changisha_donation_action=1" method="post">';
	$output .= '<div class="changisha-form-text"><input type="text" id="changishaName" name="changishaName" value="" placeholder="Name" style="width: 100%;" required /></div>';
	$output .= '<div class="changisha-form-text"><input type="number" id="changishaPhoneNumber" name="changishaPhoneNumber" value="" placeholder="Phone Number" style="width: 100%;" required /></div>';
	$output .= '<div class="changisha-form-text"><input type="number" id="changishaAmount" name="changishaAmount" value="" placeholder="Amount" style="width: 100%;" required /></div>';
	$output .= '<p id="alertBox" class="changisha-form-alert hide"></p>';
	$output .= '<div><button type="submit">Donate</button></div>';
	$output .= '</form></div>';

	return $output;
}