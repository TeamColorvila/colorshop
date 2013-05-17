<?php

	$load_path = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '\wp-load.php';
	
	//require( dirname(__FILE__) . '/wp-load.php' );
	include_once  $load_path;
	
	
	
	
	
	$data = $_POST;
	unset($data['xfile']); 
	
	
	$user_id = get_current_user_id();
	$post_id = $data['post-id'];
	unset($data['post-id']);
	
// 	$test = print_r($data, true);
// 	file_put_contents('c:\ppp.txt', $test, FILE_APPEND);
	
	//$the_comment = get_comment( $user_id = 7 );
	
	$args = array(
			'post_id' => $post_id,
			'user_id' => $user_id,
			'type' => 'product_pin'
	);
	
	$my_comments = get_comments($args);
	
	//FB::log($my_comments, '$my_comments');
	
	if (count($my_comments) > 0) {
		$my_comment = $my_comments[0];
		$my_comment_arr = get_object_vars($my_comment);
			
		//FB::log($my_comment_arr, '$my_comment_arr');
			
		$my_comment_arr['comment_content'] = json_encode($data);
		wp_update_comment($my_comment_arr);
	}
	else {
		$current_user = wp_get_current_user();
		$commentdata = array(
				'comment_post_ID' => $post_id,
				'comment_author' => $current_user->user_login,
				'comment_author_email' => $current_user->user_email,
				'comment_author_url' => $current_user->user_url,
				'comment_content' => json_encode($data),
				'comment_type' => 'product_pin',
				'comment_parent' => 0,
				'user_id' => $user_id,
				'comment_author_IP' => preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR'] ),
				'comment_agent' => substr($_SERVER['HTTP_USER_AGENT'], 0, 254),
				'comment_date' => current_time('mysql'),
				'comment_date_gmt' => current_time('mysql', 1),
				'comment_approved' => 1
		);
		$pin_id = wp_insert_comment($commentdata);
	}
	
	//$comment_id = 0;
	
	$location = get_permalink($post_id); 
	//get_comment_link(1);//'?product=asdfasdf&cpage=1#pin-25';
	
	//$location = empty($_POST['redirect_to']) ? get_comment_link($comment_id) : $_POST['redirect_to'] . '#pin-' . $comment_id;
	//$location = apply_filters('comment_post_redirect', $location, $comment);
	
	wp_safe_redirect( $location );
?>