<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Mijireh Checkout Gateway
 *
 * Provides ColorShop with Mijireh Checkout integration.
 *
 * @class 		CS_Gateway_Mijireh
 * @extends		CS_Payment_Gateway
 * @version		1.0.3
 * @package		ColorShop/Classes/Payment
 * @author 		Mijireh
 */
class CS_Gateway_Mijireh extends CS_Payment_Gateway {

	/** @var string Access key for mijireh */
	var $access_key;

    /**
     * Constructor for the gateway.
     *
     * @access public
     * @return void
     */
	public function __construct() {
		global $colorshop;

		$this->id 			= 'mijireh_checkout';
		$this->method_title = __( 'Mijireh Checkout', 'colorshop' );
		$this->icon 		= apply_filters( 'colorshop_mijireh_checkout_icon', $colorshop->plugin_url() . '/classes/gateways/mijireh/assets/images/credit_cards.png' );
		$this->has_fields = false;

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->access_key 	= $this->get_option( 'access_key' );
		$this->title 		= $this->get_option( 'title' );
		$this->description 	= $this->get_option( 'description' );

		if ( $this->enabled && is_admin() ) {
			$this->install_slurp_page();
		}

		// Save options
		add_action( 'colorshop_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		// Payment listener/API hook
		add_action( 'colorshop_api_cs_gateway_mijireh', array( $this, 'mijireh_notification' ) );
	}

	/**
	 * install_slurp_page function.
	 *
	 * @access public
	 */
	public function install_slurp_page() {
	    $slurp_page_installed = get_option( 'slurp_page_installed', false );
		if ( $slurp_page_installed != 1 ) {
			if( ! get_page_by_path( 'mijireh-secure-checkout' ) ) {
				$page = array(
					'post_title' 		=> 'Mijireh Secure Checkout',
					'post_name' 		=> 'mijireh-secure-checkout',
					'post_parent' 		=> 0,
					'post_status' 		=> 'private',
					'post_type' 		=> 'page',
					'comment_status' 	=> 'closed',
					'ping_status' 		=> 'closed',
					'post_content' 		=> "<h1>Checkout</h1>\n\n{{mj-checkout-form}}",
				);
				wp_insert_post( $page );
			}
			update_option( 'slurp_page_installed', 1 );
		}
    }

	/**
	 * mijireh_notification function.
	 *
	 * @access public
	 * @return void
	 */
	public function mijireh_notification() {
	   global $colorshop;

		$this->init_mijireh();

		try {
		      $mj_order 	= new Mijireh_Order( esc_attr( $_GET['order_number'] ) );
		      $cs_order_id 	= $mj_order->get_meta_value( 'cs_order_id' );
		      $cs_order 	= new CS_Order( absint( $cs_order_id ) );

		      // Mark order complete
		      $cs_order->payment_complete();

		      // Empty cart and clear session
		      $colorshop->cart->empty_cart();

		      wp_redirect( $this->get_return_url( $cs_order ) );
		      exit;

		} catch (Mijireh_Exception $e) {

			$colorshop->add_error( __( 'Mijireh error:', 'colorshop' ) . $e->getMessage() );

		}
	}


    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title' => __( 'Enable/Disable', 'colorshop' ),
				'type' => 'checkbox',
				'label' => __( 'Enable Mijireh Checkout', 'colorshop' ),
				'default' => 'no'
				),
			'access_key' => array(
				'title' => __( 'Access Key', 'colorshop' ),
				'type' => 'text',
				'description' => __( 'The Mijireh access key for your store.', 'colorshop' ),
				'default' => '',
				'desc_tip'      => true,
				),
			'title' => array(
				'title' => __( 'Title', 'colorshop' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'colorshop' ),
				'default' => __( 'Credit Card', 'colorshop' ),
				'desc_tip'      => true,
				),
			'description' => array(
				'title' => __( 'Description', 'colorshop' ),
				'type' => 'textarea',
				'default' => __( 'Pay securely with your credit card.', 'colorshop' ),
				'description' => __( 'This controls the description which the user sees during checkout.', 'colorshop' ),
				),
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
		<h3><?php _e( 'Mijireh Checkout', 'colorshop' );?></h3>

		<?php if ( empty( $this->access_key ) ) : ?>
			<div id="cs_get_started" class="mijireh">
				<span class="main"><?php _e( 'Get started with Mijireh Checkout', 'colorshop' ); ?></span>
				<span><a href="http://www.colorvila.com/docs/plugins/colorshop/user-guide/admin/how-to-setup-mijireh-with-colorshop/">Mijireh Checkout</a> <?php _e( 'provides a fully PCI Compliant, secure way to collect and transmit credit card data to your payment gateway while keeping you in control of the design of your site. Mijireh supports a wide variety of payment gateways: Stripe, Authorize.net, PayPal, eWay, SagePay, Braintree, PayLeap, and more.', 'colorshop' ); ?></span>

				<p><a href="http://secure.mijireh.com/signup" target="_blank" class="button button-primary"><?php _e( 'Join for free', 'colorshop' ); ?></a> <a href="http://www.colorvila.com/docs/plugins/colorshop/user-guide/admin/how-to-setup-mijireh-with-colorshop/" target="_blank" class="button"><?php _e( 'Learn more about ColorShop and Mijireh', 'colorshop' ); ?></a></p>

			</div>
		<?php else : ?>
			<p><a href="http://www.colorvila.com/docs/plugins/colorshop/user-guide/admin/how-to-setup-mijireh-with-colorshop/">Mijireh Checkout</a> <?php _e( 'provides a fully PCI Compliant, secure way to collect and transmit credit card data to your payment gateway while keeping you in control of the design of your site.', 'colorshop' ); ?></p>
		<?php endif; ?>

		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table><!--/.form-table-->
		<?php
  	}


    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    public function process_payment( $order_id ) {
		global $colorshop;

		$this->init_mijireh();

		$mj_order = new Mijireh_Order();
		$cs_order = new CS_Order( $order_id );

		// add items to order
		$items = $cs_order->get_items();

		foreach( $items as $item ) {
			$product = $cs_order->get_product_from_item( $item );

			if ( get_option( 'colorshop_prices_include_tax' ) == 'yes' ) {

				$mj_order->add_item( $item['name'], $cs_order->get_item_subtotal( $item, true, false ), $item['qty'], $product->get_sku() );

			} else {

				$mj_order->add_item( $item['name'], $cs_order->get_item_subtotal( $item, false, false ), $item['qty'], $product->get_sku() );

			}


		}

		// Handle fees
		$items = $cs_order->get_fees();

		foreach( $items as $item ) {
			$mj_order->add_item( $item['name'], $item['line_total'], 1, '' );
		}

		// add billing address to order
		$billing 					= new Mijireh_Address();
		$billing->first_name 		= $cs_order->billing_first_name;
		$billing->last_name 		= $cs_order->billing_last_name;
		$billing->street 			= $cs_order->billing_address_1;
		$billing->apt_suite 		= $cs_order->billing_address_2;
		$billing->city 				= $cs_order->billing_city;
		$billing->state_province 	= $cs_order->billing_state;
		$billing->zip_code 			= $cs_order->billing_postcode;
		$billing->country 			= $cs_order->billing_country;
		$billing->company 			= $cs_order->billing_company;
		$billing->phone 			= $cs_order->billing_phone;
		if ( $billing->validate() )
			$mj_order->set_billing_address( $billing );

		// add shipping address to order
		$shipping 					= new Mijireh_Address();
		$shipping->first_name 		= $cs_order->shipping_first_name;
		$shipping->last_name 		= $cs_order->shipping_last_name;
		$shipping->street 			= $cs_order->shipping_address_1;
		$shipping->apt_suite 		= $cs_order->shipping_address_2;
		$shipping->city 			= $cs_order->shipping_city;
		$shipping->state_province 	= $cs_order->shipping_state;
		$shipping->zip_code 		= $cs_order->shipping_postcode;
		$shipping->country 			= $cs_order->shipping_country;
		$shipping->company 			= $cs_order->shipping_company;
		if ( $shipping->validate() )
			$mj_order->set_shipping_address( $shipping );

		// set order name
		$mj_order->first_name 		= $cs_order->billing_first_name;
		$mj_order->last_name 		= $cs_order->billing_last_name;
		$mj_order->email 			= $cs_order->billing_email;

		// set order totals
		$mj_order->total 			= $cs_order->get_order_total();
		$mj_order->discount 		= $cs_order->get_total_discount();

		if ( get_option( 'colorshop_prices_include_tax' ) == 'yes' ) {
			$mj_order->shipping 		= $cs_order->get_shipping() + $cs_order->get_shipping_tax();
			$mj_order->show_tax			= false;
		} else {
			$mj_order->shipping 		= $cs_order->get_shipping();
			$mj_order->tax 				= $cs_order->get_total_tax();
		}

		// add meta data to identify colorshop order
		$mj_order->add_meta_data( 'cs_order_id', $order_id );

		// Set URL for mijireh payment notificatoin - use CS API
		$mj_order->return_url 		= str_replace( 'https:', 'http:', add_query_arg( 'cs-api', 'CS_Gateway_Mijireh', home_url( '/' ) ) );

		// Identify colorshop
		$mj_order->partner_id 		= 'color';

		try {
			$mj_order->create();
			$result = array(
				'result' => 'success',
				'redirect' => $mj_order->checkout_url
			);
			return $result;
		} catch (Mijireh_Exception $e) {
			$colorshop->add_error( __('Mijireh error:', 'colorshop' ) . $e->getMessage() );
		}
    }


	/**
	 * init_mijireh function.
	 *
	 * @access public
	 */
	public function init_mijireh() {
		if ( ! class_exists( 'Mijireh' ) ) {
	    	require_once 'includes/Mijireh.php';

	    	if ( ! isset( $this ) ) {
		    	$settings = get_option( 'colorshop_' . 'mijireh_checkout' . '_settings', null );
		    	$key = ! empty( $settings['access_key'] ) ? $settings['access_key'] : '';
	    	} else {
		    	$key = $this->access_key;
	    	}

	    	Mijireh::$access_key = $key;
	    }
	}


    /**
     * page_slurp function.
     *
     * @access public
     * @return void
     */
    public static function page_slurp() {

    	self::init_mijireh();

		$page 	= get_page( absint( $_POST['page_id'] ) );
		$url 	= get_permalink( $page->ID );
		wp_update_post( array( 'ID' => $page->ID, 'post_status' => 'publish' ) );
		$job_id = Mijireh::slurp( $url );
		wp_update_post( array( 'ID' => $page->ID, 'post_status' => 'private' ) );
		echo $job_id;
		die;
	}


    /**
     * add_page_slurp_meta function.
     *
     * @access public
     * @return void
     */
    public static function add_page_slurp_meta() {
    	global $colorshop;

    	if ( self::is_slurp_page() ) {
        	wp_enqueue_style( 'mijireh_css', $colorshop->plugin_url() . '/classes/gateways/mijireh/assets/css/mijireh.css' );
        	wp_enqueue_script( 'pusher', 'https://d3dy5gmtp8yhk7.cloudfront.net/1.11/pusher.min.js', null, false, true );
        	wp_enqueue_script( 'page_slurp', $colorshop->plugin_url() . '/classes/gateways/mijireh/assets/js/page_slurp.js', array('jquery'), false, true );

			add_meta_box(
				'slurp_meta_box', 		// $id
				'Mijireh Page Slurp', 	// $title
				array( 'CS_Gateway_Mijireh', 'draw_page_slurp_meta_box' ), // $callback
				'page', 	// $page
				'normal', 	// $context
				'high'		// $priority
			);
		}
    }


    /**
     * is_slurp_page function.
     *
     * @access public
     * @return void
     */
    public static function is_slurp_page() {
		global $post;
		$is_slurp = false;
		if ( isset( $post ) && is_object( $post ) ) {
			$content = $post->post_content;
			if ( strpos( $content, '{{mj-checkout-form}}') !== false ) {
				$is_slurp = true;
			}
		}
		return $is_slurp;
    }


    /**
     * draw_page_slurp_meta_box function.
     *
     * @access public
     * @param mixed $post
     * @return void
     */
    public static function draw_page_slurp_meta_box( $post ) {
    	global $colorshop;

    	self::init_mijireh();

		echo "<div id='mijireh_notice' class='mijireh-info alert-message info' data-alert='alert'>";
		echo    "<h2>Slurp your custom checkout page!</h2>";
		echo    "<p>Get the page designed just how you want and when you're ready, click the button below and slurp it right up.</p>";
		echo    "<div id='slurp_progress' class='meter progress progress-info progress-striped active' style='display: none;'><div id='slurp_progress_bar' class='bar' style='width: 20%;'>Slurping...</div></div>";
		echo    "<p><a href='#' id='page_slurp' rel=". $post->ID ." class='button-primary'>Slurp This Page!</a> ";
		echo    '<a class="nobold" href="' . Mijireh::preview_checkout_link() . '" id="view_slurp" target="_new">Preview Checkout Page</a></p>';
		echo  "</div>";
    }
}
