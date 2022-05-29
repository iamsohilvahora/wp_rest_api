<?php
require_once 'wp-load.php';
$url = "http://localhost/wp_rest_api/wp-json/wp/v2/posts";

// Create post
$post_create = wp_remote_post($url, array(
	"headers" => array(
		"Authorization" => "Basic ". base64_encode("wp_rest_api:wp_rest_api@123")
	),
	"body" => array(
		"title" => "Post created using file",
		"content" => "dffkndsnfdsf",
		"status" => "publish"
	)
));
$body_response = json_decode($post_create['body']);
if(!empty($body_response)){
	echo "The post with the title ". $body_response->title->rendered ." has been created";
}
?>