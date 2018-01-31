[![Build Status](https://api.travis-ci.org/awsmug/torro-forms-plugin-boilerplate.png?branch=master)](https://travis-ci.org/awsmug/torro-forms-plugin-boilerplate)
[![Test Coverage](https://codeclimate.com/github/awsmug/torro-forms-plugin-boilerplate/badges/coverage.svg)](https://codeclimate.com/github/awsmug/torro-forms-plugin-boilerplate/coverage)
[![Latest Stable Version](https://poser.pugx.org/awsmug/torro-forms-plugin-boilerplate/version)](https://packagist.org/packages/awsmug/torro-forms-plugin-boilerplate)
[![License](https://poser.pugx.org/awsmug/torro-forms-plugin-boilerplate/license)](https://packagist.org/packages/awsmug/torro-forms-plugin-boilerplate)

# Torro Forms Plugin Boilerplate

This is a plugin boilerplate for a Torro Forms extension. It is highly encouraged to use this boilerplate when building any extension for Torro Forms.

## Getting Started

Before you start, you should run some search-and-replace processes to replace the boilerplate plugin name with your actual extension name.

An example: Let's say your plugin is called `Mega Incredible Form Extension`. In this case you would need to replace:

* `Torro Forms Plugin Boilerplate` to `Mega Incredible Form Extension` (plugin name)
* `Torro_Forms_Plugin_Boilerplate` to `Mega_Incredible_Form_Extension` (PHP class names)
* `torro-forms-plugin-boilerplate` to `mega-incredible-form-extension` (textdomain / filenames / directory)
* `torro_forms_plugin_boilerplate` to `mega_incredible_form_extension` (plugin identifier)

What you should do then is adjust the `core/extension.php` file to your needs. This is where your extension will get bootstrapped (use the `includes()` method to include other files/start up some tasks). If you need to enqueue assets, you can do so using the corresponding methods (examples are in the boilerplate).

Everything else in the boilerplate is optional, the element and action contained are just samples (which are not very useful btw). Just bear in mind, you should stick to the directory structure used in the boilerplate - it is oriented after the Torro Forms base plugin.
