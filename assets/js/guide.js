import {
    confirmSwalAlertGuideDelete,
    confirmSwalAlertCategoryDelete,
    simpleSwalAlert,
    scrollToSection
} from "./tools";
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import customDatatable from "./datatable/custom_datatable";
import axios from "axios";

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        customDatatable('#dataTable_category');
        customDatatable('#dataTable_guide');
        categoryAddAjax();
        categoryDelete();
        guideAddAjax();
        guideDelete();
        var bodyEl = $('body'),
            accordionDT = $('.accordion').find('dt'),
            accordionDD = accordionDT.next('dd'),
            parentHeight = accordionDD.height(),
            childHeight = accordionDD.children('.content').outerHeight(true),
            newHeight = parentHeight > 0 ? 0 : childHeight,
            accordionPanel = $('.accordion-panel'),
            buttonsWrapper = accordionPanel.find('.buttons-wrapper'),
            openBtn = accordionPanel.find('.open-btn'),
            closeBtn = accordionPanel.find('.close-btn');

        bodyEl.on('click', function(argument) {
            var totalItems = $('.accordion').children('dt').length;
            var totalItemsOpen = $('.accordion').children('dt.is-open').length;

            if (totalItems == totalItemsOpen) {
                openBtn.addClass('hidden');
                closeBtn.removeClass('hidden');
                buttonsWrapper.addClass('is-open');
            } else {
                openBtn.removeClass('hidden');
                closeBtn.addClass('hidden');
                buttonsWrapper.removeClass('is-open');
            }
        });

        function openAll() {

            openBtn.on('click', function(argument) {

                accordionDD.each(function(argument) {
                    var eachNewHeight = $(this).children('.content').outerHeight(true);
                    $(this).css({
                        height: eachNewHeight
                    });
                });
                accordionDT.addClass('is-open');
            });
        }

        function closeAll() {

            closeBtn.on('click', function(argument) {
                accordionDD.css({
                    height: 0
                });
                accordionDT.removeClass('is-open');
            });
        }

        function openCloseItem() {
            accordionDT.on('click', function() {

                var el = $(this),
                    target = el.next('dd'),
                    parentHeight = target.height(),
                    childHeight = target.children('.content').outerHeight(true),
                    newHeight = parentHeight > 0 ? 0 : childHeight;

                // animate to new height
                target.css({
                    height: newHeight
                });

                // remove existing classes & add class to clicked target
                if (!el.hasClass('is-open')) {
                    el.addClass('is-open');
                }

                // if we are on clicked target then remove the class
                else {
                    el.removeClass('is-open');
                }
            });
        }

        openAll();
        closeAll();
        openCloseItem();
    });

    function categoryDelete() {
        $(document).on('click', '.delete_category', function(e) {
            var category = $(this).data('category');
            var url = Routing.generate('app_espace_admin_category_delete', {'id':category});
            e.preventDefault();
            confirmSwalAlertCategoryDelete('de vouloir supprimer cette catégorie', url, category);
        });
    }

    function categoryAddAjax() {
        $(document).on('submit','#form_category', function(e){
            e.preventDefault();
            var title = $('#category_guide_title').val();

            axios.post(Routing.generate('app_espace_admin_category_add'),  {
                title: title
            })
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#category_guide_title').val('');
                    $('#list-category').html(response.data.listHtml);
                    customDatatable('#dataTable_category');
                    scrollToSection('#list-category');
                })
                .catch(function (error) {
                    showToast('error', error);
                    $('#list-category').html(response.data.listHtml);
                    customDatatable('#dataTable_category');
                });
        });
    }

    function guideAddAjax() {
        $(document).on('submit','#form_guide', function(e){
            e.preventDefault();
            var question = $('#guide_question').val();
            var response = $('#guide_response').val();
            var category = $('#guide_category').val();

            axios.post(Routing.generate('app_espace_admin_guide_add'),  {
                question: question,
                response: response,
                category: category
            })
                .then(function (response) {
                    simpleSwalAlert(response.data.body, response.data.footer);
                    $('#guide_question').val('');
                    $('#guide_response').val('');
                    $('#list-guide').html(response.data.listHtml);
                    customDatatable('#dataTable_guide');
                    scrollToSection('#list-guide');
                })
                .catch(function (error) {
                    simpleSwalAlert(error, '');
                    $('#list-guide').html(response.data.listHtml);
                    customDatatable('#dataTable_guide');
                });
        });
    }

    function guideDelete() {
        $(document).on('click', '.delete_guide', function(e) {
            var guide = $(this).data('guide');
            var url = Routing.generate('app_espace_admin_guide_delete', {'id':guide});
            e.preventDefault();
            confirmSwalAlertGuideDelete('de vouloir supprimer cette guide', url, guide);
        });
    }

})(jQuery);