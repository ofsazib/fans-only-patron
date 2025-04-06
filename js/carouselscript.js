	$('.carousel').carousel({
	  interval: 5000,
   	  pause: "false"
	})
	
$(".navbar-toggler").click(function(){

  $(".navbar-toggler").toggleClass("showtoggle");

});	


$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll >= 50) {
        $(".header_sec").addClass("fixed");
    } else {
        $(".header_sec").removeClass("fixed");
    }
});

$(document).ready(function() {
  var owl = $('.testimonial_innr .owl-carousel');
  owl.owlCarousel({
    margin: 0,
    nav: true,
    autoplay: true,
    loop: true,
    responsive: {
      0: {
        items: 1
      },
      400: {
        items: 1
      },
      600: {
        items: 1
      },
      992: {
        items: 1
      },
      1200: {
        items: 1
      }
    }
  })
});


$(document).ready(function() {
  var owl = $('.client_innr .owl-carousel');
  owl.owlCarousel({
    margin: 0,
    nav: true,
    autoplay:true,
    loop: true,
    responsive: {
      0: {
        items: 1
      },
      400: {
        items: 1
      },
      600: {
        items: 1
      },
      992: {
        items: 1
      },
      1200: {
        items: 1
      }
    }
  })
});

$(window).scroll(function() {    
    var scroll = $(window).scrollTop();
    if (scroll >= 10) {
        $(".header_sec").addClass("fixed");
    } else {
        $(".header_sec").removeClass("fixed");
    }
});

$(function() {
	  $('a.scroll[href*=\\#]:not([href=\\#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {

	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        $('html,body').animate({
	          scrollTop: target.offset().top
	        }, 1000);
	        return false;
	      }
	    }
	  });
	});

$(document).ready(function(){
$('#open').click(function(){
$('#hide').slideToggle();
});
});	





















