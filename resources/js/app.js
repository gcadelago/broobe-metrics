import './bootstrap';
import toastr from 'toastr';
import 'select2';
import Chart from 'chart.js/auto';
toastr.options = {
    positionClass: 'toast-top-right',
    timeOut: 3000,
    extendedTimeOut: 1000,
    closeButton: false,
    progressBar: true,
    preventDuplicates: true
};

export { toastr, Chart };
