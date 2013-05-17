<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop, $wp_query;

if ( ! colorshop_products_will_display() )
	return;
?>

<div id="cs-filter" class="clearfix">

	<div id="cs-filter-wrap" class="clearfix">
		
		<div class="cs-filter-selected clearfix" style="display:<?php echo ($_REQUEST['attr']) ? 'block' : 'none' ?>">		
			<ul>
				<?php
				if($_REQUEST['attr']) {
					$all_attributes = explode(';', $_REQUEST['attr']);
					foreach ($all_attributes as $item) {
						$group = explode(':', $item );
						$value_select = explode(',', $group[1]);
						foreach ($value_select as $value) {
							$all_value[] = $value;
							echo '<li style=""><a href="javascript:void(0);">' . $value . '</a></li>';
						}
					}
				}
				?>
			</ul>
			<a href="javascript:void(0);" class="clearall"><?php echo __('Clear all', 'colorshop')?></a>
		</div><!-- .cs-filter-selected -->
		
		<div class="cs-filter-panel clearfix">
			<?php
				$cat = get_query_var('product_cat');
				if (empty($cat))
					$cat = 'default';
				$attribute_taxonomies = $colorshop->get_attribute_taxonomies_by($cat);
				foreach ($attribute_taxonomies as $tax) {
			?>
			<ul class="clearfix">
				<li class="first"><?php echo $tax->attribute_name;?>:</li>
				<li>
					<ul>
						<?php
						$attribute_taxonomy_name = $colorshop->attribute_taxonomy_name( $tax->attribute_name );
						$all_terms = get_terms( $attribute_taxonomy_name, 'orderby=name&hide_empty=0' );
						if ( $all_terms ) {
							foreach ( $all_terms as $term ) {
								$selected = $_REQUEST['attr'] && in_array($term->name, $all_value) ? 'selected' : '';
								echo '<li><a href="javascript:void(0);" class="' . $selected . '">' . $term->name . '</a></li>';
							}
						}
						?>
					</ul>
				</li>
			</ul>
			<?php }?>
		</div><!-- .cs-filter-panel -->
	
	</div><!-- #cs-filter-wrap -->	
	
	<?php
		$orderby = $_REQUEST['orderby'];
	?>
	<div class="cs-filter-bar clearfix">
		<div class="cs-sorting">
			<a <?php if(empty($orderby)) echo 'class="selected"'?> href="javascript:void(0);" tag="default"><?php echo __('Default', 'colorshop')?></a>
			<a href="javascript:void(0);" tag="popularity" <?php if($orderby == 'popularity') echo 'class="selected"'?>><?php echo __('Popularity', 'colorshop')?></a>
			<a href="javascript:void(0);" tag="rating" <?php if($orderby == 'rating') echo 'class="selected"'?>><?php echo __('Rating', 'colorshop')?></a>
			<a href="javascript:void(0);" tag="date" <?php if($orderby == 'date') echo 'class="selected"'?>><?php echo __('Newness', 'colorshop')?></a>
			<?php
				$price_class =  '';
				if($orderby == 'price') {
					$price_class = 'class="selected"';
 				}
 				else if($orderby == 'price-desc') { 
 					$price_class = 'class="selected down"';
				}
			?>
			<a href="javascript:void(0);" tag="price" <?php echo $price_class; ?>>
				<?php echo __('Price', 'colorshop')?>
				<span class="icon"></span>
			</a>
		</div>
	</div><!-- .cs-filter-bar -->		
	
</div><!-- #cs-filter -->
