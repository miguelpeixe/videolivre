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
		__('Program', 'videolivre-channel'),
		'programs_inner_meta_box',
		'video',
		'side',
		'default'
	);
}

add_filter("postbox_classes_video_programs_metabox", create_function('', 'return array("general-box");'));

function programs_inner_meta_box($post) {
	$programs = get_posts(array('post_type' => 'program', 'posts_per_page' => -1));
	$video_program = get_post_meta($post->ID, 'program', true);
	?>
	<p class="description">
		<?php _e('Select program to associate your video.', 'videolivre-channel'); ?><br/>
	</p>
	<div class="field relationship">
		<?php if($programs) : ?>

			<ul class="programs relation-list">
				<?php foreach($programs as $program) : ?>
					<li>
						<input id="program_<?php echo $program->ID; ?>" type="radio" name="video_program" value="<?php echo $program->ID; ?>" <?php if($program->ID == $video_program) echo 'checked'; ?> />
						<label for="program_<?php echo $program->ID; ?>"><?php echo $program->post_title; ?></label>
					</li>
				<?php endforeach; ?>
			</ul>

		<?php else : ?>

			<p><?php _e('No programs were found.', 'videolivre-channel'); ?></p>

		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
	<p class="description">
		<?php echo sprintf(__('<a href="%s" target="_blank" title="New program">Click here</a> to create a new program', 'videolivre-channel'), get_admin_url('', 'post-new.php?post_type=program')); ?>
	</p>
	<?php
}

function programs_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	update_post_meta($post_id, 'program', $_POST['video_program']);
}

?>