<?php
/**
 * Empty cart page
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<p><?php _e( 'Your cart is currently empty.', 'colorshop' ) ?></p>

<?php do_action('colorshop_cart_is_empty'); ?>

<p><a class="button" href="<?php echo get_permalink(colorshop_get_page_id('shop')); ?>"><?php _e( '&larr; Return To Shop', 'colorshop' ) ?></a></p>