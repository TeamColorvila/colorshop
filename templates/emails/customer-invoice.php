<?php
/**
 * Customer invoice email
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates/Emails
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('colorshop_email_header', $email_heading); ?>

<?php if ($order->status=='pending') : ?>

	<p><?php printf( __( 'An order has been created for you on %s. To pay for this order please use the following link: %s', 'colorshop' ), get_bloginfo( 'name' ), '<a href="' . $order->get_checkout_payment_url() . '">' . __( 'pay', 'colorshop' ) . '</a>' ); ?></p>

<?php endif; ?>

<?php do_action('colorshop_email_before_order_table', $order, false); ?>

<h2><?php echo __( 'Order:', 'colorshop' ) . ' ' . $order->get_order_number(); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( colorshop_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'colorshop' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'colorshop' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'colorshop' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			switch ( $order->status ) {
				case "completed" :
					echo $order->email_order_items_table( $order->is_download_permitted(), false, true );
				break;
				case "processing" :
					echo $order->email_order_items_table( $order->is_download_permitted(), true, true );
				break;
				default :
					echo $order->email_order_items_table( $order->is_download_permitted(), true, false );
				break;
			}
		?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action('colorshop_email_after_order_table', $order, false); ?>

<?php do_action( 'colorshop_email_order_meta', $order, false ); ?>

<?php do_action('colorshop_email_footer'); ?>