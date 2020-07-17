import * as $ from 'jquery';
import showToast from "./toastr";
import customDatatable from "./datatable/custom_datatable";
import { simpleSwalAlert, confirmSwalAlert } from "./tools";

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from 'axios';

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        var $wrapper = $('.participant-data-proto-wrapper');
        $(document).on('click', '.remove_participant', function(e) {
            e.preventDefault();
            $(this).closest('.participant-proto-wrapper')
                .fadeOut()
                .remove();
        });
        $(document).on('click', '#add_participant', function(e) {
            e.preventDefault();
            // Get the data-prototype explained earlier
            var prototype = $wrapper.data('prototype');
            // get the new index
            var index = $wrapper.data('index');
            // Replace '__name__' in the prototype's HTML to
            // instead be a number based on how many items we have
            var newForm = prototype.replace(/__name__/g, index);
            // increase the index with one for the next item
            $wrapper.data('index', index + 1);
            // Display the form in the page before the "new" link
            $(this).before(newForm);
        });

        //DataTable list Meeting
        customDatatable('#dataTable_meeting');
        detailMeeting();
        deleteMeeting();
    });

    function deleteMeeting()
    {
        $('.delete_meeting').on('click', function (e) {
            var meeting = $(this).data('meeting');
            var url = Routing.generate('app_epsace_client_meeting_delete', {'id':meeting});
            e.preventDefault();
            confirmSwalAlert('de vouloir supprimer cette réunion', url, meeting);
        });
    }

    function detailMeeting() {
        $('.detail_meeting').on('click', function (e) {
            var meeting = $(this).data('meeting');
            var url = Routing.generate('app_espace_client_meeting_detail', {'id':meeting});
            e.preventDefault();
            axios.get(url)
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                });
        });
    }

        // Copy link List meeting
    /*var copyInputBtn = document.querySelector('.js-inputcopybtn');

    copyInputBtn.addEventListener('click', function(event) {
        var copyInput = document.querySelector('.js-copyInput');
        copyInput.focus();
        copyInput.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            showToast('success', 'Copie ok');
        } catch (err) {
            showToast('error', 'Copie OK');
        }
    });*/

})(jQuery);
