<?php
/**
 * Checkout Form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$colorshop->show_messages();

do_action( 'colorshop_before_checkout_form', $checkout );

$address_b = array();
foreach ($checkout->checkout_fields['billing'] as $key => $field) {	
	$address_b[$key] = $checkout->get_value( $key );
}

$address_s = array();
foreach ($checkout->checkout_fields['shipping'] as $key => $field) {
	$address_s[$key] = $checkout->get_value( $key );
}

//add_user_meta(get_current_user_id(), 'cs_extra_address', $meta_value, true);

$extra_address_id = get_user_meta(get_current_user_id(), 'cs_extra_address_id', true);
if (! empty($extra_address_id) && sizeof($extra_address_id) > 0) {
	foreach ($extra_address_id as $id) {
		$extra_address[] = get_user_meta(get_current_user_id(), 'cs_extra_address_' . $id, true);
	}
}

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'colorshop_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'colorshop' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'colorshop_get_checkout_url', $colorshop->cart->get_checkout_url() ); ?>

<div id="cs-checkout" class="clearfix">

<?php if ( is_user_logged_in() )
		echo do_shortcode('[colorshop_edit_address]');
?>

<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
	
	<?php if ( is_user_logged_in() ) { ?>
	
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
	
	
	<h3 id="order_review_heading"><?php _e( 'Your order', 'colorshop' ); ?></h3>
	
	<?php } else { ?>
		
		<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

			<?php do_action( 'colorshop_checkout_before_customer_details' ); ?>
			<div class="colorshop">
				<div id="cs-edit-address">
					<div class="col2-set" id="customer_details">
			
						<div class="col-1">
			
							<?php do_action( 'colorshop_checkout_billing' ); ?>
			
						</div>
			
						<div class="col-2" style="width:100%;">
			
							<?php do_action( 'colorshop_checkout_shipping' ); ?>
							
							<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

								<?php if ( $checkout->enable_guest_checkout ) : ?>
							
									<p class="form-row">
										<input class="input-checkbox" id="createaccount" <?php checked($checkout->get_value('createaccount'), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account?', 'colorshop' ); ?></label>
									</p>
							
								<?php endif; ?>
							
								<?php do_action( 'colorshop_before_checkout_registration_form', $checkout ); ?>
							
								<div class="create-account">
							
									<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'colorshop' ); ?></p>
							
									<?php foreach ($checkout->checkout_fields['account'] as $key => $field) : ?>
							
										<?php colorshop_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
							
									<?php endforeach; ?>
							
									<div class="clear"></div>
							
								</div>
							
								<?php do_action( 'colorshop_after_checkout_registration_form', $checkout ); ?>
							
							<?php endif; ?>
			
						</div>
			
					</div>
				</div>
			</div>
	
			<?php do_action( 'colorshop_checkout_after_customer_details' ); ?>
	
			<h3 id="order_review_heading"><?php _e( 'Your order', 'colorshop' ); ?></h3>
	
		<?php endif; ?>
	
	<?php } ?>

	<?php do_action( 'colorshop_checkout_order_review' ); ?>
</form>

</div><!-- #cs-checkout -->

<?php do_action( 'colorshop_after_checkout_form', $checkout ); ?>