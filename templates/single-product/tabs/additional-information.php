<?php
/**
 * Additional Information tab
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop, $post, $product;

$heading = apply_filters( 'colorshop_product_additional_information_heading', __( 'Additional Information', 'colorshop' ) );
?>

<h2><?php echo $heading; ?></h2>

<?php $product->list_attributes(); ?>