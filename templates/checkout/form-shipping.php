<?php
/**
 * Checkout shipping information form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;
?>

<?php if ( ( $colorshop->cart->needs_shipping() || get_option('colorshop_require_shipping_address') == 'yes' ) && ! $colorshop->cart->ship_to_billing_address_only() ) : ?>

	<?php
		if ( empty( $_POST ) ) :

			$shiptobilling = (get_option('colorshop_ship_to_same_address')=='yes') ? 1 : 0;
			$shiptobilling = apply_filters('colorshop_shiptobilling_default', $shiptobilling);

		else :

			$shiptobilling = $checkout->get_value('shiptobilling');

		endif;
	?>

	<p class="form-row" id="shiptobilling">
		<input id="shiptobilling-checkbox" class="input-checkbox" <?php checked($shiptobilling, 1); ?> type="checkbox" name="shiptobilling" value="1" />
		<label for="shiptobilling-checkbox" class="checkbox"><?php _e( 'Ship to billing address?', 'colorshop' ); ?></label>
	</p>	

	<div class="shipping_address">
	
		<h3><?php _e( 'Shipping Address', 'colorshop' ); ?></h3>

		<?php do_action('colorshop_before_checkout_shipping_form', $checkout); ?>

		<?php foreach ($checkout->checkout_fields['shipping'] as $key => $field) : ?>

			<?php colorshop_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

		<?php do_action('colorshop_after_checkout_shipping_form', $checkout); ?>

	</div>

<?php endif; ?>

<?php do_action('colorshop_before_order_notes', $checkout); ?>

<?php if (get_option('colorshop_enable_order_comments')!='no') : ?>

	<?php if ($colorshop->cart->ship_to_billing_address_only()) : ?>

		<h3><?php _e( 'Additional Information', 'colorshop' ); ?></h3>

	<?php endif; ?>

	<?php foreach ($checkout->checkout_fields['order'] as $key => $field) : ?>

		<?php colorshop_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

	<?php endforeach; ?>

<?php endif; ?>

<?php do_action('colorshop_after_order_notes', $checkout); ?>