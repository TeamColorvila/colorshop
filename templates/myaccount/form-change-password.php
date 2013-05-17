<?php
/**
 * Change password form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;
?>

<?php $colorshop->show_messages(); ?>

<form action="<?php echo esc_url( get_permalink(colorshop_get_page_id('change_password')) ); ?>" method="post">

	<p class="form-row form-row-first">
		<label for="password_1"><?php _e( 'New password', 'colorshop' ); ?> <span class="required">*</span></label>
		<input type="password" class="input-text" name="password_1" id="password_1" />
	</p>
	<p class="form-row form-row-last">
		<label for="password_2"><?php _e( 'Re-enter new password', 'colorshop' ); ?> <span class="required">*</span></label>
		<input type="password" class="input-text" name="password_2" id="password_2" />
	</p>
	<div class="clear"></div>

	<p><input type="submit" class="button" name="change_password" value="<?php _e( 'Save', 'colorshop' ); ?>" /></p>

	<?php $colorshop->nonce_field('change_password')?>
	<input type="hidden" name="action" value="change_password" />

</form>