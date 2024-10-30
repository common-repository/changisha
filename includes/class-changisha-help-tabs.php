<?php

/**
 * Changisha Help Tabs
 *
 * @package Changisha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Changisha Help Tabs Class.
 *
 * @class Changisha_Help_Tabs
 */
class Changisha_Help_Tabs {

	/**
	 * Screen object.
	 *
	 * @var Changisha
	 * @since 1.0.0
	 */
	private $screen;

	/**
	 * Class constructor
	 */
	public function __construct( WP_Screen $screen ) {

		$this->screen = $screen;

	}

	/**
	* Renders the help tabs.
	*
	 * @since 1.0.0
	*/
	public function set_help_tabs( $type ) {

		switch ( $type ) {

			case 'dashboard':

			$this->screen->add_help_tab( array(
				'id' => 'dashboard_overview',
				'title' => __( 'Overview', 'Changisha' ),
				'content' => $this->content( 'dashboard_overview' ) ) );

			$this->sidebar();

			return;

			case 'settings':

			$this->screen->add_help_tab( array(
				'id' => 'settings_overview',
				'title' => __( 'Overview', 'Changisha' ),
				'content' => $this->content( 'settings_overview' ) ) );

			$this->sidebar();

			return;

		}

	}

	/**
	* Gets the required content.
	*
	 * @since 1.0.0
	*/
	private function content( $name ) {

		$content = array();

		$content['dashboard_overview'] = '<p>' . __( "Changisha allows all types of organisations to setup and collect donations via their website's using MPESA as their preferred choice of banking solution.", 'changisha' ) . '</p>';

		$content['settings_overview'] = '<p>' . __( "The fields on this screen determine some of the basics of Changisha plugin setup.", 'changisha' ) . '</p>';

		$content['settings_overview'] .= '<p>' . __( "All fields are required by the MPesa API and your website when making and processing payment requests via the plugin.", 'changisha' ) . '</p>';

		$content['settings_overview'] .= '<p>' . __( "Make sure to click the <strong>Save Changes</strong> button at the bottom of the screen for new settings to take effect.", 'changisha' ) . '</p>';

		if ( ! empty( $content[$name] ) ) {

			return $content[$name];

		}

	}

	/**
	* Renders the sidebar.
	*
	 * @since 1.0.0
	*/
	public function sidebar() {

		$content = '<p><strong>' . __( 'For more information:', 'changisha' ) . '</strong></p>';
		$content .= '<p><a href="https://eneokazi.com/changisha/docs/" target="_blank">' . __( 'Docs', 'changisha' ) . '</a></p>';
		$content .= '<p><a href="https://wordpress.org/plugins/changisha/faq/" target="_blank">' . __( 'FAQs', 'changisha' ) . '</a></p>';
		$content .= '<p><a href="https://eneokazi.com/support/" target="_blank">' . __( 'Support', 'changisha' ) . '</a></p>';

		$this->screen->set_help_sidebar( $content );

	}

}
