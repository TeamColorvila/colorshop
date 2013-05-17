<?php
/**
 * ColorShop Admin
 *
 * Main admin file which loads all settings panels and sets up admin menus.
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Functions for the product post type
 */
include_once( 'post-types/product.php' );

/**
 * Functions for the shop_coupon post type
 */
include_once( 'post-types/shop_coupon.php' );

/**
 * Functions for the shop_order post type
 */
include_once( 'post-types/shop_order.php' );

/**
 * Hooks in admin
 */
include_once( 'colorshop-admin-hooks.php' );

/**
 * Functions in admin
 */
include_once( 'colorshop-admin-functions.php' );

/**
 * Functions for handling taxonomies
 */
include_once( 'colorshop-admin-taxonomies.php' );

/**
 * Welcome Page
 */
include_once( 'includes/welcome.php' );

/**
 * Setup the Admin menu in WordPress
 *
 * @access public
 * @return void
 */
function colorshop_admin_menu() {
    global $menu, $colorshop;

    if ( current_user_can( 'manage_colorshop' ) )
    $menu[] = array( '', 'read', 'separator-colorshop', '', 'wp-menu-separator colorshop' );

    $main_page = add_menu_page( __( 'ColorShop', 'colorshop' ), __( 'ColorShop', 'colorshop' ), 'manage_colorshop', 'colorshop' , 'colorshop_settings_page', null, '55.5' );

    $reports_page = add_submenu_page( 'colorshop', __( 'Reports', 'colorshop' ),  __( 'Reports', 'colorshop' ) , 'view_colorshop_reports', 'colorshop_reports', 'colorshop_reports_page' );

    add_submenu_page( 'edit.php?post_type=product', __( 'Attributes', 'colorshop' ), __( 'Attributes', 'colorshop' ), 'manage_product_terms', 'colorshop_attributes', 'colorshop_attributes_page');

    add_action( 'load-' . $main_page, 'colorshop_admin_help_tab' );
    add_action( 'load-' . $reports_page, 'colorshop_admin_help_tab' );

    $print_css_on = apply_filters( 'colorshop_screen_ids', array( 'toplevel_page_colorshop', 'colorshop_page_colorshop_settings', 'colorshop_page_colorshop_reports', 'colorshop_page_colorshop_status', 'product_page_colorshop_attributes', 'edit-tags.php', 'edit.php', 'index.php', 'post-new.php', 'post.php' ) );

    foreach ( $print_css_on as $page )
    	add_action( 'admin_print_styles-'. $page, 'colorshop_admin_css' );
}

add_action('admin_menu', 'colorshop_admin_menu', 9);

/**
 * Setup the Admin menu in WordPress - later priority so they appear last
 *
 * @access public
 * @return void
 */
function colorshop_admin_menu_after() {
	$settings_page = add_submenu_page( 'colorshop', __( 'ColorShop Settings', 'colorshop' ),  __( 'Settings', 'colorshop' ) , 'manage_colorshop', 'colorshop_settings', 'colorshop_settings_page');
	$status_page = add_submenu_page( 'colorshop', __( 'ColorShop Status', 'colorshop' ),  __( 'System Status', 'colorshop' ) , 'manage_colorshop', 'colorshop_status', 'colorshop_status_page');

	add_action( 'load-' . $settings_page, 'colorshop_settings_page_init' );
}

add_action('admin_menu', 'colorshop_admin_menu_after', 50);


/**
 * Loads gateways and shipping methods into memory for use within settings.
 *
 * @access public
 * @return void
 */
function colorshop_settings_page_init() {
	$GLOBALS['colorshop']->payment_gateways();
	$GLOBALS['colorshop']->shipping();
}

/**
 * Highlights the correct top level admin menu item for post type add screens.
 *
 * @access public
 * @return void
 */
function colorshop_admin_menu_highlight() {
	global $menu, $submenu, $parent_file, $submenu_file, $self, $post_type, $taxonomy;

	$to_highlight_types = array( 'shop_order', 'shop_coupon' );

	if ( isset( $post_type ) ) {
		if ( in_array( $post_type, $to_highlight_types ) ) {
			$submenu_file = 'edit.php?post_type=' . esc_attr( $post_type );
			$parent_file  = 'colorshop';
		}

		if ( 'product' == $post_type ) {
			$screen = get_current_screen();

			if ( $screen->base == 'edit-tags' && 'pa_' == substr( $taxonomy, 0, 3 ) ) {
				$submenu_file = 'colorshop_attributes';
				$parent_file  = 'edit.php?post_type=' . esc_attr( $post_type );
			}
		}
	}

	if ( isset( $submenu['colorshop'] ) && isset( $submenu['colorshop'][2] ) ) {
		$submenu['colorshop'][0] = $submenu['colorshop'][2];
		unset( $submenu['colorshop'][2] );
	}

	// Sort out Orders menu when on the top level
	if ( ! current_user_can( 'manage_colorshop' ) ) {
		foreach ( $menu as $key => $menu_item ) {
			if ( strpos( $menu_item[0], _x('Orders', 'Admin menu name', 'colorshop') ) === 0 ) {

				$menu_name = _x('Orders', 'Admin menu name', 'colorshop');
				$menu_name_count = '';
				if ( $order_count = colorshop_processing_order_count() ) {
					$menu_name_count = " <span class='awaiting-mod update-plugins count-$order_count'><span class='processing-count'>" . number_format_i18n( $order_count ) . "</span></span>" ;
				}

				$menu[$key][0] = $menu_name . $menu_name_count;
				$submenu['edit.php?post_type=shop_order'][5][0] = $menu_name;
				break;
			}
		}
	}
}

add_action( 'admin_head', 'colorshop_admin_menu_highlight' );


/**
 * colorshop_admin_notices_styles function.
 *
 * @access public
 * @return void
 */
function colorshop_admin_notices_styles() {

	if ( get_option( '_cs_needs_update' ) == 1 || get_option( '_cs_needs_pages' ) == 1 ) {
		wp_enqueue_style( 'colorshop-activation', plugins_url(  '/assets/css/activation.css', dirname( __FILE__ ) ) );
		add_action( 'admin_notices', 'colorshop_admin_install_notices' );
	}

	$template = get_option( 'template' );

	if ( ! current_theme_supports( 'colorshop' ) && ! in_array( $template, array( 'twentyeleven', 'twentytwelve', 'twentyten' ) ) ) {

		if ( ! empty( $_GET['hide_colorshop_theme_support_check'] ) ) {
			update_option( 'colorshop_theme_support_check', $template );
			return;
		}

		if ( get_option( 'colorshop_theme_support_check' ) !== $template ) {
			wp_enqueue_style( 'colorshop-activation', plugins_url(  '/assets/css/activation.css', dirname( __FILE__ ) ) );
			add_action( 'admin_notices', 'colorshop_theme_check_notice' );
		}

	}

}

add_action( 'admin_print_styles', 'colorshop_admin_notices_styles' );


/**
 * colorshop_theme_check_notice function.
 *
 * @access public
 * @return void
 */
function colorshop_theme_check_notice() {
	include( 'includes/notice-theme-support.php' );
}


/**
 * colorshop_admin_install_notices function.
 *
 * @access public
 * @return void
 */
function colorshop_admin_install_notices() {
	global $colorshop;

	// If we need to update, include a message with the update button
	if ( get_option( '_cs_needs_update' ) == 1 ) {
		include( 'includes/notice-update.php' );
	}

	// If we have just installed, show a message with the install pages button
	elseif ( get_option( '_cs_needs_pages' ) == 1 ) {
		include( 'includes/notice-install.php' );
	}
}

/**
 * Include some admin files conditonally.
 *
 * @access public
 * @return void
 */
function colorshop_admin_init() {
	global $pagenow, $typenow;

	ob_start();

	// Install - Add pages button
	if ( ! empty( $_GET['install_colorshop_pages'] ) ) {

		require_once( 'colorshop-admin-install.php' );
		colorshop_create_pages();

		// We no longer need to install pages
		delete_option( '_cs_needs_pages' );
		delete_transient( '_cs_activation_redirect' );

		// What's new redirect
		wp_safe_redirect( admin_url( 'index.php?page=cs-about&cs-installed=true' ) );
		exit;

	// Skip button
	} elseif ( ! empty( $_GET['skip_install_colorshop_pages'] ) ) {

		// We no longer need to install pages
		delete_option( '_cs_needs_pages' );
		delete_transient( '_cs_activation_redirect' );

		// Flush rules after install
		flush_rewrite_rules();

		// What's new redirect
		wp_safe_redirect( admin_url( 'index.php?page=cs-about' ) );
		exit;

	// Update button
	} elseif ( ! empty( $_GET['do_update_colorshop'] ) ) {

		include_once( 'colorshop-admin-update.php' );
		do_update_colorshop();

		// Update complete
		delete_option( '_cs_needs_pages' );
		delete_option( '_cs_needs_update' );
		delete_transient( '_cs_activation_redirect' );

		// What's new redirect
		wp_safe_redirect( admin_url( 'index.php?page=cs-about&cs-updated=true' ) );
		exit;
	}

	// Includes
	if ( $typenow == 'post' && isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ) {
		$typenow = $post->post_type;
	} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
	    $post = get_post( $_GET['post'] );
	    $typenow = $post->post_type;
	}

	if ( $pagenow == 'index.php' ) {

		include_once( 'colorshop-admin-dashboard.php' );

	} elseif ( $pagenow == 'admin.php' && isset( $_GET['import'] ) ) {

		include_once( 'colorshop-admin-import.php' );

	} elseif ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {

		include_once( 'post-types/writepanels/writepanels-init.php' );

		if ( in_array( $typenow, array( 'product', 'shop_coupon', 'shop_order' ) ) )
			add_action('admin_print_styles', 'colorshop_admin_help_tab');

	} elseif ( $pagenow == 'users.php' || $pagenow == 'user-edit.php' || $pagenow == 'profile.php' ) {

		include_once( 'colorshop-admin-users.php' );

	}

	// Register importers
	if ( defined( 'WP_LOAD_IMPORTERS' ) ) {
		include_once( 'importers/importers-init.php' );
	}
}

add_action('admin_init', 'colorshop_admin_init');


/**
 * Include and display the settings page.
 *
 * @access public
 * @return void
 */
function colorshop_settings_page() {
	include_once( 'colorshop-admin-settings.php' );
	colorshop_settings();
}

/**
 * Include and display the reports page.
 *
 * @access public
 * @return void
 */
function colorshop_reports_page() {
	include_once( 'colorshop-admin-reports.php' );
	colorshop_reports();
}

/**
 * Include and display the attibutes page.
 *
 * @access public
 * @return void
 */
function colorshop_attributes_page() {
	include_once( 'colorshop-admin-attributes.php' );
	colorshop_attributes();
}

/**
 * Include and display the status page.
 *
 * @access public
 * @return void
 */
function colorshop_status_page() {
	include_once( 'colorshop-admin-status.php' );
	colorshop_status();
}


/**
 * Include and add help tabs to WordPress admin.
 *
 * @access public
 * @return void
 */
function colorshop_admin_help_tab() {
	include_once( 'colorshop-admin-content.php' );
	colorshop_admin_help_tab_content();
}


/**
 * Include admin scripts and styles.
 *
 * @access public
 * @return void
 */
function colorshop_admin_scripts() {
	
	global $colorshop, $pagenow, $post, $wp_query;

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Register scripts
	wp_register_script( 'colorshop_admin', $colorshop->plugin_url() . '/assets/js/admin/colorshop_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-placeholder', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), $colorshop->version );

	wp_register_script( 'jquery-blockui', $colorshop->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), $colorshop->version, true );

	wp_register_script( 'jquery-placeholder', $colorshop->plugin_url() . '/assets/js/jquery-placeholder/jquery.placeholder' . $suffix . '.js', array( 'jquery' ), $colorshop->version, true );

	wp_register_script( 'jquery-tiptip', $colorshop->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), $colorshop->version, true );

	wp_register_script( 'colorshop_writepanel', $colorshop->plugin_url() . '/assets/js/admin/write-panels'.$suffix.'.js', array('jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable'), $colorshop->version );

	wp_register_script( 'ajax-chosen', $colorshop->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery'.$suffix.'.js', array('jquery', 'chosen'), $colorshop->version );

	wp_register_script( 'chosen', $colorshop->plugin_url() . '/assets/js/chosen/chosen.jquery'.$suffix.'.js', array('jquery'), $colorshop->version );

	// Get admin screen id
    $screen = get_current_screen();

    // ColorShop admin pages
    if ( in_array( $screen->id, apply_filters( 'colorshop_screen_ids', array( 'toplevel_page_colorshop', 'colorshop_page_colorshop_settings', 'colorshop_page_colorshop_reports', 'edit-shop_order', 'edit-shop_coupon', 'shop_coupon', 'shop_order', 'edit-product', 'product' ) ) ) ) {

    	wp_enqueue_script( 'colorshop_admin' );
    	wp_enqueue_script( 'farbtastic' );
    	wp_enqueue_script( 'ajax-chosen' );
    	wp_enqueue_script( 'chosen' );
    	wp_enqueue_script( 'jquery-ui-sortable' );
    	wp_enqueue_script( 'jquery-ui-autocomplete' );
    }

    // Edit product category pages
    if ( in_array( $screen->id, array('edit-product_cat') ) ) {
		wp_enqueue_media();
		wp_enqueue_script( 'chosen' );
    }

	// Product/Coupon/Orders
	if ( in_array( $screen->id, array( 'shop_coupon', 'shop_order', 'product' ) ) ) {

		wp_enqueue_script( 'colorshop_writepanel' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_media();
		wp_enqueue_script( 'ajax-chosen' );
		wp_enqueue_script( 'chosen' );
		wp_enqueue_script( 'plupload-all' );

		$colorshop_witepanel_params = array(
			'remove_item_notice' 			=> __( 'Are you sure you want to remove the selected items? If you have previously reduced this item\'s stock, or this order was submitted by a customer, you will need to manually restore the item\'s stock.', 'colorshop' ),
			'i18n_select_items'				=> __( 'Please select some items.', 'colorshop' ),
			'remove_item_meta'				=> __( 'Remove this item meta?', 'colorshop' ),
			'remove_attribute'				=> __( 'Remove this attribute?', 'colorshop' ),
			'name_label'					=> __( 'Name', 'colorshop' ),
			'remove_label'					=> __( 'Remove', 'colorshop' ),
			'click_to_toggle'				=> __( 'Click to toggle', 'colorshop' ),
			'values_label'					=> __( 'Value(s)', 'colorshop' ),
			'text_attribute_tip'			=> __( 'Enter some text, or some attributes by pipe (|) separating values.', 'colorshop' ),
			'visible_label'					=> __( 'Visible on the product page', 'colorshop' ),
			'used_for_variations_label'		=> __( 'Used for variations', 'colorshop' ),
			'new_attribute_prompt'			=> __( 'Enter a name for the new attribute term:', 'colorshop' ),
			'calc_totals' 					=> __( 'Calculate totals based on order items, discounts, and shipping?', 'colorshop' ),
			'calc_line_taxes' 				=> __( 'Calculate line taxes? This will calculate taxes based on the customers country. If no billing/shipping is set it will use the store base country.', 'colorshop' ),
			'copy_billing' 					=> __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'colorshop' ),
			'load_billing' 					=> __( 'Load the customer\'s billing information? This will remove any currently entered billing information.', 'colorshop' ),
			'load_shipping' 				=> __( 'Load the customer\'s shipping information? This will remove any currently entered shipping information.', 'colorshop' ),
			'featured_label'				=> __( 'Featured', 'colorshop' ),
			'prices_include_tax' 			=> esc_attr( get_option('colorshop_prices_include_tax') ),
			'round_at_subtotal'				=> esc_attr( get_option( 'colorshop_tax_round_at_subtotal' ) ),
			'no_customer_selected'			=> __( 'No customer selected', 'colorshop' ),
			'plugin_url' 					=> $colorshop->plugin_url(),
			'ajax_url' 						=> admin_url('admin-ajax.php'),
			'order_item_nonce' 				=> wp_create_nonce("order-item"),
			'add_attribute_nonce' 			=> wp_create_nonce("add-attribute"),
			'save_attributes_nonce' 		=> wp_create_nonce("save-attributes"),
			'calc_totals_nonce' 			=> wp_create_nonce("calc-totals"),
			'get_customer_details_nonce' 	=> wp_create_nonce("get-customer-details"),
			'search_products_nonce' 		=> wp_create_nonce("search-products"),
			'calendar_image'				=> $colorshop->plugin_url().'/assets/images/calendar.png',
			'post_id'						=> $post->ID,
			'currency_format_num_decimals'	=> absint( get_option( 'colorshop_price_num_decimals' ) ),
			'currency_format_symbol'		=> get_colorshop_currency_symbol(),
			'currency_format_decimal_sep'	=> esc_attr( stripslashes( get_option( 'colorshop_price_decimal_sep' ) ) ),
			'currency_format_thousand_sep'	=> esc_attr( stripslashes( get_option( 'colorshop_price_thousand_sep' ) ) ),
			'currency_format'				=> esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_colorshop_price_format() ) ), // For accounting JS
			'product_types'					=> array_map( 'sanitize_title', get_terms( 'product_type', array( 'hide_empty' => false, 'fields' => 'names' ) ) ),
			'default_attribute_visibility'  => apply_filters( 'default_attribute_visibility', false ),
			'default_attribute_variation'   => apply_filters( 'default_attribute_variation', false )
		 );

		wp_localize_script( 'colorshop_writepanel', 'colorshop_writepanel_params', $colorshop_witepanel_params );
	}

	// Term ordering - only when sorting by term_order
	if ( ( strstr( $screen->id, 'edit-pa_' ) || ( ! empty( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], apply_filters( 'colorshop_sortable_taxonomies', array( 'product_cat' ) ) ) ) ) && ! isset( $_GET['orderby'] ) ) {

		wp_register_script( 'colorshop_term_ordering', $colorshop->plugin_url() . '/assets/js/admin/term-ordering.js', array('jquery-ui-sortable'), $colorshop->version );
		wp_enqueue_script( 'colorshop_term_ordering' );

		$taxonomy = isset( $_GET['taxonomy'] ) ? colorshop_clean( $_GET['taxonomy'] ) : '';

		$colorshop_term_order_params = array(
			'taxonomy' 			=>  $taxonomy
		 );

		wp_localize_script( 'colorshop_term_ordering', 'colorshop_term_ordering_params', $colorshop_term_order_params );

	}

	// Product sorting - only when sorting by menu order on the products page
	if ( current_user_can('edit_others_pages') && $screen->id == 'edit-product' && isset( $wp_query->query['orderby'] ) && $wp_query->query['orderby'] == 'menu_order title' ) {

		wp_enqueue_script( 'colorshop_product_ordering', $colorshop->plugin_url() . '/assets/js/admin/product-ordering.js', array('jquery-ui-sortable'), '1.0', true );

	}

	// Reports pages
    if ( $screen->id == apply_filters( 'colorshop_reports_screen_id', 'colorshop_page_colorshop_reports' ) ) {

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'flot', $colorshop->plugin_url() . '/assets/js/admin/jquery.flot'.$suffix.'.js', 'jquery', '1.0' );
		wp_enqueue_script( 'flot-resize', $colorshop->plugin_url() . '/assets/js/admin/jquery.flot.resize'.$suffix.'.js', array('jquery', 'flot'), '1.0' );

	}
}

add_action( 'admin_enqueue_scripts', 'colorshop_admin_scripts' );


/**
 * Queue ColorShop CSS.
 *
 * @access public
 * @return void
 */
function colorshop_admin_css() {
	global $colorshop, $typenow, $post, $wp_scripts;

	if ( $typenow == 'post' && ! empty( $_GET['post'] ) ) {
		$typenow = $post->post_type;
	} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
        $post = get_post( $_GET['post'] );
        $typenow = $post->post_type;
    }

	if ( $typenow == '' || $typenow == "product" || $typenow == "shop_order" || $typenow == "shop_coupon" ) {
		wp_enqueue_style( 'colorshop_admin_styles', $colorshop->plugin_url() . '/assets/css/admin.css' );

		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

		wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );
	}

	wp_enqueue_style('farbtastic');

	do_action('colorshop_admin_css');
}


/**
 * Queue admin menu icons CSS.
 *
 * @access public
 * @return void
 */
function colorshop_admin_menu_styles() {
	global $colorshop;
	wp_enqueue_style( 'colorshop_admin_menu_styles', $colorshop->plugin_url() . '/assets/css/menu.css' );
}

add_action( 'admin_print_styles', 'colorshop_admin_menu_styles' );


/**
 * Reorder the CS menu items in admin.
 *
 * @access public
 * @param mixed $menu_order
 * @return void
 */
function colorshop_admin_menu_order( $menu_order ) {

	// Initialize our custom order array
	$colorshop_menu_order = array();

	// Get the index of our custom separator
	$colorshop_separator = array_search( 'separator-colorshop', $menu_order );

	// Get index of product menu
	$colorshop_product = array_search( 'edit.php?post_type=product', $menu_order );

	// Loop through menu order and do some rearranging
	foreach ( $menu_order as $index => $item ) :

		if ( ( ( 'colorshop' ) == $item ) ) :
			$colorshop_menu_order[] = 'separator-colorshop';
			$colorshop_menu_order[] = $item;
			$colorshop_menu_order[] = 'edit.php?post_type=product';
			unset( $menu_order[$colorshop_separator] );
			unset( $menu_order[$colorshop_product] );
		elseif ( !in_array( $item, array( 'separator-colorshop' ) ) ) :
			$colorshop_menu_order[] = $item;
		endif;

	endforeach;

	// Return order
	return $colorshop_menu_order;
}

add_action('menu_order', 'colorshop_admin_menu_order');


/**
 * colorshop_admin_custom_menu_order function.
 *
 * @access public
 * @return void
 */
function colorshop_admin_custom_menu_order() {
	if ( ! current_user_can( 'manage_colorshop' ) )
		return false;
	return true;
}

add_action( 'custom_menu_order', 'colorshop_admin_custom_menu_order' );


/**
 * Admin Head
 *
 * Outputs some styles in the admin <head> to show icons on the colorshop admin pages
 *
 * @access public
 * @return void
 */
function colorshop_admin_head() {
	global $colorshop;

	if ( ! current_user_can( 'manage_colorshop' ) ) return false;
	?>
	<style type="text/css">
		<?php if ( isset($_GET['taxonomy']) && $_GET['taxonomy']=='product_cat' ) : ?>
			.icon32-posts-product { background-position: -243px -5px !important; }
		<?php elseif ( isset($_GET['taxonomy']) && $_GET['taxonomy']=='product_tag' ) : ?>
			.icon32-posts-product { background-position: -301px -5px !important; }
		<?php endif; ?>
	</style>
	<?php
}

add_action('admin_head', 'colorshop_admin_head');


/**
 * Duplicate a product action
 *
 * @access public
 * @return void
 */
function colorshop_duplicate_product_action() {
	include_once('includes/duplicate_product.php');
	colorshop_duplicate_product();
}

add_action('admin_action_duplicate_product', 'colorshop_duplicate_product_action');


/**
 * Post updated messages
 *
 * @access public
 * @param mixed $messages
 * @return void
 */
function colorshop_product_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['product'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Product updated. <a href="%s">View Product</a>', 'colorshop' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'colorshop' ),
		3 => __( 'Custom field deleted.', 'colorshop' ),
		4 => __( 'Product updated.', 'colorshop' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Product restored to revision from %s', 'colorshop' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Product published. <a href="%s">View Product</a>', 'colorshop' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Product saved.', 'colorshop' ),
		8 => sprintf( __( 'Product submitted. <a target="_blank" href="%s">Preview Product</a>', 'colorshop' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product</a>', 'colorshop' ),
		  date_i18n( __( 'M j, Y @ G:i', 'colorshop' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Product draft updated. <a target="_blank" href="%s">Preview Product</a>', 'colorshop' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	$messages['shop_order'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Order updated.', 'colorshop' ),
		2 => __( 'Custom field updated.', 'colorshop' ),
		3 => __( 'Custom field deleted.', 'colorshop' ),
		4 => __( 'Order updated.', 'colorshop' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Order restored to revision from %s', 'colorshop' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __( 'Order updated.', 'colorshop' ),
		7 => __( 'Order saved.', 'colorshop' ),
		8 => __( 'Order submitted.', 'colorshop' ),
		9 => sprintf( __( 'Order scheduled for: <strong>%1$s</strong>.', 'colorshop' ),
		  date_i18n( __( 'M j, Y @ G:i', 'colorshop' ), strtotime( $post->post_date ) ) ),
		10 => __( 'Order draft updated.', 'colorshop' )
	);

	$messages['shop_coupon'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Coupon updated.', 'colorshop' ),
		2 => __( 'Custom field updated.', 'colorshop' ),
		3 => __( 'Custom field deleted.', 'colorshop' ),
		4 => __( 'Coupon updated.', 'colorshop' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Coupon restored to revision from %s', 'colorshop' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __( 'Coupon updated.', 'colorshop' ),
		7 => __( 'Coupon saved.', 'colorshop' ),
		8 => __( 'Coupon submitted.', 'colorshop' ),
		9 => sprintf( __( 'Coupon scheduled for: <strong>%1$s</strong>.', 'colorshop' ),
		  date_i18n( __( 'M j, Y @ G:i', 'colorshop' ), strtotime( $post->post_date ) ) ),
		10 => __( 'Coupon draft updated.', 'colorshop' )
	);

	return $messages;
}

add_filter('post_updated_messages', 'colorshop_product_updated_messages');


/**
 * Post updated messages
 *
 * @access public
 * @param mixed $types
 * @return void
 */
function colorshop_admin_comment_types_dropdown( $types ) {
	$types['order_note'] = __( 'Order notes', 'colorshop' );
	return $types;
}

add_filter( 'admin_comment_types_dropdown', 'colorshop_admin_comment_types_dropdown' );


/**
 * colorshop_permalink_settings function.
 *
 * @access public
 * @return void
 */
function colorshop_permalink_settings() {

	echo wpautop( __( 'These settings control the permalinks used for products. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'colorshop' ) );

	$permalinks = get_option( 'colorshop_permalinks' );
	$product_permalink = $permalinks['product_base'];

	// Get shop page
	$shop_page_id 	= colorshop_get_page_id( 'shop' );
	$base_slug 		= ( $shop_page_id > 0 && get_page( $shop_page_id ) ) ? get_page_uri( $shop_page_id ) : _x( 'shop', 'default-slug', 'colorshop' );
	$product_base 	= _x( 'product', 'default-slug', 'colorshop' );

	$structures = array(
		0 => '',
		1 => '/' . trailingslashit( $product_base ),
		2 => '/' . trailingslashit( $base_slug ),
		3 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%product_cat%' )
	);
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[0]; ?>" class="wctog" <?php checked( $structures[0], $product_permalink ); ?> /> <?php _e( 'Default' ); ?></label></th>
				<td><code><?php echo home_url(); ?>/?product=sample-product</code></td>
			</tr>
			<tr>
				<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[1]; ?>" class="wctog" <?php checked( $structures[1], $product_permalink ); ?> /> <?php _e( 'Product', 'colorshop' ); ?></label></th>
				<td><code><?php echo home_url(); ?>/<?php echo $product_base; ?>/sample-product/</code></td>
			</tr>
			<?php if ( $shop_page_id ) : ?>
				<tr>
					<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[2]; ?>" class="wctog" <?php checked( $structures[2], $product_permalink ); ?> /> <?php _e( 'Shop base', 'colorshop' ); ?></label></th>
					<td><code><?php echo home_url(); ?>/<?php echo $base_slug; ?>/sample-product/</code></td>
				</tr>
				<tr>
					<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[3]; ?>" class="wctog" <?php checked( $structures[3], $product_permalink ); ?> /> <?php _e( 'Shop base with category', 'colorshop' ); ?></label></th>
					<td><code><?php echo home_url(); ?>/<?php echo $base_slug; ?>/product-category/sample-product/</code></td>
				</tr>
			<?php endif; ?>
			<tr>
				<th><label><input name="product_permalink" id="colorshop_custom_selection" type="radio" value="custom" class="tog" <?php checked( in_array( $product_permalink, $structures ), false ); ?> />
					<?php _e( 'Custom Base', 'colorshop' ); ?></label></th>
				<td>
					<input name="product_permalink_structure" id="colorshop_permalink_structure" type="text" value="<?php echo esc_attr( $product_permalink ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'colorshop' ); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('input.wctog').change(function() {
				jQuery('#colorshop_permalink_structure').val( jQuery(this).val() );
			});

			jQuery('#colorshop_permalink_structure').focus(function(){
				jQuery('#colorshop_custom_selection').click();
			});
		});
	</script>
	<?php
}

/**
 * colorshop_permalink_settings_init function.
 *
 * @access public
 * @return void
 */
function colorshop_permalink_settings_init() {

	// Add a section to the permalinks page
	add_settings_section( 'colorshop-permalink', __( 'Product permalink base', 'colorshop' ), 'colorshop_permalink_settings', 'permalink' );

	// Add our settings
	add_settings_field(
		'colorshop_product_category_slug',      	// id
		__( 'Product category base', 'colorshop' ), 	// setting title
		'colorshop_product_category_slug_input',  // display callback
		'permalink',                 				// settings page
		'optional'                  				// settings section
	);
	add_settings_field(
		'colorshop_product_tag_slug',      		// id
		__( 'Product tag base', 'colorshop' ), 	// setting title
		'colorshop_product_tag_slug_input',  		// display callback
		'permalink',                 				// settings page
		'optional'                  				// settings section
	);
	add_settings_field(
		'colorshop_product_attribute_slug',      	// id
		__( 'Product attribute base', 'colorshop' ), 	// setting title
		'colorshop_product_attribute_slug_input',  		// display callback
		'permalink',                 				// settings page
		'optional'                  				// settings section
	);
}

add_action( 'admin_init', 'colorshop_permalink_settings_init' );

/**
 * colorshop_permalink_settings_save function.
 *
 * @access public
 * @return void
 */
function colorshop_permalink_settings_save() {
	if ( ! is_admin() )
		return;

	// We need to save the options ourselves; settings api does not trigger save for the permalinks page
	if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) ) {
		// Cat and tag bases
		$colorshop_product_category_slug = colorshop_clean( $_POST['colorshop_product_category_slug'] );
		$colorshop_product_tag_slug = colorshop_clean( $_POST['colorshop_product_tag_slug'] );
		$colorshop_product_attribute_slug = colorshop_clean( $_POST['colorshop_product_attribute_slug'] );

		$permalinks = get_option( 'colorshop_permalinks' );
		if ( ! $permalinks )
			$permalinks = array();

		$permalinks['category_base'] 	= untrailingslashit( $colorshop_product_category_slug );
		$permalinks['tag_base'] 		= untrailingslashit( $colorshop_product_tag_slug );
		$permalinks['attribute_base'] 	= untrailingslashit( $colorshop_product_attribute_slug );

		// Product base
		$product_permalink = colorshop_clean( $_POST['product_permalink'] );

		if ( $product_permalink == 'custom' ) {
			$product_permalink = colorshop_clean( $_POST['product_permalink_structure'] );
		} elseif ( empty( $product_permalink ) ) {
			$product_permalink = false;
		}

		$permalinks['product_base'] = untrailingslashit( $product_permalink );

		update_option( 'colorshop_permalinks', $permalinks );
	}
}

add_action( 'before_colorshop_init', 'colorshop_permalink_settings_save' );

/**
 * colorshop_product_category_slug_input function.
 *
 * @access public
 * @return void
 */
function colorshop_product_category_slug_input() {
	$permalinks = get_option( 'colorshop_permalinks' );
	?>
	<input name="colorshop_product_category_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['category_base'] ) ) echo esc_attr( $permalinks['category_base'] ); ?>" placeholder="<?php echo _x('product-category', 'slug', 'colorshop') ?>" />
	<?php
}

/**
 * colorshop_product_tag_slug_input function.
 *
 * @access public
 * @return void
 */
function colorshop_product_tag_slug_input() {
	$permalinks = get_option( 'colorshop_permalinks' );
	?>
	<input name="colorshop_product_tag_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['tag_base'] ) ) echo esc_attr( $permalinks['tag_base'] ); ?>" placeholder="<?php echo _x('product-tag', 'slug', 'colorshop') ?>" />
	<?php
}

/**
 * colorshop_product_attribute_slug_input function.
 *
 * @access public
 * @return void
 */
function colorshop_product_attribute_slug_input() {
	$permalinks = get_option( 'colorshop_permalinks' );
	?>
	<input name="colorshop_product_attribute_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['attribute_base'] ) ) echo esc_attr( $permalinks['attribute_base'] ); ?>" /><code>/attribute-name/attribute/</code>
	<?php
}