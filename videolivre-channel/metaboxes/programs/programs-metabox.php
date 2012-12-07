<?php

add_action('admin_footer', 'programs_metabox_init');
add_action('add_meta_boxes', 'programs_add_meta_box');
add_action('save_post', 'programs_save_postdata');

function programs_metabox_init() {
	wp_enqueue_style('general-metaboxes');
}

function programs_add_meta_box() {
	add_meta_box(
		'programs_metabox',
		__('Programs', 'videolivre-channel'),
		'programs_inner_meta_box',
		'video',
		'advanced',
		'high'
	);
}

add_filter("postbox_classes_video_programs_metabox", create_function('', 'return array("general-box");'));

function programs_inner_meta_box($post) {
	/*
	$production = get_post_meta($post->ID, 'production', true);
	?>
	<div class="field">
		<div class="field-meta">
			<label for="production"><?php _e('Production', 'videolivre-channel'); ?></label>
		</div>
		<div class="field-input">
			<textarea id="production" name="production" cols="80" rows="8"><?php echo $production; ?></textarea>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php
	*/
}

function programs_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	update_post_meta($post_id, 'production', $_POST['production']);
}

?>