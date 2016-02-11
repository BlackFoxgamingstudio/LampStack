<?php
/**
 * Map View Nav
 * This file contains the map view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/map/nav.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<div class="tribe-events-pagination pagination clearfix">
	<h3 class="tribe-events-visuallyhidden"><?php _e( 'Events List Navigation', 'tokopress' ) ?></h3>
	<ul class="tribe-events-sub-nav">
		<!-- Left Navigation -->

		<?php if ( tribe_has_previous_event() ) : ?>
			<li class="prev page-numbers tribe-events-nav-previous">
				<a href="#" class="tribe_map_paged"><?php _e( 'Previous Events', 'tokopress' ) ?></a>
			</li><!-- .tribe-events-nav-left -->
		<?php endif; ?>

		<!-- Right Navigation -->
		<?php if ( tribe_has_next_event() ) : ?>
			<li class="next page-numbers tribe-events-nav-next">
				<a href="#" class="tribe_map_paged"><?php _e( 'Next Events', 'tokopress' ) ?></a>
			</li><!-- .tribe-events-nav-right -->
		<?php endif; ?>
	</ul>
</div>
