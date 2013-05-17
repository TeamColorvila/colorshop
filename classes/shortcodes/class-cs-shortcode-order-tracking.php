<?php
/**
 * Order Tracking Shortcode
 *
 * Lets a user see the status of an order by entering their order details.
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/Order_Tracking
 * @version     1.0.0
 */

class CS_Shortcode_Order_Tracking {

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

		extract(shortcode_atts(array(
		), $atts));

		global $post;

		if ( ! empty( $_REQUEST['orderid'] ) ) {

			$colorshop->verify_nonce( 'order_tracking' );

			$order_id 		= empty( $_REQUEST['orderid'] ) ? 0 : esc_attr( $_REQUEST['orderid'] );
			$order_email	= empty( $_REQUEST['order_email'] ) ? '' : esc_attr( $_REQUEST['order_email']) ;

			if ( ! $order_id ) {

				echo '<p class="colorshop-error">' . __( 'Please enter a valid order ID', 'colorshop' ) . '</p>';

			} elseif ( ! $order_email ) {

				echo '<p class="colorshop-error">' . __( 'Please enter a valid order email', 'colorshop' ) . '</p>';

			} else {

				$order = new CS_Order( apply_filters( 'colorshop_shortcode_order_tracking_order_id', $order_id ) );

				if ( $order->id && $order_email ) {

					if ( strtolower( $order->billing_email ) == strtolower( $order_email ) ) {
						do_action( 'colorshop_track_order', $order->id );
						colorshop_get_template( 'order/tracking.php', array(
							'order' => $order
						) );

						return;
					}

				} else {

					echo '<p class="colorshop-error">' . sprintf( __( 'Sorry, we could not find that order id in our database.', 'colorshop' ), get_permalink($post->ID ) ) . '</p>';

				}

			}

		}

		colorshop_get_template( 'order/form-tracking.php' );
	}
}