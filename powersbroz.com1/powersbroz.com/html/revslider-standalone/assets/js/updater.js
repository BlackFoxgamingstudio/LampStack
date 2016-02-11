jQuery(document).ready(function() {
	jQuery('#download_addon').click(function(){

		UniteAdminRev.setAjaxLoaderID("download_loader");
		UniteAdminRev.setAjaxHideButtonID("download_addon");

		var data = {
			code: jQuery('input[name="addon_purchase_token"]').val()
		}

		UniteAdminRev.ajaxRequest("download_addon",data);
	});

	jQuery('#deactivate_addon').click(function(){

		UniteAdminRev.setAjaxLoaderID("download_loader");
		UniteAdminRev.setAjaxHideButtonID("download_addon");

		var data = {
			code: jQuery('input[name="addon_purchase_token"]').val()
		}

		UniteAdminRev.ajaxRequest("deactivate_addon",data);
	});
});