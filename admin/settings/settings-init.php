<?php
/**
 * Defines the array of settings which are displayed in admin.
 *
 * Settings are defined here and displayed via functions.
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/Settings
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$localisation_setting = defined( 'WPLANG' ) && file_exists( $colorshop->plugin_path() . '/i18n/languages/informal/colorshop-' . WPLANG . '.mo' ) ? array(
	'title' => __( 'Localisation', 'colorshop' ),
	'desc' 		=> sprintf( __( 'Use informal localisation for %s', 'colorshop' ), WPLANG ),
	'id' 		=> 'colorshop_informal_localisation_type',
	'type' 		=> 'checkbox',
	'default'	=> 'no',
) : array();

$currency_code_options = get_colorshop_currencies();

foreach ( $currency_code_options as $code => $name ) {
	$currency_code_options[ $code ] = $name . ' (' . get_colorshop_currency_symbol( $code ) . ')';
}

$colorshop_settings['general'] = apply_filters('colorshop_general_settings', array(

	array( 'title' => __( 'General Options', 'colorshop' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

	array(
		'title' 	=> __( 'Base Location', 'colorshop' ),
		'desc' 		=> __( 'This is the base location for your business. Tax rates will be based on this country.', 'colorshop' ),
		'id' 		=> 'colorshop_default_country',
		'css' 		=> 'min-width:350px;',
		'default'	=> 'GB',
		'type' 		=> 'single_select_country',
		'desc_tip'	=>  true,
	),

	array(
		'title' 	=> __( 'Currency', 'colorshop' ),
		'desc' 		=> __( "This controls what currency prices are listed at in the catalog and which currency gateways will take payments in.", 'colorshop' ),
		'id' 		=> 'colorshop_currency',
		'css' 		=> 'min-width:350px;',
		'default'	=> 'GBP',
		'type' 		=> 'select',
		'class'		=> 'chosen_select',
		'desc_tip'	=>  true,
		'options'   => $currency_code_options
	),

	array(
		'title' => __( 'Allowed Countries', 'colorshop' ),
		'desc' 		=> __( 'These are countries that you are willing to ship to.', 'colorshop' ),
		'id' 		=> 'colorshop_allowed_countries',
		'default'	=> 'all',
		'type' 		=> 'select',
		'class'		=> 'chosen_select',
		'css' 		=> 'min-width:350px;',
		'desc_tip'	=>  true,
		'options' => array(
			'all'  => __( 'All Countries', 'colorshop' ),
			'specific' => __( 'Specific Countries', 'colorshop' )
		)
	),

	array(
		'title' => __( 'Specific Countries', 'colorshop' ),
		'desc' 		=> '',
		'id' 		=> 'colorshop_specific_allowed_countries',
		'css' 		=> '',
		'default'	=> '',
		'type' 		=> 'multi_select_countries'
	),

	$localisation_setting,

	array(
		'title' => __( 'Store Notice', 'colorshop' ),
		'desc' 		=> __( 'Enable site-wide store notice text', 'colorshop' ),
		'id' 		=> 'colorshop_demo_store',
		'default'	=> 'no',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Store Notice Text', 'colorshop' ),
		'desc' 		=> '',
		'id' 		=> 'colorshop_demo_store_notice',
		'default'	=> __( 'This is a demo store for testing purposes &mdash; no orders shall be fulfilled.', 'colorshop' ),
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
	),

	array( 'type' => 'sectionend', 'id' => 'general_options'),

	array(	'title' => __( 'Cart, Checkout and Accounts', 'colorshop' ), 'type' => 'title', 'id' => 'checkout_account_options' ),

	array(
		'title' => __( 'Coupons', 'colorshop' ),
		'desc'          => __( 'Enable the use of coupons', 'colorshop' ),
		'id'            => 'colorshop_enable_coupons',
		'default'       => 'yes',
		'type'          => 'checkbox',
		'desc_tip'		=>  __( 'Coupons can be applied from the cart and checkout pages.', 'colorshop' ),
	),

	array(
		'title' => __( 'Checkout', 'colorshop' ),
		'desc' 		=> __( 'Enable guest checkout (no account required)', 'colorshop' ),
		'id' 		=> 'colorshop_enable_guest_checkout',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'	=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable customer note field on checkout', 'colorshop' ),
		'id' 		=> 'colorshop_enable_order_comments',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Force secure checkout', 'colorshop' ),
		'id' 		=> 'colorshop_force_ssl_checkout',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> '',
		'show_if_checked' => 'option',
		'desc_tip'	=>  __( 'Force SSL (HTTPS) on the checkout pages (an SSL Certificate is required).', 'colorshop' ),
	),

	array(
		'desc' 		=> __( 'Un-force HTTPS when leaving the checkout', 'colorshop' ),
		'id' 		=> 'colorshop_unforce_ssl_checkout',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end',
		'show_if_checked' => 'yes',
	),

	array(
		'title' => __( 'Registration', 'colorshop' ),
		'desc' 		=> __( 'Allow registration on the checkout page', 'colorshop' ),
		'id' 		=> 'colorshop_enable_signup_and_login_from_checkout',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Allow registration on the "My Account" page', 'colorshop' ),
		'id' 		=> 'colorshop_enable_myaccount_registration',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Register using the email address for the username', 'colorshop' ),
		'id' 		=> 'colorshop_registration_email_for_username',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'title' => __( 'Customer Accounts', 'colorshop' ),
		'desc' 		=> __( 'Prevent customers from accessing WordPress admin', 'colorshop' ),
		'id' 		=> 'colorshop_lock_down_admin',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Clear cart when logging out', 'colorshop' ),
		'id' 		=> 'colorshop_clear_cart_on_logout',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Allow customers to repurchase orders from their account page', 'colorshop' ),
		'id' 		=> 'colorshop_allow_customers_to_reorder',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array( 'type' => 'sectionend', 'id' => 'checkout_account_options'),

	array(	'title' => __( 'Styles and Scripts', 'colorshop' ), 'type' => 'title', 'id' => 'script_styling_options' ),

	array(
		'title' => __( 'Styling', 'colorshop' ),
		'desc' 		=> __( 'Enable ColorShop CSS', 'colorshop' ),
		'id' 		=> 'colorshop_frontend_css',
		'default'	=> 'yes',
		'type' 		=> 'checkbox'
	),

	array(
		'type' 		=> 'frontend_styles'
	),

	array(
		'title' => __( 'Scripts', 'colorshop' ),
		'desc' 	=> __( 'Enable Lightbox', 'colorshop' ),
		'id' 		=> 'colorshop_enable_lightbox',
		'default'	=> 'yes',
		'desc_tip'	=> __( 'Include ColorShop\'s lightbox. Product gallery images and the add review form will open in a lightbox.', 'colorshop' ),
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable enhanced country select boxes', 'colorshop' ),
		'id' 		=> 'colorshop_enable_chosen',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end',
		'desc_tip'	=> __( 'This will enable a script allowing the country fields to be searchable.', 'colorshop' ),
	),

	array( 'type' => 'sectionend', 'id' => 'script_styling_options'),

	array(	'title' => __( 'Downloadable Products', 'colorshop' ), 'type' => 'title', 'id' => 'digital_download_options' ),

	array(
		'title' => __( 'File Download Method', 'colorshop' ),
		'desc' 		=> __( 'Forcing downloads will keep URLs hidden, but some servers may serve large files unreliably. If supported, <code>X-Accel-Redirect</code>/ <code>X-Sendfile</code> can be used to serve downloads instead (server requires <code>mod_xsendfile</code>).', 'colorshop' ),
		'id' 		=> 'colorshop_file_download_method',
		'type' 		=> 'select',
		'class'		=> 'chosen_select',
		'css' 		=> 'min-width:300px;',
		'default'	=> 'force',
		'desc_tip'	=>  true,
		'options' => array(
			'force'  	=> __( 'Force Downloads', 'colorshop' ),
			'xsendfile' => __( 'X-Accel-Redirect/X-Sendfile', 'colorshop' ),
			'redirect'  => __( 'Redirect only', 'colorshop' ),
		)
	),

	array(
		'title' => __( 'Access Restriction', 'colorshop' ),
		'desc' 		=> __( 'Downloads require login', 'colorshop' ),
		'id' 		=> 'colorshop_downloads_require_login',
		'type' 		=> 'checkbox',
		'default'	=> 'no',
		'desc_tip'	=> __( 'This setting does not apply to guest purchases.', 'colorshop' ),
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Grant access to downloadable products after payment', 'colorshop' ),
		'id' 		=> 'colorshop_downloads_grant_access_after_payment',
		'type' 		=> 'checkbox',
		'default'	=> 'yes',
		'desc_tip'	=> __( 'Enable this option to grant access to downloads when orders are "processing", rather than "completed".', 'colorshop' ),
		'checkboxgroup'		=> 'end'
	),

	array( 'type' => 'sectionend', 'id' => 'digital_download_options' ),

)); // End general settings

// Get shop page
$shop_page_id = colorshop_get_page_id('shop');

$base_slug = ($shop_page_id > 0 && get_page( $shop_page_id )) ? get_page_uri( $shop_page_id ) : 'shop';

$colorshop_prepend_shop_page_to_products_warning = '';

if ( $shop_page_id > 0 && sizeof(get_pages("child_of=$shop_page_id")) > 0 )
	$colorshop_prepend_shop_page_to_products_warning = ' <mark class="notice">' . __( 'Note: The shop page has children - child pages will not work if you enable this option.', 'colorshop' ) . '</mark>';

$colorshop_settings['pages'] = apply_filters('colorshop_page_settings', array(

	array(
		'title' => __( 'Page Setup', 'colorshop' ),
		'type' => 'title',
		'desc' => sprintf( __( 'Set up core ColorShop pages here, for example the base page. The base page can also be used in your %sproduct permalinks%s.', 'colorshop' ), '<a target="_blank" href="' . admin_url( 'options-permalink.php' ) . '">', '</a>' ),
		'id' => 'page_options'
	),

	array(
		'title' => __( 'Shop Base Page', 'colorshop' ),
		'desc' 		=> __( 'This sets the base page of your shop - this is where your product archive will be.', 'colorshop' ),
		'id' 		=> 'colorshop_shop_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true
	),

	array(
		'title' => __( 'Terms Page ID', 'colorshop' ),
		'desc' 		=> __( 'If you define a "Terms" page the customer will be asked if they accept them when checking out.', 'colorshop' ),
		'id' 		=> 'colorshop_terms_page_id',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'type' 		=> 'single_select_page',
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'page_options' ),

	array( 'title' => __( 'Shop Pages', 'colorshop' ), 'type' => 'title', 'desc' => __( 'The following pages need selecting so that ColorShop knows where they are. These pages should have been created upon installation of the plugin, if not you will need to create them.', 'colorshop' ) ),

	array(
		'title' => __( 'Cart Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_cart]', 'colorshop' ),
		'id' 		=> 'colorshop_cart_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Checkout Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_checkout]', 'colorshop' ),
		'id' 		=> 'colorshop_checkout_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Pay Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_pay] Parent: "Checkout"', 'colorshop' ),
		'id' 		=> 'colorshop_pay_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Thanks Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_thankyou] Parent: "Checkout"', 'colorshop' ),
		'id' 		=> 'colorshop_thanks_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'My Account Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_my_account]', 'colorshop' ),
		'id' 		=> 'colorshop_myaccount_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Edit Address Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_edit_address] Parent: "My Account"', 'colorshop' ),
		'id' 		=> 'colorshop_edit_address_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'View Order Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_view_order] Parent: "My Account"', 'colorshop' ),
		'id' 		=> 'colorshop_view_order_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Change Email & Password Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_change_password] Parent: "My Account"', 'colorshop' ),
		'id' 		=> 'colorshop_change_email_and_password_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Logout Page', 'colorshop' ),
		'desc' 		=> __( 'Parent: "My Account"', 'colorshop' ),
		'id' 		=> 'colorshop_logout_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Lost Password Page', 'colorshop' ),
		'desc' 		=> __( 'Page contents: [colorshop_lost_password] Parent: "My Account"', 'colorshop' ),
		'id' 		=> 'colorshop_lost_password_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'page_options')

)); // End pages settings


$colorshop_settings['catalog'] = apply_filters('colorshop_catalog_settings', array(

	array(	'title' => __( 'Catalog Options', 'colorshop' ), 'type' => 'title','desc' => '', 'id' => 'catalog_options' ),

	array(
		'title' => __( 'Default Product Sorting', 'colorshop' ),
		'desc' 		=> __( 'This controls the default sort order of the catalog.', 'colorshop' ),
		'id' 		=> 'colorshop_default_catalog_orderby',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'title',
		'type' 		=> 'select',
		'options' => apply_filters('colorshop_default_catalog_orderby_options', array(
			'menu_order' => __( 'Default sorting (custom ordering + name)', 'colorshop' ),
			'popularity' => __( 'Popularity (sales)', 'colorshop' ),
			'rating'     => __( 'Average Rating', 'colorshop' ),
			'date'       => __( 'Sort by most recent', 'colorshop' ),
			'price'      => __( 'Sort by price (asc)', 'colorshop' ),
			'price-desc' => __( 'Sort by price (desc)', 'colorshop' ),
		)),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Shop Page Display', 'colorshop' ),
		'desc' 		=> __( 'This controls what is shown on the product archive.', 'colorshop' ),
		'id' 		=> 'colorshop_shop_page_display',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Show products', 'colorshop' ),
			'subcategories' => __( 'Show subcategories', 'colorshop' ),
			'both'   		=> __( 'Show both', 'colorshop' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Default Category Display', 'colorshop' ),
		'desc' 		=> __( 'This controls what is shown on category archives.', 'colorshop' ),
		'id' 		=> 'colorshop_category_archive_display',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Show products', 'colorshop' ),
			'subcategories' => __( 'Show subcategories', 'colorshop' ),
			'both'   		=> __( 'Show both', 'colorshop' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Add to cart', 'colorshop' ),
		'desc' 		=> __( 'Redirect to the cart page after successful addition', 'colorshop' ),
		'id' 		=> 'colorshop_cart_redirect_after_add',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable AJAX add to cart buttons on archives', 'colorshop' ),
		'id' 		=> 'colorshop_enable_ajax_add_to_cart',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array( 'type' => 'sectionend', 'id' => 'catalog_options' ),

	array(	'title' => __( 'Product Data', 'colorshop' ), 'type' => 'title', 'desc' => __( 'The following options affect the fields available on the edit product page.', 'colorshop' ), 'id' => 'product_data_options' ),

	array(
		'title' => __( 'Product Fields', 'colorshop' ),
		'desc' 		=> __( 'Enable the <strong>SKU</strong> field for products', 'colorshop' ),
		'id' 		=> 'colorshop_enable_sku',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable the <strong>weight</strong> field for products (some shipping methods may require this)', 'colorshop' ),
		'id' 		=> 'colorshop_enable_weight',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Enable the <strong>dimension</strong> fields for products (some shipping methods may require this)', 'colorshop' ),
		'id' 		=> 'colorshop_enable_dimensions',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Show <strong>weight and dimension</strong> values on the <strong>Additional Information</strong> tab', 'colorshop' ),
		'id' 		=> 'colorshop_enable_dimension_product_attributes',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'title' => __( 'Weight Unit', 'colorshop' ),
		'desc' 		=> __( 'This controls what unit you will define weights in.', 'colorshop' ),
		'id' 		=> 'colorshop_weight_unit',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'kg',
		'type' 		=> 'select',
		'options' => array(
			'kg'  => __( 'kg', 'colorshop' ),
			'g'   => __( 'g', 'colorshop' ),
			'lbs' => __( 'lbs', 'colorshop' ),
			'oz' => __( 'oz', 'colorshop' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Dimensions Unit', 'colorshop' ),
		'desc' 		=> __( 'This controls what unit you will define lengths in.', 'colorshop' ),
		'id' 		=> 'colorshop_dimension_unit',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'cm',
		'type' 		=> 'select',
		'options' => array(
			'm'  => __( 'm', 'colorshop' ),
			'cm' => __( 'cm', 'colorshop' ),
			'mm' => __( 'mm', 'colorshop' ),
			'in' => __( 'in', 'colorshop' ),
			'yd' => __( 'yd', 'colorshop' ),
		),
		'desc_tip'	=>  true,
	),
	
	array( 'type' => 'sectionend', 'id' => 'product_data_options' ),
	
	array(	'title' => __( 'Product Review', 'colorshop' ), 'type' => 'title', 'desc' => '', 'id' => 'product_review_options' ),

	array(
		'title' => __( 'Product Ratings', 'colorshop' ),
		'desc' 		=> __( 'Enable ratings on reviews', 'colorshop' ),
		'id' 		=> 'colorshop_enable_review_rating',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start',
		'show_if_checked' => 'option',
	),

	array(
		'desc' 		=> __( 'Ratings are required to leave a review', 'colorshop' ),
		'id' 		=> 'colorshop_review_rating_required',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> '',
		'show_if_checked' => 'yes',
	),

	array(
		'desc' 		=> __( 'Show "verified owner" label for customer reviews', 'colorshop' ),
		'id' 		=> 'colorshop_review_rating_verification_label',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> '',
		'show_if_checked' => 'yes',
	),
	
	array(
			'desc' 		=> __( 'Enable comment image on review', 'colorshop' ),
			'id' 		=> 'colorshop_enable_comment_image',
			'default'	=> 'yes',
			'type' 		=> 'checkbox',
			'checkboxgroup'		=> 'end',
			'show_if_checked' => 'yes'
	),
	
	array( 'type' => 'sectionend', 'id' => 'product_review_options' ),

	array(	'title' => __( 'Pricing Options', 'colorshop' ), 'type' => 'title', 'desc' => __( 'The following options affect how prices are displayed on the frontend.', 'colorshop' ), 'id' => 'pricing_options' ),

	array(
		'title' => __( 'Currency Position', 'colorshop' ),
		'desc' 		=> __( 'This controls the position of the currency symbol.', 'colorshop' ),
		'id' 		=> 'colorshop_currency_pos',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'left',
		'type' 		=> 'select',
		'options' => array(
			'left' => __( 'Left', 'colorshop' ),
			'right' => __( 'Right', 'colorshop' ),
			'left_space' => __( 'Left (with space)', 'colorshop' ),
			'right_space' => __( 'Right (with space)', 'colorshop' )
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Thousand Separator', 'colorshop' ),
		'desc' 		=> __( 'This sets the thousand separator of displayed prices.', 'colorshop' ),
		'id' 		=> 'colorshop_price_thousand_sep',
		'css' 		=> 'width:50px;',
		'default'	=> ',',
		'type' 		=> 'text',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Decimal Separator', 'colorshop' ),
		'desc' 		=> __( 'This sets the decimal separator of displayed prices.', 'colorshop' ),
		'id' 		=> 'colorshop_price_decimal_sep',
		'css' 		=> 'width:50px;',
		'default'	=> '.',
		'type' 		=> 'text',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Number of Decimals', 'colorshop' ),
		'desc' 		=> __( 'This sets the number of decimal points shown in displayed prices.', 'colorshop' ),
		'id' 		=> 'colorshop_price_num_decimals',
		'css' 		=> 'width:50px;',
		'default'	=> '2',
		'desc_tip'	=>  true,
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		)
	),

	array(
		'title'		=> __( 'Trailing Zeros', 'colorshop' ),
		'desc' 		=> __( 'Remove zeros after the decimal point. e.g. <code>$10.00</code> becomes <code>$10</code>', 'colorshop' ),
		'id' 		=> 'colorshop_price_trim_zeros',
		'default'	=> 'yes',
		'type' 		=> 'checkbox'
	),

	array( 'type' => 'sectionend', 'id' => 'pricing_options' ),

	array(	'title' => __( 'Image Options', 'colorshop' ), 'type' => 'title','desc' => sprintf(__( 'These settings affect the actual dimensions of images in your catalog - the display on the front-end will still be affected by CSS styles. After changing these settings you may need to <a href="%s">regenerate your thumbnails</a>.', 'colorshop' ), 'http://wordpress.org/extend/plugins/regenerate-thumbnails/'), 'id' => 'image_options' ),

	array(
		'title' => __( 'Catalog Images', 'colorshop' ),
		'desc' 		=> __( 'This size is usually used in product listings', 'colorshop' ),
		'id' 		=> 'shop_catalog_image_size',
		'css' 		=> '',
		'type' 		=> 'image_width',
		'default'	=> array(
			'width' 	=> '150',
			'height'	=> '150',
			'crop'		=> true
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Single Product Image', 'colorshop' ),
		'desc' 		=> __( 'This is the size used by the main image on the product page.', 'colorshop' ),
		'id' 		=> 'shop_single_image_size',
		'css' 		=> '',
		'type' 		=> 'image_width',
		'default'	=> array(
			'width' 	=> '300',
			'height'	=> '300',
			'crop'		=> 1
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Product Thumbnails', 'colorshop' ),
		'desc' 		=> __( 'This size is usually used for the gallery of images on the product page.', 'colorshop' ),
		'id' 		=> 'shop_thumbnail_image_size',
		'css' 		=> '',
		'type' 		=> 'image_width',
		'default'	=> array(
			'width' 	=> '90',
			'height'	=> '90',
			'crop'		=> 1
		),
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'image_options' ),

)); // End catalog settings


$colorshop_settings['inventory'] = apply_filters('colorshop_inventory_settings', array(

	array(	'title' => __( 'Inventory Options', 'colorshop' ), 'type' => 'title','desc' => '', 'id' => 'inventory_options' ),

	array(
		'title' => __( 'Manage Stock', 'colorshop' ),
		'desc' 		=> __( 'Enable stock management', 'colorshop' ),
		'id' 		=> 'colorshop_manage_stock',
		'default'	=> 'yes',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Hold Stock (minutes)', 'colorshop' ),
		'desc' 		=> __( 'Hold stock (for unpaid orders) for x minutes. When this limit is reached, the pending order will be cancelled. Leave blank to disable.', 'colorshop' ),
		'id' 		=> 'colorshop_hold_stock_minutes',
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		),
		'css' 		=> 'width:50px;',
		'default'	=> '60'
	),

	array(
		'title' => __( 'Notifications', 'colorshop' ),
		'desc' 		=> __( 'Enable low stock notifications', 'colorshop' ),
		'id' 		=> 'colorshop_notify_low_stock',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup' => 'start'
	),

	array(
		'desc' 		=> __( 'Enable out of stock notifications', 'colorshop' ),
		'id' 		=> 'colorshop_notify_no_stock',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup' => 'end'
	),

	array(
		'title' => __( 'Notification Recipient', 'colorshop' ),
		'desc' 		=> '',
		'id' 		=> 'colorshop_stock_email_recipient',
		'type' 		=> 'email',
		'default'	=> get_option( 'admin_email' )
	),

	array(
		'title' => __( 'Low Stock Threshold', 'colorshop' ),
		'desc' 		=> '',
		'id' 		=> 'colorshop_notify_low_stock_amount',
		'css' 		=> 'width:50px;',
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		),
		'default'	=> '2'
	),

	array(
		'title' => __( 'Out Of Stock Threshold', 'colorshop' ),
		'desc' 		=> '',
		'id' 		=> 'colorshop_notify_no_stock_amount',
		'css' 		=> 'width:50px;',
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		),
		'default'	=> '0'
	),

	array(
		'title' => __( 'Out Of Stock Visibility', 'colorshop' ),
		'desc' 		=> __( 'Hide out of stock items from the catalog', 'colorshop' ),
		'id' 		=> 'colorshop_hide_out_of_stock_items',
		'default'	=> 'no',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Stock Display Format', 'colorshop' ),
		'desc' 		=> __( 'This controls how stock is displayed on the frontend.', 'colorshop' ),
		'id' 		=> 'colorshop_stock_format',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Always show stock e.g. "12 in stock"', 'colorshop' ),
			'low_amount'	=> __( 'Only show stock when low e.g. "Only 2 left in stock" vs. "In Stock"', 'colorshop' ),
			'no_amount' 	=> __( 'Never show stock amount', 'colorshop' ),
		),
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'inventory_options'),

)); // End inventory settings


$colorshop_settings['shipping'] = apply_filters('colorshop_shipping_settings', array(

	array( 'title' => __( 'Shipping Options', 'colorshop' ), 'type' => 'title', 'id' => 'shipping_options' ),

	array(
		'title' 		=> __( 'Shipping Calculations', 'colorshop' ),
		'desc' 		=> __( 'Enable shipping', 'colorshop' ),
		'id' 		=> 'colorshop_calc_shipping',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable the shipping calculator on the cart page', 'colorshop' ),
		'id' 		=> 'colorshop_enable_shipping_calc',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Hide shipping costs until an address is entered', 'colorshop' ),
		'id' 		=> 'colorshop_shipping_cost_requires_address',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'title' 	=> __( 'Shipping Method Display', 'colorshop' ),
		'desc' 		=> __( 'This controls how multiple shipping methods are displayed on the frontend.', 'colorshop' ),
		'id' 		=> 'colorshop_shipping_method_format',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Radio buttons', 'colorshop' ),
			'select'		=> __( 'Select box', 'colorshop' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' 	=> __( 'Shipping Destination', 'colorshop' ),
		'desc' 		=> __( 'Only ship to the users billing address', 'colorshop' ),
		'id' 		=> 'colorshop_ship_to_billing_address_only',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Ship to billing address by default', 'colorshop' ),
		'id' 		=> 'colorshop_ship_to_same_address',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Collect shipping address even when not required', 'colorshop' ),
		'id' 		=> 'colorshop_require_shipping_address',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'type' 		=> 'shipping_methods',
	),

	array( 'type' => 'sectionend', 'id' => 'shipping_options' ),

)); // End shipping settings


$colorshop_settings['payment_gateways'] = apply_filters('colorshop_payment_gateways_settings', array(

	array( 'title' => __( 'Payment Gateways', 'colorshop' ), 'desc' => __( 'Installed payment gateways are displayed below. Drag and drop payment gateways to control their display order on the checkout.', 'colorshop' ), 'type' => 'title', 'id' => 'payment_gateways_options' ),

	array(
		'type' 		=> 'payment_gateways',
	),

	array( 'type' => 'sectionend', 'id' => 'payment_gateways_options' ),

)); // End payment_gateway settings

$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'colorshop_tax_classes' ) ) ) );
$classes_options = array();
if ( $tax_classes )
	foreach ( $tax_classes as $class )
		$classes_options[ sanitize_title( $class ) ] = esc_html( $class );

$colorshop_settings['tax'] = apply_filters('colorshop_tax_settings', array(

	array(	'title' => __( 'Tax Options', 'colorshop' ), 'type' => 'title','desc' => '', 'id' => 'tax_options' ),

	array(
		'title' => __( 'Enable Taxes', 'colorshop' ),
		'desc' 		=> __( 'Enable taxes and tax calculations', 'colorshop' ),
		'id' 		=> 'colorshop_calc_taxes',
		'default'	=> 'no',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Prices Entered With Tax', 'colorshop' ),
		'id' 		=> 'colorshop_prices_include_tax',
		'default'	=> 'no',
		'type' 		=> 'radio',
		'desc_tip'	=>  __( 'This option is important as it will affect how you input prices. Changing it will not update existing products.', 'colorshop' ),
		'options'	=> array(
			'yes' => __( 'Yes, I will enter prices inclusive of tax', 'colorshop' ),
			'no' => __( 'No, I will enter prices exclusive of tax', 'colorshop' )
		),
	),

	array(
		'title'     => __( 'Calculate Tax Based On:', 'colorshop' ),
		'id'        => 'colorshop_tax_based_on',
		'desc_tip'	=>  __( 'This option determines which address is used to calculate tax.', 'colorshop' ),
		'default'   => 'shipping',
		'type'      => 'select',
		'options'   => array(
			'shipping' => __( 'Customer shipping address', 'colorshop' ),
			'billing'  => __( 'Customer billing address', 'colorshop' ),
			'base'     => __( 'Shop base address', 'colorshop' )
		),
	),

	array(
		'title'     => __( 'Default Customer Address:', 'colorshop' ),
		'id'        => 'colorshop_default_customer_address',
		'desc_tip'	=>  __( 'This option determines the customers default address (before they input their own).', 'colorshop' ),
		'default'   => 'base',
		'type'      => 'select',
		'options'   => array(
			''     => __( 'No address', 'colorshop' ),
			'base' => __( 'Shop base address', 'colorshop' ),
		),
	),

	array(
		'title' 		=> __( 'Shipping Tax Class:', 'colorshop' ),
		'desc' 		=> __( 'Optionally control which tax class shipping gets, or leave it so shipping tax is based on the cart items themselves.', 'colorshop' ),
		'id' 		=> 'colorshop_shipping_tax_class',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'title',
		'type' 		=> 'select',
		'options' 	=> array( '' => __( 'Shipping tax class based on cart items', 'colorshop' ), 'standard' => __( 'Standard', 'colorshop' ) ) + $classes_options,
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Rounding', 'colorshop' ),
		'desc' 		=> __( 'Round tax at subtotal level, instead of rounding per line', 'colorshop' ),
		'id' 		=> 'colorshop_tax_round_at_subtotal',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
	),

	array(
		'title' 		=> __( 'Additional Tax Classes', 'colorshop' ),
		'desc' 		=> __( 'List additonal tax classes below (1 per line). This is in addition to the default <code>Standard Rate</code>. Tax classes can be assigned to products.', 'colorshop' ),
		'id' 		=> 'colorshop_tax_classes',
		'css' 		=> 'width:100%; height: 65px;',
		'type' 		=> 'textarea',
		'default'	=> sprintf( __( 'Reduced Rate%sZero Rate', 'colorshop' ), PHP_EOL )
	),

	array(
		'title'   => __( 'Display prices during cart/checkout:', 'colorshop' ),
		'id'      => 'colorshop_tax_display_cart',
		'default' => 'excl',
		'type'    => 'select',
		'options' => array(
			'incl'   => __( 'Including tax', 'colorshop' ),
			'excl'   => __( 'Excluding tax', 'colorshop' ),
		),
	),

	array( 'type' => 'sectionend', 'id' => 'tax_options' ),

)); // End tax settings

$colorshop_settings['email'] = apply_filters('colorshop_email_settings', array(

	array( 'type' => 'sectionend', 'id' => 'email_recipient_options' ),

	array(	'title' => __( 'Email Sender Options', 'colorshop' ), 'type' => 'title', 'desc' => __( 'The following options affect the sender (email address and name) used in ColorShop emails.', 'colorshop' ), 'id' => 'email_options' ),

	array(
		'title' => __( '"From" Name', 'colorshop' ),
		'desc' 		=> '',
		'id' 		=> 'colorshop_email_from_name',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'default'	=> esc_attr(get_bloginfo('title'))
	),

	array(
		'title' => __( '"From" Email Address', 'colorshop' ),
		'desc' 		=> '',
		'id' 		=> 'colorshop_email_from_address',
		'type' 		=> 'email',
		'custom_attributes' => array(
			'multiple' 	=> 'multiple'
		),
		'css' 		=> 'min-width:300px;',
		'default'	=> get_option('admin_email')
	),

	array( 'type' => 'sectionend', 'id' => 'email_options' ),

	array(	'title' => __( 'Email Template', 'colorshop' ), 'type' => 'title', 'desc' => sprintf(__( 'This section lets you customise the ColorShop emails. <a href="%s" target="_blank">Click here to preview your email template</a>. For more advanced control copy <code>colorshop/templates/emails/</code> to <code>yourtheme/colorshop/emails/</code>.', 'colorshop' ), wp_nonce_url(admin_url('?preview_colorshop_mail=true'), 'preview-mail')), 'id' => 'email_template_options' ),

	array(
		'title' => __( 'Header Image', 'colorshop' ),
		'desc' 		=> sprintf(__( 'Enter a URL to an image you want to show in the email\'s header. Upload your image using the <a href="%s">media uploader</a>.', 'colorshop' ), admin_url('media-new.php')),
		'id' 		=> 'colorshop_email_header_image',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'default'	=> ''
	),

	array(
		'title' => __( 'Email Footer Text', 'colorshop' ),
		'desc' 		=> __( 'The text to appear in the footer of ColorShop emails.', 'colorshop' ),
		'id' 		=> 'colorshop_email_footer_text',
		'css' 		=> 'width:100%; height: 75px;',
		'type' 		=> 'textarea',
		'default'	=> get_bloginfo('title') . ' - ' . __( 'Powered by ColorShop', 'colorshop' )
	),

	array(
		'title' => __( 'Base Colour', 'colorshop' ),
		'desc' 		=> __( 'The base colour for ColorShop email templates. Default <code>#557da1</code>.', 'colorshop' ),
		'id' 		=> 'colorshop_email_base_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#557da1'
	),

	array(
		'title' => __( 'Background Colour', 'colorshop' ),
		'desc' 		=> __( 'The background colour for ColorShop email templates. Default <code>#f5f5f5</code>.', 'colorshop' ),
		'id' 		=> 'colorshop_email_background_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#f5f5f5'
	),

	array(
		'title' => __( 'Email Body Background Colour', 'colorshop' ),
		'desc' 		=> __( 'The main body background colour. Default <code>#fdfdfd</code>.', 'colorshop' ),
		'id' 		=> 'colorshop_email_body_background_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#fdfdfd'
	),

	array(
		'title' => __( 'Email Body Text Colour', 'colorshop' ),
		'desc' 		=> __( 'The main body text colour. Default <code>#505050</code>.', 'colorshop' ),
		'id' 		=> 'colorshop_email_text_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#505050'
	),

	array( 'type' => 'sectionend', 'id' => 'email_template_options' ),

)); // End email settings