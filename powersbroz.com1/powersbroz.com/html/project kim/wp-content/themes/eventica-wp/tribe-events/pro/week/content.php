<?php
/**
 * Week View Content
 * The content template for the week view. This template is also used for
 * the response that is returned on week view ajax requests.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/week/content.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<div id="tribe-events-content" class="tribe-events-week-grid tribe-clearfix">

	<!-- List Title -->
	<div class="tribe-events-page-title-wrap">
		<?php do_action( 'tribe_events_before_the_title' ); ?>
		<h2 class="tribe-events-page-title"><?php echo tribe_get_events_title() ?></h2>
		<?php do_action( 'tribe_events_after_the_title' ); ?>
	</div>

	<!-- Notices -->
	<?php 
	if ( function_exists('tribe_the_notices') ) {
		tribe_the_notices();
	}
	elseif ( function_exists('tribe_events_the_notices') ) {
		tribe_events_the_notices();
	}
	?>

	<!-- Calendar Header -->
	<?php do_action( 'tribe_events_before_header' ) ?>
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes( 'week-header' ) ?>>
	</div><!-- #tribe-events-header -->
	<?php do_action( 'tribe_events_after_header' ) ?>

	<!-- Calendar Grid -->
	<?php tribe_get_template_part( 'pro/week/loop', 'grid' ) ?>

	<!-- Calendar Footer -->
	<?php do_action( 'tribe_events_before_footer' ) ?>
	<div id="tribe-events-footer">

		<!-- Footer Navigation -->
		<?php do_action( 'tribe_events_before_footer_nav' ); ?>
		<?php tribe_get_template_part( 'pro/week/nav', 'footer' ); ?>
	</div><!-- #tribe-events-footer -->
		<?php do_action( 'tribe_events_after_footer_nav' ); ?>

	<?php do_action( 'tribe_events_after_footer' ) ?>

	<?php tribe_get_template_part( 'pro/week/mobile' ); ?>
	<?php tribe_get_template_part( 'pro/week/tooltip' ); ?>

</div><!-- #tribe-events-content -->