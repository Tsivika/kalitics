import { simpleSwalAlert } from "./tools";
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from 'axios';
const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

try {
    var handler = StripeCheckout.configure({
        key: keyStripe,
        image: icon,
        locale: 'auto',
        token: function (token, args) {
            $('input#token-stripe').val(token.id);

            $('div#modal-confirmation-abonnement').modal('show');
            setTimeout(function(){
                $('#subscribe-form').submit();
            }, 1000);

        }
    });
} catch (e) {
    console.error('StripeCheckout is not defined');
}

try {
    $("#btn-payement-stripe").on("click", function (e) {
        var form    = $('#subscribe-form').serialize();

        axios.post(Routing.generate('app_espace_client_profil_subscription_pre_payment', {'id': type}), {
            data: form
        })
            .then(function (response) {
                if (response.success) {
                    handler.open({
                        name: 'Hiboo',
                        description: '',
                        zipCode: false,
                        amount: checkPrice(),
                        currency: 'EUR',
                        allowRememberMe: false,
                        email: $('.email-user').val()
                    });
                    $("input#total-paid").val(checkPrixByCountry() / 100);
                    $('input#subscription-next-id').val($(" input[type='radio']:checked").val());

                } else {
                    // simpleSwalAlert(response.data, '')
                    console.log(response.data)
                }
            })
            .catch(function (error) {
                simpleSwalAlert(error, '');
            });
        e.preventDefault();
    })
} catch (e) {
    console.error('btn-payement-stripe is null');
}

var getPrice = function (amount, reduction) {
    var price = 0;
    if(parseInt(reduction) == 100){
        return price;
    }
    var totalReduction;
    totalReduction =   parseInt(reduction) * parseInt(amount) / 100;
    price = (parseInt(amount) - totalReduction) * 100;


    return price;
}
var checkPrice = function () {
    var projet = $('#hiboo_projet').val();
    var entreprise = $('#hiboo_entreprise').val();
    var price = 0;
    var reduction = $('#reduction').val();
        if ($('.projet').is(':checked')) {
            price = getPrice(projet, reduction);
        }
        if ($('.entreprise').is(':checked')) {
            price = getPrice(entreprise, reduction);
        }
    return price;
};
