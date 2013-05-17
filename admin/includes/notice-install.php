<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated colorshop-message cs-connect">
	<div class="squeezer">
		<h4><?php _e( '<strong>Welcome to ColorShop</strong> &#8211; You\'re almost ready to start selling :)', 'colorshop' ); ?></h4>
		<p class="submit"><a href="<?php echo add_query_arg('install_colorshop_pages', 'true', admin_url('admin.php?page=colorshop_settings') ); ?>" class="button-primary"><?php _e( 'Install ColorShop Pages', 'colorshop' ); ?></a> <a class="skip button-primary" href="<?php echo add_query_arg('skip_install_colorshop_pages', 'true', admin_url('admin.php?page=colorshop_settings') ); ?>"><?php _e( 'Skip setup', 'colorshop' ); ?></a></p>
	</div>
</div>