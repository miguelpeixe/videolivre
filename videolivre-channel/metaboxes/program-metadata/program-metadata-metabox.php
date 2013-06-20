<?php

add_action('admin_footer', 'program_metadata_metabox_init');
add_action('add_meta_boxes', 'program_metadata_add_meta_box');
add_action('save_post', 'program_metadata_save_postdata');

function program_metadata_metabox_init() {
	wp_enqueue_style('general-metaboxes');
	wp_enqueue_script('iris');
}

function program_metadata_add_meta_box() {
	add_meta_box(
		'program_metadata_metabox',
		__('Program information', 'videolivre-channel'),
		'program_metadata_inner_meta_box',
		'program',
		'advanced',
		'high'
	);
}

add_filter("postbox_classes_program_program_metadata_metabox", create_function('', 'return array("general-box");'));

function program_metadata_inner_meta_box($post) {
	$color = get_post_meta($post->ID, 'program_color', true);
	if(!$color)
		$color = '#aa5555';
	$production = get_post_meta($post->ID, 'production', true);
	?>
	<div class="field">
		<div class="field-meta">
			<label for="program_color"><?php _e('Program color', 'videolivre-channel'); ?></label>
		</div>
		<div class="field-input">
			<input type="text" id="program_color" name="program_color" class="color-picker" value="<?php echo $color; ?>" />
		</div>
	</div>
	<div class="field">
		<div class="field-meta">
			<label for="production"><?php _e('Production', 'videolivre-channel'); ?></label>
		</div>
		<div class="field-input">
			<textarea id="production" name="production" cols="80" rows="8"><?php echo $production; ?></textarea>
		</div>
	</div>
	<div class="clearfix"></div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#program_color').iris({
				hide: false
			});
		});
	</script>
	<?php
}

function program_metadata_save_postdata($post_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	if (false !== wp_is_post_revision($post_id))
		return;

	update_post_meta($post_id, 'production', $_POST['production']);
	update_post_meta($post_id, 'program_color', $_POST['program_color']);
}

?>