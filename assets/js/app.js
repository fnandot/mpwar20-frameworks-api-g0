/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)

require('bootstrap');
require('@fortawesome/fontawesome-free');
require('../css/app.scss');

const $ = require('jquery');



import increaseCounter from "./increaseCounter";
import onLogSummaryIncreased from './onLogSummaryIncreased';
import onLogEntryCreated from './onLogEntryCreated';

const toastr = require('toastr');

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function () {

    $('[data-toggle="popover"]').popover();

    $('.counter .counter-value').each(function () {
        increaseCounter($(this), 0, $(this).text());
    });

});


const MERCURE_SUBSCRIBE_URL = process.env.MERCURE_SUBSCRIBE_URL;
const MERCURE_RESOURCE_ENTRYPOINT = process.env.MERCURE_RESOURCE_ENTRYPOINT;
const mercureUrl = new URL(MERCURE_SUBSCRIBE_URL);

console.log(MERCURE_SUBSCRIBE_URL);

mercureUrl
    .searchParams
    .append('topic', `${MERCURE_RESOURCE_ENTRYPOINT}/log-summaries/{id}`);

mercureUrl
    .searchParams
    .append('topic', `${MERCURE_RESOURCE_ENTRYPOINT}/log-entries/{id}`);

const eventSource = new EventSource(mercureUrl.toString(), { withCredentials: true });

// import { NativeEventSource, EventSourcePolyfill } from 'event-source-polyfill';
//
// const eventSource = new EventSourcePolyfill(mercureUrl.toString(), {
//     headers: {
//         'Authorization': 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyJodHRwOlwvXC9sb2NhbGhvc3Q6ODA4MFwvYXBpXC9yb2xlc1wvdXNlciIsImh0dHA6XC9cL2xvY2FsaG9zdDo4MDgwXC9hcGlcL3JvbGVzXC9kZXZlbG9wZXIiXX19.y8AVSVfDZwRMbZzMfF7EyoazJMslP3ZrG5QmfoZ1vDk'
//     }
// });

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
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
};

const compose = (...fns) => x => fns.reduceRight((v, f) => f(v), x);

const parseEvent = ({data}) => JSON.parse(data);

eventSource.onmessage = compose(onLogEntryCreated, onLogSummaryIncreased, parseEvent);



