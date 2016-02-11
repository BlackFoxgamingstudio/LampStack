<div id="header-block" class="site-header site-header-ubermenu">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-5 col-lg-4">
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
							<div class="site-logo">
								<img src="<?php echo esc_url( of_get_option( 'tokopress_site_logo' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
							</div>
						<?php endif; ?>
					</a>
				</div>
			</div>
			<?php if( has_nav_menu( 'header_menu' ) ) : ?>
				<div class="col-sm-12 col-md-7 col-lg-8">
					<div class="header-ubermenu-wrap">
		                <?php ubermenu( 'main' , array( 'theme_location' => 'header_menu' ) ); ?>
					</div>
				</div>
            <?php endif; ?>

		</div>
	</div>
</div>