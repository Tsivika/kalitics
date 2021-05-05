/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

const $ = require('jquery');
global.$ = global.jQuery = $;

import '../css/app.css';
import './jquery.min';
import './bootstrap.bundle';
import '../externes/jquery.easing/jquery.easing.min';
import { simpleSwalAlert } from "./tools";

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from "axios";

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        dailyUserAddAjax();
        weeklyUserAddAjax();
    });

    function dailyUserAddAjax() {
        $(document).on('blur','#pointing_date', function(e){
            e.preventDefault();
            var chantier = $('#pointing_chantier').val();
            var user = $('#pointing_user').val();
            var date = $('#pointing_date').val();

            axios.post(Routing.generate('daily_user_pointing'),  {
                chantier: chantier,
                user: user,
                date: date
            })
                .then(function (response) {
                    if (response.data.body !== '') {
                        simpleSwalAlert(response.data.title, response.data.body, response.data.footer);
                        document.getElementById("submit_form").disabled = true;
                    } else {
                        document.getElementById("submit_form").disabled = false;
                    }
                })
                .catch(function (error) {
                    simpleSwalAlert(error, 'Erreur');
                });
        });
    }

    function weeklyUserAddAjax() {
        $(document).on('blur','#pointing_duration', function(e){
            e.preventDefault();
            var user = $('#pointing_user').val();
            var duration = $('#pointing_duration').val();

            axios.post(Routing.generate('weekly_user_pointing'),  {
                user: user,
                duration: duration
            })
                .then(function (response) {
                    /*if (response.data.body !== '') {
                        simpleSwalAlert(response.data.title, response.data.body, response.data.footer);
                        document.getElementById("submit_form").disabled = true;
                    } else {
                        document.getElementById("submit_form").disabled = false;
                    }*/
                })
                .catch(function (error) {
                    simpleSwalAlert(error, 'Erreur');
                });
        });
    }
})(jQuery);
