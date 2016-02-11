<?php
global $post;
$schedule = get_post_meta( $post->ID, '_tokopress_events_scedule', true );

if( ! trim( $schedule ) )
	return;

$schedule = explode( "\n", $schedule );

?>

<div class="tribe-events-meta-group tribe-events-meta-group-schedule">
	<h3 class="tribe-events-single-section-title">
		<?php _e( 'Schedule', 'tokopress' ); ?>
	</h3>
	<ul>
		<?php foreach ( $schedule as $item ) { if ( $item ) { ?>
			<li class="item"><?php echo esc_attr( $item ); ?></li>
		<?php } } ?>
		<li class="timeline">&nbsp;</li>
	</ul>
</div>
