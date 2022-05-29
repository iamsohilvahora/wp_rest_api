<?php
require_once 'wp-load.php';
//$url = "http://localhost/wp_rest_api/wp-json/wp/v2/posts/id";
$url = "http://localhost/wp_rest_api/wp-json/wp/v2/posts/65/?force=true";

// Delete post
$post_delete = wp_remote_post($url, array(
	"method"=> "DELETE",
	"headers" => array(
		"Authorization" => "Basic ". base64_encode("wp_rest_api:wp_rest_api@123")
	),
));
$body_response = json_decode($post_delete['body']);
if(!empty($body_response)){
	echo "Post has been deleted";
}
?>