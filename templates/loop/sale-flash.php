<?php
/**
 * Product loop sale flash
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<?php if ($product->is_on_sale()) : ?>

	<?php echo apply_filters('colorshop_sale_flash', '<span class="onsale">'.__( 'Sale!', 'colorshop' ).'</span>', $post, $product); ?>

<?php endif; ?>