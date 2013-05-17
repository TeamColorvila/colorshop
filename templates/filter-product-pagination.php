// <?php
// 	$load_path = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '\wp-load.php';
// 	include_once  $load_path;
	
// 	global $wp_query;
	
	
// 	$all_attributes = explode(';', $_REQUEST['attr']);
// 	$args = array( 'post_type' => 'product', 'numberposts' => -1 );
// 	$lastposts = get_posts( $args );
	
// 	$count = 0;
// 	foreach($lastposts as $post) : setup_postdata($post);
// 	if($_REQUEST['attr']) {
// 		$product = get_product($post);
// 		$attributes = $product->get_attributes();
// 		$product_arr = array();
// 		foreach ($attributes as $attr) {
// 			$name = $attr['name'];
// 			$value = $product->get_attribute($name);
// 			$value = str_replace(' ', '', $value);
// 			$product_arr[$name] = explode(',', $value);
// 		}
// 		$product_select_arr = array();
// 		$no_match = false;
// 		foreach ($all_attributes as $item) {
// 			$group = explode(':', $item );
// 			$value_current = $product_arr['pa_' . $group[0]];
// 			if (empty($value_current)) {
// 				$no_match = true;
// 				break;
// 			}
// 			$value_select = explode(',', $group[1]);
// 			$combine = array_intersect($value_current, $value_select);
	
// 			if (empty($combine)) {
// 				$no_match = true;
// 				break;
// 			}
// 		}
			
// 		if ($no_match) {
// 			continue;
// 		}
// 	}
// 	//colorshop_get_template( 'content-product.php' );
// 	$count++;
// 	endforeach;
	
// 	$page_size = get_option('posts_per_page');
	
// 	$total_page =  ceil($count / $page_size);
	
// 	require_once 'f:/workspace/wordpress3/FirePHPCore/fb.php';
// 	ob_start();
	
// 	FB::log($count, '$count');
// 	FB::log($page_size, '$page_size');
// 	FB::log($total_page, '$total_page');
	
// 	FB::log(get_pagenum_link( 999999999 ), 'get_pagenum_link( 999999999 )');
	
// 	//FB::log($_SERVER["REQUEST_URI"], '$_SERVER["REQUEST_URI"]');
// 	$url = '?post_type=product&attr=' . $_REQUEST['attr']; 
// 	FB::log($url, '$url');
// ?>
<!-- <!-- <nav class="colorshop-pagination"> --> -->
	<?php
	
// 		//get_pagenum_link();$url, //str_replace( 999999999, '%#%', $url ),//
		
// 		echo paginate_links( apply_filters( 'colorshop_pagination_args', array(
// 			'base' 			=> str_replace( 999999999, '%#%', get_pagenum_link() ),
// 			'format' 		=> '',
// 			'current' 		=> max( 1, get_query_var('paged') ),
// 			'total' 		=> $total_page, //$wp_query->max_num_pages,
// 			'prev_text' 	=> '&larr;',
// 			'next_text' 	=> '&rarr;',
// 			'type'			=> 'list',
// 			'end_size'		=> 3,
// 			'mid_size'		=> 3
// 		) ) );

// 		//get_pagenum_link()
// 	?>
<!-- <!-- </nav> --> -->