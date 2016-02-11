<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'get_header', 'tokopress_events_action_get_header' );
function tokopress_events_action_get_header() {

	/* Hide Event Calendar Pro Related Events, use our theme */
	if ( class_exists('Tribe__Events__Pro__Main') ) {
		tokopress_remove_filter_class( 'tribe_events_single_event_after_the_meta', 'Tribe__Events__Pro__Main', 'register_related_events_view', 10 );
	}

	/* Hide Event Calendar Pro Additional Fields, use our theme */
	if ( class_exists('Tribe__Events__Pro__Single_Event_Meta') ) {
		tokopress_remove_filter( 'tribe_events_single_event_meta_primary_section_end', 'additional_fields', 10 );
	}
}

add_filter( 'tribe_meta_event_tags', 'tokopress_events_overide_markup_tags', 10, 3 );
function tokopress_events_overide_markup_tags( $list, $label, $separator ) {
	$list = get_the_term_list( get_the_ID(), 'post_tag', '<tr><th>' . $label . '</th><td class="tribe-event-tags">', $separator, '</td></tr>' );

	return $list;
}

add_action( 'woocommerce_cart_is_empty', 'tokopress_events_cart_return_to_events', 99 );
function tokopress_events_cart_return_to_events() {
	echo '<p class="return-to-events"><a class="button" href="'.tribe_get_events_link().'">'.__( 'Return To Events', 'tokopress' ).'</a></p>';
}

function tokopress_tribe_events_pre_get_posts( $query ) {
	$query->set( 'orderby', 'post__in' );
	$query->set( 'order', 'ASC' );
	return $query;
}

add_filter( 'tribe_wootickets_stylesheet_url', 'tokopress_events_tribe_wootickets_stylesheet_url' );
function tokopress_events_tribe_wootickets_stylesheet_url( $stylesheet_url ) {
	return false;
}
