		<?php if( !of_get_option( 'tokopresss_disable_footer_widget' ) )
			get_template_part( 'block-footer-widget' ); ?>

		<?php if( !of_get_option( 'tokopresss_disable_footer_buttom' ) )
			get_template_part( 'block-footer-credit' ); ?>

		</div>
		<div id="back-top" style="display:block;"><i class="fa fa-angle-up"></i></div>
		<div class="sb-slidebar sb-left sb-style-push"></div>
		<?php wp_footer(); ?>
	</body>
</html>
