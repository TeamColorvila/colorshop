<?php
/**
 * Admin taxonomy functions
 *
 * These functions control admin interface bits like category ordering.
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/Taxonomies
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Category thumbnail fields.
 *
 * @access public
 * @return void
 */
function colorshop_add_category_fields() {
	global $colorshop;
	?>
	<div class="form-field">
		<label for="display_type"><?php _e( 'Display type', 'colorshop' ); ?></label>
		<select id="display_type" name="display_type" class="postform">
			<option value=""><?php _e( 'Default', 'colorshop' ); ?></option>
			<option value="products"><?php _e( 'Products', 'colorshop' ); ?></option>
			<option value="subcategories"><?php _e( 'Subcategories', 'colorshop' ); ?></option>
			<option value="both"><?php _e( 'Both', 'colorshop' ); ?></option>
		</select>
	</div>
	<div class="form-field">
		<label><?php _e( 'Thumbnail', 'colorshop' ); ?></label>
		<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo colorshop_placeholder_img_src(); ?>" width="60px" height="60px" /></div>
		<div style="line-height:60px;">
			<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" />
			<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', 'colorshop' ); ?></button>
			<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', 'colorshop' ); ?></button>
		</div>
		<script type="text/javascript">

			 // Only show the "remove image" button when needed
			 if ( ! jQuery('#product_cat_thumbnail_id').val() )
				 jQuery('.remove_image_button').hide();

			// Uploading files
			var file_frame;

			jQuery(document).on( 'click', '.upload_image_button', function( event ){

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.downloadable_file = wp.media({
					title: '<?php _e( 'Choose an image', 'colorshop' ); ?>',
					button: {
						text: '<?php _e( 'Use image', 'colorshop' ); ?>',
					},
					multiple: false
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					attachment = file_frame.state().get('selection').first().toJSON();

					jQuery('#product_cat_thumbnail_id').val( attachment.id );
					jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
					jQuery('.remove_image_button').show();
				});

				// Finally, open the modal.
				file_frame.open();
			});

			jQuery(document).on( 'click', '.remove_image_button', function( event ){
				jQuery('#product_cat_thumbnail img').attr('src', '<?php echo colorshop_placeholder_img_src(); ?>');
				jQuery('#product_cat_thumbnail_id').val('');
				jQuery('.remove_image_button').hide();
				return false;
			});
			
		</script>
		<div class="clear"></div>
	</div>
	<div class="form-field colorshop_attributes">
		<label><?php _e( 'Attribute Group', 'colorshop' ); ?></label>
		<select class="chosen" style="width:95%;" name="group_category[]" id="group_category" multiple data-placeholder="Your Attribute Group" >
				<?php
					$categories = $colorshop->get_attribute_taxonomies();
					foreach ($categories as $value) {
						echo '<option>' . $value->attribute_name .'</option>';
					}
				?>
			</select>
			<script>jQuery(".chosen").chosen();</script>
	</div>
	<?php
}

add_action( 'product_cat_add_form_fields', 'colorshop_add_category_fields' );

/**
 * Edit category thumbnail field.
 *
 * @access public
 * @param mixed $term Term (category) being edited
 * @param mixed $taxonomy Taxonomy of the term being edited
 * @return void
 */
function colorshop_edit_category_fields( $term, $taxonomy ) {
	global $colorshop;

	$display_type	= get_colorshop_term_meta( $term->term_id, 'display_type', true );
	$image 			= '';
	$thumbnail_id 	= absint( get_colorshop_term_meta( $term->term_id, 'thumbnail_id', true ) );
	$group_category = get_colorshop_term_meta( $term->term_id, 'group_category', true );
	if (empty($group_category))
		$group_category = array();
	if ($thumbnail_id) :
		$image = wp_get_attachment_url( $thumbnail_id );
	else :
		$image = colorshop_placeholder_img_src();
	endif;
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e( 'Display type', 'colorshop' ); ?></label></th>
		<td>
			<select id="display_type" name="display_type" class="postform">
				<option value="" <?php selected( '', $display_type ); ?>><?php _e( 'Default', 'colorshop' ); ?></option>
				<option value="products" <?php selected( 'products', $display_type ); ?>><?php _e( 'Products', 'colorshop' ); ?></option>
				<option value="subcategories" <?php selected( 'subcategories', $display_type ); ?>><?php _e( 'Subcategories', 'colorshop' ); ?></option>
				<option value="both" <?php selected( 'both', $display_type ); ?>><?php _e( 'Both', 'colorshop' ); ?></option>
			</select>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'colorshop' ); ?></label></th>
		<td>
			<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo $image; ?>" width="60px" height="60px" /></div>
			<div style="line-height:60px;">
				<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
				<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', 'colorshop' ); ?></button>
				<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', 'colorshop' ); ?></button>
			</div>
			<script type="text/javascript">

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', 'colorshop' ); ?>',
						button: {
							text: '<?php _e( 'Use image', 'colorshop' ); ?>',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#product_cat_thumbnail_id').val( attachment.id );
						jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#product_cat_thumbnail img').attr('src', '<?php echo colorshop_placeholder_img_src(); ?>');
					jQuery('#product_cat_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e( 'Attribute Group', 'colorshop' ); ?></label></th>
		<td>
			<select class="chosen" style="width:95%;" multiple data-placeholder="Your Attribute Group" name="group_category[]" id="group_category" >
				<?php
					$categories = $colorshop->get_attribute_taxonomies();
					foreach ($categories as $value) {
				?>
				<option <?php if (in_array($value->attribute_name, $group_category)) echo 'selected'; ?>><?php echo $value->attribute_name; ?></option>
				<?php
					}
				?>
			</select>
			<script>jQuery(".chosen").chosen();</script>
		</td>
	</tr>
	<?php
}

add_action( 'product_cat_edit_form_fields', 'colorshop_edit_category_fields', 10,2 );


/**
 * colorshop_category_fields_save function.
 *
 * @access public
 * @param mixed $term_id Term ID being saved
 * @param mixed $tt_id
 * @param mixed $taxonomy Taxonomy of the term being saved
 * @return void
 */
function colorshop_category_fields_save( $term_id, $tt_id, $taxonomy ) {
	if ( isset( $_POST['display_type'] ) )
		update_colorshop_term_meta( $term_id, 'display_type', esc_attr( $_POST['display_type'] ) );

	if ( isset( $_POST['product_cat_thumbnail_id'] ) )
		update_colorshop_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_cat_thumbnail_id'] ) );
	
	if ( isset( $_POST['group_category'] ) )
		update_colorshop_term_meta($term_id, 'group_category', array_map( 'strip_tags', $_POST['group_category'] ) );

	delete_transient( 'cs_term_counts' );
}

add_action( 'created_term', 'colorshop_category_fields_save', 10,3 );
add_action( 'edit_term', 'colorshop_category_fields_save', 10,3 );


/**
 * Description for product_cat page to aid users.
 *
 * @access public
 * @return void
 */
function colorshop_product_cat_description() {

	echo wpautop( __( 'Product categories for your store can be managed here. To change the order of categories on the front-end you can drag and drop to sort them. To see more categories listed click the "screen options" link at the top of the page.', 'colorshop' ) );

}

add_action( 'product_cat_pre_add_form', 'colorshop_product_cat_description' );


/**
 * Description for shipping class page to aid users.
 *
 * @access public
 * @return void
 */
function colorshop_shipping_class_description() {

	echo wpautop(__( 'Shipping classes can be used to group products of similar type. These groups can then be used by certain shipping methods to provide different rates to different products.', 'colorshop' ));

}

add_action( 'product_shipping_class_pre_add_form', 'colorshop_shipping_class_description' );


/**
 * Fix for the per_page option
 *
 * Trac: http://core.trac.wordpress.org/ticket/19465
 *
 * @access public
 * @param mixed $per_page
 * @param mixed $post_type
 * @return void
 */
function colorshop_fix_edit_posts_per_page( $per_page, $post_type ) {

	if ( $post_type !== 'product' )
		return $per_page;

	$screen = get_current_screen();

	if ( strstr( $screen->id, '-' ) ) {

		$option = 'edit_' . str_replace( 'edit-', '', $screen->id ) . '_per_page';

		if ( isset( $_POST['wp_screen_options']['option'] ) && $_POST['wp_screen_options']['option'] == $option ) {

			update_user_meta( get_current_user_id(), $option, $_POST['wp_screen_options']['value'] );

			wp_redirect( remove_query_arg( array('pagenum', 'apage', 'paged'), wp_get_referer() ) );
			exit;

		}

		$user_per_page = (int) get_user_meta( get_current_user_id(), $option, true );

		if ( $user_per_page )
			$per_page = $user_per_page;

	}

	return $per_page;
}

add_filter( 'edit_posts_per_page', 'colorshop_fix_edit_posts_per_page', 1, 2 );


/**
 * Thumbnail column added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function colorshop_product_cat_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = __( 'Image', 'colorshop' );

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}

add_filter( 'manage_edit-product_cat_columns', 'colorshop_product_cat_columns' );


/**
 * Thumbnail column value added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function colorshop_product_cat_column( $columns, $column, $id ) {
	global $colorshop;

	if ( $column == 'thumb' ) {

		$image 			= '';
		$thumbnail_id 	= get_colorshop_term_meta( $id, 'thumbnail_id', true );

		if ($thumbnail_id)
			$image = wp_get_attachment_url( $thumbnail_id );
		else
			$image = colorshop_placeholder_img_src();

		$columns .= '<img src="' . $image . '" alt="Thumbnail" class="wp-post-image" height="48" width="48" />';

	}

	return $columns;
}

add_filter( 'manage_product_cat_custom_column', 'colorshop_product_cat_column', 10, 3 );


/**
 * Add a configure button column for the shipping classes page.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function colorshop_shipping_class_columns( $columns ) {
	$columns['edit'] = '&nbsp;';
	return $columns;
}

add_filter( 'manage_edit-product_shipping_class_columns', 'colorshop_shipping_class_columns' );


/**
 * Add a configure button for the shipping classes page.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function colorshop_shipping_class_column( $columns, $column, $id ) {
	if ( $column == 'edit' )
		$columns .= '<a href="'. admin_url( 'edit-tags.php?action=edit&taxonomy=product_shipping_class&tag_ID='. $id .'&post_type=product' ) .'" class="button alignright">'.__( 'Edit Class', 'colorshop' ).'</a>';

	return $columns;
}

add_filter( 'manage_product_shipping_class_custom_column', 'colorshop_shipping_class_column', 10, 3 );