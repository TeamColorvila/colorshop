<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $colorshop;

$customer_orders = get_posts( array(
    'numberposts' => $order_count,
    'meta_key'    => '_customer_user',
    'meta_value'  => get_current_user_id(),
    'post_type'   => 'shop_order',
    'post_status' => 'publish'
) );

if ( $customer_orders ) : ?>

	<h2><?php echo apply_filters( 'colorshop_my_account_my_orders_title', __( 'Recent Orders', 'colorshop' ) ); ?></h2>
	
	<div id="cs-view-order">
		<table class="cs-view-order">
			<?php
			foreach ( $customer_orders as $customer_order ) :
				$order = new CS_Order();
	
				$order->populate( $customer_order );
	
				$status     = get_term_by( 'slug', $order->status, 'shop_order_status' );
				$item_count = $order->get_item_count();
	
				?>
			<tr>
				<td colspan="6" class="cs-header">
					<?php echo _e('Order:', 'colorshop'); ?>
					<a href="<?php echo esc_url( add_query_arg('order', $order->id, get_permalink( colorshop_get_page_id( 'view_order' ) ) ) ); ?>">
						<?php echo $order->get_order_number(); ?>
					</a>
					<?php echo _e('Date：', 'colorshop') . date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?>
				</td>
			</tr>	
			<?php
				$product_count = sizeof($order->get_items());
				$count = 0;
				foreach($order->get_items() as $item) :
				
				$_product = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
			?>
			<tr>
				<td><a href="<?php echo get_permalink( $item['product_id'] ); ?>"><?php echo $item['name']; ?> </a></td>
				<td><?php echo colorshop_price( $_product->get_price() ); ?></td>
				<td><?php echo $item['qty']; ?></td>
				<?php if ($count == 0) : ?>
				<td rowspan="<?php echo $product_count; ?>" class="middle"><?php echo $order->get_formatted_order_total(); ?></td>
				<td rowspan="<?php echo $product_count; ?>" class="middle">
					<span class="status"><?php echo ucfirst( __( $status->name, 'colorshop' ) ); ?></span>
					<br>
					<?php
						$actions = array();

						if ( in_array( $order->status, apply_filters( 'colorshop_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) )
							$actions['pay'] = array(
								'url'  => $order->get_checkout_payment_url(),
								'name' => __( 'Pay', 'colorshop' )
							);

						if ( in_array( $order->status, apply_filters( 'colorshop_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) )
							$actions['cancel'] = array(
								'url'  => $order->get_cancel_order_url(),
								'name' => __( 'Cancel', 'colorshop' )
							);

						$actions['view'] = array(
							'url'  => add_query_arg( 'order', $order->id, get_permalink( colorshop_get_page_id( 'view_order' ) ) ),
							'name' => __( 'View', 'colorshop' )
						);

						$actions = apply_filters( 'colorshop_my_account_my_orders_actions', $actions, $order );

						foreach( $actions as $key => $action ) {
							echo '<a href="' . esc_url( $action['url'] ) . '" class="' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
						}
					?>
				</td>
				<?php $count++; endif; ?>
				<td class="middle">
					<a href="<?php echo get_permalink( $item['product_id'] ) . '#comment-' . $item['product_id']; ?>"><?php _e('Comments', 'colorshop')?></a>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php endforeach; ?>
		</table>
		<table class="cs-view-order">
			
		</table>
	</div><!-- #cs-view-order -->

<?php endif; ?>