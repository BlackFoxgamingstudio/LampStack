<?php
/**
 * Day View Loop
 * This file sets up the structure for the day loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/loop.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $more, $post, $wp_query;
$more = false;
$current_timeslot = null;

global $tp_sidebar;
if (defined('DOING_AJAX') && DOING_AJAX) {
	if ( is_singular() && of_get_option( 'tokopress_events_hide_single_sidebar' ) ) {
		$tp_sidebar = false;
	}
	elseif ( !is_singular() && of_get_option( 'tokopress_events_hide_catalog_sidebar' ) ) {
		$tp_sidebar = false;
	}
	else {
		$tp_sidebar = true;
	}
}
if ( ! $tp_sidebar ) {
	$class = 'col-md-4 col-sm-6';
}
else {
	$class = 'col-sm-6';
}
?>

<div class="tribe-events-loop hfeed vcalendar">
	<div class="row">

		<div class="tribe-events-day-time-slot">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php do_action( 'tribe_events_inside_before_loop' ); ?>

			<?php if ( $current_timeslot != $post->timeslot ) :
				$current_timeslot = $post->timeslot; ?>
				</div>
				<!-- .tribe-events-day-time-slot -->

				<div class="tribe-events-day-time-slot">
					<h5><?php echo esc_attr( $current_timeslot ); ?></h5>
				<?php endif; ?>

				<!-- Event  -->
				<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?> <?php echo esc_attr( $class ); ?>">
					<?php tribe_get_template_part( 'day/single', 'event' ) ?>
				</div>
				<!-- .hentry .vevent -->

				<?php do_action( 'tribe_events_inside_after_loop' ); ?>
		<?php endwhile; ?>

		</div>
		<!-- .tribe-events-day-time-slot -->

	</div>
</div><!-- .tribe-events-loop -->
