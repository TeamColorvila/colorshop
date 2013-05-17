<?php
/**
 * Cart Shortcode
 *
 * Used on the cart page, the cart shortcode displays the cart contents and interface for coupon codes and other cart bits and pieces.
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/Cart
 * @version     1.0.0
 */
class CS_Shortcode_Cart {

	/**
	 * Output the cart shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $atts ) {
		global $colorshop;

		$colorshop->nocache();

		if ( ! defined( 'COLORSHOP_CART' ) ) define( 'COLORSHOP_CART', true );

		// Add Discount
		if ( ! empty( $_POST['apply_coupon'] ) ) {

			if ( ! empty( $_POST['coupon_code'] ) ) {
				$colorshop->cart->add_discount( sanitize_text_field( $_POST['coupon_code'] ) );
			} else {
				$colorshop->add_error( CS_Coupon::get_generic_coupon_error( CS_Coupon::E_CS_COUPON_PLEASE_ENTER ) );
			}

		// Remove Coupon Codes
		} elseif ( isset( $_GET['remove_discounts'] ) ) {

			$colorshop->cart->remove_coupons( $_GET['remove_discounts'] );

		// Update Shipping
		} elseif ( ! empty( $_POST['calc_shipping'] ) && $colorshop->verify_nonce('cart') ) {

			$validation = $colorshop->validation();

			$colorshop->shipping->reset_shipping();
			$colorshop->customer->calculated_shipping( true );
			$country 	= $_POST['calc_shipping_country'];
			$state 		= $_POST['calc_shipping_state'];
			$postcode 	= $_POST['calc_shipping_postcode'];

			if ( $postcode && ! $validation->is_postcode( $postcode, $country ) ) {
				$colorshop->add_error( __( 'Please enter a valid postcode/ZIP.', 'colorshop' ) );
				$postcode = '';
			} elseif ( $postcode ) {
				$postcode = $validation->format_postcode( $postcode, $country );
			}

			if ( $country ) {

				// Update customer location
				$colorshop->customer->set_location( $country, $state, $postcode );
				$colorshop->customer->set_shipping_location( $country, $state, $postcode );
				$colorshop->add_message(  __( 'Shipping costs updated.', 'colorshop' ) );

			} else {

				$colorshop->customer->set_to_base();
				$colorshop->customer->set_shipping_to_base();
				$colorshop->add_message(  __( 'Shipping costs updated.', 'colorshop' ) );

			}

			do_action( 'colorshop_calculated_shipping' );
		}

		// Check cart items are valid
		do_action('colorshop_check_cart_items');

		// Calc totals
		$colorshop->cart->calculate_totals();

		if ( sizeof( $colorshop->cart->get_cart() ) == 0 )
			colorshop_get_template( 'cart/cart-empty.php' );
		else
			colorshop_get_template( 'cart/cart.php' );

	}
}