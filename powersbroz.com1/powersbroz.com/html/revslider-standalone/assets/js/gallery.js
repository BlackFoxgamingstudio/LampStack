
/**
 * Nwdthemes Standalone Slider Revolution
 *
 * @package     StandaloneRevslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2015. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */

var Gallery = new function(){

	var t = this;

	var isMultipleUpload = null;
	var onInsertCallback = null;

	t.open = function(title,onInsert,isMultiple) {

		isMultipleUpload = isMultiple == undefined ? false : isMultiple;
		onInsertCallback = onInsert;

		jQuery('<div class="gallery_dialog" />').load(g_urlMediaGallery, function() {

			initGallery();
			initDialog();
			initUploader();

		}).dialog({
			title: title,
			minWidth:900,
			minHeight:500,
			modal:true,
			dialogClass:"tpdialogs",
			open: function(event, ui) {
				jQuery(event.target).parents('.ui-dialog').attr('id', 'viewWrapper');
			},
			close: function(event, ui) {
				jQuery('#fine-uploader').remove();
				jQuery('.color-box').colorbox().remove();
			}
		});
	}

	var initGallery = function() {

		// select action

		jQuery('.gallery .select-handle').on('click', function() {
			var $this = jQuery(this).parents('.photo-box');
			if (isMultipleUpload)
			{
				if ($this.hasClass('selected'))
				{
					$this.removeClass('selected');
				}
				else
				{
					$this.addClass('selected');
				}
			}
			else
			{
				jQuery('.gallery .photo-box.selected').removeClass('selected');
				$this.addClass('selected');
			}
			jQuery('.gallery .insert-button').attr('disabled', jQuery('.gallery .photo-box.selected').length ? '' : 'disabled');
			return false;
		});

		// delete action

		jQuery('.delete-anchor').click(function(){
			if(confirm('Are you sure want to delete this image?'))
			{
				jQuery.ajax({
					url:jQuery(this).attr('href'),
					dataType: 'json',
					beforeSend: function()
					{
						jQuery('.file-upload-messages-container:first').show();
						jQuery('.file-upload-message').html("Deleting image...");
					},
					success: function(response) {
						if (response.success)
						{
							loadPhotoGallery();
						}
						else
						{
							jQuery('.qq-upload-list').append('<li class="qq-upload-fail" title="' + response.responseProperty + '"><span class="qq-upload-status-text">' + response.responseProperty + '</span></li>');
						}
					}
				});
			}
			return false;
		});

		// preview action

		jQuery('.color-box').colorbox({
			rel: 'color-box'
		});

	}

	var initDialog = function() {

		jQuery('.gallery .insert-button').on('click', function() {
			if (jQuery('.gallery .photo-box.selected').length)
			{
				if (isMultipleUpload)
				{
					var arrImages = [];
					jQuery('.gallery .photo-box.selected').each(function(key, photo) {
						var $photo = jQuery(photo);
						arrImages.push({
							url:$photo.data('url'),
							id:$photo.data('id')
						});
					});
					onInsertCallback(arrImages);
				}
				else
				{
					var $photo = jQuery('.gallery .photo-box.selected');
					onInsertCallback($photo.data('url'), $photo.data('id'));
				}
				jQuery('.gallery_dialog').dialog('close');
			}
		});

	}

	var initUploader = function() {

		var uploader = new qq.FineUploader({
			element: document.getElementById('fine-uploader'),
			request: {
				 endpoint: g_urlMediaUpload
			},
			validation: {
				 allowedExtensions: ['jpeg', 'jpg', 'png', 'gif']
			},
			callbacks: {
				 onComplete: function(id, fileName, responseJSON) {
					if (responseJSON.success)
					{
						loadPhotoGallery();
					}
				 }
			},
			failedUploadTextDisplay: {
				mode: 'custom',
					 responseProperty: 'responseProperty'
			},
			debug: false
		});

	}

	var loadPhotoGallery = function() {

		jQuery.ajax({
			url: g_urlMediaAjax,
			cache: false,
			dataType: 'text',
			beforeSend: function() {
				jQuery('.file-upload-messages-container:first').show();
				jQuery('.file-upload-message').html("Loading images...");
			},
			complete: function() {
				jQuery('.file-upload-messages-container').hide();
				jQuery('.file-upload-message').html('');
			},
			success: function(data){
				jQuery('#gallery_content').html(data);
				initGallery();
				initUploader();
			}
		});

	}

}