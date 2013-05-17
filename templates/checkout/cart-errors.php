<?php
/**
 * Cart errors page
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php $colorshop->show_messages(); ?>

<p><?php _e( 'There are some issues with the items in your cart (shown above). Please go back to the cart page and resolve these issues before checking out.', 'colorshop' ) ?></p>

<?php do_action('colorshop_cart_has_errors'); ?>

<p><a class="button" href="<?php echo get_permalink(colorshop_get_page_id('cart')); ?>"><?php _e( '&larr; Return To Cart', 'colorshop' ) ?></a></p>