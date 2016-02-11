<?php
$placeholder = of_get_option( 'tokopress_home_search_text' );
if ( ! trim($placeholder) ) {
	$placeholder = _x( 'Search Event &hellip;', 'placeholder', 'tokopress' );
}
?>
<div class="home-search-box">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<form role="search" class="search-form" name="tribe-bar-form" method="post" action="<?php echo esc_url( tribe_get_events_link() ); ?>">
					<input type="hidden" name="tribe-bar-date" value="">
					<input type="hidden" name="tribe-bar-date-day" value="">
					<label>
						<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'tokopress' ); ?></span>
						<input type="search" class="search-field" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo get_search_query(); ?>" name="tribe-bar-search" title="<?php echo esc_attr_x( 'Search for:', 'label', 'tokopress' ); ?>" />
					</label>
					<button type="submit" class="search-submit">
						<i class="fa fa-search"></i>
					</button>
				</form>

			</div>
		</div>
	</div>
</div>
