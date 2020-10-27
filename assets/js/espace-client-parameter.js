(function ($) {
    $(document).ready(function() {
        $('.box_param .item_param').on('click', function(){
            $checkBox = $(this).find(".checkbox-input input[type='checkbox']")
            $checkBox.attr("checked", !$checkBox.attr("checked"))
            $(this).toggleClass('active')
            
        })
    })
})(jQuery)