<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

final class Torro_Forms_Plugin_Boilerplate_Element_Type_Date extends Torro_Element_Type {
  /**
   * Initializing.
   *
   * @since 1.0.0
   */
  protected function init() {
    $this->name = 'date';
    $this->title = __( 'Date', 'torro-forms-plugin-boilerplate' );
    $this->description = __( 'Add an element to select a date', 'torro-forms-plugin-boilerplate' );
    $this->icon_url = torro()->get_asset_url( 'calendar-icon', 'png' );
  }

  public function to_json( $element ) {
    $data = parent::to_json( $element );

    if ( empty( $element->response ) || ! preg_match( '/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/', $element->response ) ) {
      $element->response = current_time( 'Y-m-d' );
      $data['response'] = $element->response;
    }

    $parts = explode( '-', $data['response'] );

    $data['response_year'] = $parts[0];
    $data['response_month'] = $parts[1];
    $data['response_day'] = $parts[2];

    $data['years'] = $this->get_year_choices();
    $data['months'] = $this->get_month_choices();
    $data['days'] = $this->get_day_choices();

    return $data;
  }

  protected function settings_fields() {
    $this->settings_fields = array(
      'description' => array(
        'title'     => __( 'Description', 'torro-forms' ),
        'type'      => 'textarea',
        'description' => __( 'The description will be shown after the element.', 'torro-forms' ),
        'default'   => ''
      ),
    );
  }

  public function validate( $input, $element ) {
    if ( ! isset( $input['year'] ) || ! isset( $input['month'] ) || ! isset( $input['day'] ) ) {
      return new Torro_Error( 'invalid_input', __( 'The input is missing at least the year, month or day.', 'torro-forms-plugin-boilerplate' ) );
    }

    $year = absint( $input['year'] );
    $month = absint( $input['month'] );
    $day = absint( $input['day'] );

    return zeroise( $year, 4 ) . '-' . zeroise( $month, 2 ) . '-' . zeroise( $day, 2 );
  }

  protected function get_year_choices() {
    $choices = array();
    for ( $i = 1970; $i <= absint( current_time( 'Y' ) ); $i++ ) {
      $choices[] = array(
        'value' => (string) $i,
        'label' => (string) $i,
      );
    }
    return $choices;
  }

  protected function get_month_choices() {
    global $wp_locale;

    $choices = array();
    foreach ( $wp_locale->month as $number => $name ) {
      $choices[] = array(
        'value' => $number,
        'label' => $name,
      );
    }

    return $choices;
  }

  protected function get_day_choices() {
    $choices = array();
    for ( $i = 1; $i <= 31; $i++ ) {
      $choices[] = array(
        'value' => zeroise( $i, 2 ),
        'label' => (string) $i,
      );
    }
    return $choices;
  }
}

torro()->element_types()->register( 'Torro_Forms_Plugin_Boilerplate_Element_Type_Date' );
