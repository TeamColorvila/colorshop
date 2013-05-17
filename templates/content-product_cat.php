<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/colorshop/content-product_cat.php
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop_loop;

// Store loop count we're currently on
if ( empty( $colorshop_loop['loop'] ) )
	$colorshop_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $colorshop_loop['columns'] ) )
	$colorshop_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Increase loop count
$colorshop_loop['loop']++;
?>
<li class="product-category product<?php
    if ( ( $colorshop_loop['loop'] - 1 ) % $colorshop_loop['columns'] == 0 || $colorshop_loop['columns'] == 1)
        echo ' first';
	if ( $colorshop_loop['loop'] % $colorshop_loop['columns'] == 0 )
		echo ' last';
	?>">

	<?php do_action( 'colorshop_before_subcategory', $category ); ?>

	<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">

		<?php
			/**
			 * colorshop_before_subcategory_title hook
			 *
			 * @hooked colorshop_subcategory_thumbnail - 10
			 */
			do_action( 'colorshop_before_subcategory_title', $category );
		?>

		<h3>
			<?php
				echo $category->name;

				if ( $category->count > 0 )
					echo apply_filters( 'colorshop_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
			?>
		</h3>

		<?php
			/**
			 * colorshop_after_subcategory_title hook
			 */
			do_action( 'colorshop_after_subcategory_title', $category );
		?>

	</a>

	<?php do_action( 'colorshop_after_subcategory', $category ); ?>

</li>