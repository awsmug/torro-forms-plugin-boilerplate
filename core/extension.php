<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Torro_Forms_Plugin_Boilerplate extends Torro_Extension {
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initializing.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
	}

	protected function init() {
		$this->title = 'Torro Forms Plugin Boilerplate';

		$this->name = 'torro_forms_plugin_boilerplate';

		$this->item_name = 'Torro Forms Plugin Boilerplate';

		$this->plugin_file = dirname( dirname( __FILE__ ) ) . '/torro-forms-plugin-boilerplate.php';

		$this->version = '1.0.0';

		$this->settings_fields = array(
			// this key must exist for all purchaseable extensions
			'serial'			=> array(
				'title'					=> __( 'Serial Number', 'torro-forms' ),
				'description'			=> __( 'Input the serial number of the conditional logic extension.', 'torro-forms' ),
				'type'					=> 'text',
			),
		);

		add_filter( 'torro_template_locations', array( $this, 'register_template_location' ) );
	}

	public function register_template_location( $locations ) {
		$locations[70] = $this->get_path( 'templates/' );

		return $locations;
	}

	protected function includes() {
		require_once $this->get_path( 'core/element-types/date.php' );
		require_once $this->get_path( 'core/element-types/iframe.php' );
		require_once $this->get_path( 'components/actions/pluginsearcher.php' );
	}

	public function frontend_scripts() {
		// not needed (this function could also be removed from here)
	}

	public function frontend_styles() {
		wp_enqueue_style( 'torro-forms-plugin-boilerplate-frontend', $this->get_asset_url( 'frontend', 'css' ), array(), $this->version );
	}

	public function admin_scripts() {
		wp_enqueue_script( 'torro-forms-plugin-boilerplate-admin', $this->get_asset_url( 'admin', 'js' ), array( 'torro-form-edit' ), $this->version );
	}

	public function admin_styles() {
		wp_enqueue_style( 'torro-forms-plugin-boilerplate-admin', $this->get_asset_url( 'admin', 'css' ), array( 'torro-form-edit' ), $this->version );
	}
}

torro()->extensions()->register( 'Torro_Forms_Plugin_Boilerplate' );
