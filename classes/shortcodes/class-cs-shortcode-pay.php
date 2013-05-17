<?php
/**
 * Pay Shortcode
 *
 * The pay page. Used for form based gateways to show payment forms and order info.
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/Pay
 * @version     1.0.0
 */

class CS_Shortcode_Pay {

	/**
	 * Get the shortcode content.
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	public static function get( $atts ) {
		global $colorshop;
		return $colorshop->shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $atts ) {
		global $colorshop;

		$colorshop->nocache();

		do_action( 'before_colorshop_pay' );

		$colorshop->show_messages();

		if ( isset( $_GET['pay_for_order'] ) && isset( $_GET['order'] ) && isset( $_GET['order_id'] ) ) {

			// Pay for existing order
			$order_key            = urldecode( $_GET[ 'order' ] );
			$order_id             = absint( $_GET[ 'order_id' ] );
			$order                = new CS_Order( $order_id );
			$valid_order_statuses = apply_filters( 'colorshop_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order );

			if ( $order->id == $order_id && $order->order_key == $order_key && in_array( $order->status, $valid_order_statuses ) ) {

				// Set customer location to order location
				if ( $order->billing_country )
					$colorshop->customer->set_country( $order->billing_country );
				if ( $order->billing_state )
					$colorshop->customer->set_state( $order->billing_state );
				if ( $order->billing_postcode )
					$colorshop->customer->set_postcode( $order->billing_postcode );

				// Show form
				colorshop_get_template( 'checkout/form-pay.php', array( 'order' => $order ) );

			} elseif ( ! in_array( $order->status, $valid_order_statuses ) ) {

				$colorshop->add_error( __( 'Your order has already been paid for. Please contact us if you need assistance.', 'colorshop' ) );
				$colorshop->show_messages();

			} else {

				$colorshop->add_error( __( 'Invalid order.', 'colorshop' ) );
				$colorshop->show_messages();

			}

		} else {

			// Pay for order after checkout step
			$order_id  = isset( $_GET['order'] ) ? absint( $_GET['order'] ) : 0;
			$order_key = isset( $_GET['key'] ) ? colorshop_clean( $_GET['key'] ) : '';

			if ( $order_id > 0 ) {

				$order                = new CS_Order( $order_id );
				$valid_order_statuses = apply_filters( 'colorshop_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order );

				if ( $order->order_key == $order_key && in_array( $order->status, $valid_order_statuses ) ) {

					?>
					<ul class="order_details">
						<li class="order">
							<?php _e( 'Order:', 'colorshop' ); ?>
							<strong><?php echo $order->get_order_number(); ?></strong>
						</li>
						<li class="date">
							<?php _e( 'Date:', 'colorshop' ); ?>
							<strong><?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?></strong>
						</li>
						<li class="total">
							<?php _e( 'Total:', 'colorshop' ); ?>
							<strong><?php echo $order->get_formatted_order_total(); ?></strong>
						</li>
						<?php if ($order->payment_method_title) : ?>
						<li class="method">
							<?php _e( 'Payment method:', 'colorshop' ); ?>
							<strong><?php
								echo $order->payment_method_title;
							?></strong>
						</li>
						<?php endif; ?>
					</ul>

					<?php do_action( 'colorshop_receipt_' . $order->payment_method, $order_id ); ?>

					<div class="clear"></div>
					<?php

				} elseif ( ! in_array( $order->status, $valid_order_statuses ) ) {

					$colorshop->add_error( __( 'Your order has already been paid for. Please contact us if you need assistance.', 'colorshop' ) );
					$colorshop->show_messages();

				}

			} else {

				$colorshop->add_error( __( 'Invalid order.', 'colorshop' ) );
				$colorshop->show_messages();

			}

		}

		do_action( 'after_colorshop_pay' );
	}
}