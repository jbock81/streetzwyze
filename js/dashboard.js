$(function() {
    'use strict'

    $('[data-toggle="offcanvas"]').on('click', function() {
        $('.offcanvas-collapse').toggleClass('open')
    });

    $("#mobilemenu").mmenu({
        offCanvas: {
            position  : "left",
            zposition : "front"
        },
        navbar: {
            add: false,
            title : ""
        }
    });

    var API = $("#mobilemenu").data( "mmenu" );
    $('.navbar-toggler').click(function(){
        API.open();
    });

    $('.close-panel').click(function(){
        API.close();
    });

    $("#adv-slider").owlCarousel({
        items: 1,
        loop: true,
        dots: true
    });

    $('.float-nav').click(function() {
        $('.main-nav, .menu-btn').toggleClass('active');
    });

    $('#profile-progress').circleProgress({
        value: 0.75,
        size: 60,
        fill: {
            color: "#B53336"
        }
    });

    $('#profile-main').circleProgress({
        value: 0.75,
        size: 200,
        fill: {
            color: "#B53336"
        }
    });
});