(function($){
  $.fn.uploadPhoto = function(vartxx, rtempx, prefj, msg1, msg2) {
    var thumb = $('img#prwimg');
    new AjaxUpload(this, {

      action: siteurl + "upload/photo/tp:" + vartxx + "/prefj:" + prefj + "/fold:" + rtempx.replace(/\//gi,'-') + "/r:" + Math.round(Math.random()*1000),
      name: 'uploaded_photo',
      onSubmit: function(file, ext) {
        if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))) {
          alert(msg1);
          return false;
        } else {
			$('#linkUpPhoto').hide();
			$('#areapreload').show();
			fileOriginal = file;
        }
      },
      onComplete: function(file, response) {
		txtresult=response.substring(3);
		switch(response.charAt(0)){
		case '0':
			$('#linkUpPhoto').css('display','block');
			$('#areapreload').css('display','none');			
			alert(txtresult);
			break;
		case '1':
			var randon = Math.random();
			thumb.attr('src',siteurl + rtempx + txtresult + '?randon='+randon);
			$('#loadedimage').val(txtresult);
			if ($('#title').val()=='') $('#title').val(fileOriginal);	
			$('#didchanges').val('1');
			$('#linkUpPhoto').show();
			$('#linkUpPhoto').html(msg2);
			$('#areapreload').hide();
			$('#prwimg').show('slow');
			break;					
		}		
      }
    });
  };
})(jQuery); 