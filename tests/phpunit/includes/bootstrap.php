<?php
/**
 * @package TorroFormsPluginBoilerplate
 * @subpackage Tests
 */

$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array(
		'torro-forms/torro-forms.php',
		'torro-forms-plugin-boilerplate/torro-forms-plugin-boilerplate.php',
	),
);

require '../../../../torro-forms/tests/phpunit/includes/bootstrap.php';

function _manually_load_extension() {
	require '../../../torro-forms-plugin-boilerplate.php';
}

if ( defined( 'TORRO_MANUAL_LOAD' ) && TORRO_MANUAL_LOAD ) {
	tests_add_filter( 'muplugins_loaded', '_manually_load_extension' );
}

echo "Installing Torro Forms Plugin Boilerplate...\n";

activate_plugin( 'torro-forms-plugin-boilerplate/torro-forms-plugin-boilerplate.php' );
