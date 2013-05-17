<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/colorshop/archive-product.php
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

	<?php
		/**
		 * colorshop_before_main_content hook
		 *
		 * @hooked colorshop_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked colorshop_breadcrumb - 20
		 */
		do_action('colorshop_before_main_content');
	?>

		<h1 class="page-title"><?php colorshop_page_title(); ?></h1>

		<?php do_action( 'colorshop_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * colorshop_before_shop_loop hook
				 *
				 * @hooked colorshop_result_count - 20
				 * @hooked colorshop_catalog_ordering - 30
				 */
				do_action( 'colorshop_before_shop_loop' );
			?>
			
			<?php
					
				$display_product = array();
				$all_attributes = explode(';', $_REQUEST['attr']);
				
				$cat_name = get_query_var('product_cat');
				if (empty($cat_name)) {
					//$cat_name = 'all';
					$tax_query = '';
				} else {
					$tax_query = array (
							array (
									'taxonomy' => 'product_cat',
									'field' => 'slug',
									'terms' => $cat_name
							)
					);
				}
				
				$args = array( 'post_type' => 'product', 'tax_query' => $tax_query, 'numberposts' => -1, 'orderby' => 'meta_value_num', 'order' => get_query_var('order'), 'meta_key' => '_price' );
				$lastposts = get_posts( $args );
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				/*** page size ***/
				$per_page = get_option('posts_per_page');
				
				$start = ($paged - 1) * $per_page + 1;
				$end = $paged * $per_page;
				$count = 0;
				
				foreach($lastposts as $post) : //setup_postdata($post);
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
					
					$count++;
					
					if ($count >= $start && $count <= $end) {
						$display_product[] = $post;
					}
					
				
				endforeach; // end of the loop. 
				
				global $total;
				$total = $count;
			?>
			
			<p class="colorshop-result-count">
				<?php
				//$paged    = max( 1, $wp_query->get( 'paged' ) );
				//$per_page = $wp_query->get( 'posts_per_page' );
				//$total    = $count;//$wp_query->found_posts;
				$first    = ( $per_page * $paged ) - $per_page + 1;
				$last     = min( $total, $per_page * $paged );
				
				?>
				
				<?php
				if ( 1 == $total ) {
					_e( 'Showing the single result', 'colorshop' );
				} elseif ( $total <= $per_page ) {
					printf( __( 'Showing all %d results', 'colorshop' ), $total );
				} else {
					printf( _x( 'Showing %1$d–%2$d of %3$d results', '%1$d = first, %2$d = last, %3$d = total', 'colorshop' ), $first, $last, $total );
				}
				?>
			</p>

			<?php colorshop_product_loop_start(); ?>

				<?php colorshop_product_subcategories(); ?>
				<?php
					foreach ($display_product as $tmp_post) {
						global $product;
						global $post;
						$post = $tmp_post;
						$product = get_product($tmp_post);
						colorshop_get_template( 'content-product.php' );
					}
				?>

			<?php colorshop_product_loop_end(); ?>

			<?php
				/**
				 * colorshop_after_shop_loop hook
				 *
				 * @hooked colorshop_pagination - 10
				 */
				do_action( 'colorshop_after_shop_loop' );
			?>

		<?php elseif ( ! colorshop_product_subcategories( array( 'before' => colorshop_product_loop_start( false ), 'after' => colorshop_product_loop_end( false ) ) ) ) : ?>

			<?php colorshop_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * colorshop_after_main_content hook
		 *
		 * @hooked colorshop_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('colorshop_after_main_content');
	?>

	<?php
		/**
		 * colorshop_sidebar hook
		 *
		 * @hooked colorshop_get_sidebar - 10
		 */
		do_action('colorshop_sidebar');
	?>

<?php get_footer('shop'); ?>