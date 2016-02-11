<div class="col-md-3">
	<div id="sidebar">
		
		<?php if( is_active_sidebar( 'event' ) ) : ?>
			<?php dynamic_sidebar( 'event' ); ?>
		<?php else : ?>
			<?php the_widget( 'WP_Widget_Search' ); ?>

			<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

			<?php the_widget( 'WP_Widget_Meta' ); ?>
		<?php endif; ?>

	</div>
</div><!-- ./ sidebar -->