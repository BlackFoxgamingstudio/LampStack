<?php get_header(); ?>

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
								
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-single clearfix' ); ?>>
	
									<div class="inner-post">
										<?php if( has_post_thumbnail() ) : ?>
											<div class="post-thumbnail">
												<?php the_post_thumbnail(); ?>
											</div>
										<?php endif; ?>

										<div class="col-md-9 col-md-push-3">
											<div class="post-summary">
												<?php if( ! of_get_option( 'tokopress_page_title_disable' ) ) : ?>
												    <h2 class="post-title screen-reader-text"><?php the_title(); ?></h2>
												<?php else : ?>
												    <h1 class="post-title"><?php the_title(); ?></h2>
												<?php endif; ?>

												<?php the_content(); ?>

												<?php wp_link_pages( array( 'before' => '<p class="page-link"><span>' . __( 'Pages:', 'tokopress' ) . '</span>', 'after' => '</p>' ) ); ?>
											</div>
										</div>

										<div class="col-md-3 col-md-pull-9">
											<div class="post-meta">
												<ul class="list-post-meta">
													<li>
														<div class="post-date"><time><?php echo tokopress_get_post_date(); ?></time></div>
													</li>
													<li>
														<div class="post-term-category"><?php echo tokopress_get_post_terms( array( 'taxonomy' => 'category', 'before' => '<p>' . __( 'Posted Under', 'tokopress' ) . '</p>' ) ); ?></div>
													</li>
													<li>
														<div class="post-author">
															<?php echo tokopress_get_post_author(); ?>
														</div>
													</li>

													<li>
														<div class="post-term-tags"><?php echo tokopress_get_post_terms( array( 'before' => '<p>' . __( 'Tags', 'tokopress' ) . '</p>' ) ); ?></div>
													</li>
	
												</ul>
											</div>
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

						<?php get_template_part( 'content', '404' ); ?>

					<?php endif; ?>

				</div>

				<?php get_sidebar(); ?>

			</div>
		</div>
	</div>

<?php get_footer(); ?>