<?php
/**
 * Cart totals
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$available_methods = $colorshop->shipping->get_available_shipping_methods();
?>
<div class="cart_totals <?php if ( $colorshop->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'colorshop_before_cart_totals' ); ?>

	<?php if ( ! $colorshop->shipping->enabled || $available_methods || ! $colorshop->customer->get_shipping_country() || ! $colorshop->customer->has_calculated_shipping() ) : ?>

		<h2><?php _e( 'Cart Totals', 'colorshop' ); ?></h2>

		<table cellspacing="0">
			<tbody>

				<tr class="cart-subtotal">
					<th><strong><?php _e( 'Cart Subtotal', 'colorshop' ); ?></strong></th>
					<td><strong><?php echo $colorshop->cart->get_cart_subtotal(); ?></strong></td>
				</tr>

				<?php if ( $colorshop->cart->get_discounts_before_tax() ) : ?>

					<tr class="discount">
						<th><?php _e( 'Cart Discount', 'colorshop' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '1', $colorshop->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'colorshop' ); ?></a></th>
						<td>-<?php echo $colorshop->cart->get_discounts_before_tax(); ?></td>
					</tr>

				<?php endif; ?>

				<?php if ( $colorshop->cart->needs_shipping() && $colorshop->cart->show_shipping() && ( $available_methods || get_option( 'colorshop_enable_shipping_calc' ) == 'yes' ) ) : ?>

					<?php do_action( 'colorshop_cart_totals_before_shipping' ); ?>

					<tr class="shipping">
						<th><?php _e( 'Shipping', 'colorshop' ); ?></th>
						<td><?php colorshop_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>
					</tr>

					<?php do_action( 'colorshop_cart_totals_after_shipping' ); ?>

				<?php endif ?>

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
					// Show the tax row if showing prices exclusive of tax only
					if ( $colorshop->cart->tax_display_cart == 'excl' ) {
						$taxes = $colorshop->cart->get_formatted_taxes();

						if ( sizeof( $taxes ) > 0 ) {

							$has_compound_tax = false;

							foreach ( $taxes as $key => $tax ) {
								if ( $colorshop->cart->tax->is_compound( $key ) ) {
									$has_compound_tax = true;
									continue;
								}

								echo '<tr class="tax-rate tax-rate-' . $key . '">
									<th>' . $colorshop->cart->tax->get_rate_label( $key ) . '</th>
									<td>' . $tax . '</td>
								</tr>';
							}

							if ( $has_compound_tax ) {

								echo '<tr class="order-subtotal">
									<th><strong>' . __( 'Subtotal', 'colorshop' ) . '</strong></th>
									<td><strong>' . $colorshop->cart->get_cart_subtotal( true ) . '</strong></td>
								</tr>';
							}

							foreach ( $taxes as $key => $tax ) {
								if ( ! $colorshop->cart->tax->is_compound( $key ) )
									continue;

								echo '<tr class="tax-rate tax-rate-' . $key . '">
									<th>' . $colorshop->cart->tax->get_rate_label( $key ) . '</th>
									<td>' . $tax . '</td>
								</tr>';
							}

						} elseif ( $colorshop->cart->get_cart_tax() > 0 ) {

							echo '<tr class="tax">
								<th>' . __( 'Tax', 'colorshop' ) . '</th>
								<td>' . $colorshop->cart->get_cart_tax() . '</td>
							</tr>';
						}
					}
				?>

				<?php if ( $colorshop->cart->get_discounts_after_tax() ) : ?>

					<tr class="discount">
						<th><?php _e( 'Order Discount', 'colorshop' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '2', $colorshop->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'colorshop' ); ?></a></th>
						<td>-<?php echo $colorshop->cart->get_discounts_after_tax(); ?></td>
					</tr>

				<?php endif; ?>

				<?php do_action( 'colorshop_cart_totals_before_order_total' ); ?>

				<tr class="total">
					<th><strong><?php _e( 'Order Total', 'colorshop' ); ?></strong></th>
					<td>
						<strong><?php echo $colorshop->cart->get_total(); ?></strong>
						<?php
							// If prices are tax inclusive, show taxes here
							if (  $colorshop->cart->tax_display_cart == 'incl' ) {
								$tax_string_array = array();
								$taxes = $colorshop->cart->get_formatted_taxes();

								if ( sizeof( $taxes ) > 0 )
									foreach ( $taxes as $key => $tax )
										$tax_string_array[] = sprintf( '%s %s', $tax, $colorshop->cart->tax->get_rate_label( $key ) );
								elseif ( $colorshop->cart->get_cart_tax() )
									$tax_string_array[] = sprintf( '%s tax', $tax );

								if ( ! empty( $tax_string_array ) ) {
									echo '<small class="includes_tax">' . sprintf( __( '(Includes %s)', 'colorshop' ), implode( ', ', $tax_string_array ) ) . '</small>';
								}
							}
						?>
					</td>
				</tr>

				<?php do_action( 'colorshop_cart_totals_after_order_total' ); ?>

			</tbody>
		</table>

		<?php if ( $colorshop->cart->get_cart_tax() ) : ?>

			<p><small><?php

				$estimated_text = ( $colorshop->customer->is_customer_outside_base() && ! $colorshop->customer->has_calculated_shipping() ) ? sprintf( ' ' . __( ' (taxes estimated for %s)', 'colorshop' ), $colorshop->countries->estimated_for_prefix() . __( $colorshop->countries->countries[ $colorshop->countries->get_base_country() ], 'colorshop' ) ) : '';

				printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'colorshop' ), $estimated_text );

			?></small></p>

		<?php endif; ?>

	<?php elseif( $colorshop->cart->needs_shipping() ) : ?>

		<?php if ( ! $colorshop->customer->get_shipping_state() || ! $colorshop->customer->get_shipping_postcode() ) : ?>

			<div class="colorshop-info">

				<p><?php _e( 'No shipping methods were found; please recalculate your shipping and enter your state/county and zip/postcode to ensure there are no other available methods for your location.', 'colorshop' ); ?></p>

			</div>

		<?php else : ?>

			<?php

				$customer_location = $colorshop->countries->countries[ $colorshop->customer->get_shipping_country() ];

				echo apply_filters( 'colorshop_cart_no_shipping_available_html',
					'<div class="colorshop-error"><p>' .
					sprintf( __( 'Sorry, it seems that there are no available shipping methods for your location (%s).', 'colorshop' ) . ' ' . __( 'If you require assistance or wish to make alternate arrangements please contact us.', 'colorshop' ), $customer_location ) .
					'</p></div>'
				);

			?>

		<?php endif; ?>

	<?php endif; ?>

	<?php do_action( 'colorshop_after_cart_totals' ); ?>

</div>