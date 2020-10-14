import {
    confirmSwalAlertTestimonial,
    confirmSwalAlertPartner,
    confirmSwalAlertVideoGuide
} from "./tools";
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import { customDatatableMeeting } from "./datatable/custom_datatable";

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        customDatatableMeeting('#dataTable_testimonial');
        customDatatableMeeting('#dataTable_partner');
        customDatatableMeeting('#dataTable_video_guide');
        deleteTestimonial();
        deletePartner();
        deleteVideoGuide();
    });

    function deleteTestimonial()
    {
        $(document).on('click', '.delete_testimonial', function(e) {
            let testimonial = $(this).data('testimonial');
            let url = Routing.generate('app_espace_admin_testimonial_delete', {'id':testimonial});
            e.preventDefault();
            confirmSwalAlertTestimonial('de vouloir supprimer cet avis', url, testimonial);
        }); 
    }

    function deletePartner()
    {
        $(document).on('click', '.delete_partner', function(e) {
            let testimonial = $(this).data('partner');
            let url = Routing.generate('app_espace_admin_partner_delete', {'id':testimonial});
            e.preventDefault();
            confirmSwalAlertPartner('de vouloir supprimer ce partenaire', url, testimonial);
        });
    }

    function deleteVideoGuide()
    {
        $(document).on('click', '.delete_video_guide', function(e) {
            let video_guide = $(this).data('video_guide');
            let url = Routing.generate('app_espace_admin_video_guide_delete', {'id':video_guide});
            e.preventDefault();
            confirmSwalAlertVideoGuide('de vouloir supprimer cette Guide vidéo', url, video_guide);
        });
    }

})(jQuery);