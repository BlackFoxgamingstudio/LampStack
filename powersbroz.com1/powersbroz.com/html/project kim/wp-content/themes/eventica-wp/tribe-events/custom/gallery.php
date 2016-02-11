<?php
global $post;
$gallery_image = get_post_meta( $post->ID, '_format_gallery_ids', true );
$attachments = array_filter( explode( ',', $gallery_image ) );

if ( empty( $attachments ) )
	return;
?>

<div class="event-gallery-wrap">
	<div class="event-gallery-title">
		<h2><?php _e( 'Event Gallery', 'tokopress' ); ?></h2>
	</div>

	<div class="event-gallery-images">
		<div class="row">
			<?php foreach ( $attachments as $attachment_id ) : ?>
				<?php
				$image_link = wp_get_attachment_url( $attachment_id );
				$image_title = esc_attr( get_the_title( $attachment_id ) );
				?>
				<div class="gallery-image col-md-2 col-xs-3">
					<a href="<?php echo esc_url( $image_link ) ?>" title="<?php echo esc_attr( $image_title ); ?>" >
						<?php echo wp_get_attachment_image( $attachment_id, 'thumbnail' ); ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

</div>
