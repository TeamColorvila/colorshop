<?php
/**
 * Customer Reset Password email
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates/Emails/Plain
 * @version     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo __( 'Someone requested that the password be reset for the following account:', 'colorshop' ) . "\r\n\r\n";
echo network_home_url( '/' ) . "\r\n\r\n";
echo sprintf(__( 'Username: %s', 'colorshop' ), $user_login) . "\r\n\r\n";
echo __( 'If this was a mistake, just ignore this email and nothing will happen.', 'colorshop' ) . "\r\n\r\n";
echo __( 'To reset your password, visit the following address:', 'colorshop' ) . "\r\n\r\n";

echo get_permalink( colorshop_get_page_id( 'lost_password' ) ) . sprintf( '?key=%s&login=%s', $reset_key, $user_login ) . "\r\n";

echo "\n****************************************************\n\n";

echo apply_filters( 'colorshop_email_footer_text', get_option( 'colorshop_email_footer_text' ) );