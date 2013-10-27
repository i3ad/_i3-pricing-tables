<?php
/*
Plugin Name: _i3-pricing-tables
Plugin URI: -
Description: A plugin to create and display pricing tables for WordPress.
Version: 1.0
Author: Mo
Author URI: -
License: GPL2+
*/

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// Script version, used to add version for scripts and styles
define( 'RWMB_VER', '4.3.4' );

// Define plugin URLs, for fast enqueuing scripts and styles
if ( ! defined( 'RWMB_URL' ) )
	define( 'RWMB_URL', plugin_dir_url( __FILE__ ) );
define( 'RWMB_JS_URL', trailingslashit( RWMB_URL . 'js' ) );
define( 'RWMB_CSS_URL', trailingslashit( RWMB_URL . 'css' ) );

// Plugin paths, for including files
if ( ! defined( 'RWMB_DIR' ) )
	define( 'RWMB_DIR', plugin_dir_path( __FILE__ ) );
define( 'RWMB_INC_DIR', trailingslashit( RWMB_DIR . 'inc' ) );
define( 'RWMB_FIELDS_DIR', trailingslashit( RWMB_INC_DIR . 'fields' ) );

// Optimize code for loading plugin files ONLY on admin side
// @see http://www.deluxeblogtips.com/?p=345

// Helper function to retrieve meta value
require_once RWMB_INC_DIR . 'helpers.php';

if ( is_admin() )
{
	require_once RWMB_INC_DIR . 'common.php';

	// Field classes
	foreach ( glob( RWMB_FIELDS_DIR . '*.php' ) as $file )
	{
		require_once $file;
	}

	// Main file
	require_once RWMB_INC_DIR . 'meta-box.php';
	require_once RWMB_INC_DIR . 'init.php';
}

// Include Fields
include 'demo/demo.php';

// Create CPT
include 'demo/cpt.php';

