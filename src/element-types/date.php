<?php
/**
 * Date element type class
 *
 * @package TorroFormsPluginBoilerplate
 * @since 1.0.0
 */

namespace PluginVendor\TorroFormsPluginBoilerplate\Element_Types;

use awsmug\Torro_Forms\DB_Objects\Elements\Element_Types\Element_Type;

/**
 * Class representing a dropdown-based date element type.
 *
 * @since 1.0.0
 */
class Date extends Element_Type {

	/**
	 * Filters the array representation of a given element of this type.
	 *
	 * @since 1.0.0
	 *
	 * @global WP_Locale $wp_locale WordPress locale object
	 *
	 * @param array           $data       Element data to filter.
	 * @param Element         $element    The element object to get the data for.
	 * @param Submission|null $submission Optional. Submission to get the values from, if available. Default null.
	 * @return array Array including all information for the element type.
	 */
	public function filter_json( $data, $element, $submission = null ) {
		global $wp_locale;

		$data = parent::filter_json( $data, $element, $submission );

		$settings = $this->get_settings( $element );

		$min_year = ! empty( $settings['min_year'] ) ? $settings['min_year'] : current_time( 'Y' );
		$max_year = ! empty( $settings['max_year'] ) ? $settings['max_year'] : current_time( 'Y' );

		$day_range  = range( 1, 31 );
		$year_range = range( $min_year, $max_year );

		$data['date_fields_order'] = $this->detect_date_fields_order();

		$data['date_choices'] = array(
			'year'  => array_combine( $year_range, $year_range ),
			'month' => $wp_locale->month,
			'day'   => array_combine( $day_range, $day_range ),
		);

		$data['date_labels'] = array(
			'year'  => __( 'Year', 'torro-forms-plugin-boilerplate' ),
			'month' => __( 'Month', 'torro-forms-plugin-boilerplate' ),
			'day'   => __( 'Day', 'torro-forms-plugin-boilerplate' ),
		);

		$data['date_values'] = array(
			'year'  => 0,
			'month' => '00',
			'day'   => 0,
		);

		if ( ! empty( $data['value'] ) ) {
			list( $data['date_values']['year'], $data['date_values']['month'], $data['date_values']['day'] ) = explode( '-', $data['value'], 3 );

			$data['date_values']['month'] = zeroise( $data['date_values']['month'], 2 );
		}

		$data['legend_attrs'] = $data['label_attrs'];
		unset( $data['legend_attrs']['for'] );

		$data['input_attrs']['name']  .= '[%index%]';
		$data['input_attrs']['id']     = str_replace( (string) $element->id, (string) $element->id . '-%index%', $data['input_attrs']['id'] );
		$data['label_attrs']['id']     = str_replace( (string) $element->id, (string) $element->id . '-%index%', $data['label_attrs']['id'] );
		$data['label_attrs']['for']    = str_replace( (string) $element->id, (string) $element->id . '-%index%', $data['label_attrs']['for'] );
		$data['label_attrs']['class'] .= ' screen-reader-text';

		return $data;
	}

	/**
	 * Validates a field value for an element.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed      $value      The value to validate. It is already unslashed when it arrives here.
	 * @param Element    $element    Element to validate the field value for.
	 * @param Submission $submission Submission the value belongs to.
	 * @return mixed|WP_Error Validated value, or error object on failure.
	 */
	public function validate_field( $value, $element, $submission ) {
		$settings = $this->get_settings( $element );

		// Support Y-m-d string passed, just so that submission editing with single field works too.
		if ( is_string( $value ) && 2 === substr_count( $value, '-' ) ) {
			list( $year, $month, $day ) = array_map( 'absint', explode( '-', $value ) );
		} else {
			$value = (array) $value;

			$year  = ! empty( $value['year'] ) ? (int) $value['year'] : 0;
			$month = ! empty( $value['month'] ) ? (int) $value['month'] : 0;
			$day   = ! empty( $value['day'] ) ? (int) $value['day'] : 0;
		}

		$parsed_value = zeroise( $year, 4 ) . '-' . zeroise( $month, 2 ) . '-' . zeroise( $day, 2 );

		if ( ! empty( $settings['required'] ) && ( empty( $year ) || empty( $month ) || empty( $day ) ) ) {
			return $this->create_error( 'value_required', __( 'You must enter something here.', 'torro-forms-plugin-boilerplate' ), $parsed_value );
		}

		if ( $month < 1 || $month > 12 ) {
			return $this->create_error( 'month_out_of_boundaries', __( 'The value for the month must be between 1 and 12.', 'torro-forms-plugin-boilerplate' ), $parsed_value );
		}

		if ( $day < 1 || $month > 31 ) {
			return $this->create_error( 'day_out_of_boundaries', __( 'The value for the day must be between 1 and 31.', 'torro-forms-plugin-boilerplate' ), $parsed_value );
		}

		$min_year = ! empty( $settings['min_year'] ) ? (int) $settings['min_year'] : (int) current_time( 'Y' );
		$max_year = ! empty( $settings['max_year'] ) ? (int) $settings['max_year'] : (int) current_time( 'Y' );

		if ( $year < $min_year || $year > $max_year ) {
			/* translators: 1: minimum year, 2: maximum year */
			return $this->create_error( 'year_out_of_boundaries', sprintf( __( 'The value for the year must be between %1$s and %2$s.', 'torro-forms-plugin-boilerplate' ), $min_year, $max_year ), $parsed_value );
		}

		return $parsed_value;
	}

	/**
	 * Gets the fields arguments for an element of this type when editing submission values in the admin.
	 *
	 * @since 1.0.0
	 *
	 * @param Element $element Element to get fields arguments for.
	 * @return array An associative array of `$field_slug => $field_args` pairs.
	 */
	public function get_edit_submission_fields_args( $element ) {
		$fields = parent::get_edit_submission_fields_args( $element );

		$slug = $this->get_edit_submission_field_slug( $element->id );

		$fields[ $slug ]['type']  = 'datetime';
		$fields[ $slug ]['store'] = 'date';

		return $fields;
	}

	/**
	 * Bootstraps the element type by setting properties.
	 *
	 * @since 1.0.0
	 */
	protected function bootstrap() {
		$this->slug        = 'date';
		$this->title       = __( 'Date', 'torro-forms-plugin-boilerplate' );
		$this->description = __( 'A dropdown-based date field element.', 'torro-forms-plugin-boilerplate' );
		$this->icon_svg_id = 'torro-icon-textfield';

		$this->add_description_settings_field();
		$this->add_required_settings_field();
		$this->settings_fields['min_year'] = array(
			'section'       => 'settings',
			'type'          => 'number',
			'label'         => __( 'Minimum year', 'torro-forms-plugin-boilerplate' ),
			'input_classes' => array( 'small-text' ),
			'default'       => current_time( 'Y' ),
			'min'           => 1,
			'step'          => 1,
		);
		$this->settings_fields['max_year'] = array(
			'section'       => 'settings',
			'type'          => 'number',
			'label'         => __( 'Maximum year', 'torro-forms-plugin-boilerplate' ),
			'input_classes' => array( 'small-text' ),
			'default'       => current_time( 'Y' ),
			'min'           => 1,
			'step'          => 1,
		);
		$this->add_css_classes_settings_field();
	}

	/**
	 * Detects how the three date fields should be ordered, depending on the date format setting.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array containing 'year', 'month' and 'day' keys in their order.
	 */
	protected function detect_date_fields_order() {
		$date_format = get_option( 'date_format' );
		$date_format = str_split( $date_format );

		$year_group  = array( 'Y', 'y' );
		$month_group = array( 'm', 'n', 'F', 'M' );
		$day_group   = array( 'd', 'j' );

		$order = array();
		foreach ( $date_format as $part ) {
			switch ( true ) {
				case in_array( $part, $year_group, true ):
					$order[] = 'year';
					break;
				case in_array( $part, $month_group, true ):
					$order[] = 'month';
					break;
				case in_array( $part, $day_group, true ):
					$order[] = 'day';
					break;
			}
		}

		if ( 3 !== count( $order ) ) {
			return array( 'year', 'month', 'day' ); // Fallback.
		}

		return $order;
	}
}
