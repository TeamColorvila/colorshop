<?php
// require_once 'f:/workspace/wordpress3/FirePHPCore/fb.php';
// ob_start();

	$load_path = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '\wp-load.php';
	include_once  $load_path;

	$all_attributes = explode(';', $_REQUEST['attr']);
	$args = array( 'post_type' => 'product' );
	$r = new WP_Query($args);
	$lastposts = $r->posts;
	
	foreach($lastposts as $post) : setup_postdata($post);
		if($_REQUEST['attr']) {
			$product = get_product($post);
			$attributes = $product->get_attributes();
			$product_arr = array();
			foreach ($attributes as $attr) {
				$name = $attr['name'];
				$value = $product->get_attribute($name);
				$value = str_replace(' ', '', $value);
				$product_arr[$name] = explode(',', $value);
			}
			$product_select_arr = array();
			$no_match = false;
			foreach ($all_attributes as $item) {
				$group = explode(':', $item );
				$value_current = $product_arr['pa_' . $group[0]];
				if (empty($value_current)) {
					$no_match = true;
					break;
				}
				$value_select = explode(',', $group[1]);
				$combine = array_intersect($value_current, $value_select);
				
				if (empty($combine)) {
					$no_match = true;
					break;
				}	
			}
			
			if ($no_match) {
				continue;
			}
		}
		colorshop_get_template( 'content-product.php' );
	endforeach; 
?>