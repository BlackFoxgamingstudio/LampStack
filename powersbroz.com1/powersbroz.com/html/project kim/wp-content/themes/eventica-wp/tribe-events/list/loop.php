<?php
/**
 * List View Loop
 * This file sets up the structure for the list loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/loop.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $more;
$more = false;

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

<div class="events-loop tribe-events-loop vcalendar">

	<div class="row">
		
		<?php while ( have_posts() ) : the_post(); ?>
			<?php do_action( 'tribe_events_inside_before_loop' ); ?>

			<!-- Month / Year Headers -->
			<?php tribe_events_list_the_date_headers(); ?>

			<!-- Event  -->
			<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?> <?php echo esc_attr( $class ); ?>">
				<?php tribe_get_template_part( 'list/single', 'event' ) ?>
			</div><!-- .hentry .vevent -->


			<?php do_action( 'tribe_events_inside_after_loop' ); ?>
		<?php endwhile; ?>

	</div>

</div><!-- .tribe-events-loop -->
