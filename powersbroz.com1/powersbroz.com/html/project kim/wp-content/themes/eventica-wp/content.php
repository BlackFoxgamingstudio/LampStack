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
			<?php 
			$image_ids = array_keys(
				get_children(
					array(
						'post_parent'    => get_the_ID(),
						'post_type'	     => 'attachment',
						'post_mime_type' => 'image',
						'orderby'        => 'menu_order',
						'order'	         => 'ASC',
					)
				)
			);
			?>
			<?php if ( isset( $image_ids[0] ) ) : ?>
				<div class="post-thumbnail">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php echo wp_get_attachment_image( $image_ids[0], 'blog-thumbnail', false ); ?>
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
		<?php endif; ?>

		<div class="post-inner">
		    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-title"><h2><?php the_title(); ?></h2></a>
		    <div class="post-meta"><?php echo tokopress_get_post_date(); ?> <?php echo tokopress_get_post_edit_link(); ?></div>

			<div class="post-summary">
				<?php the_excerpt(); ?>
			</div>
		</div>
	</div>

</article>