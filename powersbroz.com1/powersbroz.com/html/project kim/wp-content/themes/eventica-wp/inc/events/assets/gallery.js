
/*--------------------------------------------------------------
Gallery Metabox JS
Credits: WooCommerce Product Gallery Images
--------------------------------------------------------------*/

jQuery( function( $ ){
	'use strict';
	
	var gallery_image_frame;
	var $gallery_image_ids = $('#gallery_image_ids');
	var $gallery_images = $('#gallery_images_container ul.gallery_images');

	jQuery('.add_gallery_images').on( 'click', 'a', function( event ) {
		var $el = $(this);
		var attachment_ids = $gallery_image_ids.val();

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( gallery_image_frame ) {
			gallery_image_frame.open();
			return;
		}

		// Create the media frame.
		gallery_image_frame = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable :	'all',
					multiple: true,
				})
			]
		});

		// When an image is selected, run a callback.
		gallery_image_frame.on( 'select', function() {

			var selection = gallery_image_frame.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

					var attachment_url = attachment.sizes.thumbnail.url ? attachment.sizes.thumbnail.url : attachment.url;

					var image = '';
					image = image + '<li class="image" data-attachment_id="' + attachment.id + '">';
					image = image + '<img src="' + attachment_url + '" />';
					image = image + '<ul class="actions">';
					image = image + '<li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li>';
					image = image + '</ul>';
					image = image + '</li>';
					$gallery_images.append( image );

				}

			});

			$gallery_image_ids.val( attachment_ids );
		});

		// Finally, open the modal.
		gallery_image_frame.open();
	});

	// Image ordering
	$gallery_images.sortable({
		items: 'li.image',
		cursor: 'move',
		// scrollSensitivity:40,
		forcePlaceholderSize: true,
		// forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'gallery-metabox-sortable-placeholder',
		start:function(event,ui){
			ui.item.css('background-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
		},
		update: function(event, ui) {
			var attachment_ids = '';

			$('#gallery_images_container ul li.image').css('cursor','default').each(function() {
				var attachment_id = jQuery(this).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$gallery_image_ids.val( attachment_ids );
		}
	});

	// Remove images
	$('#gallery_images_container').on( 'click', 'a.delete', function() {
		$(this).closest('li.image').remove();

		var attachment_ids = '';

		$('#gallery_images_container ul li.image').css('cursor','default').each(function() {
			var attachment_id = jQuery(this).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$gallery_image_ids.val( attachment_ids );

		return false;
	});
});
