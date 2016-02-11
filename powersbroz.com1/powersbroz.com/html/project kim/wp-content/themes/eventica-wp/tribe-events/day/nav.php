<?php
/**
 * Day View Nav
 * This file contains the day view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/day/nav.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<div class="tribe-events-pagination pagination clearfix">
	<h3 class="tribe-events-visuallyhidden"><?php _e( 'Day Navigation', 'tokopress' ) ?></h3>
	<ul class="tribe-events-sub-nav">

		<!-- Previous Page Navigation -->
		<li class="prev page-numbers tribe-events-nav-previous"><?php tribe_the_day_link( 'previous day', __( 'Previous Day', 'tokopress' ) ) ?></li>

		<!-- Next Page Navigation -->
		<li class="next page-numbers tribe-events-nav-next"><?php tribe_the_day_link( 'next day', __( 'Next Day', 'tokopress' ) ) ?></li>

	</ul>
</div>