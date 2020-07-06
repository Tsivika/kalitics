import toastr from './toastr.min';

    function Toast(type, css, msg) {
        this.type = type; // info - warning - success - error
        this.css = css;
        this.msg =  msg ;
    }

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    export default function showToast(type, msg) {
        var t = new Toast(type, 'toast-bottom-right', msg);
        toastr.options.positionClass = t.css;
        toastr[t.type](t.msg);
    }
