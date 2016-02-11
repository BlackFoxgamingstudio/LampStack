function reloadinfo(thatthing)
{

	$('#bmore').hide();
	$('#morepreload').show();
		
	numitems = $('#numitems').val();
	
	switch (thatthing) {
		case 'followers':
			urltarget = 'ajax/reload-followers-dashboard/r:';
			break;
		case 'following':
			urltarget = 'ajax/reload-following-dashboard/r:';
			break;
		case 'albums':
			urltarget = 'ajax/reload-albums-dashboard/r:';
			break;
		case 'likes':
			urltarget = 'ajax/reload-likes-dashboard/r:';
			break;
		case 'comments':
			urltarget = 'ajax/reload-comments-dashboard/r:';
			break;
		case 'activities':
			urltarget = 'ajax/reload-activities-dashboard/r:';
			break;
		case 'notifications':
			urltarget = 'ajax/reload-notifications-dashboard/r:';
			break;
		case 'messages':
			urltarget = 'ajax/reload-messages-dashboard/r:';
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

function savePhoto(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');
	withoutAlbum = $('#withoutAlbum').val();
	if (withoutAlbum == 1) {
		album = $.trim($('#album').val());
		if (album == '') {
			$('#album').val(album);
			openandclose(diverror,msg04,1700);
			$('#album').focus();
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
			return;
		}
	} else {
		album = $('#album').val();
	}

	loadedimage=$.trim($('#loadedimage').val());
	if (loadedimage == '') {
		openandclose(diverror,msg03,1700);
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000); 
		return;
	}
	didchanges=$('#didchanges').val();
	
	title = $.trim($('#title').val());
	if (title == '') {
		$('#title').val(title);
		openandclose(diverror,msg05,1700);
		$('#title').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}
	
	description = $.trim($('#description').val());

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myitems/r:' + Math.round(Math.random()*1000),
		data: 'todo=1&wa=' + withoutAlbum + '&a=' + album + '&t=' + encodeURIComponent(title) + '&dch=' + didchanges + '&ph=' + loadedimage + '&d=' + encodeURIComponent(description),
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					$('.tabtitle').fadeOut("slow");
					$('#loadedimage').val('');
					$('#didchanges').val(0);
					$('#formedit').fadeOut("slow",function(){
						$('#formedit').hide(function(){
							$('#linkother').attr('href',siteurl + 'dashboard/myitems/tab:2/a:' + resp.substring(3))
							$('#msgok1').fadeIn('slow');
						});
					});
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

function saveFolder(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');
	
	namealbum = $.trim($('#namealbum').val());
	if (namealbum == '') {
		$('#namealbum').val(namealbum);
		openandclose(diverror,msg04,1700);
		$('#namealbum').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}
	
	description = $.trim($('#description').val());

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myitems/r:' + Math.round(Math.random()*1000),
		data: 'todo=2&n=' + encodeURIComponent(namealbum) + '&d=' + encodeURIComponent(description) ,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					$('#linkaddphotos').attr('href', siteurl + 'dashboard/myitems/tab:2/a:' + resp.substring(3));
					$('#linkaddvideos').attr('href', siteurl + 'dashboard/myitems/tab:3/a:' + resp.substring(3));
					$('#formedit').fadeOut("slow",function(){
						$('#formedit').hide(function(){
							$('#msgok1').fadeIn('slow');
						});
					});	
					break;
			}
		},
		error: function(){
			openandclose(diverror,mi_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/***********************************************************************/

function editfolder(codea)
{
	$('#headonealbum_' + codea).slideUp('slow', function(){
		$('#formonealbum_' + codea).slideDown('slow',function(){
			$('#namealbum_' + codea).focus();
		});
	})
}

function canceleditfolder(codea)
{
	$('#formonealbum_' + codea).slideUp('slow', function(){
		$('#headonealbum_' + codea).slideDown('slow',function(){
			$('#namealbum_' + codea).val($('#na_' + codea).text());
			$('#description_' + codea).val($('#da_' + codea).text());
		});
	})
}

function updateFolder(diverror, bsubmit, codea)
{
	$(bsubmit).attr('disabled','true');
	
	namealbum = $.trim($('#namealbum_' + codea).val());
	if (namealbum == '') {
		$('#namealbum_' + codea).val(namealbum);
		openandclose(diverror,msg_noname,1700);
		$('#namealbum_' + codea).focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}
	
	description = $.trim($('#description_' + codea).val());

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myitems/r:' + Math.round(Math.random()*1000),
		data: 'todo=4&n=' + encodeURIComponent(namealbum) + '&d=' + encodeURIComponent(description) + '&c=' + codea,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					
					elem = resp.substring(3).split('|#||#|');
					$('#na_' + codea).html(elem[0]);
					$('#da_' + codea).html(elem[1]);
					$('#formonealbum_' + codea).slideUp('slow', function(){
						$('#headonealbum_' + codea).slideDown('slow', function(){
							$(bsubmit).removeAttr('disabled');	
						});
					})
					break;
			}
		},
		error: function(){
			openandclose(diverror, norequest, 2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/***********************************************************************/

function deleteFolder(codea)
{
	if (confirm(msgalert)) {
		
		$.ajax({
			type: 'POST',
			url: siteurl + 'ajax/dashboard-myitems/r:' + Math.round(Math.random()*1000),
			data: 'todo=3&ca=' + codea,
			success: function(resp){
				switch(resp.charAt(0)){
					case '0':
						alert(resp.substring(3));
						break;
					case '1':
						$('#a_' + codea).fadeOut(500, function() { $('#a' + codea).remove(); });
						break;
				}
			},
			error: function(){
				alert(dashboard_norequest);
			} //end error
		}); // end ajax
		
	}
}


/***********************************************************************/

function editphoto(codep)
{
	$('#headonephoto_' + codep).slideUp('slow', function(){
		$('#formonephoto_' + codep).slideDown('slow',function(){
			$('#namephoto_' + codep).focus();
		});
	})
}

function canceleditphoto(codep)
{
	$('#formonephoto_' + codep).slideUp('slow', function(){
		$('#headonephoto_' + codep).slideDown('slow',function(){
			$('#namephoto_' + codep).val($('#tf_' + codep).text());
			$('#description_' + codep).val($('#df_' + codep).text())
		});
	})
}

function updateItem(diverror, bsubmit, codep)
{
	$(bsubmit).attr('disabled','true');
	
	namephoto = $.trim($('#namephoto_' + codep).val());
	if (namephoto == '') {
		$('#namephoto_' + codep).val(namephoto);
		openandclose(diverror,msg_noname,1700);
		$('#namephoto_' + codep).focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}
	
	description = $.trim($('#description_' + codep).val());

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myitems/r:' + Math.round(Math.random()*1000),
		data: 'todo=6&n=' + encodeURIComponent(namephoto) + '&d=' + encodeURIComponent(description) + '&c=' + codep,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					
					elem = resp.substring(3).split('|#||#|');
					$('#tf_' + codep).html(elem[0]);
					$('#df_' + codep).html(elem[1]);
					$('#formonephoto_' + codep).slideUp('slow', function(){
						$('#headonephoto_' + codep).slideDown('slow',function(){
							$(bsubmit).removeAttr('disabled');	
						});
					})
					break;
			}
		},
		error: function(){
			openandclose(diverror, norequest, 2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/***********************************************************************/

function deleteItem(codei)
{
	if (confirm(msgalert)) {
		
		$.ajax({
			type: 'POST',
			url: siteurl + 'ajax/dashboard-myitems/r:' + Math.round(Math.random()*1000),
			data: 'todo=5&ci=' + codei,
			success: function(resp){
				switch(resp.charAt(0)){
					case '0':
						alert(resp.substring(3));
						break;
					case '1':
						$('#oneph_' + codei).fadeOut(500, function() { $('#oneph' + codei).remove(); });
						break;
				}
			},
			error: function(){
				alert(dashboard_norequest);
			} //end error
		}); // end ajax
		
	}
}


/***********************************************************************/

function previewVideo(diverror,divok,bsubmit,divform)
{
	$(bsubmit).attr('disabled','true');

	urlvideo=$.trim($('#urlvideo').val());
	if(urlvideo=='')
	{
		$('#urlvideo').val(urlvideo);
		$('#urlvideo').focus();
		openandclose(diverror,vmsg1,1700);
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000); 
		return;
	}
	
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/dashboard-getformvideoyoutube/r:" + Math.round(Math.random()*1000),
		data: 'url=' + urlvideo,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),1700);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000); 
					break;
				case '1':
					$("#msgvideoyt").html(resp.substring(3));
					$(divform).slideUp("slow",function(){
						$(divform).hide(function(){
							$("#msgvideoyt").slideDown('slow');
							$(bsubmit).removeAttr('disabled');
						});
					}); 
			}
		},
		error: function(){
			openandclose(diverror,norequest,1700);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000); 
		} //fin error
	}); // fin ajax		
	
}

/***********************************************************************/

function saveVideo(diverror,divok,bsubmit,divform)
{
	$(bsubmit).attr('disabled','true');
	
	withoutAlbum = $('#withoutAlbum').val();
	if (withoutAlbum == 1) {
		album = $.trim($('#album').val());
		if (album == '') {
			$('#album').val(album);
			openandclose(diverror,vmsg3,1700);
			$('#album').focus();
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
			return;
		}
	} else {
		album = $('#album').val();
	}

	vtitle=$.trim($('#vtitle').val());
	if (vtitle == '') {
		$('#vtitle').val(vtitle);
		openandclose(diverror,vmsg2,1700);
		$('#vtitle').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}

	vdescription=$.trim($('#vdescription').val());
	
	cyt=$('#codyt').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myitems/r:' + Math.round(Math.random()*1000),
		data: 'todo=7&wa=' + withoutAlbum + '&a=' + album + '&t=' + encodeURIComponent(vtitle) + '&d=' + encodeURIComponent(vdescription) + '&cyt=' + cyt,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					$('.tabtitle').fadeOut("slow");
					$(divform).fadeOut("slow",function(){
						$(divform).hide(function(){
							$('#linkother').attr('href',siteurl + 'dashboard/myitems/tab:3/a:' + resp.substring(3))
							$(divok).fadeIn('slow');
						});
					}); 
			}
		},
		error: function(){
			openandclose(diverror,norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //fin error
	}); // fin ajax	

}

