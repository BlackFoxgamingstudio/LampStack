<?php

add_theme_support( 'woocommerce' );

/**
 * Redirect Option After customer Login
 */
function tokopress_wc_login_redirect( $redirect_to ) {
    $redirect_to = esc_url( of_get_option( 'tokopress_wc_red_cus_login' ) );
    return $redirect_to;
}
if( of_get_option( 'tokopress_wc_red_cus_login' ) ) {
	add_filter( 'woocommerce_login_redirect', 'tokopress_wc_login_redirect' );
}