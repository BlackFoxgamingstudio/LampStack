<?php
/**
 * Week View Nav
 * This file loads the week view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/week/nav.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<div class="tribe-events-pagination pagination clearfix">

	<?php do_action( 'tribe_events_before_nav' ) ?>

	<h3 class="tribe-events-visuallyhidden"><?php _e( 'Week Navigation', 'tokopress' ) ?></h3>

	<ul class="tribe-events-sub-nav">
		<li class="prev page-numbers tribe-events-nav-previous">
			<?php echo tribe_previous_week_link() ?>
		</li>
		<!-- .tribe-events-nav-previous -->
		<li class="next page-numbers tribe-events-nav-next">
			<?php echo tribe_next_week_link() ?>
		</li>
		<!-- .tribe-events-nav-next -->
	</ul><!-- .tribe-events-sub-nav -->

	<?php do_action( 'tribe_events_after_nav' ) ?>

</div>
