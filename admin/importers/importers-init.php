<?php
/**
 * Init/register importers for ColorShop.
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/Importers
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

register_importer( 'colorshop_tax_rate_csv', __( 'ColorShop Tax Rates (CSV)', 'colorshop' ), __( 'Import <strong>tax rates</strong> to your store via a csv file.', 'colorshop'), 'colorshop_tax_rates_importer' );

/**
 * colorshop_tax_rates_importer function.
 *
 * @access public
 * @return void
 */
function colorshop_tax_rates_importer() {

	// Load Importer API
	require_once ABSPATH . 'wp-admin/includes/import.php';

	if ( ! class_exists( 'WP_Importer' ) ) {
		$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		if ( file_exists( $class_wp_importer ) )
			require $class_wp_importer;
	}

	// includes
	require dirname( __FILE__ ) . '/tax-rates-importer.php';

	// Dispatch
	$CS_CSV_Tax_Rates_Import = new CS_CSV_Tax_Rates_Import();

	$CS_CSV_Tax_Rates_Import->dispatch();
}