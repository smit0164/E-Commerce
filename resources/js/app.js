import './bootstrap';
import '../css/app.css';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right', // Default position
    timeOut:2000, // 5 seconds
};
window.toastr = toastr;