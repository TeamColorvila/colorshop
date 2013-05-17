<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/colorshop/single-product.php
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

	<?php
		/**
		 * colorshop_before_main_content hook
		 *
		 * @hooked colorshop_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked colorshop_breadcrumb - 20
		 */
		do_action('colorshop_before_main_content');
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php colorshop_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * colorshop_after_main_content hook
		 *
		 * @hooked colorshop_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('colorshop_after_main_content');
	?>

	<?php
		/**
		 * colorshop_sidebar hook
		 *
		 * @hooked colorshop_get_sidebar - 10
		 */
		do_action('colorshop_sidebar');
	?>

<?php get_footer('shop'); ?>