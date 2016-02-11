<?php
/**
 * Theme Options Settings
 *
 * @package Theme Options
 * @author Tokopress
 *
 */

/*
 * Load Option Framework
 */
define( 'OPTIONS_FRAMEWORK_DIRECTORY', THEME_URI . '/inc/libs/option-framework/' );

/**
 * Set Option Name For Option Framework
 */
function optionsframework_option_name() {
	$optionsframework_settings = get_option( 'optionsframework' );
	if ( defined( 'THEME_NAME' ) ) {
		$optionsframework_settings['id'] = THEME_NAME;
	}
	else {
		$themename = wp_get_theme();
		$themename = preg_replace("/\W/", "_", strtolower($themename) );
		$optionsframework_settings['id'] = $themename;
	}
	update_option( 'optionsframework', $optionsframework_settings );

    $defaults = optionsframework_defaults();
	add_option( $optionsframework_settings['id'], $defaults, '', 'yes' );
}

/**
 * Get Default Options For Option Framework
 */
function optionsframework_defaults() {
    $options = null;
    $location = apply_filters( 'options_framework_location', array(get_template_directory() . '/inc/theme/options.php') );
    if ( $optionsfile = locate_template( $location ) ) {
        $maybe_options = tokopress_require_file( $optionsfile );
        if ( is_array( $maybe_options ) ) {
			$options = $maybe_options;
        } else if ( function_exists( 'optionsframework_options' ) ) {
			$options = optionsframework_options();
		}
    }
    $options = apply_filters( 'of_options', $options );
    $defaults = array();
    foreach ($options as $key => $value) {
    	if( isset($value['id']) && isset($value['std']) ) {
    		$defaults[$value['id']] = $value['std'];
    	}
    }
    return $defaults;
}

/* 
 * Override a default filter for 'textarea' sanitization and $allowedposttags + embed and script.
 */
add_action('admin_init','optionscheck_change_santiziation', 100);
function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'tokopress_of_sanitize_textarea' );
}
function tokopress_of_sanitize_textarea($input) {
    global $allowedposttags;
    $custom_allowedtags["embed"] = array(
      "src" => array(),
      "type" => array(),
      "allowfullscreen" => array(),
      "allowscriptaccess" => array(),
      "height" => array(),
          "width" => array()
      );
      $custom_allowedtags["script"] = array();
 
      $custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
      $output = wp_kses( $input, $custom_allowedtags);
    return $output;
}

/**
 * Load Custom Style For Option Framework
 */
function tokopress_style_option_framework() {
	wp_enqueue_style( 'style-option-framework', OPTIONS_FRAMEWORK_DIRECTORY . '/css/option-framework.css' );
}
add_action( 'optionsframework_custom_scripts', 'tokopress_style_option_framework' );

/**
 * Header Settings
 */
function tokopress_header_settings( $options ) {

	$options[] = array(
		'name' 	=> __( 'Header', 'tokopress' ),
		'type' 	=> 'heading'
	);

		if ( function_exists( 'wp_site_icon' ) ) {
			$options[] = array(
				'name' 	=> __( 'Favicon', 'tokopress' ),
				'type' 	=> 'info',
				'desc' 	=> sprintf( __( 'Go to <a href="%s">Appearance - Customize - Site Identity</a> to customize Favicon (Site Icon).', 'tokopress' ), admin_url( 'customize.php?autofocus[control]=site_icon' ) ),
			);
		}
		else {
			$options[] = array(
				'name' 	=> __( 'Favicon', 'tokopress' ),
				'id' 	=> 'tokopress_favicon',
				'type' 	=> 'upload',
			);
		}

		$options[] = array(
			'name' 	=> __( 'Sticky Header', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name' 	=> __( 'Sticky Header', 'tokopress' ),
				'desc' 	=> __( 'ENABLE sticky header', 'tokopress' ),
				'id' 	=> 'tokopress_sticky_header',
				'std' 	=> '0',
				'type' 	=> 'checkbox'
			);

		$options[] = array(
			'name' 	=> __( 'Header Section', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name' 	=> __( 'Site Logo', 'tokopress' ),
				'id' 	=> 'tokopress_site_logo',
				'type' 	=> 'upload'
			);

		$options[] = array(
			'name' 	=> __( 'Page Title Section', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name' 	=> __( 'Page Title', 'tokopress' ),
				'desc' 	=> __( 'DISABLE page title section globally.', 'tokopress' ),
				'id' 	=> 'tokopress_page_title_disable',
				'std' 	=> '0',
				'type' 	=> 'checkbox'
			);

		$options[] = array(
			'name' 	=> __( 'Header Scripts', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name' 	=> __( 'Header Script', 'tokopress' ),
				'desc' 	=> __( 'You can put any script here, for example your Google Analytics scripts.', 'tokopress' ),
				'id' 	=> 'tokopress_header_script',
				'std' 	=> '',
				'type' 	=> 'textarea'
			);

	return $options;
}
add_filter( 'of_options', 'tokopress_header_settings' );

/**
 * Footer Settings
 */
function tokopress_footer_settings( $options ) {
	$theme_name = wp_get_theme();

	$options[] = array(
		'name' 	=> __( 'Footer', 'tokopress' ),
		'type' 	=> 'heading'
	);

		$options[] = array(
			'name' 	=> __( 'Footer Section', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name' 	=> __( 'Footer Widget', 'tokopress' ),
				'desc' 	=> __( 'DISABLE footer widget', 'tokopress' ),
				'id' 	=> 'tokopresss_disable_footer_widget',
				'std' 	=> '0',
				'type' 	=> 'checkbox'
			);

			$options[] = array(
				'name' 	=> __( 'Footer Buttom', 'tokopress' ),
				'desc' 	=> __( 'DISABLE footer buttom', 'tokopress' ),
				'id' 	=> 'tokopresss_disable_footer_buttom',
				'std' 	=> '0',
				'type' 	=> 'checkbox'
			);

			$options[] = array(
				'name' 	=> __( 'Footer Credit Text', 'tokopress' ),
				'desc' 	=> '',
				'id' 	=> 'tokopress_footer_text',
				'type' 	=> 'textarea'
			);

		$options[] = array(
			'name' 	=> __( 'Social Icon', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name' => __( 'DISABLE Social icons', 'tokopress' ),
				'desc' => __( 'DISABLE social icons in footer', 'tokopress' ),
				'id' => 'tokopress_hide_social',
				'type' => 'checkbox'
			);

			$socials = array(
				'' 				=> '&nbsp;',
				'rss' 			=> 'RSS Feed',
				'envelope-o' 	=> 'E-mail',
				'twitter' 		=> 'Twitter',
				'facebook' 		=> 'Facebook',
				'google-plus' 	=> 'gPlus',
				'youtube' 		=> 'Youtube',
				'flickr' 		=> 'Flickr',
				'linkedin' 		=> 'Linkedin',
				'pinterest' 	=> 'Pinterest',
				'dribbble' 		=> 'Dribbble',
				'github' 		=> 'Github',
				'lastfm' 		=> 'LastFm',
				'vimeo-square' 	=> 'Vimeo',
				'tumblr' 		=> 'Tumblr',
				'instagram' 	=> 'Instagram',
				'soundcloud' 	=> 'Sound Cloud',
				'behance' 		=> 'Behance',
				'deviantart' 	=> 'Daviant Art'
			);

			for( $is=1;$is<=5;$is++ ) {
				$options[] = array(
					'name' => sprintf( __( 'Social #%s', 'tokopress' ), $is ),
					'desc' => '',
					'id' => 'tokopress_social_' . $is,
					'type' => 'select',
					'options' => $socials
				);
				$options[] = array(
					'name' => sprintf( __( 'Social URL #%s', 'tokopress' ), $is ),
					'desc' => '',
					'id' => 'tokopress_social_' . $is . '_url',
					'type' => 'text'
				);
			}

		$options[] = array(
			'name' 	=> __( 'Footer Scripts', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name' 	=> __( 'Footer Script', 'tokopress' ),
				'desc' 	=> __( 'You can put any script here, for example your Google Analytics scripts.', 'tokopress' ),
				'id' 	=> 'tokopress_footer_script',
				'std' 	=> '',
				'type' 	=> 'textarea'
			);

	return $options;
}
add_filter( 'of_options', 'tokopress_footer_settings' );

/**
 * Homepage Settings
 */
function tokopress_homepage_settings( $options ) {
	$options[] = array(
		'name' 	=> __( 'Home Template', 'tokopress' ),
		'type' 	=> 'heading'
	);

	/**
	 * Events Options
	 */
	if( class_exists( 'Tribe__Events__Main' ) ) {

		$options[] = array(
			'name' 	=> __( 'Events Slider', 'tokopress' ),
			'type' 	=> 'info'
		);

			$options[] = array(
				'name'	=> __( 'Events Slider', 'tokopress' ),
				'desc'	=> __( 'DISABLE Events Slider', 'tokopress' ),
				'id' 	=> 'tokopress_home_slider_disable',
				'type' 	=> 'checkbox'
			);

			$options[] = array(
				'name'=> __( 'Event IDs', 'tokopress' ),
				'desc'=> __( 'Separated by comma. Use this is you want to show some specific events for slider. "Number of Events" and "Event Category" options will be ignored.', 'tokopress' ),
				'id'=> 'tokopress_home_slider_ids',
				'type'=> 'text'
			);

			$options[] = array(
				'name'=> __( 'Number of Events', 'tokopress' ),
				'desc'=> __( 'Use this is you want to show upcoming events for slider. Leave "Event IDs" option empty.', 'tokopress' ),
				'id'=> 'tokopress_home_slider_numbers',
				'type'=> 'text',
				'std'=> '4'
			);

			$options[] = array(
				'name'=> __( 'Event Category Name', 'tokopress' ),
				'desc'=> __( 'Put event category name here if you want to retrieve upcoming events from this category only. Leave "Event IDs" option empty.', 'tokopress' ),
				'id'=> 'tokopress_home_slider_category',
				'type'=> 'text',
				'std'=> ''
			);

			$options[] = array(
				'name'=> __( 'Button Text', 'tokopress' ),
				'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'Detail', 'tokopress' ),
				'id'=> 'tokopress_home_slide_button',
				'type'=> 'text',
				'std'=> ''
			);

		$options[] = array(
			'name' => __( 'Search Form', 'tokopress' ),
			'type' => 'info'
		);

			$options[] = array(
				'name'	=> __( 'Search Form', 'tokopress' ),
				'desc'	=> __( 'DISABLE Search Form', 'tokopress' ),
				'id' 	=> 'tokopress_home_search_disable',
				'type' 	=> 'checkbox'
			);

			$options[] = array(
				'name'=> __( 'Placeholder Text', 'tokopress' ),
				'desc'=> __( 'Default:', 'tokopress' ).' '._x( 'Search Event &hellip;', 'placeholder', 'tokopress' ),
				'id'=> 'tokopress_home_search_text',
				'type'=> 'text',
				'std'=> ''
			);

		$options[] = array(
			'name' => __( 'Upcoming Events', 'tokopress' ),
			'type' => 'info'
		);

			$options[] = array(
				'name'	=> __( 'Upcoming Events', 'tokopress' ),
				'desc'	=> __( 'DISABLE Upcoming Events', 'tokopress' ),
				'id' 	=> 'tokopress_home_upcoming_event_disable',
				'type' 	=> 'checkbox'
			);

			$options[] = array(
				'name'=> __( 'Number of Events', 'tokopress' ),
				'desc'=> __( 'Number of upcoming events to be displayed.', 'tokopress' ),
				'id'=> 'tokopress_home_upcoming_event_numbers',
				'type'=> 'text',
				'std'=> '3'
			);

			$options[] = array(
				'name'=> __( 'Event Category Name', 'tokopress' ),
				'desc'=> __( 'Put event category name here if you want to retrieve upcoming events from this category only.', 'tokopress' ),
				'id'=> 'tokopress_home_upcoming_event_category',
				'type'=> 'text',
				'std'=> ''
			);

			$options[] = array(
				'name'=> __( 'Exclude', 'tokopress' ),
				'desc'=> __( 'Insert Event IDs (separated by comma) to exclude.', 'tokopress' ),
				'id'=> 'tokopress_home_upcoming_event_exclude',
				'type'=> 'text'
			);

			$options[] = array(
				'name'=> __( 'Section Title', 'tokopress' ),
				'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'Upcoming Events', 'tokopress' ),
				'id'=> 'tokopress_home_upcoming_event',
				'type'=> 'text',
				'std'=> ''
			);

			$options[] = array(
				'name'=> __( 'Link Text', 'tokopress' ),
				'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'All Events', 'tokopress' ),
				'id'=> 'tokopress_home_upcoming_event_text',
				'type'=> 'text',
				'std'=> ''
			);

		$options[] = array(
			'name' => __( 'Featured Event', 'tokopress' ),
			'type' => 'info'
		);

			$options[] = array(
				'name'=> __( 'Section Title', 'tokopress' ),
				'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'Featured Event', 'tokopress' ),
				'id'=> 'tokopress_home_featured_event',
				'type'=> 'text'
			);
			$options[] = array(
				'name'=> __( 'Event ID', 'tokopress' ),
				'desc'=> __( 'Insert Event ID for Featured Event.', 'tokopress' ),
				'id'=> 'tokopress_home_featured_event_page',
				'type'=> 'text'
			);
	}

	/**
	 * Post Options
	 */
	$options[] = array(
		'name' => __( 'Recent Updates (Blog)', 'tokopress' ),
		'type' => 'info'
	);

		$options[] = array(
			'name'	=> __( 'Recent Updates (Blog)', 'tokopress' ),
			'desc'	=> __( 'DISABLE Recent Updates (Blog)', 'tokopress' ),
			'id' 	=> 'tokopress_home_recent_post_disable',
			'type' 	=> 'checkbox'
		);

		$options[] = array(
			'name'=> __( 'Section Title', 'tokopress' ),
			'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'Recent Updates', 'tokopress' ),
			'id'=> 'tokopress_home_recent_post',
			'type'=> 'text'
		);

		$options[] = array(
			'name'=> __( 'Link Text', 'tokopress' ),
			'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'All Events', 'tokopress' ),
			'id'=> 'tokopress_home_recent_post_text',
			'type'=> 'text',
			'std'=> ''
		);

	/**
	 * MailChimp Options
	 */
	$options[] = array(
		'name' => __( 'Subscribe Form', 'tokopress' ),
		'type' => 'info'
	);

		$options[] = array(
			'name'	=> __( 'Subscribe Form', 'tokopress' ),
			'desc'	=> __( 'DISABLE Subscribe Form', 'tokopress' ),
			'id' 	=> 'tokopress_home_subscribe_disable',
			'type' 	=> 'checkbox'
		);

		$options[] = array(
			'name'=> __( 'Section Title', 'tokopress' ),
			'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'Subscribe to our newsletter', 'tokopress' ),
			'id'=> 'tokopress_home_subscribe_title',
			'type'=> 'text'
		);

		$options[] = array(
			'name'=> __( 'Section Description', 'tokopress' ),
			'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'never miss our latest news and events updates', 'tokopress' ),
			'id'=> 'tokopress_home_subscribe_text',
			'type'=> 'text'
		);

	$options[] = array(
		'name' 	=> __( 'Testimonials', 'tokopress' ),
		'type' 	=> 'info'
	);

		$options[] = array(
			'name' 	=> __( 'Testimonials', 'tokopress' ),
			'desc' 	=> __( 'DISABLE Testimonials', 'tokopress' ),
			'id' 	=> 'tokopresss_home_testimonials_disable',
			'std' 	=> '0',
			'type' 	=> 'checkbox'
		);

		$options[] = array(
			'name'=> __( 'Section Title', 'tokopress' ),
			'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'Testimonials', 'tokopress' ),
			'id'=> 'tokopress_home_testimonials',
			'type'=> 'text',
			'std'=> ''
		);

	$options[] = array(
		'name' 	=> __( 'Brand Sponsors', 'tokopress' ),
		'type' 	=> 'info'
	);

		$options[] = array(
			'name' 	=> __( 'Brand Sponsors', 'tokopress' ),
			'desc' 	=> __( 'DISABLE brand sponsors', 'tokopress' ),
			'id' 	=> 'tokopresss_disable_brands_sponsors',
			'std' 	=> '0',
			'type' 	=> 'checkbox'
		);

		$options[] = array(
				'name'=> __( 'Section Title', 'tokopress' ),
				'desc'=> __( 'Default:', 'tokopress' ).' '.__( 'Our Sponsors', 'tokopress' ),
				'id'=> 'tokopress_brand_title',
				'type'=> 'text'
			);

		for ($i=1; $i <= 8; $i++) { 
			$options[] = array(
				'name' 	=> sprintf( __( 'Sponsor Logo #%s', 'tokopress' ), $i ),
				'id' 	=> "tokopress_brand_img_{$i}",
				'std' 	=> '',
				'type' 	=> 'upload'
			);
			$options[] = array(
				'name' 	=> sprintf( __( 'Sponsor Link #%s', 'tokopress' ), $i ),
				'id' 	=> "tokopress_brand_link_{$i}",
				'std' 	=> '#',
				'type' 	=> 'text'
			);
		}

	return $options;
}
add_filter( 'of_options', 'tokopress_homepage_settings' );

/**
 * Contact Tab Options
 */
function tokopress_contact_settings( $options ) {
	$options[] =array(
		'name' => __( 'Contact Template', 'tokopress' ),
		'type' => 'heading'
	);

		$options[] = array(
			'name' 	=> __( 'DISABLE Page Title', 'tokopress' ),
			'desc' 	=> __( 'DISABLE Page Title in Contact page template', 'tokopress' ),
			'id' 	=> 'tokopress_disable_contact_title',
			'std' 	=> '0',
			'type' 	=> 'checkbox'
		);

		$options[] = array(
			'name' 	=> __( 'DISABLE Sidebar', 'tokopress' ),
			'desc' 	=> __( 'DISABLE Sidebar in Contact page template', 'tokopress' ),
			'id' 	=> 'tokopress_disable_contact_sidebar',
			'std' 	=> '0',
			'type' 	=> 'checkbox'
		);

		$options[] = array(
			'name' 	=> __( 'DISABLE Google Map', 'tokopress' ),
			'desc' 	=> __( 'DISABLE Google Map in Contact page template', 'tokopress' ),
			'id' 	=> 'tokopress_disable_contact_map',
			'std' 	=> '0',
			'type' 	=> 'checkbox'
		);

		$options[] = array(
			'name'=> __( 'Latitude', 'tokopress' ),
			'desc'=> __( 'Insert Latitude coordinate', 'tokopress' ),
			'id'=> 'tokopress_contact_lat',
			'type'=> 'text',
			'std'=> '-6.903932'
		);
		$options[] = array(
			'name'=> __( 'Longitude', 'tokopress' ),
			'desc'=> __( 'Insert Longitude coordinate', 'tokopress' ),
			'id'=> 'tokopress_contact_long',
			'type'=> 'text',
			'std'=> '107.610344'
		);
		$options[] = array(
			'name'=> __( 'Marker Title', 'tokopress' ),
			'desc'=> __( 'Insert marker title', 'tokopress' ),
			'id'=> 'tokopress_contact_marker_title',
			'type'=> 'text',
			'std'=> 'Marker Title'
		);
		$options[] = array(
			'name'=> __( 'Marker Description', 'tokopress' ),
			'desc'=> __( 'Insert marker description', 'tokopress' ),
			'id'=> 'tokopress_contact_marker_desc',
			'type'=> 'textarea',
			'std'=> 'Marker Content'
		);

	return $options;
}
add_filter( 'of_options', 'tokopress_contact_settings' );