function jsFollow(uid)
{
	$('.follow').attr('disabled','true');
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/relations-actions/r:" + Math.round(Math.random()*1000),
		data: 'todo=1&uid=' + uid,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					alert(resp.substring(3));
					$('.follow').removeAttr('disabled');
					break;
				case '1':
					$('.follow').css('display','none');
					$('.following').css('display','block');
					ssgg=1;
					$('.follow').removeAttr('disabled');
					$('#numfollowers').html(parseInt($('#numfollowers').html()) + 1);
					if (showprofile==0) self.location = self.location;
					break;
				}
		},
		error: function(){
			alert(msg_norequest);
			$('.follow').removeAttr('disabled');
		} //end error
	}); // end ajax	
}

function jsUnfollow(uid)
{
	$('.unfollow').attr('disabled','true');
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/relations-actions/r:" + Math.round(Math.random()*1000),
		data: 'todo=2&uid=' + uid,
		success: function(resp){
			switch(resp.charAt(0)){
				case '0':
					alert(resp.substring(3));
					$('.unfollow').removeAttr('disabled');
					break;
				case '1':
					$('.js-action-unfollow').css('display','none');
					$('.follow').css('display','block');
					ssgg=0;
					$('.unfollow').removeAttr('disabled');
					$('#numfollowers').html(parseInt($('#numfollowers').html()) - 1);
					break;
				}
		},
		error: function(){
			alert(msg_norequest);
			$('.unfollow').removeAttr('disabled');
		} //end error
	}); // end ajax	
}