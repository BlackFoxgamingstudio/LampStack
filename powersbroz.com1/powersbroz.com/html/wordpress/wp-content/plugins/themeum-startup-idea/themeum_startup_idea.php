<?php
/**
 * Plugin Name: Themeum Startup Idea
 * Plugin URI: http://www.themeum.com/wordpress/startupidea
 * Description: Themeum Startup Idea is ultimate event plugins
 * Author: Themeum
 * Version: 2.0
 * Author URI: http://www.themeum.com
 *
 * Tested up to: 4.2
 * Text Domain: themeum-startup-idea
 *
 * @package Themeum Startup Idea
 * @category Core
 * @author Themeum
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'THMRWMB_URL', trailingslashit( plugins_url( 'post-type/meta-box' , __FILE__ ) ) );
define( 'THMRWMB_DIR', trailingslashit(  plugin_dir_path( __FILE__ ). 'post-type/meta-box' ) );

add_action( 'plugins_loaded', 'themeum_startup_idea_init' );

function themeum_startup_idea_init(){
	themeum_language_load();
}

function themeum_language_load($locale = null){
	global $l10n;
	
	$domain = 'themeum-startup-idea';

	if ( get_locale() == $locale ) {
		$locale = null;
	}

	if ( empty( $locale ) ) {
		if ( is_textdomain_loaded( $domain ) ) {
			return true;
		} else {
			return load_plugin_textdomain( $domain, false, $domain . '/languages' );
		}
	} else {
		$mo_orig = $l10n[$domain];
		unload_textdomain( $domain );

		$mofile = $domain . '-' . $locale . '.mo';
		$path = WP_PLUGIN_DIR . '/' . $domain . '/languages';

		if ( $loaded = load_textdomain( $domain, $path . '/'. $mofile ) ) {
			return $loaded;
		} else {
			$mofile = WP_LANG_DIR . '/plugins/' . $mofile;
			return load_textdomain( $domain, $mofile );
		}

		$l10n[$domain] = $mo_orig;
	}

	return false;
	
}

// Include the meta box script
require_once THMRWMB_DIR . 'meta-box.php';



//Register Post Type 
include_once( 'post-type/themeum-investment.php' );
include_once( 'post-type/themeum-project.php' );
//include_once( 'post-type/themeum-management.php' );


// Theme Metabox
include_once( 'post-type/meta_box.php' );


// Shortcode ( New For startup idea )
require_once( 'shortcodes/themeum-feature-ideas.php');
require_once( 'shortcodes/themeum-project.php');
require_once( 'shortcodes/themeum-project-listing.php');
require_once( 'shortcodes/handpick-project.php');
require_once( 'shortcodes/themeum-project-categories.php');
require_once( 'shortcodes/themeum-category-projects.php');


// Admin Menu
include_once( 'admin/menus.php' );

// Admin Dashboard
include_once( 'admin/dashboard.php' );

//Include plugins settings page
include_once( 'themeum_startup_idea_settings.php' );

//Add Themeum Payment module
include_once( 'payment/paypal/paypalplatform.php'); 	// Paypal Class Add
include_once( 'payment/payment-table.php' ); 			// Short the default table in Backend
include_once( 'payment/payment-form-submit.php' ); 	

