<?php
/**
 * Autocomplete element type class
 *
 * @package TorroFormsPluginBoilerplate
 * @since 1.0.0
 */

namespace PluginVendor\TorroFormsPluginBoilerplate\Element_Types;

use awsmug\Torro_Forms\DB_Objects\Elements\Element_Types\Element_Type;
use WP_REST_Request;

/**
 * Class representing an autocomplete element type using the REST API.
 */
class Autocomplete extends Element_Type {

	/**
	 * Filters the array representation of a given element of this type.
	 *
	 * @param array           $data       Element data to filter.
	 * @param Element         $element    The element object to get the data for.
	 * @param Submission|null $submission Optional. Submission to get the values from, if available. Default null.
	 * @return array Array including all information for the element type.
	 */
	public function filter_json( $data, $element, $submission = null ) {
		$data = parent::filter_json( $data, $element, $submission );

		$settings = $this->get_settings( $element );

		$datasources = $this->get_autocomplete_datasources();
		if ( ! empty( $settings['datasource'] ) && isset( $datasources[ $settings['datasource'] ] ) ) {
			$datasource = $datasources[ $settings['datasource'] ];
		} else {
			// Just for extra security, fall back to regular posts.
			$datasource = $datasources['post_type_post'];
		}

		// Attributes for an extra hidden field that holds the actual item ID.
		$data['extra_input_attrs'] = array(
			'type' => 'hidden',
			'id'   => $data['input_attrs']['id'] . '-raw',
			'name' => $data['input_attrs']['name'],
		);

		// Adjustments of the autocomplete field that displays the visual label for the actual item ID.
		$data['input_attrs']['type']                 = 'text';
		$data['input_attrs']['data-target-id']       = $data['extra_input_attrs']['id'];
		$data['input_attrs']['data-search-route']    = $datasource['rest_placeholder_search_route'];
		$data['input_attrs']['data-value-generator'] = $datasource['value_generator'];
		$data['input_attrs']['data-label-generator'] = $datasource['label_generator'];
		if ( ! empty( $data['input_attrs']['class'] ) ) {
			$data['input_attrs']['class'] .= ' torro-plugin-boilerplate-autocomplete';
		} else {
			$data['input_attrs']['class'] = 'torro-plugin-boilerplate-autocomplete';
		}
		unset( $data['input_attrs']['name'] );

		$data['value_label'] = '';
		if ( ! empty( $data['value'] ) ) {
			$rest_url = rest_url( str_replace( '%value%', $data['value'], $datasource['rest_placeholder_label_route'] ) );
			$request  = WP_REST_Request::from_url( $rest_url );
			if ( $request ) {
				$response = rest_do_request( $request );
				if ( ! is_wp_error( $response ) && ! $response->is_error() ) {
					$item          = $response->get_data();
					$property_path = explode( '.', trim( $datasource['label_generator'], '%' ) );
					$not_found     = false;

					while ( ! empty( $property_path ) ) {
						$property = array_shift( $property_path );
						if ( ! isset( $item->$property ) ) {
							$not_found = true;
							break;
						}

						$item = $item->$property;
					}

					if ( ! $not_found ) {
						$data['value_label'] = $item;
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Validates a field value for an element.
	 *
	 * @param mixed      $value      The value to validate. It is already unslashed when it arrives here.
	 * @param Element    $element    Element to validate the field value for.
	 * @param Submission $submission Submission the value belongs to.
	 * @return mixed|WP_Error Validated value, or error object on failure.
	 */
	public function validate_field( $value, $element, $submission ) {
		$settings = $this->get_settings( $element );

		$value = (int) trim( $value );

		if ( ! empty( $settings['required'] ) && empty( $value ) ) {
			return $this->create_error( 'value_required', __( 'You must enter something here.', 'myplugin' ) );
		}

		$datasources = $this->get_autocomplete_datasources();
		if ( ! empty( $settings['datasource'] ) && isset( $datasources[ $settings['datasource'] ] ) ) {
			$datasource = $datasources[ $settings['datasource'] ];
		} else {
			// Just for extra security, fall back to regular posts.
			$datasource = $datasources['post_type_post'];
		}

		$rest_url = rest_url( str_replace( '%value%', $value, $datasource['rest_placeholder_label_route'] ) );
		$request  = WP_REST_Request::from_url( $rest_url );
		if ( ! $request ) {
			return $this->create_error( 'invalid_rest_placeholder_label_route', __( 'Internal error: Invalid REST placeholder label route.', 'myplugin' ) );
		}

		$response = rest_do_request( $request );
		if ( is_wp_error( $response ) || $response->is_error() ) {
			return $this->create_error( 'invalid_value', __( 'The value is invalid.', 'myplugin' ) );
		}

		return $value;
	}

	/**
	 * Bootstraps the element type by setting properties.
	 */
	protected function bootstrap() {
		$this->slug        = 'autocomplete';
		$this->title       = __( 'Autocomplete', 'myplugin' );
		$this->description = __( 'An autocomplete text field element using the REST API.', 'myplugin' );
		$this->icon_svg_id = 'torro-icon-textfield';

		$this->add_description_settings_field();
		$this->add_placeholder_settings_field();
		$this->add_required_settings_field();
		$this->settings_fields['datasource'] = array(
			'section'     => 'settings',
			'type'        => 'select',
			'label'       => __( 'Datasource', 'myplugin' ),
			'description' => __( 'Select what type of content the autocomplete should use.', 'myplugin' ),
			'choices'     => array_map( function( $data ) {
				return $data['label'];
			}, $this->get_autocomplete_datasources() ),
			'default'     => 'post_type_post',
		);
		$this->add_css_classes_settings_field();
	}

	/**
	 * Gets the available datasources with their definition.
	 *
	 * Every post type and taxonomy that has a REST API endpoint is available as a datasource.
	 *
	 * @return array Associative array of $datasource_slug => $data pairs.
	 */
	protected function get_autocomplete_datasources() {
		$datasources = array();

		$post_types = get_post_types( array( 'show_in_rest' => true ), 'objects' );
		foreach ( $post_types as $post_type ) {
			$rest_base = ! empty( $post_type->rest_base ) ? $post_type->rest_base : $post_type->name;

			$datasources[ 'post_type_' . $post_type->name ] = array(
				'label'                         => $post_type->label,
				'rest_placeholder_search_route' => 'wp/v2/' . $rest_base . '?search=%search%',
				'rest_placeholder_label_route'  => 'wp/v2/' . $rest_base . '/%value%',
				'value_generator'               => '%id%',
				'label_generator'               => '%title.rendered%',
			);
		}

		$taxonomies = get_taxonomies( array( 'show_in_rest' => true ), 'object' );
		foreach ( $taxonomies as $taxonomy ) {
			$rest_base = ! empty( $taxonomy->rest_base ) ? $taxonomy->rest_base : $taxonomy->name;

			$datasources[ 'taxonomy_' . $taxonomy->name ] = array(
				'label'                         => $taxonomy->label,
				'rest_placeholder_search_route' => 'wp/v2/' . $rest_base . '?search=%search%',
				'rest_placeholder_label_route'  => 'wp/v2/' . $rest_base . '/%value%',
				'value_generator'               => '%id%',
				'label_generator'               => '%name%',
			);
		}

		return $datasources;
	}
}
