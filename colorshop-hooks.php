<?php
/**
 * ColorShop Hooks
 *
 * Action/filter hooks used for ColorShop functions/templates
 *
 * @author 		ColorVila
 * @category 	Core
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Template Hooks ********************************************************/

if ( ! is_admin() || defined('DOING_AJAX') ) {

	/**
	 * Content Wrappers
	 *
	 * @see colorshop_output_content_wrapper()
	 * @see colorshop_output_content_wrapper_end()
	 */
	add_action( 'colorshop_before_main_content', 'colorshop_output_content_wrapper', 10 );
	add_action( 'colorshop_after_main_content', 'colorshop_output_content_wrapper_end', 10 );

	/**
	 * Sale flashes
	 *
	 * @see colorshop_show_product_loop_sale_flash()
	 * @see colorshop_show_product_sale_flash()
	 */
	add_action( 'colorshop_before_shop_loop_item_title', 'colorshop_show_product_loop_sale_flash', 10 );
	add_action( 'colorshop_before_single_product_summary', 'colorshop_show_product_sale_flash', 10 );

	/**
	 * Breadcrumbs
	 *
	 * @see colorshop_breadcrumb()
	 */
	add_action( 'colorshop_before_main_content', 'colorshop_breadcrumb', 20, 0 );

	/**
	 * Sidebar
	 *
	 * @see colorshop_get_sidebar()
	 */
	add_action( 'colorshop_sidebar', 'colorshop_get_sidebar', 10 );

	/**
	 * Archive descriptions
	 *
	 * @see colorshop_taxonomy_archive_description()
	 * @see colorshop_product_archive_description()
	 */
	add_action( 'colorshop_archive_description', 'colorshop_taxonomy_archive_description', 10 );
	add_action( 'colorshop_archive_description', 'colorshop_product_archive_description', 10 );

	/**
	 * Products Loop
	 *
	 * @see colorshop_show_messages()
	 * @see colorshop_result_count()
	 * @see colorshop_catalog_ordering()
	 */
	add_action( 'colorshop_before_shop_loop', 'colorshop_show_messages', 10 );
	add_action( 'colorshop_before_shop_loop', 'colorshop_result_count', 20 );
	//add_action( 'colorshop_before_shop_loop', 'colorshop_catalog_ordering', 30 );

	/**
	 * Product Loop Items
	 *
	 * @see colorshop_show_messages()
	 * @see colorshop_template_loop_add_to_cart()
	 * @see colorshop_template_loop_product_thumbnail()
	 * @see colorshop_template_loop_price()
	 */
	add_action( 'colorshop_after_shop_loop_item', 'colorshop_template_loop_add_to_cart', 10 );
	add_action( 'colorshop_before_shop_loop_item_title', 'colorshop_template_loop_product_thumbnail', 10 );
	add_action( 'colorshop_after_shop_loop_item_title', 'colorshop_template_loop_price', 10 );
	add_action( 'colorshop_after_shop_loop_item_title', 'colorshop_template_loop_rating', 5 );

	/**
	 * Subcategories
	 *
	 * @see colorshop_subcategory_thumbnail()
	 */
	add_action( 'colorshop_before_subcategory_title', 'colorshop_subcategory_thumbnail', 10 );

	/**
	 * Before Single Products
	 *
	 * @see colorshop_show_messages()
	 */
	add_action( 'colorshop_before_single_product', 'colorshop_show_messages', 10 );

	/**
	 * Before Single Products Summary Div
	 *
	 * @see colorshop_show_product_images()
	 * @see colorshop_show_product_thumbnails()
	 */
	add_action( 'colorshop_before_single_product_summary', 'colorshop_show_product_images', 20 );
	add_action( 'colorshop_product_thumbnails', 'colorshop_show_product_thumbnails', 20 );

	/**
	 * After Single Products Summary Div
	 *
	 * @see colorshop_output_product_data_tabs()
	 * @see colorshop_upsell_display()
	 * @see colorshop_output_related_products()
	 */
	add_action( 'colorshop_after_single_product_summary', 'colorshop_output_product_data_tabs', 10 );
	add_action( 'colorshop_after_single_product_summary', 'colorshop_upsell_display', 15 );
	add_action( 'colorshop_after_single_product_summary', 'colorshop_output_related_products', 20 );

	/**
	 * Product Summary Box
	 *
	 * @see colorshop_template_single_title()
	 * @see colorshop_template_single_price()
	 * @see colorshop_template_single_excerpt()
	 * @see colorshop_template_single_meta()
	 * @see colorshop_template_single_sharing()
	 */
	add_action( 'colorshop_single_product_summary', 'colorshop_template_single_title', 5 );
	add_action( 'colorshop_single_product_summary', 'colorshop_template_single_price', 10 );
	add_action( 'colorshop_single_product_summary', 'colorshop_template_single_excerpt', 20 );
	add_action( 'colorshop_single_product_summary', 'colorshop_template_single_meta', 40 );
	add_action( 'colorshop_single_product_summary', 'colorshop_template_single_sharing', 50 );


	/**
	 * Product Add to cart
	 *
	 * @see colorshop_template_single_add_to_cart()
	 * @see colorshop_simple_add_to_cart()
	 * @see colorshop_grouped_add_to_cart()
	 * @see colorshop_variable_add_to_cart()
	 * @see colorshop_external_add_to_cart()
	 */
	add_action( 'colorshop_single_product_summary', 'colorshop_template_single_add_to_cart', 30 );
	add_action( 'colorshop_simple_add_to_cart', 'colorshop_simple_add_to_cart', 30 );
	add_action( 'colorshop_grouped_add_to_cart', 'colorshop_grouped_add_to_cart', 30 );
	add_action( 'colorshop_variable_add_to_cart', 'colorshop_variable_add_to_cart', 30 );
	add_action( 'colorshop_external_add_to_cart', 'colorshop_external_add_to_cart', 30 );

	/**
	 * Pagination after shop loops
	 *
	 * @see colorshop_pagination()
	 */
	add_action( 'colorshop_after_shop_loop', 'colorshop_pagination', 10 );

	/**
	 * Product page tabs
	 */
	add_filter( 'colorshop_product_tabs', 'colorshop_default_product_tabs' );
	add_filter( 'colorshop_product_tabs', 'colorshop_sort_product_tabs', 99 );

	/**
	 * Checkout
	 *
	 * @see colorshop_checkout_login_form()
	 * @see colorshop_checkout_coupon_form()
	 * @see colorshop_order_review()
	 */
	add_action( 'colorshop_before_checkout_form', 'colorshop_checkout_login_form', 10 );
	add_action( 'colorshop_before_checkout_form', 'colorshop_checkout_coupon_form', 10 );
	add_action( 'colorshop_checkout_order_review', 'colorshop_order_review', 10 );

	/**
	 * Cart
	 *
	 * @see colorshop_cross_sell_display()
	 */
	add_action( 'colorshop_cart_collaterals', 'colorshop_cross_sell_display' );

	/**
	 * Footer
	 *
	 * @see colorshop_demo_store()
	 */
	add_action( 'wp_footer', 'colorshop_demo_store' );

	/**
	 * Order details
	 *
	 * @see colorshop_order_details_table()
	 * @see colorshop_order_details_table()
	 */
	add_action( 'colorshop_view_order', 'colorshop_order_details_table', 10 );
	add_action( 'colorshop_thankyou', 'colorshop_order_details_table', 10 );
}

/** Store Event Hooks *****************************************************/

/**
 * Shop Page Handling and Support
 *
 * @see colorshop_template_redirect()
 * @see colorshop_nav_menu_item_classes()
 * @see colorshop_list_pages()
 */
add_action( 'template_redirect', 'colorshop_template_redirect' );
add_filter( 'wp_nav_menu_objects',  'colorshop_nav_menu_item_classes', 2, 20 );
add_filter( 'wp_list_pages', 'colorshop_list_pages' );

/**
 * Logout link
 *
 * @see colorshop_nav_menu_items()
 */
add_filter( 'wp_nav_menu_objects', 'colorshop_nav_menu_items', 10, 2 );

/**
 * Clear the cart
 *
 * @see colorshop_empty_cart()
 * @see colorshop_clear_cart_after_payment()
 */
if ( get_option( 'colorshop_clear_cart_on_logout' ) == 'yes' )
	add_action( 'wp_logout', 'colorshop_empty_cart' );
add_action( 'get_header', 'colorshop_clear_cart_after_payment' );

/**
 * Disable admin bar
 *
 * @see colorshop_disable_admin_bar()
 */
add_filter( 'show_admin_bar', 'colorshop_disable_admin_bar', 10, 1 );

/**
 * Cart Actions
 *
 * @see colorshop_update_cart_action()
 * @see colorshop_add_to_cart_action()
 * @see colorshop_load_persistent_cart()
 */
add_action( 'init', 'colorshop_update_cart_action' );
add_action( 'init', 'colorshop_add_to_cart_action' );
add_action( 'wp_login', 'colorshop_load_persistent_cart', 1, 2 );

/**
 * Checkout Actions
 *
 * @see colorshop_checkout_action()
 * @see colorshop_pay_action()
 */
add_action( 'init', 'colorshop_checkout_action', 20 );
add_action( 'init', 'colorshop_pay_action', 20 );

/**
 * Login and Registration
 *
 * @see colorshop_process_login()
 * @see colorshop_process_registration()
 */
add_action( 'init', 'colorshop_process_login' );
add_action( 'init', 'colorshop_process_registration' );

/**
 * Product Downloads
 *
 * @see colorshop_download_product()
 */
add_action('init', 'colorshop_download_product');

/**
 * Analytics
 *
 * @see colorshop_ecommerce_tracking_piwik()
 */
add_action( 'colorshop_thankyou', 'colorshop_ecommerce_tracking_piwik' );

/**
 * RSS Feeds
 *
 * @see colorshop_products_rss_feed()
 */
add_action( 'wp_head', 'colorshop_products_rss_feed' );

/**
 * Order actions
 *
 * @see colorshop_cancel_order()
 * @see colorshop_order_again()
 */
add_action( 'init', 'colorshop_cancel_order' );
add_action( 'init', 'colorshop_order_again' );

/**
 * Star Ratings
 *
 * @see colorshop_add_comment_rating()
 * @see colorshop_check_comment_rating()
 */
add_action( 'comment_post', 'colorshop_add_comment_rating', 1 );
add_filter( 'preprocess_comment', 'colorshop_check_comment_rating', 0 );

/**
 * Filters
 */
add_filter( 'colorshop_short_description', 'wptexturize'        );
add_filter( 'colorshop_short_description', 'convert_smilies'    );
add_filter( 'colorshop_short_description', 'convert_chars'      );
add_filter( 'colorshop_short_description', 'wpautop'            );
add_filter( 'colorshop_short_description', 'shortcode_unautop'  );
add_filter( 'colorshop_short_description', 'prepend_attachment' );
add_filter( 'colorshop_short_description', 'do_shortcode', 11 ); // AFTER wpautop()