<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * New Order Email
 *
 * An email sent to the admin when a new order is received/paid for.
 *
 * @class 		CS_Email_New_Order
 * @version		1.0.0
 * @package		ColorShop/Classes/Emails
 * @author 		ColorVila
 * @extends 	CS_Email
 */
class CS_Email_New_Order extends CS_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id 				= 'new_order';
		$this->title 			= __( 'New order', 'colorshop' );
		$this->description		= __( 'New order emails are sent when an order is received/paid by a customer.', 'colorshop' );

		$this->heading 			= __( 'New customer order', 'colorshop' );
		$this->subject      	= __( '[{blogname}] New customer order ({order_number}) - {order_date}', 'colorshop' );

		$this->template_html 	= 'emails/admin-new-order.php';
		$this->template_plain 	= 'emails/plain/admin-new-order.php';

		// Triggers for this email
		add_action( 'colorshop_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
		add_action( 'colorshop_order_status_pending_to_completed_notification', array( $this, 'trigger' ) );
		add_action( 'colorshop_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ) );
		add_action( 'colorshop_order_status_failed_to_processing_notification', array( $this, 'trigger' ) );
		add_action( 'colorshop_order_status_failed_to_completed_notification', array( $this, 'trigger' ) );
		add_action( 'colorshop_order_status_failed_to_on-hold_notification', array( $this, 'trigger' ) );

		// Call parent constructor
		parent::__construct();

		// Other settings
		$this->recipient = $this->get_option( 'recipient' );

		if ( ! $this->recipient )
			$this->recipient = get_option( 'admin_email' );
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
			'recipient' => array(
				'title' 		=> __( 'Recipient(s)', 'colorshop' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'colorshop' ), esc_attr( get_option('admin_email') ) ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'subject' => array(
				'title' 		=> __( 'Subject', 'colorshop' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'colorshop' ), $this->subject ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading' => array(
				'title' 		=> __( 'Email Heading', 'colorshop' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'colorshop' ), $this->heading ),
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
					'plain'		 	=> __( 'Plain text', 'colorshop' ),
					'html' 			=> __( 'HTML', 'colorshop' ),
					'multipart' 	=> __( 'Multipart', 'colorshop' ),
				)
			)
		);
    }
}