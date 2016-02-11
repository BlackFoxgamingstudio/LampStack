
function loadCountries(cod, msgccountry, msgcregion, idccountry, idcregion ){
	$(idccountry).html('<option value="0">' + msgccountry + '</option>');
	$(idccountry).attr('disabled','true');
	$(idcregion).html('<option value="0">' + msgcregion + '</option>');
	$.ajax({
		type: 'POST',
		url:  siteurl + "ajax/dashboard-getcountries/r:" + Math.round(Math.random()*1000),
		data: 'cod=' + cod,
		success: function(h){
			switch(h.charAt(0)){
				case '0':
				
					break;
				case '1':
					$(idccountry).html(h.substring(3));
					$(idccountry).removeAttr('disabled');
					break;
			}
		},
		error: function(){

			
		} //end error

	}); // end ajax
	
}

function loadRegions(codcountry, idregion, msgcregion, idcregion) {	
	$(idcregion).html('<option value="0">' + msgcregion + '</option>');
	$(idcregion).attr('disabled','true');
	$.ajax({
		type: 'POST',
		url: siteurl + "ajax/dashboard-getregions/r:" + Math.round(Math.random()*1000),
		data: 'cod=' + codcountry + '&r=' + idregion,
		success: function(h){
			switch(h.charAt(0)){
				case '0':				

					break;
				case '1':
					$(idcregion).html(h.substring(3));
					$(idcregion).removeAttr('disabled');
					break;
			}
		},
		error: function(){

			
		} //end error

	}); // end ajax
	
}