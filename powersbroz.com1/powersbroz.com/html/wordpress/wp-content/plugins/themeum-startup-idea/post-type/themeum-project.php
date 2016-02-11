<?php
/*--------------------------------------------------------------
 *			Register Project Post Type
 *-------------------------------------------------------------*/

function themeum_post_type_project()
{
	$labels = array( 
			'name'                	=> _x( 'Project', 'Project', 'themeum-startup-idea' ),
			'singular_name'       	=> _x( 'Project', 'Project', 'themeum-startup-idea' ),
			'menu_name'           	=> __( 'Project', 'themeum-startup-idea' ),
			'parent_item_colon'   	=> __( 'Parent Project:', 'themeum-startup-idea' ),
			'all_items'           	=> __( 'All Project', 'themeum-startup-idea' ),
			'view_item'           	=> __( 'View Project', 'themeum-startup-idea' ),
			'add_new_item'        	=> __( 'Add Project Title', 'themeum-startup-idea' ),
			'add_new'             	=> __( 'New Project', 'themeum-startup-idea' ),
			'edit_item'           	=> __( 'Edit Project', 'themeum-startup-idea' ),
			'update_item'         	=> __( 'Update Project', 'themeum-startup-idea' ),
			'search_items'        	=> __( 'Search Project', 'themeum-startup-idea' ),
			'not_found'           	=> __( 'No article found', 'themeum-startup-idea' ),
			'not_found_in_trash'  	=> __( 'No article found in Trash', 'themeum-startup-idea' )
		);

	$args = array(  
			'labels'             	=> $labels,
			'public'             	=> true,
			'publicly_queryable' 	=> true,
			'show_in_menu'       	=> true,
			'show_in_admin_bar'   	=> true,
			'can_export'          	=> true,
			'has_archive'        	=> false,
			'hierarchical'       	=> false,
			'menu_position'      	=> null,
			'supports'           	=> array( 'title','editor','thumbnail','comments')
		);

	register_post_type('project',$args);

}

add_action('init','themeum_post_type_project');


/*--------------------------------------------------------------
 *			View Message When Updated Project
 *-------------------------------------------------------------*/

function themeum_update_message_project()
{
	global $post, $post_ID;

	$message['project'] = array(
					0 	=> '',
					1 	=> sprintf( __('Project updated. <a href="%s">View Project</a>', 'themeum-startup-idea' ), esc_url( get_permalink($post_ID) ) ),
					2 	=> __('Custom field updated.', 'themeum-startup-idea' ),
					3 	=> __('Custom field deleted.', 'themeum-startup-idea' ),
					4 	=> __('Project updated.', 'themeum-startup-idea' ),
					5 	=> isset($_GET['revision']) ? sprintf( __('Project restored to revision from %s', 'themeum-startup-idea' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					6 	=> sprintf( __('Project published. <a href="%s">View Project</a>', 'themeum-startup-idea' ), esc_url( get_permalink($post_ID) ) ),
					7 	=> __('Project saved.', 'themeum-startup-idea' ),
					8 	=> sprintf( __('Project submitted. <a target="_blank" href="%s">Preview portfolio</a>', 'themeum-startup-idea' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
					9 	=> sprintf( __('Project scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Project</a>', 'themeum-startup-idea' ), date_i18n( __( 'M j, Y @ G:i','themeum-startup-idea'), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
					10 	=> sprintf( __('Project draft updated. <a target="_blank" href="%s">Preview Project</a>', 'themeum-startup-idea' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

return $message;
}

add_filter( 'post_updated_messages', 'themeum_update_message_project' );


/*--------------------------------------------------------------
 *			Register Custom Taxonomies(Category)
 *-------------------------------------------------------------*/

function themeum_create_project_taxonomy()
{
	$labels = array(	'name'              => _x( 'Project Categories', 'taxonomy general name','themeum-startup-idea'),
						'singular_name'     => _x( 'Project Category', 'taxonomy singular name','themeum-startup-idea' ),
						'search_items'      => __( 'Search Project Category','themeum-startup-idea'),
						'all_items'         => __( 'All Project Category','themeum-startup-idea'),
						'parent_item'       => __( 'Parent Project Category','themeum-startup-idea'),
						'parent_item_colon' => __( 'Parent Project Category:','themeum-startup-idea'),
						'edit_item'         => __( 'Edit Project Category','themeum-startup-idea'),
						'update_item'       => __( 'Update Project Category','themeum-startup-idea'),
						'add_new_item'      => __( 'Add New Project Category','themeum-startup-idea'),
						'new_item_name'     => __( 'New Project Category Name','themeum-startup-idea'),
						'menu_name'         => __( 'Project Category','themeum-startup-idea')
		);

	$args = array(	'hierarchical'      => true,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
		);

	register_taxonomy('project_category',array( 'project' ),$args);

}

add_action('init','themeum_create_project_taxonomy');



/*--------------------------------------------------------------
 *			Register Custom Taxonomies(Tag)
 *-------------------------------------------------------------*/

//create two taxonomies, genres and tags for the post type "tag"
function create_tag_project_taxonomies() 
{
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
			    'name' => _x( 'Project Tags', 'taxonomy general name','themeum-startup-idea' ),
			    'singular_name' => _x( 'Project Tag', 'taxonomy singular name','themeum-startup-idea' ),
			    'search_items' =>  __( 'Search Project Tags','themeum-startup-idea' ),
			    'popular_items' => __( 'Popular Project Tags','themeum-startup-idea' ),
			    'all_items' => __( 'All Project Tags','themeum-startup-idea' ),
			    'parent_item' => null,
			    'parent_item_colon' => null,
			    'edit_item' => __( 'Edit Project Tag','themeum-startup-idea' ), 
			    'update_item' => __( 'Update Project Tag','themeum-startup-idea' ),
			    'add_new_item' => __( 'Add New Project Tag','themeum-startup-idea' ),
			    'new_item_name' => __( 'New Project Tag Name','themeum-startup-idea' ),
			    'separate_items_with_commas' => __( 'Separate Project tags with commas','themeum-startup-idea' ),
			    'add_or_remove_items' => __( 'Add or remove Project tags','themeum-startup-idea' ),
			    'choose_from_most_used' => __( 'Choose from the most used Project tags','themeum-startup-idea' ),
			    'menu_name' => __( 'Project Tags','themeum-startup-idea' ),
			  ); 

  register_taxonomy('project_tag','project',array(
											    'hierarchical' => false,
											    'labels' => $labels,
											    'show_ui' => true,
											    'update_count_callback' => '_update_post_term_count',
											    'query_var' => true,
											    'rewrite' => array( 'slug' => 'tag' ),
											  ));
}
add_action( 'init', 'create_tag_project_taxonomies', 0 );