<?php
$cats = wp_get_post_terms( get_the_ID(), 'tribe_events_cat', array( 'fields' => 'ids' ) );
$tags = wp_get_post_terms( get_the_ID(), 'post_tag', array( 'fields' => 'ids' ) );

if( empty( $cats ) && empty( $tags ) )
	return;

global $tp_sidebar;
if ( $tp_sidebar ) {
	$class = 'col-sm-6';
}
else {
	$class = 'col-md-4 col-sm-6';
}

$args = array(
		'post_type'			=> 'tribe_events',
		'posts_per_page'	=> ( $tp_sidebar ? 4 : 6 ),
		'post__not_in'		=> array( get_the_ID() ),
		'eventDisplay' 		=> 'list',
		'tax_query' 		=> array('relation' => 'OR'),
		'orderby'			=>'rand',
	);
if ( !empty( $cats ) ) {
	$args['tax_query'][] = array(
			'taxonomy'=> 'tribe_events_cat',
			'terms'=> $cats,
			'field'=> 'id'
		);

}
if ( !empty( $tags ) ) {
	$args['tax_query'][] = array(
			'taxonomy'=> 'post_tag',
			'terms'=> $tags,
			'field'=> 'id'
		);

}
$query = new WP_Query( $args );
?>

<?php if( $query->have_posts() ) : ?>

	<div class="related-event-wrap tribe-events-list">
		<div class="related-event-title">
			<h2><?php _e( 'RELATED EVENTS', 'tokopress' ); ?></h2>
		</div>

		<div class="events-loop">
			<div class="row">
				<?php while ( $query->have_posts() ) : ?>
					<?php $query->the_post(); ?>
					
					<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?> <?php echo esc_attr( $class ); ?>">
						<?php tribe_get_template_part( 'list/single', 'event' ); ?>
					</div>
							
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>

	</div>

<?php endif; ?>