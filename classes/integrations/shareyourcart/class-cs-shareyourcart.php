<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * ShareYourCart Integration
 *
 * Enables ShareYourCart integration.
 *
 * @class 		CS_ShareYourCart
 * @package		ColorShop
 * @category	Integrations
 * @author		ColorVila
 */
class CS_ShareYourCart extends CS_Integration {

	public $ShareYourCartColorShop;
	public $enabled;

	public function __construct() {
        $this->id					= 'shareyourcart';
        $this->method_title     	= __( 'ShareYourCart', 'colorshop' );
        $this->method_description	= __( 'Increase your social media exposure by 10 percent! ShareYourCart helps you get more customers by motivating satisfied customers to talk with their friends about your products.', 'colorshop' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		if ( $this->enabled == 'yes' ) {

			//the classes need to be initialized
			$this->init_share_your_cart();
		}

		//hook to the admin settings page
		add_action( 'colorshop_update_options_integration_shareyourcart', array( &$this, 'process_forms') );
    }

	/**
	 * styles function.
	 *
	 * @access public
	 * @return void
	 */
	public function styles() {
		wp_enqueue_style( 'shareyourcart', plugins_url( 'css/style.css', __FILE__ ) );
	}

	/**
	 * init_share_your_cart function.
	 *
	 * @access public
	 * @return void
	 */
	function init_share_your_cart() {

		if ( empty( $this->shareYourCartColorShop ) ) {
			// Share your cart api class
			include_once('class-shareyourcart-colorshop-extended.php');

			// Init the class
			$this->shareYourCartColorShop = new ShareYourCartColorShopEx( $this->settings );

			//by the time we get here, the plugins_loaded hook has allready been called
			//so call the method manually
			$this->shareYourCartColorShop->pluginsLoadedHook();
		}

	}

	/**
	 * process_forms function.
	 *
	 * @access public
	 */
	function process_forms() {

		//after this function completes, ColorShop will refresh the page, so we need to save the data here
		$this->init_share_your_cart();

		//stripslashes from button_html
		if(isset($_POST['button_html']))
			$_POST['button_html'] = stripslashes($_POST['button_html']);

		//TODO: investigate why the files are not being uploaded

		$this->shareYourCartColorShop->getAdminPage($this); //get the admin page ( so that the data is processed, but do not show it )
		$this->shareYourCartColorShop->getButtonCustomizationPage(); //get the customization page ( so that the data is processed, but do not show it )
	}

	/**
	 * Admin Options
	 *
	 * Setup the gateway settings screen.
	 * Override this in your gateway.
	 *
	 * @since 1.0.0
	 */
	function admin_options() {

		$this->init_share_your_cart();

		if ( $this->shareYourCartColorShop->isActive() ) {

			// call this manually ( to determine if there needs to be a table update, or not )
			$this->shareYourCartColorShop->install();
		}

		$this->shareYourCartColorShop->showAdminHeader();
		$this->shareYourCartColorShop->showAdminPage($this,true,false); //send this obj to the view, but do not show the footer
		$this->shareYourCartColorShop->showButtonCustomizationPage(null,false,false); //do not show neither the header, nor the footer of this page
	}
}

/**
 * Add the integration to ColorShop
 **/
function add_shareyourcart_integration( $integrations ) {
	if ( ! class_exists('ShareYourCartAPI') ) // Only allow this integration if we're not already using shareyourcart via another plugin
		$integrations[] = 'CS_ShareYourCart';
	return $integrations;
}
add_filter('colorshop_integrations', 'add_shareyourcart_integration' );
