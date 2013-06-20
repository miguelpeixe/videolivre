<?php

/* Post types */

add_action('init', 'register_cpt_video');

function register_cpt_video() {

    $labels = array( 
        'name' => __('Videos', 'videolivre-channel'),
        'singular_name' => __('Video', 'videolivre-channel'),
        'add_new' => __('Add new', 'videolivre-channel'),
        'add_new_item' => __('Add new video', 'videolivre-channel'),
        'edit_item' => __('Edit video', 'videolivre-channel'),
        'new_item' => __('New video', 'videolivre-channel'),
        'view_item' => __('View video', 'videolivre-channel'),
        'search_items' => __('Search videos', 'videolivre-channel'),
        'not_found' => __('No videos found', 'videolivre-channel'),
        'not_found_in_trash' => __('No videos found in trash', 'videolivre-channel'),
        'parent_item_colon' => __('Parent video:', 'videolivre-channel'),
        'menu_name' => __('Videos', 'videolivre-channel'),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array('title', 'editor', 'author', 'trackbacks', 'comments'),
        'taxonomies' => array('post_tag'),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 2,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type('video', $args);
}

add_action('init', 'register_cpt_program');

function register_cpt_program() {

    $labels = array( 
        'name' => __('Programs', 'videolivre-channel'),
        'singular_name' => __('Program', 'videolivre-channel'),
        'add_new' => __('Add new', 'videolivre-channel'),
        'add_new_item' => __('Add new program', 'videolivre-channel'),
        'edit_item' => __('Edit program', 'videolivre-channel'),
        'new_item' => __('New program', 'videolivre-channel'),
        'view_item' => __('View program', 'videolivre-channel'),
        'search_items' => __('Search programs', 'videolivre-channel'),
        'not_found' => __('No programs found', 'videolivre-channel'),
        'not_found_in_trash' => __('No programs found in trash', 'videolivre-channel'),
        'parent_item_colon' => __('Parent program:', 'videolivre-channel'),
        'menu_name' => __('Programs', 'videolivre-channel'),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'author', 'trackbacks', 'comments' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type('program', $args);
}

// custom menu position for program

function program_menu() {
    add_submenu_page('edit.php?post_type=video', __('Programs', 'videolivre-channel'), __('Programs', 'videolivre-channel'), 'edit_posts', 'edit.php?post_type=program');
    add_submenu_page('edit.php?post_type=video', __('Add new program', 'videolivre-channel'), __('Add new program', 'videolivre-channel'), 'edit_posts', 'post-new.php?post_type=program');
}

add_action('admin_menu', 'program_menu');