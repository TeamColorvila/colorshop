<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * ColorShop Payment Gateway class
 *
 * Extended by individual payment gateways to handle payments.
 *
 * @class 		CS_Payment_Gateway
 * @extends		CS_Settings_API
 * @version		1.0.0
 * @package		ColorShop/Abstracts
 * @category	Abstract Class
 * @author 		ColorVila
 */
abstract class CS_Payment_Gateway extends CS_Settings_API {

	/** @var string Payment method ID. */
	var $id;

	/** @var string Payment method title. */
	var $title;

	/** @var string Chosen payment method id. */
	var $chosen;

	/** @var bool True if the gateway shows fields on the checkout. */
	var $has_fields;

	/** @var array Array of countries this gateway is allowed for. */
	var $countries;

	/** @var string Available for all counties or specific. */
	var $availability;

	/** @var bool True if the method is enabled. */
	var $enabled;

	/** @var string Icon for the gateway. */
	var $icon;

	/** @var string Description for the gateway. */
	var $description;

	/** @var array Array of supported features. */
	var $supports		= array( 'products' );

	/**
	 * Get the return url (thank you page)
	 *
	 * @access public
	 * @param string $order (default: '')
	 * @return string
	 */
	function get_return_url( $order = '' ) {

		$thanks_page_id = colorshop_get_page_id('thanks');
		if ( $thanks_page_id ) :
			$return_url = get_permalink($thanks_page_id);
		else :
			$return_url = home_url();
		endif;

		if ( $order ) :
			$return_url = add_query_arg( 'key', $order->order_key, add_query_arg( 'order', $order->id, $return_url ) );
		endif;

		if ( is_ssl() || get_option('colorshop_force_ssl_checkout') == 'yes' )
			$return_url = str_replace( 'http:', 'https:', $return_url );

		return apply_filters( 'colorshop_get_return_url', $return_url );
	}


	/**
	 * Check If The Gateway Is Available For Use
	 *
	 * @access public
	 * @return bool
	 */
	function is_available() {
		if ( $this->enabled == "yes" )
			return true;
	}


	/**
	 * has_fields function.
	 *
	 * @access public
	 * @return bool
	 */
	function has_fields() {
		return $this->has_fields ? true : false;
	}


	/**
	 * Return the gateways title
	 *
	 * @access public
	 * @return string
	 */
	function get_title() {
		return apply_filters( 'colorshop_gateway_title', $this->title, $this->id );
	}


	/**
	 * Return the gateways description
	 *
	 * @access public
	 * @return string
	 */
	function get_description() {
		return apply_filters( 'colorshop_gateway_description', $this->description, $this->id );
	}


	/**
	 * get_icon function.
	 *
	 * @access public
	 * @return string
	 */
	function get_icon() {
		global $colorshop;

		$icon = $this->icon ? '<img src="' . $colorshop->force_ssl( $this->icon ) . '" alt="' . $this->title . '" />' : '';

		return apply_filters( 'colorshop_gateway_icon', $icon, $this->id );
	}


	/**
	 * Set As Current Gateway.
	 *
	 * Set this as the current gateway.
	 *
	 * @access public
	 * @return void
	 */
	function set_current() {
		$this->chosen = true;
	}


	/**
	 * Process Payment
	 *
	 * Process the payment. Override this in your gateway.
	 *
	 * @access public
	 * @return void
	 */
	function process_payment() {}


	/**
	 * Validate Frontend Fields
	 *
	 * Validate payment fields on the frontend.
	 *
	 * @access public
	 * @return bool
	 */
	function validate_fields() { return true; }


    /**
     * If There are no payment fields show the description if set.
     * Override this in your gateway if you have some.
     *
     * @access public
     * @return void
     */
    function payment_fields() {
        if ( $description = $this->get_description() )
        	echo wpautop( wptexturize( $description ) );
    }


	/**
	 * Check if a gateway supports a given feature.
	 *
	 * Gateways should override this to declare support (or lack of support) for a feature.
	 * For backward compatibility, gateways support 'products' by default, but nothing else.
	 *
	 * @access public
	 * @param $feature string The name of a feature to test support for.
	 * @return bool True if the gateway supports the feature, false otherwise.
	 * @since 1.5.7
	 */
	function supports( $feature ) {
		return apply_filters( 'colorshop_payment_gateway_supports', in_array( $feature, $this->supports ) ? true : false, $feature, $this );
	}

}