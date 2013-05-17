<?php
/**
 * Order tracking form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop, $post;
?>

<form action="<?php echo esc_url( get_permalink($post->ID) ); ?>" method="post" class="track_order">

	<p><?php _e( 'To track your order please enter your Order ID in the box below and press return. This was given to you on your receipt and in the confirmation email you should have received.', 'colorshop' ); ?></p>

	<p class="form-row form-row-first"><label for="orderid"><?php _e( 'Order ID', 'colorshop' ); ?></label> <input class="input-text" type="text" name="orderid" id="orderid" placeholder="<?php _e( 'Found in your order confirmation email.', 'colorshop' ); ?>" /></p>
	<p class="form-row form-row-last"><label for="order_email"><?php _e( 'Billing Email', 'colorshop' ); ?></label> <input class="input-text" type="text" name="order_email" id="order_email" placeholder="<?php _e( 'Email you used during checkout.', 'colorshop' ); ?>" /></p>
	<div class="clear"></div>

	<p class="form-row"><input type="submit" class="button" name="track" value="<?php _e( 'Track', 'colorshop' ); ?>" /></p>
	<?php $colorshop->nonce_field('order_tracking') ?>

</form>