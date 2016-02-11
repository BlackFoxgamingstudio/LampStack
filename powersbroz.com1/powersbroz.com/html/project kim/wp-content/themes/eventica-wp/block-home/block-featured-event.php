<?php if( ! class_exists( 'Tribe__Events__Main' ) ) : ?>
	<p class="nothing-plugins"><?php _e( 'Please install <strong>The Event Calendar</strong> plugin.', 'tokopress' ); ?></p>
	<?php return; ?>
<?php endif; ?>

<div class="home-featured-event">
	<?php
	$id = (int)( of_get_option( 'tokopress_home_featured_event_page' ) );
	$args = array(
		'post_status'=>'publish',
		'post_type'=>array(Tribe__Events__Main::POSTTYPE),
		//required in 3.x
		'eventDisplay'=>'custom',
	);
	if ( $id > 0 ) {
		$args['post__in'] = array( $id );
	}
	else {
		$args['posts_per_page'] = 1;
	}
	$the_featured_event = new WP_Query( $args );
	?>

	<?php if( $the_featured_event->have_posts() ) : ?>

		<div class="featured-event-wrap ">
			<div class="featured-event-title">
				<?php if( "" != of_get_option( 'tokopress_home_featured_event' ) ) : ?>
					<h2><?php echo esc_attr( of_get_option( 'tokopress_home_featured_event' ) ); ?></h2>
				<?php else : ?>
					<h2><?php _e( 'Featured Event', 'tokopress' ); ?></h2>
				<?php endif; ?>
			</div>

			<div id="tribe-events-content" class="tribe-events-single vevent clearfix">

				<?php 
				if ( function_exists('tribe_the_notices') ) {
					tribe_the_notices();
				}
				elseif ( function_exists('tribe_events_the_notices') ) {
					tribe_events_the_notices();
				}
				?>

				<?php while ( $the_featured_event->have_posts() ) : ?>
					<?php $the_featured_event->the_post(); $event_id = get_the_ID(); ?>
					
					<?php if( !of_get_option( 'tokopress_home_recent_post_disable' ) ) : ?>
						<div class="events-single-right col-sm-6 col-sm-push-6">
					<?php else : ?>
						<div class="events-single-right col-sm-8 col-sm-push-4">
					<?php endif; ?>

						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<h2 class="entry-title">
								<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
									<?php the_title() ?>
								</a>
							</h2>
							<?php if(has_post_thumbnail()) : ?>
								<div class="tribe-events-event-image">
									<?php the_post_thumbnail( 'large' ); ?>
								</div>
							<?php endif; ?>
							<div class="tribe-events-single-event-description tribe-events-content entry-content description">
								<?php the_content(); ?>
							</div>
						</div>

					</div>

					<?php if( !of_get_option( 'tokopress_home_recent_post_disable' ) ) : ?>
						<div class="events-single-left col-sm-6 col-sm-pull-6">
					<?php else : ?>
						<div class="events-single-left col-sm-4 col-sm-pull-8">
					<?php endif; ?>
						
						<?php tribe_get_template_part( 'custom/cta' ); ?>

						<?php tribe_get_template_part( 'modules/meta/details' ); ?>
						
						<?php tribe_get_template_part( 'modules/meta/venue' ); ?>
						
						<?php tribe_get_template_part( 'custom/schedule' ); ?>
						
						<?php tribe_get_template_part( 'custom/custom' ); ?>

					</div>
							
					<div class="clearfix"></div>

					<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>

				<?php endwhile; ?>
			</div>

		</div>

	<?php else : ?>
		<p class="nothing-plugins"><?php _e( 'Please use correct published Event ID.', 'tokopress' ); ?></p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>