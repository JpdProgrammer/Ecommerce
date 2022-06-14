require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import '@fortawesome/fontawesome-free/js/fontawesome';
import '@fortawesome/fontawesome-free/js/solid';
import '@fortawesome/fontawesome-free/js/regular';
import '@fortawesome/fontawesome-free/js/brands';
import 'glider-js/glider.min.js';
import swal from 'sweetalert2';
window.Swal = swal;
var flatpickr = require("flatpickr");
require("flatpickr/dist/flatpickr.min.css");

