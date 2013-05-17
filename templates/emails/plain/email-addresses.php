<?php
/**
 * Email Addresses (plain)
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates/Emails/Plain
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo "\n" . __( 'Billing address', 'colorshop' ) . ":\n";
echo $order->get_formatted_billing_address() . "\n\n";

if ( get_option( 'colorshop_ship_to_billing_address_only' ) == 'no' && ( $shipping = $order->get_formatted_shipping_address() ) ) :

	echo __( 'Shipping address', 'colorshop' ) . ":\n";

	echo $shipping . "\n\n";

endif;