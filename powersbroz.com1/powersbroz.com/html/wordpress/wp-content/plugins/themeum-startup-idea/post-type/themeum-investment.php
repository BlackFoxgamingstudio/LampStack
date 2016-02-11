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

function themeum_startup_idea_post_type_investment()
{
	$labels = array( 
		'name'                	=> _x( 'Investments', 'Investments', 'themeum-startup-idea' ),
		'singular_name'       	=> _x( 'Investment', 'Investment', 'themeum-startup-idea' ),
		'menu_name'           	=> __( 'Investments', 'themeum-startup-idea' ),
		'parent_item_colon'   	=> __( 'Parent Investment:', 'themeum-startup-idea' ),
		'all_items'           	=> __( 'All Investment', 'themeum-startup-idea' ),
		'view_item'           	=> __( 'View Investment', 'themeum-startup-idea' ),
		'add_new_item'        	=> __( 'Add New Investment', 'themeum-startup-idea' ),
		'add_new'             	=> __( 'New Investment', 'themeum-startup-idea' ),
		'edit_item'           	=> __( 'Edit Investment', 'themeum-startup-idea' ),
		'update_item'         	=> __( 'Update Investment', 'themeum-startup-idea' ),
		'search_items'        	=> __( 'Search Investment', 'themeum-startup-idea' ),
		'not_found'           	=> __( 'No article found', 'themeum-startup-idea' ),
		'not_found_in_trash'  	=> __( 'No article found in Trash', 'themeum-startup-idea' )
		);

	$args = array(  
		'labels'             	=> $labels,
		'public'             	=> true,
		'publicly_queryable' 	=> true,
		'show_in_menu'       	=> 'edit.php?post_type=investment',
		'show_in_admin_bar'   	=> true,
		'can_export'          	=> true,
		'has_archive'        	=> false,
		'hierarchical'       	=> false,
		'menu_position'      	=> null,
		'menu_icon'				=> true,
		'supports'           	=> array('comments')
		);

	register_post_type('investment',$args);

}

add_action('init','themeum_startup_idea_post_type_investment');


/**
 * View Message When Updated Project
 *
 * @param array $messages Existing post update messages.
 * @return array
 */

function themeum_startup_idea_update_message_investment( $messages ){
	
	global $post, $post_ID;

	$message['investment'] = array(
		0 => '',
		1 => sprintf( __('Investment updated. <a href="%s">View Investment</a>', 'themeum-startup-idea' ), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'themeum-startup-idea' ),
		3 => __('Custom field deleted.', 'themeum-startup-idea' ),
		4 => __('Investment updated.', 'themeum-startup-idea' ),
		5 => isset($_GET['revision']) ? sprintf( __('Investment restored to revision from %s', 'themeum-startup-idea' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Investment published. <a href="%s">View Investment</a>', 'themeum-startup-idea' ), esc_url( get_permalink($post_ID) ) ),
		7 => __('Investment saved.', 'themeum-startup-idea' ),
		8 => sprintf( __('Investment submitted. <a target="_blank" href="%s">Preview Investment</a>', 'themeum-startup-idea' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Investment scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Investment</a>', 'themeum-startup-idea' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Investment draft updated. <a target="_blank" href="%s">Preview Investment</a>', 'themeum-startup-idea' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

return $message;
}
add_filter( 'post_updated_messages', 'themeum_startup_idea_update_message_investment' );