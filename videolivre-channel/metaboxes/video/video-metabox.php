<?php

add_action('admin_footer', 'video_metabox_init');
add_action('add_meta_boxes', 'video_add_meta_box');
add_action('save_post', 'video_save_postdata');

function video_metabox_init() {
	wp_enqueue_script('google-jsapi', 'http://www.google.com/jsapi');
	wp_enqueue_script('video-metabox', get_template_directory_uri() . '/metaboxes/video/video-metabox.js', array('jquery', 'google-jsapi'), '0.0.1');
	wp_localize_script('video-metabox', 'video_metabox_messages', array(
		'empty_url' => __('You must enter a valid URL', 'videolivre-channel'),
		'placeholders' => array(
			'subtitle_url' => __('Subtitle url', 'videolivre-channel'),
			'subtitle_lang' => __('Language', 'videolivre-channel')
		)
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
	$video_html5_mp4 = get_post_meta($post->ID, 'video_html5_mp4', true);
	$video_html5_webm = get_post_meta($post->ID, 'video_html5_webm', true);
	$video_html5_ogv = get_post_meta($post->ID, 'video_html5_ogv', true);
	?>
	<div id="video-metabox">
		<input type="hidden" name="video_srv" value="<?php echo $video_srv; ?>" />
		<input type="hidden" name="video_src" value="<?php echo $video_src; ?>" />
		<div class="supported-formats">
			<span><?php _e('Supported formats', 'videolivre-channel'); ?></span>
			<ul>
				<li class="youtube" title="YouTube">YouTube</li>
				<li class="vimeo" title="Vimeo">Vimeo</li>
				<li class="html5" title="HTML5">HTML5</li>
			</ul>
		</div>
		<h4><?php _e('Paste a video URL and click "Load video" or press enter to load the video', 'videolivre-channel'); ?></h4>
		<p><input type="text" size="80" id="video_url" name="video_url" placeholder="<?php _e('Video URL', 'videolivre-channel'); ?>" value="<?php echo $video_url; ?>" /></p>
	    <div class="html5-extras">
	    	<p>
		    	<input type="text" size="80" class="html5_mp4" name="html5_mp4" placeholder="<?php echo _e('MP4 fallback', 'videolivre-channel'); ?>" value="<?php echo $video_html5_mp4; ?>" />
		    	<input type="text" size="80" class="html5_ogv" name="html5_ogv" placeholder="<?php echo _e('OGV fallback', 'videolivre-channel'); ?>" value="<?php echo $video_html5_ogv; ?>" />
		    	<input type="text" size="80" class="html5_webm" name="html5_webm" placeholder="<?php echo _e('WebM fallback', 'videolivre-channel'); ?>" value="<?php echo $video_html5_webm; ?>" />
		    </p>
		    <div class="subtitle-tracks">
		    	<a href="#" class="add-subtitle"><?php _e('+ Add subtitle track (.srt)', 'videolivre-channel'); ?></a>
		    	<ul class="subtitle-list">
		    	</ul>
		    </div>
	    </div>
	    <p><input type="button" class="locate-video" value="<?php _e('Load video', 'videolivre-channel'); ?>" /></p>
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

	if($_POST['video_srv'] == 'html5') {
		update_post_meta($post_id, 'video_html5_mp4', $_POST['html5_mp4']);
		update_post_meta($post_id, 'video_html5_webm', $_POST['html5_webm']);
		update_post_meta($post_id, 'video_html5_ogv', $_POST['html5_ogv']);
	}
}

?>