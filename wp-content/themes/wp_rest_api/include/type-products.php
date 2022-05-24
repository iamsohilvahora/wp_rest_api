<?php
	// REGISTER CUSTOM POST TYPES
	if ( ! function_exists( 'create_post_type' ) ){
		function create_post_type() {
		  $labels = array(
		    'name'                  => 'Products',
		    'singular_name'         => 'Product',
		    'menu_name'             => 'Product',
		    'name_admin_bar'        => 'Products',
		    'archives'              => 'Item Archives',
		    'attributes'            => 'Item Attributes',
		    'parent_item_colon'     => 'Parent Item',
		    'all_items'             => 'All Product',
		    'add_new_item'          => 'Add New Product',
		    'add_new'               => 'Add New Product',
		    'new_item'              => 'New Item',
		    'edit_item'             => 'Edit Item',
		    'update_item'           => 'Update Item',
		    'view_item'             => 'View Item',
		    'view_items'            => 'View Items',
		    'search_items'          => 'Search Item',
		    'not_found'             => 'Not found',
		    'not_found_in_trash'    => 'Not found in Trash',
		    'featured_image'        => 'Featured Image',
		    'set_featured_image'    => 'Set featured image',
		    'remove_featured_image' => 'Remove featured image',
		    'use_featured_image'    => 'Use as featured image',
		    'insert_into_item'      => 'Insert into item',
		    'uploaded_to_this_item' => 'Uploaded to this item',
		    'items_list'            => 'Items list',
		    'items_list_navigation' => 'Items list navigation',
		    'filter_items_list'     => 'Filter items list',
		  );
		  $args = array(
		    'label'                 => 'Products',
		    'description'           => 'Products Description',
		    'labels'                => $labels,
		    'supports'              => array( 'title','page template', 'custom-fields', 'page-attributes', 'thumbnail','editor','excerpt','slug'),
		    'hierarchical'          => true,
		    'public'                => true,
		    'show_ui'               => true,
		    'show_in_menu'          => true,
		    'menu_position'         => 75,
		    'menu_icon' => "dashicons-format-video",
		    'rewrite' => array ( 'slug' => __( 'product' ) ), // change the name
		    'show_in_admin_bar'     => true,
		    'show_in_nav_menus'     => true,
		    'can_export'            => true,
		    'has_archive'           => true,
		    //'taxonomies' => array( 'category', 'post_tag' ), // do you need categories and tags?
		    'exclude_from_search'   => true,
		    'publicly_queryable'    => true,
		    'capability_type'       => 'post',
		  );
		  register_post_type( 'products', $args );
		  }
	}

//set custom post types
add_action( 'init', 'create_post_type' );


//Register Custom Taxonomy
function custom_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Products_Cats', 'taxonomy general name' ),
		'singular_name'              => _x( 'Products_Cat', 'taxonomy singular name' ),
		'menu_name'                  => __( 'Products_Cat'),
		'all_items'                  => __( 'All Products', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Products', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'products_categories', array('products'), $args );

}
add_action( 'init', 'custom_taxonomy', 0 );


?>
