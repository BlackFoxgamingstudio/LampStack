<div class="home-subscribe-form clearfix">
	<div class="row">
		<div class="col-md-7">
			<h2>
				<?php if( "" != of_get_option( 'tokopress_home_subscribe_title' ) ) : ?>
					<?php echo esc_attr( of_get_option( 'tokopress_home_subscribe_title' ) ); ?>
				<?php else : ?>
					<?php _e( 'Subscribe to our newsletter', 'tokopress' ); ?>
				<?php endif; ?>
			</h2>
			<p>
				<?php if( "" != of_get_option( 'tokopress_home_subscribe_text' ) ) : ?>
					<?php echo esc_attr( of_get_option( 'tokopress_home_subscribe_text' ) ); ?>
				<?php else : ?>
					<?php _e( 'never miss our latest news and events updates', 'tokopress' ); ?>
				<?php endif; ?>
			</p>
		</div>
		<div class="col-md-5">
			<?php /* NOTE: currently using do_shortcode is the best way to output subscribe form*/ ?>
			<?php /* TODO: contact mailchimp4wp plugin developer to add direct function call to output subscibe form */ ?>
			<?php echo do_shortcode( '[mc4wp_form]' ); ?>
		</div>
	</div>
</div>