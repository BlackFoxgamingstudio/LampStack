<?php if( ! class_exists( 'Tribe__Events__Main' ) ) : ?>
	<p class="nothing-plugins"><?php _e( 'Please install <strong>The Event Calendar</strong> plugin.', 'tokopress' ); ?></p>
	<?php return; ?>
<?php endif; ?>

<?php

$numbers = intval( of_get_option( 'tokopress_home_upcoming_event_numbers' ) );
if ( $numbers < 1 )
	$numbers = 3;

$args = array(
	'post_type'		 => array(Tribe__Events__Main::POSTTYPE),
	'posts_per_page' => $numbers,
	'orderby'        => 'event_date',
	'order'          => 'ASC',
	//required in 3.x
	'eventDisplay'=>'list'
);
if ( $category = of_get_option('tokopress_home_upcoming_event_category') ) {
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
		// echo '<p">'.sprintf( __('"%s" event category does not exist.', 'tokopress'), $category ).'</p>';
		return;
	}
}
$ids = trim( of_get_option( 'tokopress_home_upcoming_event_exclude' ) );
if ( ! empty( $ids ) ) {
	$ids = explode( ",", $ids );
	$args['post__not_in'] = $ids;
}
$the_upcoming_events = new WP_Query( $args );
?>

<?php if( $the_upcoming_events->have_posts() ) : ?>
<div class="home-upcoming-events clearfix">
	<div class="container">

		<a class="upcoming-event-nav" href="<?php echo tribe_get_events_link() ?>">
			<?php if( "" != of_get_option( 'tokopress_home_upcoming_event_text' ) ) : ?>
				<?php echo esc_attr( of_get_option( 'tokopress_home_upcoming_event_text' ) ); ?> 
			<?php else : ?>
				<?php _e( 'All Events', 'tokopress' ); ?> 
			<?php endif; ?>
			<i class="fa fa-chevron-right"></i>
		</a>

		<h2 class="upcoming-event-title">
			<?php if( "" != of_get_option( 'tokopress_home_upcoming_event' ) ) : ?>
				<?php echo esc_attr( of_get_option( 'tokopress_home_upcoming_event' ) ); ?>
			<?php else : ?>
				<?php _e( 'Upcoming Events', 'tokopress' ); ?>
			<?php endif; ?>
		</h2>

		<div class="row">

			<div class="upcoming-event-wrap tribe-events-list">

				<div class="events-loop">
					<?php while ( $the_upcoming_events->have_posts() ) : ?>
						<?php $the_upcoming_events->the_post(); ?>
						
						<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?> col-sm-6 col-md-4">
							<?php tribe_get_template_part( 'list/single', 'event' ); ?>
						</div>
								
					<?php endwhile; ?>
				</div>

			</div>

		</div>
	</div>

</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>