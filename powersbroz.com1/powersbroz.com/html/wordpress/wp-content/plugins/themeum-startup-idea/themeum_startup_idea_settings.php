<?php
/**
 * Plugins Settings List
 *
 * @author 		Themeum
 * @category 	Admin Settings
 * @package 	Startup Idea
 *-------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

add_action('admin_enqueue_scripts', function(){
	wp_enqueue_style('themeum-startup-idea', plugins_url( ) .'/themeum-startup-idea/assets/css/admin.css');
});
