<?php
/**
 * Additional payment gateway settings
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/Settings
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Output payment gateway settings.
 *
 * @access public
 * @return void
 */
function colorshop_payment_gateways_setting() {
	global $colorshop;
	?>
	<tr valign="top">
	    <td class="forminp" colspan="2">
			<table class="cs_gateways widefat" cellspacing="0">
				<thead>
					<tr>
						<th width="1%"><?php _e( 'Default', 'colorshop' ); ?></th>
						<th><?php _e( 'Gateway', 'colorshop' ); ?></th>
						<th><?php _e( 'Status', 'colorshop' ); ?></th>
					</tr>
				</thead>
				<tbody>
		        	<?php
		        	foreach ( $colorshop->payment_gateways->payment_gateways() as $gateway ) :

		        		$default_gateway = get_option('colorshop_default_gateway');

		        		echo '<tr>
		        			<td width="1%" class="radio">
		        				<input type="radio" name="default_gateway" value="' . esc_attr( $gateway->id ) . '" ' . checked( $default_gateway, esc_attr( $gateway->id ), false ) . ' />
		        				<input type="hidden" name="gateway_order[]" value="' . esc_attr( $gateway->id ) . '" />
		        			</td>
		        			<td>
		        				<p><strong>' . $gateway->get_title() . '</strong><br/>
		        				<small>' . __( 'Gateway ID', 'colorshop' ) . ': ' . esc_html( $gateway->id ) . '</small></p>
		        			</td>
		        			<td>';

		        		if ( $gateway->enabled == 'yes' )
		        			echo '<img src="' . $colorshop->plugin_url() . '/assets/images/success@2x.png" width="16" height="14" alt="yes" />';
						else
							echo '<img src="' . $colorshop->plugin_url() . '/assets/images/success-off@2x.png" width="16" height="14" alt="no" />';

		        		echo '</td>
		        		</tr>';

		        	endforeach;
		        	?>
				</tbody>
			</table>
		</td>
	</tr>
	<?php
}

add_action( 'colorshop_admin_field_payment_gateways', 'colorshop_payment_gateways_setting' );