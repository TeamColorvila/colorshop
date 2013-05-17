<?php

/**
 * Product Factory Class
 *
 * The ColorShop product factory creating the right product object
 *
 * @class 		CS_Product_Factory
 * @version		1.0.0
 * @package		ColorShop/Classes
 * @category	Class
 * @author 		ColorVila
 */
class CS_Product_Factory {

	/**
	 * get_product function.
	 *
	 * @access public
	 * @param bool $the_product (default: false)
	 * @param array $args (default: array())
	 * @return CS_Product_Simple
	 */
	public function get_product( $the_product = false, $args = array() ) {
		global $post;

		if ( false === $the_product ) {
			$the_product = $post;
		} elseif ( is_numeric( $the_product ) ) {
			$the_product = get_post( $the_product );
		}

		if ( ! $the_product )
			return false;

		$product_id = absint( $the_product->ID );
		$post_type  = $the_product->post_type;

		if ( in_array( $post_type, array( 'product', 'product_variation' ) ) ) {
			if ( isset( $args['product_type'] ) ) {
				$product_type = $args['product_type'];
			} elseif ( 'product_variation' == $post_type ) {
				$product_type = 'variation';
			} else {
				$terms        = get_the_terms( $product_id, 'product_type' );
				$product_type = ! empty( $terms ) && isset( current( $terms )->name ) ? sanitize_title( current( $terms )->name ) : 'simple';
			}

			// Create a CS coding standards compliant class name e.g. CS_Product_Type_Class instead of CS_Product_type-class
			$classname = 'CS_Product_' . preg_replace( '/-(.)/e', "'_' . strtoupper( '$1' )", ucfirst( $product_type ) );
		} else {
			$classname = false;
			$product_type = false;
		}

		// Filter classname so that the class can be overridden if extended.
		$classname = apply_filters( 'colorshop_product_class', $classname, $product_type, $post_type, $product_id );

		if ( ! class_exists( $classname ) )
			$classname = 'CS_Product_Simple';

		return new $classname( $the_product, $args );
	}
}