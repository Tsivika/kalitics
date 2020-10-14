import Swal from '../plugins/sweetalert/js/sweetalert2.min';
import { customDatatable, customDatatableMeeting } from "./datatable/custom_datatable";
import '../plugins/sweetalert/css/sweetalert2.min.css';
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from 'axios';

Routing.setRoutingData(routes);

export function scrollToSection(sectionId) {
    $([document.documentElement, document.body]).stop(true, false).animate({
        scrollTop: $(sectionId).offset().top
    }, 2000);
}

export function cardTarif() {
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

export function confirmSwalAlertMeeting(msg, url, id) {
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
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-meetings').html(response.data.listHtml);
                    customDatatableMeeting('#dataTable_meeting');
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    customDatatableMeeting('#dataTable_meeting');
                });
        }
    })
}

export function confirmSwalAlertCreateMeeting(msg, url) {
    Swal.fire({
        // title: 'Vous êtes sur ?',
        html: msg,
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#00C9AE',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    if (response.data.success) {
                        window.location.replace(response.data.urlRedirect);
                    }
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
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                });
        }
    })
}

export function confirmSwalAlertSubscriptionDeactive(msg, url) {
    Swal.fire({
        title: 'Êtes-vous certaines de vouloir',
        html: msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF5A5F',
        cancelButtonColor: '#00C9AE',
        confirmButtonText: 'Se désabonner',
        cancelButtonText: 'Annuler',
        footer: 'Comme indiqué dans nos CGV, tout abonnement débuté ne peut être remboursé.Merci de votre confiance.'
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    $('#list-subscription').html(response.data.listHtml);
                    simpleSwalAlert(response.data.body, response.data.footer);
                    cardTarif();
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                });
        }
    })
}

export function confirmSwalAlertCouponDelete(msg, url, id) {
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
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-coupons').html(response.data.listHtml);
                    customDatatable('#dataTable_coupon');
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    customDatatable('#dataTable_coupon');
                });
        }
    })
}

export function confirmSwalAlertProfilDelete(msg, url) {
    Swal.fire({
        title: 'Attention !',
        text: msg,
        type: 'error',
        showCancelButton: true,
        confirmButtonColor: '#FF5A5F',
        cancelButtonColor: '#00C9AE',
        confirmButtonText: 'Supprimer mon compte',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    setTimeout(function(){
                        window.location.href = "/login";
                    }, 3000);
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', '');
                });
        }
    })
}

export function confirmSwalAlertProfilChangeRole(msg, url) {
    Swal.fire({
        title: 'Attention !',
        text: msg,
        type: 'error',
        showCancelButton: true,
        confirmButtonColor: '#FF5A5F',
        cancelButtonColor: '#00C9AE',
        confirmButtonText: 'Valider',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', '');
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

export function simpleSwalAlertSuccess(message, footer) {
    Swal.fire({
        title: 'Félicitation',
        type: 'success',
        html: message,
        confirmButtonText: 'Ok',
        confirmButtonColor: '#FF5A5F',
        footer: footer
    });
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
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    cardTarif();
                });
        }
    })
}

export function confirmSwalAlertCategoryDelete(msg, url, id) {
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
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-category').html(response.data.listHtml);
                    customDatatable('#dataTable_category');
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    customDatatable('#dataTable_category');
                });
        }
    })
}

export function confirmSwalAlertGuideDelete(msg, url, id) {
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
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-guide').html(response.data.listHtml);
                    customDatatable('#dataTable_guide');
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    customDatatable('#dataTable_guide');
                });
        }
    })
}

export function confirmSwalAlertTestimonial(msg, url, id) {
    Swal.fire({
        title: 'Vous êtes sur ?',
        text: msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Supprimer !',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-testimonials').html(response.data.listHtml);
                    customDatatableMeeting('#dataTable_testimonial');
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    customDatatableMeeting('#dataTable_testimonial');
                });
        }
    })
}

export function confirmSwalAlertPartner(msg, url, id) {
    Swal.fire({
        title: 'Vous êtes sur ?',
        text: msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Supprimer !',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-partner').html(response.data.listHtml);
                    customDatatableMeeting('#dataTable_partner');
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    customDatatableMeeting('#dataTable_partner');
                });
        }
    })
}

export function confirmSwalAlertVideoGuide(msg, url, id) {
    Swal.fire({
        title: 'Vous êtes sur ?',
        text: msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Supprimer !',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.value) {
            axios.get(url)
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-video-guide').html(response.data.listHtml);
                    customDatatableMeeting('#dataTable_video_guide');
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                    customDatatableMeeting('#dataTable_video_guide');
                });
        }
    })
}