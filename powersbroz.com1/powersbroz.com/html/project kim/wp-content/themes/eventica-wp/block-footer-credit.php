<?php
	$socials = array(
		'rss' 			=> 'RSS Feed',
		'envelope-o' 	=> 'E-mail',
		'twitter' 		=> 'Twitter',
		'facebook' 		=> 'Facebook',
		'google-plus' 	=> 'gPlus',
		'youtube' 		=> 'Youtube',
		'flickr' 		=> 'Flickr',
		'linkedin' 		=> 'Linkedin',
		'pinterest' 	=> 'Pinterest',
		'dribbble' 		=> 'Dribbble',
		'github' 		=> 'Github',
		'lastfm' 		=> 'LastFm',
		'vimeo-square' 	=> 'Vimeo',
		'tumblr' 		=> 'Tumblr',
		'instagram' 	=> 'Instagram',
		'soundcloud' 	=> 'Sound Cloud',
		'behance' 		=> 'Behance',
		'deviantart' 	=> 'Daviant Art'
	);
?>

<div id="footer-block">
	<div class="container">
		<div class="row">
			
			<div class="col-md-6">
				<div class="footer-credit">
					<p>
						<?php if( "" != of_get_option( 'tokopress_footer_text' ) ) : ?>
							<?php echo wp_kses_post( of_get_option( 'tokopress_footer_text' ) ); ?>
						<?php else : ?>
							&copy; <a href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a> Powered by <a href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a> and <a href="https://wordpress.org/">Wordpress</a>.
						<?php endif; ?>

					</p>
				</div>
			</div>
			<div class="col-md-6">
				<div id="footer-menu">
					<?php if( has_nav_menu( 'footer_menu' ) ) : ?>
		                <?php
		                    $secondary_args = array(
		                        'theme_location'    => 'footer_menu',
		                        'depth'             => 3,
		                        'container'         => 'div',
		                        'container_id'      => 'secondary-menu',
		                        'menu_class'		=> 'footer-menu'
		                    );
		                    wp_nav_menu( $secondary_args );
		                ?>
		            <?php endif; ?>
		            <?php if( !of_get_option( 'tokopress_hide_social' ) ) : ?>
		            <ul id="social-icon">
		            	<li>

		            		<?php $numbers = count( $socials ); ?>
							<?php for ( $i = 1; $i <= $numbers; $i++ ) : ?>
								<?php $social = of_get_option( "tokopress_social_{$i}" ); ?>
								<?php $social_url = of_get_option( "tokopress_social_{$i}_url" ); ?>
								<?php if ( $social ) echo '<a href="' . ( $social_url ? esc_url( $social_url ) : '#' ) . '" title="' . $socials[$social] . '" class="' . $social . '"><i class="fa fa-' . $social . '"></i></a>'; ?>
							<?php endfor; ?>

		            	</li>
		            </ul>
			        <?php endif; ?>
				</div>
			</div>

		</div>
	</div>
</div><!-- ./footer block -->