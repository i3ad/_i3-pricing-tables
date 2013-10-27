<?php

/* ==========================================================================
   $REGISTER THE POST-TYPE
   ========================================================================== */

if ( ! function_exists('ptable_post_type') ) {

// Register Custom Post Type
function ptable_post_type() {

	$labels = array(
		'name'                => _x( 'Tables', 'Post Type General Name', 'mo_ptable' ),
		'singular_name'       => _x( 'Table', 'Post Type Singular Name', 'mo_ptable' ),
		'menu_name'           => __( 'Pricing Tables', 'mo_ptable' ),
		'parent_item_colon'   => __( 'Parent Table', 'mo_ptable' ),
		'all_items'           => __( 'All Tables', 'mo_ptable' ),
		'view_item'           => __( 'View Table', 'mo_ptable' ),
		'add_new_item'        => __( 'Add New Table', 'mo_ptable' ),
		'add_new'             => __( 'New Table', 'mo_ptable' ),
		'edit_item'           => __( 'Edit Table', 'mo_ptable' ),
		'update_item'         => __( 'Update Table', 'mo_ptable' ),
		'search_items'        => __( 'Search Tables', 'mo_ptable' ),
		'not_found'           => __( 'No Tables found', 'mo_ptable' ),
		'not_found_in_trash'  => __( 'No Tables found in Trash', 'mo_ptable' ),
	);
	$args = array(
		'label'               => __( 'ptable', 'mo_ptable' ),
		'description'         => __( 'Product information pages', 'mo_ptable' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 80,
		'menu_icon'           => plugin_dir_url( __FILE__ ) . 'img/ptable-icon.png',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'ptable', $args );

}

// Hook into the 'init' action
add_action( 'init', 'ptable_post_type', 0 );

}

/* ==========================================================================
   $BUILD THE SHORTCODE
   ========================================================================== 

	Usage:
		[pricing-table tables="1732, 1752, 1749"]

		"tables"		= Comma separated list of table-post-ID´s

*/
function ptable_shortcode( $atts , $content = null ) {

/* ATTRIBUTES
   ========================================================================== */
	extract( shortcode_atts(
		array(
			'tables' 		=> '', // post ID´s
		), $atts )

	);


/* THE LOOP
   ========================================================================== */
	$output = '<div class="pricing-table-wrapper columns' . count( explode(',', $tables) ) . ' clearfix">';
	$the_query = new WP_Query( array ( 
			'post_type'			=> 'ptable', 			// only select ptable post_type
			'post__in' 			=> explode(',', $tables), 	// explode comma separated list
			'orderby' 			=> 'post__in', 			// sort by post__in order
	) );

	while ( $the_query->have_posts() ):
		$the_query->the_post();

		$id = get_the_ID(); // get post id to pass to class

		// Check if "featured" checkbox is checked
		$featured = rwmb_meta( '_ptable_feat_checkbox', 'type=checkbox' );
		if( ! empty( $featured ) ) { // if its checked add the class .featured
			$output .= '<ul class="pricing-table ptable-'. $id .' featured">';
		} else { 
			$output .= '<ul class="pricing-table ptable-'. $id .'">';
		}
		
		// Display the title
		$output .= '<li class="title">' . get_the_title() . '</li>';

		// Only show .price li if price is entered
		$price = rwmb_meta( '_ptable_price');
		if( ! empty( $price ) ) {
			$output .= '<li class="price">' . rwmb_meta( "_ptable_price" ) . '</li>';
		}

		// Only show the_content when content is entered
		$thecontent = get_the_content();
		if( ! empty($thecontent)) {
			$output .= '<li class="description">' . get_the_content() . '</li>';
		}

		$items = rwmb_meta( '_ptable_bullet_item');
    		foreach ( $items as $item )
   			{
    			$output .= '<li class="bullet-item">' . $item . '</li>';
    		}
		
		//Only show the button if there is button-text available
		$txt = rwmb_meta( '_ptable_button_txt');
		if( ! empty( $txt ) ) {
			$output .= '<li class="button"><a class="btn" href="' . rwmb_meta( "_ptable_button_url" ) . '" >' . rwmb_meta( "_ptable_button_txt" ) . '</a></li>';
		}

		$output .= '</ul>';

	endwhile;
	wp_reset_postdata();
	$output .= var_dump($the_query->request);
	$output .= '</div>';
	return $output;

}
add_shortcode( 'pricing-table', 'ptable_shortcode' );



/* ==========================================================================
   $INCLUDE STYLESHEETS
   ========================================================================== */

function ptable_styles() {
        wp_enqueue_style( 'ptable-style', plugin_dir_url( __FILE__ ) . 'style.css', array(), '0.1', 'screen' );
        // Only enqueue custom-style if file is there
        if ( is_readable( plugin_dir_path( __FILE__ ) . 'custom-style.css' ) ) {
            wp_enqueue_style( 'ptable-custom-style', plugin_dir_url( __FILE__ ) . 'custom-style.css', array(), '0.1', 'screen' );
        }
}
add_action( 'wp_enqueue_scripts', 'ptable_styles' );



/* ==========================================================================
   $ADMIN COLUMNS
   ========================================================================== */

/* ADD NEW ADMIN COLUMNS
   ========================================================================== */
function i3_columns_head2($defaults) { 
	$defaults = array(
		'cb' 					=> '<input type="checkbox" />',
		'item_id' 				=> __( 'Table ID', '_i3-base' ),
		'title' 				=> __( 'Title', '_i3-base' ),
		'price' 				=> __( 'Price', '_i3-base' ),
		'featured' 				=> __( 'Featured', '_i3-base' ),
		'date' 					=> __( 'Date', '_i3-base' )
	);
    return $defaults;  
} 

/* DEFINE CONTENT OF NEW ADMIN COLUMNS
   ========================================================================== */ 
function i3_columns_content2($column_name, $post_ID) {  
    if ($column_name == 'item_id') { 
    	 the_ID();
    }
    if ($column_name == 'price') { 
    	$price = get_post_meta( get_the_ID(), '_ptable_price', true );
		echo $price;
    }
     if ($column_name == 'featured') { 
		$featured = get_post_meta( get_the_ID(), '_ptable_feat_checkbox', true );
		// check if the custom field has a value
		if( ! empty( $featured ) ) {
		  echo '<span style="font-size:43px;">&bull;</span>';
		} 
    }
}    
add_filter('manage_ptable_posts_columns', 'i3_columns_head2');
add_action('manage_ptable_posts_custom_column', 'i3_columns_content2', 10, 2);

/* STYLE NEW ADMIN COLUMNS
   ========================================================================== */ 
function i3_columns_style2() {
    echo '<style type="text/css">';
    echo ' { width: 5em; text-align:center; }';
    echo '.column-price { width: 5em }';
    echo '.column-featured, th.column-featured, .column-item_id, th.column-item_id{ width: 5em; text-align:center; }';
    echo '</style>';
}
add_action('admin_head', 'i3_columns_style2');
