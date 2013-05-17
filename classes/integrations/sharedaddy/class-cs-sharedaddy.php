<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * ShareDaddy Integration
 *
 * Enables ShareDaddy integration.
 *
 * @class 		CS_ShareDaddy
 * @extends		CS_Integration
 * @version		1.0.0
 * @package		ColorShop/Classes/Integrations
 * @author 		ColorVila
 */
class CS_ShareDaddy extends CS_Integration {

	/**
	 * Init and hook in the integration.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
        $this->id					= 'sharedaddy';
        $this->method_title     	= __( 'ShareDaddy', 'colorshop' );
        $this->method_description	= __( 'ShareDaddy is a sharing plugin bundled with JetPack.', 'colorshop' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Actions
		add_action( 'colorshop_update_options_integration_sharedaddy', array( $this, 'process_admin_options' ) );

		// Share widget
		add_action( 'colorshop_share', array( $this, 'sharedaddy_code' ) );
    }


    /**
     * Initialise Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {

    	$this->form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Output ShareDaddy button?', 'colorshop' ),
				'description' 	=> __( 'Enable this option to show the ShareDaddy button on the product page.', 'colorshop' ),
				'type' 			=> 'checkbox',
				'default' 		=> get_option('colorshop_sharedaddy') ? get_option('colorshop_sharedaddy') : 'no'
			)
		);

    }


    /**
     * Output share code.
     *
     * @access public
     * @return void
     */
    function sharedaddy_code() {
    	global $post;

    	if ( $this->enabled == 'yes' && function_exists('sharing_display') ) {

    		?><div class="social"><?php echo sharing_display(); ?></div><?php

    	}
    }

}


/**
 * Add the integration to ColorShop.
 *
 * @package		ColorShop/Classes/Integrations
 * @access public
 * @param array $integrations
 * @return array
 */
function add_sharedaddy_integration( $integrations ) {
	if ( class_exists('jetpack') )
		$integrations[] = 'CS_ShareDaddy';
	return $integrations;
}

add_filter('colorshop_integrations', 'add_sharedaddy_integration' );