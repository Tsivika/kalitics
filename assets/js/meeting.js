import * as $ from 'jquery';
import showToast from "./toastr";
import customDatatable from "./datatable/custom_datatable";
import { simpleSwalAlert, confirmSwalAlertMeeting, confirmSwalAlertSubscription } from "./tools";
import axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');

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
            let count = $('.participant-proto-wrapper').length;
            var nbr_participant = $('.participants').data('nbr_participant');

            if (parseInt(nbr_participant) === count) {

                simpleSwalAlert('Vous avez atteint le nombre maximum de participants.', '');

                return;
            } else {
                var prototype = $wrapper.data('prototype');
                var index = $wrapper.data('index');
                var newForm = prototype.replace(/__name__/g, index);
                $wrapper.data('index', index + 1);
                $(this).before(newForm);

                return;
            }
        });

        $(document).on('blur', '#meeting_durationM', function(e) {
            e.preventDefault();
            let durationM = $('.durationM').val();
            let durationInput = $(this).val()

            if (durationInput > parseInt(durationM)) {
                simpleSwalAlert('La durée de votre réunion est de :' + durationM, '');
                $(this).focus();
                $(this).val('20');
            }
        });

        customDatatable('#dataTable_meeting');
        detailMeeting();
        deleteMeeting();
        deleteMeetingBo();
    });

    function deleteMeeting()
    {
        $(document).on('click', '.delete_meeting', function(e) {
            var meeting = $(this).data('meeting');
            var url = Routing.generate('app_epsace_client_meeting_delete', {'id':meeting});
            e.preventDefault();
            confirmSwalAlertMeeting('de vouloir supprimer cette réunion', url, meeting);
        });
    }

    function deleteMeetingBo()
    {
        $(document).on('click', '.delete_meeting_bo', function(e) {
            var meeting = $(this).data('meeting');
            var url = Routing.generate('app_espace_admin_meeting_delete', {'id':meeting});
            e.preventDefault();
            confirmSwalAlertMeeting('de vouloir supprimer cette réunion', url, meeting);
        });
    }

    function detailMeeting() {
        $(document).on('click', '.detail_meeting', function(e) {
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

})(jQuery);
