import {
    simpleSwalAlert,
    confirmSwalAlertTestimonial,
    confirmSwalAlertPartner,
    confirmSwalAlertVideoGuide,
    confirmSwalAlertUserDelete,
    confirmSwalAlertUserDeactive
} from "./tools";
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

import tinymce from "tinymce";
import 'tinymce/themes/silver/theme';
import 'tinymce/plugins/paste';
import 'tinymce/plugins/link';

(function ($) {
    $(document).ready(function() {
        deleteTestimonial();
        deletePartner();
        deleteVideoGuide();
        showingSelectedFilename();
    });

    tinymce.init({
        selector: "textarea",
        init_instance_callback: function (editor) {
            editor.on('keypress', function (e) {
                if (getStats('testimonial_content').chars >= 200) {
                    simpleSwalAlert('Nombre de caractères autorisé pour le contenu : 200 maxi', '');
                }
            });
        },
        branding: false,
        height: 300,
        width: 660,
        plugins: 'link',
        toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        menubar: false,
        toolbar_items_size: 'small',
        style_formats: [{
            title: 'Bold text',
            inline: 'b'
        }, {
            title: 'Red text',
            inline: 'span',
            styles: {
                color: '#ff0000'
            }
        }, {
            title: 'Red header',
            block: 'h1',
            styles: {
                color: '#ff0000'
            }
        }, {
            title: 'Example 1',
            inline: 'span',
            classes: 'example1'
        }, {
            title: 'Example 2',
            inline: 'span',
            classes: 'example2'
        }, {
            title: 'Table styles'
        }, {
            title: 'Table row 1',
            selector: 'tr',
            classes: 'tablerow1'
        }],

        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }],
        content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ],

    });

    function getStats(id) {
        let body = tinymce.get(id).getBody(), text = tinymce.trim(body.innerText || body.textContent);

        return {
            chars: text.length,
            words: text.split(/[\w\u2019\'-]+/).length
        };
    }

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

    function showingSelectedFilename()
    {
        $('.dropdown-toggle').dropdown();
        $('.custom-file-input').on('change', function(event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent()
                .find('.custom-file-label')
                .html(inputFile.files[0].name);
        });
    }

    $(document).on('click', '#delete_user', function(e) {
        let user_id = $(this).data('id_user');
        let url = Routing.generate('app_espace_admin_user_delete', {'id':user_id});
        e.preventDefault();
        confirmSwalAlertUserDelete('de vouloir supprimer cet utilisateur', url, user_id);
    });

    $(document).on('click', '.deactive_user', function(e) {
        let user_id = $(this).data('id_user');
        let status = $(this).prop('checked');
        let msg_stat = status ? 'activer' : 'désactivé';
        let url = Routing.generate('app_espace_admin_user_deactive', {'id':user_id, 'status': status});
        e.preventDefault();
        confirmSwalAlertUserDeactive('de vouloir ' + msg_stat + ' cet utilisateur', url, msg_stat, user_id);
    });

})(jQuery);
