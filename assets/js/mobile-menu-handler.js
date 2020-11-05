(function ($) {
    $(document).ready(function(){
        $("#menu-primary .navbar-toggler, #menu-primary .navbar-closer, .home-navbar-toggler").on('click', function(){
            $("#menu-primary").toggleClass('open')
        })
    })
})(jQuery);
