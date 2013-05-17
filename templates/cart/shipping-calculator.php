<?php
/**
 * Shipping Calculator
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

if ( get_option('colorshop_enable_shipping_calc')=='no' || ! $colorshop->cart->needs_shipping() ) return;
?>

<?php do_action( 'colorshop_before_shipping_calculator' ); ?>

<form class="shipping_calculator" action="<?php echo esc_url( $colorshop->cart->get_cart_url() ); ?>" method="post">
	<h2><a href="#" class="shipping-calculator-button"><?php _e( 'Calculate Shipping', 'colorshop' ); ?> <span>&darr;</span></a></h2>
	<section class="shipping-calculator-form">
		<p class="form-row form-row-wide">
			<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state" rel="calc_shipping_state">
				<option value=""><?php _e( 'Select a country&hellip;', 'colorshop' ); ?></option>
				<?php
					foreach( $colorshop->countries->get_allowed_countries() as $key => $value )
						echo '<option value="' . $key . '"' . selected( $colorshop->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				?>
			</select>
		</p>
		<p class="form-row form-row-wide">
			<?php
				$current_cc = $colorshop->customer->get_shipping_country();
				$current_r = $colorshop->customer->get_shipping_state();

				$states = $colorshop->countries->get_states( $current_cc );

				if ( is_array( $states ) && empty( $states ) ) {

					// Hidden
					?>
					<input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'colorshop' ); ?>" />
					<?php

				} elseif ( is_array( $states ) ) {

					// Dropdown
					?>
					<span>
						<select name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'colorshop' ); ?>"><option value=""><?php _e( 'Select a state&hellip;', 'colorshop' ); ?></option><?php
							foreach ( $states as $ckey => $cvalue )
								echo '<option value="' . esc_attr( $ckey ) . '" '.selected( $current_r, $ckey, false ) .'>' . __( esc_html( $cvalue ), 'colorshop' ) .'</option>';
						?></select>
					</span>
					<?php

				} else {

					// Input
					?>
					<input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php _e( 'State / county', 'colorshop' ); ?>" name="calc_shipping_state" id="calc_shipping_state" />
					<?php

				}
			?>
		</p>
		<p class="form-row form-row-wide">
			<input type="text" class="input-text" value="<?php echo esc_attr( $colorshop->customer->get_shipping_postcode() ); ?>" placeholder="<?php _e( 'Postcode / Zip', 'colorshop' ); ?>" title="<?php _e( 'Postcode', 'colorshop' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
		</p>
		<div class="clear"></div>
		<p><button type="submit" name="calc_shipping" value="1" class="button"><?php _e( 'Update Totals', 'colorshop' ); ?></button></p>
		<?php $colorshop->nonce_field('cart') ?>
	</section>
</form>

<?php do_action( 'colorshop_after_shipping_calculator' ); ?>
