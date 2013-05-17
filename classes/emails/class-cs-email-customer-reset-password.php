<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Customer Reset Password
 *
 * An email sent to the customer when they reset their password.
 *
 * @class 		CS_Email_Customer_Reset_Password
 * @version		1.0.0
 * @package		ColorShop/Classes/Emails
 * @author 		ColorVila
 * @extends 	CS_Email
 */
class CS_Email_Customer_Reset_Password extends CS_Email {

	/** @var string */
	var $user_login;

	/** @var string */
	var $user_email;

	/** @var string */
	var $reset_key;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {

		$this->id 				= 'customer_reset_password';
		$this->title 			= __( 'Reset password', 'colorshop' );
		$this->description		= __( 'Customer reset password emails are sent when a customer resets their password.', 'colorshop' );

		$this->template_html 	= 'emails/customer-reset-password.php';
		$this->template_plain 	= 'emails/plain/customer-reset-password.php';

		$this->subject 			= __( 'Password Reset for {blogname}', 'colorshop');
		$this->heading      	= __( 'Password Reset Instructions', 'colorshop');

		// Trigger
		add_action( 'colorshop_reset_password_notification', array( $this, 'trigger' ), 10, 2 );

		// Call parent constructor
		parent::__construct();
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $user_login = '', $reset_key = '' ) {
		global $colorshop;
		if ( $user_login && $reset_key ) {
			$this->object 		= get_user_by( 'login', $user_login );

			$this->user_login 	= $user_login;
			$this->reset_key		= $reset_key;
			$this->user_email 	= stripslashes( $this->object->user_email );
			$this->recipient	= $this->user_email;
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
			'email_heading' => $this->get_heading(),
			'user_login' 	=> $this->user_login,
			'reset_key'		=> $this->reset_key,
			'blogname'		=> $this->get_blogname()
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
			'email_heading' => $this->get_heading(),
			'user_login' 	=> $this->user_login,
			'reset_key'		=> $this->reset_key,
			'blogname'		=> $this->get_blogname()
		) );
		return ob_get_clean();
	}
}