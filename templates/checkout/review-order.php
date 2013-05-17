<?php
/**
 * Review order form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$available_methods = $colorshop->shipping->get_available_shipping_methods();
?>
<div id="order_review">

	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'colorshop' ); ?></th>
				<th class="product-total"><?php _e( 'Total', 'colorshop' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr class="cart-subtotal">
				<th><?php _e( 'Cart Subtotal', 'colorshop' ); ?></th>
				<td><?php echo $colorshop->cart->get_cart_subtotal(); ?></td>
			</tr>

			<?php if ( $colorshop->cart->get_discounts_before_tax() ) : ?>

			<tr class="discount">
				<th><?php _e( 'Cart Discount', 'colorshop' ); ?></th>
				<td>-<?php echo $colorshop->cart->get_discounts_before_tax(); ?></td>
			</tr>

			<?php endif; ?>

			<?php if ( $colorshop->cart->needs_shipping() && $colorshop->cart->show_shipping() ) : ?>

				<?php do_action('colorshop_review_order_before_shipping'); ?>

				<tr class="shipping">
					<th><?php _e( 'Shipping', 'colorshop' ); ?></th>
					<td><?php colorshop_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>
				</tr>

				<?php do_action('colorshop_review_order_after_shipping'); ?>

			<?php endif; ?>

			<?php foreach ( $colorshop->cart->get_fees() as $fee ) : ?>

				<tr class="fee fee-<?php echo $fee->id ?>">
					<th><?php echo $fee->name ?></th>
					<td><?php
						if ( $colorshop->cart->tax_display_cart == 'excl' )
							echo colorshop_price( $fee->amount );
						else
							echo colorshop_price( $fee->amount + $fee->tax );
					?></td>
				</tr>

			<?php endforeach; ?>

			<?php
				// Show the tax row if showing prices exlcusive of tax only
				if ( $colorshop->cart->tax_display_cart == 'excl' ) {

					$taxes = $colorshop->cart->get_formatted_taxes();

					if ( sizeof( $taxes ) > 0 ) {

						$has_compound_tax = false;

						foreach ( $taxes as $key => $tax ) {
							if ( $colorshop->cart->tax->is_compound( $key ) ) {
								$has_compound_tax = true;
								continue;
							}
							?>
							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
								<th><?php echo $colorshop->cart->tax->get_rate_label( $key ); ?></th>
								<td><?php echo $tax; ?></td>
							</tr>
							<?php
						}

						if ( $has_compound_tax ) {
							?>
							<tr class="order-subtotal">
								<th><?php _e( 'Subtotal', 'colorshop' ); ?></th>
								<td><?php echo $colorshop->cart->get_cart_subtotal( true ); ?></td>
							</tr>
							<?php
						}

						foreach ( $taxes as $key => $tax ) {
							if ( ! $colorshop->cart->tax->is_compound( $key ) )
								continue;
							?>
							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
								<th><?php echo $colorshop->cart->tax->get_rate_label( $key ); ?></th>
								<td><?php echo $tax; ?></td>
							</tr>
							<?php
						}

					} elseif ( $colorshop->cart->get_cart_tax() ) {
						?>
						<tr class="tax">
							<th><?php _e( 'Tax', 'colorshop' ); ?></th>
							<td><?php echo $colorshop->cart->get_cart_tax(); ?></td>
						</tr>
						<?php
					}
				}
			?>

			<?php if ($colorshop->cart->get_discounts_after_tax()) : ?>

			<tr class="discount">
				<th><?php _e( 'Order Discount', 'colorshop' ); ?></th>
				<td>-<?php echo $colorshop->cart->get_discounts_after_tax(); ?></td>
			</tr>

			<?php endif; ?>

			<?php do_action( 'colorshop_review_order_before_order_total' ); ?>

			<tr class="total">
				<th><strong><?php _e( 'Order Total', 'colorshop' ); ?></strong></th>
				<td>
					<strong><?php echo $colorshop->cart->get_total(); ?></strong>
					<?php
						// If prices are tax inclusive, show taxes here
						if ( $colorshop->cart->tax_display_cart == 'incl' ) {
							$tax_string_array = array();
							$taxes = $colorshop->cart->get_formatted_taxes();

							if ( sizeof( $taxes ) > 0 ) {
								foreach ( $taxes as $key => $tax ) {
									$tax_string_array[] = sprintf( '%s %s', $tax, $colorshop->cart->tax->get_rate_label( $key ) );
								}
							} elseif ( $colorshop->cart->get_cart_tax() ) {
								$tax_string_array[] = sprintf( '%s tax', $tax );
							}

							if ( ! empty( $tax_string_array ) ) {
								?><small class="includes_tax"><?php printf( __( '(Includes %s)', 'colorshop' ), implode( ', ', $tax_string_array ) ); ?></small><?php
							}
						}
					?>
				</td>
			</tr>

			<?php do_action( 'colorshop_review_order_after_order_total' ); ?>

		</tfoot>
		<tbody>
			<?php
				do_action( 'colorshop_review_order_before_cart_contents' );

				if (sizeof($colorshop->cart->get_cart())>0) :
					foreach ($colorshop->cart->get_cart() as $cart_item_key => $values) :
						$_product = $values['data'];
						if ($_product->exists() && $values['quantity']>0) :
							echo '
								<tr class="' . esc_attr( apply_filters('colorshop_checkout_table_item_class', 'checkout_table_item', $values, $cart_item_key ) ) . '">
									<td class="product-name">' .
										apply_filters( 'colorshop_checkout_product_title', $_product->get_title(), $_product ) . ' ' .
										apply_filters( 'colorshop_checkout_item_quantity', '<strong class="product-quantity">&times; ' . $values['quantity'] . '</strong>', $values, $cart_item_key ) .
										$colorshop->cart->get_item_data( $values ) .
									'</td>
									<td class="product-total">' . apply_filters( 'colorshop_checkout_item_subtotal', $colorshop->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key ) . '</td>
								</tr>';
						endif;
					endforeach;
				endif;

				do_action( 'colorshop_review_order_after_cart_contents' );
			?>
		</tbody>
	</table>

	<div id="payment">
		<?php if ($colorshop->cart->needs_payment()) : ?>
		<ul class="payment_methods methods">
			<?php
				$available_gateways = $colorshop->payment_gateways->get_available_payment_gateways();
				if ( ! empty( $available_gateways ) ) {

					// Chosen Method
					if ( isset( $colorshop->session->chosen_payment_method ) && isset( $available_gateways[ $colorshop->session->chosen_payment_method ] ) ) {
						$available_gateways[ $colorshop->session->chosen_payment_method ]->set_current();
					} elseif ( isset( $available_gateways[ get_option( 'colorshop_default_gateway' ) ] ) ) {
						$available_gateways[ get_option( 'colorshop_default_gateway' ) ]->set_current();
					} else {
						current( $available_gateways )->set_current();
					}

					foreach ( $available_gateways as $gateway ) {
						?>
						<li>
							<input type="radio" id="payment_method_<?php echo $gateway->id; ?>" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />
							<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
							<?php
								if ( $gateway->has_fields() || $gateway->get_description() ) :
									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
									$gateway->payment_fields();
									echo '</div>';
								endif;
							?>
						</li>
						<?php
					}
				} else {

					if ( ! $colorshop->customer->get_country() )
						echo '<p>' . __( 'Please fill in your details above to see available payment methods.', 'colorshop' ) . '</p>';
					else
						echo '<p>' . __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'colorshop' ) . '</p>';

				}
			?>
		</ul>
		<?php endif; ?>

		<div class="form-row place-order">

			<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'colorshop' ); ?><br/><input type="submit" class="button alt" name="colorshop_checkout_update_totals" value="<?php _e( 'Update totals', 'colorshop' ); ?>" /></noscript>

			<?php $colorshop->nonce_field('process_checkout')?>

			<?php do_action( 'colorshop_review_order_before_submit' ); ?>

			<input type="submit" class="button alt" name="colorshop_checkout_place_order" id="place_order" value="<?php echo apply_filters('colorshop_order_button_text', __( 'Place order', 'colorshop' )); ?>" />

			<?php if (colorshop_get_page_id('terms')>0) : ?>
			<p class="form-row terms">
				<label for="terms" class="checkbox"><?php _e( 'I have read and accept the', 'colorshop' ); ?> <a href="<?php echo esc_url( get_permalink(colorshop_get_page_id('terms')) ); ?>" target="_blank"><?php _e( 'terms &amp; conditions', 'colorshop' ); ?></a></label>
				<input type="checkbox" class="input-checkbox" name="terms" <?php checked( isset( $_POST['terms'] ), true ); ?> id="terms" />
			</p>
			<?php endif; ?>

			<?php do_action( 'colorshop_review_order_after_submit' ); ?>

		</div>

		<div class="clear"></div>

	</div>

</div>
