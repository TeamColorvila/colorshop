<?php

/**
 * Display single product reviews (comments)
 *
 * @author 		ColorVila
 * @package 	ColorShop/Templates
 * @version     1.0.0
 */
global $colorshop, $product;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<?php if ( comments_open() ) : ?><div id="reviews"><?php

	echo '<div id="comments">';

	if ( get_option('colorshop_enable_review_rating') == 'yes' ) {

		$count = $product->get_rating_count();

		if ( $count > 0 ) {

			$average = $product->get_average_rating();

			echo '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';

			echo '<div class="star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'colorshop' ), $average ).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'colorshop' ).'</span></div>';

			echo '<h2>'.sprintf( _n('%s review for %s', '%s reviews for %s', $count, 'colorshop'), '<span itemprop="ratingCount" class="count">'.$count.'</span>', wptexturize($post->post_title) ).'</h2>';

			echo '</div>';

		} else {

			echo '<h2>'.__( 'Reviews', 'colorshop' ).'</h2>';

		}

	} else {

		echo '<h2>'.__( 'Reviews', 'colorshop' ).'</h2>';

	}

	$title_reply = '';

	if ( have_comments() ) :

		echo '<ol class="commentlist">';

		wp_list_comments( array( 'callback' => 'colorshop_comments' ) );
		

		echo '</ol>';

		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Previous', 'colorshop' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Next <span class="meta-nav">&rarr;</span>', 'colorshop' ) ); ?></div>
			</div>
		<?php endif;

		echo '<p class="add_review"><a href="#review_form" class="inline show_review_form button" title="' . __( 'Add Your Review', 'colorshop' ) . '">' . __( 'Add Review', 'colorshop' ) . '</a></p>';

		$title_reply = __( 'Add a review', 'colorshop' );

	else :

		$title_reply = __( 'Be the first to review', 'colorshop' ).' &ldquo;'.$post->post_title.'&rdquo;';

		echo '<p class="noreviews">'.__( 'There are no reviews yet, would you like to <a href="#review_form" class="inline show_review_form">submit yours</a>?', 'colorshop' ).'</p>';

	endif;

	$commenter = wp_get_current_commenter();

	echo '</div><div id="review_form_wrapper"><div id="review_form">';

	$comment_form = array(
		'title_reply' => $title_reply,
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'fields' => array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'colorshop' ) . '</label> ' . '<span class="required">*</span>' .
			            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'colorshop' ) . '</label> ' . '<span class="required">*</span>' .
			            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
		),
		'label_submit' => __( 'Submit Review', 'colorshop' ),
		'logged_in_as' => '',
		'comment_field' => ''
	);

	if ( get_option('colorshop_enable_review_rating') == 'yes' ) {

		$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . __( 'Rating', 'colorshop' ) .'</label><select name="rating" id="rating">
			<option value="">'.__( 'Rate&hellip;', 'colorshop' ).'</option>
			<option value="5">'.__( 'Perfect', 'colorshop' ).'</option>
			<option value="4">'.__( 'Good', 'colorshop' ).'</option>
			<option value="3">'.__( 'Average', 'colorshop' ).'</option>
			<option value="2">'.__( 'Not that bad', 'colorshop' ).'</option>
			<option value="1">'.__( 'Very Poor', 'colorshop' ).'</option>
		</select></p>';

	}

	$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Your Review', 'colorshop' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>' . $colorshop->nonce_field('comment_rating', true, false);
	
	
	if ( get_option('colorshop_enable_comment_image') == 'yes' ) {
	
	$comment_form['comment_field'] .= '
		<h3 class="cs-img-title">Select an image for your comment (GIF, PNG, JPG, JPEG):</h3>
		<div class="cs-photolist">
			<ul>
				<li>
					<label>
						<input type="file" class="droparea spot" name="xfile" data-post="' . $colorshop->plugin_url() . '/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
					</label>
					<div class="clouse" style="display:none;"></div>
					<span class="left"></span>
					<span class="right"></span>
				</li>			
				<li style="display:none;">
					<label>
						<input type="file" class="droparea spot" name="xfile" data-post="' . $colorshop->plugin_url() . '/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
					</label>
					<div class="clouse" style="display:none;"></div>
					<span class="left"></span>
					<span class="right"></span>
				</li>
				<li style="display:none;">
					<label>
						<input type="file" class="droparea spot" name="xfile" data-post="' . $colorshop->plugin_url() . '/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
					</label>
					<div class="clouse" style="display:none;"></div>
					<span class="left"></span>
					<span class="right"></span>
				</li>
				<li style="display:none;">
					<label>
						<input type="file" class="droparea spot" name="xfile" data-post="' . $colorshop->plugin_url() . '/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
					</label>
					<div class="clouse" style="display:none;"></div>
					<span class="left"></span>
					<span class="right"></span>
				</li>
				<li style="display:none;">
					<label>
						<input type="file" class="droparea spot" name="xfile" data-post="' . $colorshop->plugin_url() . '/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
					</label>
					<div class="clouse" style="display:none;"></div>
					<span class="left"></span>
					<span class="right"></span>
				</li>
			</ul> 
			<input type="hidden" name="img1">
	        <input type="hidden" name="img2">
	        <input type="hidden" name="img3">
	        <input type="hidden" name="img4">
	        <input type="hidden" name="img5">
		</div><div style="clear:both;"></div>';
	}

	comment_form( apply_filters( 'colorshop_product_review_comment_form_args', $comment_form ) );

	echo '</div></div>';

?><div class="clear"></div></div>
<?php endif; ?>