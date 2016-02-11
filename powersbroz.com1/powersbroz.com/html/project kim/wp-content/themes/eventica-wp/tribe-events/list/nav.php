<?php
/**
 * List View Nav Template
 * This file loads the list view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/nav.php
 *
 * @package TribeEventsCalendar
 *
 */
global $wp_query;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<div class="tribe-events-pagination pagination clearfix">
	<h3 class="tribe-events-visuallyhidden"><?php _e( 'Events List Navigation', 'tokopress' ) ?></h3>
	<ul class="tribe-events-sub-nav">
		<!-- Left Navigation -->

		<?php if ( tribe_has_previous_event() ) : ?>
			<li class="prev page-numbers <?php echo esc_attr( tribe_left_navigation_classes() ); ?>">
				<a href="<?php echo esc_url( tribe_get_listview_prev_link() ); ?>" rel="prev"><?php _e( 'Previous Events', 'tokopress' ) ?></a>
			</li><!-- .tribe-events-nav-left -->
		<?php endif; ?>

		<!-- Right Navigation -->
		<?php if ( tribe_has_next_event() ) : ?>
			<li class="next page-numbers <?php echo esc_attr( tribe_right_navigation_classes() ); ?>">
				<a href="<?php echo esc_url( tribe_get_listview_next_link() ); ?>" rel="next"><?php _e( 'Next Events', 'tokopress' ) ?></a>
			</li><!-- .tribe-events-nav-right -->
		<?php endif; ?>
	</ul>
</div>