<?php
/**
 * Changisha Template Functions
 *
 * General template functions available on both the front-end and admin.
 *
 * @package Changisha\Functions
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders the dashboard page.
 *
 * @since 1.0.0
 */
function changisha_dashboard_page() {
	$current_user = wp_get_current_user();
?>
	<div class="wrap">
		<h2><?php _e( 'Changisha', 'changisha' ); ?></h2>
		<h2><?php _e( 'MPesa donations made simple on WordPress using Changisha plugin.', 'changisha' ); ?></h2>
		<p><?php _e( 'Changisha allows all types of organisations to setup and collect donations via their website\'s using MPESA as their preferred choice of banking solution.', 'changisha' ); ?></p>
		<hr />
		<p><?php _e( 'Copy and paste this shortcode on a post or page to display the donation form.', 'changisha' ); ?></p>
		<p><input type="text" name="shortcode" value="[changisha-form]" class="regular-text" placeholder="Shortcode" onfocus="select();" readonly required />
		<hr />
		<h3><?php _e( 'More subscribers, better newsletters.', 'changisha' ); ?></h3>
		<p><?php _e( 'Learn how to best grow your website traffic by subscribing to our monthly tips.', 'changisha' ); ?></p>
		<form action="https://eneokazi.us10.list-manage.com/subscribe/post?u=55cba3d3e0f33eb26e469b5d9&amp;id=0ad891ece0" method="post" id="mc-embedded-subscribe-form" name="changisha-subscribe-form" target="_blank">
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="changisha-email">Email Address</label></th>
					<td><input type="email" name="EMAIL" value="<?php echo $current_user->user_email; ?>" class="regular-text" id="changisha-email" required /></td>
				</tr>
				<tr>
					<th scope="row"><label for="changisha-fname">First Name</label></th>
					<td><input type="text" name="FNAME" value="<?php echo $current_user->user_firstname; ?>" class="regular-text" id="changisha-fname" required /></td>
				</tr>
				<tr>
					<th scope="row"><label for="changisha-fname">Last Name</label></th>
					<td><input type="text" name="LNAME" value="<?php echo $current_user->user_lastname; ?>" class="regular-text" id="changisha-lname" required /></td>
				</tr>
			</table>
			<p class="submit"><input type="submit" value="Subscribe" name="subscribe" class="button button-primary" /></p>
		</form>
		<hr />
		<h3><?php _e( 'Looking for help?', 'changisha' ); ?></h3>
		<p><?php _e( 'We have some resources available to put you in the right direction.', 'changisha' ); ?></p>
		<ul class="ul-square">
			<li><a href="https://eneokazi.com/changisha/docs/" target="_blank"><?php _e( 'Knowledge Base', 'changisha' ); ?></a></li>
			<li><a href="https://wordpress.org/plugins/changisha/faq/" target="_blank"><?php _e( 'Frequently Asked Questions', 'changisha' ); ?></a></li>
		</ul>
		<p><?php _e( 'If your answer can not be found in the resources listed above, please use the', 'changisha' ); ?> <a href="https://wordpress.org/support/plugin/changisha" target="_blank"><?php _e( 'support forums on WordPress.org</a>.', 'changisha' ); ?></p>
		<p><?php _e( 'Found a bug? Please', 'changisha' ); ?> <a href="https://github.com/eneokazi254/changisha/issues" target="_blank"><?php _e( 'open an issue on GitHub', 'changisha' ); ?></a>.</p>
	</div>
<?php
}

/**
 * Renders the settings callback.
 *
 * @since 1.0.0
 */
function changisha_settings_callback() {
?>
	<p><?php _e( 'For more information visit: <a href="https://eneokazi.com/changisha/docs/" target="_blank">https://eneokazi.com/changisha/docs/</a>, send an e-mail to <a href="mailto:support@eneokazi.com">support@eneokazi.com</a> or call us <a href="tel:+254794984164">+254 (0) 794984164</a> today.' ); ?></p>
<?php
}

/**
 * Renders options text fields.
 *
 * @since 1.0.0
 */
function changisha_display_text_field( $data = array() ) {

	extract($data);

	$options = get_option( 'changisha_options' );
?>
	<input type="<?php echo $type; ?>" name="changisha_options[<?php echo $name; ?>]" value="<?php echo esc_html( $options[$name] ); ?>" class="regular-text" required />
<?php
}

/**
 * Renders the settings page.
 *
 * @since 1.0.0
 */
function changisha_settings_page() {
?>
	<div class="wrap">
		<h2><?php _e( 'Settings', 'changisha' ); ?></h2>
		<?php settings_errors(); ?>
		<form name="changisha_options_form_settings_api" action="<?php echo admin_url( 'options.php' ); ?>" method="post">
			<?php settings_fields( 'changisha_settings' ); ?>
			<?php do_settings_sections( 'changisha_settings_section' ); ?>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p>
		</form>
	</div>
<?php
}
