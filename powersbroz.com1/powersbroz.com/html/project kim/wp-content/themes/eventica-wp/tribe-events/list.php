<?php
/**
 * List View Template
 * The wrapper template for a list of events. This includes the Past Events and Upcoming Events views
 * as well as those same views filtered to a specific category.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $tp_sidebar, $tp_content_class, $tp_title;
?>

<div class="container <?php echo ( !$tp_title ? 'notitle': '' ); ?>">
	<?php tribe_get_template_part( 'modules/bar' ); ?>
</div>

<?php tribe_get_template_part( 'custom/wrapper-start' ); ?>

<?php do_action( 'tribe_events_before_template' ); ?>

<?php tribe_get_template_part( 'list/content' ) ?>

<div class="tribe-clear"></div>

<?php do_action( 'tribe_events_after_template' ) ?>

<?php tribe_get_template_part( 'custom/wrapper-end' ); ?>
