/***************************************************************************************************************
||||||||||||||||||||||||||||         CUSTOM SCRIPT FOR Archit            ||||||||||||||||||||||||||||||||||||
****************************************************************************************************************
||||||||||||||||||||||||||||              TABLE OF CONTENT                  ||||||||||||||||||||||||||||||||||||
****************************************************************************************************************
****************************************************************************************************************
1. RevolutionSliderActiver
2. GalleryFilter
3. FancyboxInit
4. Accordion
5. TestimonialCarosule
6. ProcessCarosule
7. Brand Carousel
8. Contact Form Validation
9. CounterNumberChanger
10. Features Carosell
11. progressBarConfig

****************************************************************************************************************
||||||||||||||||||||||||||||            End TABLE OF CONTENT                ||||||||||||||||||||||||||||||||||||
****************************************************************************************************************/
"use strict";
// 1 revolutionSliderActiver
function revolutionSliderActiver () {
	if ($('.rev_slider_wrapper #slider1').length) {
		$("#slider1").revolution({
			sliderType:"standard",
			sliderLayout:"auto",
			delay:5000,
            hideTimerBar:"off",
            onHoverStop:"off",
			navigation: {
				arrows:{enable:true} 
			}, 
			gridwidth:1170,
			gridheight:870 
		});
	};
}
 // 2. GalleryFilter
function GalleryFilter () {

	if ($('.portfolio_image_gallery').length) {
		$('.portfolio_image_gallery').each(function () {
			var Self = $(this);
			var filterSelector = Self.data('filter-class');
			var showItemOnLoad = Self.data('show-on-load');
			
			if (showItemOnLoad) {
				Self.mixItUp({
					load: {
						filter: '.'+showItemOnLoad
					},
					selectors: {
						filter: '.'+filterSelector
					}
				})	
			};
			Self.mixItUp({
				selectors: {
					filter: '.'+filterSelector
				}
			});
		});
	};
}
// 3. fancyboxInit
function fancyboxInit () {
	var galleryFcb = $('.fancybox');
	if(galleryFcb.length){
		galleryFcb.fancybox({
			openEffect  : 'elastic',
			closeEffect : 'elastic',
			helpers : {
				media : {}
			}
		});
	}
}

// 4. CounterNumberChanger
function CounterNumberChanger () {
	var sFcounter = $('.sF-counter');
	if(sFcounter.length) {
		sFcounter.appear(function () {
			sFcounter.countTo();
			
		});
	};

}

//5. Testimonials Slider
	if($('#testimonials-one').length){
		
		var slider = new MasterSlider();
		slider.control('bullets');  
    	slider.control('bullets',{autohide:false});
		slider.setup('testimonials-one' , {
			autoplay:true,
			loop:true,
			width:120,
			height:120,
			speed:20,
			view:'wave',
			preload:0,
			space:100,
			autoHeight:true,
			wheel:true,
			filters: {
            grayscale: 1
        },
			viewOptions:{centerSpace:1.6}
		});
		slider.control('slideinfo',{insertTo:'#staff-info'});
		
	}
//6. Brand Carousel
function brandCarousel () {
    if ($('.owl-demo-brand').length) {
        $('.owl-demo-brand').owlCarousel({
            dots: false,
            loop:true,
            margin:0,
            nav: true,
				navText: [
                    '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                    '<i class="fa fa-angle-right" aria-hidden="true"></i>'
                ],
            autoplay: 5000,
            smartSpeed: 1000,
            responsive:{
                0:{
                    items:1
                },
				400:{
                    items:2
                },
                600:{
                    items:3
                },
                800:{
                    items:4
                },
                1024:{
                    items:5
                },
                1100:{
                    items:5
                }
            }
        });    		
    }
}
(function($) {
	
	//Hide Loading Box (Preloader)
	function handlePreloader() {
		if($('.preloader').length){
			$('.preloader').delay(200).fadeOut(500);
		}
	}
	
	
// Dom Ready Function
jQuery(document).on('ready', function () {
	(function ($) {
	// add your functions

	revolutionSliderActiver ();
    GalleryFilter ();
    fancyboxInit ();
    CounterNumberChanger ();
    brandCarousel ();
	 handlePreloader();
	})(jQuery);
});









	
	//Update Scroll to Top
	function headerStyle() {
		if($('.main-header').length){
			var windowpos = $(window).scrollTop();
			if (windowpos >= 200) {
				$('.main-header').addClass('fixed-header');
				$('.scroll-to-top').fadeIn(300);
			} else {
				$('.main-header').removeClass('fixed-header');
				$('.scroll-to-top').fadeOut(300);
			}
		}
	}
	
	headerStyle();
	
	
	//Submenu Dropdown Toggle
	if($('.main-header li.dropdown ul').length){
		$('.main-header li.dropdown').append('<div class="dropdown-btn"></div>');
		
		//Dropdown Button
		$('.main-header li.dropdown .dropdown-btn').on('click', function() {
			$(this).prev('ul').slideToggle(500);
		});
		
		//Disable dropdown parent link
		$('.navigation li.dropdown > a').on('click', function(e) {
			e.preventDefault();
		});
	}
	
	
	//Search Box Toggle
	if($('.main-header .seach-toggle').length){
		//Dropdown Button
		$('.main-header .seach-toggle').on('click', function() {
			$(this).next('.search-box').toggleClass('now-visible');
		});
	}
	
	
	//Hidden Bar Menu Config
	function hiddenBarMenuConfig() {
		var menuWrap = $('.hidden-bar .side-menu');
		// appending expander button
		menuWrap.find('.dropdown').children('a').append(function () {
			return '<button type="button" class="btn expander"><i class="icon fa fa-bars"></i></button>';
		});
		// hidding submenu 
		menuWrap.find('.dropdown').children('ul').hide();
		// toggling child ul
		menuWrap.find('.btn.expander').each(function () {
			$(this).on('click', function () {
				$(this).parent() // return parent of .btn.expander (a) 
					.parent() // return parent of a (li)
						.children('ul').slideToggle();
	
				// adding class to expander container
				$(this).parent().toggleClass('current');
				// toggling arrow of expander
				$(this).find('i').toggleClass('fa-minus fa-bars');
	
				return false;
	
			});
		});
	}
	
	hiddenBarMenuConfig();
	
	
	
	//Custom Scroll for Hidden Sidebar
	if ($('.hidden-bar-wrapper').length) {
		$('.hidden-bar-wrapper').mCustomScrollbar();
	}
	
	
	//Hidden Bar Toggler
	if ($('.hidden-bar-closer').length) {
		$('.hidden-bar-closer').on('click', function () {
			$('.hidden-bar').removeClass('visible-sidebar');
		});
	}
	if ($('.hidden-bar-opener').length) {
		$('.hidden-bar-opener').on('click', function () {
			$('.hidden-bar').addClass('visible-sidebar');
		});
	}

	//Contact Form Validation
	if($('#contact-form').length){
		$('#contact-form').validate({
			rules: {
				username: {
					required: true
				},
				lastname: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				phone: {
					required: true
				},
				message: {
					required: true
				}
			}
		});
	}
	//Contact Form Validation
	if($('#contact-form-2').length){
		$('#contact-form-2').validate({
			rules: {
				username: {
					required: true
				},
				lastname: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				phone: {
					required: true
				},
				message: {
					required: true
				}
			}
		});
	}
	
	// Scroll to a Specific Div
	if($('.scroll-to-target').length){
		$(".scroll-to-target").on('click', function() {
			var target = $(this).attr('data-target');
		   // animate
		   $('html, body').animate({
			   scrollTop: $(target).offset().top
			 }, 1000);
	
		});
	}
	
	
	// Elements Animation
	if($('.wow').length){
		var wow = new WOW(
		  {
			boxClass:     'wow',      // animated element css class (default is wow)
			animateClass: 'animated', // animation css class (default is animated)
			offset:       0,          // distance to the element when triggering the animation (default is 0)
			mobile:       true,       // trigger animations on mobile devices (default is true)
			live:         true       // act on asynchronously loaded content (default is true)
		  }
		);
		wow.init();
	}


/* ==========================================================================
   When document is Scrollig, do
   ========================================================================== */
	$(window).on('scroll', function() {
		headerStyle();
       
	});
	

})(window.jQuery);

