<section id="page-title" class="page-title">
	<div class="container">
		<?php tokopress_breadcrumb_event(); ?>
		<?php if ( is_single() ) : ?>
			<h1><?php the_title() ?></h1>
		<?php else : ?>
			<h1><?php _e( 'Events', 'tokopress' ); ?></h1>
		<?php endif; ?>
	</div>
</section>
