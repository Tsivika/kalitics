import { confirmSwalAlertProfilDelete, confirmSwalAlertProfilChangeRole } from "./tools";
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

(function ($) {
    $(document).ready(function() {
        profilDelete();
        profilChangeRole();
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

    function profilChangeRole()
    {
        $(document).on('click', '#change-roles', function(e) {
            var role = $(this).val();
            var id_profil = $(this).data('id_profil');
            console.log(id_profil);
            var url = Routing.generate('app_espace_client_profil_change_role', {'id':id_profil, 'role':role});
            e.preventDefault();
            confirmSwalAlertProfilChangeRole('Vous êtes sur le point de changer le rôle de cet utilisateur. Souhaite vous valider ?', url);
        });
    }

})(jQuery);