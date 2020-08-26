import { simpleSwalAlert } from "./tools";
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from 'axios';
const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

var stripe = Stripe('pk_test_51H84neKvOQA5MGqY35NRBUYhZbeU7MxUg1mMxznzVqa7txtKg8fDLwfSyOMzQy23hDOtMK0cs7xG8u74yzbglt5C00alOB3VI2');
var elements = stripe.elements();

var style = {
    base: {
        iconColor: '#c4f0ff',
        color: '#495057',
        fontWeight: 500,
        fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
        fontSize: '16px',
        fontSmoothing: 'antialiased',
        ':-webkit-autofill': {
            color: '#fce883',
        },
        '::placeholder': {
            color: '#cccccc',
        },
    },
    invalid: {
        iconColor: '#ff00ff',
        color: '#FF5A5F',
    },
}
var card = elements.create('card', {style: style});
card.mount('#card-element');

card.addEventListener("change", (event) => {
    let displayError = document.getElementById("card-errors")
    if(event.error){
        displayError.textContent = event.error.message;
    }else{
        displayError.textContent = "";
    }
});

var form = document.getElementById('payment-form');
form.addEventListener('submit', function (event) {
    event.preventDefault();

    stripe.createToken(card).then(function (result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
});

function stripeTokenHandler(token) {
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    form.submit();
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
