<?php
/**
 * My Account Shortcodes
 *
 * Shows the 'my account' section where the customer can view past orders and update their information.
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/My_Account
 * @version     1.0.0
 */

class CS_Shortcode_My_Account {

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

		$colorshop->nocache();

		if ( ! is_user_logged_in() ) {

			colorshop_get_template( 'myaccount/form-login.php' );

		} else {

			extract( shortcode_atts( array(
		    	'order_count' => 5
			), $atts ) );

			colorshop_get_template( 'myaccount/my-account.php', array(
				'current_user' 	=> get_user_by( 'id', get_current_user_id() ),
				'order_count' 	=> 'all' == $order_count ? -1 : $order_count
			) );

		}
	}
}