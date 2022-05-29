<?php
require_once 'wp-load.php';
//$url = "http://localhost/wp_rest_api/wp-json/wp/v2/posts/id";
$url = "http://localhost/wp_rest_api/wp-json/wp/v2/posts/70";

// Update post
$post_update = wp_remote_post($url, array(
	"headers" => array(
		"Authorization" => "Basic ". base64_encode("wp_rest_api:wp_rest_api@123")
	),
	"body" => array(
		"title" => "Post created using file",
		"content" => "This is updated post",
	)
));
$body_response = json_decode($post_update['body']);
if(!empty($body_response)){
	echo $body_response->title->rendered ." has been updated";
}
?>