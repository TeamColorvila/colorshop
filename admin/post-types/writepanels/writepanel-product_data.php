<?php
/**
 * Product Data
 *
 * Function for displaying the product data meta boxes
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/WritePanels
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Variable products */
require_once( 'writepanel-product-type-variable.php' );

/**
 * Display the product data meta box.
 *
 * Displays the product data box, tabbed, with several panels covering price, stock etc.
 *
 * @access public
 * @return void
 */
function colorshop_product_data_box() {
	global $post, $wpdb, $thepostid, $colorshop;
	wp_nonce_field( 'colorshop_save_data', 'colorshop_meta_nonce' );
	
	$thepostid = $post->ID;

	if ( $terms = wp_get_object_terms( $post->ID, 'product_type' ) )
		$product_type = sanitize_title( current( $terms )->name );
	else
		$product_type = 'simple';

	$product_type_selector = apply_filters( 'product_type_selector', array(
		'simple' 	=> __( 'Simple product', 'colorshop' ),
		'grouped' 	=> __( 'Grouped product', 'colorshop' ),
		'external' 	=> __( 'External/Affiliate product', 'colorshop' )
	), $product_type );

	$type_box  = '<label for="product-type"><select id="product-type" name="product-type"><optgroup label="' . __( 'Product Type', 'colorshop' ) . '">';
	foreach ( $product_type_selector as $value => $label )
		$type_box .= '<option value="' . esc_attr( $value ) . '" ' . selected( $product_type, $value, false ) .'>' . esc_html( $label ) . '</option>';
	$type_box .= '</optgroup></select></label>';

	$product_type_options = apply_filters('product_type_options', array(
		'virtual' => array(
			'id' => '_virtual',
			'wrapper_class' => 'show_if_simple',
			'label' => __( 'Virtual', 'colorshop' ),
			'description' => __( 'Virtual products are intangible and aren\'t shipped.', 'colorshop' )
		),
		'downloadable' => array(
			'id' => '_downloadable',
			'wrapper_class' => 'show_if_simple',
			'label' => __( 'Downloadable', 'colorshop' ),
			'description' => __( 'Downloadable products give access to a file upon purchase.', 'colorshop' )
		)
	) );

	foreach ( $product_type_options as $key => $option ) {
		$selected_value = get_post_meta( $post->ID, '_' . $key, true );
		$type_box .= '<label for="' . esc_attr( $option['id'] ) . '" class="'. esc_attr( $option['wrapper_class'] ) . ' tips" data-tip="' . esc_attr( $option['description'] ) . '">' . esc_html( $option['label'] ) . ': <input type="checkbox" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" ' . checked( $selected_value, 'yes', false ) .' /></label>';
	}

	?>
	<div class="panel-wrap product_data">

		<span class="type_box"> &mdash; <?php echo $type_box; ?></span>

		<div class="cs-tabs-back"></div>

		<ul class="product_data_tabs cs-tabs" style="display:none;">

			<li class="active general_options hide_if_grouped"><a href="#general_product_data"><?php _e( 'General', 'colorshop' ); ?></a></li>

			<li class="inventory_tab show_if_simple show_if_variable show_if_grouped inventory_options"><a href="#inventory_product_data"><?php _e( 'Inventory', 'colorshop' ); ?></a></li>

			<li class="shipping_tab hide_if_virtual shipping_options hide_if_grouped hide_if_external"><a href="#shipping_product_data"><?php _e( 'Shipping', 'colorshop' ); ?></a></li>

			<li class="linked_product_tab linked_product_options"><a href="#linked_product_data"><?php _e( 'Linked Products', 'colorshop' ); ?></a></li>

			<li class="attributes_tab attribute_options"><a href="#colorshop_attributes"><?php _e( 'Attributes', 'colorshop' ); ?></a></li>

			<li class="advanced_tab advanced_options"><a href="#advanced_product_data"><?php _e( 'Advanced', 'colorshop' ); ?></a></li>

			<?php do_action( 'colorshop_product_write_panel_tabs' ); ?>

		</ul>
		<div id="general_product_data" class="panel colorshop_options_panel"><?php

			echo '<div class="options_group hide_if_grouped">';

				// SKU
				if( get_option('colorshop_enable_sku', true) !== 'no' )
					colorshop_wp_text_input( array( 'id' => '_sku', 'label' => '<abbr title="'. __( 'Stock Keeping Unit', 'colorshop' ) .'">' . __( 'SKU', 'colorshop' ) . '</abbr>', 'desc_tip' => 'true', 'description' => __( 'SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'colorshop' ) ) );
				else
					echo '<input type="hidden" name="_sku" value="' . esc_attr( get_post_meta( $thepostid, '_sku', true ) ) . '" />';

				do_action('colorshop_product_options_sku');

			echo '</div>';

			echo '<div class="options_group show_if_external">';

				// External URL
				colorshop_wp_text_input( array( 'id' => '_product_url', 'label' => __( 'Product URL', 'colorshop' ), 'placeholder' => 'http://', 'description' => __( 'Enter the external URL to the product.', 'colorshop' ) ) );

				// Button text
				colorshop_wp_text_input( array( 'id' => '_button_text', 'label' => __( 'Button text', 'colorshop' ), 'placeholder' => _x('Buy product', 'placeholder', 'colorshop'), 'description' => __( 'This text will be shown on the button linking to the external product.', 'colorshop' ) ) );

			echo '</div>';

			echo '<div class="options_group pricing show_if_simple show_if_external">';

				// Price
				colorshop_wp_text_input( array( 'id' => '_regular_price', 'class' => 'cs_input_price short', 'label' => __( 'Regular Price', 'colorshop' ) . ' ('.get_colorshop_currency_symbol().')', 'type' => 'number', 'custom_attributes' => array(
					'step' 	=> 'any',
					'min'	=> '0'
				) ) );

				// Special Price
				colorshop_wp_text_input( array( 'id' => '_sale_price', 'class' => 'cs_input_price short', 'label' => __( 'Sale Price', 'colorshop' ) . ' ('.get_colorshop_currency_symbol().')', 'description' => '<a href="#" class="sale_schedule">' . __( 'Schedule', 'colorshop' ) . '</a>', 'type' => 'number', 'custom_attributes' => array(
					'step' 	=> 'any',
					'min'	=> '0'
				) ) );

				// Special Price date range
				$sale_price_dates_from 	= ( $date = get_post_meta( $thepostid, '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
				$sale_price_dates_to 	= ( $date = get_post_meta( $thepostid, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';

				echo '	<p class="form-field sale_price_dates_fields">
							<label for="_sale_price_dates_from">' . __( 'Sale Price Dates', 'colorshop' ) . '</label>
							<input type="text" class="short" name="_sale_price_dates_from" id="_sale_price_dates_from" value="' . $sale_price_dates_from . '" placeholder="' . _x( 'From&hellip;', 'placeholder', 'colorshop' ) . ' YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
							<input type="text" class="short" name="_sale_price_dates_to" id="_sale_price_dates_to" value="' . $sale_price_dates_to . '" placeholder="' . _x( 'To&hellip;', 'placeholder', 'colorshop' ) . '  YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
							<a href="#" class="cancel_sale_schedule">'. __( 'Cancel', 'colorshop' ) .'</a>
						</p>';

				do_action( 'colorshop_product_options_pricing' );

			echo '</div>';

			echo '<div class="options_group show_if_downloadable">';

				// File URL
				$file_paths = get_post_meta( $post->ID, '_file_paths', true );
				if ( is_array( $file_paths ) )
					$file_paths = implode( "\n", $file_paths );

				echo '<p class="form-field"><label for="_file_paths">' . __( 'File paths (one per line)', 'colorshop' ) . ':</label>
					<textarea style="float:left;height:5em;" id="_file_paths" class="short file_paths" cols="20" rows="3" placeholder="' . __( 'File paths/URLs, one per line', 'colorshop' ) . '" name="_file_paths" wrap="off">' . esc_textarea( $file_paths ) . '</textarea>
					<input type="button" class="upload_file_button button" data-choose="' . __( 'Choose a file', 'colorshop' ) . '" data-update="' . __( 'Insert file URL', 'colorshop' ) . '" value="' . __( 'Choose a file', 'colorshop' ) . '" />
				</p>';

				// Download Limit
				colorshop_wp_text_input( array( 'id' => '_download_limit', 'label' => __( 'Download Limit', 'colorshop' ), 'placeholder' => __( 'Unlimited', 'colorshop' ), 'description' => __( 'Leave blank for unlimited re-downloads.', 'colorshop' ), 'type' => 'number', 'custom_attributes' => array(
					'step' 	=> '1',
					'min'	=> '0'
				) ) );

				// Expirey
				colorshop_wp_text_input( array( 'id' => '_download_expiry', 'label' => __( 'Download Expiry', 'colorshop' ), 'placeholder' => __( 'Never', 'colorshop' ), 'description' => __( 'Enter the number of days before a download link expires, or leave blank.', 'colorshop' ), 'type' => 'number', 'custom_attributes' => array(
					'step' 	=> '1',
					'min'	=> '0'
				) ) );

				do_action( 'colorshop_product_options_downloads' );

			echo '</div>';

			if ( get_option( 'colorshop_calc_taxes' ) == 'yes' ) {

				echo '<div class="options_group show_if_simple show_if_external show_if_variable">';

					// Tax
					colorshop_wp_select( array( 'id' => '_tax_status', 'label' => __( 'Tax Status', 'colorshop' ), 'options' => array(
						'taxable' 	=> __( 'Taxable', 'colorshop' ),
						'shipping' 	=> __( 'Shipping only', 'colorshop' ),
						'none' 		=> __( 'None', 'colorshop' )
					) ) );

					$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'colorshop_tax_classes' ) ) ) );
					$classes_options = array();
					$classes_options[''] = __( 'Standard', 'colorshop' );
		    		if ( $tax_classes )
		    			foreach ( $tax_classes as $class )
		    				$classes_options[ sanitize_title( $class ) ] = esc_html( $class );

					colorshop_wp_select( array( 'id' => '_tax_class', 'label' => __( 'Tax Class', 'colorshop' ), 'options' => $classes_options ) );

					do_action( 'colorshop_product_options_tax' );

				echo '</div>';

			}

			do_action( 'colorshop_product_options_general_product_data' );
			?>
		</div>

		<div id="inventory_product_data" class="panel colorshop_options_panel">

			<?php

			echo '<div class="options_group">';

			if (get_option('colorshop_manage_stock')=='yes') {

				// manage stock
				colorshop_wp_checkbox( array( 'id' => '_manage_stock', 'wrapper_class' => 'show_if_simple show_if_variable', 'label' => __('Manage stock?', 'colorshop' ), 'description' => __( 'Enable stock management at product level', 'colorshop' ) ) );

				do_action('colorshop_product_options_stock');

				echo '<div class="stock_fields show_if_simple show_if_variable">';

				// Stock
				colorshop_wp_text_input( array( 'id' => '_stock', 'label' => __( 'Stock Qty', 'colorshop' ), 'desc_tip' => true, 'description' => __( 'Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'colorshop' ), 'type' => 'number', 'custom_attributes' => array(
					'step' 	=> 'any'
				)  ) );

				do_action('colorshop_product_options_stock_fields');

				echo '</div>';

			}

			// Stock status
			colorshop_wp_select( array( 'id' => '_stock_status', 'label' => __( 'Stock status', 'colorshop' ), 'options' => array(
				'instock' => __( 'In stock', 'colorshop' ),
				'outofstock' => __( 'Out of stock', 'colorshop' )
			), 'desc_tip' => true, 'description' => __( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'colorshop' ) ) );

			if (get_option('colorshop_manage_stock')=='yes') {

				echo '<div class="show_if_simple show_if_variable">';

				// Backorders?
				colorshop_wp_select( array( 'id' => '_backorders', 'label' => __( 'Allow Backorders?', 'colorshop' ), 'options' => array(
					'no' => __( 'Do not allow', 'colorshop' ),
					'notify' => __( 'Allow, but notify customer', 'colorshop' ),
					'yes' => __( 'Allow', 'colorshop' )
				), 'desc_tip' => true, 'description' => __( 'If managing stock, this controls whether or not backorders are allowed for this product and variations. If enabled, stock quantity can go below 0.', 'colorshop' ) ) );

				echo '</div>';

			}

			echo '</div>';

			echo '<div class="options_group show_if_simple show_if_variable">';

			// Individual product
			colorshop_wp_checkbox( array( 'id' => '_sold_individually', 'wrapper_class' => 'show_if_simple show_if_variable', 'label' => __('Sold Individually', 'colorshop'), 'description' => __('Enable this to only allow one of this item to be bought in a single order', 'colorshop') ) );

			do_action('colorshop_product_options_sold_individually');

			echo '</div>';

			?>

		</div>

		<div id="shipping_product_data" class="panel colorshop_options_panel">

			<?php

			echo '<div class="options_group">';

				// Weight
				if( get_option('colorshop_enable_weight', true) !== 'no' ) :
					colorshop_wp_text_input( array( 'id' => '_weight', 'label' => __( 'Weight', 'colorshop' ) . ' ('.get_option('colorshop_weight_unit').')', 'placeholder' => '0.00', 'description' => __( 'Weight in decimal form', 'colorshop' ), 'type' => 'number', 'custom_attributes' => array(
					'step' 	=> 'any',
					'min'	=> '0'
				) ) );
				else:
					echo '<input type="hidden" name="_weight" value="' . esc_attr( get_post_meta( $thepostid, '_weight', true ) ) . '" />';
				endif;

				// Size fields
				if( get_option( 'colorshop_enable_dimensions', true ) !== 'no' ) :
					?><p class="form-field dimensions_field">
						<label for="product_length"><?php echo __( 'Dimensions', 'colorshop' ) . ' (' . get_option( 'colorshop_dimension_unit' ) . ')'; ?></label>
						<span class="wrap">
							<input id="product_length" placeholder="<?php _e( 'Length', 'colorshop' ); ?>" class="input-text" size="6" type="number" name="_length" value="<?php echo esc_attr( get_post_meta( $thepostid, '_length', true ) ); ?>" step="any" min="0" />
							<input placeholder="<?php _e( 'Width', 'colorshop' ); ?>" class="input-text" size="6" type="number" name="_width" value="<?php echo esc_attr( get_post_meta( $thepostid, '_width', true ) ); ?>"  step="any" min="0" />
							<input placeholder="<?php _e( 'Height', 'colorshop' ); ?>" class="input-text last" size="6" type="number" name="_height" value="<?php echo esc_attr( get_post_meta( $thepostid, '_height', true ) ); ?>"  step="any" min="0" />
						</span>
						<span class="description"><?php _e( 'LxWxH in decimal form', 'colorshop' ); ?></span>
					</p><?php
				else:
					echo '<input type="hidden" name="_length" value="' . esc_attr( get_post_meta( $thepostid, '_length', true ) ) . '" />';
					echo '<input type="hidden" name="_width" value="' . esc_attr( get_post_meta( $thepostid, '_width', true ) ) . '" />';
					echo '<input type="hidden" name="_height" value="' . esc_attr( get_post_meta( $thepostid, '_height', true ) ) . '" />';
				endif;

				do_action( 'colorshop_product_options_dimensions' );

			echo '</div>';

			echo '<div class="options_group">';

				// Shipping Class
				$classes = get_the_terms( $thepostid, 'product_shipping_class' );
				if ( $classes && ! is_wp_error( $classes ) ) $current_shipping_class = current($classes)->term_id; else $current_shipping_class = '';

				$args = array(
					'taxonomy' 			=> 'product_shipping_class',
					'hide_empty'		=> 0,
					'show_option_none' 	=> __( 'No shipping class', 'colorshop' ),
					'name' 				=> 'product_shipping_class',
					'id'				=> 'product_shipping_class',
					'selected'			=> $current_shipping_class,
					'class'				=> 'select short'
				);
				?><p class="form-field dimensions_field"><label for="product_shipping_class"><?php _e( 'Shipping class', 'colorshop' ); ?></label> <?php wp_dropdown_categories( $args ); ?> <span class="description"><?php _e( 'Shipping classes are used by certain shipping methods to group similar products.', 'colorshop' ); ?></span></p><?php

				do_action( 'colorshop_product_options_shipping' );

			echo '</div>';
			?>

		</div>

		<div id="colorshop_attributes" class="panel cs-metaboxes-wrapper">

			<p class="toolbar">
				<a href="#" class="close_all"><?php _e( 'Close all', 'colorshop' ); ?></a><a href="#" class="expand_all"><?php _e( 'Expand all', 'colorshop' ); ?></a>
			</p>

			<div class="colorshop_attributes cs-metaboxes">

				<?php
					// Array of defined attribute taxonomies
					$attribute_taxonomies = $colorshop->get_attribute_taxonomies();

					// Product attributes - taxonomies and custom, ordered, with visibility and variation attributes set
					$attributes = maybe_unserialize( get_post_meta( $thepostid, '_product_attributes', true ) );

					$i = -1;

					// Taxonomies
					if ( $attribute_taxonomies ) {
				    	foreach ( $attribute_taxonomies as $tax ) { $i++;

				    		// Get name of taxonomy we're now outputting (pa_xxx)
				    		$attribute_taxonomy_name = $colorshop->attribute_taxonomy_name( $tax->attribute_name );

				    		// Ensure it exists
				    		if ( ! taxonomy_exists( $attribute_taxonomy_name ) ) continue;

				    		// Get product data values for current taxonomy - this contains ordering and visibility data
				    		if ( isset( $attributes[ $attribute_taxonomy_name ] ) )
				    			$attribute = $attributes[ $attribute_taxonomy_name ];

				    		$position = empty( $attribute['position'] ) ? 0 : absint( $attribute['position'] );

				    		// Get terms of this taxonomy associated with current product
				    		$post_terms = wp_get_post_terms( $thepostid, $attribute_taxonomy_name );

				    		// Any set?
				    		$has_terms = ( is_wp_error( $post_terms ) || ! $post_terms || sizeof( $post_terms ) == 0 ) ? 0 : 1;
				    		?>
				    		<div class="colorshop_attribute cs-metabox closed taxonomy <?php echo $attribute_taxonomy_name; ?>" rel="<?php echo $position; ?>" <?php if ( ! $has_terms ) echo 'style="display:none"'; ?>>
								<h3>
									<button type="button" class="remove_row button"><?php _e( 'Remove', 'colorshop' ); ?></button>
									<div class="handlediv" title="<?php _e( 'Click to toggle', 'colorshop' ); ?>"></div>
									<strong class="attribute_name"><?php echo apply_filters( 'colorshop_attribute_label', $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name, $tax->attribute_name ); ?></strong>
								</h3>
								<table cellpadding="0" cellspacing="0" class="colorshop_attribute_data cs-metabox-content">
									<tbody>
										<tr>
											<td class="attribute_name">
												<label><?php _e( 'Name', 'colorshop' ); ?>:</label>
												<strong><?php echo $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name; ?></strong>

												<input type="hidden" name="attribute_names[<?php echo $i; ?>]" value="<?php echo esc_attr( $attribute_taxonomy_name ); ?>" />
												<input type="hidden" name="attribute_position[<?php echo $i; ?>]" class="attribute_position" value="<?php echo esc_attr( $position ); ?>" />
												<input type="hidden" name="attribute_is_taxonomy[<?php echo $i; ?>]" value="1" />
											</td>
											<td rowspan="3">
												<label><?php _e( 'Value(s)', 'colorshop' ); ?>:</label>
												<?php if ( $tax->attribute_type == "select" ) : ?>
													<select multiple="multiple" data-placeholder="<?php _e( 'Select terms', 'colorshop' ); ?>" class="multiselect attribute_values" name="attribute_values[<?php echo $i; ?>][]">
														<?php
							        					$all_terms = get_terms( $attribute_taxonomy_name, 'orderby=name&hide_empty=0' );
						        						if ( $all_terms ) {
							        						foreach ( $all_terms as $term ) {
							        							$has_term = has_term( $term->term_id, $attribute_taxonomy_name, $thepostid ) ? 1 : 0;
							        							echo '<option value="' . $term->slug . '" ' . selected( $has_term, 1, false ) . '>' . $term->name . '</option>';
															}
														}
														?>
													</select>

													<button class="button plus select_all_attributes"><?php _e( 'Select all', 'colorshop' ); ?></button> <button class="button minus select_no_attributes"><?php _e( 'Select none', 'colorshop' ); ?></button>

													<button class="button fr plus add_new_attribute" data-attribute="<?php echo $attribute_taxonomy_name; ?>"><?php _e( 'Add new', 'colorshop' ); ?></button>

												<?php elseif ( $tax->attribute_type == "text" ) : ?>
													<input type="text" name="attribute_values[<?php echo $i; ?>]" value="<?php

														// Text attributes should list terms pipe separated
														if ( $post_terms ) {
															$values = array();
															foreach ( $post_terms as $term )
																$values[] = $term->name;
															echo implode( ' | ', $values );
														}

													?>" placeholder="<?php _e( 'Pipe (|) separate terms', 'colorshop' ); ?>" />
												<?php endif; ?>
												<?php do_action( 'colorshop_product_option_terms', $tax, $i ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<label><input type="checkbox" class="checkbox" <?php

													if ( isset( $attribute['is_visible'] ) )
														checked( $attribute['is_visible'], 1 );
													else
														checked( apply_filters( 'default_attribute_visibility', false, $tax ), true );

												?> name="attribute_visibility[<?php echo $i; ?>]" value="1" /> <?php _e( 'Visible on the product page', 'colorshop' ); ?></label>
											</td>
										</tr>
										<tr>
											<td>
												<div class="enable_variation show_if_variable">
												<label><input type="checkbox" class="checkbox" <?php

													if ( isset( $attribute['is_variation'] ) )
														checked( $attribute['is_variation'], 1 );
													else
														checked( apply_filters( 'default_attribute_variation', false, $tax ), true );

												?> name="attribute_variation[<?php echo $i; ?>]" value="1" /> <?php _e( 'Used for variations', 'colorshop' ); ?></label>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
				    		<?php
				    	}
				    }

					// Custom Attributes
					if ( ! empty( $attributes ) ) foreach ( $attributes as $attribute ) {
						if ( $attribute['is_taxonomy'] )
							continue;

						$i++;

			    		$position = empty( $attribute['position'] ) ? 0 : absint( $attribute['position'] );
						?>
			    		<div class="colorshop_attribute cs-metabox closed" rel="<?php echo $position; ?>">
							<h3>
								<button type="button" class="remove_row button"><?php _e( 'Remove', 'colorshop' ); ?></button>
								<div class="handlediv" title="<?php _e( 'Click to toggle', 'colorshop' ); ?>"></div>
								<strong class="attribute_name"><?php echo apply_filters( 'colorshop_attribute_label', esc_html( $attribute['name'] ), esc_html( $attribute['name'] ) ); ?></strong>
							</h3>
							<table cellpadding="0" cellspacing="0" class="colorshop_attribute_data cs-metabox-content">
								<tbody>
									<tr>
										<td class="attribute_name">
											<label><?php _e( 'Name', 'colorshop' ); ?>:</label>
											<input type="text" class="attribute_name" name="attribute_names[<?php echo $i; ?>]" value="<?php echo esc_attr( $attribute['name'] ); ?>" />
											<input type="hidden" name="attribute_position[<?php echo $i; ?>]" class="attribute_position" value="<?php echo esc_attr( $position ); ?>" />
											<input type="hidden" name="attribute_is_taxonomy[<?php echo $i; ?>]" value="0" />
										</td>
										<td rowspan="3">
											<label><?php _e( 'Value(s)', 'colorshop' ); ?>:</label>
											<textarea name="attribute_values[<?php echo $i; ?>]" cols="5" rows="5" placeholder="<?php _e( 'Enter some text, or some attributes by pipe (|) separating values.', 'colorshop' ); ?>"><?php echo esc_textarea( $attribute['value'] ); ?></textarea>
										</td>
									</tr>
									<tr>
										<td>
											<label><input type="checkbox" class="checkbox" <?php checked( $attribute['is_visible'], 1 ); ?> name="attribute_visibility[<?php echo $i; ?>]" value="1" /> <?php _e( 'Visible on the product page', 'colorshop' ); ?></label>
										</td>
									</tr>
									<tr>
										<td>
											<div class="enable_variation show_if_variable">
											<label><input type="checkbox" class="checkbox" <?php checked( $attribute['is_variation'], 1 ); ?> name="attribute_variation[<?php echo $i; ?>]" value="1" /> <?php _e( 'Used for variations', 'colorshop' ); ?></label>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php
					}
				?>
			</div>

			<p class="toolbar">
				<button type="button" class="button button-primary add_attribute"><?php _e( 'Add', 'colorshop' ); ?></button>
				<select name="attribute_taxonomy" class="attribute_taxonomy">
					<option value=""><?php _e( 'Custom product attribute', 'colorshop' ); ?></option>
					<?php
						if ( $attribute_taxonomies ) {
					    	foreach ( $attribute_taxonomies as $tax ) {
					    		$attribute_taxonomy_name = $colorshop->attribute_taxonomy_name( $tax->attribute_name );
					    		$label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
					    		echo '<option value="' . esc_attr( $attribute_taxonomy_name ) . '">' . esc_html( $label ) . '</option>';
					    	}
					    }
					?>
				</select>

				<button type="button" class="button save_attributes"><?php _e( 'Save attributes', 'colorshop' ); ?></button>
			</p>
		</div>
		<div id="linked_product_data" class="panel colorshop_options_panel">

			<div class="options_group">

			<p class="form-field"><label for="upsell_ids"><?php _e( 'Up-Sells', 'colorshop' ); ?></label>
			<select id="upsell_ids" name="upsell_ids[]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'colorshop' ); ?>">
				<?php
					$upsell_ids = get_post_meta( $post->ID, '_upsell_ids', true );
					$product_ids = ! empty( $upsell_ids ) ? array_map( 'absint',  $upsell_ids ) : null;
					if ( $product_ids ) {
						foreach ( $product_ids as $product_id ) {

							$product      = get_product( $product_id );
							$product_name = colorshop_get_formatted_product_name( $product );

							echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . esc_html( $product_name ) . '</option>';
						}
					}
				?>
			</select> <img class="help_tip" data-tip='<?php _e( 'Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'colorshop' ) ?>' src="<?php echo $colorshop->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /></p>

			<p class="form-field"><label for="crosssell_ids"><?php _e( 'Cross-Sells', 'colorshop' ); ?></label>
			<select id="crosssell_ids" name="crosssell_ids[]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'colorshop' ); ?>">
				<?php
					$crosssell_ids = get_post_meta( $post->ID, '_crosssell_ids', true );
					$product_ids = ! empty( $crosssell_ids ) ? array_map( 'absint',  $crosssell_ids ) : null;
					if ( $product_ids ) {
						foreach ( $product_ids as $product_id ) {

							$product      = get_product( $product_id );
							$product_name = colorshop_get_formatted_product_name( $product );

							echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . esc_html( $product_name ) . '</option>';
						}
					}
				?>
			</select> <img class="help_tip" data-tip='<?php _e( 'Cross-sells are products which you promote in the cart, based on the current product.', 'colorshop' ) ?>' src="<?php echo $colorshop->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /></p>

			</div>

			<?php

			echo '<div class="options_group grouping show_if_simple show_if_external">';

				// List Grouped products
				$post_parents = array();
				$post_parents[''] = __( 'Choose a grouped product&hellip;', 'colorshop' );

				$posts_in = array_unique( (array) get_objects_in_term( get_term_by( 'slug', 'grouped', 'product_type' )->term_id, 'product_type' ) );
				if ( sizeof( $posts_in ) > 0 ) {
					$args = array(
						'post_type'		=> 'product',
						'post_status' 	=> 'any',
						'numberposts' 	=> -1,
						'orderby' 		=> 'title',
						'order' 		=> 'asc',
						'post_parent' 	=> 0,
						'include' 		=> $posts_in,
					);
					$grouped_products = get_posts( $args );

					if ( $grouped_products ) {
						foreach ( $grouped_products as $product ) {

							if ( $product->ID == $post->ID )
								continue;

							$post_parents[ $product->ID ] = $product->post_title;
						}
					}
				}

				colorshop_wp_select( array( 'id' => 'parent_id', 'label' => __( 'Grouping', 'colorshop' ), 'value' => absint( $post->post_parent ), 'options' => $post_parents, 'desc_tip' => true, 'description' => __( 'Set this option to make this product part of a grouped product.', 'colorshop' ) ) );

				colorshop_wp_hidden_input( array( 'id' => 'previous_parent_id', 'value' => absint( $post->post_parent ) ) );

				do_action( 'colorshop_product_options_grouping' );

			echo '</div>';
			?>

			<?php do_action( 'colorshop_product_options_related' ); ?>

		</div>

		<div id="advanced_product_data" class="panel colorshop_options_panel">

			<?php

			echo '<div class="options_group hide_if_external">';

				// Purchase note
				colorshop_wp_textarea_input(  array( 'id' => '_purchase_note', 'label' => __( 'Purchase Note', 'colorshop' ), 'description' => __( 'Enter an optional note to send the customer after purchase.', 'colorshop' ) ) );

			echo '</div>';

			echo '<div class="options_group">';

				// menu_order
				colorshop_wp_text_input(  array( 'id' => 'menu_order', 'label' => __( 'Menu order', 'colorshop' ), 'description' => __( 'Custom ordering position.', 'colorshop' ), 'value' => intval( $post->menu_order ), 'type' => 'number', 'custom_attributes' => array(
					'step' 	=> '1'
				)  ) );

			echo '</div>';

			echo '<div class="options_group reviews">';

				colorshop_wp_checkbox( array( 'id' => 'comment_status', 'label' => __( 'Enable reviews', 'colorshop' ), 'cbvalue' => 'open', 'value' => esc_attr( $post->comment_status ) ) );

				do_action( 'colorshop_product_options_reviews' );

			echo '</div>';
			?>

		</div>

		<?php do_action( 'colorshop_product_write_panels' ); ?>

		<div class="clear"></div>

	</div>
	<?php
}


/**
 * Save the product data meta box.
 *
 * @access public
 * @param mixed $post_id
 * @param mixed $post
 * @return void
 */
function colorshop_process_product_meta( $post_id, $post ) {
	global $wpdb, $colorshop, $colorshop_errors;
	
	// Add any default post meta
	add_post_meta( $post_id, 'total_sales', '0', true );

	// Get types
	$product_type 		= empty( $_POST['product-type'] ) ? 'simple' : sanitize_title( stripslashes( $_POST['product-type'] ) );
	$is_downloadable 	= isset( $_POST['_downloadable'] ) ? 'yes' : 'no';
	$is_virtual 		= isset( $_POST['_virtual'] ) ? 'yes' : 'no';

	// Product type + Downloadable/Virtual
	wp_set_object_terms( $post_id, $product_type, 'product_type' );
	update_post_meta( $post_id, '_downloadable', $is_downloadable );
	update_post_meta( $post_id, '_virtual', $is_virtual );

	// Gallery Images
	$attachment_ids = array_filter( explode( ',', colorshop_clean( $_POST['product_image_gallery'] ) ) );
	update_post_meta( $post_id, '_product_image_gallery', implode( ',', $attachment_ids ) );

	// Update post meta
	update_post_meta( $post_id, '_regular_price', stripslashes( $_POST['_regular_price'] ) );
	update_post_meta( $post_id, '_sale_price', stripslashes( $_POST['_sale_price'] ) );
	update_post_meta( $post_id, '_tax_status', stripslashes( $_POST['_tax_status'] ) );
	update_post_meta( $post_id, '_tax_class', stripslashes( $_POST['_tax_class'] ) );
	update_post_meta( $post_id, '_visibility', stripslashes( $_POST['_visibility'] ) );
	update_post_meta( $post_id, '_purchase_note', stripslashes( $_POST['_purchase_note'] ) );
	update_post_meta( $post_id, '_featured', isset( $_POST['_featured'] ) ? 'yes' : 'no' );

	// Dimensions
	if ( $is_virtual == 'no' ) {
		update_post_meta( $post_id, '_weight', stripslashes( $_POST['_weight'] ) );
		update_post_meta( $post_id, '_length', stripslashes( $_POST['_length'] ) );
		update_post_meta( $post_id, '_width', stripslashes( $_POST['_width'] ) );
		update_post_meta( $post_id, '_height', stripslashes( $_POST['_height'] ) );
	} else {
		update_post_meta( $post_id, '_weight', '' );
		update_post_meta( $post_id, '_length', '' );
		update_post_meta( $post_id, '_width', '' );
		update_post_meta( $post_id, '_height', '' );
	}

	// Save shipping class
	$product_shipping_class = $_POST['product_shipping_class'] > 0 && $product_type != 'external' ? absint( $_POST['product_shipping_class'] ) : '';
	wp_set_object_terms( $post_id, $product_shipping_class, 'product_shipping_class');

	// Unique SKU
	$sku				= get_post_meta($post_id, '_sku', true);
	$new_sku 			= esc_html( trim( stripslashes( $_POST['_sku'] ) ) );
	if ( $new_sku == '' ) {
		update_post_meta( $post_id, '_sku', '' );
	} elseif ( $new_sku !== $sku ) {
		if ( ! empty( $new_sku ) ) {
			if (
				$wpdb->get_var( $wpdb->prepare("
					SELECT $wpdb->posts.ID
				    FROM $wpdb->posts
				    LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
				    WHERE $wpdb->posts.post_type = 'product'
				    AND $wpdb->posts.post_status = 'publish'
				    AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'
				 ", $new_sku ) )
				) {
				$colorshop_errors[] = __( 'Product SKU must be unique.', 'colorshop' );
			} else {
				update_post_meta( $post_id, '_sku', $new_sku );
			}
		} else {
			update_post_meta( $post_id, '_sku', '' );
		}
	}
	

	// Save Attributes
	$attributes = array();

	if ( isset( $_POST['attribute_names'] ) ) {
		$attribute_names = $_POST['attribute_names'];
		$attribute_values = $_POST['attribute_values'];
		
		if ( isset( $_POST['attribute_visibility'] ) )
			$attribute_visibility = $_POST['attribute_visibility'];

		if ( isset( $_POST['attribute_variation'] ) )
			$attribute_variation = $_POST['attribute_variation'];

		$attribute_is_taxonomy = $_POST['attribute_is_taxonomy'];
		$attribute_position = $_POST['attribute_position'];

		$attribute_names_count = sizeof( $attribute_names );
		
		

		for ( $i=0; $i < $attribute_names_count; $i++ ) {
			if ( ! $attribute_names[ $i ] )
				continue;

			$is_visible 	= isset( $attribute_visibility[ $i ] ) ? 1 : 0;
			$is_variation 	= isset( $attribute_variation[ $i ] ) ? 1 : 0;
			$is_taxonomy 	= $attribute_is_taxonomy[ $i ] ? 1 : 0;

			if ( $is_taxonomy ) {

				if ( isset( $attribute_values[ $i ] ) ) {
					
			 		// Format values
			 		if ( is_array( $attribute_values[ $i ] ) ) {
				 		$values = array_map( 'colorshop_clean', $attribute_values[ $i ] );
				 	} else {
				 		// Text based, separate by pipe
				 		$values = array_map( 'colorshop_clean', explode( '|', $attribute_values[ $i ] ) );
				 	}

				 	// Remove empty items in the array
				 	$values = array_filter( $values );

			 	} else {
			 		$values = array();
			 	}

		 		// Update post terms
		 		if ( taxonomy_exists( $attribute_names[ $i ] ) )
		 			wp_set_object_terms( $post_id, $values, $attribute_names[ $i ] );

		 		if ( $values ) {
			 		// Add attribute to array, but don't set values
			 		$attributes[ sanitize_title( $attribute_names[ $i ] ) ] = array(
				 		'name' 			=> colorshop_clean( $attribute_names[ $i ] ),
				 		'value' 		=> '',
				 		'position' 		=> $attribute_position[ $i ],
				 		'is_visible' 	=> $is_visible,
				 		'is_variation' 	=> $is_variation,
				 		'is_taxonomy' 	=> $is_taxonomy
				 	);
			 	}

		 	} elseif ( isset( $attribute_values[ $i ] ) ) {

		 		// Text based, separate by pipe
		 		$values = implode( ' | ', array_map( 'colorshop_clean', explode( '|', $attribute_values[ $i ] ) ) );

		 		// Custom attribute - Add attribute to array and set the values
			 	$attributes[ sanitize_title( $attribute_names[ $i ] ) ] = array(
			 		'name' 			=> colorshop_clean( $attribute_names[ $i ] ),
			 		'value' 		=> $values,
			 		'position' 		=> $attribute_position[ $i ],
			 		'is_visible' 	=> $is_visible,
			 		'is_variation' 	=> $is_variation,
			 		'is_taxonomy' 	=> $is_taxonomy
			 	);
		 	}

		 }
	}

	if ( ! function_exists( 'attributes_cmp' ) ) {
		function attributes_cmp( $a, $b ) {
		    if ( $a['position'] == $b['position'] ) return 0;
		    return ( $a['position'] < $b['position'] ) ? -1 : 1;
		}
	}
	uasort( $attributes, 'attributes_cmp' );
	
	update_post_meta( $post_id, '_product_attributes', $attributes );

	// Sales and prices
	if ( in_array( $product_type, array( 'variable', 'grouped' ) ) ) {

		// Variable and grouped products have no prices
		update_post_meta( $post_id, '_regular_price', '' );
		update_post_meta( $post_id, '_sale_price', '' );
		update_post_meta( $post_id, '_sale_price_dates_from', '' );
		update_post_meta( $post_id, '_sale_price_dates_to', '' );
		update_post_meta( $post_id, '_price', '' );

	} else {

		$date_from = isset( $_POST['_sale_price_dates_from'] ) ? $_POST['_sale_price_dates_from'] : '';
		$date_to = isset( $_POST['_sale_price_dates_to'] ) ? $_POST['_sale_price_dates_to'] : '';

		// Dates
		if ( $date_from )
			update_post_meta( $post_id, '_sale_price_dates_from', strtotime( $date_from ) );
		else
			update_post_meta( $post_id, '_sale_price_dates_from', '' );

		if ( $date_to )
			update_post_meta( $post_id, '_sale_price_dates_to', strtotime( $date_to ) );
		else
			update_post_meta( $post_id, '_sale_price_dates_to', '' );

		if ( $date_to && ! $date_from )
			update_post_meta( $post_id, '_sale_price_dates_from', strtotime( 'NOW', current_time( 'timestamp' ) ) );

		// Update price if on sale
		if ( $_POST['_sale_price'] != '' && $date_to == '' && $date_from == '' )
			update_post_meta( $post_id, '_price', stripslashes( $_POST['_sale_price'] ) );
		else
			update_post_meta( $post_id, '_price', stripslashes( $_POST['_regular_price'] ) );

		if ( $_POST['_sale_price'] != '' && $date_from && strtotime( $date_from ) < strtotime( 'NOW', current_time( 'timestamp' ) ) )
			update_post_meta( $post_id, '_price', stripslashes($_POST['_sale_price']) );

		if ( $date_to && strtotime( $date_to ) < strtotime( 'NOW', current_time( 'timestamp' ) ) ) {
			update_post_meta( $post_id, '_price', stripslashes($_POST['_regular_price']) );
			update_post_meta( $post_id, '_sale_price_dates_from', '');
			update_post_meta( $post_id, '_sale_price_dates_to', '');
		}
	}

	// Update parent if grouped so price sorting works and stays in sync with the cheapest child
	if ( $post->post_parent > 0 || $product_type == 'grouped' || $_POST['previous_parent_id'] > 0 ) {

		$clear_parent_ids = array();

		if ( $post->post_parent > 0 )
			$clear_parent_ids[] = $post->post_parent;

		if ( $product_type == 'grouped' )
			$clear_parent_ids[] = $post_id;

		if ( $_POST['previous_parent_id'] > 0 )
			$clear_parent_ids[] = absint( $_POST['previous_parent_id'] );

		if ( $clear_parent_ids ) {
			foreach( $clear_parent_ids as $clear_id ) {

				$children_by_price = get_posts( array(
					'post_parent' 	=> $clear_id,
					'orderby' 		=> 'meta_value_num',
					'order'			=> 'asc',
					'meta_key'		=> '_price',
					'posts_per_page'=> 1,
					'post_type' 	=> 'product',
					'fields' 		=> 'ids'
				) );
				if ( $children_by_price ) {
					foreach ( $children_by_price as $child ) {
						$child_price = get_post_meta( $child, '_price', true );
						update_post_meta( $clear_id, '_price', $child_price );
					}
				}

				// Clear cache/transients
				$colorshop->clear_product_transients( $clear_id );
			}
		}
	}

	// Sold Individuall
	if ( ! empty( $_POST['_sold_individually'] ) ) {
		update_post_meta( $post_id, '_sold_individually', 'yes' );
	} else {
		update_post_meta( $post_id, '_sold_individually', '' );
	}

	// Stock Data
	if ( get_option('colorshop_manage_stock') == 'yes' ) {

		if ( $product_type == 'grouped' ) {

			update_post_meta( $post_id, '_stock_status', stripslashes( $_POST['_stock_status'] ) );
			update_post_meta( $post_id, '_stock', '' );
			update_post_meta( $post_id, '_manage_stock', 'no' );
			update_post_meta( $post_id, '_backorders', 'no' );

		} elseif ( $product_type == 'external' ) {

			update_post_meta( $post_id, '_stock_status', 'instock' );
			update_post_meta( $post_id, '_stock', '' );
			update_post_meta( $post_id, '_manage_stock', 'no' );
			update_post_meta( $post_id, '_backorders', 'no' );

		} elseif ( ! empty( $_POST['_manage_stock'] ) ) {

			// Manage stock
			update_post_meta( $post_id, '_stock', (int) $_POST['_stock'] );
			update_post_meta( $post_id, '_stock_status', stripslashes( $_POST['_stock_status'] ) );
			update_post_meta( $post_id, '_backorders', stripslashes( $_POST['_backorders'] ) );
			update_post_meta( $post_id, '_manage_stock', 'yes' );

			// Check stock level
			if ( $product_type !== 'variable' && $_POST['_backorders'] == 'no' && (int) $_POST['_stock'] < 1 )
				update_post_meta( $post_id, '_stock_status', 'outofstock' );

		} else {

			// Don't manage stock
			update_post_meta( $post_id, '_stock', '' );
			update_post_meta( $post_id, '_stock_status', stripslashes( $_POST['_stock_status'] ) );
			update_post_meta( $post_id, '_backorders', stripslashes( $_POST['_backorders'] ) );
			update_post_meta( $post_id, '_manage_stock', 'no' );

		}

	} else {

		update_post_meta( $post_id, '_stock_status', stripslashes( $_POST['_stock_status'] ) );

	}

	// Upsells
	if ( isset( $_POST['upsell_ids'] ) ) {
		$upsells = array();
		$ids = $_POST['upsell_ids'];
		foreach ( $ids as $id )
			if ( $id && $id > 0 )
				$upsells[] = $id;

		update_post_meta( $post_id, '_upsell_ids', $upsells );
	} else {
		delete_post_meta( $post_id, '_upsell_ids' );
	}

	// Cross sells
	if ( isset( $_POST['crosssell_ids'] ) ) {
		$crosssells = array();
		$ids = $_POST['crosssell_ids'];
		foreach ( $ids as $id )
			if ( $id && $id > 0 )
				$crosssells[] = $id;

		update_post_meta( $post_id, '_crosssell_ids', $crosssells );
	} else {
		delete_post_meta( $post_id, '_crosssell_ids' );
	}

	// Downloadable options
	if ( $is_downloadable == 'yes' ) {

		$_download_limit = absint( $_POST['_download_limit'] );
		if ( ! $_download_limit )
			$_download_limit = ''; // 0 or blank = unlimited

		$_download_expiry = absint( $_POST['_download_expiry'] );
		if ( ! $_download_expiry )
			$_download_expiry = ''; // 0 or blank = unlimited

		// file paths will be stored in an array keyed off md5(file path)
		if ( isset( $_POST['_file_paths'] ) ) {
			$_file_paths = array();
			$file_paths = str_replace( "\r\n", "\n", esc_attr( $_POST['_file_paths'] ) );
			$file_paths = trim( preg_replace( "/\n+/", "\n", $file_paths ) );
			if ( $file_paths ) {
				$file_paths = explode( "\n", $file_paths );

				foreach ( $file_paths as $file_path ) {
					$file_path = trim( $file_path );
					$_file_paths[ md5( $file_path ) ] = $file_path;
				}
			}

			// grant permission to any newly added files on any existing orders for this product
			do_action( 'colorshop_process_product_file_download_paths', $post_id, 0, $_file_paths );

			update_post_meta( $post_id, '_file_paths', $_file_paths );
		}
		if ( isset( $_POST['_download_limit'] ) )
			update_post_meta( $post_id, '_download_limit', esc_attr( $_download_limit ) );
		if ( isset( $_POST['_download_expiry'] ) )
			update_post_meta( $post_id, '_download_expiry', esc_attr( $_download_expiry ) );
	}

	// Product url
	if ( $product_type == 'external' ) {
		if ( isset( $_POST['_product_url'] ) && $_POST['_product_url'] )
			update_post_meta( $post_id, '_product_url', esc_attr( $_POST['_product_url'] ) );
		if ( isset( $_POST['_button_text'] ) && $_POST['_button_text'] )
			update_post_meta( $post_id, '_button_text', esc_attr( $_POST['_button_text'] ) );
	}

	// Do action for product type
	do_action( 'colorshop_process_product_meta_' . $product_type, $post_id );

	// Clear cache/transients
	$colorshop->clear_product_transients( $post_id );
}
add_action('colorshop_process_product_meta', 'colorshop_process_product_meta', 1, 2);


/**
 * Output product visibility options.
 *
 * @access public
 * @return void
 */
function colorshop_product_data_visibility() {
	global $post;

	if ( $post->post_type != 'product' )
		return;

	$current_visibility = ( $current_visibility = get_post_meta( $post->ID, '_visibility', true ) ) ? $current_visibility : 'visible';
	$current_featured 	= ( $current_featured = get_post_meta( $post->ID, '_featured', true ) ) ? $current_featured : 'no';

	$visibility_options = apply_filters( 'colorshop_product_visibility_options', array(
		'visible' 	=> __( 'Catalog/search', 'colorshop' ),
		'catalog' 	=> __( 'Catalog', 'colorshop' ),
		'search' 	=> __( 'Search', 'colorshop' ),
		'hidden' 	=> __( 'Hidden', 'colorshop' )
	) );
	?>
	<div class="misc-pub-section" id="catalog-visibility">
		<?php _e( 'Catalog visibility:', 'colorshop' ); ?> <strong id="catalog-visibility-display"><?php

			echo isset( $visibility_options[ $current_visibility ]  ) ? esc_html( $visibility_options[ $current_visibility ] ) : esc_html( $current_visibility );

			if ( $current_featured == 'yes' )
				echo ', ' . __( 'Featured', 'colorshop' );
		?></strong>

		<a href="#catalog-visibility" class="edit-catalog-visibility hide-if-no-js"><?php _e( 'Edit', 'colorshop' ); ?></a>

		<div id="catalog-visibility-select" class="hide-if-js">

			<input type="hidden" name="current_visibility" id="current_visibility" value="<?php echo esc_attr( $current_visibility ); ?>" />
			<input type="hidden" name="current_featured" id="current_featured" value="<?php echo esc_attr( $current_featured ); ?>" />

			<?php
				echo '<p>' . __( 'Define the loops this product should be visible in. The product will still be accessible directly.', 'colorshop' ) . '</p>';

				foreach ( $visibility_options as $name => $label ) {
					echo '<input type="radio" name="_visibility" id="_visibility_' . esc_attr( $name ) . '" value="' . esc_attr( $name ) . '" ' . checked( $current_visibility, $name, false ) . ' data-label="' . esc_attr( $label ) . '" /> <label for="_visibility_' . esc_attr( $name ) . '" class="selectit">' . esc_html( $label ) . '</label><br />';
				}

				echo '<p>' . __( 'Enable this option to feature this product.', 'colorshop' ) . '</p>';

				echo '<input type="checkbox" name="_featured" id="_featured" ' . checked( $current_featured, 'yes', false ) . ' /> <label for="_featured">' . __( 'Featured Product', 'colorshop' ) . '</label><br />';
			?>

			<p>
			 <a href="#catalog-visibility" class="save-post-visibility hide-if-no-js button"><?php _e( 'OK', 'colorshop' ); ?></a>
			 <a href="#catalog-visibility" class="cancel-post-visibility hide-if-no-js"><?php _e( 'Cancel', 'colorshop' ); ?></a>
			</p>

		</div>
	</div>
	<?php
}

add_action( 'post_submitbox_misc_actions', 'colorshop_product_data_visibility' );
