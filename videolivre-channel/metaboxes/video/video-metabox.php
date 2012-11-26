<?php

add_action('admin_footer', 'video_metabox_init');
add_action('add_meta_boxes', 'video_add_meta_box');
add_action('save_post', 'video_save_postdata');

function video_metabox_init() {
	wp_enqueue_script('google-jsapi', 'http://www.google.com/jsapi');
	wp_enqueue_script('video-metabox', get_template_directory_uri() . '/metaboxes/video/video-metabox.js', array('jquery', 'google-jsapi'), '0.0.1');
	wp_localize_script('video-metabox', 'video_metabox_messages', array(
		'empty_url' => __('You must enter a valid URL', 'videolivre-channel')
	));
	wp_enqueue_style('video-metabox', get_template_directory_uri() . '/metaboxes/video/video-metabox.css');
}

function video_add_meta_box() {
	add_meta_box(
		'video-metabox',
		__('Add video', 'videolivre-channel'),
		'video_inner_meta_box',
		'post',
		'advanced',
		'high'
	);
}

function video_inner_meta_box($post) {
	$video_src = get_post_meta($post->ID, 'video_src', true);
	$video_url = get_post_meta($post->ID, 'video_url', true);
	$video_srv = get_post_meta($post->ID, 'video_srv', true);
	?>
	<div id="video-metabox">
		<input type="hidden" name="video_srv" value="<?php echo $video_srv; ?>" />
		<input type="hidden" name="video_src" value="<?php echo $video_src; ?>" />
		<h4><?php _e('Paste a video URL and click "Load video" or press enter to load the video', 'videolivre-channel'); ?></h4>
		<div class="supported-videos"></div>
		<p>
		    <input type="text" size="80" id="video_url" name="video_url" placeholder="<?php _e('Video URL', 'videolivre-channel'); ?>" value="<?php echo $video_url; ?>" />
		    <p class="html5-fallbacks">
		    	<input type="text" size="80" class="html5_mp4" placeholder="<?php echo _e('MP4 fallback', 'videolivre-channel'); ?>" value="<?php echo $html5_mp4; ?>" />
		    	<input type="text" size="80" class="html5_ogv" placeholder="<?php echo _e('OGV fallback', 'videolivre-channel'); ?>" value="<?php echo $html5_webm; ?>" />
		    	<input type="text" size="80" class="html5_webm" placeholder="<?php echo _e('WebM fallback', 'videolivre-channel'); ?>" value="<?php echo $html5_webm; ?>" />
		    </p>
		    <input type="button" class="locate-video" value="<?php _e('Load video', 'videolivre-channel'); ?>" />
	    </p>
	    <div class="video-container">
	    	<div id="apiplayer"></div>
	    </div>
	</div>
	<?php
}

function video_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	update_post_meta($post_id, 'video_src', $_POST['video_src']);
	update_post_meta($post_id, 'video_url', $_POST['video_url']);
	update_post_meta($post_id, 'video_srv', $_POST['video_srv']);
}

?>