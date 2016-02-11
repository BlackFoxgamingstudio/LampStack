<div id="header-block" class="site-header">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="site-branding">
					<a href="<?php echo esc_url( home_url() ); ?>">
						<?php if( !of_get_option( 'tokopress_site_logo' ) ) : ?>
							<div class="site-icon">
								<i class="fa fa-calendar"></i>
							</div>
							<div class="site-logo">
								<h2><?php bloginfo( 'name' ); ?></h2>
								<p><?php bloginfo( 'description' ); ?></p>
							</div>
						<?php else : ?>
							<div class="site-logo-image">
								<img src="<?php echo esc_url( of_get_option( 'tokopress_site_logo' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
							</div>
						<?php endif; ?>
					</a>
				</div>
			</div>
			<?php if( has_nav_menu( 'header_menu' ) ) : ?>
				<div class="col-sm-6 col-md-7 col-lg-8 mobile-menu visible-xs visible-sm">
					<a href="javascript:void(0)" class="sb-toggle-left">
						<i class="fa fa-navicon"></i>
					</a>
				</div>
				<div class="col-sm-6 col-md-7 col-lg-8 hidden-xs hidden-sm">
	                <?php
	                    $primary_args = array(
	                        'theme_location'    => 'header_menu',
	                        'depth'             => 3,
	                        'container'         => 'div',
	                        'container_class'   => 'primary-menu',
	                        'container_id'      => 'primary-menu',
	                        'menu_id'		    => 'header-menu',
	                        'menu_class'		=> 'header-menu sf-menu'
	                    );
	                    wp_nav_menu( $primary_args );
	                ?>
				</div>
            <?php endif; ?>

		</div>
	</div>
</div>