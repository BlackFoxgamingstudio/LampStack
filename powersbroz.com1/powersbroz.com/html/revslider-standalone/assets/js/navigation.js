jQuery(document).ready(function() {
	jQuery("#nav_button_export_slider").click(function(){
		var sliderID = jQuery('#rs_breadcrumbs_slider_settings').data('id');
		if ( isNaN(sliderID) || sliderID == '' ) {
			UniteAdminRev.showInfo({type: 'warning', hideon: '', event: '', content: 'Slider ID should not be empty', hidedelay: 3});
			return;
		}
		var useDummy = jQuery('input[name="export_dummy_images"]').is(':checked');
		var urlAjaxExport = ajaxurl+"&action="+g_uniteDirPlugin+"_ajax_action&client_action=export_slider&dummy=0&nonce=" + g_revNonce;
		urlAjaxExport += "&sliderid=" + sliderID;
		location.href = urlAjaxExport;
	});
});