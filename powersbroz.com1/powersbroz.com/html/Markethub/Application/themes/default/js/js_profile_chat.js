function reload_msgchat()
{
	$('#linkmore').hide();
	$('#morepreload').show();
	
	numitems = $('#numitems').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/reload-messages/r:' + Math.round(Math.random()*1000),
		data: 'ni=' + numitems + '&uid=' + uid,
		success: function(h){
			switch(h.charAt(0)){
				case '0':
					alert(h.substring(3));
					$('#morepreload').hide();
					$('#linkmore').show();
					break;
				
				case '1':
					cad = h.substring(3);
					$('.txtchats').prepend(cad);
					$('#numitems').val(parseInt($('#numitems').val()) + parseInt(itemperpage));
					
					$('#morepreload').hide();
					$('#linkmore').show();
					break;
					
				case '2':
					cad = h.substring(3);
					$('.txtchats').prepend(cad);
					$('.loadmorechat').hide();
					break;
			}
		},
		error: function(){
			alert(txt_norequest);
			$('#morepreload').hide();
			$('#bmore').show();
		} //end error
	}); // end ajax
}


/***********************************************************************/

