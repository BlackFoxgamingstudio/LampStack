<?php
global $post;
$custom = get_post_meta( $post->ID, '_tokopress_events_custom', true );

if( ! trim( $custom ) )
	return;

?>

<div class="tribe-events-meta-group tribe-events-meta-group-custom">
	<?php echo wpautop( $custom ); ?>
</div>
