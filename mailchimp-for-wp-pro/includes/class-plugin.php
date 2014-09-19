<?php

if( ! defined("MC4WP_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class MC4WP {

	/**
	* @var MC4WP_Form_Manager
	*/
	private $form_manager;

	/**
	* @var MC4WP_Checkbox_Manager
	*/
	private $checkbox_manager;

	/**
	* @var MC4WP_API
	*/
	private $api = null;

	/**
	* @var MC4WP_Log
	*/
	private $log;

	/**
	* Constructor
	*/
	public function __construct() {

		spl_autoload_register( array( $this, 'autoload') );

		// init checkboxes
		$this->checkbox_manager = new MC4WP_Checkbox_Manager();

		// init forms
		$this->form_manager = new MC4WP_Form_Manager();

		// check if logging has been disabled
		$disable_logging = apply_filters( 'mc4wp_disable_logging', false );
		if( false === $disable_logging ) {

			// load log functions
			require_once MC4WP_PLUGIN_DIR . 'includes/functions/log.php';

			// initialize logging class
			$this->log = new MC4WP_Log();
		}

		// init widget
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		
	}

	public function autoload( $class ) {

		static $classes = null;

		if( $classes === null ) {

			$include_path = MC4WP_PLUGIN_DIR . 'includes/';

			$classes = array(
				'mc4wp_api'                             => $include_path . 'class-api.php',
				'mc4wp_checkbox_manager'                => $include_path . 'class-checkbox-manager.php',
				'mc4wp_form_manager'                    => $include_path . 'class-form-manager.php',
				'mc4wp_form_request'                    => $include_path . 'class-form-request.php',
				'mc4wp_log'                             => $include_path . 'class-log.php',
				'mc4wp_statistics'                      => $include_path . 'admin/class-statistics.php',
				'mc4wp_widget'                          => $include_path . 'class-widget.php',
				'mc4wp_mailchimp'                       => $include_path . 'class-mailchimp.php',
				'mc4wp_styles_builder'                     => $include_path . 'admin/class-styles-builder.php',

				// license manager
				'dvk_license_manager'                   => $include_path . 'library/license-manager/class-license-manager.php',
				'dvk_plugin_license_manager'            => $include_path . 'library/license-manager/class-plugin-license-manager.php',
				'dvk_product'                           => $include_path . 'library/license-manager/class-product.php',
				'mc4wp_product'                         => $include_path . 'class-product.php',

				// tables
				'mc4wp_forms_table'                     => $include_path . 'tables/class-forms-table.php',
				'mc4wp_log_table'                       => $include_path . 'tables/class-log-table.php',

				// integrations
				'mc4wp_integration'                     => $include_path . 'integrations/class-integration.php',
				'mc4wp_bbpress_integration'             => $include_path . 'integrations/class-bbpress.php',
				'mc4wp_buddypress_integration'          => $include_path . 'integrations/class-buddypress.php',
				'mc4wp_cf7_integration'                 => $include_path . 'integrations/class-cf7.php',
				'mc4wp_events_manager_integration' => $include_path . 'integrations/class-events-manager.php',
				'mc4wp_comment_form_integration'        => $include_path . 'integrations/class-comment-form.php',
				'mc4wp_edd_integration'                 => $include_path . 'integrations/class-edd.php',
				'mc4wp_general_integration'             => $include_path . 'integrations/class-general.php',
				'mc4wp_multisite_integration'           => $include_path . 'integrations/class-multisite.php',
				'mc4wp_registration_form_integration'   => $include_path . 'integrations/class-registration-form.php',
				'mc4wp_woocommerce_integration'         => $include_path . 'integrations/class-woocommerce.php'
			);
		}

		$class_name = strtolower( $class );

		if( isset( $classes[$class_name] ) ) {
			require_once $classes[$class_name];
			return true;
		}

		return false;
	}

	/**
	* @return MC4WP_Form_Manager
	*/
	public function get_form_manager() {
		return $this->form_manager;
	}

	/**
	* @return MC4WP_Checkbox_Manager
	*/
	public function get_checkbox_manager() {
		return $this->checkbox_manager;
	}

	/**
	* Returns an instance of the MailChimp for WordPress API class
	*
	* @return MC4WP_API
	*/
	public function get_api() {

		if( $this->api === null ) {
			$opts = mc4wp_get_options( 'general' );
			$this->api = new MC4WP_API( $opts['api_key'] );
		}
		
		return $this->api;
	}

	public function get_log() {
		return $this->log;
	}

	/**
	* Register the MC4WP_Widget
	*/
	public function register_widget() {
		register_widget( 'MC4WP_Widget' );
	}

}
