<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * ColorShop Integration class
 *
 * Extended by individual integrations to offer additional functionality.
 *
 * @class 		CS_Integration
 * @extends		CS_Settings_API
 * @version		1.0.0
 * @package		ColorShop/Abstracts
 * @category	Abstract Class
 * @author 		ColorVila
 */
abstract class CS_Integration extends CS_Settings_API {

	/**
	 * Admin Options
	 *
	 * Setup the gateway settings screen.
	 * Override this in your gateway.
	 *
	 * @access public
	 * @return void
	 */
	function admin_options() { ?>

		<h3><?php echo isset( $this->method_title ) ? $this->method_title : __( 'Settings', 'colorshop' ) ; ?></h3>

		<?php echo isset( $this->method_description ) ? wpautop( $this->method_description ) : ''; ?>

		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table>

		<!-- Section -->
		<div><input type="hidden" name="section" value="<?php echo $this->id; ?>" /></div>

		<?php
	}

}