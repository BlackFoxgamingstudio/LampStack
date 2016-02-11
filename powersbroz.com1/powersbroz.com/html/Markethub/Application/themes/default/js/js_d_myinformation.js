
function updatePersonalInfo(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	firstname=$.trim($('#firstname').val());
	if (firstname == '') {
		$('#firstname').val(firstname);
		openandclose(diverror,mi_pi_msg1,1700);
		$('#firstname').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}

	lastname=$.trim($('#lastname').val());
	if (lastname == '') {
		$('#lastname').val(lastname);
		openandclose(diverror,mi_pi_msg2,1700);
		$('#lastname').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}


	gender = $('#gender').val();
	if (gender == 0) {
		openandclose(diverror,mi_pi_msg3,1700);
		$('#gender').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000); 
		return;
	}
	
	day = $('#day').val();
	month = $('#month').val();	
	year = $('#year').val();
	if (day == 0 || month == 0 || year == 0) {
		openandclose(diverror,mi_pi_msg4,1700);
		$('#year').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000); 
		return;
	}

	
	xday = day;
	xmonth = month;
	if (day<10) xday='0' + day;
	if (month<10) xmonth='0' + month;
	caddate = xday + '/' + xmonth + '/' + year;
	if (!validatedate(caddate)) {
		openandclose(diverror,mi_pi_msg5,1700);
		$('#year').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000); 
		return;
	}
	
	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myinformation/r:' + Math.round(Math.random()*1000),
		data: 'todo=1&fn=' + encodeURIComponent(firstname) + '&ln=' + encodeURIComponent(lastname) + '&g=' + gender + '&b=' + year + '-' + xmonth + '-'+ xday,
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
			openandclose(diverror,mi_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/**************************************************************************/

function updateAboutMe(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	aboutme=$.trim($('#aboutme').val());
	if (aboutme == '') {
		$('#aboutme').val(aboutme);
		openandclose(diverror,mi_am_msg1,1700);
		$('#aboutme').focus();
		setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 2000);
		return;
	}

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myinformation/r:' + Math.round(Math.random()*1000),
		data: 'todo=2&am=' + encodeURIComponent(aboutme),
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
			openandclose(diverror,mi_norequest,2000);
			setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/***********************************************************************/

function updateInfoAccess(diverror,divok,bsubmit)
{
	$(bsubmit).attr('disabled','true');

	currentpass = $('#currentpass').val();
	if (currentpass == '') {
		openandclose(diverror,mi_ia_msg1,1700);
		$('#currentpass').focus();
		setTimeout(function() { $(bsubmit).removeAttr('disabled'); }, 2000);
		return;
	}

	newpass = $('#newpass').val();
	if (newpass == '') {
		openandclose(diverror,mi_ia_msg2,1700);
		$('#newpass').focus();
		setTimeout(function() { $(bsubmit).removeAttr('disabled'); }, 2000);
		return;
	}
	
	newpass2 = $('#newpass2').val();
	if (newpass2 == '') {
		openandclose(diverror,mi_ia_msg3,1700);
		$('#newpass2').focus();
		setTimeout(function() { $(bsubmit).removeAttr('disabled'); }, 2000);
		return;
	}
	
	if (newpass != newpass2) {
		openandclose(diverror,mi_ia_msg4,1700);
		$('#newpass2').focus();
		setTimeout(function() { $(bsubmit).removeAttr('disabled'); }, 2000);
		return;
	}

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myinformation/r:' + Math.round(Math.random()*1000),
		data: 'todo=4&cp=' + CryptoJS.MD5(currentpass) + '&np=' + CryptoJS.MD5(newpass),
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					$('#currentpass').val('');
					$('#newpass').val('');
					$('#newpass2').val('');
					setTimeout(function() {$(bsubmit).removeAttr('disabled');}, 3000); 
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

function updateMyAvatar(diverror, divok, bsumbit)
{
	$(bsumbit).attr('disabled','true');

	loadedimage=$.trim($('#loadedimage').val());
	if (loadedimage == '') {
		openandclose(diverror,mi_mav_msg3,1700);
		setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 2000); 
		return;
	}
	
	didchanges=$('#didchanges').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myinformation/r:' + Math.round(Math.random()*1000),
		data: 'todo=3&dch=' + didchanges + '&limg=' + loadedimage,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					$('#loadedimage').val(loadedimage);
					$('#didchanges').val(0);
					oneimage = new Image();
					oneimage.src = siteurl + folderphotos + loadedimage;
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,mi_norequest,2000);
			setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	

}

/***********************************************************************/



function updateLocation(diverror, divok, bsumbit)
{
	$(bsumbit).attr('disabled','true');

	country = $('#country').val();
	if (country == '0') {
		openandclose(diverror,mi_lo_msg1,1700);
		$('#country').focus();
		setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 2000);
		return;
	}

	region = $('#region').val();
	if (region == '0') {
		openandclose(diverror,mi_lo_msg2,1700);
		$('#region').focus();
		setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 2000);
		return;
	}
	
	city = $.trim($('#city').val());
	if (city == '') {
		$('#city').val(city);
		openandclose(diverror,mi_lo_msg3,1700);
		$('#city').focus();
		setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 2000);
		return;
	}

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myinformation/r:' + Math.round(Math.random()*1000),
		data: 'todo=5&cc=' + country + '&r=' + region + '&c=' + encodeURIComponent(city),
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,mi_norequest,2000);
			setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/***********************************************************************/

function updatePrivacy(diverror, divok, bsumbit)
{
	$(bsumbit).attr('disabled','true');

	privacy = $('#privacy').val();

	$.ajax({
		type: 'POST',
		url: siteurl + 'ajax/dashboard-myinformation/r:' + Math.round(Math.random()*1000),
		data: 'todo=6&p=' + privacy,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					openandclose(diverror,resp.substring(3),2000);
					setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
					break;
				case '1':
					openandclose(divok,resp.substring(3),2000);
					setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
					break;
			}
		},
		error: function(){
			openandclose(diverror,mi_norequest,2000);
			setTimeout(function() {$(bsumbit).removeAttr('disabled');}, 3000); 
		} //end error
	}); // end ajax	
}

/***********************************************************************/