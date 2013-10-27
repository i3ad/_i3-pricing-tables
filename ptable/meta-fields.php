<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = '_ptable_';

global $meta_boxes;

$meta_boxes = array();

// 1st meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'ptable_options',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => __( 'Pricing Table Options', 'rwmb' ),

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'ptable' ),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'side',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'low',

	// Auto save: true, false (default). Optional.
	'autosave' => true,

	// List of meta fields
	'fields' => array(

		// Price
		array(
			// Field name - Will be used as label
			'name'  => __( 'Price', 'rwmb' ),
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}price",
			// Field description (optional)
			'desc'  => __( 'Enter the Price here', 'rwmb' ),
			'type'  => 'text',
			// Default value (optional)
			//'std'   => __( '$99.99', 'rwmb' ),
		),

		// Featured-Checkbox
		array(
		'name' => __( 'Add "<em>.featured</em>" Class', 'rwmb' ),
		'id' => "{$prefix}feat_checkbox",
		'type' => 'checkbox',
		// Value can be 0 or 1
		'std' => 0,
		),

		// Divider
		array(
			'type' => 'divider',
			'id'   => 'fake_divider_id', // Not used, but needed
		),

		// Button-Text
		array(
			// Field name - Will be used as label
			'name'  => __( 'Button-Text', 'rwmb' ),
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}button_txt",
			// Field description (optional)
			'desc'  => __( 'Enter Button-text here', 'rwmb' ),
			'type'  => 'text',
			// Default value (optional)
			'std'   => __( 'Buy Now', 'rwmb' ),
		),
		// Button-URL
		array(
			'name'  => __( 'Button-URL', 'rwmb' ),
			'id'    => "{$prefix}button_url",
			'desc'  => __( 'Enter the URL here', 'rwmb' ),
			'type'  => 'url',
			'std'   => 'http://',
		),

		// Heading
		array(
			'type' => 'heading',
			'name' => __( 'Bullet-Items', 'rwmb' ),
			'id'   => 'fake_id', // Not used but needed for plugin
		),

		// Bullet-Item
		array(
			// Field name - Will be used as label
			//'name'  => __( 'Bullet-Item', 'rwmb' ),
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}bullet_item",
			// Field description (optional)
			//'desc'  => __( 'Enter seperate Bullet-Items', 'rwmb' ),
			'type'  => 'text',
			// Default value (optional)
			//'std'   => __( '1 Database', 'rwmb' ),
			// CLONES: Add to make the field cloneable (i.e. have multiple value)
			'clone' => true,
		),

	)
);

/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function ptable_register_meta_boxes()
{
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( !class_exists( 'RW_Meta_Box' ) )
		return;

	global $meta_boxes;
	foreach ( $meta_boxes as $meta_box )
	{
		new RW_Meta_Box( $meta_box );
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'ptable_register_meta_boxes' );
