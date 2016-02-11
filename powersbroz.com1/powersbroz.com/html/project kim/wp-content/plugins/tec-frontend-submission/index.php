<?php
/**
 * Plugin Name:  The Events Calendar: Frontend Submission
 * Plugin URI:   http://www.xtensionpress.com
 * Description:  Frontend submission shortcode for The Events Calendar plugin.
 * Author:       XtensionPress
 * Author URI:   http://www.xtensionpress.com
 * Version:      1.0
 * Text Domain:  xtensionpress
 * Domain Path:  languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load Languages
 **/
load_plugin_textdomain( 'xtensionpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

add_action( 'cmb2_init', 'xt_tec_frontend_form_register' );
function xt_tec_frontend_form_register() {

	$cmb = new_cmb2_box( array(
		'id'           => 'tec-frontend-submit-form',
		'object_types' => array( 'tribe_events' ),
		'hookup'       => false,
		'save_fields'  => false,
	) );

	$cmb->add_field( array(
		'name'    => __( 'Event Name', 'xtensionpress' ),
		'id'      => 'event_title',
		'type'    => 'text',
	    'attributes'  => array(
	        'required'    => 'required',
	    ),
	) );

	$cmb->add_field( array(
		'name'    => __( 'Event Description', 'xtensionpress' ),
		'id'      => 'event_content',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 8,
			'media_buttons' => false,
		),
	) );

	$cmb->add_field( array(
	    'name' 	  => __( 'Event Start Date', 'xtensionpress' ),
	    'id'      => 'event_startdate',
	    'type'    => 'text_date',
	    // 'date_format' => 'Y-m-d',
		'desc' => __( 'Choose event start date using this date picker', 'xtensionpress' ),
	    'attributes'  => array(
	        'required'    => 'required',
	    ),
	) );

	$cmb->add_field( array(
	    'name' 	  => __( 'Event Start Time', 'xtensionpress' ),
	    'id'      => 'event_starttime',
	    'type'    => 'text_time',
	    // 'time_format' => "h:i A",
		'desc' => __( 'Leave it empty for all day event', 'xtensionpress' ),
	) );

	$cmb->add_field( array(
	    'name' 	  => __( 'Event End Date', 'xtensionpress' ),
	    'id'      => 'event_enddate',
	    'type'    => 'text_date',
	    // 'date_format' => 'Y-m-d',
		'desc' => __( 'Choose event end date using this date picker', 'xtensionpress' ),
	    'attributes'  => array(
	        'required'    => 'required',
	    ),
	) );

	$cmb->add_field( array(
	    'name' 	  => __( 'Event End Time', 'xtensionpress' ),
	    'id'      => 'event_endtime',
	    'type'    => 'text_time',
	    // 'time_format' => "h:i A",
		'desc' => __( 'Leave it empty for all day event', 'xtensionpress' ),
	) );

	$cmb->add_field( array(
		'name' => __( 'Event Cost', 'xtensionpress' ),
		'desc' => __( 'Enter a 0 for events that are free.', 'xtensionpress' ),
		'id'   => 'event_cost',
		'type' => 'text_small',
	) );

	$cmb->add_field( array(
		'name' => __( 'Event Website', 'xtensionpress' ),
		'id'   => 'event_website',
		'type' => 'text_url',
	) );

	$cmb->add_field( array(
		'name'       => __( 'Event Image', 'xtensionpress' ),
		'id'         => 'event_image',
		'type'       => 'text',
		'attributes' => array(
			'type' => 'file', // Let's use a standard file upload field
		),
	) );

	$cmb->add_field( array(
	    'name'     => __( 'Event Category', 'xtensionpress' ),
	    'id'       => 'event_category',
	    'taxonomy' => 'tribe_events_cat',
	    'type'     => 'taxonomy_select',
	) );

	$cmb->add_field( array(
		'name'       => __( 'Event Venue', 'xtensionpress' ),
		'desc' => __( 'For new venue, please put venue information to the "additional info" section.', 'xtensionpress' ),
		'id'         => 'event_venue',
		'type'       => 'select',
		'show_option_none' => true,
		'options' => xt_tec_frontend_get_posts( array( 'post_type' => 'tribe_venue', 'numberposts' => '-1' ) ),
	) );

	$cmb->add_field( array(
		'name'       => __( 'Event Organizer', 'xtensionpress' ),
		'id'         => 'event_organizer',
		'desc' => __( 'For new organizer, please put organizer information to the "additional info" section.', 'xtensionpress' ),
		'type'       => 'select',
		'show_option_none' => true,
		'options' => xt_tec_frontend_get_posts( array( 'post_type' => 'tribe_organizer', 'numberposts' => '-1' ) ),
	) );

	$cmb->add_field( array(
		'name' => __( 'Additional Info', 'xtensionpress' ),
		'id'   => 'event_notes',
		'type' => 'textarea',
	    'attributes'  => array(
	        'rows'        => 4,
	    ),
    ) );

	if ( !is_user_logged_in() ) { 
		$cmb->add_field( array(
			'name' => __( 'Your Name', 'xtensionpress' ),
			'desc' => __( 'Please enter your name for credit.', 'xtensionpress' ),
			'id'   => 'event_author_name',
			'type' => 'text',
		) );

		$cmb->add_field( array(
			'name' => __( 'Your Email', 'xtensionpress' ),
			'desc' => __( 'Please enter your email so we can contact you.', 'xtensionpress' ),
			'id'   => 'event_author_email',
			'type' => 'text_email',
		) );
	}

}

function xt_tec_frontend_get_posts( $query_args ) {
    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'post',
        'numberposts' => 10,
    ) );
    $posts = get_posts( $args );
    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    }
    return $post_options;
}

function xt_tec_frontend_cmb2_localize_filter( $args ) {
	$args['defaults']['date_picker']['dateFormat'] = "yy-mm-dd";
	$args['defaults']['time_picker']['timeFormat'] = "hh:mm:ss TT";
	return $args;
}

add_shortcode( 'tec-frontend-submission', 'xt_tec_frontend_submission_shortcode' );
function xt_tec_frontend_submission_shortcode( $atts = array() ) {

	if( ! class_exists( 'TribeEvents' ) )
		return '<p class="cmb-error">' . __( 'Please install <strong>The Events Calendar</strong> plugin.', 'xtensionpress' ) . '</p>';

	if ( ! function_exists( 'new_cmb2_box' ) )
		return;

	add_filter( 'cmb2_localized_data', 'xt_tec_frontend_cmb2_localize_filter' );

	// Current user
	$user_id = get_current_user_id();

	// Use ID of metabox in wds_frontend_form_register
	$metabox_id = 'tec-frontend-submit-form';

	// since post ID will not exist yet, just need to pass it something
	$object_id  = 'tec-frontend-object-id';

	// Get CMB2 metabox object
	$cmb = cmb2_get_metabox( $metabox_id, $object_id );

	// Get $cmb object_types
	$post_types = $cmb->prop( 'object_types' );

	// Parse attributes
	$atts = shortcode_atts( array(
		'login_only' => 'no',
		'post_author' => $user_id ? $user_id : 1, // Current user, or admin
		'post_status' => 'pending',
		'post_type'   => reset( $post_types ), // Only use first object_type in array
	), $atts, 'tec-frontend-submission' );

	if ( empty( $atts['login_only'] ) ) {
		$atts['login_only'] = 'no';
	}

	if ( $atts['login_only'] != 'no' && ! is_user_logged_in() ) {
		return '<p class="cmb-error">' . __( 'Please login to submit new event.', 'xtensionpress' ) . '</p>';
	}

	// Initiate our output variable
	$output = '';

	// Handle form saving (if form has been submitted)
	$new_id = xt_tec_frontend_submission_handle( $cmb, $atts );

	if ( $new_id ) {

		if ( is_wp_error( $new_id ) ) {

			// If there was an error with the submission, add it to our ouput.
			$output .= '<p class="cmb-error">' . sprintf( __( 'There was an error in the submission: %s', 'xtensionpress' ), '<strong>'. $new_id->get_error_message() .'</strong>' ) . '</p>';

		} 
		else {

			// Get submitter's name
			$name = isset( $_POST['event_author_name'] ) && $_POST['event_author_name']
				? ' '. $_POST['event_author_name']
				: '';

			// Add notice of submission
			$output .= '<p class="cmb-success">' . sprintf( __( 'Thank you %s, your event has been submitted and is pending review.', 'xtensionpress' ), esc_html( $name ) ) . '</p>';

			return $output;

		}

	}

	// Get our form
	$output .= cmb2_get_metabox_form( $cmb, $object_id, array( 'save_button' => __( 'Submit Event', 'xtensionpress' ) ) );

	return $output;
}

function xt_tec_frontend_submission_handle( $cmb, $post_data = array() ) {

	// If no form submission, bail
	if ( empty( $_POST ) ) {
		return false;
	}

	// check required $_POST variables and security nonce
	if (
		! isset( $_POST['submit-cmb'], $_POST['object_id'], $_POST[ $cmb->nonce() ] )
		|| ! wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() )
	) {
		return new WP_Error( 'security_fail', __( 'Security check failed.' ) );
	}

	if ( empty( $_POST['event_title'] ) ) {
		return new WP_Error( 'post_data_missing', __( 'Event name is required.' ) );
	}

	if ( empty( $_POST['event_startdate'] ) ) {
		return new WP_Error( 'post_data_missing', __( 'Event start date is required.' ) );
	}

	if ( empty( $_POST['event_enddate'] ) ) {
		return new WP_Error( 'post_data_missing', __( 'Event end date is required.' ) );
	}

	/**
	 * Fetch sanitized values
	 */
	$sanitized_values = $cmb->get_sanitized_values( $_POST );

	// Set our post data arguments
	$post_data['post_title']   = $sanitized_values['event_title'];
	unset( $sanitized_values['event_title'] );
	$post_data['post_content'] = isset( $sanitized_values['event_content'] ) ? $sanitized_values['event_content'] : '';
	unset( $sanitized_values['event_content'] );

	// Create the new post
	$new_submission_id = wp_insert_post( $post_data, true );

	// If we hit a snag, update the user
	if ( is_wp_error( $new_submission_id ) ) {
		return $new_submission_id;
	}

	if ( isset( $sanitized_values['event_starttime'] ) && isset( $sanitized_values['event_endtime'] ) && trim( $sanitized_values['event_starttime'] ) && trim( $sanitized_values['event_endtime'] ) ) {
		delete_post_meta( $new_submission_id, '_EventAllDay' );
		$sanitized_values['event_startdate'] = date( TribeDateUtils::DBDATETIMEFORMAT, strtotime( $sanitized_values['event_startdate'] . " " . $sanitized_values['event_starttime'] ) );
		$sanitized_values['event_enddate'] = date( TribeDateUtils::DBDATETIMEFORMAT, strtotime( $sanitized_values['event_enddate'] . " " . $sanitized_values['event_endtime'] ) );
	} 
	else {
		update_post_meta( $new_submission_id, '_EventAllDay', 'yes' );
		$sanitized_values['event_startdate'] = tribe_event_beginning_of_day( $sanitized_values['event_startdate'] );
		$sanitized_values['event_enddate'] = tribe_event_end_of_day( $sanitized_values['event_enddate'] );
	}

	// sanity check that start date < end date
	$startTimestamp = strtotime( $sanitized_values['event_startdate'] );
	$endTimestamp   = strtotime( $sanitized_values['event_enddate'] );

	if ( $startTimestamp > $endTimestamp ) {
		$sanitized_values['event_enddate'] = $sanitized_values['event_startdate'];
	}

	$sanitized_values['event_duration'] = strtotime( $sanitized_values['event_enddate'] ) - $startTimestamp;

	update_post_meta( $new_submission_id, '_EventStartDate', $sanitized_values['event_startdate'] );
	update_post_meta( $new_submission_id, '_EventEndDate', $sanitized_values['event_enddate'] );
	update_post_meta( $new_submission_id, '_EventDuration', $sanitized_values['event_duration'] );

	if ( isset( $sanitized_values['event_website'] ) ) {
		update_post_meta( $new_submission_id, '_EventURL', esc_url( $sanitized_values['event_website'] ) );
	}

	if ( isset( $sanitized_values['event_cost'] ) ) {
		update_post_meta( $new_submission_id, '_EventCost', $sanitized_values['event_cost'] );
	}

	if ( isset( $_POST['event_category'] ) ) {
		$sanitized_values['event_category'] = esc_attr( $_POST['event_category'] );
		wp_set_object_terms( $new_submission_id, $sanitized_values['event_category'], 'tribe_events_cat' );
	}
	
	if ( isset( $sanitized_values['event_venue'] ) ) {
		update_post_meta( $new_submission_id, '_EventVenueID', $sanitized_values['event_venue'] );
	}

	if ( isset( $sanitized_values['event_organizer'] ) ) {
		update_post_meta( $new_submission_id, '_EventOrganizerID', $sanitized_values['event_organizer'] );
	}

	if ( isset( $sanitized_values['event_notes'] ) ) {
		update_post_meta( $new_submission_id, '_xt_event_notes', $sanitized_values['event_notes'] );
	}
	if ( isset( $sanitized_values['event_author_name'] ) ) {
		update_post_meta( $new_submission_id, '_xt_event_author_name', $sanitized_values['event_author_name'] );
	}
	if ( isset( $sanitized_values['event_author_email'] ) ) {
		update_post_meta( $new_submission_id, '_xt_event_author_email', $sanitized_values['event_author_email'] );
	}

	/**
	 * Other than post_type and post_status, we want
	 * our uploaded attachment post to have the same post-data
	 */
	unset( $post_data['post_type'] );
	unset( $post_data['post_status'] );

	// Try to upload the featured image
	$img_id = xt_tec_frontend_submission_handle_upload( $new_submission_id, $post_data );

	// If our photo upload was successful, set the featured image
	if ( $img_id && ! is_wp_error( $img_id ) ) {
		set_post_thumbnail( $new_submission_id, $img_id );
	}

	return $new_submission_id;
}

function xt_tec_frontend_submission_handle_upload( $post_id, $attachment_post_data = array() ) {
	// Make sure the right files were submitted
	if (
		empty( $_FILES )
		|| ! isset( $_FILES['event_image'] )
		|| isset( $_FILES['event_image']['error'] ) && 0 !== $_FILES['event_image']['error']
	) {
		return;
	}

	// Filter out empty array values
	$files = array_filter( $_FILES['event_image'] );

	// Make sure files were submitted at all
	if ( empty( $files ) ) {
		return;
	}

	// Make sure to include the WordPress media uploader API if it's not (front-end)
	if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
	}

	// Upload the file and send back the attachment post ID
	return media_handle_upload( 'event_image', $post_id, $attachment_post_data );
}
