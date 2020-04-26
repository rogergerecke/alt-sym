/* base js file include code for all sites */
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('ion-rangeslider');
require('@fortawesome/fontawesome-free');


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

$(document).ready(function () {
    /*$('[data-toggle="popover"]').popover();*/


    /* init the range-slider plugin */
    $(".js-range-slider").ionRangeSlider({
        skin: "round"
    });

    /* prevent dropdown before close in the hostel_search */
    $('#soapy .dropdown-menu').click(function (e) {
        e.stopPropagation();
    });

    /* add smooth cross browser scroll to a#anker */
    $("a").on('click', function (event) {
        // if a# not empty prevent and save it
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;

            // and smooth scroll to #hash
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 800, function () {

                window.location.hash = hash;
            });
        }
    });

});