<?php
/**
 * Checkout coupon form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

if ( ! $colorshop->cart->coupons_enabled() )
	return;

$info_message = apply_filters('colorshop_checkout_coupon_message', __( 'Have a coupon?', 'colorshop' ));
?>

<p class="colorshop-info"><?php echo $info_message; ?> <a href="#" class="showcoupon"><?php _e( 'Click here to enter your code', 'colorshop' ); ?></a></p>

<form class="checkout_coupon" method="post" style="display:none">

	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'colorshop' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'colorshop' ); ?>" />
	</p>

	<div class="clear"></div>
</form>