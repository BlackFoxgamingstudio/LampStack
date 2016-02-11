
function actionLogin(btlogin, diverror)
{
	$(btlogin).attr('disabled','true');

	usernamel=$.trim($('#usernamel').val());
	if (usernamel == '') {
		openandclose(diverror,ltxterror1,1700);		
		$('#usernamel').focus();
		setTimeout(function() {$(btlogin).removeAttr('disabled');}, 2000); 
		return;
	}
	
	if (usernamel.indexOf('@') != -1 && !emailvalidate(usernamel)) {
		$('#usernamel').val(usernamel);
		openandclose(diverror,ltxterror2,1700);
		$('#usernamel').focus();
		setTimeout(function() {$(btlogin).removeAttr('disabled');}, 2000); 
		return;
	}
	
	passwordl=$('#passwordl').val();
	if (passwordl == '') {
		openandclose(diverror,ltxterror3,1700);		
		$('#passwordl').focus();
		setTimeout(function() {$(btlogin).removeAttr('disabled');}, 2000); 
		return;
	}
	
	if (passwordl.length < 6 || passwordl.length > 15) {
		openandclose(diverror,ltxterror4,1700);
		$(passwordl).focus();
		setTimeout(function() {$(btlogin).removeAttr('disabled');}, 2000); 
		return;
	}

	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/login/r:" + Math.round(Math.random()*1000),
		data: 'un=' + usernamel + '&pw=' + CryptoJS.MD5(passwordl),
		success: function(resp) {
			switch (resp.charAt(0)) {
				case '0':
					openandclose(diverror,resp.substring(3),1700)
					setTimeout(function() {$(btlogin).removeAttr('disabled');}, 2000);
					break;
				case '1':
					location.href = siteurl + "dashboard";
					break;
				case '2':
					location.href = resp.substring(3);
					break;
			}
		},
		error: function() {
			openandclose(diverror,txtconnerror,1700)
			setTimeout(function() {$(btlogin).removeAttr('disabled');}, 2000); 
		} //end error

	}); // end ajax	
}