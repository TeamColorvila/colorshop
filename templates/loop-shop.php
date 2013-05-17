<?php
/**
 * Loop-shop (deprecated)
 *
 * Outputs a product loop
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 * @deprecated 	1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

_deprecated_file( basename(__FILE__), '1.6', '', 'Use your own loop code, as well as the content-product.php template. loop-shop.php will be removed in CS 2.1.' );
?>

<?php if ( have_posts() ) : ?>

	<?php do_action('colorshop_before_shop_loop'); ?>

	<?php colorshop_product_loop_start(); ?>

		<?php colorshop_product_subcategories(); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php colorshop_get_template_part( 'content', 'product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php colorshop_product_loop_end(); ?>

	<?php do_action('colorshop_after_shop_loop'); ?>

<?php else : ?>

	<?php if ( ! colorshop_product_subcategories( array( 'before' => colorshop_product_loop_start( false ), 'after' => colorshop_product_loop_end( false ) ) ) ) : ?>

		<p><?php _e( 'No products found which match your selection.', 'colorshop' ); ?></p>

	<?php endif; ?>

<?php endif; ?>

<div class="clear"></div>