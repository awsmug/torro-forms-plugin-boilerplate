<?php
/**
 * Extension main class
 *
 * @package TorroFormsPluginBoilerplate
 * @since 1.0.0
 */

namespace PluginVendor\TorroFormsPluginBoilerplate;

use awsmug\Torro_Forms\Components\Extension as Extension_Base;

class Extension extends Extension_Base {

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
	 * @since 1.0.0
	 */
	protected function instantiate_services() {
		// The following call is sample code to register this extension's template location.
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
	 * Sets up all action and filter hooks for the service.
	 *
	 * This method must be implemented and then be called from the constructor.
	 *
	 * @since 1.0.0
	 */
	protected function setup_hooks() {
		// The following hook is sample code and can be removed.
		$this->actions[] = array(
			'name'     => 'torro_register_element_types',
			'callback' => array( $this, 'register_date_element_type' ),
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
