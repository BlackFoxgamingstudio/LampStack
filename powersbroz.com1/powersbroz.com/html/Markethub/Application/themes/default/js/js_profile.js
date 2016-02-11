function reloadinfo(thatthing)
{

	$('#bmore').hide();
	$('#morepreload').show();

	
	numitems = $('#numitems').val();
	
	switch (thatthing) {
		case 'followers':
			urltarget = 'ajax/reload-followers/r:';
			break;
		case 'following':
			urltarget = 'ajax/reload-following/r:';
			break;
		case 'albums':
			urltarget = 'ajax/reload-albums/r:';
			break;
		case 'likes':
			urltarget = 'ajax/reload-likes/r:';
			break;
		case 'activities':
			urltarget = 'ajax/reload-activities/r:';
			break;
	}

	$.ajax({
		type: 'POST',
		url: siteurl + urltarget + Math.round(Math.random()*1000),
		data: 'ni=' + numitems + '&idu=' + idu,
		success: function(h){
			switch(h.charAt(0)){
				case '0':
					alert(h.substring(3));
					$('#morepreload').hide();
					$('#bmore').show();
					break;
				
				case '1':
					cad = h.substring(3);
					$('#moreitems').append(cad);
					$('#numitems').val(parseInt($('#numitems').val()) + parseInt(itemperpage));
					
					$('#morepreload').hide();
					$('#bmore').show();
					break;
					
				case '2':
					cad = h.substring(3);
					$('#moreitems').append(cad);
					$('#moreitemsbar').hide();
					//$('#numitems').val(parseInt($('#numitems').val()) + parseInt(itemperpage));
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

function saveComment(diverror, bsubmit)
{
	
	comment = $.trim($('#comment').val());
	if (comment == '') {
		$('#comment').val(comment);
		openandclose(diverror,msgnocomment,1700);
		$('#comment').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}
	
	ip = $('#ip').val();
	iu = $('#iu').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/profile-items/r:' + Math.round(Math.random()*1000),
		data: 'todo=1&c=' + encodeURIComponent(comment) + '&ip=' + ip + '&iu=' + iu,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					newtext = resp.substring(3);
					if($('#nocomments')) $('#nocomments').slideUp('slow');
					$('#newcomment').html(newtext);
					$('#newcomment').slideDown('slow', function(){
						$('#sectioncomment').hide('slow');
					});
					
					$('#numcom').html(parseInt($('#numcom').html()) + 1);
					break;
			}
		},
		error: function(){
			openandclose(diverror,norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/***********************************************************************/

function deleteComments(idc, idp, idu) {
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/profile-items/r:' + Math.round(Math.random()*1000),
		data: 'todo=2&idc=' + idc + '&idp=' + idp + '&idu=' + idu,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					alert(resp.substring(3));
					break;
				case '1':
					$('#oc_' + idc).fadeOut(500, function() { $('#oc_' + idc).remove(); });
					$('#numcom').html(parseInt($('#numcom').html()) - 1);
					break;
			};				
		},
		error: function(){
			alert(msg_norequest);
		} //end error
	}); // end ajax	
}

/***********************************************************************/


function liked(idp, whatdo) {
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/profile-items/r:' + Math.round(Math.random()*1000),
		data: 'todo=3&w=' + whatdo + '&idp=' + idp + '&iu=' + iu,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					alert(resp.substring(3));
					break;
				case '1':
					if (whatdo==1) {
						$('#numlikes').html(parseInt($('#numlikes').html()) + 1);
						$('.liked').html('<span><img src="' + siteurl + 'themes/default/imgs/icolike.png"></span>');
					} else {
						if (whatdo==0) {
							$('#numlikes').html(parseInt($('#numlikes').html()) - 1);
							$('.liked').html('<span><img src="' + siteurl + 'themes/default/imgs/icounlike.png"></span>');
						}						
					}
					break;
			};				
		},
		error: function(){
			alert(norequest);
		} //end error
	}); // end ajax		
}

/*********************************************************/

function censored(idp, whatdo) {
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/profile-items/r:' + Math.round(Math.random()*1000),
		data: 'todo=4&w=' + whatdo + '&idp=' + idp + '&iu=' + iu,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					alert(resp.substring(3));
					break;
				case '1':
					if (whatdo==1) {
						$('.censored').html('<span><img src="' + siteurl + 'themes/default/imgs/icoflagred.png"></span>');
					} else {
						if (whatdo==0) {
							$('.censored').html('<span><img src="' + siteurl + 'themes/default/imgs/icoflaggreen.png"></span>');
						}						
					}
					break;
			};				
		},
		error: function(){
			alert(norequest);
		} //end error
	}); // end ajax		
}

/*********************************************************/
