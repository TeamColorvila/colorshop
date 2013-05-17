<?php
/**
 * Checkout Shortcode
 *
 * Used on the checkout page, the checkout shortcode displays the checkout process.
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/Checkout
 * @version     1.0.0
 */

class CS_Shortcode_Checkout {

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

		// Prevent cache
		$colorshop->nocache();

		// Show non-cart errors
		$colorshop->show_messages();

		// Check cart has contents
		if ( sizeof( $colorshop->cart->get_cart() ) == 0 ) return;

		// Calc totals
		$colorshop->cart->calculate_totals();

		// Check cart contents for errors
		do_action('colorshop_check_cart_items');

		// Get checkout object
		$checkout = $colorshop->checkout();

		if ( empty( $_POST ) && $colorshop->error_count() > 0 ) {

			colorshop_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );

		} else {

			$non_js_checkout = ! empty( $_POST['colorshop_checkout_update_totals'] ) ? true : false;

			if ( $colorshop->error_count() == 0 && $non_js_checkout )
				$colorshop->add_message( __( 'The order totals have been updated. Please confirm your order by pressing the Place Order button at the bottom of the page.', 'colorshop' ) );

			colorshop_get_template( 'checkout/form-checkout.php', array( 'checkout' => $checkout ) );

		}
	}
}