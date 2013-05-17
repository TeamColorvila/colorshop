<?php
/**
 * Welcome Page Class
 *
 * Shows a feature overview for the new version (major) and credits.
 *
 *
 * @author 		ColorVila
 * @category 	Admin
 * @package 	ColorShop/Admin
 * @version     1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CS_Welcome_Page class.
 *
 * @since 1.0
 */
class CS_Welcome_Page {

	private $plugin;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->plugin = 'colorshop/colorshop.php';

		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Add admin menus/screens
	 *
	 * @access public
	 * @return void
	 */
	public function admin_menus() {

		$welcome_page_title = __( 'Welcome to ColorShop', 'colorshop' );

		// About
		$about = add_dashboard_page( $welcome_page_title, $welcome_page_title, 'manage_options', 'cs-about', array( $this, 'about_screen' ) );

		// Credits
		$credits = add_dashboard_page( $welcome_page_title, $welcome_page_title, 'manage_options', 'cs-credits', array( $this, 'credits_screen' ) );

		add_action( 'admin_print_styles-'. $about, array( $this, 'admin_css' ) );
		add_action( 'admin_print_styles-'. $credits, array( $this, 'admin_css' ) );
	}

	/**
	 * admin_css function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_css() {
		wp_enqueue_style( 'colorshop-activation', plugins_url(  '/assets/css/activation.css', dirname( dirname( __FILE__ ) ) ) );
	}

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_head() {
		global $colorshop;

		remove_submenu_page( 'index.php', 'cs-about' );
		remove_submenu_page( 'index.php', 'cs-credits' );

		// Badge for welcome page
		$badge_url = $colorshop->plugin_url() . '/assets/images/welcome/cs-badge.png';
		?>
		<style type="text/css">
			/*<![CDATA[*/
			.cs-badge {
				padding-top: 150px;
				height: 52px;
				width: 185px;
				color: #9c5d90;
				font-weight: bold;
				font-size: 14px;
				text-align: center;
				text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
				margin: 0 -5px;
				background: url('<?php echo $colorshop->plugin_url() . '/assets/images/welcome/cs-welcome.png'; ?>') no-repeat center center;
			}

			@media
			(-webkit-min-device-pixel-ratio: 2),
			(min-resolution: 192dpi) {
				.cs-badge {
					background-image:url('<?php echo $colorshop->plugin_url() . '/assets/images/welcome/cs-welcome@2x.png'; ?>');
					background-size: 173px 194px;
				}
			}

			.about-wrap .cs-badge {
				position: absolute;
				top: 0;
				right: 0;
			}
			/*]]>*/
		</style>
		<?php
	}

	/**
	 * Into text/links shown on all about pages.
	 *
	 * @access private
	 * @return void
	 */
	private function intro() {
		global $colorshop;

		// Flush after upgrades
		if ( ! empty( $_GET['cs-updated'] ) || ! empty( $_GET['cs-installed'] ) )
			flush_rewrite_rules();

		// Drop minor version if 0
		$major_version = substr( $colorshop->version, 0, 3 );
		?>
		<h1><?php printf( __( 'Welcome to ColorShop %s', 'colorshop' ), $major_version ); ?></h1>

		<div class="about-text colorshop-about-text">
			<?php
				if ( ! empty( $_GET['cs-installed'] ) )
					$message = __( 'Thanks, all done!', 'colorshop' );
				elseif ( ! empty( $_GET['cs-updated'] ) )
					$message = __( 'Thank you for updating to the latest version!', 'colorshop' );
				else
					$message = __( 'Thanks for installing!', 'colorshop' );

				printf( __( '%s ColorShop is a powerful eCommerce plugin with amazing attributes filtering and sorting system, multiple addresses and product photographs reviews. We hope you enjoy it.', 'colorshop' ), $message);
			?>
		</div>

		<div class="cs-badge"></div>

		<p class="colorshop-actions">
			<a href="<?php echo admin_url('admin.php?page=colorshop_settings'); ?>" class="button button-primary"><?php _e( 'Settings', 'colorshop' ); ?></a>
			<a class="docs button button-primary" href="http://colorvila.com/docs"><?php _e( 'Docs', 'colorshop' ); ?></a>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://colorvila.com/colorshop/" data-text="A open-source (free) #ecommerce plugin for #WordPress that helps you sell anything. Beautifully." data-via="ColorVila" data-size="large" data-hashtags="ColorShop">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</p>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( $_GET['page'] == 'cs-about' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'cs-about' ), 'index.php' ) ) ); ?>">
				<?php _e( "What's New", 'colorshop' ); ?>
			</a>
		</h2>
		<?php
	}

	/**
	 * Output the about screen.
	 *
	 * @access public
	 * @return void
	 */
	public function about_screen() {
		global $colorshop;
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<!--<div class="changelog point-releases"></div>-->

			<div class="changelog">

				<h3><?php _e( 'Powerful Ajax Filtering & Sorting System', 'colorshop' ); ?></h3>

				<div class="feature-section images-stagger-right">
					<img src="<?php echo $colorshop->plugin_url() . '/assets/images/welcome/filtering-sorting.png'; ?>" alt="filtering and sorting" style="padding: 1em" />
					<h4><?php _e( 'Ajax Multi-select Filtering System', 'colorshop' ); ?></h4>
					<p><?php _e( 'Ajax multi-select filtering system on Shop and Category pages. ', 'colorshop' ); ?></p>
					<h4><?php _e( 'Ajax Sorting Options', 'colorshop' ); ?></h4>
					<p><?php _e( 'Improved Sorting Options, customers now can sort products by popularity, ratings, newness and price.', 'colorshop' ); ?></p>
					
				</div>				
				
				<h3><?php _e( 'Multiple Adresses', 'colorshop' ); ?></h3>

				<div class="feature-section images-stagger-right">
					<img src="<?php echo $colorshop->plugin_url() . '/assets/images/welcome/multiple-addresses.png'; ?>" alt="Multiple Adresses" style="padding: 1em" />
					<p><?php _e( 'ColorShop support Multiple Addresses, the user can freely switch between them. You never need to remove any of your addresses now.', 'colorshop' ); ?></p>
				</div>
				
				<h3><?php _e( 'Improved Order View', 'colorshop' ); ?></h3>

				<div class="feature-section images-stagger-right">
					<img src="<?php echo $colorshop->plugin_url() . '/assets/images/welcome/view-orders.png'; ?>" alt="Improved Order View" style="padding: 1em" />					
					<p><?php _e( 'Improved order view, make every orders and product information be clear at a glance.', 'colorshop' ); ?></p>
				</div>
				
				<h3><?php _e( 'Sharing your Product Photographs', 'colorshop' ); ?></h3>
				<div class="feature-section images-stagger-right">
					<img src="<?php echo $colorshop->plugin_url() . '/assets/images/welcome/share-photos.png'; ?>" alt="Sharing your Product Photographs" style="padding: 1em" />					
					<p><?php _e( 'ColorShop add Product Photographs for product review. Users can share their products with others.', 'colorshop' ); ?></p>
				</div>
				
				<h3><?php _e( 'New Order Status', 'colorshop' ); ?></h3>
				<div class="feature-section images-stagger-right">
					<img src="<?php echo $colorshop->plugin_url() . '/assets/images/welcome/order-status.png'; ?>" alt="New Order Status" style="padding: 1em" />
				 	<p><?php _e( 'Graphical order status is more intuitive and friendly.', 'colorshop' ); ?></p>
				</div>			

			</div>

			<div class="changelog">
				
			</div>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'colorshop_settings' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to ColorShop Settings', 'colorshop' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Sends user to the welcome page on first activation
	 */
	public function welcome() {

		// Bail if no activation redirect transient is set
	    if ( ! get_transient( '_cs_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_cs_activation_redirect' );

		// Bail if we are waiting to install or update via the interface update/install links
		if ( get_option( '_cs_needs_update' ) == 1 || get_option( '_cs_needs_pages' ) == 1 )
			return;

		// Bail if activating from network, or bulk, or within an iFrame
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || defined( 'IFRAME_REQUEST' ) )
			return;

		if ( ( isset( $_GET['action'] ) && 'upgrade-plugin' == $_GET['action'] ) && ( isset( $_GET['plugin'] ) && strstr( $_GET['plugin'], 'colorshop.php' ) ) )
			return;

		wp_safe_redirect( admin_url( 'index.php?page=cs-about' ) );
		exit;
	}
}

new CS_Welcome_Page();