<?php
/**
 * Template Name: With Comments
 *
 * WARNING: This file is part of the Eventica parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category Page
 * @package  Templates
 * @author   TokoPress
 * @link     http://www.tokopress.com
 */

get_header(); ?>

	<?php if( ! of_get_option( 'tokopress_page_title_disable' ) ) : ?>
		<?php get_template_part( 'block-page-title' ); ?>
	<?php endif; ?>

	<div id="main-content">
		
		<div class="container">
			<div class="row">
				
				<div class="col-md-9">
					
					<?php if ( have_posts() ) : ?>

						<div class="main-wrapper">

							<?php do_action( 'tokopress_before_content' ); ?>
				
							<?php while ( have_posts() ) : the_post(); ?>
								
								<article id="page-<?php the_ID(); ?>" <?php post_class( 'page-single clearfix' ); ?>>
	
									<div class="inner-page">
										<?php if( has_post_thumbnail() ) : ?>
											<div class="post-thumbnail">
												<?php the_post_thumbnail(); ?>
											</div>
										<?php endif; ?>

										<div class="post-content">
											<?php if( ! of_get_option( 'tokopress_page_title_disable' ) ) : ?>
											    <h2 class="post-title screen-reader-text"><?php the_title(); ?></h2>
											<?php else : ?>
											    <h1 class="post-title"><?php the_title(); ?></h2>
											<?php endif; ?>

											<?php the_content(); ?>

											<?php wp_link_pages( array( 'before' => '<p class="page-link"><span>' . __( 'Pages:', 'tokopress' ) . '</span>', 'after' => '</p>' ) ); ?>
										</div>
									</div>

								</article>

							<?php endwhile; ?>

							<?php
			                if ( comments_open() || '0' != get_comments_number() ) :
			                    comments_template();
			                endif;
			                ?>

							<?php do_action( 'tokopress_after_content' ); ?>

						</div>
						
					<?php else : ?>

						<?php get_template_part( 'content', 'none' ); ?>

					<?php endif; ?>

				</div>

				<?php get_sidebar(); ?>

			</div>
		</div>
	</div>

<?php get_footer(); ?>
