<?php

/*
 * Video Livre
 * Video attachments
 */

class VL_Attachments extends VL_Video {

	function __construct() {
		add_action('vl_video_init', array($this, 'init'));
	}

	function init() {

		$this->box_setup();

	}

	function box_setup() {
		add_filter('postbox_classes_' . parent::$post_type . '_attachments_metabox', create_function('', 'return array("general-box");'));
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_post'));
	}

	function add_meta_boxes() {
		add_meta_box(
			'attachments_metabox',
			__('Video attachments', 'videolivre-channel'),
			array($this, 'box'),
			parent::$post_type,
			'advanced',
			'high'
		);
	}

	function box($post) {

		wp_enqueue_style('general-metaboxes', parent::uri() . '/css/metaboxes.css');
		wp_enqueue_script('custom-uploader', parent::uri() . '/js/custom-uploader.js');
		wp_enqueue_script('attachments-metabox', parent::uri() . '/js/attachments-metabox.js', array('jquery'));

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

	function save_post($post_id) {
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

	function get_attachments($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		$attachments = get_post_meta($post_id, 'video_attachments', true);
		return apply_filters('vl_video_attachments', $attachments);
	}

}

$GLOBALS['vl_attachments'] = new VL_Attachments();

function vl_get_the_attachments($post_id = false) {
	return $GLOBALS['vl_attachments']->get_attachments($post_id);
}