(function ($) {
    $(document).ready(function() {
        $('.box_param .item_param').on('click', function(){
            //$(this).find(".checkbox-input input[type='checkbox']").trigger('click')
            $(this).toggleClass('active')
        })
    })
})(jQuery)