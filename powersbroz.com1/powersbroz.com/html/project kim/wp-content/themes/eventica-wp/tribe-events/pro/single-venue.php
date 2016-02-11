<?php
/**
 * Single Venue Template
 * The template for a venue. By default it displays venue information and lists 
 * events that occur at the specified venue.
 *
 * This view contains the filters required to create an effective single venue view.
 *
 * You can recreate an ENTIRELY new single venue view by doing a template override, and placing
 * a single-venue.php file in a tribe-events/pro/ directory within your theme directory, which
 * will override the /views/single-venue.php. 
 *
 * You can use any or all filters included in this file or create your own filters in 
 * your functions.php. In order to modify or extend a single filter, please see our
 * readme on templates hooks and filters (TO-DO)
 *
 * @package TribeEventsCalendarPro
 *
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$venue_id = get_the_ID();

global $tp_sidebar, $tp_content_class, $tp_title;
if ( $tp_sidebar ) {
	$right_class = 'col-sm-7 col-sm-push-5';
	$left_class = 'col-sm-5 col-sm-pull-7';
}
else {
	$right_class = 'col-sm-8 col-sm-push-4';
	$left_class = 'col-sm-4 col-sm-pull-8';
}
?>

<?php tribe_get_template_part( 'custom/wrapper-start' ); ?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="tribe-events-venue">

	<div class="pagination clearfix">
		<a class="prev page-numbers" href="<?php echo tribe_get_events_link() ?>" rel="bookmark"><?php _e( 'Back to Events', 'tokopress' ) ?></a>
	</div>

	<div id="tribe-events-content" class="tribe-events-single vevent hentry">

		<div class="events-single-right <?php echo esc_attr( $right_class ); ?>">

			<div class="tribe_events">

				<!-- Event featured image, but exclude link -->
				<?php if(has_post_thumbnail()) : ?>
					<div class="tribe-events-event-image">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<!-- Event content -->
				<div class="tribe-events-single-event-description tribe-events-content entry-content description">
					<?php the_content(); ?>
				</div>

				<?php if( !has_post_thumbnail() && !get_the_content() ) : ?>
					<div class="tribe-events-event-image">
						<img src="//placehold.it/1024x768" alt="<?php the_title(); ?>" class="no-thumb">
					</div>
				<?php endif; ?>

			</div>

		</div>

		<div class="events-single-left <?php echo esc_attr( $left_class ); ?>">
			
			<?php do_action('tribe_events_single_organizer_before_title') ?>
			<h2 class="entry-title author fn org">
				<?php the_title() ?>
			</h2>
			<?php do_action('tribe_events_single_organizer_after_title') ?>

			<?php do_action('tribe_events_single_venue_before_the_meta') ?>
			<?php $phone = get_post_meta( $venue_id, '_VenuePhone', true ); ?>
			<?php $website_url = get_post_meta( $venue_id, '_VenueURL', true ); ?>

			<div class="tribe-events-meta-group tribe-events-meta-group-venue">
				<h3 class="tribe-events-single-section-title"> <?php _e( 'Venue', 'tokopress' ) ?> </h3>
				<div class="meta-inner">
					<p class="author fn org">
						<?php echo tribe_get_venue() ?> 
					</p>

					<?php
					// Do we have an address?
					$address = tribe_address_exists() ? tribe_get_full_address() : '';

					// Do we have a Google Map link to display?
					$gmap_link = tribe_show_google_map_link() ? tribe_get_map_link_html() : '';
					$gmap_link = apply_filters( 'tribe_event_meta_venue_address_gmap', $gmap_link );
					?>

					<?php if ( ! empty( $address ) ) : ?>
						<address class="tribe-events-address">
							<?php echo wp_kses_data( $address ); ?>
						</address>
						<p class="location">
							<?php echo wp_kses_data( $gmap_link ); ?>
						</p>
					<?php endif ?>

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
				</div>
			</div>
			<?php do_action('tribe_events_single_venue_after_the_meta') ?>

			<?php if ( tribe_embed_google_map() && tribe_address_exists() ) : ?>
				<div class="tribe-events-meta-group tribe-events-meta-group-gmap">
					<div class="tribe-events-venue-map">
						<div class="tribe-events-map-wrap">
							<?php echo tribe_get_embedded_map( $venue_id, '100%', '200px' ); ?>
						</div><!-- .tribe-events-map-wrap -->
					</div>
				</div>
			<?php endif; ?>

		</div>

		<div class="clearfix"></div>
		
	</div><!-- #tribe-events-content -->

	<?php if( class_exists('Tribe__Events__Pro__Main')) : ?>
		<!-- Upcoming event list -->
		<?php do_action('tribe_events_single_venue_before_upcoming_events') ?>
		<?php // Use the 'tribe_events_single_venue_posts_per_page' to filter the 
		 	  // number of events to display beneath the venue info on the venue page.
		?>
		<?php
		// @todo rewrite + use tribe_venue_upcoming_events()
		echo tribe_include_view_list( array(
			'venue'          => $venue_id,
			'eventDisplay'   => 'list',
			'posts_per_page' => apply_filters( 'tribe_events_single_venue_posts_per_page', 100 )
		) ) ?>
		<?php do_action('tribe_events_single_venue_after_upcoming_events') ?>
	<?php endif; ?>
	
</div><!-- .tribe-events-venue -->
<?php endwhile; ?>

<?php tribe_get_template_part( 'custom/wrapper-end' ); ?>
