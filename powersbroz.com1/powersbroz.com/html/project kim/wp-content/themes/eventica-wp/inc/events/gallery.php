<?php 

/**
 * Enqueue JS & CSS
 */
add_action( 'admin_enqueue_scripts', 'tokopress_metabox_event_gallery_enqueue' );
function tokopress_metabox_event_gallery_enqueue() {
	if ( get_post_type() != 'tribe_events' )
		return;

	wp_enqueue_style( 'tokopress-event-gallery', THEME_URI . '/inc/events/assets/gallery.css', null, '' );
	wp_enqueue_script( 'tokopress-event-gallery', THEME_URI . '/inc/events/assets/gallery.js', array( 'jquery' ), '', true );
}

/**
 * Metaboxes
 */
add_action( 'admin_menu', 'tokopress_metabox_event_gallery_add' );
if ( ! function_exists( 'tokopress_metabox_event_gallery_add' ) ) :
function tokopress_metabox_event_gallery_add() { 
	add_meta_box( 'postgallerydiv', __( 'Event Gallery', 'tokopress' ), 'tokopress_metabox_event_gallery_box', 'tribe_events', 'side' );
}
endif;

/**
 * Gallery images metabox
 * Credits: WooCommerce Product Gallery Images
 */
if ( ! function_exists( 'tokopress_metabox_event_gallery_box' ) ) :
function tokopress_metabox_event_gallery_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'tokopress_event_gallery_nonce' );
	?>
	<div id="gallery_images_container">
		<ul class="gallery_images">
			<?php
				$gallery_image = get_post_meta( $post->ID, '_format_gallery_ids', true );

				$attachments = array_filter( explode( ',', $gallery_image ) );

				if ( $attachments ) {
					foreach ( $attachments as $attachment_id ) {
						echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
							' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
							<ul class="actions">
								<li><a href="#" class="delete">' . __( 'Delete', 'tokopress' ) . '</a></li>
							</ul>
						</li>';
					}
				}
			?>
		</ul>

		<input type="hidden" id="gallery_image_ids" name="gallery_image_ids" value="<?php echo esc_attr( $gallery_image ); ?>" />

	</div>
	<p class="add_gallery_images hide-if-no-js">
		<a href="#" data-choose="<?php _e( 'Add Images to Gallery', 'tokopress' ); ?>" data-update="<?php _e( 'Add to gallery', 'tokopress' ); ?>" data-delete="<?php _e( 'Delete image', 'tokopress' ); ?>" data-text="<?php _e( 'Delete', 'tokopress' ); ?>"><?php _e( 'Add gallery images', 'tokopress' ); ?></a>
	</p>
	<?php
}
endif;

/**
 * Save gallery images
 */
add_action( 'save_post', 'tokopress_metabox_event_gallery_save', 10, 2 );
if ( ! function_exists( 'tokopress_metabox_event_gallery_save' ) ) :
function tokopress_metabox_event_gallery_save( $post_id, $post ) {
	if ( !isset( $_POST['tokopress_event_gallery_nonce'] ) || !wp_verify_nonce( $_POST['tokopress_event_gallery_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	$attachment_ids = array_filter( explode( ',', esc_attr( $_POST['gallery_image_ids'] ) ) );
	update_post_meta( $post_id, '_format_gallery_ids', implode( ',', $attachment_ids ) );

}
endif;
