import './bootstrap';
import $ from "jquery"
import toastr from 'toastr';
import 'select2';
import 'datatables.net';

toastr.options = {
    positionClass: 'toast-top-right',
    timeOut: 3000,
    extendedTimeOut: 1000,
    closeButton: false,
    progressBar: true,
    preventDuplicates: true
};

export { toastr, $ };
