<?php
/*
Plugin Name: Eventica Testimonials
Plugin URI: http://toko.press
Description: Testimonial Plugins For Eventica WordPress Theme
Version: 1.0
Author: TokoPress
Author URI: http://toko.press/
License: GPL
*/

/**
 * Register Post Type Testimonial
 */
add_action( 'init', 'register_tokopress_testimonial' );
function register_tokopress_testimonial() {

    $labels = array( 
        'name' 					=> _x( 'Testimonial', 'testimonial', 'tokopress' ),
        'singular_name' 		=> _x( 'Testimonial', 'testimonial', 'tokopress' ),
        'add_new' 				=> _x( 'Add New', 'testimonial', 'tokopress' ),
        'add_new_item' 			=> _x( 'Add New Testimonial', 'testimonial', 'tokopress' ),
        'edit_item' 			=> _x( 'Edit Testimonial', 'testimonial', 'tokopress' ),
        'new_item' 				=> _x( 'New Testimonial', 'testimonial', 'tokopress' ),
        'view_item' 			=> _x( 'View Testimonial', 'testimonial', 'tokopress' ),
        'search_items' 			=> _x( 'Search Testimonial', 'testimonial', 'tokopress' ),
        'not_found' 			=> _x( 'No testimonial found', 'testimonial', 'tokopress' ),
        'not_found_in_trash' 	=> _x( 'No testimonial found in Trash', 'testimonial', 'tokopress' ),
        'parent_item_colon' 	=> _x( 'Parent Testimonial:', 'testimonial', 'tokopress' ),
        'menu_name' 			=> _x( 'Testimonial', 'testimonial', 'tokopress' ),
    );

    $args = array( 
        'labels' 				=> $labels,
        'hierarchical' 			=> true,
        
        'supports' 				=> array( 'title', 'editor', 'author' ),
        
        'public' 				=> true,
        'show_ui' 				=> true,
        'show_in_menu' 			=> true,
        'menu_position'	 		=> 20,
        'menu_icon' 			=> 'dashicons-groups',
        'show_in_nav_menus' 	=> true,
        'publicly_queryable' 	=> true,
        'exclude_from_search' 	=> false,
        'has_archive' 			=> false,
        'query_var' 			=> true,
        'can_export' 			=> true,
        'rewrite' 				=> true,
        'capability_type' 		=> 'post'
    );

    register_post_type( 'testimonial', $args );
}