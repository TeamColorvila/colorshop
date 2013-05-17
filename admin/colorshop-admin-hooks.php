<?php
/**
 * ColorShop Admin Hooks
 *
 * Action/filter hooks used for ColorShop functions.
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Events
 *
 * @see colorshop_delete_post()
 * @see colorshop_trash_post()
 * @see colorshop_untrash_post()
 * @see colorshop_preview_emails()
 * @see colorshop_prevent_admin_access()
 * @see colorshop_check_download_folder_protection()
 * @see colorshop_ms_protect_download_rewite_rules()
 */
add_action('delete_post', 'colorshop_delete_post');
add_action('wp_trash_post', 'colorshop_trash_post');
add_action('untrash_post', 'colorshop_untrash_post');
add_action('admin_init', 'colorshop_preview_emails');
add_action('admin_init', 'colorshop_prevent_admin_access');
add_action('colorshop_settings_saved', 'colorshop_check_download_folder_protection');
add_filter('mod_rewrite_rules', 'colorshop_ms_protect_download_rewite_rules');

/**
 * File uploads
 *
 * @see colorshop_downloads_upload_dir()
 * @see colorshop_media_upload_downloadable_product()
 */
add_filter('upload_dir', 'colorshop_downloads_upload_dir');
add_action('media_upload_downloadable_product', 'colorshop_media_upload_downloadable_product');

/**
 * Shortcode buttons
 *
 * @see colorshop_add_shortcode_button()
 * @see colorshop_refresh_mce()
 */
add_action( 'init', 'colorshop_add_shortcode_button' );
add_filter( 'tiny_mce_version', 'colorshop_refresh_mce' );

/**
 * Category/term ordering
 *
 * @see colorshop_create_term()
 * @see colorshop_delete_term()
 */
add_action( "create_term", 'colorshop_create_term', 5, 3 );
add_action( "delete_term", 'colorshop_delete_term', 5 );

/**
 * Bulk editing
 *
 * @see colorshop_bulk_admin_footer()
 * @see colorshop_order_bulk_action()
 * @see colorshop_order_bulk_admin_notices()
 */
add_action( 'admin_footer', 'colorshop_bulk_admin_footer', 10 );
add_action( 'load-edit.php', 'colorshop_order_bulk_action' );
add_action( 'admin_notices', 'colorshop_order_bulk_admin_notices' );

/**
 * Mijireh Gateway
 */
add_action( 'add_meta_boxes', array( 'CS_Gateway_Mijireh', 'add_page_slurp_meta' ) );
add_action( 'wp_ajax_page_slurp', array( 'CS_Gateway_Mijireh', 'page_slurp' ) );