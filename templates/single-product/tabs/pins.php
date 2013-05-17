<?php 
	global $colorshop, $post;
	
// 	require_once 'f:/workspace/wordpress3/FirePHPCore/fb.php';
// 	ob_start();
	
	//FB::log($post);
	
	//$pins = get_post_meta($post->ID, '_product_pin');
	//FB::log($pins, '$pins');
	
// 	foreach ($test as $key => $value) {
// 		if (substr($key, 0, 3) == 'img') {
// 			$pin_images[] = $value; 
// 		}
// 	}
	
// 	FB::log($pin_images, '$pin_images');
	
	$args = array(
			'post_id' => $post->ID,
			'type' => 'product_pin'
	);
	
	$pins = get_comments($args);
	//FB::log($pins, '$pins');
	
?>

<div id="cs-pins" class="clearfix">

	<ul class="clearfix">
	<?php
	
	function get_big_image_path($filename) {
		$index = strlen($filename) - strpos(strrev($filename), '?');
		$filename = substr($filename, 0, $index - 1);
		$path_parts = pathinfo($filename);
		return str_replace($path_parts['filename'], $path_parts['filename'] . '_o', $filename);
	}
	
	foreach ($pins as $pin) :
		$content = json_decode($pin->comment_content);
		$pin_images = array();
		foreach ($content as $key => $value) {
			if (substr($key, 0, 3) == 'img') {
				$pin_images[] = $value;
			}
		}
	?>
		<li>
			<div class="cs-pins-img">
				<?php
					$count = 0; 
					foreach ($pin_images as $image) :
						if (empty($image))
							continue;
				?>
				<a href="<?php echo get_big_image_path($pin_images[$count]); ?>" rel="prettyPhoto[pp_gal_<?php echo $pin->comment_author; ?>]" alt="<?php echo $content->title; ?>">
					<?php if ($count == 0) : ?>
						<img src="<?php echo $pin_images[$count]; ?>" />
					<?php endif; $count++; ?>
				</a>
				<?php endforeach; ?>
			</div>			
			<div class="cs-pins-title"><?php echo $content->title; ?></div>
			<div class="cs-pins-author">
				<span>From&nbsp;:&nbsp;</span><?php echo $pin->comment_author; ?> <?php
						if ( get_option('colorshop_product_pin_verification_label') == 'yes' )
							if ( colorshop_customer_bought_product( $pin->comment_author_email, $pin->user_id, $post->ID ) )
								echo '<em class="verified">(' . __( 'verified owner', 'colorshop' ) . ')</em> ';
					?>
			</div>
			<div class="cs-pins-content"><?php echo $content->comment; ?></div>			
		</li>
	<?php endforeach; ?>
	</ul>
	<?php
		$option_text = colorshop_check_publish_pin($post->ID) ? 'Edit' : 'Add';
		$current_user = wp_get_current_user();
		$display = colorshop_customer_bought_product( $current_user->user_email, $current_user->ID, $post->ID ) ? 'block' : 'none';
	?>
	<p class="cs-add_pin" style="display:<?php echo $display; ?>"><a title="<?php echo $option_text; ?> Your Pin" class="inline show_pin_form button" href="#pin_form"><?php echo $option_text; ?> Pin</a></p>

</div>
<?php
	$user_id = get_current_user_id();
	
	//$the_comment = get_comment( $user_id = 7 );
	
	$args = array(
			'post_id' => $post->ID,
			'user_id' => $user_id,
			'type' => 'product_pin'
	);
	
	$my_comments = get_comments($args);
	
	//FB::log($my_comments, '$my_comments');
	
	if (count($my_comments) > 0) {
		$my_comment = $my_comments[0];
		$my_comment_arr = get_object_vars($my_comment);
		$my_comment_content = $my_comment_arr['comment_content'];
		$my_comment_json = json_decode($my_comment_content);
		
		$pin_images = array();
		foreach ($my_comment_json as $key => $value) {
			if (substr($key, 0, 3) == 'img') {
				$pin_images[] = $value;
			}
		}
		//FB::log($my_comment_json, '$my_comment_json');
		
		
		$pin_images = array_pad($pin_images, 5, '');

		//FB::log($pin_images, '$pin_images');
				
		
		
		//FB::log($my_comment_json, '$my_comment_json');
		
			
		//FB::log($my_comment_arr, '$my_comment_arr');
			
		//$my_comment_arr['comment_content'] = json_encode($data);
		//wp_update_comment($my_comment_arr);
	} 
?>
<div id="pin_form_wrapper" style="display: none;">
<div id="pin_form">
	<form id="cs-pin-form" method="post" action="wp-content/plugins/colorshop/templates/post-pin.php">
		<div class="cs-upload-picture">
	    	<ul>
	        	<li class="cs-first">
					<label>晒单标题<span>*</span></label>		
					<input type="text" name="title" value="<?php echo $my_comment_json->title;?>"/>
					<span>已输入0字，还可输入20字</span>
				</li>
				<li>
					<label>上传图片<span>*</span></label>	
					<span></span><!-- class="btn_upload_holder" -->	
	<!-- 				<img id="btn_upload" src="http://star.vancl.com.cn/star/sites/seller/addsuit/liulbtn.gif" height="22" width="93"> -->
	<!-- 				<input type="file" name="" value="上传晒单图" class="button" /> -->
					<span>最多上传5张图片，可拖动图片改变显示顺序，第一张为晒单主图</span>
				</li>
				<li class="cs-photolist clearfix">
					<ul>
					<?php
						if (count($my_comments) > 0) {
							$first = true;
							$count = 1;
							foreach ($pin_images as $value) {
								//FB::log($first, '$first');
								//FB::log($value, '$value');
								$img_value = empty($value) ? '' : $value;
								//FB::log($img_value, '$img_value');
								$display = (! empty($value) || empty($value) && $first == true) ? 'block' : 'none';
								$display_operation = (! empty($value)) ? 'block' : 'none';
					?>
					<li style="display:<?php echo $display; ?>;">
						<label>
							<input type="file" class="droparea spot" name="xfile" data-post="wp-content/plugins/colorshop/templates/upload.php" data-width="69" data-height="92" data-crop="true" data-value="<?php echo $img_value; ?>"/>
						</label>
						<div class="clouse" style="display:<?php echo $display_operation; ?>"></div>
						<span class="left" style="display:<?php echo $display_operation; ?>"></span>
						<span class="right" style="display:<?php echo $display_operation; ?>"></span>
					</li>
					<?php
						$count++;
						if (empty($value))
							$first = false; 
						} } else { ?>
						<li>
							<label>
								<input type="file" class="droparea spot" name="xfile" data-post="wp-content/plugins/colorshop/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
							</label>
							<div class="clouse" style="display:none;"></div>
							<span class="left"></span>
							<span class="right"></span>
						</li>			
						<li style="display:none;">
							<label>
								<input type="file" class="droparea spot" name="xfile" data-post="wp-content/plugins/colorshop/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
							</label>
							<div class="clouse" style="display:none;"></div>
							<span class="left"></span>
							<span class="right"></span>
						</li>
						<li style="display:none;">
							<label>
								<input type="file" class="droparea spot" name="xfile" data-post="wp-content/plugins/colorshop/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
							</label>
							<div class="clouse" style="display:none;"></div>
							<span class="left"></span>
							<span class="right"></span>
						</li>
						<li style="display:none;">
							<label>
								<input type="file" class="droparea spot" name="xfile" data-post="wp-content/plugins/colorshop/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
							</label>
							<div class="clouse" style="display:none;"></div>
							<span class="left"></span>
							<span class="right"></span>
						</li>
						<li style="display:none;">
							<label>
								<input type="file" class="droparea spot" name="xfile" data-post="wp-content/plugins/colorshop/templates/upload.php" data-width="69" data-height="92" data-crop="true"/>
							</label>
							<div class="clouse" style="display:none;"></div>
							<span class="left"></span>
							<span class="right"></span>
						</li>
					<?php } ?>
					</ul>
				</li>
				<li>
					<label>Comments<span>*</span></label>
					<textarea aria-required="true" name="comment" class="cs-comment"><?php echo $my_comment_json->comment;?></textarea>
				</li>
				<li>
	<!-- 			<button type="button" class="cs-Publish button">Publish</button> -->
	<!-- 				<input type="submit" value="Submit Review" id="submit" name="submit"> -->
					<input type="submit" class="cs-Publish button" value=Publish>
				</li>
	        </ul>
	        <input type="hidden" name="img1" value="<?php echo $pin_images[0]?>">
	        <input type="hidden" name="img2" value="<?php echo $pin_images[1]?>">
	        <input type="hidden" name="img3" value="<?php echo $pin_images[2]?>">
	        <input type="hidden" name="img4" value="<?php echo $pin_images[3]?>">
	        <input type="hidden" name="img5" value="<?php echo $pin_images[4]?>">
	        <input type="hidden" name="post-id" value="<?php echo $post->ID; ?>">
	        
	   </div>
   </form>
</div>


</div>

<script>
            // Calling jQuery "droparea" plugin
            jQuery(document).ready(function(){

				

	            

	         	//jQuery('.cs-Publish').click(function(){
		         	//jQuery('<div>ddddd</div>').dialog();
		         	//jQuery( "#dialog" ).dialog();
	         	//});
            });
       </script>