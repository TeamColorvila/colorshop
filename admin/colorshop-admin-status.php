<?php
/**
 * Debug/Status page
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin/System Status
 * @version     1.0.0
 */

/**
 * Output the content of the debugging page.
 *
 * @access public
 * @return void
 */
function colorshop_status() {
	global $colorshop, $wpdb;

	$current_tab = ! empty( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'status';
    ?>
	<div class="wrap colorshop">
		<div class="icon32 icon32-colorshop-status" id="icon-colorshop"><br /></div><h2 class="nav-tab-wrapper color-nav-tab-wrapper">
			<?php
				$tabs = array(
					'status' => __( 'System Status', 'colorshop' ),
					'tools'  => __( 'Tools', 'colorshop' ),
				);
				foreach ( $tabs as $name => $label ) {
					echo '<a href="' . admin_url( 'admin.php?page=colorshop_status&tab=' . $name ) . '" class="nav-tab ';
					if ( $current_tab == $name ) echo 'nav-tab-active';
					echo '">' . $label . '</a>';
				}
			?>
		</h2><br/>
		<?php
			switch ( $current_tab ) {
				case "tools" :
					colorshop_status_tools();
				break;
				default :
					colorshop_status_report();
				break;
			}
		?>
	</div>
	<?php
}

/**
 * colorshop_status_report function.
 *
 * @access public
 * @return void
 */
function colorshop_status_report() {
	global $colorshop, $wpdb;

	?>
	<div class="colorshop-message">
		<div class="squeezer">
			<h4><?php _e( 'Please include this information when requesting support:', 'colorshop' ); ?> </h4>
			<p class="submit"><a href="#" download="cs_report.txt" class="button-primary debug-report"><?php _e( 'Download System Report File', 'colorshop' ); ?></a></p>
		</div>
	</div>
	<br/>
	<table class="cs_status_table widefat" cellspacing="0">

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Environment', 'colorshop' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
                <td><?php _e( 'Home URL','colorshop' ); ?>:</td>
                <td><?php echo home_url(); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Site URL','colorshop' ); ?>:</td>
                <td><?php echo site_url(); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'CS Version','colorshop' ); ?>:</td>
                <td><?php echo esc_html( $colorshop->version ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'CS Database Version','colorshop' ); ?>:</td>
                <td><?php echo esc_html( get_option( 'colorshop_db_version' ) ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Version','colorshop' ); ?>:</td>
                <td><?php if ( is_multisite() ) echo 'WPMU'; else echo 'WP'; ?> <?php echo bloginfo('version'); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Web Server Info','colorshop' ); ?>:</td>
                <td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] );  ?></td>
            </tr>
            <tr>
                <td><?php _e( 'PHP Version','colorshop' ); ?>:</td>
                <td><?php if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'MySQL Version','colorshop' ); ?>:</td>
                <td><?php if ( function_exists( 'mysql_get_server_info' ) ) echo esc_html( mysql_get_server_info() ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Memory Limit','colorshop' ); ?>:</td>
                <td><?php
                	$memory = colorshop_let_to_num( WP_MEMORY_LIMIT );

                	if ( $memory < 67108864 ) {
                		echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 64MB. See: <a href="%s">Increasing memory allocated to PHP</a>', 'colorshop' ), wp_convert_bytes_to_hr( $memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
                	} else {
                		echo '<mark class="yes">' . wp_convert_bytes_to_hr( $memory ) . '</mark>';
                	}
                ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Debug Mode','colorshop' ); ?>:</td>
                <td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . __( 'Yes', 'colorshop' ) . '</mark>'; else echo '<mark class="no">' . __( 'No', 'colorshop' ) . '</mark>'; ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Max Upload Size','colorshop' ); ?>:</td>
                <td><?php echo wp_convert_bytes_to_hr( wp_max_upload_size() ); ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Post Max Size','colorshop' ); ?>:</td>
                <td><?php if ( function_exists( 'ini_get' ) ) echo wp_convert_bytes_to_hr( colorshop_let_to_num( ini_get('post_max_size') ) ); ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Time Limit','colorshop' ); ?>:</td>
                <td><?php if ( function_exists( 'ini_get' ) ) echo ini_get('max_execution_time'); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'CS Logging','colorshop' ); ?>:</td>
                <td><?php
                	if ( @fopen( $colorshop->plugin_path() . '/logs/paypal.txt', 'a' ) )
                		echo '<mark class="yes">' . __( 'Log directory is writable.', 'colorshop' ) . '</mark>';
                	else
                		echo '<mark class="error">' . __( 'Log directory (<code>colorshop/logs/</code>) is not writable. Logging will not be possible.', 'colorshop' ) . '</mark>';
                ?></td>
            </tr>
            <?php
				$posting = array();

				// fsockopen/cURL
				$posting['fsockopen_curl']['name'] = __( 'fsockopen/cURL','colorshop');
				if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
					if ( function_exists( 'fsockopen' ) && function_exists( 'curl_init' )) {
						$posting['fsockopen_curl']['note'] = __('Your server has fsockopen and cURL enabled.', 'colorshop' );
					} elseif ( function_exists( 'fsockopen' )) {
						$posting['fsockopen_curl']['note'] = __( 'Your server has fsockopen enabled, cURL is disabled.', 'colorshop' );
					} else {
						$posting['fsockopen_curl']['note'] = __( 'Your server has cURL enabled, fsockopen is disabled.', 'colorshop' );
					}
					$posting['fsockopen_curl']['success'] = true;
				} else {
	        		$posting['fsockopen_curl']['note'] = __( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'colorshop' ). '</mark>';
	        		$posting['fsockopen_curl']['success'] = false;
	        	}

	        	// SOAP
	        	$posting['soap_client']['name'] = __( 'SOAP Client','colorshop' );
				if ( class_exists( 'SoapClient' ) ) {
					$posting['soap_client']['note'] = __('Your server has the SOAP Client class enabled.', 'colorshop' );
					$posting['soap_client']['success'] = true;
				} else {
	        		$posting['soap_client']['note'] = sprintf( __( 'Your server does not have the <a href="%s">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected.', 'colorshop' ), 'http://php.net/manual/en/class.soapclient.php' ) . '</mark>';
	        		$posting['soap_client']['success'] = false;
	        	}

	        	// WP Remote Post Check
				$posting['wp_remote_post']['name'] = __( 'WP Remote Post','colorshop');
				$request['cmd'] = '_notify-validate';
				$params = array(
					'sslverify' 	=> false,
		        	'timeout' 		=> 60,
		        	'user-agent'	=> 'ColorShop/' . $colorshop->version,
		        	'body'			=> $request
				);
				$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

				if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
	        		$posting['wp_remote_post']['note'] = __('wp_remote_post() was successful - PayPal IPN is working.', 'colorshop' );
	        		$posting['wp_remote_post']['success'] = true;
	        	} elseif ( is_wp_error( $response ) ) {
	        		$posting['wp_remote_post']['note'] = __( 'wp_remote_post() failed. PayPal IPN won\'t work with your server. Contact your hosting provider. Error:', 'colorshop' ) . ' ' . $response->get_error_message();
	        		$posting['wp_remote_post']['success'] = false;
	        	} else {
	            	$posting['wp_remote_post']['note'] = __( 'wp_remote_post() failed. PayPal IPN may not work with your server.', 'colorshop' );
	        		$posting['wp_remote_post']['success'] = false;
	        	}

	        	$posting = apply_filters( 'colorshop_debug_posting', $posting );

	        	foreach( $posting as $post ) { $mark = ( isset( $post['success'] ) && $post['success'] == true ) ? 'yes' : 'error';
	        		?>
					<tr>
		                <td><?php echo esc_html( $post['name'] ); ?>:</td>
		                <td>
		                	<mark class="<?php echo $mark; ?>">
		                    	<?php echo wp_kses_data( $post['note'] ); ?>
		                	</mark>
		                </td>
		            </tr>
		            <?php
	            }
	        ?>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Plugins', 'colorshop' ); ?></th>
			</tr>
		</thead>

		<tbody>
         	<tr>
         		<td><?php _e( 'Installed Plugins','colorshop' ); ?>:</td>
         		<td><?php
         			$active_plugins = (array) get_option( 'active_plugins', array() );

         			if ( is_multisite() )
						$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

					$cs_plugins = array();

					foreach ( $active_plugins as $plugin ) {

						$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$dirname        = dirname( $plugin );
						$version_string = '';

						if ( ! empty( $plugin_data['Name'] ) ) {

							if ( strstr( $dirname, 'colorshop' ) ) {

								if ( false === ( $version_data = get_transient( $plugin . '_version_data' ) ) ) {
									$changelog = wp_remote_get( 'http://colorvila.com/changelogs/extensions/' . $dirname . '/changelog.txt' );
									$cl_lines  = explode( "\n", wp_remote_retrieve_body( $changelog ) );
									if ( ! empty( $cl_lines ) ) {
										foreach ( $cl_lines as $line_num => $cl_line ) {
											if ( preg_match( '/^[0-9]/', $cl_line ) ) {

												$date         = str_replace( '.' , '-' , trim( substr( $cl_line , 0 , strpos( $cl_line , '-' ) ) ) );
												$version      = preg_replace( '~[^0-9,.]~' , '' ,stristr( $cl_line , "version" ) );
												$update       = trim( str_replace( "*" , "" , $cl_lines[ $line_num + 1 ] ) );
												$version_data = array( 'date' => $date , 'version' => $version , 'update' => $update , 'changelog' => $changelog );
												set_transient( $plugin . '_version_data', $version_data , 60*60*12 );
												break;
											}
										}
									}
								}

								if ( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '!=' ) )
									$version_string = ' &ndash; <strong style="color:red;">' . $version_data['version'] . ' ' . __( 'is available', 'colorshop' ) . '</strong>';
							}

							$cs_plugins[] = $plugin_data['Name'] . ' ' . __( 'by', 'colorshop' ) . ' ' . $plugin_data['Author'] . ' ' . __( 'version', 'colorshop' ) . ' ' . $plugin_data['Version'] . $version_string;

						}
					}

					if ( sizeof( $cs_plugins ) == 0 )
						echo '-';
					else
						echo implode( ', <br/>', $cs_plugins );

         		?></td>
         	</tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Settings', 'colorshop' ); ?></th>
			</tr>
		</thead>

		<tbody>

            <tr>
                <td><?php _e( 'Force SSL','colorshop' ); ?>:</td>
				<td><?php echo get_option( 'colorshop_force_ssl_checkout' ) === 'yes' ? '<mark class="yes">'.__( 'Yes', 'colorshop' ).'</mark>' : '<mark class="no">'.__( 'No', 'colorshop' ).'</mark>'; ?></td>
            </tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'CS Pages', 'colorshop' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
				$check_pages = array(
					__( 'Shop Base', 'colorshop' ) => array(
							'option' => 'colorshop_shop_page_id',
							'shortcode' => ''
						),
					__( 'Cart', 'colorshop' ) => array(
							'option' => 'colorshop_cart_page_id',
							'shortcode' => '[colorshop_cart]'
						),
					__( 'Checkout', 'colorshop' ) => array(
							'option' => 'colorshop_checkout_page_id',
							'shortcode' => '[colorshop_checkout]'
						),
					__( 'Pay', 'colorshop' ) => array(
							'option' => 'colorshop_pay_page_id',
							'shortcode' => '[colorshop_pay]'
						),
					__( 'Thanks', 'colorshop' ) => array(
							'option' => 'colorshop_thanks_page_id',
							'shortcode' => '[colorshop_thankyou]'
						),
					__( 'My Account', 'colorshop' ) => array(
							'option' => 'colorshop_myaccount_page_id',
							'shortcode' => '[colorshop_my_account]'
						),
					__( 'Edit Address', 'colorshop' ) => array(
							'option' => 'colorshop_edit_address_page_id',
							'shortcode' => '[colorshop_edit_address]'
						),
					__( 'View Order', 'colorshop' ) => array(
							'option' => 'colorshop_view_order_page_id',
							'shortcode' => '[colorshop_view_order]'
						),
					__( 'Change Password', 'colorshop' ) => array(
							'option' => 'colorshop_change_password_page_id',
							'shortcode' => '[colorshop_change_password]'
						),
					__( 'Lost Password', 'colorshop' ) => array(
							'option' => 'colorshop_lost_password_page_id',
							'shortcode' => '[colorshop_lost_password]'
						)
				);

				$alt = 1;

				foreach ( $check_pages as $page_name => $values ) {

					if ( $alt == 1 ) echo '<tr>'; else echo '<tr>';

					echo '<td>' . esc_html( $page_name ) . ':</td><td>';

					$error = false;

					$page_id = get_option( $values['option'] );

					// Page ID check
					if ( ! $page_id ) {
						echo '<mark class="error">' . __( 'Page not set', 'colorshop' ) . '</mark>';
						$error = true;
					} else {

						// Shortcode check
						if ( $values['shortcode'] ) {
							$page = get_post( $page_id );

							if ( empty( $page ) ) {

								echo '<mark class="error">' . sprintf( __( 'Page does not exist', 'colorshop' ) ) . '</mark>';
								$error = true;

							} else if ( ! strstr( $page->post_content, $values['shortcode'] ) ) {

								echo '<mark class="error">' . sprintf( __( 'Page does not contain the shortcode: %s', 'colorshop' ), $values['shortcode'] ) . '</mark>';
								$error = true;

							}
						}

					}

					if ( ! $error ) echo '<mark class="yes">#' . absint( $page_id ) . ' - ' . str_replace( home_url(), '', get_permalink( $page_id ) ) . '</mark>';

					echo '</td></tr>';

					$alt = $alt * -1;
				}
			?>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'CS Taxonomies', 'colorshop' ); ?></th>
			</tr>
		</thead>

		<tbody>
            <tr>
                <td><?php _e( 'Order Statuses', 'colorshop' ); ?>:</td>
                <td><?php
                	$display_terms = array();
                	$terms = get_terms( 'shop_order_status', array( 'hide_empty' => 0 ) );
                	foreach ( $terms as $term )
                		$display_terms[] = $term->name . ' (' . $term->slug . ')';
                	echo implode( ', ', array_map( 'esc_html', $display_terms ) );
                ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Product Types', 'colorshop' ); ?>:</td>
                <td><?php
                	$display_terms = array();
                	$terms = get_terms( 'product_type', array( 'hide_empty' => 0 ) );
                	foreach ( $terms as $term )
                		$display_terms[] = $term->name . ' (' . $term->slug . ')';
                	echo implode( ', ', array_map( 'esc_html', $display_terms ) );
                ?></td>
            </tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Templates', 'colorshop' ); ?></th>
			</tr>
		</thead>

		<tbody>
            <tr>
                <td><?php _e( 'Template Overrides', 'colorshop' ); ?>:</td>
                <td><?php

					$template_path = $colorshop->plugin_path() . '/templates/';
					$found_files   = array();
					$files         = colorshop_scan_template_files( $template_path );

					foreach ( $files as $file ) {
						if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
							$found_files[] = '/' . $file;
						} elseif( file_exists( get_stylesheet_directory() . '/colorshop/' . $file ) ) {
							$found_files[] = '/colorshop/' . $file;
						}
					}

					if ( $found_files ) {
						echo implode( ', <br/>', $found_files );
					} else {
						_e( 'No core overrides present in theme.', 'colorshop' );
					}

                ?></td>
            </tr>
		</tbody>

	</table>
	<script type="text/javascript">

		jQuery.cs_strPad = function(i,l,s) {
			var o = i.toString();
			if (!s) { s = '0'; }
			while (o.length < l) {
				o = o + s;
			}
			return o;
		};

		jQuery('a.debug-report').click(function(){

			var report = "";

			jQuery('.cs_status_table thead, .cs_status_table tbody').each(function(){

				$this = jQuery( this );

				if ( $this.is('thead') ) {

					report = report + "\n### " + jQuery.trim( $this.text() ) + " ###\n\n";

				} else {

					jQuery('tr', $this).each(function(){

						$this = jQuery( this );

						name = jQuery.cs_strPad( jQuery.trim( $this.find('td:eq(0)').text() ), 25, ' ' );
						value = jQuery.trim( $this.find('td:eq(1)').text() );

						report = report + '' + name + value + "\n\n";
					});

				}
			} );

			var blob = new Blob( [report] );

			jQuery(this).attr( 'href', window.URL.createObjectURL( blob ) );

      		return true;
		});

	</script>
	<?php
}

/**
 * colorshop_scan_template_files function.
 *
 * @access public
 * @param mixed $template_path
 * @return void
 */
function colorshop_scan_template_files( $template_path ) {
	$files         = scandir( $template_path );
	$result        = array();
	if ( $files ) {
		foreach ( $files as $key => $value ) {
			if ( ! in_array( $value, array( ".",".." ) ) ) {
				if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
					$sub_files = colorshop_scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );
					foreach ( $sub_files as $sub_file ) {
						$result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
					}
				} else {
					$result[] = $value;
				}
			}
		}
	}
	return $result;
}

/**
 * colorshop_status_tools function.
 *
 * @access public
 * @return void
 */
function colorshop_status_tools() {
	global $colorshop, $wpdb;

	$tools = apply_filters( 'colorshop_debug_tools', array(
		'clear_transients' => array(
			'name'		=> __( 'CS Transients','colorshop'),
			'button'	=> __('Clear transients','colorshop'),
			'desc'		=> __( 'This tool will clear the product/shop transients cache.', 'colorshop' ),
		),
		'clear_expired_transients' => array(
			'name'		=> __( 'Expired Transients','colorshop'),
			'button'	=> __('Clear expired transients','colorshop'),
			'desc'		=> __( 'This tool will clear ALL expired transients from Wordpress.', 'colorshop' ),
		),
		'recount_terms' => array(
			'name'		=> __('Term counts','colorshop'),
			'button'	=> __('Recount terms','colorshop'),
			'desc'		=> __( 'This tool will recount product terms - useful when changing your settings in a way which hides products from the catalog.', 'colorshop' ),
		),
		'reset_roles' => array(
			'name'		=> __('Capabilities','colorshop'),
			'button'	=> __('Reset capabilities','colorshop'),
			'desc'		=> __( 'This tool will reset the admin, customer and shop_manager roles to default. Use this if your users cannot access all of the ColorShop admin pages.', 'colorshop' ),
		),
	) );

	if ( ! empty( $_GET['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'debug_action' ) ) {

		switch ( $_GET['action'] ) {
			case "clear_transients" :
				$colorshop->clear_product_transients();

				echo '<div class="updated"><p>' . __( 'Product Transients Cleared', 'colorshop' ) . '</p></div>';
			break;
			case "clear_expired_transients" :

				// http://w-shadow.com/blog/2012/04/17/delete-stale-transients/
				$rows = $wpdb->query( "
					DELETE
						a, b
					FROM
						{$wpdb->options} a, {$wpdb->options} b
					WHERE
						a.option_name LIKE '_transient_%' AND
						a.option_name NOT LIKE '_transient_timeout_%' AND
						b.option_name = CONCAT(
							'_transient_timeout_',
							SUBSTRING(
								a.option_name,
								CHAR_LENGTH('_transient_') + 1
							)
						)
						AND b.option_value < UNIX_TIMESTAMP()
				" );

				$rows2 = $wpdb->query( "
					DELETE
						a, b
					FROM
						{$wpdb->options} a, {$wpdb->options} b
					WHERE
						a.option_name LIKE '_site_transient_%' AND
						a.option_name NOT LIKE '_site_transient_timeout_%' AND
						b.option_name = CONCAT(
							'_site_transient_timeout_',
							SUBSTRING(
								a.option_name,
								CHAR_LENGTH('_site_transient_') + 1
							)
						)
						AND b.option_value < UNIX_TIMESTAMP()
				" );

				echo '<div class="updated"><p>' . sprintf( __( '%d Transients Rows Cleared', 'colorshop' ), $rows + $rows2 ) . '</p></div>';

			break;
			case "reset_roles" :
				// Remove then re-add caps and roles
				colorshop_remove_roles();
				colorshop_init_roles();

				echo '<div class="updated"><p>' . __( 'Roles successfully reset', 'colorshop' ) . '</p></div>';
			break;
			case "recount_terms" :

				$product_cats = get_terms( 'product_cat', array( 'hide_empty' => false, 'fields' => 'id=>parent' ) );

				_colorshop_term_recount( $product_cats, get_taxonomy( 'product_cat' ), false, false );

				$product_tags = get_terms( 'product_tag', array( 'hide_empty' => false, 'fields' => 'id=>parent' ) );

				_colorshop_term_recount( $product_cats, get_taxonomy( 'product_tag' ), false, false );

				echo '<div class="updated"><p>' . __( 'Terms successfully recounted', 'colorshop' ) . '</p></div>';
			break;
			default:
				$action = esc_attr( $_GET['action'] );
				if( isset( $tools[ $action ]['callback'] ) ) {
					$callback = $tools[ $action ]['callback'];
					$return = call_user_func( $callback );
					if( $return === false ) {
						if( is_array( $callback ) ) {
							echo '<div class="error"><p>' . sprintf( __( 'There was an error calling %s::%s', 'colorshop' ), get_class( $callback[0] ), $callback[1] ) . '</p></div>';

						} else {
							echo '<div class="error"><p>' . sprintf( __( 'There was an error calling %s', 'colorshop' ), $callback ) . '</p></div>';
						}
					}
				}
		}
	}

	?>
	<table class="cs_status_table widefat" cellspacing="0">

        <thead class="tools">
			<tr>
				<th colspan="2"><?php _e( 'Tools', 'colorshop' ); ?></th>
			</tr>
		</thead>

		<tbody class="tools">
		<?php foreach($tools as $action => $tool) { ?>
			<tr>
                <td><?php echo esc_html( $tool['name'] ); ?></td>
                <td>
                	<p>
                    	<a href="<?php echo wp_nonce_url( admin_url('admin.php?page=colorshop_status&tab=tools&action=' . $action ), 'debug_action' ); ?>" class="button"><?php echo esc_html( $tool['button'] ); ?></a>
                    	<span class="description"><?php echo wp_kses_post( $tool['desc'] ); ?></span>
                	</p>
                </td>
            </tr>
		<?php } ?>
		</tbody>
	</table>
	<?php
}