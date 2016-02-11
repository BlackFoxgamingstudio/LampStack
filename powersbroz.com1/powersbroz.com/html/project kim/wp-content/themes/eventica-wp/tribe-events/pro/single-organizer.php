<?php
/**
 * Single Organizer Template
 * The template for an organizer. By default it displays organizer information and lists 
 * events that occur with the specified organizer.
 *
 * This view contains the filters required to create an effective single organizer view.
 *
 * You can recreate an ENTIRELY new single organizer view by doing a template override, and placing
 * a single-organizer.php file in a tribe-events/pro/ directory within your theme directory, which
 * will override the /views/single-organizer.php. 
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

$organizer_id = get_the_ID();

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

<?php while( have_posts() ) : the_post(); ?>
<div class="tribe-events-organizer">

	<div class="pagination clearfix">
		<a class="prev page-numbers" href="<?php echo tribe_get_events_link() ?>" rel="bookmark"><?php _e( 'Back to Events', 'tokopress' ) ?></a>
	</div>

	<?php do_action( 'tribe_events_single_organizer_before_organizer' ) ?>

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

			<?php do_action( 'tribe_events_single_organizer_before_the_meta'); ?>
			<?php $phone = get_post_meta( $organizer_id, '_OrganizerPhone', true ); ?>
			<?php $email = get_post_meta( $organizer_id, '_OrganizerEmail', true ); ?>
			<?php $website_url = get_post_meta( $organizer_id, '_OrganizerWebsite', true ); ?>

			<div class="tribe-events-meta-group tribe-events-meta-group-organizer">
				<h3 class="tribe-events-single-section-title"> <?php echo tribe_get_organizer_label_singular(); ?> </h3>
				<table>
					<?php if ( ! empty( $phone ) ): ?>
						<tr>
							<th> <?php _e( 'Phone:', 'tokopress' ) ?> </th>
							<td class="tel"> <?php echo wp_kses_data( $phone ) ?> </td>
						</tr>
					<?php endif ?>

					<?php if ( ! empty( $email ) ): ?>
						<tr>
							<th> <?php _e( 'Email:', 'tokopress' ) ?> </th>
							<td class="email"> <?php echo wp_kses_data( $email ) ?> </td>
						</tr>
					<?php endif ?>

					<?php if ( ! empty( $website_url ) ): ?>
						<tr>
							<th> <?php _e( 'Website:', 'tokopress' ) ?> </th>
							<td class="url"> 
								<a href="<?php echo esc_url( $website_url ); ?>">
									<?php _e( 'Visit Organizer Website', 'tokopress' ); ?>
								</a> 
							</td>
						</tr>
					<?php endif ?>
				</table>
			</div>
			<?php do_action( 'tribe_events_single_organizer_after_the_meta' ) ?>
		</div>

		<div class="clearfix"></div>
		
	</div><!-- #tribe-events-content -->

	<?php do_action( 'tribe_events_single_organizer_after_organizer' ) ?>

	<?php if( class_exists('Tribe__Events__Pro__Main')) : ?>
		<!-- Upcoming event list -->
		<?php do_action('tribe_events_single_organizer_before_upcoming_events') ?>
		<?php // Use the 'tribe_events_single_organizer_posts_per_page' to filter the 
		 	  // number of events to display beneath the venue info on the venue page.
		?> 
		<?php echo tribe_include_view_list( array( 'organizer'    => get_the_ID(),
		                                           'eventDisplay' => 'list',
				apply_filters( 'tribe_events_single_organizer_posts_per_page', 100 )
			) ) ?>
		<?php do_action('tribe_events_single_organizer_after_upcoming_events') ?>
	<?php endif; ?>

</div><!-- .tribe-events-organizer -->
<?php do_action( 'tribe_events_single_organizer_after_template' ) ?>
<?php endwhile; ?>

<?php tribe_get_template_part( 'custom/wrapper-end' ); ?>
