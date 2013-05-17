<?php
/**
 * ColorShop Integrations class
 *
 * Loads Integrations into ColorShop.
 *
 * @class 		CS_Integrations
 * @version		1.0.0
 * @package		ColorShop/Classes/Integrations
 * @category	Class
 * @author 		ColorVila
 */
class CS_Integrations {

	/** @var array Array of integration classes */
	var $integrations = array();

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {

		do_action( 'colorshop_integrations_init' );

		$load_integrations = apply_filters( 'colorshop_integrations', array() );

		// Load integration classes
		foreach ( $load_integrations as $integration ) {

			$load_integration = new $integration();

			$this->integrations[$load_integration->id] = $load_integration;

		}

	}

	/**
	 * Return loaded integrations.
	 *
	 * @access public
	 * @return array
	 */
	public function get_integrations() {
		return $this->integrations;
	}
}