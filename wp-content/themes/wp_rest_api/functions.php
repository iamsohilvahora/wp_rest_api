<?php
//Theme setup and widgets , custom option pages 
require get_template_directory().'/include/theme_setup.php'; 

//Enqueue Scripts and style , js 
require get_template_directory().'/include/enqueue_scripts.php'; 

//General Hooks , action and function used in theme globally 
require get_template_directory().'/include/general_function.php';

//custom image sizes
require get_template_directory().'/include/custom_image_size.php';

//Products CPT
require get_template_directory().'/include/type-products.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

add_action( 'after_setup_theme', 'wordpress_rest_api_setup' );
add_action( 'after_setup_theme', 'wordpress_rest_api_content_width', 0 );
add_action( 'widgets_init', 'wordpress_rest_api_widgets_init' );
add_action( 'wp_enqueue_scripts', 'wordpress_rest_api_scripts' );

add_filter('upload_mimes', 'cc_mime_types');
add_filter( 'admin_post_thumbnail_html', 'add_featured_image_instruction');

add_action('admin_init', 'df_disable_comments_post_types_support');
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);
add_action('admin_menu', 'df_disable_comments_admin_menu');
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');
add_action('admin_init', 'df_disable_comments_dashboard');
add_action('init', 'df_disable_comments_admin_bar');

// Rest API
function wp_register_rest_route_func(){
    register_rest_route('wp/v3', '/userlist', array(
        // 'methods' => 'GET, POST, PUT, DELETE, PATCH', 
        // 'methods' => WP_REST_Server::READABLE, // only get method 
        'methods' => WP_REST_Server::CREATABLE, // only post method 
        // 'methods' => WP_REST_Server::EDITABLE, // only post, put, patch method 
        // 'methods' => WP_REST_Server::DELETABLE, // only delete method 
        // 'methods' => WP_REST_Server::ALLMETHODS, // only delete method 
        'callback' => 'wp_display_user_data_func',
        'args' => array(
            'name' => array(
                'type' => 'string',
                'required' => true, 
                'validate_callback' => function($param){
                    if(strlen($param)> 10){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            ),
            'email' => array(
                'type' => 'string',
                'required' => true, 
                'validate_callback' => function($param){
                    if(filter_var($param, FILTER_VALIDATE_EMAIL)){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            ),
            'age' => array(
                'type' => 'integer',
                'required' => true, 
                'validate_callback' => function($param){
                    if($param > -1){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            ),
        ),
    ));
}
function wp_display_user_data_func(WP_REST_Request $request){
    // global $wpdb;
    // $users_list = $wpdb->get_results(
    //     $wpdb->prepare("SELECT * FROM ".$wpdb->prefix. "users ORDER BY ID DESC", ""), ARRAY_A);
    // return $users_list;
    $request_type = $_SERVER['REQUEST_METHOD'];
    if($request_type == "GET"){
        return array('status'=>1, 'method'=>'get');
    }
    else if($request_type == "POST"){
        $parameters = array(
            'user_name'=> $request->get_param('name'),
            'user_email'=> $request->get_param('email'),
            'user_age'=> $request->get_param('age'),
        );
        return array('status'=>1, 'method'=>'post', 'parameter' => $parameters);
    }
    else if($request_type == "DELETE"){
        return array('status'=>1, 'method'=>'delete');
    }
    else if($request_type == "PATCH"){
        return array('status'=>1, 'method'=>'patch');
    }
    else if($request_type == "PUT"){
        return array('status'=>1, 'method'=>'put');
    }
}
// add_action('rest_api_init', 'wp_register_rest_route_func');

function wp_list_post_func(){
    register_rest_route('wp/v3', '/postlist', array( 
        'methods' => WP_REST_Server::READABLE, // only get method          
        'callback' => 'wp_display_post_data_func',
    ));

    register_rest_route('wp/v3', '/create-post', array(
        'methods' => WP_REST_Server::CREATABLE, // only post method
        'callback' => 'wp_create_post_data_func',
        'args' => array(
            'title' => array(
                'type' => 'string',
                'required' => true, 
                'validate_callback' => function($param){
                    if(empty($param)){
                        return false;
                    }
                    else{
                        return true;
                    }
                }
            ),
            'content' => array(
                'type' => 'string',
                'required' => true, 
                'validate_callback' => function($param){
                    if(empty($param)){
                        return false;
                    }
                    else{
                        return true;
                    }
                }
            ),
        ),
    ));

    register_rest_route('wp/v3', '/update-post/(?P<id>[\d]+)', array(
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'wp_update_post_data_func',
        'args' => array(
            'title' => array(
                'type' => 'string',
                'required' => true,
            ),
            'content' => array(
                'type' => 'string',
                'required' => true,
            ),
        ),
    ));

    register_rest_route('wp/v3', '/delete-post/(?P<id>[\d]+)', array(
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'wp_delete_post_data_func',
    ));
}
function wp_display_post_data_func(){
    global $wpdb;
    $post_list = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM ".$wpdb->prefix. "posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY ID DESC", ""), ARRAY_A);
    return $post_list;
}
function wp_create_post_data_func(WP_REST_Request $request){
    $parameters = $request->get_params();
    $post_id = wp_insert_post(array(
        'post_title' => $parameters['title'],
        'post_content' => $parameters['content'],
        'post_status' => 'publish',
    ));
    if($post_id > 0){
        return json_encode(array('status' => true, 'messeage' => 'Post has been created.'));
    }
    else{
        return json_encode(array('status' => false, 'messeage' => 'Failed to create WP Post.'));
    }
}
function wp_update_post_data_func(WP_REST_Request $request){
    $updated_id = $request->get_param('id');
    $updated_title = $request->get_param('title');
    $updated_content = $request->get_param('content');
    $update_post = wp_update_post(array(
        'ID' => $updated_id,
        'post_title' => $updated_title,
        'post_content' => $updated_content,
    ));
    if($update_post > 0){
        return json_encode(array('status' => true, 'messeage' => 'Post has been updated.'));
    }
}
function wp_delete_post_data_func(WP_REST_Request $request){
    $deleted_id = $request->get_param('id');
    $deleted_post = wp_delete_post($deleted_id);
    if($deleted_post > 0){
        return json_encode(array('status' => true, 'messeage' => 'Post has been deleted.'));
    }
}
add_action('rest_api_init', 'wp_list_post_func');