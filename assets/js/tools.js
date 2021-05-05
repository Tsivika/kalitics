import Swal from '../plugins/sweetalert/js/sweetalert2.min';
import '../plugins/sweetalert/css/sweetalert2.min.css';

export function simpleSwalAlert(title, message, footer) {
    Swal.fire({
        title: title,
        html: message,
        confirmButtonText: 'Ok',
        confirmButtonColor: '#FF5A5F',
        footer: footer
    });
}