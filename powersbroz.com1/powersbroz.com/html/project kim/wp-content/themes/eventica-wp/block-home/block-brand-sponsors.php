<div class="home-sponsors">
	<div class="container">

		<h3 class="section-title">
			<?php echo ( !of_get_option( 'tokopress_brand_title' ) ) ? __( 'Our Sponsors', 'tokopress' ) : esc_attr( of_get_option( 'tokopress_brand_title' ) ); ?>
		</h3>

		<div class="sponsors-inner clearfix">
			<?php for( $i=1; $i <= 8; $i++ ) : if( "" != of_get_option( 'tokopress_brand_img_' . $i ) ) : ?>
				<div class="sponsor">
					<a href="<?php echo esc_url( of_get_option( 'tokopress_brand_link_' . $i ) ); ?>"><img src="<?php echo esc_url( of_get_option( 'tokopress_brand_img_' . $i ) ); ?>" alt="Sponsor"></a>
				</div>
			<?php endif; endfor; ?>
		</div>

	</div>
</div>