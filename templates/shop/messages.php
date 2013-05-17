<?php
/**
 * Show messages
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
?>

<?php foreach ( $messages as $message ) : ?>
	<div class="colorshop-message"><?php echo wp_kses_post( $message ); ?></div>
<?php endforeach; ?>
