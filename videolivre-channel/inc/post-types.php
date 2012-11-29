<?php

/* Post types */

add_action( 'init', 'register_cpt_program' );

function register_cpt_program() {

    $labels = array( 
        'name' => _x( 'Programs', 'videolivre-channel' ),
        'singular_name' => _x( 'Program', 'videolivre-channel' ),
        'add_new' => _x( 'Add new', 'videolivre-channel' ),
        'add_new_item' => _x( 'Add new program', 'videolivre-channel' ),
        'edit_item' => _x( 'Edit program', 'videolivre-channel' ),
        'new_item' => _x( 'New program', 'videolivre-channel' ),
        'view_item' => _x( 'View program', 'videolivre-channel' ),
        'search_items' => _x( 'Search programs', 'videolivre-channel' ),
        'not_found' => _x( 'No programs found', 'videolivre-channel' ),
        'not_found_in_trash' => _x( 'No programs found in trash', 'videolivre-channel' ),
        'parent_item_colon' => _x( 'Parent program:', 'videolivre-channel' ),
        'menu_name' => _x( 'Programs', 'videolivre-channel' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'author', 'trackbacks', 'comments' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'program', $args );
}