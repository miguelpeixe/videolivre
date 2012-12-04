<?php

add_action('admin_footer', 'video_metadata_metabox_init');
add_action('add_meta_boxes', 'video_metadata_add_meta_box');
add_action('save_post', 'video_metadata_save_postdata');

function video_metadata_metabox_init() {
	wp_enqueue_style('metaboxes');
}

function video_metadata_add_meta_box() {
	add_meta_box(
		'video_metadata_metabox',
		__('Video information', 'videolivre-channel'),
		'video_metadata_inner_meta_box',
		'post',
		'advanced',
		'high'
	);

}

add_filter("postbox_classes_post_video_metadata_metabox", create_function('', 'return array("general-box");'));

function video_metadata_inner_meta_box($post) {
	$year = get_post_meta($post->ID, 'year', true);
	$direction = get_post_meta($post->ID, 'direction', true);
	$casting = get_post_meta($post->ID, 'casting', true);
	$team = get_post_meta($post->ID, 'team', true);
	?>
	<div id="video_metadata" class="general-metabox">
		<div class="field">
			<div class="field-meta">
				<label for="production_year"><?php _e('Year of production', 'videolivre-channel'); ?></label>
				<p class="instruction"><?php _e('E.g.: 2008', 'videolivre-channel'); ?></p>
			</div>
			<div class="field-input">
				<input id="production_year" type="text" size="4" name="production_year" value="<?php echo $year; ?>" />
			</div>
		</div>
		<div class="field">
			<div class="field-meta">
				<label for="direction"><?php _e('Direction', 'videolivre-channel'); ?></label>
				<p class="instruction"><?php _e('E.g.: John Doe', 'videolivre-channel'); ?></p>
			</div>
			<div class="field-input">
				<input id="direction" type="text" size="30" name="direction" value="<?php echo $direction; ?>" />
			</div>
		</div>
		<div class="field">
			<div class="field-meta">
				<label for="casting"><?php _e('Casting', 'videolivre-channel'); ?></label>
			</div>
			<div class="field-input">
				<textarea id="casting" type="text" cols="80" rows="8" name="casting"><?php echo $casting; ?></textarea>
			</div>
		</div>
		<div class="field">
			<div class="field-meta">
				<label for="production_team"><?php _e('Production team', 'videolivre-channel'); ?></label>
			</div>
			<div class="field-input">
				<textarea id="production_team" type="text" cols="80" rows="8" name="team"><?php echo $team; ?></textarea>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php
}

function video_metadata_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	update_post_meta($post_id, 'year', $_POST['production_year']);
	update_post_meta($post_id, 'direction', $_POST['direction']);
	update_post_meta($post_id, 'casting', $_POST['casting']);
	update_post_meta($post_id, 'team', $_POST['team']);
}

?>