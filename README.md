[![Build Status](https://api.travis-ci.org/awsmug/torro-forms-plugin-boilerplate.png?branch=master)](https://travis-ci.org/awsmug/torro-forms-plugin-boilerplate)
[![Code Climate](https://codeclimate.com/github/awsmug/torro-forms-plugin-boilerplate/badges/gpa.svg)](https://codeclimate.com/github/awsmug/torro-forms-plugin-boilerplate)
[![Test Coverage](https://codeclimate.com/github/awsmug/torro-forms-plugin-boilerplate/badges/coverage.svg)](https://codeclimate.com/github/awsmug/torro-forms-plugin-boilerplate/coverage)
[![Latest Stable Version](https://poser.pugx.org/awsmug/torro-forms-plugin-boilerplate/version)](https://packagist.org/packages/awsmug/torro-forms-plugin-boilerplate)
[![License](https://poser.pugx.org/awsmug/torro-forms-plugin-boilerplate/license)](https://packagist.org/packages/awsmug/torro-forms-plugin-boilerplate)

# Torro Forms Plugin Boilerplate

This is a plugin boilerplate for a Torro Forms extension. It is highly encouraged to use this boilerplate when building any extension for Torro Forms.

## Getting Started

To create your own plugin, download this repository. For the next steps, let's assume your plugin should be called `Torro Super Extension`.

1. Rename the directory to `torro-super-extension`.
2. Rename the plugin main file to `torro-super-extension.php`.
2. Open `gulpfile.js` and scroll to the bottom.
3. Replace every value in the `replacements` object with your new plugin name or plugin author data in the appropriate format. For example, replace `my-new-plugin-name` with `torro-super-extension`, `MY_NEW_PLUGIN_NAME` with `TORRO_SUPER_EXTENSION` and so on. Replace the plugin namespace vendor, plugin URL, author name, author email and author URL with your respective data.
4. Save the changes.
5. Run `npm install` in the console.
6. Run `gulp init-replace` in the console.
7. Open `gulpfile.js` again and remove the entire bottom section that starts with `INITIAL SETUP TASK`, save the file afterwards.
8. Check the `composer.json` and `package.json` files. You might wanna update some details to your preferences.
9. Check the top of `gulpfile.js`, containing the `config` object. You might wanna update some details to your preferences.
10. Run `gulp build` once to compile everything.

Now you're good to go! One more thing: If you want to publish the plugin on wordpress.org, it's recommended to remove the `/languages` directory, plus set the `config.domainPath` to `false` and remove the `pot` task in `gulpfile.js`. Then, remove the now unnecessary arguments from the `Extension::load_textdomain()` method accordingly.

### Actual Development

Adjust the `src/extension.php` file to your needs. This is where your extension will get bootstrapped. You can instantiate the services your extension needs here, setup hooks (which will then be automatically invoked by the Torro Forms main plugin) and more.

All further classes and assets in the boilerplate is optional and simply sample code. Just bear in mind, you should stick to the directory structure used in the boilerplate - it is oriented after the Torro Forms base plugin.

## Common Gulp Tasks

* `gulp sass`: Compiles CSS/Sass
* `gulp js`: Compiles JavaScript
* `gulp pot`: Refreshes POT file
* `gulp header-replace`: Replaces the plugin header with latest data
* `gulp readme-replace`: Replaces the header and description in the readme with latest data
* `gulp build`: Runs all of the above tasks
