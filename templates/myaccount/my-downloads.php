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

if ( $downloads = $colorshop->customer->get_downloadable_products() ) : ?>

	<h2><?php echo apply_filters( 'colorshop_my_account_my_downloads_title', __( 'Available downloads', 'colorshop' ) ); ?></h2>

	<ul class="digital-downloads">
		<?php foreach ( $downloads as $download ) : ?>
			<li>
				<?php
					do_action( 'colorshop_available_download_start', $download );

					if ( is_numeric( $download['downloads_remaining'] ) )
						echo apply_filters( 'colorshop_available_download_count', '<span class="count">' . sprintf( _n( '%s download remaining', '%s downloads remaining', $download['downloads_remaining'], 'colorshop' ), $download['downloads_remaining'] ) . '</span> ', $download );

					echo apply_filters( 'colorshop_available_download_link', '<a href="' . esc_url( $download['download_url'] ) . '">' . $download['download_name'] . '</a>', $download );

					do_action( 'colorshop_available_download_end', $download );
				?>
			</li>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>