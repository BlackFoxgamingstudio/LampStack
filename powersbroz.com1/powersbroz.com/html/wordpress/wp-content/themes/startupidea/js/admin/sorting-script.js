jQuery(document).ready(function($) {		
	var slides = $('#themeum-sorting');
			
	slides.sortable({
		update: function(event, ui) {
			$('#themeum-sort-animation').show(); // Show the animate loading gif while waiting
			
			opts = {
				url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
				type: 'POST',
				async: true,
				cache: false,
				dataType: 'json',
				data:{
					action: 'themeum_sort', // Tell WordPress how to handle this ajax request
					order: slides.sortable('toArray').toString() // Passes ID's of list items in	1,3,2 format
				},
				success: function(response) {
					$('#themeum-sort-animation').hide(); // Hide the loading animation
					return; 
				},
				error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
					alert('There was an error saving the updates');
					$('#themeum-sort-animation').hide(); // Hide the loading animation
					return; 
				}
			};
			$.ajax(opts);
		}
	});	
});