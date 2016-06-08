<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Torro_Forms_Plugin_Boilerplate_Element_Type_IFrame extends Torro_Element_Type {
	/**
	 * Initializing.
	 *
	 * @since 1.0.0
	 */
	protected function init() {
		$this->name = 'iframe';
		$this->title = __( 'IFrame', 'torro-forms-plugin-boilerplate' );
		$this->description = __( 'Adds an iframe to the form.', 'torro-forms-plugin-boilerplate' );
		$this->icon_url = torro()->get_asset_url( 'icon-text', 'png' );

		$this->input = false;
	}

	public function to_json( $element ) {
		$data = parent::to_json( $element );
		$data['iframe_src'] = $element->settings['url']->value;

		return $data;
	}

	public function admin_content_html( $element ) {
		$admin_input_name = $this->get_admin_input_name( $element );
		$iframe_src = isset( $element->settings['url'] ) ? $element->settings['url']->value : '';

		$html = '<label for="' . $admin_input_name . '[label]">' . __( 'Label ', 'torro-forms-plugin-boilerplate' ) . '</label><input type="text" name="' . $admin_input_name . '[label]" value="' . $element->label . '" class="form-label" />';

		$html .= '<div class="iframe-container">';
		$html .= '<div class="iframe-wrap">';
		$html .= '<iframe src="' . $iframe_src . '"></iframe>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	protected function settings_fields() {
		$this->settings_fields = array(
			'url'		=> array(
				'title'			=> __( 'URL', 'torro-forms-plugin-boilerplate' ),
				'type'			=> 'text',
				'description'	=> __( 'The URL that the iframe should display.', 'torro-forms-plugin-boilerplate' ),
				'default'		=> 'http://torro-forms.com',
			),
		);
	}
}

torro()->element_types()->register( 'Torro_Forms_Plugin_Boilerplate_Element_Type_IFrame' );
