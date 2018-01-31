<?php
/**
 * Plugin initialization file
 *
 * @package TorroFormsPluginBoilerplate
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Torro Forms Plugin Boilerplate
 * Plugin URI:  https://torro-forms-plugin-boilerplate.com
 * Description: Plugin Boilerplate for Torro Forms.
 * Version:     1.0.0
 * Author:      Awesome UG
 * Author URI:  http://www.awesome.ug
 * License:     GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: torro-forms-plugin-boilerplate
 * Tags:        extension, torro forms, forms, form builder, api
 */

defined( 'ABSPATH' ) || exit;

class Torro_Forms_Plugin_Boilerplate_Init {
	public static function init() {
		self::load_textdomain();

		if ( ! function_exists( 'torro_load' ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'torro_not_active' ) );
			return;
		}

		torro_load( array( __CLASS__, 'load_extension' ) );
	}

	public static function load_extension() {
		require_once plugin_dir_path( __FILE__ ) . 'core/extension.php';
	}

	public static function torro_not_active() {
		?>
		<div class="notice notice-warning">
			<p><?php printf( __( 'Torro Forms is not activated. Please activate it in order to use the extension %s.', 'torro-forms-plugin-boilerplate' ), 'Torro Forms Plugin Boilerplate' ); ?></p>
		</div>
		<?php
	}

	private static function load_textdomain() {
		return load_plugin_textdomain( 'torro-forms-plugin-boilerplate', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}

add_action( 'plugins_loaded', array( 'Torro_Forms_Plugin_Boilerplate_Init', 'init' ) );
