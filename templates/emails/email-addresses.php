<?php
/**
 * Email Addresses
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates/Emails
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><table cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0">

	<tr>

		<td valign="top" width="50%">

			<h3><?php _e( 'Billing address', 'colorshop' ); ?></h3>

			<p><?php echo $order->get_formatted_billing_address(); ?></p>

		</td>

		<?php if ( get_option( 'colorshop_ship_to_billing_address_only' ) == 'no' && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>

		<td valign="top" width="50%">

			<h3><?php _e( 'Shipping address', 'colorshop' ); ?></h3>

			<p><?php echo $shipping; ?></p>

		</td>

		<?php endif; ?>

	</tr>

</table>