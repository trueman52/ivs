// JavaScript Document
$(document).ready(function () {

    $('.dropdown').hover(function () {
        $(this).addClass('show');
    },
    function () {
        $(this).removeClass('show');
        $(this).find('.dropdown-menu').removeClass('show');
    });

    $('.navbar-toggler,.menu_close a').click(function (e) {
        e.preventDefault();
        jQuery('.mobile-menu').toggleClass('active');
    })

    $('body:not(.booking-page) .sticky-content.summary-details .col-lg-7, body:not(.booking-page) .sticky-content.summary-details .cart_sm').theiaStickySidebar({
        additionalMarginTop: 168
    });

    $('body:not(.booking-page) .sticky-content.summary-details .col-lg-7, body:not(.booking-page) .sticky-content.summary-details .col-lg-5').theiaStickySidebar({
        additionalMarginTop: 168
    });

    $('body:not(.booking-page) .sticky-content:not(.summary-details) .col-lg-7, body:not(.booking-page) .sticky-content:not(.summary-details) .col-lg-5').theiaStickySidebar({
        additionalMarginTop: 100
    });

    jQuery('.mobile_menu_opener').click(function () {
        jQuery('body').toggleClass('active');
    })


    //$(".option-select").chosen({disable_search_threshold: 10});

    $('.joiningevents__card a').responsiveEqualHeightGrid();
    $('.spaces__content__card__content').responsiveEqualHeightGrid();
    $('.spaces__content__card').responsiveEqualHeightGrid();
    $('.same_height_1').responsiveEqualHeightGrid();


    $('.spaces__top li a').click(function () {
        $('.spaces__top li').removeClass('active');
        $(this).parent().addClass('active');

        var tabLink = $(this).attr('href');

        $('.tab-content .tab-pane').removeClass('active');
        $(tabLink).addClass('active');
    })

    $(function () {
        if (navigator.userAgent.indexOf('Mac OS X') != -1) {
            $(".abc").css("line-height", "40px");
        }

        if (navigator.userAgent.indexOf('Mac') > 0 && navigator.userAgent.indexOf('Firefox') > 0) {
            $(".abc").css("line-height", "38px");
        }

        if (navigator.userAgent.indexOf('Mac') > 0 && navigator.userAgent.indexOf('Chrome') > 0) {
            $(".abc").css("line-height", "40px");
        }
    });





    /*var pageBoby = $('body');
     var topBar=2;
     $(window).on('scroll', function() {
     if ($(this).scrollTop() >= topBar) {
     pageBoby.addClass("f-header");
     } else {
     pageBoby.removeClass("f-header");
     }
     });*/


    jQuery('.sp_menu_top ul li a').on('click', function (event) {
        var $anchor = jQuery(this);
        jQuery('html, body').stop().animate({
            scrollTop: jQuery($anchor.attr('href')).offset().top
        }, 500);
        event.preventDefault();
    });





    jQuery('.owl-carousel').owlCarousel({
        loop: true,
        margin: 0,
        responsiveClass: true,
        autoplay: true,
        nav: false,
        center: true,
        dots: false,
        responsive: {
            0: {
                items: 2,
                margin: 4,
            },
            480: {
                items: 2,
                margin: 2,
            },
            640: {
                items: 2,
                margin: 2,
            },
            768: {
                items: 3,
                margin: 2,
            },
            992: {
                items: 4,
                margin: 4,
            },
            1200: {
                items: 4,
                margin: 4,    },
        }
    });



});

jQuery(window).on('load', function () {

    jQuery('.step_single a').responsiveEqualHeightGrid();
    jQuery('.sp_event_info').responsiveEqualHeightGrid();
    jQuery('.spaces_event_single').responsiveEqualHeightGrid();
    jQuery('.same_height_1').responsiveEqualHeightGrid();
    var offsetContainer = jQuery('#inner_header').attr('class');


    if (offsetContainer != undefined || offsetContainer != null) {
        var stickyOffset = jQuery('#inner_header').offset().top;
        var headerHeight = jQuery('nav.navbar').outerHeight();
        //console.log(headerHeight);

        jQuery(window).scroll(function () {
            var sticky = jQuery('#inner_header'),
                    scroll = jQuery(window).scrollTop();

            if (scroll >= stickyOffset - headerHeight) {
                sticky.addClass('fixed');
            } else {
                sticky.removeClass('fixed');
            }
        });
    }


})


