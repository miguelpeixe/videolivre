<?php

/* Include metaboxes */

// register general metabox files
add_action('admin_footer', 'metaboxes_init');

function metaboxes_init() {
	wp_register_style('general-metaboxes', get_template_directory_uri() . '/metaboxes/metaboxes.css', array(), '1.0');
	wp_register_script('custom-uploader', get_template_directory_uri() . '/js/custom-uploader.js', array('jquery','media-upload','thickbox'));
}

// video metabox
include(TEMPLATEPATH . '/metaboxes/video/video-metabox.php');
include(TEMPLATEPATH . '/metaboxes/video-metadata/video-metadata-metabox.php');
include(TEMPLATEPATH . '/metaboxes/program-metadata/program-metadata-metabox.php');