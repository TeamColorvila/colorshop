<?php
/**
 * Edit address form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop, $current_user;

get_currentuserinfo();
?>

<?php $colorshop->show_messages(); ?>

<?php if (!$load_address) : ?>

	<?php colorshop_get_template('myaccount/my-address.php'); ?>

<?php else : ?>

	<form action="<?php echo esc_url( add_query_arg( 'address', $load_address, get_permalink( colorshop_get_page_id('edit_address') ) ) ); ?>" method="post">

		<h3><?php if ($load_address=='billing') _e( 'Billing Address', 'colorshop' ); else _e( 'Shipping Address', 'colorshop' ); ?></h3>

		<?php
		foreach ($address as $key => $field) :
			$value = (isset($_POST[$key])) ? $_POST[$key] : get_user_meta( get_current_user_id(), $key, true );

			// Default values
			if (!$value && ($key=='billing_email' || $key=='shipping_email')) $value = $current_user->user_email;
			if (!$value && ($key=='billing_country' || $key=='shipping_country')) $value = $colorshop->countries->get_base_country();
			if (!$value && ($key=='billing_state' || $key=='shipping_state')) $value = $colorshop->countries->get_base_state();

			colorshop_form_field( $key, $field, $value );
		endforeach;
		?>
		<p>
			<input type="submit" class="button" name="save_address" value="<?php _e( 'Save Address', 'colorshop' ); ?>" />
			<?php $colorshop->nonce_field('edit_address') ?>
			<input type="hidden" name="action" value="edit_address" />
		</p>

	</form>

<?php endif; ?>