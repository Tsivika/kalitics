import Swal from '../plugins/sweetalert/js/sweetalert2.min'
import '../plugins/sweetalert/css/sweetalert2.min.css'
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from 'axios';

Routing.setRoutingData(routes);

export function confirmSwalAlert(msg, url, id) {
    Swal.fire({
        title: 'Vous êtes sur ?',
        text: msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    $('#meeting_'+id+'').fadeOut('slow', function() {
                        $(this).remove();
                    });
                    simpleSwalAlert(response.data.body, response.data.footer);
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                });
        }
    })
}

export function simpleSwalAlert(message, footer) {
    Swal.fire({
        title: '<div class="logo_small"></div>',
        html: message,
        confirmButtonText: 'Ok',
        confirmButtonColor: '#FF5A5F',
        footer: footer
    });
}
