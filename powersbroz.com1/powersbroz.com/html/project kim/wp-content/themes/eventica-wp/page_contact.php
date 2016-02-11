<?php
/**
 * Template Name: Contact
 * Description: The template for displaying Contact Form page. 
 *
 * WARNING: This file is part of the Eventica parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category Page
 * @package  Templates
 * @author   TokoPress
 * @link     http://www.tokopress.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $tp_sidebar, $tp_content_class;

if( "" != of_get_option( 'tokopress_disable_contact_sidebar' ) ) {
	$tp_sidebar = false;
	$tp_content_class = 'col-md-12';
}
else {
	$tp_sidebar = true;
	$tp_content_class = 'col-md-9';
}
?>

<?php get_header(); ?>

	<?php if( ! of_get_option( 'tokopress_page_title_disable' ) ) : ?>
		<?php if( "" == of_get_option( 'tokopress_disable_contact_title' ) ) get_template_part( 'block-page-title' ); ?>
	<?php endif; ?>

	<div id="main-content">
		
		<div class="container">
			<div class="row">
				
				<div class="<?php echo esc_attr( $tp_content_class ); ?>">
					
					<?php if ( have_posts() ) : ?>

						<div class="main-wrapper">

							<?php do_action( 'tokopress_before_content' ); ?>

								<?php if( !of_get_option( 'tokopress_disable_contact_map' ) ) : ?>

								<?php 
									$latitude = ( "" != of_get_option( 'tokopress_contact_lat' ) ) ? of_get_option( 'tokopress_contact_lat' ) : -6.903932;
									$longitude = ( "" != of_get_option( 'tokopress_contact_long' ) ) ? of_get_option( 'tokopress_contact_long' ) : 107.610344;

									$get_marker_title = of_get_option( 'tokopress_contact_marker_title' );
									$get_marker_content = of_get_option( 'tokopress_contact_marker_desc' );
									$marker_title = ( "" != $get_marker_title ) ? $get_marker_title : 'Marker Title';
										$clear_marker_title = str_replace( "\r\n", "<br/>", $marker_title );

									$marker_content = ( "" != $get_marker_content ) ? $get_marker_content : 'Marker Content';
										$clear_marker_content = str_replace( "\r\n", "<br/>", $marker_content );
								?>

								<script type="text/javascript">
									var map;
									var map_latitude = <?php echo esc_js( $latitude ); ?>;
									var map_longitude = <?php echo esc_js( $longitude ); ?>;
									var markerTitle = "<?php echo esc_js( $clear_marker_title ); ?>";
									var markerContent = "<h1><?php echo esc_js( $clear_marker_title ); ?></h1><p><?php echo esc_js( $clear_marker_content ); ?></p>";

									jQuery(document).ready(function(){map=new GMaps({el:"#map",lat:map_latitude,lng:map_longitude,zoom:15,scrollwheel:false});map.addMarker({lat:map_latitude,lng:map_longitude,title:markerTitle,infoWindow:{content:markerContent}})})
								</script>

								<div id="map" style="height:500px;"></div>

									<?php $contact_class = 'page-contact no-margin'; ?>

								<?php else: ?>
									<?php $contact_class = 'page-contact'; ?>
								<?php endif; ?>
				
							<?php while ( have_posts() ) : the_post(); ?>
								
								<article id="page-<?php the_ID(); ?>" <?php post_class( $contact_class . ' clearfix' ); ?>>
	
									<div class="inner-page">
										<?php if( has_post_thumbnail() ) : ?>
											<div class="post-thumbnail">
												<?php the_post_thumbnail(); ?>
											</div>
										<?php endif; ?>

										<div class="col-md-12">
											<div class="post-summary">
												<?php if( ! of_get_option( 'tokopress_page_title_disable' ) ) : ?>
												    <h2 class="post-title screen-reader-text"><?php the_title(); ?></h2>
												<?php else : ?>
												    <h1 class="post-title"><?php the_title(); ?></h2>
												<?php endif; ?>

												<?php the_content(); ?>

												<?php wp_link_pages( array( 'before' => '<p class="page-link"><span>' . __( 'Pages:', 'tokopress' ) . '</span>', 'after' => '</p>' ) ); ?>
											</div>
										</div>
									</div>

								</article>

							<?php endwhile; ?>

							<?php
								$current_page_id = $wp_query->get_queried_object_id();
								
								$args = array();

								$email = tokopress_get_post_meta( '_toko_contact_email', $current_page_id );
								if ( $email ) $args['email'] = $email;

								$subject = tokopress_get_post_meta( '_toko_contact_subject', $current_page_id );
								if ( $subject ) $args['subject'] = $subject;

								$sendcopy = tokopress_get_post_meta( '_toko_contact_sendcopy', $current_page_id );
								if ( $sendcopy ) $args['sendcopy'] = $sendcopy;

								$button_text = tokopress_get_post_meta( '_toko_contact_button', $current_page_id );
								if ( $button_text ) $args['button_text'] = $button_text;

								echo tokopress_get_contact_form( $args );
								?>
								<!-- Contact Form -->

							<?php do_action( 'tokopress_after_content' ); ?>

						</div>
						
					<?php else : ?>

						<?php get_template_part( 'content', 'none' ); ?>

					<?php endif; ?>

				</div>

				<?php if ( $tp_sidebar ) get_sidebar(); ?>

			</div>
		</div>
	</div>

<?php get_footer(); ?>