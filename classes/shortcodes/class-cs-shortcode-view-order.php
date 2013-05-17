<?php
/**
 * View_Order Shortcode
 *
 * @author 		ColorVila
 * @category 	Shortcodes
 * @package 	ColorShop/Shortcodes/View_Order
 * @version     1.0.0
 */

class CS_Shortcode_View_Order {

	/**
	 * Get the shortcode content.
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	public static function get( $atts ) {
		global $colorshop;
		return $colorshop->shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $atts ) {
		global $colorshop;

		$colorshop->nocache();

		if ( ! is_user_logged_in() ) return;

		extract( shortcode_atts( array(
	    	'order_count' => 10
		), $atts ) );

		$user_id      	= get_current_user_id();
		$order_id		= ( isset( $_GET['order'] ) ) ? $_GET['order'] : 0;
		$order 			= new CS_Order( $order_id );

		if ( $order_id == 0 ) {
			colorshop_get_template( 'myaccount/my-orders.php', array( 'order_count' => 'all' == $order_count ? -1 : $order_count ) );
			return;
		}

		if ( $order->user_id != $user_id ) {
			echo '<div class="colorshop-error">' . __( 'Invalid order.', 'colorshop' ) . ' <a href="'.get_permalink( colorshop_get_page_id('myaccount') ).'">'. __( 'My Account &rarr;', 'colorshop' ) .'</a>' . '</div>';
			return;
		}

		$status = get_term_by('slug', $order->status, 'shop_order_status');
		
		echo '<p class="order-info">'
		. sprintf( __( 'Order <mark class="order-number">%s</mark> made on <mark class="order-date">%s</mark>', 'colorshop'), $order->get_order_number(), date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ) )
		. '. ' . sprintf( __( 'Order status: <mark class="order-status">%s</mark>', 'colorshop' ), __( $status->name, 'colorshop' ) )
		. '.</p>';
		
		?>
		<div id="cs-view-order" class="clearfix">
		<div class="cs-order-status clearfix">
		<div class="cs-order-diagrammatize">
			<ul class="clearfix">
					<?php
					$address1 = '';
					$address2 = '';
					$address3 = '';
					$address4 = '';
					$address5 = '';
					$address6 = '';
					if ($status->name == 'on-hold') {
						$address1 = $colorshop->plugin_url() . '/assets/images/round.png';
						$address2 =  $colorshop->plugin_url() . '/assets/images/arrows-left-black.png';
						$address3 =  $colorshop->plugin_url() . '/assets/images/arrows-center-black.png';
						$address4 =  $colorshop->plugin_url() . '/assets/images/arrows-right-black.png';
						$address5 =  $colorshop->plugin_url() . '/assets/images/round-black.png';
						$address6 =  $colorshop->plugin_url() . '/assets/images/round-black.png';
						$address7 =  $colorshop->plugin_url() . '/assets/images/arrows-left-black.png';
						$address8 =  $colorshop->plugin_url() . '/assets/images/arrows-center-black.png';
						$address9 =  $colorshop->plugin_url() . '/assets/images/arrows-right-black.png';
					} elseif ($status->name == 'processing') {
						$address1 =  $colorshop->plugin_url() . '/assets/images/round.png';
						$address2 =  $colorshop->plugin_url() . '/assets/images/arrows-left.png';
						$address3 =  $colorshop->plugin_url() . '/assets/images/arrows-center.png';
						$address4 =  $colorshop->plugin_url() . '/assets/images/arrows-right.png';
						$address5 =  $colorshop->plugin_url() . '/assets/images/round.png';
						$address6 =  $colorshop->plugin_url() . '/assets/images/round-black.png';
						$address7 =  $colorshop->plugin_url() . '/assets/images/arrows-left-black.png';
						$address8 =  $colorshop->plugin_url() . '/assets/images/arrows-center-black.png';
						$address9 =  $colorshop->plugin_url() . '/assets/images/arrows-right-black.png';
					} elseif ($status->name == 'completed') {
						$address1 =  $colorshop->plugin_url() . '/assets/images/round.png';
						$address2 =  $colorshop->plugin_url() . '/assets/images/arrows-left.png';
						$address3 =  $colorshop->plugin_url() . '/assets/images/arrows-center.png';
						$address4 =  $colorshop->plugin_url() . '/assets/images/arrows-right.png';
						$address5 =  $colorshop->plugin_url() . '/assets/images/round.png';
						$address6 =  $colorshop->plugin_url() . '/assets/images/round.png';
						$address7 =  $colorshop->plugin_url() . '/assets/images/arrows-left.png';
						$address8 =  $colorshop->plugin_url() . '/assets/images/arrows-center.png';
						$address9 =  $colorshop->plugin_url() . '/assets/images/arrows-right.png';
					}
					?>
			<li class="first"><img src="<?php echo $address1; ?>" alt="" title="" /></li>
			
			<li><img src="<?php echo $address2; ?>" alt="" title="" /></li>						
			<li><img src="<?php echo $address3; ?>" alt="" title="" /></li>						
			<li class="right"><img src="<?php echo $address4; ?>" alt="" title="" /></li>	
			
			<li><img src="<?php echo $address2; ?>" alt="" title="" /></li>						
			<li><img src="<?php echo $address3; ?>" alt="" title="" /></li>						
			<li class="right"><img src="<?php echo $address4; ?>" alt="" title="" /></li>
			
			<li><img src="<?php echo $address2; ?>" alt="" title="" /></li>						
			<li><img src="<?php echo $address3; ?>" alt="" title="" /></li>						
			<li class="right"><img src="<?php echo $address4; ?>" alt="" title="" /></li>			
			
			<li class="feature"><img src="<?php echo $address5; ?>" alt="" title="" /></li>
			
			<li><img src="<?php echo $address7; ?>" alt="" title="" /></li>						
			<li><img src="<?php echo $address8; ?>" alt="" title="" /></li>						
			<li class="right"><img src="<?php echo $address9; ?>" alt="" title="" /></li>	
			
			<li><img src="<?php echo $address7; ?>" alt="" title="" /></li>						
			<li><img src="<?php echo $address8; ?>" alt="" title="" /></li>						
			<li class="right"><img src="<?php echo $address9; ?>" alt="" title="" /></li>	
			
			<li><img src="<?php echo $address7; ?>" alt="" title="" /></li>						
			<li><img src="<?php echo $address8; ?>" alt="" title="" /></li>						
			<li class="right"><img src="<?php echo $address9; ?>" alt="" title="" /></li>				
			
			<li class="last"><img src="<?php echo $address6; ?>" alt="" title="" /></li>
		</ul>
				</div><!-- .cs-order-diagrammatize -->
				
				<div class="cs-status-text clearfix">
					<p class="cs-status1">
						<span>on-hold</span>
					</p>
					<p class="cs-status2">
						<span>processing</span>
					</p>
					<p class="cs-status3">
						<span>completed</span>
					</p>
				</div><!-- .cs-order-text -->
		
			</div><!-- .cs-order-status -->
		</div>
		<?php
		
		$notes = $order->get_customer_order_notes();
		if ($notes) :
			?>
			<h2><?php _e( 'Order Updates', 'colorshop' ); ?></h2>
			<ol class="commentlist notes">
				<?php foreach ($notes as $note) : ?>
				<li class="comment note">
					<div class="comment_container">
						<div class="comment-text">
							<p class="meta"><?php echo date_i18n(__( 'l jS \of F Y, h:ia', 'colorshop' ), strtotime($note->comment_date)); ?></p>
							<div class="description">
								<?php echo wpautop(wptexturize($note->comment_content)); ?>
							</div>
			  				<div class="clear"></div>
			  			</div>
						<div class="clear"></div>
					</div>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php
		endif;

		do_action( 'colorshop_view_order', $order_id );
	}
}