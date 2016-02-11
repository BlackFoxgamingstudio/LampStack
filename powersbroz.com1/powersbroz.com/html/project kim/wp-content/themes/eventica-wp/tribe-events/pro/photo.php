<?php
/**
 * Photo View Template
 * The wrapper template for photo view.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/photo.php
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

<div class="tribe-events-photo-intro">
</div>

<?php tribe_get_template_part( 'pro/photo/content' ) ?>

<?php do_action( 'tribe_events_after_template' ) ?>

<?php tribe_get_template_part( 'custom/wrapper-end' ); ?>
