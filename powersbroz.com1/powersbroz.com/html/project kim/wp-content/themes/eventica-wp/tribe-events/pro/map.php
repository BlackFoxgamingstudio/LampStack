<?php
/**
 * Map View Template
 * The wrapper template for map view.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/map.php
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

<div class="container">
	<!-- Google Map Container -->
	<?php tribe_get_template_part( 'pro/map/gmap-container' ) ?>
</div>

<?php tribe_get_template_part( 'custom/wrapper-start' ); ?>

<?php do_action( 'tribe_events_before_template' ); ?>

<!-- Main Events Content -->
<?php tribe_get_template_part( 'pro/map/content' ) ?>

<div class="tribe-clear"></div>

<?php do_action( 'tribe_events_after_template' ) ?>

<?php tribe_get_template_part( 'custom/wrapper-end' ); ?>
