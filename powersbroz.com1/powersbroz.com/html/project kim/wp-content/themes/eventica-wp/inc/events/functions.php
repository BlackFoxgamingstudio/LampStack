<?php

/**
 * Output Breadcrumb
 */
function tokopress_breadcrumb_event( $args = array() ) {
	echo tokopress_get_breadcrumb_event( $args );
}

/**
 * Return breadcrumb. */
function tokopress_get_breadcrumb_event( $args = array() ) {
	$defaults = array(
		'container'       => 'div',
		'separator'       => '&#47;',
		'before'          => '',
		'after'           => '',
		'show_on_front'   => false,
		'network'         => false,
		'show_title'      => true,
		'show_browse'     => false,
		'echo'            => true,
		'post_taxonomy'   => array(
			'tribe_events'        => 'tribe_events_cat',
		),
	);

	$args = apply_filters( 'toko_breadcrumb_event_args', wp_parse_args( $args, $defaults ) );
	
	$breadcrumb = new event_Breadcrumb_Trail( $args );
	return $breadcrumb->trail();
}

class event_Breadcrumb_Trail extends Breadcrumb_Trail {

	public function do_trail_items() {

		$this->do_network_home_link();
		$this->do_site_home_link();

		$this->do_event_link();

		/* If viewing a single post. */
		if ( is_singular() ) {
			$this->do_singular_items();
		}
		elseif ( is_tax() ) {
			$this->do_term_archive_items();
		}
		/* If viewing the 404 page. */
		elseif ( is_404() ) {
			$this->do_404_items();
		}

		/* Add paged items if they exist. */
		$this->do_paged_items();

		/* Allow developers to overwrite the items for the breadcrumb trail. */
		$this->items = apply_filters( 'breadcrumb_trail_items', $this->items, $this->args );
	}

	public function do_event_link() {
		$event_url = tribe_get_events_link();
		$event_title = __( 'Events', 'tokopress' );
		$this->items[] = '<a href="' . $event_url . '" title="' . esc_attr( $event_title ) . '">' . $event_title . '</a>';
	}

	public function do_singular_items() {

		/* Get the queried post. */
		$post    = get_queried_object();
		$post_id = get_queried_object_id();

		/* Display terms for specific post type taxonomy if requested. */
		$this->do_post_terms( $post_id );

		/* End with the post title. */
		if ( $post_title = single_post_title( '', false ) ) {

			if ( 1 < get_query_var( 'page' ) )
				$this->items[] = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( $post_title ) . '">' . $post_title . '</a>';

			elseif ( true === $this->args['show_title'] )
				$this->items[] = $post_title;
		}
	}
}

function tokopress_tribe_event_time() {
	global $post;
	$event = $post;
	if ( tribe_event_is_multiday( $event ) ) { // multi-date event
		$from = tribe_get_start_date( $event, false, 'U' );
		$to = tribe_get_end_date( $event, false, 'U' );

		/* derive from human_time_diff WordPress function */
		$diff = (int) abs( $to - $from );
		if ( $diff < HOUR_IN_SECONDS ) {
			$mins = round( $diff / MINUTE_IN_SECONDS );
			if ( $mins <= 1 )
				$mins = 1;
			printf( _n( '%s minute', '%s minutes', $mins, 'tokopress' ), $mins );
		} 
		elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
			$hours = round( $diff / HOUR_IN_SECONDS );
			if ( $hours <= 1 )
				$hours = 1;
			printf( _n( '%s hour', '%s hours', $hours, 'tokopress' ), $hours );
		} 
		elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
			$days = round( $diff / DAY_IN_SECONDS );
			if ( $days <= 1 )
				$days = 1;
			printf( _n( '%s day', '%s days', $days, 'tokopress' ), $days );
		} 
		elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
			$weeks = round( $diff / WEEK_IN_SECONDS );
			if ( $weeks <= 1 )
				$weeks = 1;
			printf( _n( '%s week', '%s weeks', $weeks, 'tokopress' ), $weeks );
		} 
		elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
			$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
			if ( $months <= 1 )
				$months = 1;
			printf( _n( '%s month', '%s months', $months, 'tokopress' ), $months );
		} 
		elseif ( $diff >= YEAR_IN_SECONDS ) {
			$years = round( $diff / YEAR_IN_SECONDS );
			if ( $years <= 1 )
				$years = 1;
			printf( _n( '%s year', '%s years', $years, 'tokopress' ), $years );
		}
	}
	elseif ( tribe_event_is_all_day( $event ) ) { // all day event
		_e( 'One day', 'tokopress' );
	}
	else {
		$time_format = get_option( 'time_format' );
		$start_date = tribe_get_start_date( $event, false, $time_format );
		$end_date = tribe_get_end_date( $event, false, $time_format );
		if ( $start_date !== $end_date ) {
			printf( __( '%s - %s', 'tokopress' ), $start_date, $end_date );
		}
		else {
			printf( '%s', $start_date );
		}
	}
}

function tokopress_get_event_cta_text() {
	global $post;

	$ticket_sell = get_post_meta( $post->ID, '_tokopress_events_ticket', true );
	if ( class_exists( 'woocommerce' ) && ( $ticket_sell == 'tokopress' || $ticket_sell == 'wooticket' ) ) {
		$gmt_offset = ( get_option( 'gmt_offset' ) >= '0' ) ? ' +' . get_option( 'gmt_offset' ) : " " . get_option( 'gmt_offset' );
		$gmt_offset = str_replace( array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), $gmt_offset );

		if ( strtotime( tribe_get_end_date( $post, false, 'Y-m-d G:i' ) . $gmt_offset ) <= time() ) {
			return false;
		}
	}

	$cta_text = get_post_meta( $post->ID, '_tokopress_events_cta_text', true );
	$cost = tribe_get_cost( null, true ); 
	if ( $cta_text ) {
		return $cta_text;
	}
	elseif ( !$cta_text && $cost ) {
		return $cost;
	}
	else {
		return false;
	}
}

function tokopress_get_event_cta_url() {
	global $post;
	$ticket_id = tokopress_get_ticket_id();
	$cta_url = get_post_meta( $post->ID, '_tokopress_events_cta_url', true );
	$web_url = tribe_get_event_website_url();
	if ( class_exists('woocommerce') && $ticket_id > 0 ) {
		$product_data = get_post( $ticket_id );
		$_product = wc_get_product( $product_data );
		$url = esc_url( add_query_arg( array('ticket_redirect' => 'cart' ), $_product->add_to_cart_url() ) );
		$url = str_replace( '&#038;', '&amp;', $url );
		return $url;
	}
	elseif ( $cta_url ) {
		return esc_url( $cta_url );
	}
	elseif ( empty( $cta_url ) && $web_url ) {
		return esc_url( $web_url );
	}
	else {
		return '#';
	}
}

function tokopress_get_ticket_id() {
	global $post;
	
	/* TODO: Deprecated this in favor of Event Tickets */
	if ( class_exists( 'Tribe__Tickets__Main' ) || class_exists( 'Tribe__Events__Tickets__Woo__Main' ) ) {
		$ticket_sell = '';
	}
	else {
		$ticket_sell = get_post_meta( $post->ID, '_tokopress_events_ticket', true );
		if ( function_exists( 'tribe_events_admin_show_cost_field' ) && tribe_events_admin_show_cost_field() ) {
			$ticket_cost = floatval( get_post_meta( $post->ID, '_EventCost', true ) );
		}
		else {
			$ticket_cost = floatval( get_post_meta( $post->ID, '_tokopress_events_cost', true ) );
		}
	}

	$ticket_id = 0;
	if ( class_exists( 'woocommerce' ) && $ticket_sell == 'tokopress' ) {

		$ticket_title = sprintf( __( 'Ticket - %s', 'tokopress' ), get_the_title( $post->ID ) );
		$ticket_image = get_post_meta( $post->ID, '_thumbnail_id', true );

		$saved_ticket_id = get_posts( 'numberposts=1&post_type=product&post_status=publish&orderby=date&order=DESC&meta_key=_tokopress_event_id&meta_value=' . $post->ID . '&fields=ids' );
		if ( !empty( $saved_ticket_id ) && $saved_ticket_id['0'] ) {
			$args = array( 
				'ID'           => $saved_ticket_id['0'],
			    'post_excerpt' => '',
			    'post_title'   => $ticket_title,
			);
			$ticket_id = wp_update_post( $args );
		}
		else {
			$args = array( 
            	'post_title'   => $ticket_title,
            	'post_type'    => 'product',
				'post_status'  => 'publish',
            	'post_excerpt' => '',
            	'post_author'  => get_current_user_id(),
			);
			$ticket_id = wp_insert_post( $args );
		}

		if ( intval( $ticket_id ) > 0 ) {
			update_post_meta( $ticket_id, '_tokopress_event_id', $post->ID );

			update_post_meta( $ticket_id, '_visibility', 'hidden' );
			update_post_meta( $ticket_id, '_virtual', 'yes' );
			update_post_meta( $ticket_id, '_downloadable', 'no' );

			update_post_meta( $ticket_id, '_thumbnail_id', $ticket_image );

			update_post_meta( $ticket_id, '_price', $ticket_cost );
			update_post_meta( $ticket_id, '_regular_price', $ticket_cost );

			// update_post_meta( $ticket_id, '_sale_price', '' );
			// update_post_meta( $ticket_id, '_sale_price_dates_from', '' );
			// update_post_meta( $ticket_id, '_sale_price_dates_to', '' );

			// update_post_meta( $ticket_id, 'total_sales', 0 );

			// update_post_meta( $ticket_id, '_weight', '' );
			// update_post_meta( $ticket_id, '_length', '' );
			// update_post_meta( $ticket_id, '_width', '' );
			// update_post_meta( $ticket_id, '_height', '' );

			// update_post_meta( $ticket_id, '_tax_status', 'taxable' );
			// update_post_meta( $ticket_id, '_tax_class', '' );
			// update_post_meta( $ticket_id, '_purchase_note', '' );

			// update_post_meta( $ticket_id, '_product_attributes', array() );
		}
	}
	if ( intval( $ticket_id ) > 0 ) {
		return $ticket_id;
	}
	else {
		return false;
	}
}

add_filter( 'post_type_link', 'tokopress_ticket_to_event_link' , 10, 4 );
function tokopress_ticket_to_event_link( $post_link, $post, $leavename, $sample ) {
	if ( $post->post_type == 'product' ) {
		$event_id = get_post_meta( $post->ID, '_tokopress_event_id', true );
		if ( $event_id > 0 )
			$post_link = get_permalink( $event_id );
	}
	return $post_link;
}

add_action( 'wp_loaded', 'tokopress_ticket_add_redirect_to', 10 );
function tokopress_ticket_add_redirect_to() {
	if ( empty( $_REQUEST['ticket_redirect'] ) || ! $_REQUEST['ticket_redirect'] == 'cart' ) {
		return;
	}
	if ( empty( $_REQUEST['add-to-cart'] ) || ! is_numeric( $_REQUEST['add-to-cart'] ) ) {
		return;
	}
	add_filter( 'woocommerce_add_to_cart_redirect', 'tokopress_ticket_add_redirect_url' );
}

function tokopress_ticket_add_redirect_url( $url ) {
	if ( !class_exists('woocommerce') )
		return $url;

	return WC()->cart->get_cart_url();
}

add_filter( 'tribe_events_event_costs', 'tokopress_ticket_cost_sync', 30, 2 );
function tokopress_ticket_cost_sync( array $event_cost, $event_id ) {
	/* TODO: Deprecated this in favor of Event Tickets */
	if ( ! ( class_exists( 'Tribe__Tickets__Main' ) || class_exists( 'Tribe__Events__Tickets__Woo__Main' ) ) ) {
		$ticket_sell = get_post_meta( $event_id, '_tokopress_events_ticket', true );
		if ( class_exists( 'woocommerce' ) && $ticket_sell == 'tokopress' && function_exists( 'tribe_events_admin_show_cost_field' ) && ! tribe_events_admin_show_cost_field() ) {
			$event_cost[] = intval( get_post_meta( $event_id, '_tokopress_events_cost', true ) );
		}
	}
	return $event_cost;
}

add_filter( 'tribe_events_register_venue_type_args', 'tokopress_events_post_type_supports', 20 );
add_filter( 'tribe_events_register_organizer_type_args', 'tokopress_events_post_type_supports', 20 );
function tokopress_events_post_type_supports( $args ) {
	$args['publicly_queryable'] = true;
	$args['supports'][] = 'thumbnail';
	return $args;
}

add_filter( 'tribe_events_current_view_template', 'tokopress_events_page_templates', 10 );
function tokopress_events_page_templates( $template ) {
	if ( !class_exists('Tribe__Events__Templates') )
		return $template;

	if( is_singular( 'tribe_venue' ) ) {
		$template = Tribe__Events__Templates::getTemplateHierarchy( 'pro/single-venue' );
	}
	if( is_singular( 'tribe_organizer' ) ) {
		$template = Tribe__Events__Templates::getTemplateHierarchy( 'pro/single-organizer' );
	}
	return $template;
}

add_filter('manage_edit-tribe_events_columns', 'tokopress_events_columns_id', 5);
function tokopress_events_columns_id($defaults){
	$defaults = array_merge( array('tp_event_id' => 'ID'), $defaults );
    return $defaults;
}

add_action('manage_posts_custom_column', 'tokopress_events_custom_id_columns', 5, 2);
function tokopress_events_custom_id_columns($column_name, $id){
        if($column_name === 'tp_event_id'){
                echo $id;
    }
}

add_filter( 'tribe_settings_get_option_value_pre_display', 'tokopress_events_tribe_settings_get_option_value_pre_display', 20, 2 );
function tokopress_events_tribe_settings_get_option_value_pre_display( $value, $key ) {
	if ( $key == 'tribeEventsTemplate' ) {
		$value = '';
	}
	elseif ( class_exists('woocommerce') && $key == 'defaultCurrencySymbol' ) {
		$value = get_woocommerce_currency_symbol();
	}
	elseif ( class_exists('woocommerce') && $key == 'reverseCurrencyPosition' ) {
		$pos = get_option( 'woocommerce_currency_pos' );
		if ( $pos == 'right' || $pos == 'right_space' ) {
			$value = 1;
		}
		else {
			$value = 0;
		}
	}
	return $value;
}

add_filter( 'tribe_get_option', 'tokopress_events_tribe_get_option', 20, 2 );
function tokopress_events_tribe_get_option( $option, $optionName ) {
	if ( $optionName == 'tribeEventsTemplate' ) {
		$option = '';
	}
	elseif ( class_exists('woocommerce') && $optionName == 'defaultCurrencySymbol' ) {
		$option = get_woocommerce_currency_symbol();
	}
	elseif ( class_exists('woocommerce') && $optionName == 'reverseCurrencyPosition' ) {
		$pos = get_option( 'woocommerce_currency_pos' );
		if ( $pos == 'right' || $pos == 'right_space' ) {
			$option = 1;
		}
		else {
			$option = 0;
		}
	}
	return $option;
}

add_filter( 'tribe_get_event_meta', 'tokopress_events_tribe_get_event_meta', 10, 4 );
function tokopress_events_tribe_get_event_meta( $output, $postId, $meta, $single ) {
	if ( class_exists('woocommerce') ) {
		$pos = get_option( 'woocommerce_currency_pos' );
		if ( $meta == '_EventCurrencySymbol' ) {
			$output = get_woocommerce_currency_symbol();
			if ( $pos == 'left_space' ) {
				$output = $output.' ';
			}
			elseif ( $pos == 'right_space' ) {
				$output = ' '.$output;
			}
		}
		elseif ( $meta == '_EventCurrencyPosition' ) {
			if ( $pos == 'right' || $pos == 'right_space' ) {
				$output = 1;
			}
			else {
				$output = 0;
			}
		}
	}
	return $output;
}

add_filter( 'get_post_metadata', 'tokopress_events_get_post_metadata', 10, 4 );
function tokopress_events_get_post_metadata( $meta_value, $post_id, $meta_key, $single ) {
	if ( class_exists('woocommerce') ) {
		$pos = get_option( 'woocommerce_currency_pos' );
		if ( $meta_key == '_EventCurrencySymbol' ) {
			$meta_value = get_woocommerce_currency_symbol();
		}
		elseif ( $meta_key == '_EventCurrencyPosition' ) {
			if ( $pos == 'right' || $pos == 'right_space' ) {
				$meta_value = 'suffix';
			}
			else {
				$meta_value = 'prefix';
			}
		}
	}
	return $meta_value;
}
