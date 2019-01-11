/*
------------------------------------------------------------------------
* Template Name    : Oxcer | Responsive Bootstrap 4 Landing Template  *
* Author           : ThemesBoss                                       *
* Version          : 1.0.0                                            *
* Created          : November 2018                                    *
* File Description : Main Js file of the template                     *
*-----------------------------------------------------------------------
*/

! function($) {
    "use strict";

    var Oxcer = function() {};

    //scroll
    Oxcer.prototype.initStickyMenu = function() {
        $(window).on('scroll',function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 50) {
                $(".sticky").addClass("stickyadd");
            } else {
                $(".sticky").removeClass("stickyadd");
            }
        });
    },

    //Smooth
    Oxcer.prototype.initSmoothLink = function() {
        $('.navbar-nav a').on('click', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top - 0
            }, 1500, 'easeInOutExpo');
            event.preventDefault();
        });
    },

    //Scrollspy
    Oxcer.prototype.initScrollspy = function() {
        $("#navbarCollapse").scrollspy({
            offset:20
        });
    },

    //Collaps
    Oxcer.prototype.initCollapsHide = function() {
        $(document).on('click','.navbar-collapse.show',function(e) {
            if( $(e.target).is('a') ) {
                $(this).collapse('hide');
            }
        });
    },

    //Client Slider
    Oxcer.prototype.initClientSlider = function() {
        $("#owl-demo").owlCarousel({
            autoPlay: 7000,
            stopOnHover: true,
            navigation: false,
            paginationSpeed: 1000,
            goToFirstSpeed: 2000,
            singleItem: true,
            autoHeight: true,
        });
    },

    Oxcer.prototype.init = function() {
        this.initStickyMenu();
        this.initSmoothLink();
        this.initScrollspy();
        this.initCollapsHide();
        this.initClientSlider();
    },
    //init
    $.Oxcer = new Oxcer, $.Oxcer.Constructor = Oxcer
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Oxcer.init();
}(window.jQuery);
