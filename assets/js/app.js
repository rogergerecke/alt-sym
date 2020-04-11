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



/* Include only icons we need */
// https://fontawesome.com/how-to-use/with-the-api/setup/importing-icons
import {library} from '@fortawesome/fontawesome-svg-core'
import {faAngleDown, faAddressBook} from '@fortawesome/free-solid-svg-icons'
import {far} from '@fortawesome/free-regular-svg-icons'
import {fab} from '@fortawesome/free-brands-svg-icons'
// add the selected icon
library.add(faAngleDown, faAddressBook);

$(document).ready(function () {
    /*$('[data-toggle="popover"]').popover();*/

    /* Hostel search distance slider */
    const $valueSpan = $('.valueSpan');
    const $value = $('#see_distance');
    $valueSpan.html($value.val());
    $value.on('input change', () => {

        $valueSpan.html($value.val());
    });

    /*range slider init */
   /* $(".js-price-slider").ionRangeSlider();
    $(".js-guest-slider").ionRangeSlider();*/
    $(".js-range-slider").ionRangeSlider({
        skin: "sharp"
    });

    /* prevent the extra menu from close when clicking the option */
    $('#soapy .dropdown-menu').click(function(e) {
        e.stopPropagation();
    });

});