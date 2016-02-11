<div class="col-md-3">
	<div id="sidebar">
		
		<?php if( is_active_sidebar( 'shop' ) ) : ?>
			<?php dynamic_sidebar( 'shop' ); ?>
		<?php else : ?>
			<?php the_widget( 'WP_Widget_Search' ); ?>

			<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

			<?php the_widget( 'WP_Widget_Meta' ); ?>
		<?php endif; ?>

	</div>
</div><!-- ./ sidebar -->