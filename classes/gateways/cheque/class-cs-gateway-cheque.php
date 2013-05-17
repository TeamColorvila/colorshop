<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Cheque Payment Gateway
 *
 * Provides a Cheque Payment Gateway, mainly for testing purposes.
 *
 * @class 		CS_Gateway_Cheque
 * @extends		CS_Payment_Gateway
 * @version		1.0.0
 * @package		ColorShop/Classes/Payment
 * @author 		ColorVila
 */
class CS_Gateway_Cheque extends CS_Payment_Gateway {

    /**
     * Constructor for the gateway.
     *
     * @access public
     * @return void
     */
	public function __construct() {
        $this->id				= 'cheque';
        $this->icon 			= apply_filters('colorshop_cheque_icon', '');
        $this->has_fields 		= false;
        $this->method_title     = __( 'Cheque', 'colorshop' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );

		// Actions
		add_action( 'colorshop_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    	add_action( 'colorshop_thankyou_cheque', array( $this, 'thankyou_page' ) );

    	// Customer Emails
    	add_action( 'colorshop_email_before_order_table', array( $this, 'email_instructions' ), 10, 2 );
    }


    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {

    	$this->form_fields = array(
			'enabled' => array(
							'title' => __( 'Enable/Disable', 'colorshop' ),
							'type' => 'checkbox',
							'label' => __( 'Enable Cheque Payment', 'colorshop' ),
							'default' => 'yes'
						),
			'title' => array(
							'title' => __( 'Title', 'colorshop' ),
							'type' => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'colorshop' ),
							'default' => __( 'Cheque Payment', 'colorshop' ),
							'desc_tip'      => true,
						),
			'description' => array(
							'title' => __( 'Customer Message', 'colorshop' ),
							'type' => 'textarea',
							'description' => __( 'Let the customer know the payee and where they should be sending the cheque to and that their order won\'t be shipping until you receive it.', 'colorshop' ),
							'default' => __( 'Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'colorshop' )
						)
			);

    }


	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @access public
	 * @return void
	 */
	public function admin_options() {

    	?>
    	<h3><?php _e( 'Cheque Payment', 'colorshop' ); ?></h3>
    	<p><?php _e( 'Allows cheque payments. Why would you take cheques in this day and age? Well you probably wouldn\'t but it does allow you to make test purchases for testing order emails and the \'success\' pages etc.', 'colorshop' ); ?></p>
    	<table class="form-table">
    	<?php
    		// Generate the HTML For the settings form.
    		$this->generate_settings_html();
    	?>
		</table><!--/.form-table-->
    	<?php
    }


    /**
     * Output for the order received page.
     *
     * @access public
     * @return void
     */
	function thankyou_page() {
		if ( $description = $this->get_description() )
        	echo wpautop( wptexturize( $description ) );
	}


    /**
     * Add content to the CS emails.
     *
     * @access public
     * @param CS_Order $order
     * @param bool $sent_to_admin
     * @return void
     */
	function email_instructions( $order, $sent_to_admin ) {
    	if ( $sent_to_admin ) return;

    	if ( $order->status !== 'on-hold') return;

    	if ( $order->payment_method !== 'cheque') return;

		if ( $description = $this->get_description() )
        	echo wpautop( wptexturize( $description ) );
	}


    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
	function process_payment( $order_id ) {
		global $colorshop;

		$order = new CS_Order( $order_id );

		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status('on-hold', __( 'Awaiting cheque payment', 'colorshop' ));

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		$colorshop->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(colorshop_get_page_id('thanks'))))
		);

	}

}