<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


add_filter( 'rwmb_meta_boxes', 'thm_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function thm_register_meta_boxes( $meta_boxes )
{
	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'thm_';

		// 1st meta box
	$meta_boxes[] = array(
		'id' => 'post-meta-quote',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Quote Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => __( 'Qoute Text', 'themeum' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}qoute",
				'desc'  => __( 'Write Your Qoute Here', 'themeum' ),
				'type'  => 'textarea',
				// Default value (optional)
				'std'   => ''
			),
			array(
				// Field name - Will be used as label
				'name'  => __( 'Qoute Author', 'themeum' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}qoute_author",
				'desc'  => __( 'Write Qoute Author or Source', 'themeum' ),
				'type'  => 'text',
				// Default value (optional)
				'std'   => ''
			)
			
		)
	);

	$meta_boxes[] = array(
		'id' => 'post-meta-chat',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Chat Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => __( 'Chat Message', 'themeum' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}chat_text",
				'type' => 'wysiwyg',
				'raw'  => false,
				'options' => array(
					'textarea_rows' => 4,
					'teeny'         => false,
					'media_buttons' => false,
				)
			)
			
		)
	);


	$meta_boxes[] = array(
		'id' => 'post-meta-link',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Link Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => __( 'Link URL', 'themeum' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}link",
				'desc'  => __( 'Write Your Link', 'themeum' ),
				'type'  => 'text',
				// Default value (optional)
				'std'   => ''
			)
			
		)
	);


	$meta_boxes[] = array(
		'id' => 'post-meta-audio',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Audio Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => __( 'Audio Embed Code', 'themeum' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}audio_code",
				'desc'  => __( 'Write Your Audio Embed Code Here', 'themeum' ),
				'type'  => 'textarea',
				// Default value (optional)
				'std'   => ''
			)
			
		)
	);

	$meta_boxes[] = array(
		'id' => 'post-meta-status',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Status Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array('post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => __( 'Status URL', 'themeum' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}status_url",
				'desc'  => __( 'Write Facebook, Twitter etc status link', 'themeum' ),
				'type'  => 'textarea',
				// Default value (optional)
				'std'   => ''
			)
			
		)
	);


	$meta_boxes[] = array(
		'id' => 'post-meta-video',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Video Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => __( 'Video Embed Code/ID', 'themeum' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}video",
				'desc'  => __( 'Write Your Vedio Embed Code/ID Here', 'themeum' ),
				'type'  => 'textarea',
				// Default value (optional)
				'std'   => ''
			),
			array(
				'name'     => __( 'Select Vedio Type/Source', 'themeum' ),
				'id'       => "{$prefix}video_source",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'1' => __( 'Embed Code', 'themeum' ),
					'2' => __( 'YouTube', 'themeum' ),
					'3' => __( 'Vimeo', 'themeum' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '1'
			),
			
		)
	);


	$meta_boxes[] = array(
		'id' => 'post-meta-gallery',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Gallery Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				'name'             => __( 'Gallery Image Upload', 'themeum' ),
				'id'               => "{$prefix}gallery_images",
				'type'             => 'image_advanced',
				'max_file_uploads' => 6,
			)			
		)
	);

	$meta_boxes[] = array(
		'id' => 'page-meta-settings',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Page Settings', 'themeum' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'page','project'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// List of meta fields
		'fields' => array(
			array(
				'name'             => __( 'Upload Sub Title Banner Image', 'themeum' ),
				'id'               => "{$prefix}subtitle_images",
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
			),	

			array(
				'name'             => __( 'Upload Sub Title BG Color', 'themeum' ),
				'id'               => "{$prefix}subtitle_color",
				'type'             => 'color',
				'std' 			   => "#444"
			),	
		)
	);


	$meta_boxes[] = array(
		'id' => 'project-meta-setting',
		'title' => __( 'Project Infomation', 'themeum' ),
		'pages' => array( 'project'),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(

			array(
				'name'  => __( 'Location', 'themeum' ),
				'id'    => "{$prefix}location",
				'type'  => 'text',
				'std'   => ''
			),
			array(
				'name'  => __( 'Project Start Date', 'themeum' ),
				'id'    => "{$prefix}start_date",
				'type'  => 'date',
				'std'   => ''			
			),
			array(
				'name'  => __( 'Project End Date', 'themeum' ),
				'id'    => "{$prefix}end_date",
				'type'  => 'date',
				'std'   => ''
			),
			array(
				'name'  => __( 'Funding Goal(Project Budget)', 'themeum' ),
				'id'    => "{$prefix}funding_goal",
				'type'  => 'text',
				'std'   => ''
			),
			array(
				'name'  => __( 'Video URL', 'themeum' ),
				'id'    => "{$prefix}video_url",
				'type'  => 'text',
				'std'   => ''
			),
			array(
				'name'     => __( 'Type', 'themeum' ),
				'id'       => "{$prefix}type",
				'type'     => 'select',
				'options'  => array(
					'Non Profitable' => __( 'Non Profitable', 'themeum' ),
					'Profitable' => __( 'Profitable', 'themeum' ),
					),
				'multiple'    => false,
				'std'         => '1'
			),
			array(
				'name' => __( 'Featured', 'themeum' ),
				'id'   => "{$prefix}featured",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
			),
			array(
				'name'  => __( 'About the Project Creator', 'themeum' ),
				'id'    => "{$prefix}about",
				'type'  => 'textarea',
				'std'   => ''
			),


			array(
				'name'   => __( '<b>Reward</b>', 'themeum-soccer' ),
				'id'     => 'themeum_reward',
				'type'   => 'group',
				'fields' => array(			
						array(
							'name'          => __( 'Limit 1', 'themeum-soccer' ),
							'id'            => "themeum_min",
							'desc'			=> __( 'Limit 1(Ex: 30)', 'themeum-soccer' ),
							'type'          => 'text',
							'std'           => '',
						),

						array(
							'name'          => __( 'Limit 2', 'themeum-soccer' ),
							'id'            => "themeum_max",
							'desc'			=> __( 'Limit 2(Ex: 99)', 'themeum-soccer' ),
							'type'          => 'text',
							'std'           => '',
						),	

						array(
							'name'  => __( 'Reward', 'themeum' ),
							'id'    => "themeum_reward_data",
							'type'  => 'textarea',
							'std'   => ''
						),		
				),
				'clone'  => true,
			),






		)
	);



	//clienta
	$meta_boxes[] = array(
		'id' => 'client-meta-setting',
		'title' => __( 'Additional Infomation', 'themeum' ),
		'pages' => array( 'client'),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(

			array(
				'name'  => __( 'Button Link', 'themeum' ),
				'id'    => "{$prefix}btn_link",
				'type'  => 'text',
				'std'   => ''
			),
		)
	);


	//Testimonial
	$meta_boxes[] = array(
		'id' => 'testimonial-meta-setting',
		'title' => __( 'Additional Infomation', 'themeum' ),
		'pages' => array( 'testimonial'),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(

			array(
				'name'  => __( 'Website URL', 'themeum' ),
				'id'    => "{$prefix}website_url",
				'type'  => 'text',
				'std'   => ''
			),			

			array(
				'name'  => __( 'Testimonial Image', 'themeum' ),
				'id'    => "{$prefix}image",
				'type'  => 'image_advanced',
				'max_file_uploads' => 1,
			),
		)
	);

	
	return $meta_boxes;
}