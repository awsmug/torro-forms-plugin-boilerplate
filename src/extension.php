<?php
/**
 * Extension main class
 *
 * @package TorroFormsPluginBoilerplate
 * @since 1.0.0
 */

namespace PluginVendor\TorroFormsPluginBoilerplate;

use awsmug\Torro_Forms\Components\Extension as Extension_Base;
use Leaves_And_Love\Plugin_Lib\Assets;

/**
 * Extension main class.
 *
 * @since 1.0.0
 */
class Extension extends Extension_Base {

	/**
	 * The assets manager instance.
	 *
	 * @since 1.0.0
	 * @var Assets
	 */
	protected $assets;

	/**
	 * Checks whether the extension can run on this setup.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Error|null Error object if the extension cannot run on this setup, null otherwise.
	 */
	public function check() {
		return null;
	}

	/**
	 * Loads the base properties of the class.
	 *
	 * @since 1.0.0
	 */
	protected function load_base_properties() {
		$this->version      = '1.0.0';
		$this->vendor_name  = 'PluginVendor';
		$this->project_name = 'TorroFormsPluginBoilerplate';
	}

	/**
	 * Loads the extension's textdomain.
	 *
	 * @since 1.0.0
	 */
	protected function load_textdomain() {
		$this->load_plugin_textdomain( 'torro-forms-plugin-boilerplate', '/languages' );
	}

	/**
	 * Instantiates the extension services.
	 *
	 * Any service instances registered in here can be retrieved from the outside,
	 * by calling a method with the same name of the property.
	 *
	 * @since 1.0.0
	 */
	protected function instantiate_services() {
		// This is sample code and only needed if your extension includes assets.
		$this->assets = new Assets( 'torro_pb_', array(
			'path_callback'  => array( $this, 'path' ),
			'url_callback'   => array( $this, 'url' ),
			'plugin_version' => $this->version,
		) );

		// This is sample code to register this extension's template location.
		// It can be removed if the extension does not include any templates.
		$this->parent_plugin->template()->register_location( 'torro-forms-plugin-boilerplate', $this->path( 'templates/' ) );
	}

	/**
	 * Registers the 'date' element type part of the extension.
	 *
	 * This method is sample code and can be removed.
	 *
	 * @since 1.0.0
	 *
	 * @param \awsmug\Torro_Forms\DB_Objects\Elements\Element_Types $element_type_manager Element type manager.
	 */
	protected function register_date_element_type( $element_type_manager ) {
		$element_type_manager->register( 'date', 'PluginVendor\TorroFormsPluginBoilerplate\Element_Types\Date' );
	}

	/**
	 * Registers the extension stylesheet.
	 *
	 * @since 1.0.0
	 */
	protected function register_stylesheet() {
		$this->assets->register_style( 'date-element', 'assets/dist/css/date-element.css', array(
			'deps' => array( 'torro-frontend' ),
			'ver'  => $this->version,
		) );
	}

	/**
	 * Enqueues the extension stylesheet if not prevented by setting.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $load_css Whether CSS assets should not be enqueued.
	 */
	protected function enqueue_stylesheet( $load_css ) {
		if ( ! $load_css ) {
			return;
		}

		$this->assets->enqueue_style( 'date-element' );
	}

	/**
	 * Sets up all action and filter hooks for the service.
	 *
	 * This method must be implemented and then be called from the constructor.
	 *
	 * @since 1.0.0
	 */
	protected function setup_hooks() {
		// The following hooks are sample code and can be removed.
		$this->actions[] = array(
			'name'     => 'torro_register_element_types',
			'callback' => array( $this, 'register_date_element_type' ),
			'priority' => 10,
			'num_args' => 1,
		);
		$this->actions[] = array(
			'name'     => 'torro_register_assets',
			'callback' => array( $this, 'register_stylesheet' ),
			'priority' => 10,
			'num_args' => 0,
		);
		$this->actions[] = array(
			'name'     => 'torro_enqueue_form_frontend_assets',
			'callback' => array( $this, 'enqueue_stylesheet' ),
			'priority' => 10,
			'num_args' => 1,
		);
	}

	/**
	 * Checks whether the dependencies have been loaded.
	 *
	 * If this method returns false, the extension will attempt to require the composer-generated
	 * autoloader script. If your extension uses additional dependencies, override this method with
	 * a check whether these dependencies already exist.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the dependencies are loaded, false otherwise.
	 */
	protected function dependencies_loaded() {
		return true;
	}
}
