import { register } from 'register-service-worker'
import toast from 'izitoast';

if (process.env.NODE_ENV === 'production') {
    register('/serviceWorker.js', {
        ready(registration) {
            console.log('Service worker is active.');
        },
        registered(registration) {
            console.log('Service worker has been registered.');
        },
        cached(registration) {
            console.log('Content has been cached for offline use.');
        },
        updatefound(registration) {
            console.log('New content is downloading.');
        },
        updated(registration) {
            console.log('New content is available; please refresh.');
            toast.question({
                title: "Update available for the app.",
                message: '',
                timeout: 10000,
                icon: '',
                position: 'bottomCenter',
                buttons: [
                    ['<button>Update</button>', () => {
                        registration.waiting.postMessage({action: 'skipWaiting'});
                    }],
                ]
            });
        },
        offline() {
            console.log('No internet connection found. App is running in offline mode.');
        },
        error(error) {
            console.error('Error during service worker registration:', error);
        }
    });

    let refreshing;
    navigator.serviceWorker.addEventListener("controllerchange", () => {
        if (refreshing) return;
        window.location.reload();
        refreshing = true;
    });
}
