<?php
global $tp_sidebar, $tp_content_class, $tp_title;
if ( !$tp_content_class ) 
	$tp_content_class = 'col-md-9';
?>

<div id="main-content">
	
	<div class="container">
		<div class="row">
			
			<div class="<?php echo esc_attr( $tp_content_class ); ?>">
				
				<div id="events-calendar-plugins">
					<?php tribe_events_before_html(); ?>
