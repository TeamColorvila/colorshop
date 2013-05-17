<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Google Analytics Integration
 *
 * Allows tracking code to be inserted into store pages.
 *
 * @class 		CS_Google_Analytics
 * @extends		CS_Integration
 * @version		1.0.0
 * @package		ColorShop/Classes/Integrations
 * @author 		ColorVila
 */
class CS_Google_Analytics extends CS_Integration {

	/**
	 * Init and hook in the integration.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
        $this->id					= 'google_analytics';
        $this->method_title     	= __( 'Google Analytics', 'colorshop' );
        $this->method_description	= __( 'Google Analytics is a free service offered by Google that generates detailed statistics about the visitors to a website.', 'colorshop' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->ga_id 							= $this->get_option( 'ga_id' );
		$this->ga_standard_tracking_enabled 	= $this->get_option( 'ga_standard_tracking_enabled' );
		$this->ga_ecommerce_tracking_enabled 	= $this->get_option( 'ga_ecommerce_tracking_enabled' );
		$this->ga_event_tracking_enabled		= $this->get_option( 'ga_event_tracking_enabled' );

		// Actions
		add_action( 'colorshop_update_options_integration_google_analytics', array( $this, 'process_admin_options') );

		// Tracking code
		add_action( 'wp_footer', array( $this, 'google_tracking_code' ) );
		add_action( 'colorshop_thankyou', array( $this, 'ecommerce_tracking_code' ) );

		// Event tracking code
		add_action( 'colorshop_after_add_to_cart_button', array( $this, 'add_to_cart' ) );
		add_action( 'colorshop_after_shop_loop', array( $this, 'loop_add_to_cart' ) );
    }


    /**
     * Initialise Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {

    	$this->form_fields = array(
			'ga_id' => array(
				'title' 			=> __( 'Google Analytics ID', 'colorshop' ),
				'description' 		=> __( 'Log into your google analytics account to find your ID. e.g. <code>UA-XXXXX-X</code>', 'colorshop' ),
				'type' 				=> 'text',
		    	'default' 			=> get_option('colorshop_ga_id') // Backwards compat
			),
			'ga_standard_tracking_enabled' => array(
				'title' 			=> __( 'Tracking code', 'colorshop' ),
				'label' 			=> __( 'Add tracking code to your site\'s footer. You don\'t need to enable this if using a 3rd party analytics plugin.', 'colorshop' ),
				'type' 				=> 'checkbox',
				'checkboxgroup'		=> 'start',
				'default' 			=> get_option('colorshop_ga_standard_tracking_enabled') ? get_option('colorshop_ga_standard_tracking_enabled') : 'no'  // Backwards compat
			),
			'ga_ecommerce_tracking_enabled' => array(
				'label' 			=> __( 'Add eCommerce tracking code to the thankyou page', 'colorshop' ),
				'type' 				=> 'checkbox',
				'checkboxgroup'		=> '',
				'default' 			=> get_option('colorshop_ga_ecommerce_tracking_enabled') ? get_option('colorshop_ga_ecommerce_tracking_enabled') : 'no'  // Backwards compat
			),
			'ga_event_tracking_enabled' => array(
				'label' 			=> __( 'Add event tracking code for add to cart actions', 'colorshop' ),
				'type' 				=> 'checkbox',
				'checkboxgroup'		=> 'end',
				'default' 			=> 'no'
			)
		);

    } // End init_form_fields()


	/**
	 * Google Analytics standard tracking
	 *
	 * @access public
	 * @return void
	 */
	function google_tracking_code() {
		global $colorshop;

		if ( is_admin() || current_user_can('manage_options') || $this->ga_standard_tracking_enabled == "no" ) return;

		$tracking_id = $this->ga_id;

		if ( ! $tracking_id ) return;

		$loggedin 	= ( is_user_logged_in() ) ? 'yes' : 'no';
		if ( is_user_logged_in() ) {
			$user_id 		= get_current_user_id();
			$current_user 	= get_user_by('id', $user_id);
			$username 		= $current_user->user_login;
		} else {
			$user_id 		= '';
			$username 		= __( 'Guest', 'colorshop' );
		}

		echo "<script type='text/javascript'>

			var _gaq = _gaq || [];
			_gaq.push(
				['_setAccount', '" . esc_js( $tracking_id ) . "'],
				['_setCustomVar', 1, 'logged-in', '" . $loggedin . "', 1],
				['_setCustomVar', 2, 'user-id', '" . $user_id . "', 1],
				['_setCustomVar', 3, 'username', '" . $username . "', 1],
				['_trackPageview']
			);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();

		</script>";
	}


	/**
	 * Google Analytics eCommerce tracking
	 *
	 * @access public
	 * @param mixed $order_id
	 * @return void
	 */
	function ecommerce_tracking_code( $order_id ) {
		global $colorshop;

		if ( $this->ga_ecommerce_tracking_enabled == "no" || current_user_can('manage_options') || get_post_meta( $order_id, '_ga_tracked', true ) == 1 )
			return;

		$tracking_id = $this->ga_id;

		if ( ! $tracking_id ) return;

		// Doing eCommerce tracking so unhook standard tracking from the footer
		remove_action( 'wp_footer', array( $this, 'google_tracking_code' ) );

		// Get the order and output tracking code
		$order = new CS_Order( $order_id );

		$loggedin = is_user_logged_in() ? 'yes' : 'no';

		if ( is_user_logged_in() ) {
			$user_id 		= get_current_user_id();
			$current_user 	= get_user_by('id', $user_id);
			$username 		= $current_user->user_login;
		} else {
			$user_id 		= '';
			$username 		= __( 'Guest', 'colorshop' );
		}

		$code = "
			var _gaq = _gaq || [];

			_gaq.push(
				['_setAccount', '" . esc_js( $tracking_id ) . "'],
				['_setCustomVar', 1, 'logged-in', '" . esc_js( $loggedin ) . "', 1],
				['_setCustomVar', 2, 'user-id', '" . esc_js( $user_id ) . "', 1],
				['_setCustomVar', 3, 'username', '" . esc_js( $username ) . "', 1],
				['_trackPageview']
			);

			_gaq.push(['_addTrans',
				'" . esc_js( $order_id ) . "',           		// order ID - required
				'" . esc_js( get_bloginfo( 'name' ) ) . "',  	// affiliation or store name
				'" . esc_js( $order->get_total() ) . "',   	// total - required
				'" . esc_js( $order->get_total_tax() ) . "',  // tax
				'" . esc_js( $order->get_shipping() ) . "',	// shipping
				'" . esc_js( $order->billing_city ) . "',     // city
				'" . esc_js( $order->billing_state ) . "',    // state or province
				'" . esc_js( $order->billing_country ) . "'   // country
			]);
		";

		// Order items
		if ( $order->get_items() ) {
			foreach ( $order->get_items() as $item ) {
				$_product = $order->get_product_from_item( $item );

				$code .= "_gaq.push(['_addItem',";
				$code .= "'" . esc_js( $order_id ) . "',";
				$code .= "'" . esc_js( $_product->get_sku() ? __( 'SKU:', 'colorshop' ) . ' ' . $_product->get_sku() : $_product->id ) . "',";
				$code .= "'" . esc_js( $item['name'] ) . "',";

				if ( isset( $_product->variation_data ) ) {

					$code .= "'" . esc_js( colorshop_get_formatted_variation( $_product->variation_data, true ) ) . "',";

				} else {
					$out = array();
					$categories = get_the_terms($_product->id, 'product_cat');
					if ( $categories ) {
						foreach ( $categories as $category ){
							$out[] = $category->name;
						}
					}
					$code .= "'" . esc_js( join( "/", $out) ) . "',";
				}

				$code .= "'" . esc_js( $order->get_item_total( $item ) ) . "',";
				$code .= "'" . esc_js( $item['qty'] ) . "'";
				$code .= "]);";
			}
		}

		$code .= "
			_gaq.push(['_trackTrans']); 					// submits transaction to the Analytics servers

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		";

		echo '<script type="text/javascript">' . $code . '</script>';

		update_post_meta( $order_id, '_ga_tracked', 1 );
	}


	/**
	 * Google Analytics event tracking for single product add to cart
	 *
	 * @access public
	 * @return void
	 */
	function add_to_cart() {

		if ( $this->disable_tracking( $this->ga_event_tracking_enabled ) ) return;
		if ( ! is_single() ) return;

		global $product;

		$parameters = array();
		// Add single quotes to allow jQuery to be substituted into _trackEvent parameters
		$parameters['category'] = "'" . __( 'Products', 'colorshop' ) . "'";
		$parameters['action'] = "'" . __( 'Add to cart', 'colorshop' ) . "'";
		$parameters['label'] = "'" . esc_js( $product->get_sku() ? __('SKU:', 'colorshop') . ' ' . $product->get_sku() : "#" . $product->id ) . "'";

		$this->event_tracking_code( $parameters, '.single_add_to_cart_button' );
	}


	/**
	 * Google Analytics event tracking for loop add to cart
	 *
	 * @access public
	 * @return void
	 */
	function loop_add_to_cart() {

		if ( $this->disable_tracking( $this->ga_event_tracking_enabled ) ) return;

		$parameters = array();
		// Add single quotes to allow jQuery to be substituted into _trackEvent parameters
		$parameters['category'] = "'" . __( 'Products', 'colorshop' ) . "'";
		$parameters['action'] 	= "'" . __( 'Add to Cart', 'colorshop' ) . "'";
		$parameters['label'] 	= "($(this).data('product_sku')) ? ('SKU: ' + $(this).data('product_sku')) : ('#' + $(this).data('product_id'))"; // Product SKU or ID

		$this->event_tracking_code( $parameters, '.add_to_cart_button:not(.product_type_variable, .product_type_grouped)' );
	}


	/**
	 * Google Analytics event tracking for loop add to cart
	 *
	 * @access private
	 * @param mixed $parameters associative array of _trackEvent parameters
	 * @param mixed $selector jQuery selector for binding click event
	 * @return void
	 */
	private function event_tracking_code( $parameters, $selector ) {
		global $colorshop;

		$parameters = apply_filters( 'colorshop_ga_event_tracking_parameters', $parameters );

		$colorshop->add_inline_js("
			$('" . $selector . "').click(function() {
				" . sprintf( "_gaq.push(['_trackEvent', %s, %s, %s]);", $parameters['category'], $parameters['action'], $parameters['label'] ) . "
			});
		");
	}


	/**
	 * Check if tracking is disabled
	 *
	 * @access private
	 * @param mixed $type
	 * @return bool
	 */
	private function disable_tracking( $type ) {

		if ( is_admin() || current_user_can( 'manage_options' ) || ( ! $this->ga_id ) || 'no' == $type ) return true;

	}

}


/**
 * Add the integration to ColorShop.
 *
 * @package		ColorShop/Classes/Integrations
 * @access public
 * @param array $integrations
 * @return array
 */
function add_google_analytics_integration( $integrations ) {
	$integrations[] = 'CS_Google_Analytics';
	return $integrations;
}

add_filter('colorshop_integrations', 'add_google_analytics_integration' );
