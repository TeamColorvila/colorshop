<?php
/**
 * Additional shipping settings
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/Settings
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Output shipping method settings.
 *
 * @access public
 * @return void
 */
function colorshop_shipping_methods_setting() {
	global $colorshop;
	?>
	<tr valign="top">
		<th scope="row" class="titledesc"><?php _e( 'Shipping Methods', 'colorshop' ) ?></th>
	    <td class="forminp">
	    	<p class="description" style="margin-top: 0;"><?php _e( 'Drag and drop methods to control their display order.', 'colorshop' ); ?></p>
			<table class="cs_shipping widefat" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e( 'Default', 'colorshop' ); ?></th>
						<th><?php _e( 'Shipping Method', 'colorshop' ); ?></th>
						<th><?php _e( 'Status', 'colorshop' ); ?></th>
					</tr>
				</thead>
				<tbody>
			    	<?php
			    	foreach ( $colorshop->shipping->load_shipping_methods() as $method ) {

				    	$default_shipping_method = esc_attr( get_option('colorshop_default_shipping_method') );

				    	echo '<tr>
				    		<td width="1%" class="radio">
				    			<input type="radio" name="default_shipping_method" value="' . $method->id . '" ' . checked( $default_shipping_method, $method->id, false ) . ' />
				    			<input type="hidden" name="method_order[]" value="' . $method->id . '" />
				    			<td>
				    				<p><strong>' . $method->get_title() . '</strong><br/>
				    				<small>' . __( 'Method ID', 'colorshop' ) . ': ' . $method->id . '</small></p>
				    			</td>
				    			<td>';

			    		if ($method->enabled == 'yes')
			    			echo '<img src="' . $colorshop->plugin_url() . '/assets/images/success@2x.png" width="16 height="14" alt="yes" />';
						else
							echo '<img src="' . $colorshop->plugin_url() . '/assets/images/success-off@2x.png" width="16" height="14" alt="no" />';

			    		echo '</td>
			    		</tr>';

			    	}
			    	?>
				</tbody>
			</table>
		</td>
	</tr>
	<?php
}

add_action( 'colorshop_admin_field_shipping_methods', 'colorshop_shipping_methods_setting' );