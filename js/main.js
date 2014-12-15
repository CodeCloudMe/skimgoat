$(document).ready(function(){
	"use strict";
	/////////// Home Page SVG resize//////////////

	$(window).load(function(){
		$(".work figure").each(function(){
			var svg, height;
			svg = $(this).find("svg")[0];
			height = parseInt($(this).css("height"));
			svg.setAttribute('viewBox', '0 0 175 ' + height);
		});
	});

	jQuery(window).on('resizestop', function () {
	   $(".work figure").each(function(){
	   		var svg, height;
			svg = $(this).find("svg")[0];
			height = parseInt($(this).css("height"));
			svg.setAttribute('viewBox', '0 0 175 ' + height);
		});
	});

	/////////// Loader //////////////

	$(window).load(function(){
	  $('#loader').delay(1000).fadeOut(500);
	});

	///////////Mobile Menu Button Toggle//////////////

	$( "#responsive-menu-button" ).click(function(e) {
	  	e.preventDefault();  
        $('.menu').slideToggle(200);  
	});

	jQuery(window).on('resizestop', function () {
	    width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
		if(width>=768){
			if($('.menu').css("display") == 'none'){
	    		$('.menu').css("display","block");
	    	}
	    }else{
	    	if($('.menu').css("display") == 'block'){
	    		$('.menu').css("display","none");
	    	}
	    }
	});


	/////////// Home Page Filters //////////////
	var width;
	width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	if(width>=992){
	  	$('head').append('<style>.filters:before{width:0% !important;}</style>');
	}

	var startWidth, endWidth;

	jQuery(window).on('resizestop', function () {
	    width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
		if(width>=992){
	    	$('head:last-child',this).remove();
	    	$('head').append('<style>.filters:before{width:0% !important;}</style>');
	    }else{
    		$('head:last-child',this).remove();
    		$('head').append('<style>.filters:before{width:100% !important;}</style>');
	    }
	});

	/////////// Countdown //////////////

	if ($('#countdown').length) {
		var countdownDate = new Date(); 
		countdownDate = new Date(2014,6,1); 
		$('#countdown').countdown({until: countdownDate}); 
	}

	/////////// Show/Hide Filters //////////////

	$( "#menu-button" ).click(function(e) {
	  	e.preventDefault();  
	  	var opacity, height;
	  	opacity = parseInt($( ".filters ul" ).css("opacity"));
	  	if( opacity == 0){
	  		$('head:last-child',this).remove();
	  		$( ".filters ul li" ).css("display","inline-block");
	  		height = $( ".filters ul li" ).css("height");
	  		$( ".filters ul" ).css("max-height",height);
	  		
	  		
		  	$( ".filters ul" ).delay(100).queue(function() {
		  				$('head').append('<style>.filters:before{width:100% !important;}</style>');
				      	$( ".filters ul" ).css("opacity","1").dequeue();
			});
		  	
		}else{
			$('head:last-child',this).remove();
			$('head').append('<style>.filters:before{width:0% !important;}</style>');
			$( ".filters ul" ).css("opacity","0");
			$( ".filters ul" ).delay(100).queue(function() {
				
					$( ".filters ul li" ).delay(200).queue(function(){
						$( ".filters ul li" ).css("display","none").dequeue();
					});
				$( ".filters ul" ).css("max-height","0").dequeue();
			});
			$( ".filters" ).find(".active").removeClass("active");
			$( ".work" ).each(function() {	
		  		$(this).find("figure").css({
		  			'transform': 'scale(1,1)' ,
				    '-moz-transform': 'scale(1,1)',
				    '-webkit-transform': 'scale(1,1)',
				    'opacity':'1'
				});
		  	});
		}
	});

	///////////Gallery Filter Effect//////////////

	$( ".filters li" ).click(function() {
	  if(!$(this).hasClass("active")){
	  	$( ".filters" ).find(".active").removeClass("active");
	  	$(this).addClass("active");
	  	var filter = $(this).attr("data-filter");
	  	$( ".work" ).each(function() {	
	  		$(this).find("figure").css({
	  			'transform': 'scale(1,1)' ,
			    '-moz-transform': 'scale(1,1)',
			    '-webkit-transform': 'scale(1,1)',
			    'opacity':'1'
			});
	  		if(filter != 'all' && !$(this).hasClass(filter)){
	  			$(this).find("figure").css({
		  			'transform': 'scale(0.8,0.8)' ,
				    '-moz-transform': 'scale(0.8,0.8)',
				    '-webkit-transform': 'scale(0.8,0.8)',
				    'opacity':'0.5'
				});
	  		}
	  	});
	  }
	});

	/////////// Sliders Settings //////////////

	$(window).load(function(){
		if ($('.post .slider ul').length) {
			$('.post .slider ul').bxSlider({
				auto: true,
			  	infiniteLoop: true,
			  	touchEnabled: true,
			  	pause: 3000,
			  	nextSelector: ".slider .controls .next",
				prevSelector: ".slider .controls .prev",
				nextText: "",
			  	prevText: ""
			  	
			});
		}

		if ($('aside .slider ul').length) {
			$('aside .slider ul').bxSlider({
			  	infiniteLoop: true,
			  	controls: false	
			});
		}

		if ($('.home-slider ul').length) {
			$('.home-slider ul').bxSlider({
			  	infiniteLoop: true,
			  	touchEnabled: true,
			  	nextSelector: ".home-slider .controls .next",
				prevSelector: ".home-slider .controls .prev",
			  	auto: true,
			  	nextText: "",
			  	prevText: "",
			  	pause: 10000,
			  	pager: false
			});
		}

		jQuery(window).on('resizestop', function () {
			if ($('.post .slider ul').length) {
				$('.post .slider ul').reloadSlider();
			}
			if ($('aside .slider ul').length) {
				$('aside .slider ul').reloadSlider();
			}
			if ($('.home-slider ul').length) {
				$('.home-slider ul').reloadSlider();
			}
		});
	});

	/////////// Flickr Settings //////////////

	if ($('.flickr').length) {
		$('.flickr').flickrush({
			limit:8,
			id:'52617155@N08',
			random:true
		}); 
	}

	/////////// Responsive Video Frames //////////////

	Fluidvids.init({
	    selector: 'iframe',
	    players: ['www.youtube.com', 'player.vimeo.com']
	  });


	/////////// Responsive Video Frames //////////////

	$( 'nav.menu li:has(ul)' ).doubleTapToGo();

	/////////// Home Page SVG Animation //////////////

	if ($('#masonry').length) {
		new CBPGridGallery( document.getElementById( 'masonry' ) );
	}

	

	if ($('.work figure').length) {
		(function() {
			"use strict";
			function init() {
				var speed = 330,
					easing = mina.backout;

				[].slice.call ( document.querySelectorAll( ' .gallery .grid li > a.work ,  .related .grid li > a.work' ) ).forEach( function( el ) {
					var s = Snap( el.querySelector( 'svg' ) ), path = s.select( 'path' ),
						pathConfig = {
							from : path.attr( 'd' ),
							to : el.getAttribute( 'data-path-to' )
						};

					el.addEventListener( 'mouseenter', function() {
						path.animate( { 'path' : pathConfig.to }, speed, easing );
					} );

					el.addEventListener( 'mouseover', function() {
						path.animate( { 'path' : pathConfig.to }, speed, easing );
					} );

					el.addEventListener( 'mouseleave', function() {
						path.animate( { 'path' : pathConfig.from }, speed, easing );
					} );

					el.addEventListener( 'mouseout', function() {
						path.animate( { 'path' : pathConfig.from }, speed, easing );
					} );
				} );
			}

			init();

		})();
	}
});

//menu

( function( $ ) {
$( document ).ready(function() {
$('.cssmenu').prepend('<div id="menu-button">Menu</div>');
	$('.cssmenu #menu-button').on('click', function(){
		var menu = $(this).next('ul');
		if (menu.hasClass('open')) {
			menu.removeClass('open');
		}
		else {
			menu.addClass('open');
		}
	});
});
} )( jQuery );
