<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$event_id = get_the_ID();

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

<div id="tribe-events-content" class="tribe-events-single vevent hentry">

	<!-- Notices -->
	<?php 
	if ( function_exists('tribe_the_notices') ) {
		tribe_the_notices();
	}
	elseif ( function_exists('tribe_events_the_notices') ) {
		tribe_events_the_notices();
	}
	?>

	<div class="events-single-right <?php echo esc_attr( $right_class ); ?>">

		<?php while ( have_posts() ) :  the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( !$tp_title ) : ?>
					<h2 class="entry-title">
						<?php the_title() ?>
					</h2>
				<?php endif; ?>

				<!-- Event featured image, but exclude link -->
				<?php if(has_post_thumbnail()) : ?>
					<div class="tribe-events-event-image">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<!-- Event content -->
				<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>

				<div class="tribe-events-single-event-description tribe-events-content entry-content description">
					<?php the_content(); ?>
				</div>
				<!-- .tribe-events-single-event-description -->
				<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>

			</div> <!-- #post-x -->
		<?php endwhile; ?>

	</div>

	<div class="events-single-left <?php echo esc_attr( $left_class ); ?>">
			
		<?php tribe_get_template_part( 'custom/cta' ); ?>

		<!-- Event meta -->
		<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>

		<?php do_action( 'tribe_events_single_event_meta_primary_section_start' ); ?>

		<?php tribe_get_template_part( 'modules/meta/details' ); ?>

		<?php do_action( 'tribe_events_single_event_meta_primary_section_end' ); ?>

		<?php tribe_get_template_part( 'modules/meta/venue' ); ?>
		
		<?php tribe_get_template_part( 'modules/meta/map' ); ?>

		<?php tribe_get_template_part( 'custom/schedule' ); ?>

		<?php tribe_get_template_part( 'custom/custom' ); ?>

		<?php 
		// Include organizer meta if appropriate
		if ( tribe_has_organizer() ) {
			tribe_get_template_part( 'modules/meta/organizer' );
		} 
		?>

	</div>

	<div class="clearfix"></div>

	<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php _e( 'Event Navigation', 'tokopress' ) ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
		</ul>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-footer -->
	
</div><!-- #tribe-events-content -->

<?php tribe_get_template_part( 'custom/attendees' ); ?>

<?php tribe_get_template_part( 'custom/gallery' ); ?>

<?php tribe_get_template_part( 'custom/related-event' ); ?>
	
<?php // if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>

<?php
if ( of_get_option( 'tokopress_events_show_single_comment' ) && ( comments_open() || '0' != get_comments_number() ) )  :
    comments_template();
endif;
?>

<?php tribe_get_template_part( 'custom/wrapper-end' ); ?>
