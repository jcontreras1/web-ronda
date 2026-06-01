//bootstrap.js
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;
import $ from 'jquery';
import Swal from 'sweetalert2'
window.$ = $;
window.jQuery = $;
window.Swal = Swal;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;


if (bootstrap && bootstrap.Offcanvas && bootstrap.Offcanvas.prototype) {
    bootstrap.Offcanvas.prototype._initializeFocusTrap = () => ({
        activate: () => {},
        deactivate: () => {}
    });
}