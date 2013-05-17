<?php
/**
 * Single Product Up-Sells
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $colorshop, $colorshop_loop;

$upsells = $product->get_upsells();

if ( sizeof( $upsells ) == 0 ) return;

$meta_query = array();
$meta_query[] = $colorshop->query->visibility_meta_query();
$meta_query[] = $colorshop->query->stock_status_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $upsells,
	'post__not_in'        => array( $product->id ),
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$colorshop_loop['columns'] 	= $columns;

if ( $products->have_posts() ) : ?>

	<div class="upsells products">

		<h2><?php _e( 'You may also like&hellip;', 'colorshop' ) ?></h2>

		<?php colorshop_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php colorshop_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php colorshop_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_postdata();
