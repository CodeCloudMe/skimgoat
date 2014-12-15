$(document).ready(function () {
	//CUSTOM FORM ELEMENTS
	$('select, input[type=radio],input[type=checkbox],input[type=file]').uniform();
	
	//MOBILE MENU
	$('#menu').slicknav();
	
	//SCROLL TO TOP BUTTON
	$('.scroll-to-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});
	
	//MY PROFILE TABS 
	$('.tab-content').hide().first().show();
	$('.tabs li:first').addClass("active");

	$('.tabs a').on('click', function (e) {
		e.preventDefault();
		$(this).closest('li').addClass("active").siblings().removeClass("active");
		$($(this).attr('href')).show().siblings('.tab-content').hide();
	});

	var hash = $.trim( window.location.hash );
	if (hash) $('.tab-nav a[href$="'+hash+'"]').trigger('click');
	
	//ALERTS
	$('.close').on('click', function (e) {
		e.preventDefault();
		$(this).closest('.alert').hide(400);
	});
	
	//CONTACT FORM 
	$('#contactform').submit(function(){
	
		var action = $(this).attr('action');
		
		$("#message").show(400,function() {
		$('#message').hide();
		
 		$('#submit')
			.after('<img src="images/contact-ajax-loader.gif" class="loader" />')
			.attr('disabled','disabled');
		
		$.post(action, { 
			name: $('#name').val(),
			email: $('#email').val(),
			phone: $('#phone').val(),
			comments: $('#comments').val()
		},
			function(data){
				document.getElementById('message').innerHTML = data;
				$('#message').slideDown('slow');
				$('#contactform img.loader').fadeOut('slow',function(){$(this).remove()});
				$('#submit').removeAttr('disabled'); 
				//if(data.match('success') != null) $('#contactform').slideUp(3000);
				
			}
		);
		
		});
		
		return false; 
	
	});
});

//PRELOADER
$(window).load(function(){
	$('.preloader').fadeOut();
});

