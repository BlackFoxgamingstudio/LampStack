<?php
/*
Plugin Name: Feed Them Social (Facebook, Instagram, Twitter, etc)
Plugin URI: http://slickremix.com/
Description: Create and display custom feeds for Facebook Groups, Facebook Pages, Facebook Events, Facebook Photos, Facebook Album Covers, Twitter, Instagram, Pinterest and YouTube.
Version: 1.9.3
Author: SlickRemix
Author URI: http://slickremix.com/
Text Domain: feed-them-social
Domain Path: /languages
Requires at least: wordpress 3.6.0
Tested up to: WordPress 4.3.1
Stable tag: 1.9.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @package    			Feed Them
 * @category   			Core
 * @author     		 SlickRemix
 * @copyright  			Copyright (c) 2012-2015 SlickRemix
If you need support or want to tell us thanks please contact us at support@slickremix.com or use our support forum on slickremix.com.
*/
define( 'FEED_THEM_PLUGIN_PATH', plugins_url());
// Makes sure the plugin is defined before trying to use it
if ( ! function_exists( 'is_plugin_active' ) )
 require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
$fts_plugin_rel_url = plugin_dir_path( __FILE__ );
// Returns current plugin version. SRL added
// @return string Plugin version
function ftsystem_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}
// Define minimum premium version allowed to be active with Free Version
function fts_versions_needed(){
	$fts_versions_needed = array(
		'feed-them-premium/feed-them-premium.php' => '1.5.3',
		'fts-bar/fts-bar.php' => '1.0.8',
		'feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php' => '1.0.0',
	);
	return $fts_versions_needed;
}
// Make sure php version is greater than 5.3
if ( function_exists( 'phpversion' ) )					
	$phpversion = phpversion();
	$phpcheck = '5.2.9';
if ($phpversion > $phpcheck) {

//Error Handler
include( $fts_plugin_rel_url.'includes/error-handler.php' );
new feedthemsocial\fts_error_handler();
	
function fts_action_init()
{
// Localization
load_plugin_textdomain('feed-them-social', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
// Add actions
add_action('init', 'fts_action_init');
// Include admin
include( $fts_plugin_rel_url.'admin/feed-them-system-info.php' );
include( $fts_plugin_rel_url.'admin/feed-them-settings-page.php' );
include( $fts_plugin_rel_url.'admin/feed-them-facebook-style-options-page.php' );
include( $fts_plugin_rel_url.'admin/feed-them-twitter-style-options-page.php' );
include( $fts_plugin_rel_url.'admin/feed-them-instagram-style-options-page.php' );
include( $fts_plugin_rel_url.'admin/feed-them-pinterest-style-options-page.php' );
include( $fts_plugin_rel_url.'admin/feed-them-youtube-style-options-page.php' );
// Include core files and classes
include( $fts_plugin_rel_url.'includes/feed-them-functions.php' );
$load_fts = 'feedthemsocial\feed_them_social_functions';
$load_fts = new $load_fts;
$load_fts->init();
// Include feeds
include( $fts_plugin_rel_url.'feeds/facebook/facebook-feed.php' );
include( $fts_plugin_rel_url.'feeds/facebook/facebook-feed-post-types.php' );
$load_fb_fts = 'feedthemsocial\FTS_Facebook_Feed';
new $load_fb_fts;
include_once( $fts_plugin_rel_url.'feeds/twitter/twitter-feed.php' );
$load_tw_fts = 'feedthemsocial\FTS_Twitter_Feed';
new $load_tw_fts;
include_once( $fts_plugin_rel_url.'feeds/instagram/instagram-feed.php' );
include_once( $fts_plugin_rel_url.'feeds/pinterest/pinterest-feed.php' );
include_once( $fts_plugin_rel_url.'feeds/twitter/vine-feed.php' );
$load_vn_fts = 'feedthemsocial\FTS_Vine_Feed';
new $load_vn_fts;
} // end if php version check
else  {
	// if the php version is not at least 5.3 do action
	deactivate_plugins( 'feed-them-social/feed-them.php' ); 
    	if ($phpversion < $phpcheck) {
	add_action( 'admin_notices', 'fts_required_php_check1' );	
	function fts_required_php_check1() {
			echo '<div class="error"><p>' . __( '<strong>Warning:</strong> Your php version is '.phpversion().'. You need to be running at least 5.3 or greater to use this plugin. Please upgrade the php by contacting your host provider. Some host providers will allow you to change this yourself in the hosting control panel too.<br/><br/>If you are hosting with BlueHost or Godaddy and the php version above is saying you are running 5.2.17 but you are really running something higher please <a href="https://wordpress.org/support/topic/php-version-difference-after-changing-it-at-bluehost-php-config?replies=4" target="_blank">click here for the fix</a>. If you cannot get it to work using the method described in the link please contact your host provider and explain the problem so they can fix it.', 'my-theme' ) . '</p></div>';
	}
   }
} // end fts_required_php_check
//Prevent errors for now WILL BE REMOVED IN FuTURE VERSIONS
class feed_them_social_functions{
	function register_settings(){}
}
if( !class_exists('EDD_SL_Plugin_Updater')) {
	class EDD_SL_Plugin_Updater{}
}
if( !class_exists('EDD_SL_Plugin_Licence_Manager')) {
	class EDD_SL_Plugin_Licence_Manager{}
}?>