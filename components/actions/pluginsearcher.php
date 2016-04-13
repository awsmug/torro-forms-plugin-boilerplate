<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Torro_Forms_Plugin_Boilerplate_Pluginsearcher extends Torro_Action {
	/**
	 * Instance
	 *
	 * @var null|Torro_Forms_Plugin_Boilerplate_Pluginsearcher
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Singleton
	 *
	 * @return Torro_Forms_Plugin_Boilerplate_Pluginsearcher
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
	}

	/**
	 * Initializing
	 *
	 * @since 1.0.0
	 */
	protected function init() {
		$this->title = __( 'WP.org Plugin Search Redirection', 'torro-forms-plugin-boilerplate' );
		$this->name = 'pluginsearcher';
	}

	public function handle( $form_id, $response_id, $response ) {
		$field_to_search = get_post_meta( $form_id, 'field_to_search_plugin', true );

		$elements = torro()->elements()->query( array(
			'number'	=> 1,
			'form_id'	=> $form_id,
			'label'		=> $field_to_search,
		) );
		if ( ! $elements ) {
			return;
		}

		$element_id = $elements[0]->id;
		$field_value = false;
		foreach ( $response['containers'] as $container_id => $data ) {
			if ( ! isset( $data['elements'] ) ) {
				continue;
			}
			if ( isset( $data['elements'][ $element_id ] ) ) {
				$field_value = $data['elements'][ $element_id ];
				break;
			}
		}

		if ( ! $field_value ) {
			return;
		}

		wp_redirect( add_query_arg( 'q', $field_value, 'https://wordpress.org/plugins/search.php' ) );
		exit;
	}

	public function option_content() {
		global $post;

		$form_id = $post->ID;

		$field_to_search = get_post_meta( $form_id, 'field_to_search_plugin', true );

		$html = '<div id="form-pluginsearcher">';
		$html .= '<table class="form-table">';
		$html .= '<tr>';
		$html .= '<td>';
		$html .= '<label for="field_to_search_plugin">' . esc_attr__( 'Field to search for', 'torro-forms-plugin-boilerplate' ) . '</label>';
		$html .= '</td>';
		$html .= '<td>';
		$html .= '<input type="text" name="field_to_search_plugin" value="' . $field_to_search . '" />';
		$html .= '<small>' . __( 'Redirect the user to wp.org to search for a plugin named after the field content.', 'torro-forms-plugin-boilerplate' ) . '</small>';
		$html .= '</td>';
		$html .= '</tr>';

		$html .= '</table>';
		$html .= '</div>';

		return $html;
	}

	public function save_option_content() {
		global $post;

		$field_to_search = $_POST['field_to_search_plugin'];
		update_post_meta( $post->ID, 'field_to_search_plugin', $field_to_search );
	}
}

torro()->actions()->register( 'Torro_Forms_Plugin_Boilerplate_Pluginsearcher' );
