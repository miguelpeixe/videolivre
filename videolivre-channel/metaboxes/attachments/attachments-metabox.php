<?php

add_action('admin_footer', 'attachments_metabox_init');
add_action('add_meta_boxes', 'attachments_add_meta_box');
add_action('save_post', 'attachments_save_postdata');

function attachments_metabox_init() {
	wp_enqueue_style('general-metaboxes');
	wp_enqueue_script('custom-uploader');

	wp_enqueue_script('attachments-metabox', get_template_directory_uri() . '/metaboxes/attachments/attachments-metabox.js', array('jquery'));
}

function attachments_add_meta_box() {
	add_meta_box(
		'attachments_metabox',
		__('Video attachments', 'videolivre-channel'),
		'attachments_inner_meta_box',
		'video',
		'advanced',
		'high'
	);
}

add_filter("postbox_classes_video_attachments_metabox", create_function('', 'return array("general-box");'));

function attachments_inner_meta_box($post) {
	$video_attachments = get_post_meta($post->ID, 'video_attachments', true);
	?>
	<p class="description"><?php _e('Add attachments to your video, including PDF, ZIP, or any other file.', 'videolivre-channel'); ?></p>
	<div class="attachments_container">
		<div class="field file-item model">
			<div class="sub-item file_url">
				<div class="field-meta">
					<label><?php _e('File', 'videolivre-channel'); ?></label>
				</div>
				<div class="field-input">
					<input type="text" size="60" />
					<a class="button upload_file_button"><?php _e('Upload file', 'videolivre-channel'); ?></a>
					<p class="instruction"><?php _e('Enter file URL or upload new file', 'videolivre-channel'); ?></p>
				</div>
			</div>
			<div class="sub-item file_name">
				<div class="field-meta">
					<label><?php _e('File name', 'videolivre-channel'); ?></label>
				</div>
				<div class="field-input">
					<input type="text" size="60" />
				</div>
			</div>
			<div class="sub-item file_description">
				<div class="field-meta">
					<label><?php _e('File description', 'videolivre-channel'); ?></label>
				</div>
				<div class="field-input">
					<textarea cols="80" rows="8"></textarea>
				</div>
			</div>
			<p class="remove-item"><a href="#" class="remove-file remove-item"><?php _e('Remove file', 'videolivre-channel'); ?></a></p>
		</div>
		<?php if($video_attachments) {
			$i = 0;
			foreach($video_attachments as $attachment) { ?>
				<div class="field file-item">
					<div class="sub-item file_url">
						<div class="field-meta">
							<label><?php _e('File', 'videolivre-channel'); ?></label>
						</div>
						<div class="field-input">
							<input type="text" name="video_attachments[<?php echo $i; ?>][url]" size="60" value="<?php echo $attachment['url']; ?>" />
							<a class="button upload_file_button"><?php _e('Upload file', 'videolivre-channel'); ?></a>
							<p class="instruction"><?php _e('Enter file URL or upload new file', 'videolivre-channel'); ?></p>
						</div>
					</div>
					<div class="sub-item file_name">
						<div class="field-meta">
							<label><?php _e('File name', 'videolivre-channel'); ?></label>
						</div>
						<div class="field-input">
							<input type="text" name="video_attachments[<?php echo $i; ?>][name]" size="80" value="<?php echo $attachment['name']; ?>" />
						</div>
					</div>
					<div class="sub-item file_description">
						<div class="field-meta">
							<label><?php _e('File description', 'videolivre-channel'); ?></label>
						</div>
						<div class="field-input">
							<textarea name="video_attachments[<?php echo $i; ?>][description]" cols="80" rows="8"><?php echo $attachment['description']; ?></textarea>
						</div>
					</div>
					<p class="remove-item"><a href="#" class="remove-file remove-item"><?php _e('Remove file', 'videolivre-channel'); ?></a></p>
				</div>
				<?php
				$i++;
			}
		} ?>
	</div>
	<div class="field">
		<a class="button add-new-file"><?php _e('+ Add new file', 'videolivre-channel'); ?></a>
	</div>
	<div class="clearfix"></div>
	<?php
}

function attachments_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	if(isset($_POST['video_attachments'])) {
		$attachments = $_POST['video_attachments'];
		foreach($attachments as &$attachment) {
			$attachment['base'] = basename($attachment['url']);
		}
		update_post_meta($post_id, 'video_attachments', $attachments);
	}
}

?>