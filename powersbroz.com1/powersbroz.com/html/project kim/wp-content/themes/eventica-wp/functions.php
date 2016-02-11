<?php
/**
 * Functions file.
 *
 * @package Themes 
 * @author Tokopress
 *
 */

if ( ! isset( $content_width ) ) $content_width = 960;

define( 'THEME_NAME' , 'eventica-wp' );
define( 'THEME_VERSION', '1.6' );

define( 'THEME_DIR' , get_template_directory() );
define( 'THEME_URI', get_template_directory_uri() );

include_once( trailingslashit( THEME_DIR ) . 'inc/libs/option-framework/options-framework.php' );
include_once( trailingslashit( THEME_DIR ) . 'inc/libs/tokopress-general.php' );
include_once( trailingslashit( THEME_DIR ) . 'inc/libs/tokopress-post-meta.php' );
include_once( trailingslashit( THEME_DIR ) . 'inc/libs/tokopress-breadcrumb.php' );
include_once( trailingslashit( THEME_DIR ) . 'inc/setup.php' );

/**
 * Theme Scripts
 */
function tokopress_scripts() {

	// CMB2 styles
	$styles = apply_filters( 'cmb2_style_dependencies', array() );
	wp_register_style( 'cmb2-styles', THEME_URI . '/inc/libs/cmb2/cmb2.min.css', $styles );

	// Fonts
	// wp_enqueue_style( 'tokopress-fonts', 'http://fonts.googleapis.com/css?family=Noto+Sans:400,700|Raleway:400,700', array(), THEME_VERSION, 'all' );

	// Superfish
	wp_enqueue_script( 'tokopress-superfish-js', THEME_URI . '/js/superfish.js', array( 'jquery' ), '', true );

	// Slidebars
	wp_enqueue_script( 'tokopress-slidebars-js', THEME_URI . '/js/slidebars.js', array( 'jquery' ), '', true );

	// Sticky
	if ( of_get_option( 'tokopress_sticky_header' ) ) {
		wp_enqueue_script( 'tokopress-sticky-js', THEME_URI . '/js/jquery.sticky.js', array( 'jquery' ), '', true );
	}

	// Magnific Popup
	wp_register_script( 'tokopress-magnific-popup', THEME_URI . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '', true );
	if ( is_singular('tribe_events') ) {
		wp_enqueue_script( 'tokopress-magnific-popup' );
	}

	// OWL Carousel
	wp_register_script( 'tokopress-owl-js', THEME_URI . '/js/owl.carousel.min.js', array( 'jquery' ), '2.0', true );
	if( class_exists( 'woocommerce' ) && is_product() ) {
		wp_enqueue_script( 'tokopress-owl-js' );
	}
	if( is_page_template( 'page_home_event.php' ) ) {
		wp_enqueue_script( 'tokopress-owl-js' );
	}

	// Comments Reply
	if ( is_singular() ) {
		wp_enqueue_script( "comment-reply" );
	}

	// Gmaps
	if( is_page_template( 'page_contact.php' ) ) {
  		wp_enqueue_script( 'tokopress-maps-js', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true', array( 'jquery' ), '3', true );
  		wp_enqueue_script( 'tokopress-gmaps-js', trailingslashit( THEME_URI ) . 'js/gmaps.js', array( 'jquery' ), '0.4.12', true );
  	}

}
add_action( 'wp_enqueue_scripts', 'tokopress_scripts' );

/* This is needed to make it fully compatible with Visual Composer */
function tokopress_scripts_late() {
	// Theme script
	wp_enqueue_script( 'tokopress-js', THEME_URI . '/js/eventica.js', array( 'jquery' ), THEME_VERSION, true );
}
add_action( 'wp_footer', 'tokopress_scripts_late', 11 );

function tokopress_include_file( $file ) {
	include_once( $file );
}
function tokopress_require_file( $file ) {
	require_once( $file );
}
