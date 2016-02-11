<?php get_header(); ?>

	<?php if( ! of_get_option( 'tokopress_page_title_disable' ) ) : ?>
		<?php get_template_part( 'block-page-title' ); ?>
	<?php endif; ?>

	<div id="main-content">
		
		<div class="container">
			<div class="row">
				
				<div class="col-md-12">
					
					<article id="page-none" class="page-none page-single clearfix">
						<h2 class="post-title"><?php _e( '404 - Not Found', 'tokopress' ); ?></h2>
						<div class="post-content group">
							<p><?php _e( 'It looks like this was the result of either:', 'tokopress' ); ?></p>
							
							<ul>
								<li><?php _e( 'a mistyped address', 'tokopress' ); ?></li>
								<li><?php _e( 'an out-of-date link', 'tokopress' ); ?></li>
							</ul>
							
							<p><?php _e( 'Perhaps searching can help.', 'tokopress' ); ?></p>
							<p><?php get_search_form(); ?></p>
						</div>
					</article>

				</div>

			</div>
		</div>
	</div>

<?php get_footer(); ?>