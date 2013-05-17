<?php
/**
 * ColorShop Uninstall
 *
 * Uninstalling ColorShop deletes user roles, options, tables, and pages.
 *
 * @author 		ColorVila
 * @category 	Core
 * @package 	ColorShop/Uninstaller
 * @version     1.0.0
 */
if( !defined('WP_UNINSTALL_PLUGIN') ) exit();

global $wpdb, $wp_roles;

// Roles + caps
if ( ! function_exists( 'colorshop_remove_roles' ) )
	include_once( 'colorshop-core-functions.php' );

if ( function_exists( 'colorshop_remove_roles' ) )
	colorshop_remove_roles();

// Pages
wp_delete_post( get_option('colorshop_shop_page_id'), true );
wp_delete_post( get_option('colorshop_cart_page_id'), true );
wp_delete_post( get_option('colorshop_checkout_page_id'), true );
wp_delete_post( get_option('colorshop_myaccount_page_id'), true );
wp_delete_post( get_option('colorshop_edit_address_page_id'), true );
wp_delete_post( get_option('colorshop_view_order_page_id'), true );
wp_delete_post( get_option('colorshop_change_password_page_id'), true );
wp_delete_post( get_option('colorshop_pay_page_id'), true );
wp_delete_post( get_option('colorshop_thanks_page_id'), true );

// mijireh checkout page
if ( $mijireh_page = get_page_by_path( 'mijireh-secure-checkout' ) )
	wp_delete_post( $mijireh_page->ID, true );

// Tables
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "colorshop_attribute_taxonomies" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "colorshop_downloadable_product_permissions" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "colorshop_termmeta" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->base_prefix . "shareyourcart_tokens" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->base_prefix . "shareyourcart_coupons" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "colorshop_tax_rates" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "colorshop_tax_rate_locations" );

// Delete options
$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'colorshop_%';");