<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/colorshop/content-single-product.php
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * colorshop_before_single_product hook
	 *
	 * @hooked colorshop_show_messages - 10
	 */
	 do_action( 'colorshop_before_single_product' );
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * colorshop_show_product_images hook
		 *
		 * @hooked colorshop_show_product_sale_flash - 10
		 * @hooked colorshop_show_product_images - 20
		 */
		do_action( 'colorshop_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * colorshop_single_product_summary hook
			 *
			 * @hooked colorshop_template_single_title - 5
			 * @hooked colorshop_template_single_price - 10
			 * @hooked colorshop_template_single_excerpt - 20
			 * @hooked colorshop_template_single_add_to_cart - 30
			 * @hooked colorshop_template_single_meta - 40
			 * @hooked colorshop_template_single_sharing - 50
			 */
			do_action( 'colorshop_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * colorshop_after_single_product_summary hook
		 *
		 * @hooked colorshop_output_product_data_tabs - 10
		 * @hooked colorshop_output_related_products - 20
		 */
		do_action( 'colorshop_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'colorshop_after_single_product' ); ?>