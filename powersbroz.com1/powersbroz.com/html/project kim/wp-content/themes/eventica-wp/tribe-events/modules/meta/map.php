<?php
/**
 * Single Event Meta (Map) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */

if ( !tribe_embed_google_map() )
	return;

$map = apply_filters( 'tribe_event_meta_venue_map', tribe_get_embedded_map() );
if ( empty( $map ) ) {
	return;
}
?>

<div class="tribe-events-meta-group tribe-events-meta-group-gmap">
	<div class="tribe-events-venue-map">
		<?php
		do_action( 'tribe_events_single_meta_map_section_start' );
		echo wp_kses_post( $map );
		do_action( 'tribe_events_single_meta_map_section_end' );
		?>
	</div>
</div>
