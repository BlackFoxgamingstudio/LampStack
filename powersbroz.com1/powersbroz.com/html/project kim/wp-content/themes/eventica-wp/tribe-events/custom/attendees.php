<?php

$attendees = get_post_meta( get_the_ID(), '_tokopress_events_attendees', true );

if ( $attendees != 'yes' )
	return;

if ( ! ( class_exists('Tribe__Tickets__Tickets') || class_exists('Tribe__Events__Tickets__Tickets') ) )
	return;

if ( class_exists('Tribe__Tickets__Tickets') ) {
	$attendees = Tribe__Tickets__Tickets::get_event_attendees( get_the_ID() );
}
elseif ( class_exists('Tribe__Events__Tickets__Tickets') ) {
	$attendees = Tribe__Events__Tickets__Tickets::get_event_attendees( get_the_ID() );
}

if ( empty($attendees) ) 
	return;

$attendees_id = array();
foreach ( $attendees as $key => $row ) {
	$attendees_id[$key] = $row['attendee_id'];
}
array_multisort( $attendees_id, SORT_ASC, $attendees );
?>

<div class="event-attendees-wrap">
	<div class="event-attendees-title">
		<h2><?php _e( 'Event Attendees', 'tokopress' ); ?></h2>
	</div>

	<div class="event-attendees-list">
		<table>
			<tr>
				<th class="ticket-no">No.</th>
				<th class="ticket-name">Name</th>
				<th class="ticket-id">Ticket ID</th>
				<th class="ticket-type">Ticket Type</th>
			</tr>
			<?php $i = 0; ?>
			<?php foreach( $attendees as $attendee ) : $i++; ?>
				<tr>
					<td class="ticket-no"><?php echo $i; ?>.</td>
					<td class="ticket-name"><?php echo $attendee['purchaser_name']; ?></td>
					<td class="ticket-id"><?php echo $attendee['attendee_id']; ?></td>
					<td class="ticket-type"><?php echo $attendee['ticket']; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
