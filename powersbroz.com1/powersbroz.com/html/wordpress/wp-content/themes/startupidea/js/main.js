/*global $:false */

jQuery(document).ready(function($){'use strict';


	/* ************************************* */
	/* ****** Form Editable ********* */
	/* ************************************* */
	$('.edit-form').on( "click",function(e) {
		$('input,textarea,button').removeAttr("disabled");
		e.preventDefault();
	});

	/* ************************************* */
	/* ****** General Editing Form ********* */
	/* ************************************* */
	function general_form(e){
		var postdata = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postdata,
			success:function(data, textStatus, jqXHR) {
				$('#dashboard-msg').modal();
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#dashboard-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
			}
		});
		e.preventDefault();	//STOP default action
	}
	$("#general-form").submit( general_form ); //SUBMIT FORM



/* ************************************* */
/* ****** Contact Editing Form ********* */
/* ************************************* */
	function contact_form(e){
		var postdata = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postdata,
			success:function(data, textStatus, jqXHR) {
				$('#dashboard-msg').modal();
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#dashboard-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
			}
		});
		e.preventDefault();	//STOP default action
	}
	$("#contact-form").submit( contact_form ); //SUBMIT FORM



/* ************************************* */
/* ****** Password Editing Form ******** */
/* ************************************* */
	function password_form(e){
		var postdata = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postdata,
			success:function(data, textStatus, jqXHR) {
				$('#dashboard-msg').modal();
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#dashboard-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
			}
		});
		e.preventDefault();	//STOP default action
	}
	$("#password-form").submit( password_form ); //SUBMIT FORM



/* ************************************* */
/* ***********	Sticky Nav ************* */
/* ************************************* */
	$(window).on('scroll', function(){'use strict';
		if ( $(window).scrollTop() > 130 ) {
			$('#masthead').addClass('sticky');
		} else {
			$('#masthead').removeClass('sticky');
		}
	});



/* ************************************* */
/* ***********	Menu Fix *************** */
/* ************************************* */
	// ------------- Menu Start ----------------------
	$('#showmenu').on( "click",function() {
			$('.main-nav').slideToggle("fast","linear");
		});
	//add and remove class
	var $window = $(window),
	     $ul = $('ul.main-nav');

	if ($window.width() < 768) {
	   $ul.removeClass('slideRight'); 
	};



/* ************************************* */
/* ********** Carousel Setup ********** */
/* ************************************* */
	$(window).on('resize', function () {
	    if ($window.width() < 768) {
	       $ul.removeClass('slideRight');
	   	}else{
		    $ul.addClass('slideRight')
		}
	 });
	//setup owl-carousel for partners
	$('.popular-ideas').owlCarousel({
		items: 3,
		// itemsCustom: [[0,1], [768,3], [992,3]],
		dots: false,
		nav: false,
		responsive: {
			0: {
				items: 1,
				margin: 30
			},
			992: {
				items: 3,
				margin: 30
			}
		}
	});
	// scroll animation initialize
	new WOW().init();


	//Window-size div
	$(window).resize(function() {
		$('#comming-soon').height($(window).height());
	});

	$(window).trigger('resize');


   	$(window).resize(function() {
		$('#error-page').height($(window).height());
	}); 
    $(window).trigger('resize');


	$(".youtube a[data-rel^='prettyPhoto']").prettyPhoto();
	$(".vimeo a[data-rel^='prettyPhoto']").prettyPhoto();



/*--------------------------------------------------------------
 * 					Personal Profile Form AJAX
 *-------------------------------------------------------------*/
	function profile_form(e){
			var postdata = $(this).serializeArray();
			var formURL = $(this).attr("action");
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postdata,
				success:function(data, textStatus, jqXHR) {
					$('#profile-msg').modal();
				},
				error: function(jqXHR, textStatus, errorThrown){
					$("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
				}
			});
			e.preventDefault();	//STOP default action
			//e.unbind(); //Remove a previously-attached event handler
		}
	$("#profile-form").submit( profile_form ); //SUBMIT FORM
	



/*--------------------------------------------------------------
 * 					Paypal User Email Form AJAX
 *-------------------------------------------------------------*/
	function paypal_user_form(e){
			var postdata = $(this).serializeArray();
			var formURL = $(this).attr("action");
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postdata,
				success:function(data, textStatus, jqXHR) {
					$('#profile-msg').modal();
				},
				error: function(jqXHR, textStatus, errorThrown){
					$("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
				}
			});
			e.preventDefault();	//STOP default action
			//e.unbind(); //Remove a previously-attached event handler
		}
	$("#paypal-user-form").submit( paypal_user_form ); //SUBMIT FORM




/*--------------------------------------------------------------
 * 					Ratting Submit Form AJAX
 *-------------------------------------------------------------*/
	function ratting_submit_form(e){
			var postdata = $(this).serializeArray();
			var formURL = $(this).attr("action");
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postdata,
				success:function(data, textStatus, jqXHR) {
					$('#ratting-msg').modal();
				},
				error: function(jqXHR, textStatus, errorThrown){
					$("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
				}
			});
			e.preventDefault();	//STOP default action
			// e.unbind(); //Remove a previously-attached event handler
		}
	$("#ratting-submit-form").submit(ratting_submit_form); //SUBMIT FORM

	$('#ratting-close').on('click',function() {
	    window.location.href = $('#redirect_url_ratting').val();
	});



/*--------------------------------------------------------------
 * 					Project Submit Form AJAX
 *-------------------------------------------------------------*/
 	$('#project-submit').mousedown( function() {
    	tinyMCE.triggerSave();
    });

	function project_submit_form(e){
		var postdata = $(this).serializeArray();
		var formURL = $(this).attr("action");
		
			$.ajax(
				{
					url : formURL,
					type: "POST",
					data : postdata,
					success:function(data, textStatus, jqXHR) {
						//$('#project-submit-form')[0].reset();
						$('#welcome-msg').modal();
						$('#dashboard-msg').modal();
					},
					error: function(jqXHR, textStatus, errorThrown){
						$("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
					}
				});
		e.preventDefault();	//STOP default action
		//e.unbind(); //Remove a previously-attached event handler
	}
	$("#project-submit-form").submit( project_submit_form ); //SUBMIT FORM

	

	$('#form-submit-close').on('click',function() {
	    window.location.href = $('#redirect_url_add').val();
	});

	$('#edit-submit-close').on('click',function() {
	    window.location.href = $('#redirect_url_edit').val();
	});


/*--------------------------------------------------------------
 * 					Update Submit Form AJAX
 *-------------------------------------------------------------*/
	function update_submit_form(e){
		var postdata = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postdata,
			success:function(data, textStatus, jqXHR) {
				//$('#project-submit-form')[0].reset();
				$('#dashboard-msg').modal();
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
			}
		});
		e.preventDefault();	//STOP default action
		e.unbind(); //Remove a previously-attached event handler
	}
	$("#update-submit-form").submit(update_submit_form); //SUBMIT FORM



	$('#update-close').on('click',function() {
	    window.location.href = $('#redirect_url').val();
	});
	



/*--------------------------------------------------------------
 * 					Project Money Withdraw
 *-------------------------------------------------------------*/
	function withdraw_submit_form(e){
		var postdata = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postdata,
			success:function(data, textStatus, jqXHR) {
				//$('#project-submit-form')[0].reset();
				$('#withdraw-msg').modal();
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
			}
		});
		e.preventDefault();	//STOP default action
		e.unbind(); //Remove a previously-attached event handler
	}
	$("#withdraw-submit-form").submit(withdraw_submit_form); //SUBMIT FORM


	$('#withdraw-close').on('click',function() {
	    window.location.href = $('#redirect_url_withdraw').val();
	});




/*--------------------------------------------------------------
 * 					Project Submit Form
 *-------------------------------------------------------------*/

    // Color Change When Text Input
    function color_changer(ids,classs){  
        if($('#'+ids).val() == ""){
            $('.'+classs).css('color','#FF0000');
        }else{
            $('.'+classs).css('color','#16DA00');
        }
    }
    color_changer('project-title'           ,'title-color');
    color_changer('project-tag'             ,'tag-color');
    color_changer('location'                ,'location-color');
    color_changer('project-about'           ,'about-color');

    $('#project-title').on('keyup',function(event){ 
        $('#auto-title').html( $('#project-title').val()); 
        color_changer('project-title','title-color');
    });

    $('#location').on('keyup',function(event){  
        $('#auto-location').html( $('#location').val()); 
        color_changer('location','location-color');
    });

    $('#project-about').on('keyup',function(event){ 
        color_changer('project-about','about-color');
    });

    $('#project-tag').on('keyup',function(event){  
        $('#auto-tag').html( $('#project-tag').val()); 
        color_changer('project-tag','tag-color');
    });

    $('#investment-amount').on('keyup',function(event){  
        $('#auto-investment').html( $('#investment-amount').val()); 
    });



	/* --------------------------------------
	*		Shortcode hover color effect 
	*  -------------------------------------- */
	var clr = '';
	var clr_bg = '';
	var clr_border = '';
	$(".thm-color").on({
	    mouseenter: function () {
	     	clr = $(this).css('color');
			clr_bg = $(this).css('backgroundColor');
			clr_border = $(this).css('border-color');
			$(this).css("color", $(this).data("hover-color"));
			$(this).css("background-color", $(this).data("hover-bg-color"));
			$(this).css("border-color", $(this).data("hover-border-color"));
	    },
	    mouseleave: function () {
	        $(this).css("color", clr );
			$(this).css("background-color", clr_bg );
			$(this).css("border-color", clr_border );
	    }
	});



	$("#amount").on('keypress',function (e) {
	     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	        return false;
	    }
	});


	var $spc = 2;
	var html = $('#clone-form').html();

	$('#add-more').on('click',function() {
		var final_html = html;
		final_html = final_html.replace( "min1", "min"+$spc );
		final_html = final_html.replace( "max1", "max"+$spc );
		final_html = final_html.replace( "reward1", "reward"+$spc );
		$('#clone-form').append( final_html );
		$spc++;

	});
});


// payment form submit
jQuery(document).ready(function($){'use strict';

	$('#submitbtn').on('click', function(event) {
		event.preventDefault();

		var $paymentForm 	= $('#fund_paymet_form'),
		gatewayType 	= 'paypal';

		var validator = $( "#fund_paymet_form" ).validate({
			rules: {
				first_name: "required",
				last_name: "required",
				email: {
					required: true,
					email: true
				},
				address1: "required",
				address2: "required",
				city: "required",
				state: "required",
				zip:  "required",
				country: "required",
				amount:  {
					required: true,
					number: true,
					min:1
				},
			},
		}),
		validate_status = validator.form();

		if ( !validate_status ) {
			return false;
		}

		$('.donate-project-page > .container').block({ 
                message: '<h1>Processing</h1>', 
                css: {
                	padding:        '30px 0', 
                	margin:         0, 
                	width:          '30%', 
                	top:            '40%', 
                	left:           '35%', 
                	textAlign:      'center', 
                	color:          '#ccc!important', 
                	border:         '2px solid #f5f5f5', 
                	backgroundColor:'#ffffff', 
                	cursor:         'wait' 
                },
                overlayCSS:  { 
                	backgroundColor: '#000', 
                	opacity:         0.3, 
                	cursor:          'wait' 
                }, 
        });

		gatewayType = $('input[name=gateway_type]:checked').val();

		if ( gatewayType == 'paypal' )
		{
			submitPaymentForm();
		}
		else if(gatewayType == 'card')
		{
			stripePaymentForm();
		}
		else
		{
			alert('Please select a payment method first.');
		}

	});
});

var submitPaymentForm = function()
{
	var formData = jQuery('#fund_paymet_form').serialize();

	var data = {
		'action':'fund_paymet_form_submit',
		'wpnonce':paymentAjax.paymentNonce,
		'data' : formData
	};

	jQuery.ajax({
		url: paymentAjax.ajaxurl,
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(data) {
		console.log(data);
		if (data.status == true) {
			window.location = data.redirect;
		}
	})
	.fail(function() {
		console.log("error");
	});
};