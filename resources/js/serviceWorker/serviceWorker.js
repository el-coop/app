import {precacheAndRoute, cleanupOutdatedCaches} from 'workbox-precaching';
import {setCacheNameDetails} from 'workbox-core';

setCacheNameDetails({prefix: "app.elcoop.io"});
cleanupOutdatedCaches()

precacheAndRoute(self.__WB_MANIFEST);

self.addEventListener('message', (message) => {
    console.log('message', message);
    if (message.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});
