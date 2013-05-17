<?php
/**
 * Account setting form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
	
	<div id="cs-change-email" class="clearfix">
	
		<h2>Change Email <?php if (get_option('colorshop_email')) echo '(' . get_option('colorshop_email') . ')';?></h2>
		<p>
			<label>New email<span>*</span></label>
			<input type="email" name="email_1" />
		</p>
		
		<p class="right">
			<label>Re-enter new email<span>*</span></label>
			<input type="email" name="email_2" />			
		</p>
		
		<!--  
			<p><input type="submit" class="button" name="change_email" value="<?php _e( 'Save', 'colorshop' ); ?>" /></p>
		-->
		<p><button type="button" class="button account_setting"><?php _e( 'Save', 'colorshop' ); ?></button></p>
		<script>
			jQuery(".account_setting").on('click', function() {
				jQuery('#cs-change-email').block({ message: null, overlayCSS: { background: '#fff url(wp-content/plugins/colorshop/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6 } });

				var data = {
						data:			jQuery('#cs-change-email').find('input, select, textarea').serialize(),
						action: 		'colorshop_account_setting'
				};

				jQuery.post( 'wp-admin/admin-ajax.php', data, function( response ) {
					if (response) {
						jQuery('#cs-change-email h2').text('Change Email (' + response + ')');	
					}
					jQuery('#cs-change-email').unblock();
				});
			});
			
		</script>
	</div><!-- #cs-change-email -->
	
	<div id="cs-change-password" class="clearfix">
	
		<h2>Change Password</h2>
		
		<p class="form-row form-row-first">
			<label for="password_1"><?php _e( 'New password', 'colorshop' ); ?> <span class="required">*</span></label>
			<input type="password" class="input-text" name="password_1" id="password_1" />
		</p>
		<p class="form-row form-row-last">
			<label for="password_2"><?php _e( 'Re-enter new password', 'colorshop' ); ?> <span class="required">*</span></label>
			<input type="password" class="input-text" name="password_2" id="password_2" />
		</p>
		<div class="clear"></div>
		
		<p><button type="button" class="button change_password"><?php _e( 'Save', 'colorshop' ); ?></button></p>
		<script>
			jQuery(".change_password").on('click', function() {
				jQuery('#cs-change-password').block({ message: null, overlayCSS: { background: '#fff url(wp-content/plugins/colorshop/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6 } });

				var data = {
						data:			jQuery('#cs-change-password').find('input, select, textarea').serialize(),
						action: 		'colorshop_change_password'
				};

				jQuery.post( 'wp-admin/admin-ajax.php', data, function( response ) {
					jQuery('#cs-change-password').unblock();
				});
			});
			
		</script>
		
		<!-- 
		<p><input type="submit" class="button" name="change_password" value="<?php _e( 'Save', 'colorshop' ); ?>" /></p>
		 -->

		<?php $colorshop->nonce_field('change_password')?>
		<input type="hidden" name="action" value="change_password" />
		
	</div><!-- #cs-change-password -->