<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Torro_Forms_Plugin_Boilerplate_Element_IFrame extends Torro_Form_Element {
	/**
	 * Initializing.
	 *
	 * @since 1.0.0
	 */
	protected function init() {
		parent::init();

		$this->type = $this->name = 'iframe';
		$this->title = __( 'IFrame', 'torro-forms-plugin-boilerplate' );
		$this->description = __( 'Adds an iframe to the form.', 'torro-forms-plugin-boilerplate' );
		$this->icon_url = torro()->get_asset_url( 'icon-text', 'png' );

		$this->input = false;
	}

	public function get_input_html() {
		$html = '<div class="iframe-container">';
		$html .= '<div class="iframe-wrap">';
		$html .= '<iframe src="' . $this->settings['url']->value . '"></iframe>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	public function admin_content_html() {
		$admin_input_name = $this->get_admin_input_name();

		$html = '<label for="' . $admin_input_name . '[label]">' . __( 'Label ', 'torro-forms-plugin-boilerplate' ) . '</label><input type="text" name="' . $admin_input_name . '[label]" value="' . $this->label . '" class="form-label" />';

		$html .= '<div class="iframe-container">';
		$html .= '<div class="iframe-wrap">';
		$html .= '<iframe src="' . $this->settings['url']->value . '"></iframe>';
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

torro()->element_types()->register( 'Torro_Forms_Plugin_Boilerplate_Element_IFrame' );
