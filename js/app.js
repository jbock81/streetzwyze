$(function() {
    'use strict'

    $('[data-toggle="offcanvas"]').on('click', function() {
        $('.offcanvas-collapse').toggleClass('open')
    })

    $('#AppGallery a').nivoLightbox({
        effect: 'fall'
    });

    $('#quotes-slider').owlCarousel({
        items: 1,
        loop: true,
        dots: false,
        autoplay: true
    });

    var ms = $('#main-slider');
    // Listen to owl events:

    ms.on('initialized.owl.carousel', function(event) {
        $(".slider-1 h1").addClass("animate__animated animate__fadeInLeft");
        $(".slider-1 h3").addClass("animate__animated animate__fadeInLeft animate__delay-2s");
        $(".slider-1 p").addClass("animate__animated animate__fadeInLeft animate__delay-3s");
        $(".slider-1 a").addClass("animate__animated animate__fadeInLeft animate__delay-4s");
        $(".slider-1 img").addClass("animate__animated animate__fadeInRight animate__delay-5s");
    });

    ms.owlCarousel({
        items: 1,
        loop: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 10000
    });
    ms.owlCarousel();
    
    ms.on('changed.owl.carousel', function(event) {
        if($(".slider-1 h1").hasClass('animate__animated animate__fadeInLeft')){
            $(".slider-1 h1").removeClass();
        }else{
            $(".slider-1 h1").addClass("animate__animated animate__fadeInLeft");
        }

        if($(".slider-1 h3").hasClass('animate__animated animate__fadeInLeft')){
            $(".slider-1 h3").removeClass();
        }else{
            $(".slider-1 h3").addClass("animate__animated animate__fadeInLeft animate__delay-2s");
        }

        if($(".slider-1 p").hasClass('py-4 animate__animated animate__fadeInLeft')){
            $(".slider-1 p").removeClass('animate__animated animate__fadeInLeft animate__delay-3s');
        }else{
            $(".slider-1 p").addClass("animate__animated animate__fadeInLeft animate__delay-3s");
        }

        if($(".slider-1 a").hasClass('animate__animated animate__fadeInLeft')){
            $(".slider-1 a").removeClass('animate__animated animate__fadeInLeft animate__delay-4s');
        }else{
            $(".slider-1 a").addClass("animate__animated animate__fadeInLeft animate__delay-4s");
        }

        if($(".slider-1 img").hasClass('img-fluid animate__animated animate__fadeInRight animate__delay-5s')){
            $(".slider-1 img").removeClass('animate__animated animate__fadeInRight animate__delay-5s');
        }else{
            $(".slider-1 img").addClass("animate__animated animate__fadeInRight animate__delay-5s");
        }

        //

        if($(".slider-2 h1").hasClass('animate__animated animate__fadeInLeft')){
            $(".slider-2 h1").removeClass();
        }else{
            $(".slider-2 h1").addClass("animate__animated animate__fadeInLeft");
        }

        if($(".slider-2 h3").hasClass('animate__animated animate__fadeInLeft')){
            $(".slider-2 h3").removeClass();
        }else{
            $(".slider-2 h3").addClass("animate__animated animate__fadeInLeft animate__delay-2s");
        }

        if($(".slider-2 p").hasClass('py-4 animate__animated animate__fadeInLeft')){
            $(".slider-2 p").removeClass('animate__animated animate__fadeInLeft animate__delay-3s');
        }else{
            $(".slider-2 p").addClass("animate__animated animate__fadeInLeft animate__delay-3s");
        }

        if($(".slider-2 a").hasClass('animate__animated animate__fadeInLeft')){
            $(".slider-2 a").removeClass('animate__animated animate__fadeInLeft animate__delay-4s');
        }else{
            $(".slider-2 a").addClass("animate__animated animate__fadeInLeft animate__delay-4s");
        }

        if($(".slider-2 img").hasClass('img-fluid animate__animated animate__fadeInRight animate__delay-5s')){
            $(".slider-2 img").removeClass('animate__animated animate__fadeInRight animate__delay-5s');
        }else{
            $(".slider-2 img").addClass("animate__animated animate__fadeInRight animate__delay-5s");
        }
    });

    $("#AppGallery").owlCarousel({
        items: 5,
        loop: true,
        dots: true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });

    var sslider = $('#signup-slider');
    $(sslider).owlCarousel({
        items: 2,
        loop: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 2
            }
        }
    });

    sslider.owlCarousel();
    
    $('.signup-merchant').click(function() {
        sslider.trigger('prev.owl.carousel', [300]);
    });

    $('.signup-seller').click(function() {
        sslider.trigger('next.owl.carousel', [300]);
    });


    $("#sidebar").sticky({
        topSpacing: 100,
        bottomSpacing: 800
    });

    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;

    $(".btn-next").click(function() {

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({ opacity: 0 }, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({ 'opacity': opacity });
            },
            duration: 600
        });
    });

    $(".btn-previous").click(function() {

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();

        //hide the current fieldset with style
        current_fs.animate({ opacity: 0 }, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({ 'opacity': opacity });
            },
            duration: 600
        });
    });

    $('.radio-group .radio').click(function() {
        $(this).parent().find('.radio').removeClass('selected');
        $(this).addClass('selected');
    });

    $(".reservation-submit").click(function() {
        return false;
    });
});