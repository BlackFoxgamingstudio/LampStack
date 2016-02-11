<?php

function tokopress_wootickets_settings( $options ) {
	$options[] = array(
		'name' => __( 'Events - WooTickets', 'tokopress' ),
		'type' => 'info'
	);
	
		$options[] = array(
			'name' => __( 'WooTickets Quantity Input', 'tokopress' ),
			'desc' => __( 'Change default quantity input from "0" to "1"', 'tokopress' ),
			'id' => 'tokopress_wootickets_quantity_min1',
			'std' => '0',
			'type' => 'checkbox'
		);

	return $options;
}
add_filter( 'of_options', 'tokopress_wootickets_settings' );

function tokopress_events_wc_quantity_input_args( $args, $product ) {
	$args['input_value'] = 1;
	return $args;
}

add_action( 'tribe_events_single_event_after_the_meta', 'tokopress_events_wc_quantity_input_args_add', 1 );
function tokopress_events_wc_quantity_input_args_add() {
	if( of_get_option( 'tokopress_wootickets_quantity_min1' ) )
		add_filter( 'woocommerce_quantity_input_args', 'tokopress_events_wc_quantity_input_args', 10, 2 );
}

add_action( 'tribe_events_single_event_after_the_meta', 'tokopress_events_wc_quantity_input_args_remove', 20 );
function tokopress_events_wc_quantity_input_args_remove() {
	if( of_get_option( 'tokopress_wootickets_quantity_min1' ) )
		remove_filter( 'woocommerce_quantity_input_args', 'tokopress_events_wc_quantity_input_args', 10, 2 );
}

