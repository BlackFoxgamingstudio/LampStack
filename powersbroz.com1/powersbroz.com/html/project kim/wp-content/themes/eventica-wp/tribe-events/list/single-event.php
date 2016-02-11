<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<?php

// Setup an array of venue details for use later in the template
$venue_details = array();

if ( $venue_name = tribe_get_meta( 'tribe_event_venue_name' ) ) {
	$venue_details[] = $venue_name;
}

if ( $venue_address = tribe_get_meta( 'tribe_event_venue_address' ) ) {
	$venue_details[] = $venue_address;
}
// Venue microformats
$has_venue_address = ( $venue_address ) ? ' location' : '';

// Organizer
$organizer = tribe_get_organizer();

?>

<div class="even-list-wrapper">
	<div class="event-list-wrapper-top">
		<div class="tribe-events-event-image">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php if(has_post_thumbnail()) : ?>
					<?php the_post_thumbnail( 'blog-thumbnail' ); ?>
				<?php else : ?>
					<?php 
					global $_wp_additional_image_sizes;
					$width = $_wp_additional_image_sizes['blog-thumbnail']['width'];
					$height = $_wp_additional_image_sizes['blog-thumbnail']['height'];
					?>
					<img src="//placehold.it/<?php echo esc_attr($width); ?>x<?php echo esc_attr($height); ?>" title="<?php the_title(); ?>">
				<?php endif; ?>
			</a>
		</div>

		<div class="tribe-events-event-date">
			<span class="dd"><?php echo tribe_get_start_date( null, false, 'd' ) ?></span>
			<span class="mm"><?php echo tribe_get_start_date( null, false, 'F' ) ?></span>
			<span class="yy"><?php echo tribe_get_start_date( null, false, 'Y' ) ?></span>
		</div>
	</div>

	<div class="event-list-wrapper-bottom">
		<div class="wraper-bottom-left">
			<!-- Event Title -->
			<?php do_action( 'tribe_events_before_the_event_title' ) ?>
			<h2 class="tribe-events-list-event-title entry-title summary">
				<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
					<?php the_title() ?>
				</a>
			</h2>
			<?php do_action( 'tribe_events_after_the_event_title' ) ?>

			<!-- Event Meta -->
			<?php do_action( 'tribe_events_before_the_meta' ) ?>
			<div class="tribe-events-event-meta vcard">
				<div class="author <?php echo esc_attr( $has_venue_address ); ?>">

					<?php if ( $venue_details ) : ?>
						<!-- Venue Display Info -->
						<div class="tribe-events-venue-details">
							<?php echo wp_kses_data( $venue_details[0] ); ?>
						</div> <!-- .tribe-events-venue-details -->
					<?php endif; ?>

					<div class="time-details">
						<?php tokopress_tribe_event_time(); ?>
					</div>

				</div>
			</div><!-- .tribe-events-event-meta -->
			<?php do_action( 'tribe_events_after_the_meta' ) ?>
		</div>
		<div class="wraper-bottom-right valign-wrapper">
			<a href="<?php echo tribe_get_event_link() ?>" class="more-link valign">
				<i class="fa fa-ticket"></i>
				<?php 
				if ( $cost = tribe_get_cost( null, true ) ) 
					printf( '<br/><span class="cost">%s</span>', $cost );
				?>
			</a>
		</div>
	</div>
</div>
