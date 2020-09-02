import { confirmSwalAlertProfilDelete } from "./tools";
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        profilDelete();
    });

    function profilDelete()
    {
        $(document).on('click', '.delete_profil', function(e) {
            var id = $(this).data('profil');
            var url = Routing.generate('app_espace_client_profil_delete', {'id':id});
            e.preventDefault();
            confirmSwalAlertProfilDelete('Vous êtes sur le point de supprimer votre compte Hiboo. Cette action est' +
                'définitive et irrevocable. Souhaite vous supprimer votre compte Hiboo ?', url);
        });
    }

})(jQuery);