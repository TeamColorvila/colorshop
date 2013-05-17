<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Customer Completed Order Email
 *
 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
 *
 * @class 		CS_Email_Customer_Completed_Order
 * @version		1.0.0
 * @package		ColorShop/Classes/Emails
 * @author 		ColorVila
 * @extends 	CS_Email
 */
class CS_Email_Customer_Completed_Order extends CS_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id 				= 'customer_completed_order';
		$this->title 			= __( 'Completed order', 'colorshop' );
		$this->description		= __( 'Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.', 'colorshop' );

		$this->heading 			= __( 'Your order is complete', 'colorshop' );
		$this->subject      	= __( 'Your {blogname} order from {order_date} is complete', 'colorshop' );

		$this->template_html 	= 'emails/customer-completed-order.php';
		$this->template_plain 	= 'emails/plain/customer-completed-order.php';

		// Triggers for this email
		add_action( 'colorshop_order_status_completed_notification', array( $this, 'trigger' ) );

		// Other settings
		$this->heading_downloadable = $this->get_option( 'heading_downloadable', __( 'Your order is complete - download your files', 'colorshop' ) );
		$this->subject_downloadable = $this->get_option( 'subject_downloadable', __( 'Your {blogname} order from {order_date} is complete - download your files', 'colorshop' ) );

		// Call parent constuctor
		parent::__construct();
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order_id ) {
		global $colorshop;

		if ( $order_id ) {
			$this->object 		= new CS_Order( $order_id );
			$this->recipient	= $this->object->billing_email;

			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( colorshop_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() )
			return;

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * get_subject function.
	 *
	 * @access public
	 * @return string
	 */
	function get_subject() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() )
			return apply_filters( 'colorshop_email_subject_customer_completed_order', $this->format_string( $this->subject_downloadable ), $this->object );
		else
			return apply_filters( 'colorshop_email_subject_customer_completed_order', $this->format_string( $this->subject ), $this->object );
	}

	/**
	 * get_heading function.
	 *
	 * @access public
	 * @return string
	 */
	function get_heading() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() )
			return apply_filters( 'colorshop_email_heading_customer_completed_order', $this->format_string( $this->heading_downloadable ), $this->object );
		else
			return apply_filters( 'colorshop_email_heading_customer_completed_order', $this->format_string( $this->heading ), $this->object );
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		colorshop_get_template( $this->template_html, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading()
		) );
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		colorshop_get_template( $this->template_plain, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading()
		) );
		return ob_get_clean();
	}

    /**
     * Initialise Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
    	$this->form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Enable/Disable', 'colorshop' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable this email notification', 'colorshop' ),
				'default' 		=> 'yes'
			),
			'subject' => array(
				'title' 		=> __( 'Subject', 'colorshop' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'colorshop' ), $this->subject ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading' => array(
				'title' 		=> __( 'Email Heading', 'colorshop' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'colorshop' ), $this->heading ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'subject_downloadable' => array(
				'title' 		=> __( 'Subject (downloadable)', 'colorshop' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'colorshop' ), $this->subject_downloadable ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading_downloadable' => array(
				'title' 		=> __( 'Email Heading (downloadable)', 'colorshop' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'colorshop' ), $this->heading_downloadable ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'email_type' => array(
				'title' 		=> __( 'Email type', 'colorshop' ),
				'type' 			=> 'select',
				'description' 	=> __( 'Choose which format of email to send.', 'colorshop' ),
				'default' 		=> 'html',
				'class'			=> 'email_type',
				'options'		=> array(
					'plain'	 	=> __( 'Plain text', 'colorshop' ),
					'html' 			=> __( 'HTML', 'colorshop' ),
					'multipart' 	=> __( 'Multipart', 'colorshop' ),
				)
			)
		);
    }
}