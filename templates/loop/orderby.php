<?php
/**
 * Show options for ordering
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop, $wp_query;

if ( 1 == $wp_query->found_posts || ! colorshop_products_will_display() )
	return;
?>
<form class="colorshop-ordering" method="get">
	<select name="orderby" class="orderby">
		<?php
			$catalog_orderby = apply_filters( 'colorshop_catalog_orderby', array(
				'menu_order' => __( 'Default sorting', 'colorshop' ),
				'popularity' => __( 'Sort by popularity', 'colorshop' ),
				'rating'     => __( 'Sort by average rating', 'colorshop' ),
				'date'       => __( 'Sort by newness', 'colorshop' ),
				'price'      => __( 'Sort by price: low to high', 'colorshop' ),
				'price-desc' => __( 'Sort by price: high to low', 'colorshop' )
			) );

			if ( get_option( 'colorshop_enable_review_rating' ) == 'no' )
				unset( $catalog_orderby['rating'] );

			foreach ( $catalog_orderby as $id => $name )
				echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
		?>
	</select>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' == $key )
				continue;
			
			if (is_array($val)) {
				foreach($val as $innerVal) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
	?>
</form>
