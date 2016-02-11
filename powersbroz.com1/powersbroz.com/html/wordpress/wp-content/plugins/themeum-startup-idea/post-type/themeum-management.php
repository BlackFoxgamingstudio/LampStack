<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Admin functions for the Event post type
 *
 * @author 		Themeum
 * @category 	Admin
 * @package 	startupidea
 * @version     1.0
 *-------------------------------------------------------------*/

/**
 * Register post type Investment
 *
 * @return void
 */

function themeum_startup_idea_post_type_management()
{
	$labels = array( 
		'name'                	=> _x( 'Management', 'Management', 'themeum-startup-idea' ),
		'singular_name'       	=> _x( 'Management', 'Management', 'themeum-startup-idea' ),
		'menu_name'           	=> __( 'Management', 'themeum-startup-idea' ),
		'parent_item_colon'   	=> __( 'Parent Management:', 'themeum-startup-idea' ),
		'all_items'           	=> __( 'All Management', 'themeum-startup-idea' ),
		'view_item'           	=> __( 'View Management', 'themeum-startup-idea' ),
		'add_new_item'        	=> __( 'Add New Management', 'themeum-startup-idea' ),
		'add_new'             	=> __( 'New Management', 'themeum-startup-idea' ),
		'edit_item'           	=> __( 'Edit Management', 'themeum-startup-idea' ),
		'update_item'         	=> __( 'Update Management', 'themeum-startup-idea' ),
		'search_items'        	=> __( 'Search Management', 'themeum-startup-idea' ),
		'not_found'           	=> __( 'No article found', 'themeum-startup-idea' ),
		'not_found_in_trash'  	=> __( 'No article found in Trash', 'themeum-startup-idea' )
		);

	$args = array(  
		'labels'             	=> $labels,
		'public'             	=> true,
		'publicly_queryable' 	=> true,
		/* 'show_in_menu'       	=> 'edit.php?post_type=lmsorder', */
		'show_in_admin_bar'   	=> true,
		'can_export'          	=> true,
		'has_archive'        	=> false,
		'hierarchical'       	=> false,
		'menu_position'      	=> null,
		'menu_icon'				=> true,
		'supports'           	=> array('comments')
		);

	register_post_type('management',$args);

}

add_action('init','themeum_startup_idea_post_type_management');


/**
 * View Message When Updated Project
 *
 * @param array $messages Existing post update messages.
 * @return array
 */

function themeum_startup_idea_update_message_management( $messages ){
	
	global $post, $post_ID;

	$message['management'] = array(
		0 => '',
		1 => sprintf( __('Management updated. <a href="%s">View Management</a>', 'themeum-startup-idea' ), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'themeum-startup-idea' ),
		3 => __('Custom field deleted.', 'themeum-startup-idea' ),
		4 => __('Management updated.', 'themeum-startup-idea' ),
		5 => isset($_GET['revision']) ? sprintf( __('Management restored to revision from %s', 'themeum-startup-idea' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Management published. <a href="%s">View Management</a>', 'themeum-startup-idea' ), esc_url( get_permalink($post_ID) ) ),
		7 => __('Management saved.', 'themeum-startup-idea' ),
		8 => sprintf( __('Management submitted. <a target="_blank" href="%s">Preview Management</a>', 'themeum-startup-idea' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Management scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Management</a>', 'themeum-startup-idea' ), date_i18n( __( 'M j, Y @ G:i','themeum-startup-idea' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Management draft updated. <a target="_blank" href="%s">Preview Management</a>', 'themeum-startup-idea' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

return $message;
}
add_filter( 'post_updated_messages', 'themeum_startup_idea_update_message_management' );