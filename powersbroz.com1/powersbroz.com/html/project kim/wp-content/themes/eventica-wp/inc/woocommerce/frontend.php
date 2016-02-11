<?php

/**
 * WooCommerce Pagination
 */
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'tokopress_paging_nav', 10 );

/**
 * Add Site title in shop page
 */
add_action( 'tokopress_wc_before_wrapper', 'tokopress_wc_shop_title', 5 );
function tokopress_wc_shop_title() {
	if( of_get_option( 'tokopress_page_title_disable' ) )
		return;

	if( is_shop() ) {
		if( !of_get_option( 'tokopress_wc_hide_shop_title' ) ) {
			get_template_part( 'block-page-title' );
		}		
	}
	else {
		get_template_part( 'block-page-title' );
	}
}

/**
 * Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
 */
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	ob_start();
	
	?>
	<div class="hidden-xs">
		<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php _e( 'shopping cart', 'tokopress' ); ?>" class="cart-contents">
			<i class="fa fa-shopping-cart"></i>
			<?php _e( 'My Cart', 'tokopress' ); ?> (<?php echo esc_attr( $woocommerce->cart->get_cart_total() ); ?>)
		</a>
	</div>
	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
	
}

/**
 * Remove wocommerce breadcrumb
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Remove shop page title
 */
add_action( 'tokopress_custom_styles', 'tokopress_wc_remove_page_title', 20 );
function tokopress_wc_remove_page_title() {
	if( ! of_get_option( 'tokopress_page_title_disable' ) ) {
		echo '.woocommerce h1.page-title { display:none; }';
	}
}

/**
 * Placement Sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_action( 'tokopress_wc_after_content', 'tokopress_wc_sidebar', 55 );
function tokopress_wc_sidebar() {
	$sidebar = true;
	if ( is_singular() && of_get_option( 'tokopress_wc_hide_product_sidebar' ) ) {
		$sidebar = false;
	}
	elseif ( !is_singular() && of_get_option( 'tokopress_wc_hide_shop_sidebar' ) ) {
		$sidebar = false;
	}
	if ( $sidebar ) {
		get_sidebar( 'shop' );
	}
}

/**
 * Wrapper WooCommerce Content
 */
add_action( 'tokopress_wc_before_content', 'tokopress_wc_wrap_content_start', 5 );
function tokopress_wc_wrap_content_start() {
	$class = 'col-md-9';
	if ( is_singular() && of_get_option( 'tokopress_wc_hide_product_sidebar' ) ) {
		$class = 'col-md-12';
	}
	elseif ( !is_singular() && of_get_option( 'tokopress_wc_hide_shop_sidebar' ) ) {
		$class = 'col-md-12';
	}
	echo '<div class="'.$class.'">';
	echo '<div class="page-shop">';
}
add_action( 'tokopress_wc_after_content', 'tokopress_wc_wrap_content_end', 50 );
function tokopress_wc_wrap_content_end() {
	echo '</div>';
	echo '</div>';
}

/**
 * Placement Catalog Navigation
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
if( !of_get_option( 'tokopress_wc_hide_catalog_ordering' ) ) {
	add_action( 'tokopress_wc_before_wrapper', 'tokopress_wc_shop_catalog_wrap_start', 25 );
	function tokopress_wc_shop_catalog_wrap_start() {
		if( is_shop() ) {
			if( ! of_get_option( 'tokopress_page_title_disable' ) ) {
				echo '<div class="container"><div class="shop-catalog clearfix">';
			}
			else {
				echo '<div class="container"><div class="shop-catalog notitle clearfix">';
			}
			woocommerce_result_count();
			woocommerce_catalog_ordering();
		}
	}
	add_action( 'tokopress_wc_before_wrapper', 'tokopress_wc_shop_catalog_wrap_end', 30 );
	function tokopress_wc_shop_catalog_wrap_end() {
		if( is_shop() ) {
			echo '</div></div>';
		}
	}
}

/**
 * Custom product per page
 */
add_filter( 'loop_shop_per_page', 'tokopress_custom_loop_shop_per_page', 20 );
function tokopress_custom_loop_shop_per_page( $cols ) {
	
	$shop_per_page = of_get_option( 'tokopress_wc_products_per_page' );
	if( !$shop_per_page ) {
		$per_page = 6;
	} else {
		$per_page = of_get_option( 'tokopress_wc_products_per_page' );
	}
	return $per_page;

}

/**
 * Product Column
 */
add_filter( 'loop_shop_columns', 'tokopress_filter_class_post' );
function tokopress_filter_class_post( $classes ) {
	$columns = of_get_option( 'tokopress_wc_products_column_per_row' );
	if ( !$columns ) $columns = 2; 
	$classes = $columns;
	return $classes;
}
add_filter( 'body_class', 'tokopress_wc_shop_column' );
function tokopress_wc_shop_column( $classes ) {
	if( is_woocommerce() && ( is_shop() || is_tax() ) ) {
		$columns = of_get_option( 'tokopress_wc_products_column_per_row' );
		if ( !$columns ) $columns = 2;
		$classes[] = 'product-col-' . $columns;
	}

	return $classes;
}

/**
 * Product Loop Thumbnail
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'tokopress_wc_thumbnail_link_start', 9 );
function tokopress_wc_thumbnail_link_start() {
	echo '<a href="' . get_permalink() . '">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'tokopress_wc_thumbnail_link_end', 12 );
function tokopress_wc_thumbnail_link_end() {
	echo '</a>';
}

/**
 * Product Loop Caption Wrapper
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'tokopress_wc_product_loop_caption_start', 99 );
function tokopress_wc_product_loop_caption_start() {
	echo '<div class="wc-caption-wrap clearfix">';
}
add_action( 'woocommerce_after_shop_loop_item', 'tokopress_wc_product_loop_caption_end', 50 );
function tokopress_wc_product_loop_caption_end() {
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'tokopress_wc_caption_left_start', 100 );
function tokopress_wc_caption_left_start() {
	echo '<div class="wc-caption-left pull-left">';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'tokopress_wc_caption_left_end', 99 );
function tokopress_wc_caption_left_end() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'tokopress_wc_caption_right_start', 5 );
function tokopress_wc_caption_right_start() {
	echo '<div class="wc-caption-right pull-right">';
}
add_action( 'woocommerce_after_shop_loop_item', 'tokopress_wc_caption_right_end', 10 );
function tokopress_wc_caption_right_end() {
	echo '</div>';
}

/**
 * Product Sale Flash
 */
if ( of_get_option( 'tokopress_wc_hide_products_sale_flash' ) ) {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
}

/**
 * Product Rating
 */
if( of_get_option( 'tokopress_wc_hide_products_rating' ) ) {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
}

/**
 * Product Price
 */
if( of_get_option( 'tokopress_wc_hide_products_price' ) ) {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
}

/**
 * Add to Cart
 */
if( of_get_option( 'tokopress_wc_hide_products_cart_button' ) ) {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}


/**
 * Single Product Summary Divider
 */
add_action( 'woocommerce_single_product_summary', 'tokopress_wc_single_summary_left_start', 1 );
function tokopress_wc_single_summary_left_start() {
	echo '<div class="wc-summary-left pull-left">';
}
add_action( 'woocommerce_single_product_summary', 'tokopress_wc_single_summary_left_end', 25 );
function tokopress_wc_single_summary_left_end() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'tokopress_wc_single_summary_right_start', 26 );
function tokopress_wc_single_summary_right_start() {
	echo '<div class="wc-summary-right pull-right">';
}
add_action( 'woocommerce_single_product_summary', 'tokopress_wc_single_summary_right_end', 60 );
function tokopress_wc_single_summary_right_end() {
	echo '</div>';
}

/**
 * Remove Title single product
 */
if( ! of_get_option( 'tokopress_page_title_disable' ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
}

/**
 * Remove Sale Flash single product
 */
if( of_get_option( 'tokopress_wc_hide_product_sale_flash' ) ) {
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
}

/**
 * Remove Price single product
 */
if( of_get_option( 'tokopress_wc_hide_product_price' ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
}

/**
 * Remove Add to Cart single product
 */
if( of_get_option( 'tokopress_wc_hide_product_cart_button' ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

/**
 * Remove Meta Single Product
 */
if( of_get_option( 'tokopress_wc_hide_product_meta_tags' ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}

/**
 * Show/hide related products on single product page, based on theme settings.
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
if ( !of_get_option( 'tokopress_wc_hide_related_products' ) ) {
	add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 30 );
}

/**
 * Filter number of related products to show.
 */
if ( ! function_exists( 'woocommerce_output_related_products' ) ) {
	function woocommerce_output_related_products() {
		global $woocommerce;
		$posts_per_page = intval( of_get_option( 'tokopress_wc_products_related_number' ) );
		if ( $posts_per_page < 1 ) $posts_per_page = 2;
		$columns = intval( of_get_option( 'tokopress_wc_products_related_column' ) );
		if ( $columns < 1 ) $columns = 2;
		if ( is_object( $woocommerce ) && version_compare( $woocommerce->version, '2.1', '>=' ) ) {
			woocommerce_related_products( $args = array(
					'posts_per_page' => $posts_per_page,
					'columns' => $columns,
					'orderby' => 'rand'
				)
			);
		}
		else {
			woocommerce_related_products( $posts_per_page, $columns );
		}
	}
}

/**
 * Add related products column body class.
 */
add_filter( 'body_class', 'tokopress_wc_related_body_class' );
function tokopress_wc_related_body_class( $classes ) {
	if ( !is_product() ) return $classes;
	$columns = of_get_option( 'tokopress_wc_products_related_column' );
	if ( !$columns ) $columns = 2; 
	$classes[] = 'wc-related-col-'.$columns;
	return $classes;
}

/**
 * DISABLE up-sells products
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
if( !of_get_option( 'tokopress_wc_hide_upsells_products' ) ) {
	add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 25 );
}

/**
 * SET per-page and column up-sells product
 */
function woocommerce_upsell_display( $posts_per_page = 2, $columns = 2, $orderby = 'rand' ) {
	$posts_per_page = intval( of_get_option( 'tokopress_wc_products_upsells_number' ) );
	if ( $posts_per_page < 1 ) $posts_per_page = 2;
	$columns = intval( of_get_option( 'tokopress_wc_products_upsells_column' ) );
	if ( $columns < 1 ) $columns = 2;
	woocommerce_get_template( 'single-product/up-sells.php', array(
			'posts_per_page'  => $posts_per_page,
			'orderby'    => $orderby,
			'columns'    => $columns
		) );
}

/**
 * Add up-sells column body class.
 */
add_filter( 'body_class', 'tokopress_wc_upsells_body_class' );
function tokopress_wc_upsells_body_class( $classes ) {
	if ( !is_product() ) return $classes;
	$columns = intval( of_get_option( 'tokopress_wc_products_upsells_column' ) );
	if ( $columns < 1 ) $columns = 2;
	$classes[] = 'wc-upsells-col-' . $columns;
	return $classes;
}

/**
 * DISABLE cross-sells product on cart page
 */
if( of_get_option( 'tokopress_wc_hide_crosssells_products' ) )
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
