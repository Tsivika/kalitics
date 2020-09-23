$(document).on('eventhandler', function () {
    startTunnel()
});

function startTunnel() {
    $('#tunnel_2').fadeIn();
    scrollToSection('#tunnel_2');
}

function scrollToSection(sectionId) {
    $([document.documentElement, document.body]).stop(true, false).animate({
        scrollTop: $(sectionId).offset().top
    }, 1000);
}

function ajaxSubmitTunnel(el) {
    let tunnel = $('#tunnel_2');
    $.ajax({
        url: $(el).attr('action'),
        type: $(el).attr('method'),
        data: $(el).serialize(),
        dataType: 'json',
        beforeSend: function () {
            scrollToSection('#tunnel_2');
            let loading = '' +
                '<div class="col-12 text-center" style="padding-top:50vh; position: fixed; top: 0; left: 0; background: rgba(12,11,15,0.28); z-index: 4; height: 100vh;">' +
                '<div class="spinner-border text-secondary" style="width: 4rem; height: 4rem;" role="status">\n' +
                '  <span class="sr-only">Loading...</span>\n' +
                '</div>' +
                '</div>';
            $('#tunnel_2').append(loading);
        },
        success: function (data) {
            scrollToSection('#tunnel_2');
            tunnel.html(data.html);
        }
    });

    return false;
}
