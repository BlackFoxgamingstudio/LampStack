<?php

/**
 * Flush rewrite rules.
 * 
 * @since 1.0
 */
add_action( 'after_switch_theme', 'tokopress_flush_rewrite_rules' );
function tokopress_flush_rewrite_rules() {	
	flush_rewrite_rules();
}

/**
 * After Theme Setup
 */
add_action( 'after_setup_theme', 'tokopress_setup' );
function tokopress_setup() {

	// load Text Domain
	load_theme_textdomain( 'tokopress', THEME_DIR . '/languages' );

	// imgae size
	add_image_size( 'blog-thumbnail', 400, 200, true );

	// Register Nav Menu
	register_nav_menus( array(
			'header_menu'	=> __( 'Header Menu', 'tokopress' ),
			'footer_menu'	=> __( 'Footer Menu', 'tokopress' )
		) );

	// Theme Support
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );
	
	// Title Tag
	add_theme_support( 'title-tag' );

	// Post Thumbnails
	add_theme_support( 'post-thumbnails' );

	// Feature
	add_theme_support( 'automatic-feed-links' );

	// style editor
	add_editor_style( 'style-editor.css' );

	// Custom Header
	$custom_header = array(
		'flex-width'    		=> true,
		'width'         		=> 1200,
		'flex-height'   		=> true,
		'height'        		=> 210,
		'default-image' 		=> false,
		'uploads'       		=> true,
	);
	add_theme_support( 'custom-header', $custom_header );

	// Custom Background
	$custom_bg = array(
		'default-color' => '',
		'default-image' => '',
		'wp-head-callback' => 'tokopress_custom_background_cb',
	);
	add_theme_support( 'custom-background', $custom_bg );
}

add_action( 'widgets_init', 'tokopress_setup_sidebars' );
function tokopress_setup_sidebars() {
	register_sidebar( array(
		'id'            => 'primary',
		'name'          => _x( 'Primary', 'sidebar', 'tokopress' ),
		'description'   => __( 'The main (primary) widget area, most often used as a sidebar.', 'tokopress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'event',
		'name'          => _x( 'Event', 'sidebar', 'tokopress' ),
		'description'   => __( 'The events page widget area', 'tokopress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'shop',
		'name'          => _x( 'Shop', 'sidebar', 'tokopress' ),
		'description'   => __( 'The shop/product page widget area', 'tokopress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'footer-one',
		'name'          => _x( 'Footer #1', 'sidebar', 'tokopress' ),
		'description'   => __( 'The first footer widget area', 'tokopress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'footer-two',
		'name'          => _x( 'Footer #2', 'sidebar', 'tokopress' ),
		'description'   => __( 'The second footer widget area', 'tokopress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'footer-three',
		'name'          => _x( 'Footer #3', 'sidebar', 'tokopress' ),
		'description'   => __( 'The third footer widget area', 'tokopress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'footer-four',
		'name'          => _x( 'Footer #4', 'sidebar', 'tokopress' ),
		'description'   => __( 'The fourth footer widget area', 'tokopress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
}

/**
 * Other Required
 */
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/customizer/customizer-framework.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/customizer/customizer-fonts.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/functions.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/frontend.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/options.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/plugins.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/designs.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/widgets.php' );
tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/update.php' );

if( class_exists( 'Tribe__Events__Main' ) ) {
	tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/events/functions.php' );
	tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/events/gallery.php' );
	tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/events/frontend.php' );
	tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/events/options.php' );
	tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/events/metabox.php' );
	if ( class_exists( 'Tribe__Events__Tickets__Woo__Main' ) ) {
		tokopress_include_file( trailingslashit( THEME_DIR ) . 'inc/events/wootickets.php' );
	}
}

if( class_exists( 'woocommerce' ) ) {
	tokopress_include_file( trailingslashit( THEME_DIR  ) . 'inc/woocommerce/frontend.php' );
	tokopress_include_file( trailingslashit( THEME_DIR  ) . 'inc/woocommerce/options.php' );
	tokopress_include_file( trailingslashit( THEME_DIR  ) . 'inc/woocommerce/functions.php' );
}

