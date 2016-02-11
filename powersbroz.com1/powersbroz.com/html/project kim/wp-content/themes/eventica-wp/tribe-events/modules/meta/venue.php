<?php
/**
 * Single Event Meta (Venue) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/venue.php
 *
 * @package TribeEventsCalendar
 */

if ( ! tribe_get_venue_id() ) {
	return;
}

$venue_id = tribe_get_venue_id();
$venue_page = get_permalink( $venue_id );

$phone = function_exists( 'tribe_get_phone' ) ? tribe_get_phone() : '';
$website_url = tribe_get_event_meta( $venue_id, '_VenueURL', true );
?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<h3 class="tribe-events-single-section-title"> <?php _e( 'Venue', 'tokopress' ) ?> </h3>
	<div class="meta-inner">
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

		<p class="author fn org">
			<?php if ( of_get_option('tokopress_events_show_venue_link') ): ?>
				<a href="<?php echo esc_url( $venue_page ); ?>">
					<?php echo tribe_get_venue() ?> 
				</a>
			<?php else : ?>
				<?php echo tribe_get_venue() ?> 
			<?php endif ?>
		</p>

		<?php if ( tribe_address_exists() ) : ?>
			<address class="tribe-events-address">
				<?php echo tribe_get_full_address(); ?>

				<?php if ( tribe_show_google_map_link() ) : ?>
					<p class="location">
						<?php echo tribe_get_map_link_html(); ?>
					</p>
				<?php endif; ?>
			</address>
		<?php endif; ?>

		<?php if ( ! empty( $phone ) ): ?>
			<p class="label"> <?php _e( 'Phone:', 'tokopress' ) ?> </p>
			<p class="tel"> <?php echo wp_kses_data( $phone ); ?> </p>
		<?php endif ?>

		<?php if ( ! empty( $website_url ) ): ?>
			<p> 
				<a href="<?php echo esc_url( $website_url ); ?>">
					<?php _e( 'Visit Venue Website', 'tokopress' ); ?>
				</a> 
			</p>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
	</div>
</div>