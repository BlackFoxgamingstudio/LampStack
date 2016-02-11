<?php
$args = array(
	'post_status'=>'publish',
	'post_type'=> 'post',
	'posts_per_page'=>3,
	'orderby'=>'date',
	'order'=>'DESC',
	'ignore_sticky_posts' => true
	);
$the_recent_post = new WP_Query( $args );
?>

<?php if( $the_recent_post->have_posts() ) : ?>
<div class="home-recent-posts">

	<div class="recent-post-wrap">

		<a class="recent-post-nav" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>">
			<?php if( "" != of_get_option( 'tokopress_home_recent_post_text' ) ) : ?>
				<?php echo esc_attr( of_get_option( 'tokopress_home_recent_post_text' ) ); ?> 
			<?php else : ?>
				<?php _e( 'All Posts', 'tokopress' ); ?> 
			<?php endif; ?>
			<i class="fa fa-chevron-right"></i>
		</a>

		<h2 class="recent-post-title">
			<?php if( "" != of_get_option( 'tokopress_home_recent_post' ) ) : ?>
				<?php echo esc_attr( of_get_option( 'tokopress_home_recent_post' ) ); ?>
			<?php else : ?>
				<?php _e( 'Recent Updates', 'tokopress' ); ?>
			<?php endif; ?>
		</h2>

		<div class="row">
			<?php while ( $the_recent_post->have_posts() ) : ?>
				<?php $the_recent_post->the_post(); ?>

					<?php
					global $tp_post_classes;
					$tp_post_classes = 'col-sm-6 col-md-12';
					?>
					<?php get_template_part( 'content', get_post_format() ); ?>
						
			<?php endwhile; ?>
		</div>

	</div>

</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>