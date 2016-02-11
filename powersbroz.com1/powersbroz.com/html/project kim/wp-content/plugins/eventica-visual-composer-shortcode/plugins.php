<?php 
/*
Plugin Name: Eventica Visual Composer & Shortcodes
Plugin URI: http://tokopress.com/
Description: Visual composer elements and shortcodes for Eventica WordPress Theme
Version: 1.5.1
Author: TokoPress
Author URI: http://tokopress.com/
Text Domain: tokopress
Domain Path: /languages/

	Copyright: © 2014 TokoPress.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
	Credits:
	Eventica Shortcode http://tokopress.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load Languages
 **/
load_plugin_textdomain( 'tokopress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


include_once( 'components/vc-events-slider.php' );
include_once( 'components/vc-events-venue.php' );
include_once( 'components/vc-events-organizer.php' );
include_once( 'components/vc-events-search.php' );
include_once( 'components/vc-search.php' );
include_once( 'components/vc-upcoming-events.php' );
include_once( 'components/vc-recent-posts.php' );
include_once( 'components/vc-featured-event.php' );
include_once( 'components/vc-subscribe-form.php' );
include_once( 'components/vc-testimonial.php' );
include_once( 'components/vc-brand-sponsors.php' );
