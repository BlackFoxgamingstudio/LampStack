<?php
$args = array(
	'post_status'=>'publish',
	'post_type'=> 'testimonial',
	'posts_per_page'=> '-1',
	'orderby'=>'date',
	'order'=>'DESC',
	'ignore_sticky_posts' => true
	);
$the_testimonial = new WP_Query( $args );
?>

<?php if( $the_testimonial->have_posts() ) : ?>
	<div class="home-testimonials">
		<div class="container">
			<div class="testimonial-wrap">
				<div class="testimonial-title">
					<?php if( "" != of_get_option( 'tokopress_home_testimonials' ) ) : ?>
						<h2><?php echo esc_attr( of_get_option( 'tokopress_home_testimonials' ) ); ?></h2>
					<?php else : ?>
						<h2><?php _e( 'Testimonials', 'tokopress' ); ?></h2>
					<?php endif; ?>
				</div>

				<div class="testimonial-loop">
					<?php while ( $the_testimonial->have_posts() ) : ?>
						<?php $the_testimonial->the_post(); ?>

						<div class="testimonial-field">
							<div class="testimonial-content">
								<?php the_content(); ?>
							</div>
							<div class="testimonial-name">
								<?php the_title(); ?>
							</div>
						</div>
								
					<?php endwhile; ?>
				</div>

			</div>
		</div>
	</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>