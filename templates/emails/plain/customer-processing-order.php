<?php
/**
 * Customer processing order email
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates/Emails/Plain
 * @version     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo __( "Your order has been received and is now being processed. Your order details are shown below for your reference:", 'colorshop' ) . "\n\n";

echo "****************************************************\n\n";

do_action( 'colorshop_email_before_order_table', $order, false );

echo sprintf( __( 'Order number: %s', 'colorshop'), $order->get_order_number() ) . "\n";
echo sprintf( __( 'Order date: %s', 'colorshop'), date_i18n( colorshop_date_format(), strtotime( $order->order_date ) ) ) . "\n";

do_action( 'colorshop_email_order_meta', $order, false, true );

echo "\n" . $order->email_order_items_table( $order->is_download_permitted(), true, ($order->status=='processing') ? true : false, '', '', true );

echo "----------\n\n";

if ( $totals = $order->get_order_item_totals() ) {
	foreach ( $totals as $total ) {
		echo $total['label'] . "\t " . $total['value'] . "\n";
	}
}

echo "\n****************************************************\n\n";

do_action( 'colorshop_email_after_order_table', $order, false, true );

echo __( 'Your details', 'colorshop' ) . "\n\n";

if ( $order->billing_email )
	echo __( 'Email:', 'colorshop' ); echo $order->billing_email. "\n";

if ( $order->billing_phone )
	echo __( 'Tel:', 'colorshop' ); ?> <?php echo $order->billing_phone. "\n";

colorshop_get_template( 'emails/plain/email-addresses.php', array( 'order' => $order ) );

echo "\n****************************************************\n\n";

echo apply_filters( 'colorshop_email_footer_text', get_option( 'colorshop_email_footer_text' ) );