<?php
global $tp_post_classes;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-list ' . $tp_post_classes ); ?>>
	
	<div class="inner-loop">
		<?php if( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail( 'blog-thumbnail' ); ?>
				</a>
			</div>
		<?php else : ?>
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php 
					global $_wp_additional_image_sizes;
					$width = $_wp_additional_image_sizes['blog-thumbnail']['width'];
					$height = $_wp_additional_image_sizes['blog-thumbnail']['height'];
					?>
					<img src="//placehold.it/<?php echo esc_attr( $width ); ?>x<?php echo esc_attr( $height ); ?>" alt="<?php the_title(); ?>" class="no-thumb">
				</a>
			</div>
		<?php endif; ?>

		<div class="post-inner">
		    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-title"><h2><?php the_title(); ?></h2></a>
		</div>
	</div>

</article>