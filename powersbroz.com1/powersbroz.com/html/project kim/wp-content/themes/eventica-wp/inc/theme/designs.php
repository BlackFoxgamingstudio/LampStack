<?php
/**
 * Theme Customizer
 */

add_action( 'customize_register', 'tokopress_customize_reposition', 20 );
function tokopress_customize_reposition( $wp_customize ) {
	$wp_customize->get_section( 'background_image' )->priority = 140;
	$wp_customize->get_section( 'background_image' )->title = __( 'Background', 'tokopress' );
	$wp_customize->get_control( 'background_color' )->section = 'background_image';

	$wp_customize->get_section( 'header_image' )->priority = 160;
	$wp_customize->get_section( 'header_image' )->title = __( 'Header (Page Title)', 'tokopress' );
	$wp_customize->remove_control('header_textcolor');
	
}

/**
 * Fonts
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_fonts_section' );
function tokopress_customize_fonts_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_fonts_section',
			'label'		=> __( 'Fonts', 'tokopress' ),
			'priority'	=> 135,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_fonts_colors' );
function tokopress_customize_fonts_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_font_body', 
		'default'	=> 'Noto Sans', 
		'label'		=> __( 'Body Font', 'tokopress' ),
		'section'	=> 'tokopress_fonts_section',
		'transport'	=> 'refresh',
		'type' 		=> 'select_font',
		'selector'	=> 'body,.tribe-events-list .event-list-wrapper-bottom .wraper-bottom-left h2.tribe-events-list-event-title,.home-upcoming-events .upcoming-event-title,.home-recent-posts .recent-post-title,.home-featured-event .featured-event-title h2',
		'property'	=> 'font-family'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_font_heading', 
		'default'	=> 'Raleway', 
		'label'		=> __( 'Heading Font', 'tokopress' ),
		'section'	=> 'tokopress_fonts_section',
		'transport'	=> 'refresh',
		'type' 		=> 'select_font',
		'selector'	=> 'h1,h2,h3,h4,h5,.header-menu.sf-menu li a,.page-title .breadcrumb',
		'property'	=> 'font-family'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_fontsize_body', 
		'default'	=> '', 
		'label'		=> __( 'Body Font Size', 'tokopress' ),
		'section'	=> 'tokopress_fonts_section',
		'transport'	=> 'refresh',
		'type' 		=> 'select',
		'choices'	=> array (
				''		=> __( 'Default', 'tokopress' ),
				'13px'	=> '13px',
				'14px'	=> '14px',
				'15px'	=> '15px',
				'16px'	=> '16px',
			),
	);

	return $tk_colors;
}

add_action( 'tokopress_custom_styles', 'tokopress_customizer_css_fontsize', 10 );
function tokopress_customizer_css_fontsize() { 
	$fontsize = get_theme_mod( 'tokopress_fontsize_body' );
	if ( $fontsize ) {
		echo 'body, .blog-list .post-inner .post-title h2 {font-size:'.esc_attr($fontsize).'}';
	}
}


/**
 * Header (Top)
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_headertop_section' );
function tokopress_customize_headertop_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_headertop_section',
			'label'		=> __( 'Header (Top)', 'tokopress' ),
			'priority'	=> 150,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_headertop_colors' );
function tokopress_customize_headertop_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_bg', 
		'default'	=> '', 
		'label'		=> __( 'Header Background', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.site-header, .header-menu.sf-menu li a',
		'property'	=> 'background-color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_logo_bg', 
		'default'	=> '', 
		'label'		=> __( 'Logo Background', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.site-branding',
		'property'	=> 'background-color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_logo_color', 
		'default'	=> '', 
		'label'		=> __( 'Logo Text Color', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.site-branding a, .site-logo p',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_menu_color', 
		'default'	=> '', 
		'label'		=> __( 'Menu Color', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.site-header, .header-menu.sf-menu li a, .mobile-menu a, .mobile-menu a:visited',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_menu_color_hover', 
		'default'	=> '', 
		'label'		=> __( 'Menu Color', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.header-menu.sf-menu li a:hover, .mobile-menu a:hover',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_menu_color_hover', 
		'default'	=> '', 
		'label'		=> __( 'Menu Color (Hover)', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.header-menu .sub-menu li a:hover',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_submenu_bg', 
		'default'	=> '', 
		'label'		=> __( 'Submenu Background', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.header-menu .sub-menu li a',
		'property'	=> 'background-color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_headertop_submenu_bg_hover', 
		'default'	=> '', 
		'label'		=> __( 'Submenu Background (Hover)', 'tokopress' ),
		'section'	=> 'tokopress_headertop_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.header-menu .sub-menu li a:hover',
		'property'	=> 'background-color'
	);

	return $tk_colors;
}

/**
 * Header (Page Title)
 */
add_filter( 'tokopress_customizer_data', 'tokopress_customize_header_colors' );
function tokopress_customize_header_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_header_bg', 
		'default'	=> '', 
		'label'		=> __( 'Page Title Background', 'tokopress' ),
		'section'	=> 'header_image',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.page-title',
		'property'	=> 'background-color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_header_color', 
		'default'	=> '', 
		'label'		=> __( 'Page Title Color', 'tokopress' ),
		'section'	=> 'header_image',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.page-title, .page-title h1',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_header_breadcrumb_color', 
		'default'	=> '', 
		'label'		=> __( 'Breadcrumb Color', 'tokopress' ),
		'section'	=> 'header_image',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.page-title .breadcrumb, .page-title .breadcrumb a',
		'property'	=> 'color'
	);

	return $tk_colors;
}

/**
 * Events
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_content_section' );
function tokopress_customize_content_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_content_section',
			'label'		=> __( 'Content', 'tokopress' ),
			'priority'	=> 170,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_content_colors' );
function tokopress_customize_content_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_content_link_color', 
		'default'	=> '', 
		'label'		=> __( 'Link Color', 'tokopress' ),
		'section'	=> 'tokopress_content_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> 'a',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_content_line_color', 
		'default'	=> '', 
		'label'		=> __( 'Line/Border Color', 'tokopress' ),
		'section'	=> 'tokopress_content_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.blog-single .post-summary, .blog-single .post-meta ul li, #comments .commentslist-wrap, #comments .commentlist li.comment, #comments-block #respond, #tribe-events-content.tribe-events-single .events-single-left, #tribe-events-content.tribe-events-single .tribe-events-meta-group-details, #tribe-events-content.tribe-events-single .tribe-events-meta-group-venue, #tribe-events-content.tribe-events-single .tribe-events-meta-group-schedule, #tribe-events-content.tribe-events-single .tribe-events-meta-group-custom, #tribe-events-content.tribe-events-single .tribe-events-meta-group-organizer, .woocommerce div.product div.summary, .woocommerce-page div.product div.summary, .woocommerce div.product div.woocommerce-tabs, .woocommerce-page div.product div.woocommerce-tabs, .home-subscribe-form .form.mc4wp-form input[type="email"]',
		'property'	=> 'border-color'
	);

	return $tk_colors;
}

/**
 * Home
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_home_section' );
function tokopress_customize_home_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_home_section',
			'label'		=> __( 'Home Template', 'tokopress' ),
			'priority'	=> 170,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_home_colors' );
function tokopress_customize_home_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_home_slider_detail_bg', 
		'default'	=> '', 
		'label'		=> __( 'Events Slider - Detail Background', 'tokopress' ),
		'section'	=> 'tokopress_home_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.home-slider-events .slide-event-detail',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_home_upcoming_bg', 
		'default'	=> '', 
		'label'		=> __( 'Upcoming Events - Background Color', 'tokopress' ),
		'section'	=> 'tokopress_home_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.page-template-page_home_event-php .home-upcoming-events',
		'property'	=> 'background'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_home_upcoming_bg_img', 
		'default'	=> '', 
		'label'		=> __( 'Upcoming Events - Background Image', 'tokopress' ),
		'section'	=> 'tokopress_home_section',
		'transport'	=> 'refresh',
		'type' 		=> 'images',
		'selector'	=> '.page-template-page_home_event-php .home-upcoming-events',
		'property'	=> 'background-image'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_home_featured_title_bg', 
		'default'	=> '', 
		'label'		=> __( 'Featured Event - Title Background', 'tokopress' ),
		'section'	=> 'tokopress_home_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.home-featured-event .featured-event-title',
		'property'	=> 'background'
	);

	return $tk_colors;
}

/**
 * Events
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_events_section' );
function tokopress_customize_events_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_events_section',
			'label'		=> __( 'Events', 'tokopress' ),
			'priority'	=> 170,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_events_colors' );
function tokopress_customize_events_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_date_bg', 
		'default'	=> '', 
		'label'		=> __( 'Event List Box - Event Date Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.tribe-events-list .tribe-events-event-date',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_cost_bg', 
		'default'	=> '', 
		'label'		=> __( 'Event List Box - Event Cost Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.tribe-events-list .event-list-wrapper-bottom .wraper-bottom-right',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_separator_bg', 
		'default'	=> '', 
		'label'		=> __( 'Event List Separator Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.tribe-events-list .tribe-events-loop .tribe-events-list-separator-month, .tribe-events-list .tribe-events-loop .tribe-events-day-time-slot h5',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_calendar_day_bg', 
		'default'	=> '', 
		'label'		=> __( 'Events Calendar - Header (Day) Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.tribe-events-calendar thead th',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_calendar_day_border', 
		'default'	=> '', 
		'label'		=> __( 'Events Calendar - Header (Day) Border', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.tribe-events-calendar thead th',
		'property'	=> 'border-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_calendar_current_bg', 
		'default'	=> '', 
		'label'		=> __( 'Events Calendar - Current Date Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-], .tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-] > a',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_attendees_bg', 
		'default'	=> '', 
		'label'		=> __( 'Single Event - Events Attendees Title Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.event-attendees-wrap .event-attendees-title h2',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_gallery_bg', 
		'default'	=> '', 
		'label'		=> __( 'Single Event - Events Gallery Title Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.event-gallery-wrap .event-gallery-title h2',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_events_related_bg', 
		'default'	=> '', 
		'label'		=> __( 'Single Event - Related Events Title Background', 'tokopress' ),
		'section'	=> 'tokopress_events_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.related-event-wrap .related-event-title h2',
		'property'	=> 'background-color'
	);

	return $tk_colors;
}

/**
 * WooCommerce
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_wc_section' );
function tokopress_customize_wc_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_wc_section',
			'label'		=> __( 'WooCommerce', 'tokopress' ),
			'priority'	=> 170,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_wc_colors' );
function tokopress_customize_wc_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_wc_saleflash_bg', 
		'default'	=> '', 
		'label'		=> __( 'Sale Flash Background', 'tokopress' ),
		'section'	=> 'tokopress_wc_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.woocommerce span.onsale, .woocommerce-page span.onsale',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_wc_upsells_title_bg', 
		'default'	=> '', 
		'label'		=> __( 'Upsells - Title Background', 'tokopress' ),
		'section'	=> 'tokopress_wc_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.woocommerce .upsells.products h2, .woocommerce-page .upsells.products h2',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_wc_related_title_bg', 
		'default'	=> '', 
		'label'		=> __( 'Related Products - Title Background', 'tokopress' ),
		'section'	=> 'tokopress_wc_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.woocommerce .related.products h2, .woocommerce-page .related.products h2',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_wc_cart_table_bg', 
		'default'	=> '', 
		'label'		=> __( 'Cart - Table Header Background', 'tokopress' ),
		'section'	=> 'tokopress_wc_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.woocommerce table.shop_table thead tr th, .woocommerce-page table.shop_table thead tr th',
		'property'	=> 'background-color'
	);
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_wc_cart_table_bg', 
		'default'	=> '', 
		'label'		=> __( 'Cart/Checkout - Table Header Background', 'tokopress' ),
		'section'	=> 'tokopress_wc_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.woocommerce table.shop_table thead tr th, .woocommerce-page table.shop_table thead tr th',
		'property'	=> 'background-color'
	);

	return $tk_colors;
}

/**
 * Footer Widget
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_footerwidget_section' );
function tokopress_customize_footerwidget_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_footerwidget_section',
			'label'		=> __( 'Footer Widget', 'tokopress' ),
			'priority'	=> 170,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_footerwidget_colors' );
function tokopress_customize_footerwidget_colors( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footerwidget_bg', 
		'default'	=> '', 
		'label'		=> __( 'Footer Widget Background', 'tokopress' ),
		'section'	=> 'tokopress_footerwidget_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-widget',
		'property'	=> 'background-color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footerwidget_color', 
		'default'	=> '', 
		'label'		=> __( 'Footer Widget Color', 'tokopress' ),
		'section'	=> 'tokopress_footerwidget_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-widget, #footer-widget .widget .widget-inner, #footer-widget .widget.widget_recent_posts ul li .tp-entry-date, #footer-widget .widget.widget_upcoming_events ul li .tp-entry-date, #footer-widget .widget.widget_past_events ul li .tp-entry-date',
		'property'	=> 'color',
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footerwidget_link_color', 
		'default'	=> '', 
		'label'		=> __( 'Footer Widget Link Color', 'tokopress' ),
		'section'	=> 'tokopress_footerwidget_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-widget a',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footerwidget_title_color', 
		'default'	=> '', 
		'label'		=> __( 'Footer Widget Title Color', 'tokopress' ),
		'section'	=> 'tokopress_footerwidget_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-widget .widget .widget-title',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footer_widget_border_color', 
		'default'	=> '', 
		'priority'	=> 5, 
		'label'		=> __( 'Footer Widget Border Color', 'tokopress' ),
		'section'	=> 'tokopress_footerwidget_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-widget .widget.widget_recent_posts ul li, #footer-widget .widget.widget_upcoming_events ul li, #footer-widget .widget.widget_past_events ul li',
		'property'	=> 'border-color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footer_widget_border_top_color', 
		'default'	=> '', 
		'priority'	=> 6, 
		'label'		=> __( 'Footer Widget Border Top Color', 'tokopress' ),
		'section'	=> 'tokopress_footer_widget_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '.footer-widgets',
		'property'	=> 'border-top-color'
	);

	return $tk_colors;
}

/**
 * Footer Credit
 */
add_filter( 'tokopress_customizer_sections', 'tokopress_customize_footercredit_section' );
function tokopress_customize_footercredit_section( $tk_sections ) {
	$tk_sections[] = array(
			'slug'		=> 'tokopress_footercredit_section',
			'label'		=> __( 'Footer Credit', 'tokopress' ),
			'priority'	=> 180,
		);

	return $tk_sections;
}
add_filter( 'tokopress_customizer_data', 'tokopress_customize_footercredit_color' );
function tokopress_customize_footercredit_color( $tk_colors ) {
	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footercredit_bg', 
		'default'	=> '', 
		'priority'	=> 1, 
		'label'		=> __( 'Footer Credit Background', 'tokopress' ),
		'section'	=> 'tokopress_footercredit_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-block',
		'property'	=> 'background-color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footercredit_color', 
		'default'	=> '', 
		'priority'	=> 2, 
		'label'		=> __( 'Footer Credit Color', 'tokopress' ),
		'section'	=> 'tokopress_footercredit_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-block, #footer-block .footer-credit p',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footercredit_link_color', 
		'default'	=> '', 
		'priority'	=> 3, 
		'label'		=> __( 'Footer Credit Link Color', 'tokopress' ),
		'section'	=> 'tokopress_footercredit_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-block, #footer-block #footer-menu #secondary-menu ul.footer-menu li a, #footer-block #footer-menu ul#social-icon li a, #footer-block .footer-credit p a',
		'property'	=> 'color'
	);

	$tk_colors[] = array( 
		'slug'		=> 'tokopress_footercredit_link_color_hover', 
		'default'	=> '', 
		'priority'	=> 3, 
		'label'		=> __( 'Footer Credit Link Color (Hover)', 'tokopress' ),
		'section'	=> 'tokopress_footercredit_section',
		'transport'	=> 'postMessage',
		'type' 		=> 'color',
		'selector'	=> '#footer-block #footer-menu #secondary-menu ul.footer-menu li a:hover',
		'property'	=> 'color'
	);

	return $tk_colors;
}

add_action( 'tokopress_custom_styles', 'tokopress_designs_hamburger_mobile_output' );
function tokopress_designs_hamburger_mobile_output() {
	if ( $hex = get_theme_mod('tokopress_headertop_logo_color') ) {
		echo '@media (max-width: 767px) { .mobile-menu a, .mobile-menu a:visited { color: '.$hex.'; } }';
	}
}

add_action( 'tokopress_custom_styles', 'tokopress_designs_rgba_output' );
function tokopress_designs_rgba_output() {
	if ( $hex = get_theme_mod('tokopress_home_slider_detail_bg') ) {
		$rgb =  tokopress_helper_hex2rgb($hex);
		if ( $rgb ) {
			echo '.home-slider-events .slide-event-detail { background-color: rgba('.$rgb.',0.85); }';
		}
	}
	if ( $hex = get_theme_mod('tokopress_events_date_bg') ) {
		$rgb =  tokopress_helper_hex2rgb($hex);
		if ( $rgb ) {
			echo '.tribe-events-list .tribe-events-event-date { background-color: rgba('.$rgb.',0.64); }';
		}
	}
}

function tokopress_helper_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
   // return $rgb; // returns an array with the rgb values
}
