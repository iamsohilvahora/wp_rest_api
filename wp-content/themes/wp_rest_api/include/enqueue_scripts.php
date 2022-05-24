<?php
/**
 * Enqueue scripts and styles.
 */
function wordpress_rest_api_scripts() {
	wp_enqueue_style( 'wordpress-rest-api-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'wordpress-rest-api-style', 'rtl', 'replace' );

	wp_enqueue_script( 'wordpress-rest-api-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', array(), _S_VERSION, true );

	wp_localize_script('custom-js', 'magicalData', array(
		'nonce' => wp_create_nonce('wp_rest'),
		'siteURL' => get_site_url()
	));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wordpress_rest_api_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wordpress_rest_api_content_width', 640 );
}
