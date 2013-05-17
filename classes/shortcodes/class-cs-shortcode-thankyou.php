<?php
/**
 * Thankyou Shortcode
 *
 * The thankyou page displays after successful checkout and can be hooked into by payment gateways.
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/Thankyou
 * @version     1.0.0
 */

class CS_Shortcode_Thankyou {

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
		$colorshop->show_messages();

		$order = false;

		// Get the order
		$order_id  = apply_filters( 'colorshop_thankyou_order_id', empty( $_GET['order'] ) ? 0 : absint( $_GET['order'] ) );
		$order_key = apply_filters( 'colorshop_thankyou_order_key', empty( $_GET['key'] ) ? '' : colorshop_clean( $_GET['key'] ) );

		if ( $order_id > 0 ) {
			$order = new CS_Order( $order_id );
			if ( $order->order_key != $order_key )
				unset( $order );
		}

		// Empty awaiting payment session
		unset( $colorshop->session->order_awaiting_payment );

		colorshop_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
	}
}