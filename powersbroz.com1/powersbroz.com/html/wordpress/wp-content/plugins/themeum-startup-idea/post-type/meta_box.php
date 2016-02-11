<?php
/**
 * Admin feature for Custom Meta Box
 *
 * @author 		Themeum
 * @category 	Admin Core
 * @package 	startupidea
 *-------------------------------------------------------------*/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Registering meta boxes
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

add_filter( 'rwmb_meta_boxes', 'themeum_startup_idea_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */

function themeum_startup_idea_register_meta_boxes( $meta_boxes )
{

	$projects = get_posts( array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_type'        => 'project',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	) );

	$list_project = array();

	$project_title = array();

	foreach ($projects as $post) {
		$list_project[$post->ID] = $post->post_title;
		$project_title[$post->post_title] = $post->post_title;
	}



	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'themeum_';





	/**
	 * Register Post Meta for Order Post Type
	 *
	 * @return array
	 */





	$meta_boxes[] = array(
		'id' 		=> 'order-post-meta',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' 	=> __( 'Order Item Settings', 'themeum-startup-idea' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' 	=> array( 'investment'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' 	=> 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' 	=> 'high',

		// Auto save: true, false (default). Optional.
		'autosave' 	=> true,

		// List of meta fields
		'fields' 	=> array(
			
			array(
				'name'  		=> __( 'Project Name', 'themeum-startup-idea' ),
				'id'    		=> "{$prefix}project_name",
				'desc'  		=> '',
				'type'     		=> 'select_advanced',
				'options'  		=> $project_title,
				'multiple'    	=> false,
				'placeholder' 	=> __( 'Select Project Name', 'themeum-startup-idea' ),
			),


			array(
				'name'          => __( 'Invest ID', 'themeum-startup-idea' ),
				'id'            => "{$prefix}invest_id",
				'desc'			=> __( 'Investment ID', 'themeum-startup-idea' ),
				'type'          => 'number',
				'std'           => ''
			),	


			array(
				'name'          => __( 'Investor User ID', 'themeum-startup-idea' ),
				'id'            => "{$prefix}investor_user_id",
				'desc'			=> __( 'Investor User ID', 'themeum-startup-idea' ),
				'type'          => 'number',
				'std'           => ''
			),	
	
			array(
				'name'  		=> __( 'Investment Project ID', 'themeum-startup-idea' ),
				'id'    		=> "{$prefix}investment_project_id",
				'desc'  		=> '',
				'type'     		=> 'select_advanced',
				'options'  		=> $list_project,
				'multiple'    	=> false,
				'placeholder' 	=> __( 'Select Project', 'themeum-startup-idea' ),
			),


			array(
				'name'          => __( 'Investment Amount', 'themeum-startup-idea' ),
				'id'            => "{$prefix}investment_amount",
				'desc'			=> __( 'Investment Amount Ex. 4500', 'themeum-startup-idea' ),
				'type'          => 'number',
				'std'           => ''
			),	

			array(
				'name'          => __( 'Payment ID', 'themeum-startup-idea' ),
				'id'            => "{$prefix}payment_id",
				'desc'			=> __( 'Payment ID Ex. 333333', 'themeum-startup-idea' ),
				'type'          => 'number',
				'std'           => ''
			),	

			array(
				'name'          => __( 'Payment Method', 'themeum-startup-idea' ),
				'id'            => "{$prefix}payment_method",
				'desc'			=> __( 'Add Payment Method', 'themeum-startup-idea' ),
				'type'          => 'text',
				'std'           => ''
			),		

			array(
				'name'          => __( 'Investment Date', 'themeum-startup-idea' ),
				'id'            => "{$prefix}investment_date",
				'desc'			=> __( 'Investment Date', 'themeum-startup-idea' ),
				'type'          => 'datetime',
				'std'           => ''
			),

			array(
				'name'  		=> __( 'Comments', 'themeum-startup-idea' ),
				'id'    		=> "{$prefix}investment_comments",
				'desc'  		=> __( 'Add Your Comments Here', 'themeum-startup-idea' ),
				'type'  		=> 'textarea',
				'std'   		=> ''
			),

			array(
				'name'          => __( 'Investment Status', 'themeum-startup-idea' ),
				'id'            => "{$prefix}status_all",
				'desc'			=> __( 'Select Investment Status', 'themeum-startup-idea' ),
				'type'          => 'select',
				'std'           => 'pending',
				'options' 		=> array(
						            'pending '   => 'Pending',
						            'complete'   => 'Complete',
						            'refund'     => 'Refund',
						       		 )
			),	
					
		)
	);


	return $meta_boxes;
}