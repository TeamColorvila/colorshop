<?php
/**
 * Description tab
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop, $post;

$heading = esc_html( apply_filters('colorshop_product_description_heading', __( 'Product Description', 'colorshop' ) ) );
?>

<h2><?php echo $heading; ?></h2>

<?php the_content(); ?>