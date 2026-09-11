// Load Lodash globally
window._ = require('lodash');

/**
 * -----------------------
 * Axios HTTP Configuration
 * -----------------------
 * Axios handles all HTTP requests to your Laravel backend.
 * It automatically sends the CSRF token header using the value
 * from the <meta name="csrf-token"> tag in your Blade templates.
 */
window.axios = require('axios');

// Mark requests as AJAX
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Attach CSRF token from meta tag to all Axios requests
const csrfTokenMeta = document.head.querySelector('meta[name="csrf-token"]');
if (csrfTokenMeta) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfTokenMeta.content;
} else {
    console.error(
        'CSRF token not found: Add <meta name="csrf-token" content="{{ csrf_token() }}"> to your <head>.'
    );
}

/**
 * -----------------------
 * Laravel Echo & Pusher
 * -----------------------
 * Echo exposes an expressive API for subscribing to channels and
 * listening for events broadcast by Laravel.
 */
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// // Configure Echo with your Pusher keys (from .env)
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     forceTLS: true,
//     // Optional: Enable debug logging in development
//     // logToConsole: import.meta.env.DEV,
// });

/**
 * -----------------------
 * Example Usage:
 * -----------------------
 * // window.Echo.channel('community-chat')
 * //     .listen('MessageSent', (e) => {
 * //         console.log('Received message:', e.message);
 * //     });
 *
 * axios.post('/community-chat/send', { content: 'Hello world!' })
 *     .then(res => console.log('Message sent:', res.data))
 *     .catch(err => console.error(err));
 */
