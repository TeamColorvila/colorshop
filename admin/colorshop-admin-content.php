<?php
/**
 * Functions used for the showing help/links to ColorShop resources in admin
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Help Tab Content
 *
 * Shows some text about ColorShop and links to docs.
 *
 * @access public
 * @return void
 */
function colorshop_admin_help_tab_content() {
	$screen = get_current_screen();

	$screen->add_help_tab( array(
	    'id'	=> 'colorshop_overview_tab',
	    'title'	=> __( 'Overview', 'colorshop' ),
	    'content'	=>

	    	'<p>' . sprintf(__( 'Thank you for using ColorShop :) Should you need help using or extending ColorShop please <a href="%s">read the documentation</a>. For further assistance you can use the <a href="%s">community forum</a> or if you have access, <a href="%s">our support desk</a>.', 'colorshop' ), 'http://colorvila.com/docs', 'http://wordpress.org/support/plugin/colorshop', 'http://colorvila.com/forum') . '</p>' .

	    	'<p>' . __( 'If you are having problems, or to assist us with support, please check the status page to identify any problems with your configuration:', 'colorshop' ) . '</p>' .

	    	'<p><a href="' . admin_url('admin.php?page=colorshop_status') . '" class="button">' . __( 'System Status', 'colorshop' ) . '</a></p>' .

	    	'<p>' . sprintf(__( 'If you come across a bug, or wish to contribute to the project you can also <a href="%s">get involved on GitHub</a>.', 'colorshop' ), 'https://github.com/colorvila/colorshop') . '</p>'

	) );

	$screen->add_help_tab( array(
	    'id'	=> 'colorshop_settings_tab',
	    'title'	=> __( 'Settings', 'colorshop' ),
	    'content'	=>
	    	'<p>' . __( 'Here you can set up your store and customise it to fit your needs. The sections available from the settings page include:', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'General', 'colorshop' ) . '</strong> - ' . __( 'General settings such as your shop base, currency, and script/styling options which affect features used in your store.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Pages', 'colorshop' ) . '</strong> - ' . __( 'This is where important store page are defined. You can also set up other pages (such as a Terms page) here.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Catalog', 'colorshop' ) . '</strong> - ' . __( 'Options for how things like price, images and weights appear in your product catalog.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Inventory', 'colorshop' ) . '</strong> - ' . __( 'Options concerning stock and stock notices.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Tax', 'colorshop' ) . '</strong> - ' . __( 'Options concerning tax, including international and local tax rates.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Shipping', 'colorshop' ) . '</strong> - ' . __( 'This is where shipping options are defined, and shipping methods are set up.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Payment Methods', 'colorshop' ) . '</strong> - ' . __( 'This is where payment gateway options are defined, and individual payment gateways are set up.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Emails', 'colorshop' ) . '</strong> - ' . __( 'Here you can customise the way ColorShop emails appear.', 'colorshop' ) . '</p>' .
	    	'<p><strong>' . __( 'Integration', 'colorshop' ) . '</strong> - ' . __( 'The integration section contains options for third party services which integrate with ColorShop.', 'colorshop' ) . '</p>'
	) );

	$screen->add_help_tab( array(
	    'id'	=> 'colorshop_overview_tab_2',
	    'title'	=> __( 'Reports', 'colorshop' ),
	    'content'	=>
				'<p>' . __( 'The reports section can be accessed from the left-hand navigation menu. Here you can generate reports for sales and customers.', 'colorshop' ) . '</p>' .
				'<p><strong>' . __( 'Sales', 'colorshop' ) . '</strong> - ' . __( 'Reports for sales based on date, top sellers and top earners.', 'colorshop' ) . '</p>' .
				'<p><strong>' . __( 'Coupons', 'colorshop' ) . '</strong> - ' . __( 'Coupon usage reports.', 'colorshop' ) . '</p>' .
				'<p><strong>' . __( 'Customers', 'colorshop' ) . '</strong> - ' . __( 'Customer reports, such as signups per day.', 'colorshop' ) . '</p>' .
				'<p><strong>' . __( 'Stock', 'colorshop' ) . '</strong> - ' . __( 'Stock reports for low stock and out of stock items.', 'colorshop' ) . '</p>'
	) );

	$screen->add_help_tab( array(
	     'id'	=> 'colorshop_overview_tab_3',
	     'title'	=> __( 'Orders', 'colorshop' ),
	     'content'	=>
				'<p>' . __( 'The orders section can be accessed from the left-hand navigation menu. Here you can view and manage customer orders.', 'colorshop' ) . '</p>' .
				'<p>' . __( 'Orders can also be added from this section if you want to set them up for a customer manually.', 'colorshop' ) . '</p>'
	) );

	$screen->add_help_tab( array(
	     'id'	=> 'colorshop_overview_tab_4',
	     'title'	=> __( 'Coupons', 'colorshop' ),
	     'content'	=>
				'<p>' . __( 'Coupons can be managed from this section. Once added, customers will be able to enter coupon codes on the cart/checkout page. If a customer uses a coupon code they will be viewable when viewing orders.', 'colorshop' ) . '</p>'
	) );

	$screen->set_help_sidebar(
		'<p><strong>' . __( 'For more information:', 'colorshop' ) . '</strong></p>' .
		'<p><a href="http://colorvila.com/colorshop/" target="_blank">' . __( 'ColorShop', 'colorshop' ) . '</a></p>' .
		'<p><a href="http://wordpress.org/extend/plugins/colorshop/" target="_blank">' . __( 'Project on WordPress.org', 'colorshop' ) . '</a></p>' .
		//'<p><a href="https://github.com/colorvila/colorshop" target="_blank">' . __( 'Project on Github', 'colorshop' ) . '</a></p>' .
		'<p><a href="http://colorvila.com/docs" target="_blank">' . __( 'ColorShop Docs', 'colorshop' ) . '</a></p>' .
		//'<p><a href="http://www.colorvila.com/product-category/colorshop-extensions/" target="_blank">' . __( 'Official Extensions', 'colorshop' ) . '</a></p>' .
		'<p><a href="http://colorvila.com/themes/" target="_blank">' . __( 'Official Themes', 'colorshop' ) . '</a></p>'
	);
}