<?php
/**
 * My Account page
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$colorshop->show_messages(); ?>

<p class="myaccount_user">
	<?php
	printf(
		__( 'Hello, <strong>%s</strong>. From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">change your password</a>.', 'colorshop' ),
		$current_user->display_name,
		get_permalink( colorshop_get_page_id( 'change_password' ) )
	);
	?>
</p>

<?php do_action( 'colorshop_before_my_account' ); ?>

<?php colorshop_get_template( 'myaccount/my-downloads.php' ); ?>

<?php colorshop_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<?php colorshop_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'colorshop_after_my_account' ); ?>