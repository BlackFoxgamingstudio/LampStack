<div class="tribe-events-cta">
	<div class="tribe-events-cta-date">
		<span class="mm"><?php echo tribe_get_start_date( null, false, 'F' ) ?></span>
		<span class="dd"><?php echo tribe_get_start_date( null, false, 'd' ) ?></span>
		<span class="yy"><?php echo tribe_get_start_date( null, false, 'Y' ) ?></span>
	</div>
	<?php if ( tokopress_get_event_cta_text() ) : ?>
		<div class="tribe-events-cta-btn">
			<a class="btn" href="<?php echo tokopress_get_event_cta_url(); ?>">
				<?php echo tokopress_get_event_cta_text(); ?>
			</a>
		</div>
	<?php endif; ?>
</div>
