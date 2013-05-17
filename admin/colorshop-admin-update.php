<?php
/**
 * ColorShop Updates
 *
 * Plugin updates script which updates the database.
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/Updates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Runs the installer.
 *
 * @access public
 * @return void
 */
function do_update_colorshop() {
	global $colorshop;

	// Include installer so we have page creation functions
	include_once( 'colorshop-admin-install.php' );

	// Do updates
	$current_db_version = get_option( 'colorshop_db_version' );

	if ( version_compare( $current_db_version, '1.4', '<' ) ) {
		include( 'includes/updates/colorshop-update-1.4.php' );
		update_option( 'colorshop_db_version', '1.4' );
	}

	if ( version_compare( $current_db_version, '1.5', '<' ) ) {
		include( 'includes/updates/colorshop-update-1.5.php' );
		update_option( 'colorshop_db_version', '1.5' );
	}

	if ( version_compare( $current_db_version, '2.0', '<' ) ) {
		include( 'includes/updates/colorshop-update-2.0.php' );
		update_option( 'colorshop_db_version', '2.0' );
	}

	update_option( 'colorshop_db_version', $colorshop->version );
}