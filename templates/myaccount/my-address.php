<?php
/**
 * My Addresses
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$customer_id = get_current_user_id();

if ( get_option('colorshop_ship_to_billing_address_only') == 'no' ) {
	$page_title = apply_filters( 'colorshop_my_account_my_address_title', __( 'My Addresses', 'colorshop' ) );
	$get_addresses    = array(
		'billing' => __( 'Billing Address', 'colorshop' ),
		'shipping' => __( 'Shipping Address', 'colorshop' )
	);
} else {
	$page_title = apply_filters( 'colorshop_my_account_my_address_title', __( 'My Address', 'colorshop' ) );
	$get_addresses    = array(
		'billing' =>  __( 'Billing Address', 'colorshop' )
	);
}

$col = 1;

$checkout = $colorshop->checkout();
?>

<!-- <h2> -->
 <?php //echo $page_title; ?>
<!-- </h2> -->

<!-- <p class="myaccount_address"> -->
	<?php //echo apply_filters( 'colorshop_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'colorshop' ) ); ?>
<!-- </p> -->
<div id="cs-edit-address">
<?php if ( get_option('colorshop_ship_to_billing_address_only') == 'no' ) echo '<div class="col2-set addresses">'; ?>

<?php foreach ( $get_addresses as $name => $title ) : ?>

	<div class="col-<?php echo ( ( $col = $col * -1 ) < 0 ) ? 1 : 2; ?> address cs-<?php echo $name ?>-address">
		<header class="title">
			<h3><?php echo $title; ?></h3>
		</header>
		<address>
		<?php if ( $name == 'shipping' && get_option('colorshop_calc_shipping')=='no' ) {
				_e( 'N/A', 'colorshop' );
			 } else { 
				$address = array(
					'first_name' 	=> get_user_meta( $customer_id, $name . '_first_name', true ),
					'last_name'		=> get_user_meta( $customer_id, $name . '_last_name', true ),
					'company'		=> get_user_meta( $customer_id, $name . '_company', true ),
					'address_1'		=> get_user_meta( $customer_id, $name . '_address_1', true ),
					'address_2'		=> get_user_meta( $customer_id, $name . '_address_2', true ),
					'city'			=> get_user_meta( $customer_id, $name . '_city', true ),
					'state'			=> get_user_meta( $customer_id, $name . '_state', true ),
					'postcode'		=> get_user_meta( $customer_id, $name . '_postcode', true ),
					'country'		=> get_user_meta( $customer_id, $name . '_country', true )
				);

				$formatted_address = $colorshop->countries->get_formatted_address( $address );

				if ( ! $formatted_address )
					_e( 'You have not set up this type of address yet.', 'colorshop' );
				else
					echo $formatted_address;
			?>
		</address>
	</div>
	<?php } ?>
<?php endforeach; ?>

<?php if ( get_option('colorshop_ship_to_billing_address_only') == 'no' ) echo '</div>'; ?>

<div class="clear"></div>

<?php
	$extra_address_id = get_user_meta(get_current_user_id(), 'cs_extra_address_id', true);
	if (! empty($extra_address_id) && sizeof($extra_address_id) > 0) {
		foreach ($extra_address_id as $id) {
			$extra_address[] = get_user_meta(get_current_user_id(), 'cs_extra_address_' . $id, true);
		}
	} 
?>
<table id="address-administration" style="display:<?php echo is_checkout() ? 'none' : 'table' ?>">
	<caption><?php _e("Select to change the default billing/shipping address.", "colorshop");?></caption>
	<tr>
		<td><?php _e('Billing', 'colorshop')?></td>
		<?php if ( get_option('colorshop_calc_shipping') == 'yes' ) : ?>
		<td><?php _e('Shipping', 'colorshop')?></td>
		<?php endif; ?>
		<td><?php _e('Name', 'colorshop')?></td>
		<td><?php _e('Address', 'colorshop')?></td>
		<td><?php _e('Operation', 'colorshop')?></td>
	</tr>
	<?php
	if (! empty($extra_address)) { 
		foreach ($extra_address as $address) :
	?>
	<tr>
		<td><input class="cs-billing-input" type="radio" name="rdo-billing" value="radiobutton" checked="checked"></td>
		<?php if ( get_option('colorshop_calc_shipping') == 'yes' ) : ?>
		<td><input class="cs-shipping-input" type="radio" name="rdo-shipping" value="radiobutton" checked="checked"></td>
		<?php endif; ?>
		<td><?php echo $address['billing_first_name'] . ' ' . $address['billing_last_name']?></td>
		<td>
			<span><?php echo $address['billing_address_1']; ?></span>
			<span><?php echo $address_s['billing_city']; ?></span>
		</td>
		<td>
			<a class="cancel-address" href="javascript:void(0);" style="display:none;">Cancel</a><a class="edit-address" href="javascript:void(0);" data-tag="<?php echo $address['address_id']; ?>">Edit</a>/<a class="delete-address" href="javascript:void(0);">Delete</a>
		</td>
	</tr>
	<?php endforeach; } ?>
</table><!-- #address-administration -->
	
<?php //if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'colorshop_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details" style="display:none;">

			<div class="col-1">

				<?php do_action( 'colorshop_checkout_billing' ); ?>

			</div>

<!-- 			<div class="col-2"> -->

				<?php //do_action( 'colorshop_checkout_shipping' ); ?>

<!-- 			</div> -->


		</div>
		<p class="cs-address-bottom">
			<a class="edit-address-click" href="javascript:void(0);" style="display:<?php echo is_checkout() ? 'inline' : 'none' ?>">Edit Address</a>
			<a class="add-address" href="javascript:void(0);" style="display:<?php echo is_checkout() ? 'none' : 'inline' ?>">Add Address</a>
			<button class="save-address button" type="button" style="display:none;">Save Address</button>
			<a class="cancel-address-2" href="javascript:void(0);" style="display:none;">Cancel</a>
		</p>

		<?php do_action( 'colorshop_checkout_after_customer_details' ); ?>

		

	<?php //endif; ?>

</div>