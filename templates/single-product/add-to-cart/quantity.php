<?php
/**
 * Single product quantity inputs
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="quantity"><input type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" size="4" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'colorshop' ) ?>" class="input-text qty text" maxlength="12" /></div>