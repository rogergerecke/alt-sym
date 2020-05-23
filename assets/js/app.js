/* base js file include code for all sites */
const jQuery = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap'); // todo remove slider add magictoolbox
require('ion-rangeslider');
require('@fortawesome/fontawesome-free');
require('@google/markerclustererplus');


/* Include only icons we need from fontawesome */
// https://fontawesome.com/how-to-use/with-the-api/setup/importing-icons
import {library} from '@fortawesome/fontawesome-svg-core'
import {
    faAngleDown,
    faAddressBook,
    faEnvelope,
    faMapMarkerAlt,
    faHome,
    faUserFriends
} from '@fortawesome/free-solid-svg-icons'

// load only the selected icons from fontawesome
library.add(faAngleDown, faAddressBook, faEnvelope, faMapMarkerAlt, faHome, faUserFriends);

jQuery(function ($) {
    $(window).on('load', function () {
        // POS_LOAD the script is inserted in the window.onload(). Can use $
        /*$('[data-toggle="popover"]').popover();*/

        /* prevent dropdown before close in the hostel_search on start page */
        $('#soapy .dropdown-menu').on('click',function (e) {
            e.stopPropagation();
        });

        /* prevent dropdown before close in the view hostel list side bar */
        $('#search-sidebar .dropdown-menu').on('click',function (e) {
            e.stopPropagation();
        });

        /* init the range-slider plugin */
        $(".js-range-slider").ionRangeSlider({
            skin: "round"
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

    });
    // POS_READY the script is inserted in the jQuery's ready function
});