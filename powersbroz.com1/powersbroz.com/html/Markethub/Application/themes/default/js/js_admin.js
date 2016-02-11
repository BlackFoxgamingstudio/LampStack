
function updateAdsP(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	adsp1=$.trim($('#adsp1').val());

	adsp2=$.trim($('#adsp2').val());
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-ads/r:' + Math.round(Math.random()*1000),
		data: 'todo=1&adsp1=' + encodeURIComponent(adsp1) + '&adsp2=' + encodeURIComponent(adsp2),
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
	
}

/**********************************************************************/

function updateAdsD(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	adsd1=$.trim($('#adsd1').val());

	adsd2=$.trim($('#adsd2').val());
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-ads/r:' + Math.round(Math.random()*1000),
		data: 'todo=2&adsd1=' + encodeURIComponent(adsd1) + '&adsd2=' + encodeURIComponent(adsd2),
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax		
}

/**********************************************************************/

function updateLanguage(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	language=$.trim($('#language').val());
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-language/r:' + Math.round(Math.random()*1000),
		data: 'l=' + language,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax		
}

/**********************************************************************/

function updateTheme(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	theme=$.trim($('#theme').val());
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-theme/r:' + Math.round(Math.random()*1000),
		data: 't=' + theme,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax		
}

/**********************************************************************/

function updateStatus(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	mstatus = $('#mstatus').val();
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-details/r:' + Math.round(Math.random()*1000),
		data: 'todo=1&st=' + mstatus + '&uid=' + uidd,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax		
}

/**********************************************************************/


function updateValidated(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	mvalidated = $('#mvalidated').val();
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-details/r:' + Math.round(Math.random()*1000),
		data: 'todo=4&mv=' + mvalidated + '&uid=' + uidd,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax		
}

/**********************************************************************/

function updateLevel(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	level=$('#level').val();
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-details/r:' + Math.round(Math.random()*1000),
		data: 'todo=2&lv=' + level + '&uid=' + uidd,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax		
}

/**********************************************************************/

function deleteUser(diverror,divok,bsubmit)
{
	
	if (confirm(msgalert)) {	
	
		$(bsubmit).attr('disabled','true');
		
		$.ajax({
			type: 'POST',
			url: siteurl + 'ajax/admin-details/r:' + Math.round(Math.random()*1000),
			data: 'todo=3&uid=' + uidd,
			success: function(resp){
				switch(resp.charAt(0)){
					case '0':
						openandclose(diverror,resp.substring(3),2000);
						setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
						break;
					case '1':
						self.location = siteurl + 'admin/users';
						break;
				}
			},
			error: function(){
				openandclose(diverror,admin_norequest,2000);
				setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
			} //end error
		}); // end ajax		
		
	}
}

/**********************************************************************/

function updateGeneral(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');
	
	titlesite=$.trim($('#titlesite').val());
	if (titlesite == '') {
		$('#titlesite').val(titlesite);
		openandclose(diverror,txt_error1,1700);
		$('#titlesite').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}
	
	descsite=$.trim($('#descsite').val());
	if (descsite == '') {
		$('#descsite').val(descsite);
		openandclose(diverror,txt_error2,1700);
		$('#descsite').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}
	
	keywsite=$.trim($('#keywsite').val());
	if (keywsite == '') {
		$('#keywsite').val(keywsite);
		openandclose(diverror,txt_error3,1700);
		$('#keywsite').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}

	protected = $('#protected').val();

	language = $('#language').val();
	
	spages = $('#spages').val();
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-general/r:' + Math.round(Math.random()*1000),
		data: 'todo=1&ts=' + encodeURIComponent(titlesite) + '&ds=' + encodeURIComponent(descsite) + '&ks=' + encodeURIComponent(keywsite) + '&prt=' + protected + '&lng=' + language + '&spg=' + spages,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
	
}

/**********************************************************************/

function updateUserNotficactions(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	notievents = $('#notievents').val();
	notieventsinterval = $('#notieventsinterval').val();

	notimsg = $('#notimsg').val();
	notieventsintervalmsg = $('#notieventsintervalmsg').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-general/r:' + Math.round(Math.random()*1000),
		data: 'todo=2&ne=' + notievents + '&ine=' + notieventsinterval + '&nm=' + notimsg + '&inm=' + notieventsintervalmsg,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
	
}

/**********************************************************************/

function updateUserChats(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	numchatstart = $('#numchatstart').val();
	intervalmsgchat = $('#intervalmsgchat').val();
	chatemoticons = $('#chatemoticons').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-general/r:' + Math.round(Math.random()*1000),
		data: 'todo=3&ncs=' + numchatstart + '&imc=' + intervalmsgchat + '&ce=' + chatemoticons,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
	
}

/**********************************************************************/

function updateShowItems(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	numactivities = $('#numactivities').val();
	numfollowers = $('#numfollowers').val();
	numfollowing = $('#numfollowing').val();
	numnotifications = $('#numnotifications').val();
	numitemmsg = $('#numitemmsg').val();
	numfavorites = $('#numfavorites').val();
	numsearch = $('#numsearch').val();
	numdirmedia = $('#numdirmedia').val();
	numinstantsearch = $('#numinstantsearch').val();
	numdirpeople = $('#numdirpeople').val();
	numrecenthome = $('#numrecenthome').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-general/r:' + Math.round(Math.random()*1000),
		data: 'todo=4&na=' + numactivities + '&nfs=' + numfollowers + '&nfg=' + numfollowing + '&nn=' + numnotifications + '&nim=' + numitemmsg + '&nf=' + numfavorites + '&ns=' + numsearch + '&nmd=' + numdirmedia + '&nis=' + numinstantsearch + '&ndp=' + numdirpeople + '&nrh=' + numrecenthome,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
	
}

/**********************************************************************/

function updatePages(diverror, divok, bsubmit, page)
{
	$(bsubmit).attr('disabled','true');
	
	if (page == 1) {
		txtabout=$.trim($('#txtabout').val());
		if (txtabout == '') {
			$('#txtabout').val(txtabout);
			openandclose(diverror,admin_txt_error,1700);
			$('#txtabout').focus();
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
			return;
		}
		txtdata = '&txtpage=' + encodeURIComponent(txtabout);
	}

	if (page == 2) {
		txtprivacy=$.trim($('#txtprivacy').val());
		if (txtprivacy == '') {
			$('#txtprivacy').val(txtprivacy);
			openandclose(diverror,admin_txt_error,1700);
			$('#txtprivacy').focus();
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
			return;
		}
		txtdata = '&txtpage=' + encodeURIComponent(txtprivacy);
	}
	
	if (page == 3) {
		txttermsofuse=$.trim($('#txttermsofuse').val());
		if (txttermsofuse == '') {
			$('#txttermsofuse').val(txttermsofuse);
			openandclose(diverror,admin_txt_error,1700);
			$('#txttermsofuse').focus();
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
			return;
		}
		txtdata = '&txtpage=' + encodeURIComponent(txttermsofuse);
	}
	
	if (page == 4) {
		txtdisclaimer=$.trim($('#txtdisclaimer').val());
		if (txtdisclaimer == '') {
			$('#txtdisclaimer').val(txtdisclaimer);
			openandclose(diverror,admin_txt_error,1700);
			$('#txtdisclaimer').focus();
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
			return;
		}
		txtdata = '&txtpage=' + encodeURIComponent(txtdisclaimer);
	}
	
	if (page == 5) {
		txtcontact=$.trim($('#txtcontact').val());
		if (txtcontact == '') {
			$('#txtcontact').val(txtcontact);
			openandclose(diverror,admin_txt_error,1700);
			$('#txtcontact').focus();
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
			return;
		}
		txtdata = '&txtpage=' + encodeURIComponent(txtcontact);
	}
	
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/admin-pages/r:' + Math.round(Math.random()*1000),
		data: 'todo=' + page + txtdata,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,admin_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
	
}


/**********************************************************************/

function restoreitem(codep) {
	if (confirm(txtrestore)) {		
		$.ajax({
			type: 'POST',
			url: siteurl + 'ajax/admin-restoreitem/r:' + Math.round(Math.random()*1000),
			data: 'cph=' + codep,
			success: function(resp){
				switch(resp.charAt(0)){
					case '0':
						alert(resp.substring(3));
						break;
					case '1':
						$('#id_' + codep).fadeOut(500, function() { $('#id_' + codep).remove(); });
						break;
				}
			},
			error: function(){
				alert(resp.substring(3));
			} //end error
		}); // end ajax
	}
}


/**********************************************************************/

function deleteitem(codep) {
	if (confirm(txtdelete)) {	
		$.ajax({
			type: 'POST',
			url: siteurl + 'ajax/admin-deleteitem/r:' + Math.round(Math.random()*1000),
			data: 'cph=' + codep,
			success: function(resp){
				switch(resp.charAt(0)){
					case '0':
						alert(resp.substring(3));
						break;
					case '1':
						$('#id_' + codep).fadeOut(500, function() { $('#id_' + codep).remove(); });
						break;
				}
			},
			error: function(){
				alert(resp.substring(3));
			} //end error
		}); // end ajax
	}
	
}
	
/**********************************************************************/	