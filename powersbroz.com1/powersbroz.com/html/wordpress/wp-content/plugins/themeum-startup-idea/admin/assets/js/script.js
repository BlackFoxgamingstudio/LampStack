jQuery(document).ready(function(){'use strict';

/*--------------------------------------------------------------
 * 					Project Money Conform
 *-------------------------------------------------------------*/
jQuery("#confirm-submit-form").on('submit',function(e){'use strict';
	//alert('asd');
		var postdata = jQuery(this).serializeArray();
		var formURL = jQuery(this).attr("action");
		jQuery.ajax(
		{
			url : formURL,
			type: "POST",
			data : postdata,
			success:function(data, textStatus, jqXHR) {
				//jQuery('#project-submit-form')[0].reset();
				//jQuery('#withdraw-msg').modal();
				 window.location.href = jQuery('#redirect_url_confirm').val();
			},
			error: function(jqXHR, textStatus, errorThrown){
				jQuery("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
			}
		});
		e.preventDefault();	//STOP default action
		e.unbind(); //Remove a previously-attached event handler
	});
	jQuery("confirm-submit-form").submit(); //SUBMIT FORM

});