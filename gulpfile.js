/* ---- THE FOLLOWING CONFIG SHOULD BE EDITED ---- */

var pkg = require( './package.json' );

function parseKeywords( keywords ) {
	// These keywords are useful for Packagist/NPM/Bower, but not for the WordPress plugin repository.
	const disallowed = [ 'wordpress', 'plugin' ];

	return keywords.filter( keyword => ! disallowed.includes( keyword ) );
}

var config = {
	pluginSlug: 'torro-forms-plugin-boilerplate',
	pluginName: 'Torro Forms Plugin Boilerplate',
	pluginURI: pkg.homepage,
	author: pkg.author.name,
	authorURI: pkg.author.url,
	description: pkg.description,
	version: pkg.version,
	license: 'GNU General Public License v2 (or later)',
	licenseURI: 'http://www.gnu.org/licenses/gpl-2.0.html',
	tags: parseKeywords( pkg.keywords ).join( ', ' ),
	contributors: [ 'mahype', 'flixos90', 'awesome-ug' ].join( ', ' ),
	donateLink: false,
	minRequired: '4.8',
	testedUpTo: '4.9',
	requiresPHP: '5.6',
	translateURI: 'https://translate.wordpress.org/projects/wp-plugins/torro-forms',
	network: false
};

/* ---- DO NOT EDIT BELOW THIS LINE ---- */

// WP plugin header for main plugin file
var pluginheader = 	'Plugin Name: ' + config.pluginName + '\n' +
					'Plugin URI:  ' + config.pluginURI + '\n' +
					'Description: ' + config.description + '\n' +
					'Version:     ' + config.version + '\n' +
					'Author:      ' + config.author + '\n' +
					'Author URI:  ' + config.authorURI + '\n' +
					'License:     ' + config.license + '\n' +
					'License URI: ' + config.licenseURI + '\n' +
					'Text Domain: ' + config.pluginSlug + '\n' +
					( config.network ? ' * Network:     true' + '\n' : '' ) +
					'Tags:        ' + config.tags;

// WP plugin header for readme.txt
var readmeheader =	'Plugin Name:       ' + config.pluginName + '\n' +
					'Plugin URI:        ' + config.pluginURI + '\n' +
					'Author:            ' + config.author + '\n' +
					'Author URI:        ' + config.authorURI + '\n' +
					'Contributors:      ' + config.contributors + '\n' +
					( config.donateLink ? 'Donate link:       ' + config.donateLink + '\n' : '' ) +
					'Requires at least: ' + config.minRequired + '\n' +
					'Tested up to:      ' + config.testedUpTo + '\n' +
					( config.requiresPHP ? 'Requires PHP:      ' + config.requiresPHP + '\n' : '' ) +
					'Stable tag:        ' + config.version + '\n' +
					'Version:           ' + config.version + '\n' +
					'License:           ' + config.license + '\n' +
					'License URI:       ' + config.licenseURI + '\n' +
					'Tags:              ' + config.tags;

// header for minified assets
var assetheader =	'/*!\n' +
					' * ' + config.pluginName + ' Version ' + config.version + ' (' + config.pluginURI + ')\n' +
					' * Licensed under ' + config.license + ' (' + config.licenseURI + ')\n' +
					' */\n';


/* ---- REQUIRED DEPENDENCIES ---- */

const gulp = require( 'gulp' );

const rename = require( 'gulp-rename' );
const replace = require( 'gulp-replace' );
const banner = require( 'gulp-banner' );
const sass = require( 'gulp-sass' );
const csscomb = require( 'gulp-csscomb' );
const cleanCss = require( 'gulp-clean-css' );
const jshint = require( 'gulp-jshint' );
const jscs = require( 'gulp-jscs' );
const concat = require( 'gulp-concat' );
const uglify = require( 'gulp-uglify' );

const paths = {
	php: {
		files: [ './*.php', './src/**/*.php', './templates/**/*.php' ]
	},
	sass: {
		files: [ './assets/src/sass/*.scss' ],
		src: './assets/src/sass/',
		dst: './assets/dist/css/'
	},
	js: {
		files: [ './assets/src/js/*.js' ],
		src: './assets/src/js/',
		dst: './assets/dist/js/'
	}
};

/* ---- MAIN TASKS ---- */

// general task (compile Sass and JavaScript and refresh POT file)
gulp.task( 'default', [ 'sass', 'js' ]);

// watch Sass and JavaScript files
gulp.task( 'watch', () => {
	gulp.watch( paths.sass.files, [ 'sass' ]);
	gulp.watch( paths.js.files, [ 'js' ]);
});

// build the plugin
gulp.task( 'build', [ 'readme-replace' ], () => {
	gulp.start( 'header-replace' );
	gulp.start( 'default' );
});

/* ---- SUB TASKS ---- */

// compile Sass
gulp.task( 'sass', done => {
	gulp.src( paths.sass.files )
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'expanded'
		}) )
		.pipe( csscomb() )
		.pipe( banner( assetheader ) )
		.pipe( gulp.dest( paths.sass.dst ) )
		.pipe( cleanCss({
			keepSpecialComments: 0
		}) )
		.pipe( banner( assetheader ) )
		.pipe( rename({
			extname: '.min.css'
		}) )
		.pipe( gulp.dest( paths.sass.dst ) )
		.on( 'end', done );
});

// compile JavaScript
gulp.task( 'js', done => {
	gulp.src( paths.js.files )
		.pipe( jshint() )
		.pipe( jshint.reporter( 'default' ) )
		.pipe( jscs() )
		.pipe( jscs.reporter() )
		.pipe( banner( assetheader ) )
		.pipe( gulp.dest( paths.js.dst ) )
		.pipe( uglify() )
		.pipe( banner( assetheader ) )
		.pipe( rename({
			extname: '.min.js'
		}) )
		.pipe( gulp.dest( paths.js.dst ) )
		.on( 'end', done );
});

// replace the plugin header in the main plugin file
gulp.task( 'header-replace', done => {
	gulp.src( './' + config.pluginSlug + '.php' )
		.pipe( replace( /(?:\s\*\s@wordpress-plugin\s(?:[^*]|(?:\*+[^*\/]))*\*+\/)/, ' * @wordpress-plugin\n' + pluginheader + '\n */' ) )
		.pipe( gulp.dest( './' ) )
		.on( 'end', done );
});

// replace the plugin header in readme.txt
gulp.task( 'readme-replace', done => {
	gulp.src( './readme.txt' )
		.pipe( replace( /\=\=\= (.+) \=\=\=([\s\S]+)\=\= Description \=\=/m, '=== ' + config.pluginName + ' ===\n\n' + readmeheader + '\n\n' + config.description + '\n\n== Description ==' ) )
		.pipe( gulp.dest( './' ) )
		.on( 'end', done );
});
