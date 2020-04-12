/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

//console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('ion-rangeslider');
require('@fortawesome/fontawesome-free');


/* Include only icons we need */
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

// add the selected icon
library.add(faAngleDown, faAddressBook, faEnvelope, faMapMarkerAlt, faHome, faUserFriends);

$(document).ready(function () {
    /*$('[data-toggle="popover"]').popover();*/


    /*range slider init */
    $(".js-range-slider").ionRangeSlider({
        skin: "round"
    });

    /* prevent bootstrap dropdown-menu in search box for closing */
    $('#soapy .dropdown-menu').click(function (e) {
        e.stopPropagation();
    });

    /* add smooth cross browser anger scroll*/
    $("a").on('click', function (event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;

            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 800, function () {

                window.location.hash = hash;
            });
        }
    });

});