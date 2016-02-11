<?php
/**
 * Tokopress Upcoming Events
 *
 * @package Events
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_upcoming_events', 'eventica_shortcode_upcoming_events' );
function eventica_shortcode_upcoming_events( $atts ) {

	if( ! class_exists( 'Tribe__Events__Main' ) )
		return;

	extract( shortcode_atts( array(
		'category'			=> '',
		'exclude'			=> '',
		'numbers'			=> 3,
		'columns'			=> 3,
		'columns_tablet'	=> 2,
		'title_hide'		=> 'no',
		'title_text'		=> '',
		'title_color'		=> '',
		'link_text'			=> '',
		'link_color'		=> '',
	), $atts ) );

	$numbers = intval( $numbers );
	if ( $numbers < 1 )
		$numbers = 3;

	$columns = intval( $columns );
	if ( $columns < 1 )
		$columns = 1;
	if ( $columns > 3 )
		$columns = 3;

	$columns_tablet = intval( $columns_tablet );
	if ( $columns_tablet < 1 )
		$columns_tablet = 1;
	if ( $columns_tablet > 2 )
		$columns_tablet = 2;

	$columns_style = 'col-md-'.intval(12/$columns).' col-sm-'.intval(12/$columns_tablet);

	if ( !trim($title_text) )
		$title_text = __( 'Upcoming Events', 'tokopress' );

	if ( !trim($link_text) )
		$link_text = __( 'All Events', 'tokopress' );

	$title_style = trim( $title_color ) ? 'style="color:'.$title_color.'"' : '';
	$link_style = trim( $link_color ) ? 'style="color:'.$link_color.'"' : '';

	ob_start();

	$args = array(
		'post_type'			=> array(Tribe__Events__Main::POSTTYPE),
		'posts_per_page'	=> $numbers,
		'orderby'        	=> 'event_date',
		'order'          	=> 'ASC',
		//required in 3.x
		'eventDisplay'		=>'list'
		);

	if ( $category ) {
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
			return '<p">'.sprintf( __('"%s" event category does not exist.', 'tokopress'), $category ).'</p>';
		}
	}

	if ( ! empty( $exclude ) ) {
		$ids = explode( ",", $exclude );
		$args['post__not_in'] = $ids;
	}

	$the_upcoming_events = new WP_Query( $args );

	?>

	<?php if( $the_upcoming_events->have_posts() ) : ?>
	<div class="home-upcoming-events clearfix">

			<?php if ( $title_hide != 'yes' ) : ?>
				<a class="upcoming-event-nav" href="<?php echo tribe_get_events_link() ?>" <?php echo $link_style; ?>>
					<?php echo $link_text; ?> 
					<i class="fa fa-chevron-right"></i>
				</a>

				<h2 class="upcoming-event-title" <?php echo $title_style; ?>>
					<?php echo $title_text; ?>
				</h2>
			<?php endif; ?>

			<div class="row">

				<div class="upcoming-event-wrap tribe-events-list">

					<div class="events-loop">
						<?php while ( $the_upcoming_events->have_posts() ) : ?>
							<?php $the_upcoming_events->the_post(); ?>
							
							<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?> <?php echo $columns_style; ?>">
								<?php tribe_get_template_part( 'list/single', 'event' ); ?>
							</div>
									
						<?php endwhile; ?>
					</div>

				</div>

			</div>

	</div>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	<?php $output = ob_get_clean(); ?>

	<?php
	return $output;
}

add_action( 'vc_before_init', 'eventica_vc_upcoming_events' );
function eventica_vc_upcoming_events() {

	vc_map( array(
	   'name'				=> __( 'Eventica - Upcoming Events', 'tokopress' ),
	   'base'				=> 'eventica_upcoming_events',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
	   							array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Number Of Events', 'tokopress' ),
									'param_name'	=> 'numbers',
									'std'			=> '3',
									'value'			=> '',
								),
	   							array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Event Category Name', 'tokopress' ),
									'param_name'	=> 'category',
									'description'	=> __( 'Put event category name here if you want to retrieve upcoming events from this category only.', 'tokopress' ),
									'std'			=> '',
									'value'			=> '',
								),
	   							array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Exclude', 'tokopress' ),
									'param_name'	=> 'exclude',
									'description'	=> __( 'Insert Event IDs (separated by comma) to exclude.', 'tokopress' ),
									'std'			=> '',
									'value'			=> '',
								),
								array(
									'type'			=> 'dropdown',
									'heading'		=> __( 'Number of Columns in Desktop', 'tokopress' ),
									'param_name'	=> 'columns',
									'std'			=> '',
									'value'			=> array(
														'' => '',
														'3' => '3',
														'2' => '2',
														'1' => '1',
													),
									'description' 	=> __( 'For device width >= 992px', 'tokopress' ),
								),
								array(
									'type'			=> 'dropdown',
									'heading'		=> __( 'Number of Columns in Tablet', 'tokopress' ),
									'param_name'	=> 'columns_tablet',
									'std'			=> '',
									'value'			=> array(
														'' => '',
														'2' => '2',
														'1' => '1',
													),
									'description' 	=> __( 'For device width >= 768px and < 992px', 'tokopress' ),
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
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'Upcoming Events', 'tokopress' ),
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
									'type'			=> 'textfield',
									'heading'		=> __( 'Section Link Text', 'tokopress' ),
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'All Events', 'tokopress' ),
									'param_name'	=> 'link_text',
									'value'			=> ''
								),
								array(
									'type' 			=> 'colorpicker',
									'heading' 		=> __( 'Section Link Color', 'tokopress' ),
									'param_name' 	=> 'link_color',
									'description' 	=> __( 'Select text color for section link.', 'tokopress' )
								),
							)
	   )
	);
}
