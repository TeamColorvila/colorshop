<?php
/**
 * Change Password Shortcode
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/Change_Password
 * @version     1.0.0
 */
class CS_Shortcode_Change_Password {

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

		if ( ! is_user_logged_in() ) return;

		colorshop_get_template( 'myaccount/form-change-password.php' );
	}
}