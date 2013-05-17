<?php
/**
 * Login Form
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop; ?>

<?php $colorshop->show_messages(); ?>

<?php do_action('colorshop_before_customer_login_form'); ?>

<?php if (get_option('colorshop_enable_myaccount_registration')=='yes') : ?>

<div class="col2-set" id="customer_login">

	<div class="col-1">

<?php endif; ?>

		<h2><?php _e( 'Login', 'colorshop' ); ?></h2>
		<form method="post" class="login">
			<p class="form-row form-row-first">
				<label for="username"><?php _e( 'Username or email', 'colorshop' ); ?> <span class="required">*</span></label>
				<input type="text" class="input-text" name="username" id="username" />
			</p>
			<p class="form-row form-row-last">
				<label for="password"><?php _e( 'Password', 'colorshop' ); ?> <span class="required">*</span></label>
				<input class="input-text" type="password" name="password" id="password" />
			</p>
			<div class="clear"></div>

			<p class="form-row">
				<?php $colorshop->nonce_field('login', 'login') ?>
				<input type="submit" class="button" name="login" value="<?php _e( 'Login', 'colorshop' ); ?>" />
				<a class="lost_password" href="<?php

				$lost_password_page_id = colorshop_get_page_id( 'lost_password' );

				if ( $lost_password_page_id )
					echo esc_url( get_permalink( $lost_password_page_id ) );
				else
					echo esc_url( wp_lostpassword_url( home_url() ) );

				?>"><?php _e( 'Lost Password?', 'colorshop' ); ?></a>
			</p>
		</form>

<?php if (get_option('colorshop_enable_myaccount_registration')=='yes') : ?>

	</div>

	<div class="col-2">

		<h2><?php _e( 'Register', 'colorshop' ); ?></h2>
		<form method="post" class="register">

			<?php if ( get_option( 'colorshop_registration_email_for_username' ) == 'no' ) : ?>

				<p class="form-row form-row-first">
					<label for="reg_username"><?php _e( 'Username', 'colorshop' ); ?> <span class="required">*</span></label>
					<input type="text" class="input-text" name="username" id="reg_username" value="<?php if (isset($_POST['username'])) echo esc_attr($_POST['username']); ?>" />
				</p>

				<p class="form-row form-row-last">

			<?php else : ?>

				<p class="form-row form-row-wide">

			<?php endif; ?>

				<label for="reg_email"><?php _e( 'Email', 'colorshop' ); ?> <span class="required">*</span></label>
				<input type="email" class="input-text" name="email" id="reg_email" value="<?php if (isset($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
			</p>

			<div class="clear"></div>

			<p class="form-row form-row-first">
				<label for="reg_password"><?php _e( 'Password', 'colorshop' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password" id="reg_password" value="<?php if (isset($_POST['password'])) echo esc_attr($_POST['password']); ?>" />
			</p>
			<p class="form-row form-row-last">
				<label for="reg_password2"><?php _e( 'Re-enter password', 'colorshop' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password2" id="reg_password2" value="<?php if (isset($_POST['password2'])) echo esc_attr($_POST['password2']); ?>" />
			</p>
			<div class="clear"></div>

			<!-- Spam Trap -->
			<div style="left:-999em; position:absolute;"><label for="trap">Anti-spam</label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'register_form' ); ?>

			<p class="form-row">
				<?php $colorshop->nonce_field('register', 'register') ?>
				<input type="submit" class="button" name="register" value="<?php _e( 'Register', 'colorshop' ); ?>" />
			</p>

		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action('colorshop_after_customer_login_form'); ?>