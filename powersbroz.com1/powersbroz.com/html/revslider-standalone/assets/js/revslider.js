
var Revslider = new function() {

	var t = this;
	var cssSelector = '.revslider';
	var apiUrl = 'index.php?c=embed';

	t.init = function() {
		if (jQuery('#revslider_script').length)
		{
			apiUrl = jQuery('#revslider_script').attr('src').replace('assets/js/revslider.js', '') + apiUrl;
		}
		initSliders();
	}

	var initSliders = function() {
		jQuery(cssSelector).each(function(key, item) {
			loadSlider(item, key);
		});
	}

	var loadSlider = function(placeholder, key) {
		jQuery.ajax({
			type:		'post',
			url:		apiUrl,
			data:		{
							alias:	jQuery(placeholder).data('alias'),
							key:	key
						},
			success:	function(response) {
							jQuery(placeholder).html(response);
						}
		});
	}
}

jQuery(document).ready(Revslider.init);
