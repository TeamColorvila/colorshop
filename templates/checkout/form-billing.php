<?php
/**
 * Checkout billing information form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;
?>

<?php if (! is_user_logged_in()) : ?>

<?php if ( $colorshop->cart->ship_to_billing_address_only() && $colorshop->cart->needs_shipping() ) : ?>

	<h3><?php _e( 'Billing &amp; Shipping', 'colorshop' ); ?></h3>

<?php else : ?>

	<h3><?php _e( 'Billing Address', 'colorshop' ); ?></h3>

<?php endif; ?>
<?php endif; ?>


<?php do_action('colorshop_before_checkout_billing_form', $checkout ); ?>

<?php foreach ($checkout->checkout_fields['billing'] as $key => $field) : ?>

	<?php colorshop_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

<?php endforeach; ?>

<?php do_action('colorshop_after_checkout_billing_form', $checkout ); ?>
