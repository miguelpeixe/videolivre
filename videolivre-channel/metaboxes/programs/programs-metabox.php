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
		'side',
		'default'
	);
}

add_filter("postbox_classes_video_programs_metabox", create_function('', 'return array("general-box");'));

function programs_inner_meta_box($post) {
	$programs = get_posts(array('post_type' => 'program', 'posts_per_page' => -1));
	$video_programs = get_post_meta($post->ID, 'programs', true);
	?>
	<p class="description">
		<?php _e('Click the available programs to associate with your video.', 'videolivre-channel'); ?><br/>
		<?php echo sprintf(__('<a href="%s" target="_blank" title="New program">Click here</a> to create a new program', 'videolivre-channel'), get_admin_url('', 'post-new.php?post_type=program')); ?>
	</p>
	<div class="field relationship">
		<?php if($programs) : ?>

			<ul class="programs relation-list">
				<?php foreach($programs as $program) : ?>
					<li>
						<input id="program_<?php echo $program->ID; ?>" type="checkbox" name="video_programs[]" value="<?php echo $program->ID; ?>" <?php if(in_array($program->ID, $video_programs)) echo 'checked'; ?> />
						<label for="program_<?php echo $program->ID; ?>"><?php echo $program->post_title; ?></label>
					</li>
				<?php endforeach; ?>
			</ul>

		<?php else : ?>

			<p><?php echo sprintf(__('No programs were found. <a href="%s" target="_blank" title="New program">Click here</a> to create your first and start associating videos!', 'videolivre-channel'), get_admin_url('','post-new.php?post_type=program')); ?></p>

		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
	<?php
}

function programs_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	update_post_meta($post_id, 'programs', $_POST['video_programs']);
}

?>