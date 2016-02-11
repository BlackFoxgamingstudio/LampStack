<?php if( ! class_exists( 'Tribe__Events__Main' ) ) : ?>
	<p class="nothing-plugins"><?php _e( 'Please install <strong>The Event Calendar</strong> plugin.', 'tokopress' ); ?></p>
	<?php return; ?>
<?php endif; ?>

<?php
$numbers = intval( of_get_option( 'tokopress_home_slider_numbers' ) );
if ( $numbers < 1 )
	$numbers = 4;

$ids = of_get_option( 'tokopress_home_slider_ids' );
$args = array(
	'post_status'=>'publish',
	'post_type'=>array(Tribe__Events__Main::POSTTYPE),
	);
if ( ! empty( $ids ) ) {
	$ids = explode( ",", $ids );
	$args['post__in'] = $ids;
	$args['eventDisplay'] = 'custom';

	if ( function_exists( 'tokopress_tribe_events_pre_get_posts' ) )
		add_action( 'tribe_events_pre_get_posts', 'tokopress_tribe_events_pre_get_posts' );
	$the_slider_events = new WP_Query( $args );
	if ( function_exists( 'tokopress_tribe_events_pre_get_posts' ) )
		remove_action( 'tribe_events_pre_get_posts', 'tokopress_tribe_events_pre_get_posts' );
}
else {
	$args['posts_per_page'] = $numbers;
	$args['eventDisplay'] = 'list';
	$args['orderby'] = 'event_date';
	$args['order'] = 'ASC';

	if ( $category = of_get_option('tokopress_home_slider_category') ) {
		$term = term_exists( $category, 'tribe_events_cat' );
		if ($term !== 0 && $term !== null) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'tribe_events_cat',
					'field' => 'id',
					'terms' => array( $term['term_id'] ),
					'operator' => 'IN'
				)
			);
		}
		else {
			echo '<p">'.sprintf( __('"%s" event category does not exist.', 'tokopress'), $category ).'</p>';
			return;
		}
	}

	$the_slider_events = new WP_Query( $args );
}
?>

<?php if( $the_slider_events->have_posts() ) : ?>
<?php $class = $the_slider_events->post_count > 1 ? 'home-slider-events-active' : ''; ?>
<div class="home-slider-events clearfix <?php echo esc_attr( $class ); ?>">
	<?php while ( $the_slider_events->have_posts() ) : ?>
		<?php $the_slider_events->the_post(); ?>
		<?php $img = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'full' ); ?>
		<?php if ( $img ) : ?>
			<div class="slide-event item" style="background-image:url(<?php echo esc_url($img); ?>)">
		<?php else : ?>
			<div class="slide-event item">
		<?php endif; ?>
			<div class="container">
			<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
			<div class="slide-event-detail">
				<h2 class="slide-event-title">
					<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
						<?php the_title() ?>
					</a>
				</h2>
				<div class="slide-event-cta">
					<div class="slide-event-cta-date">
						<span class="mm"><?php echo tribe_get_start_date( null, false, 'F' ) ?></span>
						<span class="dd"><?php echo tribe_get_start_date( null, false, 'd' ) ?></span>
						<span class="yy"><?php echo tribe_get_start_date( null, false, 'Y' ) ?></span>
					</div>
					<a class="btn" href="<?php echo tribe_get_event_link() ?>">
						<?php if( "" != of_get_option( 'tokopress_home_slide_button' ) ) : ?>
							<?php echo esc_attr( of_get_option( 'tokopress_home_slide_button' ) ); ?>
						<?php else : ?>
							<?php _e( 'Detail', 'tokopress' ); ?>
						<?php endif; ?>
					</a>
				</div>
				<div class="slide-event-venue">
					<div class="slide-event-venue-name">
						<?php echo tribe_get_venue(); ?>
					</div>
					<?php if ( tribe_address_exists() ) : ?>
						<div class="slide-event-venue-address">
							<?php echo tribe_get_full_address(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			</div>
			</div>
			</div>
		</div>
	<?php endwhile; ?>
</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>