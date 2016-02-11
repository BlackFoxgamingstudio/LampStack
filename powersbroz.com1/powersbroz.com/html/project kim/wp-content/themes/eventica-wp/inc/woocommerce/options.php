<?php
/**
 * WooCommerce Options Settings
 *
 * @package Theme Options
 * @author Tokopress
 *
 */

function tokopress_wc_settings( $options ) {
	$options[] = array(
		'name' 	=> __( 'WooCommerce', 'tokopress' ),
		'type' 	=> 'heading'
	);

	$options[] = array(
		'name' => __( 'WooCommerce - Global', 'tokopress' ),
		'type' => 'info'
	);

		$options[] = array(
			'name' => __( 'Redirect URL After Customer Login', 'tokopress' ),
			'desc' => '',
			'id' => 'tokopress_wc_red_cus_login',
			'std' => '',
			'type' => 'text'
		);

	$options[] = array(
		'name' => __( 'WooCommerce - Shop Page', 'tokopress' ),
		'type' => 'info'
	);
	
		$options[] = array(
			'name' => __( 'Shop Page Title', 'tokopress' ),
			'desc' => __( 'DISABLE page title on main shop page.', 'tokopress' ),
			'id' => 'tokopress_wc_hide_shop_title',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Shop Sidebar', 'tokopress' ),
			'desc' => __( 'DISABLE sidebar on shop page.', 'tokopress' ),
			'id' => 'tokopress_wc_hide_shop_sidebar',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Catalog Ordering', 'tokopress' ),
			'desc' => __( 'DISABLE catalog ordering dropdown', 'tokopress' ),
			'id' => 'tokopress_wc_hide_catalog_ordering',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Products Per Page', 'tokopress' ),
			'desc' => '',
			'id' => 'tokopress_wc_products_per_page',
			'std' => '6',
			'type' => 'text'
		);
		$options[] = array(
			'name' => __( 'Products Column Per Row', 'tokopress' ),
			'desc' => '',
			'id' => 'tokopress_wc_products_column_per_row',
			'std' => '2',
			'type' => 'select',
			'options' => array(
					'2' => '2',
					'3' => '3',
					'4' => '4'
				)
		);
		$options[] = array(
			'name' => __( 'Products Sale Flash', 'tokopress' ),
			'desc' => __( 'hide products sale flash', 'tokopress' ),
			'id' => 'tokopress_wc_hide_products_sale_flash',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Products Rating', 'tokopress' ),
			'desc' => __( 'hide products rating', 'tokopress' ),
			'id' => 'tokopress_wc_hide_products_rating',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Products Price', 'tokopress' ),
			'desc' => __( 'hide products price', 'tokopress' ),
			'id' => 'tokopress_wc_hide_products_price',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Products "Add To Cart" Button', 'tokopress' ),
			'desc' => __( 'DISABLE products "add to cart" button', 'tokopress' ),
			'id' => 'tokopress_wc_hide_products_cart_button',
			'std' => '0',
			'type' => 'checkbox'
		);

	$options[] = array(
		'name' => __( 'WooCommerce - Single Product', 'tokopress' ),
		'type' => 'info'
	);

		$options[] = array(
			'name' => __( 'Product Sidebar', 'tokopress' ),
			'desc' => __( 'DISABLE sidebar on single product page.', 'tokopress' ),
			'id' => 'tokopress_wc_hide_product_sidebar',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => __( 'Product Sale Flash', 'tokopress' ),
			'desc' => __( 'hide product sale flash', 'tokopress' ),
			'id' => 'tokopress_wc_hide_product_sale_flash',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Product Price', 'tokopress' ),
			'desc' => __( 'hide product price', 'tokopress' ),
			'id' => 'tokopress_wc_hide_product_price',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Product "Add To Cart" Button', 'tokopress' ),
			'desc' => __( 'DISABLE product "add to cart" button', 'tokopress' ),
			'id' => 'tokopress_wc_hide_product_cart_button',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Product Meta (categories/tags)', 'tokopress' ),
			'desc' => __( 'DISABLE product meta (categories/tags)', 'tokopress' ),
			'id' => 'tokopress_wc_hide_product_meta_tags',
			'std' => '0',
			'type' => 'checkbox'
		);

	$options[] = array(
		'name' => __( 'WooCommerce - Related Product', 'tokopress' ),
		'type' => 'info'
	);

		$options[] = array(
			'name' => __( 'Related Products', 'tokopress' ),
			'desc' => __( 'DISABLE related products on single product page', 'tokopress' ),
			'id' => 'tokopress_wc_hide_related_products',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Column Per Row', 'tokopress' ),
			'desc' => '',
			'id' => 'tokopress_wc_products_related_column',
			'std' => '2',
			'type' => 'select',
			'options' => array(
					'2' => '2',
					'3' => '3',
					'4' => '4'
				)
		);
		$options[] = array(
			'name' => __( 'Number of Related Products', 'tokopress' ),
			'desc' => '',
			'id' => 'tokopress_wc_products_related_number',
			'std' => '2',
			'type' => 'select',
			'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12'
				)
		);

	$options[] = array(
		'name' => __( 'WooCommerce - Up-Sells Product', 'tokopress' ),
		'type' => 'info'
	);

		$options[] = array(
			'name' => __( 'Up-Sells', 'tokopress' ),
			'desc' => __( 'DISABLE up-sells products on single product page', 'tokopress' ),
			'id' => 'tokopress_wc_hide_upsells_products',
			'std' => '0',
			'type' => 'checkbox'
		);
		$options[] = array(
			'name' => __( 'Column Per Row', 'tokopress' ),
			'desc' => '',
			'id' => 'tokopress_wc_products_upsells_column',
			'std' => '2',
			'type' => 'select',
			'options' => array(
					'2' => '2',
					'3' => '3',
					'4' => '4'
				)
		);
		$options[] = array(
			'name' => __( 'Number of Up-Sells Products', 'tokopress' ),
			'desc' => '',
			'id' => 'tokopress_wc_products_upsells_number',
			'std' => '2',
			'type' => 'select',
			'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12'
				)
		);

	$options[] = array(
		'name' => __( 'WooCommerce - Cross-Sells Product', 'tokopress' ),
		'type' => 'info'
	);

		$options[] = array(
			'name' => __( 'Cross-Sells', 'tokopress' ),
			'desc' => __( 'DISABLE Cross-sells products on cart page', 'tokopress' ),
			'id' => 'tokopress_wc_hide_crosssells_products',
			'std' => '0',
			'type' => 'checkbox'
		);

	return $options;
}
add_filter( 'of_options', 'tokopress_wc_settings' );