<?php

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */

// Register the Metabox
function tokopress_metabox_event_details_add() {
	add_meta_box( 'tokopress-meta-box-event', __( 'Eventica - Event Details', 'tokopress' ), 'tokopress_metabox_event_details_output', 'tribe_events', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'tokopress_metabox_event_details_add' );

// Output the Metabox
function tokopress_metabox_event_details_output( $post ) {
	// create a nonce field
	wp_nonce_field( 'tokopress_meta_box_event_schedule_nonce', 'tokopress_meta_box_nonce' ); 

	/* TODO: Deprecated this in favor of Event Tickets */
	if ( class_exists( 'Tribe__Tickets__Main' ) || class_exists( 'Tribe__Events__Tickets__Woo__Main' ) ) {
		$ticket_sell = '';
	}
	else {
		$ticket_sell = get_post_meta( $post->ID, '_tokopress_events_ticket', true );
		if ( function_exists( 'tribe_events_admin_show_cost_field' ) && tribe_events_admin_show_cost_field() ) {
			$ticket_cost = intval( get_post_meta( $post->ID, '_EventCost', true ) );
		}
		else {
			$ticket_cost = intval( get_post_meta( $post->ID, '_tokopress_events_cost', true ) );
		}

		$ticket_options = array( '' => __( 'No', 'tokopress' ) );

		if ( class_exists( 'Tribe__Tickets__Main' ) )
			$ticket_options['eventtickets'] = __( 'Use Event Tickets', 'tokopress' );

		if ( class_exists( 'woocommerce' ) && class_exists( 'Tribe__Events__Tickets__Woo__Main' ) )
			$ticket_options['wooticket'] = __( 'Use The Event Calendar:WooCommerce Tickets', 'tokopress' );

		if ( class_exists( 'woocommerce' ) )
			$ticket_options['tokopress'] = __( 'Use WooCommerce with Eventica', 'tokopress' );
	}

	$cta_text = get_post_meta( $post->ID, '_tokopress_events_cta_text', true );
	$cta_url = get_post_meta( $post->ID, '_tokopress_events_cta_url', true );

	$schedule = get_post_meta( $post->ID, '_tokopress_events_scedule', true );
	$custom = get_post_meta( $post->ID, '_tokopress_events_custom', true );

	if ( class_exists( 'Tribe__Tickets__Main' ) || class_exists( 'Tribe__Events__Tickets__Woo__Main' ) )
		$attendees = get_post_meta( $post->ID, '_tokopress_events_attendees', true );

	$xtfs_author_name = get_post_meta( $post->ID, '_xt_event_author_name', true );
	$xtfs_author_email = get_post_meta( $post->ID, '_xt_event_author_email', true );
	$xtfs_notes = get_post_meta( $post->ID, '_xt_event_notes', true );

	?>
	
	<table class="form-table">
		<?php if ( class_exists( 'Tribe__Tickets__Main' ) || class_exists( 'Tribe__Events__Tickets__Woo__Main' ) ) : ?>
			<input type="hidden" name="tokopress_event_ticket" id="tokopress_event_ticket" value="" >
		<?php else : ?>
			<tr>
				<th>
					<label for="tokopress_event_text"><?php _e( 'Sell Ticket', 'tokopress' ); ?>:</label>
				</th>
				<td>
					<select name="tokopress_event_ticket" id="tokopress_event_ticket">
						<?php 
						foreach ( $ticket_options as $key => $label ) {
							$selected = ( $ticket_sell && $ticket_sell == $key ) ? ' selected="selected"' : '';
							echo '<option'. $selected .' value="'.$key.'">';
							echo $label;
							echo '</option>';
						} 
						?>
					</select>
					<?php 
					$ticket_id = tokopress_get_ticket_id(); 
					if ( $ticket_id ) {
						$ticket_report_query = array(
							'page' => 'wc-reports',
							'tab' => 'orders',
							'report' => 'sales_by_product',
							'product_ids' => $ticket_id
						);
						$ticket_report_url = esc_url( add_query_arg( $ticket_report_query, admin_url( 'admin.php' ) ) );
						echo '<p><span class="description"><a href="'.$ticket_report_url.'">'.__( 'View Ticket Sales Report', 'tokopress' ).'</a></span></p>';
					}
					?>
				</td>
		    </tr>
			<?php if ( class_exists( 'woocommerce' ) && $ticket_sell == 'tokopress' && function_exists( 'tribe_events_admin_show_cost_field' ) && !tribe_events_admin_show_cost_field() ) : ?>
				<tr>
					<th>
						<label for="tokopress_event_cost"><?php _e( 'Event Cost', 'tokopress' ); ?>:</label>
					</th>
					<td>
						<input type="text" class="small-text" name="tokopress_event_cost" id="tokopress_event_cost" value="<?php echo intval( get_post_meta( $post->ID, '_tokopress_events_cost', true ) ); ?>" >
					</td>
			    </tr>
			<?php endif; ?>
		<?php endif; ?>
		<tr>
			<th>
				<label for="tokopress_event_cta_text"><?php _e( 'Call To Action Text', 'tokopress' ); ?>:</label>
			</th>
			<td>
				<input type="text" class="large-text" name="tokopress_event_cta_text" id="tokopress_event_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" >
				<br>
				<span class="description">
					<?php _e( 'For example: Buy Ticket, Register Now, etc.', 'tokopress' ); ?><br/>
					<?php _e( 'By default, it will show event price if available.', 'tokopress' ); ?>
				</span>
			</td>
	    </tr>
		<tr>
			<th>
				<label for="tokopress_event_cta_url"><?php _e( 'Call To Action URL', 'tokopress' ); ?>:</label>
			</th>
			<td>
				<input type="text" class="large-text" class="wide regular-text" name="tokopress_event_cta_url" id="tokopress_event_cta_url" value="<?php echo esc_url( $cta_url ); ?>" >
				<span class="description">
					<?php _e( 'By default, it will show event website if available.', 'tokopress' ); ?>
				</span>
			</td>
	    </tr>
		<tr>
			<th>
				<label for="tokopress_event_schedule"><?php _e( 'Event Schedule', 'tokopress' ); ?>:</label>
			</th>
			<td>
				<textarea class="large-text" name="tokopress_event_schedule" id="tokopress_event_schedule" cols="60" rows="5"><?php echo esc_textarea( $schedule ); ?></textarea>
				<br>
				<span class="description"><?php _e( 'Please enter your event schedule, separated by new line.', 'tokopress' ); ?></span>
			</td>
	    </tr>
		<tr>
			<th>
				<label for="tokopress_event_custom"><?php _e( 'Custom Content', 'tokopress' ); ?>:</label>
			</th>
			<td>
				<textarea class="large-text" name="tokopress_event_custom" id="tokopress_event_custom" cols="60" rows="5"><?php echo esc_textarea( $custom ); ?></textarea>
				<br>
				<span class="description"><?php _e( 'Please enter your custom content, it will be placed below event schedule section.', 'tokopress' ); ?></span>
			</td>
	    </tr>
		<?php if ( class_exists( 'Tribe__Tickets__Main' ) || class_exists( 'Tribe__Events__Tickets__Woo__Main' ) ) : ?>
			<tr>
				<th>
					<label for="tokopress_event_attendees"><?php _e( 'Event Attendees', 'tokopress' ); ?>:</label>
				</th>
				<td>
					<select name="tokopress_event_attendees" id="tokopress_event_attendees">
						<option <?php if ( $attendees == 'no' ) echo 'selected="selected"'; ?> value="no"><?php _e( 'No', 'tokopress' ); ?></option>
						<option <?php if ( $attendees == 'yes' ) echo 'selected="selected"'; ?> value="yes"><?php _e( 'Yes', 'tokopress' ); ?></option>
					</select>
					<br>
					<span class="description"><?php _e( 'Show attendees list on single event page.', 'tokopress' ); ?></span>
				</td>
		    </tr>
		<?php endif; ?>
	    <?php if ( $xtfs_author_name ) : ?>
			<tr>
				<th>
					<label for="xtfs_author_name"><?php _e( 'Submitter Name', 'tokopress' ); ?>:</label>
				</th>
				<td>
					<?php echo wpautop( $xtfs_author_name ); ?>
				</td>
		    </tr>
	    <?php endif; ?>
	    <?php if ( $xtfs_author_email ) : ?>
			<tr>
				<th>
					<label for="xtfs_author_email"><?php _e( 'Submitter Email', 'tokopress' ); ?>:</label>
				</th>
				<td>
					<?php echo wpautop( $xtfs_author_email ); ?>
				</td>
		    </tr>
	    <?php endif; ?>
	    <?php if ( $xtfs_notes ) : ?>
			<tr>
				<th>
					<label for="xtfs_author_email"><?php _e( 'Submitter Notes', 'tokopress' ); ?>:</label>
				</th>
				<td>
					<?php echo wpautop( $xtfs_notes ); ?>
				</td>
		    </tr>
	    <?php endif; ?>

    </table>
    
	<?php
}

// Save the Metabox values
function tokopress_metabox_event_details_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['tokopress_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['tokopress_meta_box_nonce'], 'tokopress_meta_box_event_schedule_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', $post_id ) ) return;

	if( isset( $_POST['tokopress_event_ticket'] ) )
		update_post_meta( $post_id, '_tokopress_events_ticket', esc_attr( $_POST['tokopress_event_ticket'] ) );

	if( isset( $_POST['tokopress_event_cost'] ) )
		update_post_meta( $post_id, '_tokopress_events_cost', esc_attr( $_POST['tokopress_event_cost'] ) );

	if( isset( $_POST['tokopress_event_cta_text'] ) )
		update_post_meta( $post_id, '_tokopress_events_cta_text', esc_attr( $_POST['tokopress_event_cta_text'] ) );

	if( isset( $_POST['tokopress_event_cta_url'] ) )
		update_post_meta( $post_id, '_tokopress_events_cta_url', esc_url( $_POST['tokopress_event_cta_url'] ) );

	if( isset( $_POST['tokopress_event_schedule'] ) )
		update_post_meta( $post_id, '_tokopress_events_scedule', wp_kses_data( $_POST['tokopress_event_schedule'] ) );

	if( isset( $_POST['tokopress_event_custom'] ) )
		update_post_meta( $post_id, '_tokopress_events_custom', wp_kses_post( $_POST['tokopress_event_custom'] ) );

	if( isset( $_POST['tokopress_event_attendees'] ) )
		update_post_meta( $post_id, '_tokopress_events_attendees', esc_attr( $_POST['tokopress_event_attendees'] ) );
}
add_action( 'save_post', 'tokopress_metabox_event_details_save' );
