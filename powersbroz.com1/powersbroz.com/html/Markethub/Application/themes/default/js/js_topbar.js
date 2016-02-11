var openNnotifications = 0;
var openNmessages = 0;

function hideNotifications() {
	// we disable notifications and hide links
	$('.area-notification').hide();
	$('#linkshownot').attr('onclick','showNotifications(); return false;');
	$('#linkshownot').attr('href','#');
	$('.content-info').html('');
	openNnotifications = 0;
	checkNewNotifications();
}

function showNotifications() {
	// Stop checking for new notifications while reading them
	clearTimeout(stopNotifications);

	$('#iconotifications').attr('src',siteurl + 'themes/default/imgs/loadingtop.gif');
	

	if (openNmessages == 0) {
		hideNotificationsMessages();
	}


	$('.box-notification').hide();
	$('#linkshownot').removeAttr('onclick');
	$('#linkshownot').removeAttr('href');
	
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/get-notifications/r:" + Math.round(Math.random()*1000),
		data: '',
		success: function(resp) {
			switch(resp.charAt(0)) {
				case '0':
					break;
				case '1':
					$('.content-info').html(resp.substring(3));
					$('.area-notification').show();
					$('#iconotifications').attr('src',siteurl + 'themes/default/imgs/iconotifications.png');
					break;
			};				
		},
		error: function(){
			//alert(msg_norequest);
		} //end error
	}); // end ajax	
}

function checkNewNotifications()
{
	valuecurrent = $('.notification-value').html();
	if (valuecurrent == '') valuecurrent = 0;
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/check-notifications/r:" + Math.round(Math.random()*1000),
		data: '',
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					// If there are no notifications
					numnot = parseInt(resp.substring(3));
					if (numnot <= 0) {
						$('.box-notification').hide();
						$('.notification-value').html(0);
					}
					break;
				case '1':
					// If there are notifications
					numnot = parseInt(resp.substring(3));
					if ( numnot > 0 ) {
						$('.notification-value').html(numnot);
						$('.box-notification').show();
						if (numnot>valuecurrent) {
							if(!document.hasFocus()) {						
								// If the current document title doesn\'t have an alert, add one
								if(document.title.indexOf('[!]') == -1) {
									document.title = "[!] " + document.title;
								}
							}
						}
					}
					break;
				};
				
			stopNotifications = setTimeout(checkNewNotifications, intervalcheckevents);
				
		},
		error: function(){
			//alert(msg_norequest);
		} //end error
	}); // end ajax	
}



/*************************************************************************/
/*************************************************************************/

function hideNotificationsMessages() {
	// we disable notifications of messages and hide links
	$('.area-notification-msg').hide();
	$('#linkshownotmsg').attr('onclick','showNotificationsMessages(); return false;');
	$('#linkshownotmsg').attr('href','#');
	$('.content-info-msg').html('');
	openNmessages = 0;
	checkNewMessages();
}

function showNotificationsMessages() {
	// Stop checking for new notifications of messages while reading them
	clearTimeout(stopNewMessages);
	$('#icomessages').attr('src',siteurl + 'themes/default/imgs/loadingtop.gif');

	if (openNnotifications == 0) {
		hideNotifications();
	}


	$('.box-notification-msg').hide();
	$('#linkshownotmsg').removeAttr('onclick');
	$('#linkshownotmsg').removeAttr('href');
	
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/get-notifications-messages/r:" + Math.round(Math.random()*1000),
		data: '',
		success: function(resp) {
			switch(resp.charAt(0)) {
				case '0':
					break;
				case '1':
					$('.content-info-msg').html(resp.substring(3));
					$('.area-notification-msg').show();
					$('#icomessages').attr('src',siteurl + 'themes/default/imgs/iconotimessages.png');
					break;
			};				
		},
		error: function(){
			//alert(msg_norequest);
		} //end error
	}); // end ajax	
}

function checkNewMessages()
{
	valuecurrent = $('.notification-value-msg').html();
	if (valuecurrent == '') valuecurrent = 0;
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/check-newmessages/r:" + Math.round(Math.random()*1000),
		data: '',
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					// If there are no notifications of messages
					numnot = parseInt(resp.substring(3));
					if (numnot <= 0) {
						$('.box-notification-msg').hide();
						$('.notification-value-msg').html(0);
					}
					break;
				case '1':
					// If there are notifications of messages
					numnot = parseInt(resp.substring(3));
					if ( numnot > 0 ) {
						$('.notification-value-msg').html(numnot);
						$('.box-notification-msg').show();
						if (numnot>valuecurrent) {
							if(!document.hasFocus()) {						
								// If the current document title doesn\'t have an alert, add one
								if(document.title.indexOf('[!]') == -1) {
									document.title = "[!] " + document.title;
								}
							}
						}
					}
					break;
				};
				
			stopNewMessages = setTimeout(checkNewMessages, intervalcheckmsg);
				
		},
		error: function(){
			//alert(msg_norequest);
		} //end error
	}); // end ajax	
}