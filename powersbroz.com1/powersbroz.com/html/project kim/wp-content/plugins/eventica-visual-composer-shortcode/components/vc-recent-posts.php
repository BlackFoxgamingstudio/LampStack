<?php
/**
 * Tokopress Recent Post
 *
 * @package VC Element
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_recent_posts', 'eventica_shortcode_recent_posts' );
function eventica_shortcode_recent_posts( $atts ) {

	extract( shortcode_atts( array(
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
		$title_text = __( 'Recent Posts', 'tokopress' );

	if ( !trim($link_text) )
		$link_text = __( 'All Posts', 'tokopress' );

	$title_style = trim( $title_color ) ? 'style="color:'.$title_color.'"' : '';
	$link_style = trim( $link_color ) ? 'style="color:'.$link_color.'"' : '';

	ob_start();

	$args = array(
		'post_status'			=> 'publish',
		'post_type'				=> 'post',
		'posts_per_page'		=> $numbers,
		'orderby'				=> 'date',
		'order'					=> 'DESC',
		'ignore_sticky_posts' 	=> true
		);
	$the_recent_post = new WP_Query( $args );

	if( $the_recent_post->have_posts() ) :
	?>
	<div class="home-recent-posts">

		<div class="recent-post-wrap">

			<?php if ( $title_hide != 'yes' ) : ?>
				<a class="recent-post-nav" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" <?php echo $link_style; ?>>
					<?php echo $link_text; ?> 
					<i class="fa fa-chevron-right"></i>
				</a>

				<h2 class="recent-post-title" <?php echo $title_style; ?>>
					<?php echo $title_text; ?>
				</h2>
			<?php endif; ?>

			<div class="row">
				<?php while ( $the_recent_post->have_posts() ) : ?>
					<?php $the_recent_post->the_post(); ?>

						<?php
						global $tp_post_classes;
						$tp_post_classes = $columns_style;
						?>
						<?php get_template_part( 'content', get_post_format() ); ?>
							
				<?php endwhile; ?>
			</div>

		</div>

	</div>

	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	<?php $output = ob_get_clean(); ?>

	<?php
	return $output;
}

add_action( 'vc_before_init', 'eventica_vc_recent_posts' );
function eventica_vc_recent_posts() {
	vc_map( array(
	   'name'				=> __( 'Eventica - Recent Posts', 'tokopress' ),
	   'base'				=> 'eventica_recent_posts',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
	   							array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Number Of Posts', 'tokopress' ),
									'param_name'	=> 'numbers',
									'std'			=> '3',
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
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'Recent Posts', 'tokopress' ),
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
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'All Posts', 'tokopress' ),
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
