<?php
/**
 * Plugin Name: Custom Post Type API
 * Plugin URI: http://chrushingit.com
 * Description: Crushing it!
 * Version: 1.0
 * Author: Art Vandelay
 * Author URI: http://watch-learn.com
 */

function wcp_posts(){
	$args = [
		'numberposts' => 99999,
		'post_type' => 'post'
	];

	$posts = get_posts($args);

	$data = [];
	$i = 0;

	foreach($posts as $post){
		$data[$i]['id'] = $post->ID;
		$data[$i]['title'] = $post->post_title;
		$data[$i]['content'] = $post->post_content;
		$data[$i]['slug'] = $post->post_name;
		$data[$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'thumbnail');
		$data[$i]['featured_image']['medium'] = get_the_post_thumbnail_url($post->ID, 'medium');
		$data[$i]['featured_image']['large'] = get_the_post_thumbnail_url($post->ID, 'large');
		$i++;
	}
	return $data;
}

function wcp_post( $slug ) {
	$args = [
		'name' => $slug['slug'],
		'post_type' => 'post'
	];
	$post = get_posts($args);
	$data['id'] = $post[0]->ID;
	$data['title'] = $post[0]->post_title;
	$data['content'] = $post[0]->post_content;
	$data['slug'] = $post[0]->post_name;
	$data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail');
	$data['featured_image']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
	$data['featured_image']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large');
	return $data;
}

http://localhost/wp_rest_api/wcp/v1/products?price={"gt":20, "lt":300}
function wcp_products($params) {
	$price = json_decode($params->get_param('price'));

    function queryArgument($param, $key){
        if(is_object($param)){
            if($param->lt && $param->gt){
                return [
                    [
                        'key' => $key,
                        'value' => [$param->gt, $param->lt],
                        'type'  => 'NUMERIC',
                        'compare' => 'BETWEEN'
                    ]
                ];
            }

            if($param->lt) {
                return [
                    [
                        'key' => $key,
                        'value' => $param->lt,
                        'type'  => 'NUMERIC',
                        'compare' => '<'
                    ]
                ];
            }

            if($param->gt) {
                return [
                    [
                        'key' => $key,
                        'value' => $param->gt,
                        'type'  => 'NUMERIC',
                        'compare' => '>'
                    ]
                ];
            }
        }


        if($param) {
            return [
                [
                    'key' => $key,
                    'value' => $param,
                    'type'  => 'NUMERIC'
                ]
            ];
        }

        return null;
    }

	$args = [
		'posts_per_page' => 99999,
        'post_type' => 'products',
        'meta_query' => queryArgument($price, 'price')
	];

	 $posts = new WP_Query($args);

	// $args = [
	// 	'numberposts' => 99999,
	// 	'post_type' => 'products'
	// ];

	// $posts = get_posts($args);

	$data = [];
	$i = 0;

	// foreach($posts as $post) {
	// 	$data[$i]['id'] = $post->ID;
	// 	$data[$i]['title'] = $post->post_title;
 //        $data[$i]['slug'] = $post->post_name;
 //        $data[$i]['price'] = intval(get_field('price', $post->ID));
 //        $data[$i]['delivery'] = get_field('delivery', $post->ID);
	// 	$i++;
	// }

	foreach($posts->posts as $post) {
		$data[$i]['id'] = $post->ID;
		$data[$i]['title'] = $post->post_title;
        $data[$i]['slug'] = $post->post_name;
        $data[$i]['price'] = intval(get_field('price', $post->ID));
        $data[$i]['delivery'] = get_field('delivery', $post->ID);
		$i++;
	}

	return $data;
}

add_action('rest_api_init', function() {
	register_rest_route('wcp/v1', 'posts', [
		'methods' => 'GET',
		'callback' => 'wcp_posts',
	]);

	register_rest_route('wcp/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', array(
		'methods' => 'GET',
		'callback' => 'wcp_post',
    ));
    
    register_rest_route('wcp/v1', 'products', [
		'methods' => 'GET',
		'callback' => 'wcp_products',
	]);
});