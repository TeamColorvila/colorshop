<?php
/**
 * Shipping Methods Display
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

global $colorshop;

// If at least one shipping method is available
if ( $available_methods ) {

	// Prepare text labels with price for each shipping method
	foreach ( $available_methods as $method ) {
		$method->full_label = $method->label;

		if ( $method->cost > 0 ) {
			if ( $colorshop->cart->tax_display_cart == 'excl' ) {
				$method->full_label .= ': ' . colorshop_price( $method->cost );
				if ( $method->get_shipping_tax() > 0 && $colorshop->cart->prices_include_tax ) {
					$method->full_label .= ' <small>' . $colorshop->countries->ex_tax_or_vat() . '</small>';
				}
			} else {
				$method->full_label .= ': ' . colorshop_price( $method->cost + $method->get_shipping_tax() );
				if ( $method->get_shipping_tax() > 0 && ! $colorshop->cart->prices_include_tax ) {
					$method->full_label .= ' <small>' . $colorshop->countries->inc_tax_or_vat() . '</small>';
				}
			}
		} elseif ( $method->id !== 'free_shipping' ) {
			$method->full_label .= ' (' . __( 'Free', 'colorshop' ) . ')';
		}
		$method->full_label = apply_filters( 'colorshop_cart_shipping_method_full_label', $method->full_label, $method );
	}

	// Print a single available shipping method as plain text
	if ( 1 === count( $available_methods ) ) {

		echo wp_kses_post( $method->full_label ) . '<input type="hidden" name="shipping_method" id="shipping_method" value="' . esc_attr( $method->id ) . '" />';

	// Show select boxes for methods
	} elseif ( get_option('colorshop_shipping_method_format') == 'select' ) {

		echo '<select name="shipping_method" id="shipping_method">';

		foreach ( $available_methods as $method )
			echo '<option value="' . esc_attr( $method->id ) . '" ' . selected( $method->id, $colorshop->session->chosen_shipping_method, false ) . '>' . wp_kses_post( $method->full_label ) . '</option>';

		echo '</select>';

	// Show radio buttons for methods
	} else {

		echo '<ul id="shipping_method">';

		foreach ( $available_methods as $method )
			echo '<li><input type="radio" name="shipping_method" id="shipping_method_' . sanitize_title( $method->id ) . '" value="' . esc_attr( $method->id ) . '" ' . checked( $method->id, $colorshop->session->chosen_shipping_method, false) . ' /> <label for="shipping_method_' . sanitize_title( $method->id ) . '">' . wp_kses_post( $method->full_label ) . '</label></li>';

		echo '</ul>';
	}

// No shipping methods are available
} else {

	if ( ! $colorshop->customer->get_shipping_country() || ! $colorshop->customer->get_shipping_state() || ! $colorshop->customer->get_shipping_postcode() ) {

		echo '<p>' . __( 'Please fill in your details to see available shipping methods.', 'colorshop' ) . '</p>';

	} else {

		$customer_location = $colorshop->countries->countries[ $colorshop->customer->get_shipping_country() ];

		echo apply_filters( 'colorshop_no_shipping_available_html',
			'<p>' .
			sprintf( __( 'Sorry, it seems that there are no available shipping methods for your location (%s).', 'colorshop' ) . ' ' . __( 'If you require assistance or wish to make alternate arrangements please contact us.', 'colorshop' ), $customer_location ) .
			'</p>'
		);

	}

}