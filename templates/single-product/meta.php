<?php
/**
 * Single Product Meta
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<div class="product_meta">

	<?php do_action( 'colorshop_product_meta_start' ); ?>

	<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option( 'colorshop_enable_sku' ) == 'yes' && $product->get_sku() ) : ?>
		<span itemprop="productID" class="sku_wrapper"><?php _e( 'SKU:', 'colorshop' ); ?> <span class="sku"><?php echo $product->get_sku(); ?></span>.</span>
	<?php endif; ?>

	<?php
		$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
		echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $size, 'colorshop' ) . ' ', '.</span>' );
	?>

	<?php
		$size = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
		echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $size, 'colorshop' ) . ' ', '.</span>' );
	?>

	<?php do_action( 'colorshop_product_meta_end' ); ?>

</div>