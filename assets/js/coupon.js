import {
    confirmSwalAlertCouponDelete,
    simpleSwalAlert,
    scrollToSection
} from "./tools";
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import axios from "axios";

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        couponAddAjax();
        couponSwitchStatus();
        couponDelete();
    });

    function couponDelete()
    {
        $(document).on('click', '.delete_coupon', function(e) {
            var coupon = $(this).data('coupon');
            var url = Routing.generate('app_espace_admin_coupon_delete', {'id':coupon});
            e.preventDefault();
            confirmSwalAlertCouponDelete('de vouloir supprimer ce coupon', url, coupon);
        });
    }

    function couponAddAjax() {
        $(document).on('submit','#form_coupon', function(e){
            e.preventDefault();
            var name = $('#coupon_admin_name').val();
            var reduction = $('#coupon_admin_reduction').val();
            var code = $('#coupon_admin_code').val();
            var status = $('#coupon_admin_status').val();

            axios.post(Routing.generate('app_espace_admin_coupon_add'),  {
                name: name,
                reduction:reduction,
                code:code,
                status:status
            })
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#coupon_admin_name').val('');
                    $('#coupon_admin_reduction').val('');
                    $('#coupon_admin_code').val('');
                    $('#list-coupons').html(response.data.listHtml);
                    scrollToSection('#list-coupons');

                })
                .catch(function (error) {
                    $('#list-coupons').html(response.data.listHtml);
                });
        });
    }

    function couponSwitchStatus()
    {
        $(document).on('click','.coupon_switch', function(e){
            let id_coupon = $(this).data('coupon_switch');
            let status = $(this).prop('checked');
            let url = Routing.generate('app_espace_admin_coupon_switch_status', {'id':id_coupon, 'status': status});

            axios.get(url)
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#list-coupons').html(response.data.listHtml);
                })
                .catch(function (error) {
                    simpleSwalAlert('Une erreur s\'est produite.', response.data.footer);
                });
        });
    }

})(jQuery);