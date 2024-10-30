<?php

/**
 * Changisha
 *
 * @package Changisha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Changisha Class.
 *
 * @class Changisha
 */
final class Changisha {

	/**
	 * Changisha version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * The single instance of the class.
	 *
	 * @var Changisha
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * Changisha_Help_Tabs object.
	 *
	 * @var Changisha
	 * @since 1.0.0
	 */
	public $help_tabs;

	/**
	 * Changisha Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->define_constants();
		$this->includes();
		$this->init_hooks();

	}

	/**
	 * Main Changisha Instance.
	 *
	 * Ensures only one instance of Changisha is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Changisha - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {

		/**
		 * Core functions.
		 */
		include_once dirname( CHANGISHA_PLUGIN_FILE ) . '/includes/changisha-core-functions.php';
		include_once dirname( CHANGISHA_PLUGIN_FILE ) . '/includes/changisha-template-functions.php';
		include_once dirname( CHANGISHA_PLUGIN_FILE ) . '/includes/changisha-shortcode-functions.php';

		/**
		 * Core classes.
		 */
		include_once dirname( CHANGISHA_PLUGIN_FILE ) . '/includes/class-changisha-widget.php';
		include_once dirname( CHANGISHA_PLUGIN_FILE ) . '/includes/class-changisha-help-tabs.php';

	}

	/**
	 * Hook into actions, filters and shortcodes.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {

		add_action( 'wp_enqueue_scripts', 'changisha_enqueue_scripts' );
		add_action( 'init', 'changisha_rewrite_rules' );
		add_action( 'wp', 'changisha_donations_action' );
		add_action( 'wp', 'changisha_callbacks_action' );
		add_action( 'widgets_init', 'changisha_register_widgets' );
		add_action( 'admin_menu', array( $this, 'load_menus' ) );
		add_action( 'admin_init', 'changisha_admin_init_settings' );

		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );

		add_shortcode( 'changisha-form', 'changisha_generate_shortcode' );

	}

	/**
	 * Define Constants.
	 */
	private function define_constants() {

		$this->define( 'CHANGISHA_ABSPATH', dirname( CHANGISHA_PLUGIN_FILE ) . '/' );
		$this->define( 'CHANGISHA_PLUGIN_BASENAME', plugin_basename( CHANGISHA_PLUGIN_FILE ) );
		$this->define( 'CHANGISHA_DB_VERSION', $this->version );
		$this->define( 'CHANGISHA_PG_VERSION', $this->version );

	}

	/**
	 * Set screen options.
	 *
	 * @since 1.0.0
	 */
	public static function set_screen( $status, $option, $value ) {

		return $value;

	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	* Creates plugin menus, help tabs and the screen options.
	*
	 * @since 1.0.0
	*/
	public function load_menus() {

		$dashboard_menu = add_menu_page(
			__( 'Changisha', 'changisha' ),
			__( 'Changisha', 'changisha' ),
			'manage_options',
			'changisha-dashboard-page',
			'changisha_dashboard_page',
			'dashicons-book',
			99
		);

		$settings_menu = add_submenu_page(
			'changisha-dashboard-page',
			__( 'Settings', 'changisha' ),
			__( 'Settings', 'changisha' ),
			'manage_options',
			'changisha-settings-page',
			'changisha_settings_page'
		);

		add_action( "load-$dashboard_menu", [ $this, 'dashboard_help_tabs' ] );
		add_action( "load-$settings_menu", [ $this, 'settings_help_tabs' ] );

	}

	/**
	 * Gets all dashboard help tabs.
	 *
	 * @since 1.0.0
	 */
	public function dashboard_help_tabs() {

		$this->help_tabs = new Changisha_Help_Tabs( get_current_screen() );

		$this->help_tabs->set_help_tabs( 'dashboard' );
		
	}

	/**
	 * Gets all settings help tabs.
	 *
	 * @since 1.0.0
	 */
	public function settings_help_tabs() {

		$this->help_tabs = new Changisha_Help_Tabs( get_current_screen() );

		$this->help_tabs->set_help_tabs( 'settings' );
		
	}

}
