<?php
/**
 * Checkout login form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in()  || ! $checkout->enable_signup ) return;

$info_message = apply_filters( 'colorshop_checkout_login_message', __( 'Returning customer?', 'colorshop' ) );
?>

<p class="colorshop-info"><?php echo esc_html( $info_message ); ?> <a href="#" class="showlogin"><?php _e( 'Click here to login', 'colorshop' ); ?></a></p>

<?php
	colorshop_login_form(
		array(
			'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'colorshop' ),
			'redirect' => get_permalink( colorshop_get_page_id( 'checkout') ),
			'hidden'   => true
		)
	);
?>