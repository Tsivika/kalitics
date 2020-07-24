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
        confirmButtonText: 'Oui !',
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

export function confirmSwalAlertSubscription(msg, url, id) {
    Swal.fire({
        title: 'Vous êtes sur ?',
        text: msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Choisir !',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    $('#list-subscription').html(response.data.listHtml);
                    simpleSwalAlert(response.data.body, response.data.footer);
                    cardTarif();
                    subscriptionChoice();
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                });
        }
    })
}

export function confirmSwalAlertSubscriptionDelete(msg, url, id) {
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
                    $('#list-subscription-admin').html(response.data.listHtml);
                    simpleSwalAlert(response.data.body, response.data.footer);
                    cardTarif();
                    $('[data-toggle="tooltip"]').tooltip();
                    subscriptionDelete();
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
export function subscriptionChoice()
{
    $('.subsciprition_choice').on('click', function (e) {
        var subscription = $(this).data('subscription');
        e.preventDefault();

        var url = Routing.generate('app_espace_client_profil_subscription_choice', {'id':subscription});
        confirmSwalAlertSubscription('de choisir cet abonnement', url, subscription);
    });
}

export function subscriptionDelete()
{
    $('.delete_subscription').on('click', function (e) {
        var subscritpion = $(this).data('subscritpion');
        var url = Routing.generate('app_espace_admin_subscription_delete', {'id':subscritpion});
        e.preventDefault();
        confirmSwalAlertSubscriptionDelete('de vouloir supprimer cet abonnement', url, subscritpion);
    });
}
export function cardTarif()
{
    $(".card-tarif").mouseenter(function(){
        $(this).addClass('active');
        $(this).find(".fa-check-circle").css("color","white");
        $(this).find(".start").css("background-color","white");
        $(this).find(".start").css("color","rgba(0,201,174,1)");
    });

    $(".card-tarif").mouseleave(function(){
        $(this).removeClass('active');
        $(this).find(".fa-check-circle").css("color","rgba(0,201,174,1)");
        $(this).find(".start").css("color","white");
        $(this).find(".start").css("color","white");
        $(this).find(".start").css("background-color","rgba(0,201,174,1)");
    });
}
