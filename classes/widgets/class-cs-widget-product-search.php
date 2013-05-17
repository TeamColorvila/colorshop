<?php
/**
 * Product Search Widget
 *
 * @author 		ColorVila
 * @category 	Widgets
 * @package 	ColorShop/Widgets
 * @version 	1.0.0
 * @extends 	WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CS_Widget_Product_Search extends WP_Widget {

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
	function CS_Widget_Product_Search() {

		/* Widget variable settings. */
		$this->color_widget_cssclass = 'colorshop widget_product_search';
		$this->color_widget_description = __( 'A Search box for products only.', 'colorshop' );
		$this->color_widget_idbase = 'colorshop_product_search';
		$this->color_widget_name = __( 'ColorShop Product Search', 'colorshop' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->color_widget_cssclass, 'description' => $this->color_widget_description );

		/* Create the widget. */
		$this->WP_Widget('product_search', $this->color_widget_name, $widget_ops);
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
		extract($args);

		$title = $instance['title'];
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;

		if ($title) echo $before_title . $title . $after_title;

		get_product_search_form();

		echo $after_widget;
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
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
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
		global $wpdb;
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'colorshop' ) ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
		<?php
	}
}