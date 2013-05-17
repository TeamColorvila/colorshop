<?php
/**
 * Shopping Cart Widget
 *
 * Displays shopping cart widget
 *
 * @author 		ColorVila
 * @category 	Widgets
 * @package 	ColorShop/Widgets
 * @version 	1.0.0
 * @extends 	WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CS_Widget_Cart extends WP_Widget {

	var $color_widget_cssclass;
	var $color_widget_description;
	var $color_widget_idbase;
	var $color_widget_name;

	/**
	 * constructor
	 *
	 * @access public
	 * @return void
	 */
	function CS_Widget_Cart() {

		/* Widget variable settings. */
		$this->color_widget_cssclass 		= 'colorshop widget_shopping_cart';
		$this->color_widget_description 	= __( "Display the user's Cart in the sidebar.", 'colorshop' );
		$this->color_widget_idbase 		= 'colorshop_widget_cart';
		$this->color_widget_name 			= __( 'ColorShop Cart', 'colorshop' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->color_widget_cssclass, 'description' => $this->color_widget_description );

		/* Create the widget. */
		$this->WP_Widget( 'shopping_cart', $this->color_widget_name, $widget_ops );
	}


	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		global $colorshop;

		extract( $args );

		if ( is_cart() || is_checkout() ) return;

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Cart', 'colorshop' ) : $instance['title'], $instance, $this->id_base );
		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		if ( $hide_if_empty )
			echo '<div class="hide_cart_widget_if_empty">';

		// Insert cart widget placeholder - code in colorshop.js will update this on page load
		echo '<div class="widget_shopping_cart_content"></div>';

		if ( $hide_if_empty )
			echo '</div>';

		echo $after_widget;

		if ( $hide_if_empty && sizeof( $colorshop->cart->get_cart() ) == 0 ) {
			$colorshop->add_inline_js( "
				jQuery('.hide_cart_widget_if_empty').closest('.widget_shopping_cart').hide();

				jQuery('body').bind('adding_to_cart', function(){
					jQuery('.hide_cart_widget_if_empty').closest('.widget_shopping_cart').fadeIn();
				});
			" );
		}
	}


	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['hide_if_empty'] = empty( $new_instance['hide_if_empty'] ) ? 0 : 1;
		return $instance;
	}


	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @access public
	 * @param array $instance
	 * @return void
	 */
	function form( $instance ) {
		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'colorshop' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hide_if_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_if_empty') ); ?>"<?php checked( $hide_if_empty ); ?> />
		<label for="<?php echo $this->get_field_id('hide_if_empty'); ?>"><?php _e( 'Hide if cart is empty', 'colorshop' ); ?></label></p>
		<?php
	}

}