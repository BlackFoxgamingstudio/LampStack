(function($){
  $.fn.uploadImage = function(vartxx, rtempx, prefj, msg1, msg2) {
    var thumb = $('img#prwimg');
    new AjaxUpload(this, {

      action: siteurl + "upload/image/tp:" + vartxx + "/prefj:" + prefj + "/fold:" + rtempx.replace(/\//gi,'-') + "/r:" + Math.round(Math.random()*1000),
      name: 'uploaded_image',
      onSubmit: function(file, ext) {
        if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))) {
          alert(msg1);
          return false;
        } else {
			$('#linkUpImage').hide();
			$('#areapreload').show();
        }
      },
      onComplete: function(file, response) {
		txtresult=response.substring(3);
		switch(response.charAt(0)){
		case '0':
			$('#linkUpImage').css('display','block');
			$('#areapreload').css('display','none');			
			alert(txtresult);
			break;
		case '1':
			var randon = Math.random();
			thumb.attr('src',siteurl + rtempx + txtresult + '?randon='+randon);
			$('#loadedimage').val(txtresult);	
			$('#didchanges').val('1');
			$('#linkUpImage').show();
			$('#linkUpImage').html(msg2);
			$('#areapreload').hide();
			$('#prwimg').show('slow');
			break;					
		}		
      }
    });
  };
})(jQuery); 