<?php
/**
 * Admin functions for the shop_order post type.
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/Orders
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Disable the auto-save functionality for Orders.
 *
 * @access public
 * @return void
 */
function colorshop_disable_autosave_for_orders(){
    global $post;

    if ( $post && get_post_type( $post->ID ) === 'shop_order' ) {
        wp_dequeue_script( 'autosave' );
    }
}

add_action( 'admin_print_scripts', 'colorshop_disable_autosave_for_orders' );


/**
 * Define columns for the orders page.
 *
 * @access public
 * @param mixed $columns
 * @return array
 */
function colorshop_edit_order_columns($columns){
	global $colorshop;

	$columns = array();

	$columns["cb"] 					= "<input type=\"checkbox\" />";
	$columns["order_status"] 		= __( 'Status', 'colorshop' );
	$columns["order_title"] 		= __( 'Order', 'colorshop' );
	$columns["billing_address"] 	= __( 'Billing', 'colorshop' );
	$columns["shipping_address"] 	= __( 'Shipping', 'colorshop' );
	$columns["total_cost"] 			= __( 'Order Total', 'colorshop' );
	$columns["order_comments"] 		= '<img alt="' . esc_attr__( 'Order Notes', 'colorshop' ) . '" src="' . $colorshop->plugin_url() . '/assets/images/order-notes_head.png" class="tips" data-tip="' . __( 'Order Notes', 'colorshop' ) . '" width="12" height="12" />';
	$columns["note"] 				= '<img src="' . $colorshop->plugin_url() . '/assets/images/note_head.png" alt="' . __( 'Customer Notes', 'colorshop' ) . '" class="tips" data-tip="' . __( 'Customer Notes', 'colorshop' ) . '" width="12" height="12" />';
	$columns["order_date"] 			= __( 'Date', 'colorshop' );
	$columns["order_actions"] 		= __( 'Actions', 'colorshop' );

	return $columns;
}

add_filter('manage_edit-shop_order_columns', 'colorshop_edit_order_columns');


/**
 * Values for the custom columns on the orders page.
 *
 * @access public
 * @param mixed $column
 * @return void
 */
function colorshop_custom_order_columns( $column ) {
	global $post, $colorshop, $the_order;

	if ( empty( $the_order ) || $the_order->id != $post->ID )
		$the_order = new CS_Order( $post->ID );

	switch ( $column ) {
		case "order_status" :

			printf( '<mark class="%s tips" data-tip="%s">%s</mark>', sanitize_title( $the_order->status ), esc_html__( $the_order->status, 'colorshop' ), esc_html__( $the_order->status, 'colorshop' ) );

		break;
		case "order_title" :

			if ( $the_order->user_id )
				$user_info = get_userdata( $the_order->user_id );

			if ( ! empty( $user_info ) ) {

            	$user = '<a href="user-edit.php?user_id=' . absint( $user_info->ID ) . '">';

            	if ( $user_info->first_name || $user_info->last_name )
            		$user .= esc_html( $user_info->first_name . ' ' . $user_info->last_name );
            	else
            		$user .= esc_html( $user_info->display_name );

            	$user .= '</a>';

           	} else {
           		$user = __( 'Guest', 'colorshop' );
           	}

           	echo '<a href="' . admin_url( 'post.php?post=' . absint( $post->ID ) . '&action=edit' ) . '"><strong>' . sprintf( __( 'Order %s', 'colorshop' ), esc_attr( $the_order->get_order_number() ) ) . '</strong></a> ' . __( 'made by', 'colorshop' ) . ' ' . $user;

           	if ( $the_order->billing_email )
        		echo '<small class="meta">' . __( 'Email:', 'colorshop' ) . ' ' . '<a href="' . esc_url( 'mailto:' . $the_order->billing_email ) . '">' . esc_html( $the_order->billing_email ) . '</a></small>';

        	if ( $the_order->billing_phone )
        		echo '<small class="meta">' . __( 'Tel:', 'colorshop' ) . ' ' . esc_html( $the_order->billing_phone ) . '</small>';

		break;
		case "billing_address" :
			if ( $the_order->get_formatted_billing_address() )
        		echo '<a target="_blank" href="' . esc_url( 'http://maps.google.com/maps?&q=' . urlencode( $the_order->get_billing_address() ) . '&z=16' ) . '">' . esc_html( preg_replace( '#<br\s*/?>#i', ', ', $the_order->get_formatted_billing_address() ) ) .'</a>';
        	else
        		echo '&ndash;';

        	if ( $the_order->payment_method_title )
        		echo '<small class="meta">' . __( 'Via', 'colorshop' ) . ' ' . esc_html( $the_order->payment_method_title ) . '</small>';
		break;
		case "shipping_address" :
			if ( $the_order->get_formatted_shipping_address() )
            	echo '<a target="_blank" href="' . esc_url( 'http://maps.google.com/maps?&q=' . urlencode( $the_order->get_shipping_address() ) . '&z=16' ) . '">'. esc_html( preg_replace('#<br\s*/?>#i', ', ', $the_order->get_formatted_shipping_address() ) ) .'</a>';
        	else
        		echo '&ndash;';

        	if ( $the_order->shipping_method_title )
        		echo '<small class="meta">' . __( 'Via', 'colorshop' ) . ' ' . esc_html( $the_order->shipping_method_title ) . '</small>';
		break;
		case "total_cost" :
			echo esc_html( strip_tags( $the_order->get_formatted_order_total() ) );
		break;
		case "order_date" :

			if ( '0000-00-00 00:00:00' == $post->post_date ) {
				$t_time = $h_time = __( 'Unpublished', 'colorshop' );
			} else {
				$t_time = get_the_time( __( 'Y/m/d g:i:s A', 'colorshop' ), $post );

				$gmt_time = strtotime( $post->post_date_gmt );
				$time_diff = current_time('timestamp', 1) - $gmt_time;

				if ( $time_diff > 0 && $time_diff < 24*60*60 )
					$h_time = sprintf( __( '%s ago', 'colorshop' ), human_time_diff( $gmt_time, current_time('timestamp', 1) ) );
				else
					$h_time = get_the_time( __( 'Y/m/d', 'colorshop' ), $post );
			}

			echo '<abbr title="' . esc_attr( $t_time ) . '">' . esc_html( apply_filters( 'post_date_column_time', $h_time, $post ) ) . '</abbr>';

		break;
		case "order_actions" :

			?><p>
				<?php
					do_action( 'colorshop_admin_order_actions_start', $the_order );

					$actions = array();

					if ( in_array( $the_order->status, array( 'pending', 'on-hold' ) ) )
						$actions['processing'] = array(
							'url' 		=> wp_nonce_url( admin_url( 'admin-ajax.php?action=colorshop-mark-order-processing&order_id=' . $post->ID ), 'colorshop-mark-order-processing' ),
							'name' 		=> __( 'Processing', 'colorshop' ),
							'action' 	=> "processing"
						);

					if ( in_array( $the_order->status, array( 'pending', 'on-hold', 'processing' ) ) )
						$actions['complete'] = array(
							'url' 		=> wp_nonce_url( admin_url( 'admin-ajax.php?action=colorshop-mark-order-complete&order_id=' . $post->ID ), 'colorshop-mark-order-complete' ),
							'name' 		=> __( 'Complete', 'colorshop' ),
							'action' 	=> "complete"
						);

					$actions['view'] = array(
						'url' 		=> admin_url( 'post.php?post=' . $post->ID . '&action=edit' ),
						'name' 		=> __( 'View', 'colorshop' ),
						'action' 	=> "view"
					);

					$actions = apply_filters( 'colorshop_admin_order_actions', $actions, $the_order );

					foreach ( $actions as $action ) {
						$image = ( isset( $action['image_url'] ) ) ? $action['image_url'] : $colorshop->plugin_url() . '/assets/images/icons/' . $action['action'] . '.png';
						printf( '<a class="button tips" href="%s" data-tip="%s"><img src="%s" alt="%s" width="14" /></a>', esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_attr( $image ), esc_attr( $action['name'] ) );
					}

					do_action( 'colorshop_admin_order_actions_end', $the_order );
				?>
			</p><?php

		break;
		case "note" :

			if ( $the_order->customer_note )
				echo '<img src="'.$colorshop->plugin_url().'/assets/images/note.png" alt="yes" class="tips" data-tip="' . __( 'Yes', 'colorshop' ) . '" width="14" height="14" />';
			else
				echo '<img src="'.$colorshop->plugin_url().'/assets/images/note-off.png" alt="no" class="tips" data-tip="' . __( 'No', 'colorshop' ) . '" width="14" height="14" />';

		break;
		case "order_comments" :

			echo '<div class="post-com-count-wrapper">
				<a href="'. esc_url( admin_url('post.php?post=' . $post->ID . '&action=edit') ) .'" class="post-com-count"><span class="comment-count">'. $post->comment_count .'</span></a>
				</div>';
		break;
	}
}

add_action( 'manage_shop_order_posts_custom_column', 'colorshop_custom_order_columns', 2 );


/**
 * Filters for the order page.
 *
 * @access public
 * @param mixed $views
 * @return array
 */
function colorshop_custom_order_views( $views ) {

	unset( $views['publish'] );

	if ( isset( $views['trash'] ) ) {
		$trash = $views['trash'];
		unset( $views['draft'] );
		unset( $views['trash'] );
		$views['trash'] = $trash;
	}

	return $views;
}

add_filter( 'views_edit-shop_order', 'colorshop_custom_order_views' );


/**
 * Actions for the orders page.
 *
 * @access public
 * @param mixed $actions
 * @return array
 */
function colorshop_remove_row_actions( $actions ) {
    if( get_post_type() === 'shop_order' ) {
        unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );
    }
    return $actions;
}

add_filter( 'post_row_actions', 'colorshop_remove_row_actions', 10, 1 );


/**
 * Remove edit from the bulk actions.
 *
 * @access public
 * @param mixed $actions
 * @return array
 */
function colorshop_bulk_actions( $actions ) {

	if ( isset( $actions['edit'] ) )
		unset( $actions['edit'] );

	return $actions;
}

add_filter( 'bulk_actions-edit-shop_order', 'colorshop_bulk_actions' );


/**
 * Show custom filters to filter orders by status/customer.
 *
 * @access public
 * @return void
 */
function colorshop_restrict_manage_orders() {
	global $colorshop, $typenow, $wp_query;

	if ( $typenow != 'shop_order' )
		return;

	// Status
	?>
	<select name='shop_order_status' id='dropdown_shop_order_status'>
		<option value=""><?php _e( 'Show all statuses', 'colorshop' ); ?></option>
		<?php
			$terms = get_terms('shop_order_status');

			foreach ( $terms as $term ) {
				echo '<option value="' . esc_attr( $term->slug ) . '"';

				if ( isset( $wp_query->query['shop_order_status'] ) )
					selected( $term->slug, $wp_query->query['shop_order_status'] );

				echo '>' . esc_html__( $term->name, 'colorshop' ) . ' (' . absint( $term->count ) . ')</option>';
			}
		?>
		</select>
	<?php

	// Customers
	?>
	<select id="dropdown_customers" name="_customer_user">
		<option value=""><?php _e( 'Show all customers', 'colorshop' ) ?></option>
		<?php
			if ( ! empty( $_GET['_customer_user'] ) ) {
				$user = get_user_by( 'id', absint( $_GET['_customer_user'] ) );
				echo '<option value="' . absint( $user->ID ) . '" ';
				selected( 1, 1 );
				echo '>' . esc_html( $user->display_name ) . ' (#' . absint( $user->ID ) . ' &ndash; ' . esc_html( $user->user_email ) . ')</option>';
			}
		?>
	</select>
	<?php

	$colorshop->add_inline_js( "

		jQuery('select#dropdown_shop_order_status, select[name=m]').css('width', '150px').chosen();

		jQuery('select#dropdown_customers').css('width', '250px').ajaxChosen({
		    method: 		'GET',
		    url: 			'" . admin_url('admin-ajax.php') . "',
		    dataType: 		'json',
		    afterTypeDelay: 100,
		    minTermLength: 	1,
		    data:		{
		    	action: 	'colorshop_json_search_customers',
				security: 	'" . wp_create_nonce("search-customers") . "',
				default:	'" . __( 'Show all customers', 'colorshop' ) . "'
		    }
		}, function (data) {

			var terms = {};

		    $.each(data, function (i, val) {
		        terms[i] = val;
		    });

		    return terms;
		});
	" );
}

add_action( 'restrict_manage_posts', 'colorshop_restrict_manage_orders' );


/**
 * Filter the orders by the posted customer.
 *
 * @access public
 * @param mixed $vars
 * @return array
 */
function colorshop_orders_by_customer_query( $vars ) {
	global $typenow, $wp_query;
    if ( $typenow == 'shop_order' && isset( $_GET['_customer_user'] ) && $_GET['_customer_user'] > 0 ) {

		$vars['meta_key'] = '_customer_user';
		$vars['meta_value'] = (int) $_GET['_customer_user'];

	}

	return $vars;
}

add_filter( 'request', 'colorshop_orders_by_customer_query' );


/**
 * Make order columns sortable.
 *
 *
 * https://gist.github.com/906872
 *
 * @access public
 * @param mixed $columns
 * @return array
 */
function colorshop_custom_shop_order_sort( $columns ) {
	$custom = array(
		'order_title'	=> 'ID',
		'order_total'	=> 'order_total',
		'order_date'	=> 'date'
	);
	unset( $columns['comments'] );
	return wp_parse_args( $custom, $columns );
}

add_filter( "manage_edit-shop_order_sortable_columns", 'colorshop_custom_shop_order_sort' );


/**
 * Order column orderby/request.
 *
 * @access public
 * @param mixed $vars
 * @return array
 */
function colorshop_custom_shop_order_orderby( $vars ) {
	global $typenow, $wp_query;
    if ( $typenow != 'shop_order' )
    	return $vars;

    // Sorting
	if ( isset( $vars['orderby'] ) ) {
		if ( 'order_total' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' 	=> '_order_total',
				'orderby' 	=> 'meta_value_num'
			) );
		}
	}

	return $vars;
}

add_filter( 'request', 'colorshop_custom_shop_order_orderby' );


/**
 * Search custom fields as well as content.
 *
 * @access public
 * @param mixed $wp
 * @return void
 */
function colorshop_shop_order_search_custom_fields( $wp ) {
	global $pagenow, $wpdb;

	if ( 'edit.php' != $pagenow || empty( $wp->query_vars['s'] ) || $wp->query_vars['post_type'] != 'shop_order' )
		return $wp;

	$search_fields = array_map( 'esc_attr', apply_filters( 'colorshop_shop_order_search_fields', array(
		'_order_key',
		'_billing_first_name',
		'_billing_last_name',
		'_billing_company',
		'_billing_address_1',
		'_billing_address_2',
		'_billing_city',
		'_billing_postcode',
		'_billing_country',
		'_billing_state',
		'_billing_email',
		'_billing_phone'
	) ) );

	$search_order_id = str_replace( 'Order #', '', $_GET['s'] );
	if ( ! is_numeric( $search_order_id ) )
		$search_order_id = 0;

	// Search orders
	$post_ids = array_merge(
		$wpdb->get_col(
			$wpdb->prepare( "
				SELECT post_id
				FROM {$wpdb->postmeta}
				WHERE meta_key IN ('" . implode( "','", $search_fields ) . "')
				AND meta_value LIKE '%%%s%%'",
				esc_attr( $_GET['s'] )
			)
		),
		$wpdb->get_col(
			$wpdb->prepare( "
				SELECT order_id
				FROM {$wpdb->prefix}colorshop_order_items as order_items
				WHERE order_item_name LIKE '%%%s%%'
				",
				esc_attr( $_GET['s'] )
			)
		),
		$wpdb->get_col(
			$wpdb->prepare( "
				SELECT posts.ID
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				LEFT JOIN {$wpdb->users} as users ON postmeta.meta_value = users.ID
				WHERE
					post_excerpt 	LIKE '%%%1\$s%%' OR
					post_title 		LIKE '%%%1\$s%%' OR
					(
						meta_key		= '_customer_user' AND
						(
							user_login		LIKE '%%%1\$s%%' OR
							user_nicename	LIKE '%%%1\$s%%' OR
							user_email		LIKE '%%%1\$s%%' OR
							display_name	LIKE '%%%1\$s%%'
						)
					)
				",
				esc_attr( $_GET['s'] )
			)
		),
		array( $search_order_id )
	);

	// Remove s - we don't want to search order name
	unset( $wp->query_vars['s'] );

	// so we know we're doing this
	$wp->query_vars['shop_order_search'] = true;

	// Search by found posts
	$wp->query_vars['post__in'] = $post_ids;
}


/**
 * Change the label when searching orders.
 *
 * @access public
 * @param mixed $query
 * @return string
 */
function colorshop_shop_order_search_label($query) {
	global $pagenow, $typenow;

    if ( 'edit.php' != $pagenow ) return $query;
    if ( $typenow != 'shop_order' ) return $query;
	if ( ! get_query_var( 'shop_order_search' ) ) return $query;

	return $_GET['s'];
}

if ( is_admin() ) {
	add_filter( 'parse_query', 'colorshop_shop_order_search_custom_fields' );
	add_filter( 'get_search_query', 'colorshop_shop_order_search_label' );
}


/**
 * Query vars for custom searches.
 *
 * @access public
 * @param mixed $public_query_vars
 * @return array
 */
function colorshop_add_custom_query_var($public_query_vars) {
	$public_query_vars[] = 'sku';
	$public_query_vars[] = 'shop_order_search';
	return $public_query_vars;
}

add_filter('query_vars', 'colorshop_add_custom_query_var');

/**
 * Remove item meta on permanent deletion
 *
 * @access public
 * @return void
 **/
function colorshop_delete_order_items( $postid )
{
	global $wpdb;

	if ( get_post_type( $postid ) == 'shop_order' )
	{
		$wpdb->query( "
			DELETE {$wpdb->prefix}colorshop_order_items, {$wpdb->prefix}colorshop_order_itemmeta
			FROM {$wpdb->prefix}colorshop_order_items
			JOIN {$wpdb->prefix}colorshop_order_itemmeta ON {$wpdb->prefix}colorshop_order_items.order_item_id = {$wpdb->prefix}colorshop_order_itemmeta.order_item_id
			WHERE {$wpdb->prefix}colorshop_order_items.order_id = '{$postid}';
			" );
	}
}

add_action( 'before_delete_post', 'colorshop_delete_order_items' );