<?php
$placeholder = of_get_option( 'tokopress_home_search_text' );
if ( ! trim($placeholder) ) {
	$placeholder = _x( 'Search &hellip;', 'placeholder', 'tokopress' );
}
?>
<div class="home-search-box">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label>
						<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'tokopress' ); ?></span>
						<input type="search" class="search-field" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'tokopress' ); ?>" />
					</label>
					<button type="submit" class="search-submit">
						<i class="fa fa-search"></i>
					</button>
				</form>

			</div>
		</div>
	</div>
</div>
