<?php
/**
 * Admin new order email (plain text)
 *
 * @author		ColorVila
 * @package 	ColorShop/Templates/Emails/Plain
 * @version 	1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo sprintf( __( 'You have received an order from %s. Their order is as follows:', 'colorshop' ), $order->billing_first_name . ' ' . $order->billing_last_name ) . "\n\n";

echo "****************************************************\n\n";

do_action( 'colorshop_email_before_order_table', $order, true );

echo sprintf( __( 'Order number: %s', 'colorshop'), $order->get_order_number() ) . "\n";
echo sprintf( __( 'Order date: %s', 'colorshop'), date_i18n( __( 'jS F Y', 'colorshop' ), strtotime( $order->order_date ) ) ) . "\n";

do_action( 'colorshop_email_order_meta', $order, true, true );

echo "\n" . $order->email_order_items_table( false, true, '', '', '', true );

echo "----------\n\n";

if ( $totals = $order->get_order_item_totals() ) {
	foreach ( $totals as $total ) {
		echo $total['label'] . "\t " . $total['value'] . "\n";
	}
}

echo "\n****************************************************\n\n";

do_action( 'colorshop_email_after_order_table', $order, true, true );

_e( 'Customer details', 'colorshop' );

if ( $order->billing_email )
	echo __( 'Email:', 'colorshop' ); echo $order->billing_email. "\n";

if ( $order->billing_phone )
	echo __( 'Tel:', 'colorshop' ); ?> <?php echo $order->billing_phone. "\n";

colorshop_get_template( 'emails/plain/email-addresses.php', array( 'order' => $order ) );

echo "\n****************************************************\n\n";

echo apply_filters( 'colorshop_email_footer_text', get_option( 'colorshop_email_footer_text' ) );