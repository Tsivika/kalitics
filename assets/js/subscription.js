import {subscriptionChoice, subscriptionDelete, cardTarif} from "./tools";
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        cardTarif();
        subscriptionChoice();
        subscriptionDelete();
        $('[data-toggle="tooltip"]').tooltip();
        // $('.delete_subscription_tooltip').tooltip('toggle');
    });

})(jQuery);