<div class="col-md-3">
	<div id="sidebar">
		
		<?php if( is_active_sidebar( 'primary' ) ) : ?>
			<?php dynamic_sidebar( 'primary' ); ?>
		<?php else : ?>
			<?php the_widget( 'WP_Widget_Search' ); ?>

			<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

			<?php the_widget( 'WP_Widget_Meta' ); ?>
		<?php endif; ?>

	</div>
</div><!-- ./ sidebar -->