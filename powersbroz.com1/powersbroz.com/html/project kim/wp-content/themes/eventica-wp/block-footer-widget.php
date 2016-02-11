<div id="footer-widget">
	<div class="container">
		<div class="row">
			
			<div class="col-sm-6 col-md-3">
				<div class="footer-col">
					<?php if( is_active_sidebar( 'footer-one' ) ) : ?>

			            <?php dynamic_sidebar( 'footer-one' ); ?>

			        <?php else : ?>
			            
			            <section id="footer-one" class="widget footer-widget footer-one">
			                <h3 class="widget-title"><?php _e( 'Footer Widget #1', 'tokopress' ); ?></h3>
			                <p>
								<?php echo sprintf( __( 'This is "Footer Widget #1" widget area. Visit your <a href="%s">Widgets Page</a> to add new widget to this area.', 'tokopress' ), home_url() . '/wp-admin/widgets.php' ); ?>
			                </p>
			            </section>
			        
			        <?php endif; ?>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="footer-col">
					<?php if( is_active_sidebar( 'footer-two' ) ) : ?>

			            <?php dynamic_sidebar( 'footer-two' ); ?>

			        <?php else : ?>

			            <section id="footer-two" class="widget footer-widget footer-two">
			                <h3 class="widget-title"><?php _e( 'Footer Widget #2', 'tokopress' ); ?></h3>
			                <p>
								<?php echo sprintf( __( 'This is "Footer Widget #2" widget area. Visit your <a href="%s">Widgets Page</a> to add new widget to this area.', 'tokopress' ), home_url() . '/wp-admin/widgets.php' ); ?>
			                </p>
			            </section>

			        <?php endif; ?>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="footer-col">
					<?php if( is_active_sidebar( 'footer-three' ) ) : ?>

			            <?php dynamic_sidebar( 'footer-three' ); ?>

			        <?php else : ?>

			            <section id="footer-three" class="widget footer-widget footer-three">
			                <h3 class="widget-title"><?php _e( 'Footer Widget #3', 'tokopress' ); ?></h3>
			                <p>
			                    <?php echo sprintf( __( 'This is "Footer Widget #3" widget area. Visit your <a href="%s">Widgets Page</a> to add new widget to this area.', 'tokopress' ), home_url() . '/wp-admin/widgets.php' ); ?>
			                </p>
			            </section>

			        <?php endif; ?>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="footer-col">
					<?php if( is_active_sidebar( 'footer-four' ) ) : ?>

			            <?php dynamic_sidebar( 'footer-four' ); ?>

			        <?php else: ?>

			            <section id="footer-four" class="widget footer-widget footer-four">
			                <h3 class="widget-title"><?php _e( 'Footer Widget #4', 'tokopress' ); ?></h3>
			                <p>
			                	<?php echo sprintf( __( 'This is "Footer Widget #4" widget area. Visit your <a href="%s">Widgets Page</a> to add new widget to this area.', 'tokopress' ), home_url() . '/wp-admin/widgets.php' ); ?>
			                </p>
			            </section>

			        <?php endif; ?>
				</div>
			</div>

		</div>
	</div>
</div><!-- ./footer widget -->