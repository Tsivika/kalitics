import {
    cardTarif,
    confirmSwalAlertSubscriptionDeactive,
    confirmSwalAlertSubscription,
    confirmSwalAlertSubscriptionDelete
} from "./tools";
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        cardTarif();
        subscriptionChoice();
        subscriptionDelete();
        subscriptionDeactive();
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

    function subscriptionChoice()
    {
        $(document).on('click', '.subsciprition_choice', function(e) {
            var subscription = $(this).data('subscription');
            var url = Routing.generate('app_espace_client_profil_subscription_choice', {'id':subscription});
            e.preventDefault();
            confirmSwalAlertSubscription('de choisir cet abonnement', url, subscription);
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

})(jQuery);