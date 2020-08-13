/* base js file include code for all sites */
const jQuery = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap'); // todo remove slider add magictoolbox
require('ion-rangeslider');
require('@google/markerclustererplus');

/* Masonry */
require('imagesloaded')
var jQueryBridget = require('jquery-bridget');
var Masonry = require('masonry-layout');
jQueryBridget('masonry', Masonry, jQuery);

jQuery(function ($) {
    $(window).on('load', function () {

        // POS_LOAD the script is inserted in the window.onload(). Can use $

        // activate bootstrap tools
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip()

        /* prevent dropdown before close in the hostel_search on start page */
        $('#soapy .dropdown-menu').on('click', function (e) {
            e.stopPropagation();
        });

        /* prevent dropdown before close in the view hostel list side bar */
        $('#search-sidebar .dropdown-menu').on('click', function (e) {
            e.stopPropagation();
        });

        /* init the range-slider plugin */
        $(".js-range-slider").ionRangeSlider({
            skin: "round"
        });


        /* ajax request the notice hostel id */
        $('a.notice-button').click(function (event) {
            event.preventDefault();

            // toggle the heart icon
            $(this).find('i').toggleClass('fas far');

            // must save the link before ajax
            let link = $(this);
            let counter = $('a.nav-link').find('#counter');

            $.ajax({
                url: $(this).attr('href'),
                dataType: 'json',
                success: function (response) {
                    link.attr('href', response.url)
                    counter.html(response.counter)
                }
            });
            // add the notice counter value
            $(counter).add('#counter', counter)

            return false; // for good measure
        });

        /* add smooth cross browser scroll to a#anker */
        $("a").on('click', function (event) {
            // if a# not empty prevent and save it
            if (this.hash !== "") {
                /*event.preventDefault();*/
                var hash = this.hash;

                // and smooth scroll to #hash
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 800, function () {

                    window.location.hash = hash;
                });
                return false;
            }
        });

        /* Masonry only with jQuery Bridge */
        $('.grid').masonry({
            itemSelector: ".grid-item",
            columnWidth: ".grid-sizer",
            gutter: 15,
            percentPosition: true
        });


    });
    // POS_READY the script is inserted in the jQuery's ready function
});