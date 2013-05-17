<?php
/**
 * Customer new account email
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates/Emails/Plain
 * @version     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo sprintf( __( "Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'colorshop' ), $blogname, $user_login ) . "\n\n";

echo sprintf(__( 'You can access your account area here: %s.', 'colorshop' ), get_permalink( colorshop_get_page_id( 'myaccount' ) ) ) . "\n\n";

echo "\n****************************************************\n\n";

echo apply_filters( 'colorshop_email_footer_text', get_option( 'colorshop_email_footer_text' ) );