<?php
/**
 * Default Events Template
 * This file is the basic wrapper template for all the views if 'Default Events Template'
 * is selected in Events -> Settings -> Template -> Events Template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $tp_sidebar, $tp_content_class, $tp_title;
if ( is_singular() && of_get_option( 'tokopress_events_hide_single_sidebar' ) ) {
	$tp_sidebar = false;
	$tp_content_class = 'col-md-12';
}
elseif ( !is_singular() && of_get_option( 'tokopress_events_hide_catalog_sidebar' ) ) {
	$tp_sidebar = false;
	$tp_content_class = 'col-md-12';
}
else {
	$tp_sidebar = true;
	$tp_content_class = 'col-md-9';
}
if ( is_singular() && of_get_option( 'tokopress_events_hide_single_title' ) ) {
	$tp_title = false;
}
elseif ( !is_singular() && of_get_option( 'tokopress_events_hide_catalog_title' ) ) {
	$tp_title = false;
}
else {
	$tp_title = true;
}

/* Globally hide page title */
if( of_get_option( 'tokopress_page_title_disable' ) ) {
	$tp_title = false;
}

get_header(); ?>

<?php if ( $tp_title) tribe_get_template_part( 'custom/page-title' ); ?>

<?php tribe_get_view(); ?>

<?php get_footer(); ?>
