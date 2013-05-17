<?php
/**
 * Cart Page
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$colorshop->show_messages();
?>

<?php do_action( 'colorshop_before_cart' ); ?>

<form action="<?php echo esc_url( $colorshop->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'colorshop_before_cart_table' ); ?>

<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e( 'Product', 'colorshop' ); ?></th>
			<th class="product-price"><?php _e( 'Price', 'colorshop' ); ?></th>
			<th class="product-quantity"><?php _e( 'Quantity', 'colorshop' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Total', 'colorshop' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'colorshop_before_cart_contents' ); ?>

		<?php
		if ( sizeof( $colorshop->cart->get_cart() ) > 0 ) {
			foreach ( $colorshop->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
					<tr class = "<?php echo esc_attr( apply_filters('colorshop_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?>">
						<!-- Remove from cart link -->
						<td class="product-remove">
							<?php
								echo apply_filters( 'colorshop_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $colorshop->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'colorshop' ) ), $cart_item_key );
							?>
						</td>

						<!-- The thumbnail -->
						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'colorshop_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );

								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo $thumbnail;
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('colorshop_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
							?>
						</td>

						<!-- Product Name -->
						<td class="product-name">
							<?php
								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'colorshop_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('colorshop_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('colorshop_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );

								// Meta data
								echo $colorshop->cart->get_item_data( $values );

                   				// Backorder notification
                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'colorshop' ) . '</p>';
							?>
						</td>

						<!-- Product price -->
						<td class="product-price">
							<?php
								$product_price = get_option('colorshop_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

								echo apply_filters('colorshop_cart_item_price_html', colorshop_price( $product_price ), $values, $cart_item_key );
							?>
						</td>

						<!-- Quantity inputs -->
						<td class="product-quantity">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {

									$step	= apply_filters( 'colorshop_quantity_input_step', '1', $_product );
									$min 	= apply_filters( 'colorshop_quantity_input_min', '', $_product );
									$max 	= apply_filters( 'colorshop_quantity_input_max', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $_product );

									$product_quantity = sprintf( '<div class="quantity"><input type="number" name="cart[%s][qty]" step="%s" min="%s" max="%s" value="%s" size="4" title="' . _x( 'Qty', 'Product quantity input tooltip', 'colorshop' ) . '" class="input-text qty text" maxlength="12" /></div>', $cart_item_key, $step, $min, $max, esc_attr( $values['quantity'] ) );
								}

								echo apply_filters( 'colorshop_cart_item_quantity', $product_quantity, $cart_item_key );
							?>
						</td>

						<!-- Product subtotal -->
						<td class="product-subtotal">
							<?php
								echo apply_filters( 'colorshop_cart_item_subtotal', $colorshop->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
							?>
						</td>
					</tr>
					<?php
				}
			}
		}

		do_action( 'colorshop_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">

				<?php if ( $colorshop->cart->coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php _e( 'Coupon', 'colorshop' ); ?>:</label> <input name="coupon_code" class="input-text" id="coupon_code" value="" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'colorshop' ); ?>" />

						<?php do_action('colorshop_cart_coupon'); ?>

					</div>
				<?php } ?>

				<input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'colorshop' ); ?>" /> <input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'Proceed to Checkout &rarr;', 'colorshop' ); ?>" />

				<?php do_action('colorshop_proceed_to_checkout'); ?>

				<?php $colorshop->nonce_field('cart') ?>
			</td>
		</tr>

		<?php do_action( 'colorshop_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'colorshop_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php do_action('colorshop_cart_collaterals'); ?>

	<?php colorshop_cart_totals(); ?>

	<?php colorshop_shipping_calculator(); ?>

</div>

<?php do_action( 'colorshop_after_cart' ); ?>