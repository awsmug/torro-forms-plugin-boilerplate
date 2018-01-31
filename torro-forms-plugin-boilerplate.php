<?php
/**
 * Plugin initialization file
 *
 * @package TorroFormsPluginBoilerplate
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Torro Forms Plugin Boilerplate
 * Plugin URI:  https://plugin-website.com
 * Description: Plugin Boilerplate for Torro Forms.
 * Version:     1.0.0
 * Author:      Awesome UG
 * Author URI:  http://www.awesome.ug
 * License:     GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: torro-forms-plugin-boilerplate
 * Domain Path: /languages/
 * Tags:        extension, torro forms, forms, form builder, api
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the extension.
 *
 * @since 1.0.0
 *
 * @param Torro_Forms $torro Main plugin instance.
 * @return bool|WP_Error True on success, error object on failure.
 */
function torro_forms_plugin_boilerplate_load( $torro ) {
	// Require main extension class file. All other classes will be autoloaded.
	require_once dirname( __FILE__ ) . '/src/extension.php';

	// Use a string here for the extension class name so that this file can be parsed by PHP 5.2.
	$class_name = 'PluginVendor\TorroFormsPluginBoilerplate\Extension';

	// Store the main extension file.
	$main_file = __FILE__;

	// Determine the relative basedir (will be empty unless a must-use plugin).
	$basedir_relative = '';
	$file             = wp_normalize_path( $main_file );
	$mu_plugin_dir    = wp_normalize_path( WPMU_PLUGIN_DIR );
	if ( preg_match( '#^' . preg_quote( $mu_plugin_dir, '#' ) . '/#', $file ) && file_exists( $mu_plugin_dir . '/torro-forms-plugin-boilerplate.php' ) ) {
		$basedir_relative = 'torro-forms-plugin-boilerplate/';
	}

	$result = $torro->extensions()->register( 'torro_forms_plugin_boilerplate', $class_name, $main_file, $basedir_relative );

	if ( is_wp_error( $result ) ) {
		$method = get_class( $torro->extensions() ) . '::register()';
		$torro->error_handler()->doing_it_wrong( $method, $result->get_error_message(), null );
	}

	return $result;
}

if ( function_exists( 'torro_load' ) ) {
	torro_load( 'torro_forms_plugin_boilerplate_load' );
} else {
	add_action( 'torro_loaded', 'torro_forms_plugin_boilerplate_load', 10, 1 );
}
