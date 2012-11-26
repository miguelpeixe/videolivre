<?php

function change_default_theme($blog_id) {
	switch_to_blog($blog_id);
	switch_theme('videolivre-channel', 'videolivre-channel');
	restore_current_blog();
}
add_action('wpmu_new_blog', 'change_default_theme', 100, 1);