<?php
/**
 * Customer new account email
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates/Emails
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'colorshop_email_header', $email_heading ); ?>

<p><?php printf(__("Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'colorshop'), esc_html( $blogname ), esc_html( $user_login ) ); ?></p>

<p><?php printf(__( 'You can access your account area here: %s.', 'colorshop' ), get_permalink(colorshop_get_page_id('myaccount'))); ?></p>

<?php do_action( 'colorshop_email_footer' ); ?>