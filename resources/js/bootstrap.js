/* eslint global-require: 1 */
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window._ = require('lodash'); // eslint disable-line global-require

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default; // eslint disable-line global-require
    // window.$ = require('jquery'); // eslint disable-line global-require
    // window.jQuery = window.$;

    require('bootstrap'); // eslint disable-line global-require
} catch (e) {
    console.log(e);
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

const axios = require('axios');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios = axios;
window.io = require('socket.io-client');

// window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: `${window.location.hostname}:6001`,
    transports: ['websocket', 'polling', 'flashsocket']
});


export default axios;
