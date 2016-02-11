<?php
/**
 * Tokopress Featured Events
 *
 * @package Events
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_featured_event', 'eventica_shortcode_featured_event' );
function eventica_shortcode_featured_event( $atts ) {

	if( ! class_exists( 'Tribe__Events__Main' ) )
		return;

	/* Hide Event Calendar Pro Related Events, use our theme */
	if ( class_exists('Tribe__Events__Pro__Main') ) {
		tokopress_remove_filter_class( 'tribe_events_single_event_after_the_meta', 'Tribe__Events__Pro__Main', 'register_related_events_view', 10 );
	}

	/* Hide Event Calendar Pro Additional Fields, use our theme */
	if ( class_exists('Tribe__Events__Pro__Single_Event_Meta') ) {
		tokopress_remove_filter( 'tribe_events_single_event_meta_primary_section_end', 'additional_fields', 10 );
	}

	extract( shortcode_atts( array(
		'id'			=> '',
		'columns'		=> '1',
		'title_hide'	=> 'no',
		'title_text'	=> '',
		'title_color'	=> '',
		'title_bg'		=> '',
	), $atts ) );

	if ( !trim($title_text) )
		$title_text = __( 'Featured Event', 'tokopress' );

	$title_style = '';
	if ( $title_bg ) {
		$title_style .= 'background-color:'.$title_bg.';';
	}
	if ( $title_color ) {
		$title_style .= 'color:'.$title_color.';';
	}
	if ( $title_color ) {
		$title_style = 'style="'.$title_style.'"';
	}

	if ( $columns == '2' ) {
		$right_style = 'events-single-right col-sm-8 col-sm-push-4';
		$left_style = 'events-single-left col-sm-4 col-sm-pull-8';
	}
	else {
		$right_style = 'events-single-right col-sm-6 col-sm-push-6';
		$left_style = 'events-single-left col-sm-6 col-sm-pull-6';
	}

	$output = '';
	?>

	<?php $output .= '<div class="home-featured-event">'; ?>
		<?php
		$args = array(
			'post_status'	=>'publish',
			'post_type'		=>array(Tribe__Events__Main::POSTTYPE),
			'eventDisplay'	=>'custom',
		);
		if ( intval( $id ) > 0 ) {
			$args['post__in'] = array( intval( $id ) );
		}
		else {
			$args['posts_per_page'] = 1;
		}
		$the_featured_event = new WP_Query( $args );
		?>

		<?php ob_start(); ?>

		<?php if( $the_featured_event->have_posts() ) : ?>

			<div class="featured-event-wrap ">

				<?php if ( $title_hide != 'yes' ) : ?>
					<div class="featured-event-title" <?php echo $title_style; ?> >
						<h2>
							<?php echo $title_text; ?>
						</h2>
					</div>
				<?php endif; ?>

				<div id="tribe-events-content" class="tribe-events-single vevent clearfix">

					<?php tribe_events_the_notices() ?>

					<?php while ( $the_featured_event->have_posts() ) : ?>
						<?php $the_featured_event->the_post(); $event_id = get_the_ID(); ?>
						
						<div class="events-single-right <?php echo $right_style; ?>">

							<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<h2 class="entry-title">
									<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
										<?php the_title() ?>
									</a>
								</h2>
								<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>
								<div class="tribe-events-single-event-description tribe-events-content entry-content description">
									<?php the_content(); ?>
								</div>
							</div>

						</div>

						<div class="events-single-left <?php echo $left_style; ?>">
							
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
	<?php
	$output .= ob_get_clean();

	$output .= '</div>';
	?>

	<?php

	return $output;

}

add_action( 'vc_before_init', 'eventica_vc_featured_event' );
function eventica_vc_featured_event() {

	if( ! class_exists( 'Tribe__Events__Main' ) )
		return;

	$args = array(
		'post_status'		=>'publish',
		'posts_per_page'	=> 100,
		'post_type'			=>array(Tribe__Events__Main::POSTTYPE),
		'eventDisplay'		=>'custom',
	);
	$events = get_posts( $args );
	$eventslist = array();
	$eventslist[''] = '';
	foreach ($events as $event) {
		$eventslist[$event->post_title] = $event->ID;
	}

	vc_map( array(
	   'name'				=> __( 'Eventica - Featured Event', 'tokopress' ),
	   'base'				=> 'eventica_featured_event',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
								array(
									'type'			=> 'dropdown',
									'heading'		=> __( 'Featured Event', 'tokopress' ),
									'description'	=> __( 'Select one event for featured event.', 'tokopress' ),
									'param_name'	=> 'id',
									'value'			=> $eventslist,
									'std'			=> '',
								),
								array(
									'type'			=> 'dropdown',
									'heading'		=> __( 'Columns Style', 'tokopress' ),
									'param_name'	=> 'columns',
									'value'			=> array(
														__( '1:1', 'tokopress' ) => '1',
														__( '1:2', 'tokopress' ) => '2',
													),
								),
								array(
									'type'			=> 'dropdown',
									'heading'		=> __( 'Hide Section Title', 'tokopress' ),
									'param_name'	=> 'title_hide',
									'value'			=> array(
														__( 'No', 'tokopress' ) => 'no',
														__( 'Yes', 'tokopress' ) => 'yes',
													),
								),
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Section Title Text', 'tokopress' ),
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'Featured Event', 'tokopress' ),
									'param_name'	=> 'title_text',
									'value'			=> ''
								),
								array(
									'type' 			=> 'colorpicker',
									'heading' 		=> __( 'Section Title Color', 'tokopress' ),
									'param_name' 	=> 'title_color',
									'description' 	=> __( 'Select text color for section title.', 'tokopress' )
								),
								array(
									'type' 			=> 'colorpicker',
									'heading' 		=> __( 'Section Title Background', 'tokopress' ),
									'param_name' 	=> 'title_bg',
									'description' 	=> __( 'Select background color for section title.', 'tokopress' )
								),
							)
	   )
	);
}