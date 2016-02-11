function checkNewMsgChat()
{
	uid = $('#uid').val();
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/check-newmsgchat/r:" + Math.round(Math.random()*1000),
		data: 'uid=' + uid,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					break;
				case '1':
					$('.chatbody').append(resp.substring(3));

					$(".chatbody").scrollTop($(".chatbody")[0].scrollHeight);
					
					if(!document.hasFocus()) {						
						// If the current document title doesn\'t have an alert, add one
						if(document.title.indexOf('[!]') == -1) {
							document.title = "[!] " + document.title;
						}
					}
					
					break;
			};
				
			setTimeout(checkNewMsgChat, intervalrefreshchat);
				
		},
		error: function(){
			//alert(msg_norequest);
		} //end error
	}); // end ajax	
}

/**********************************************************************/

function deletemsg(idmsg)
{
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/delete-msgchat/r:" + Math.round(Math.random()*1000),
		data: 'idmsg=' + idmsg,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					alert(resp.substring(3));
					break;
				case '1':
					$('#msg_' + idmsg).fadeOut(500, function() { $('#msg_' + idmsg).remove(); });
					break;
			};				
		},
		error: function(){
			alert(msg_norequest);
		} //end error
	}); // end ajax	
}

/**********************************************************************/

$('#inputchat').bind('keydown', function(e) {
	if(e.keyCode==13) {
		// Store the message into var
		var txtmsg = $('#inputchat').val();
		var uid = $('#uid').val();
		if (txtmsg) {
			// Remove chat errors if any
			//$('.chat-error').remove();

			// Reset the chat input area			
			document.getElementById("inputchat").style.height = "25px";
			$('#inputchat').val('');
					
			$.ajax({
				type: 'POST',
				url: siteurl + "ajax/send-msgchat/r:" + Math.round(Math.random()*1000),
				data: 'msg=' + encodeURIComponent(txtmsg) + '&uid=' + uid,
				cache: false,
				success: function(resp){
					switch(resp.charAt(0)){
						case '0':
							break;
						case '1':
							// Check if in the mean time any message was sent
							checkNewMsgChat();
							
							// Append the new chat to the div chat container
							$('.chatbody').append(resp.substring(3));

							// Scroll at the bottom of the div (focus new content)
							$(".chatbody").scrollTop($(".chatbody")[0].scrollHeight);
							break;
					};
						
				},
				error: function(){
					//alert(msg_norequest);
				} //end error
			}); // end ajax	

		}
	}
});

/**********************************************************************/