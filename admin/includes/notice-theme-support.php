<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated colorshop-message cs-connect">
	<div class="squeezer">
		<h4><?php _e( '<strong>Your theme does not declare ColorShop support</strong> &#8211; if you encounter layout issues please read our integration guide or choose a ColorShop theme :)', 'colorshop' ); ?></h4>
		<p class="submit"><a href="http://colorvila.com/docs/plugins/colorshop/developer-guide/theming/third-party-custom-theme-compatibility/" class="button-primary"><?php _e( 'Theme Integration Guide', 'colorshop' ); ?></a> <a class="skip button-primary" href="<?php echo add_query_arg( 'hide_colorshop_theme_support_check', 'true' ); ?>"><?php _e( 'Hide this notice', 'colorshop' ); ?></a></p>
	</div>
</div>