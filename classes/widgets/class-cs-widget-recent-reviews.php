<?php
/**
 * Recent Reviews Widget
 *
 * @author 		ColorVila
 * @category 	Widgets
 * @package 	ColorShop/Widgets
 * @version 	1.0.0
 * @extends 	WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CS_Widget_Recent_Reviews extends WP_Widget {

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
	function CS_Widget_Recent_Reviews() {

		/* Widget variable settings. */
		$this->color_widget_cssclass = 'colorshop widget_recent_reviews';
		$this->color_widget_description = __( 'Display a list of your most recent reviews on your site.', 'colorshop' );
		$this->color_widget_idbase = 'colorshop_recent_reviews';
		$this->color_widget_name = __( 'ColorShop Recent Reviews', 'colorshop' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->color_widget_cssclass, 'description' => $this->color_widget_description );

		/* Create the widget. */
		$this->WP_Widget('recent_reviews', $this->color_widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
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
		global $comments, $comment, $colorshop;

		$cache = wp_cache_get('widget_recent_reviews', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		ob_start();
		extract($args);

 		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Reviews', 'colorshop' ) : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) ) $number = 5;

		$comments = get_comments( array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish', 'post_type' => 'product' ) );

		if ( $comments ) {
			echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title;
			echo '<ul class="product_list_widget">';

			foreach ( (array) $comments as $comment) {

				$_product = get_product( $comment->comment_post_ID );

				$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

				$rating_html = $_product->get_rating_html();

				echo '<li><a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">';

				echo $_product->get_image();

				echo $_product->get_title().'</a>';

				echo $rating_html;

				printf( _x( 'by %1$s', 'by comment author', 'colorshop' ), get_comment_author() ) . '</li>';

			}

			echo '</ul>';
			echo $after_widget;
		}

		$content = ob_get_clean();

		if ( isset( $args['widget_id'] ) ) $cache[$args['widget_id']] = $content;

		echo $content;

		wp_cache_set('widget_recent_reviews', $cache, 'widget');
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
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_reviews']) ) delete_option('widget_recent_reviews');

		return $instance;
	}

	/**
	 * flush_widget_cache function.
	 *
	 * @access public
	 * @return void
	 */
	function flush_widget_cache() {
		wp_cache_delete('widget_recent_reviews', 'widget');
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
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] ) $number = 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'colorshop' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of products to show:', 'colorshop' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
<?php
	}
}