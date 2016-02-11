<?php
/**
 * Events Options Settings
 *
 * @package Theme Options
 * @author Tokopress
 *
 */

function tokopress_event_settings( $options ) {
	$options[] = array(
		'name' 	=> __( 'Events', 'tokopress' ),
		'type' 	=> 'heading'
	);

	$options[] = array(
		'name' => __( 'Events - Catalog Page', 'tokopress' ),
		'type' => 'info'
	);
	
		$options[] = array(
			'name' => __( 'Events Page Title', 'tokopress' ),
			'desc' => __( 'DISABLE page title on events catalog page.', 'tokopress' ),
			'id' => 'tokopress_events_hide_catalog_title',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Events Sidebar', 'tokopress' ),
			'desc' => __( 'DISABLE sidebar on events catalog page.', 'tokopress' ),
			'id' => 'tokopress_events_hide_catalog_sidebar',
			'std' => '0',
			'type' => 'checkbox'
		);

	$options[] = array(
		'name' => __( 'Events - Single Event Page', 'tokopress' ),
		'type' => 'info'
	);
	
		$options[] = array(
			'name' => __( 'Single Event Page Title', 'tokopress' ),
			'desc' => __( 'DISABLE page title on single event page.', 'tokopress' ),
			'id' => 'tokopress_events_hide_single_title',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Single Event Sidebar', 'tokopress' ),
			'desc' => __( 'DISABLE sidebar on single event page.', 'tokopress' ),
			'id' => 'tokopress_events_hide_single_sidebar',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Single Event Comment', 'tokopress' ),
			'desc' => __( 'ENABLE comment on single event page.', 'tokopress' ),
			'id' => 'tokopress_events_show_single_comment',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Single Venue Link', 'tokopress' ),
			'desc' => __( 'LINK venue name to single venue page.', 'tokopress' ),
			'id' => 'tokopress_events_show_venue_link',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Single Organizer Link', 'tokopress' ),
			'desc' => __( 'LINK organizer name to single organizer page.', 'tokopress' ),
			'id' => 'tokopress_events_show_organizer_link',
			'std' => '0',
			'type' => 'checkbox'
		);

	return $options;
}
add_filter( 'of_options', 'tokopress_event_settings' );
