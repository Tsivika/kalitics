import {
    cardTarif,
    confirmSwalAlertSubscriptionDeactive,
    confirmSwalAlertSubscription,
    confirmSwalAlertSubscriptionDelete,
    simpleSwalAlert, simpleSwalAlertSuccess, scrollToSection
} from "./tools";
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from 'axios';
import customDatatable from "./datatable/custom_datatable";
import showToast from "./toastr";
const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        cardTarif();
        subscriptionChoice();
        subscriptionDelete();
        subscriptionDeactive();
        codePromo();
        $('[data-toggle="tooltip"]').tooltip();
    });

    function subscriptionDeactive()
    {
        $(document).on('click','#subscription_deactive', function(e){
            var url = Routing.generate('app_espace_client_profil_subscription_deactive');
            e.preventDefault();
            confirmSwalAlertSubscriptionDeactive('annuler votre abonnement', url);
        });
    }

    function subscriptionDelete()
    {
        $(document).on('click', '.delete_subscription', function(e) {
            var subscritpion = $(this).data('subscritpion');
            var url = Routing.generate('app_espace_admin_subscription_delete', {'id':subscritpion});
            e.preventDefault();
            confirmSwalAlertSubscriptionDelete('de vouloir supprimer cet abonnement', url, subscritpion);
        });
    }

    function subscriptionChoice()
    {
        $(document).on('click', '.subsciprition_choice', function(e) {
            var subscription = $(this).data('subscription');
            var url = Routing.generate('app_espace_client_profil_subscription_choice', {'id':subscription});
            e.preventDefault();
            confirmSwalAlertSubscription('de choisir cet abonnement', url, subscription);
        });
    }

    function codePromo()
    {
        $(document).on('click', '#btnCodePromo', function(e) {
            var code = $('#user_account_codePromo').val();
            e.preventDefault();
            axios.post(Routing.generate('app_espace_client_profil_code_promo'), {
                code: code
            })
                .then(function (response) {
                    if (response.data.result ==true) {
                        simpleSwalAlertSuccess('Ce code promo vous offre une remise de ' + response.data.reduction + '% sur le paiement de votre abonnement', '');
                        $('#reduction').val(response.data.reduction);
                    } else {
                        simpleSwalAlert('Code invalide ou code qui n\'existe pas.', '');
                    }
                })
                .catch(function (error) {
                    console.log('erreur');
                });
        });
    }

})(jQuery);