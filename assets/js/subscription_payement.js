const $ = require('jquery')
import './payment_stripe'

(function ($) {
    $(document).ready(function() {
        $('.subscriptionRadioWrapper').on('click',function(){
            $('.tab-content .tab-pane').removeClass('show active')
            let $id = $(this).val()
            let $targetTab = $('.tab-content').find(`#tab-${$id}`) 
            $targetTab.addClass('show active')
            let totalPaid = $targetTab.find('.total-paid-label').text()
            $("#total-paid").val(totalPaid)
        })
    })
})(jQuery)


