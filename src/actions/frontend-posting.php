<?php
/**
 * Frontend posting action class
 *
 * @package TorroForms
 * @since 1.1.0
 */

namespace PluginVendor\TorroFormsPluginBoilerplate\Actions;

use awsmug\Torro_Forms\Modules\Actions\API_Action;
use awsmug\Torro_Forms\DB_Objects\Forms\Form;
use awsmug\Torro_Forms\DB_Objects\Submissions\Submission;
use WP_Error;

/**
 * Class for an action that creates WordPress posts from the frontend via the REST API.
 *
 * @since 1.1.0
 */
class Frontend_Posting extends API_Action {

	/**
	 * Slug for the API API structure.
	 *
	 * @since 1.1.0
	 */
	const STRUCTURE_SLUG = 'local-rest-api';

	/**
	 * Bootstraps the submodule by setting properties.
	 *
	 * @since 1.1.0
	 */
	protected function bootstrap() {
		$this->slug  = 'frontend_posting';
		$this->title = __( 'Frontend Posting', 'torro-forms-plugin-boilerplate' );

		$this->register_site_apiapi_structure();
	}

	/**
	 * Gets the available API structures and their routes.
	 *
	 * @since 1.1.0
	 *
	 * @return array Associative array of $structure_slug => $data pairs. $data must be an associative array with keys
	 *               'title' and 'routes'. 'routes' must be an associative array of $route_slug => $route_title pairs.
	 */
	protected function get_available_structures_and_routes() {
		$routes = array();

		$post_types = get_post_types( array( 'show_in_rest' => true ), 'objects' );
		foreach ( $post_types as $post_type ) {
			if ( ! empty( $post_type->rest_controller_class ) && 'WP_REST_Posts_Controller' !== $post_type->rest_controller_class ) {
				continue;
			}

			$rest_base = ! empty( $post_type->rest_base ) ? $post_type->rest_base : $post_type->name;

			$routes[ 'POST:/wp/v2/' . $rest_base ] = array(
				'title' => $post_type->labels->add_new_item,
			);
		}

		return array(
			self::STRUCTURE_SLUG => array(
				'title'  => __( 'Local REST API', 'torro-forms-plugin-boilerplate' ),
				'routes' => $routes,
			),
		);
	}

	/**
	 * Registers the REST API structure for this site.
	 *
	 * It needs to be manually registered because the API-API structure for the WordPress REST API
	 * only registers for wordpress.org and wordpress.com by default.
	 *
	 * @since 1.1.0
	 */
	protected function register_site_apiapi_structure() {
		apiapi_register_structure_wordpress( self::STRUCTURE_SLUG, rest_url( '/' ) );
	}
}
