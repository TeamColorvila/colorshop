<?php

/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/colorshop/content-product.php
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $colorshop_loop;

// Store loop count we're currently on
if ( empty( $colorshop_loop['loop'] ) )
	$colorshop_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $colorshop_loop['columns'] ) )
	$colorshop_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product->is_visible() )
	return;

// Increase loop count
$colorshop_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $colorshop_loop['loop'] - 1 ) % $colorshop_loop['columns'] || 1 == $colorshop_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $colorshop_loop['loop'] % $colorshop_loop['columns'] )
	$classes[] = 'last';
?>
<li <?php post_class( $classes ); ?>>

	<?php do_action( 'colorshop_before_shop_loop_item' ); ?>

	<a href="<?php echo get_permalink($product->id)  //the_permalink(); ?>">

		<?php
			/**
			 * colorshop_before_shop_loop_item_title hook
			 *
			 * @hooked colorshop_show_product_loop_sale_flash - 10
			 * @hooked colorshop_template_loop_product_thumbnail - 10
			 */
			do_action( 'colorshop_before_shop_loop_item_title' );
		?>

		<h3><?php echo $product->post->post_title; //the_title(); ?></h3>

		<?php
			/**
			 * colorshop_after_shop_loop_item_title hook
			 *
			 * @hooked colorshop_template_loop_price - 10
			 */
			do_action( 'colorshop_after_shop_loop_item_title' );
		?>

	</a>

	<?php do_action( 'colorshop_after_shop_loop_item' ); ?>

</li>