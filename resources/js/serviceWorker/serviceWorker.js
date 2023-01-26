import {precacheAndRoute, cleanupOutdatedCaches} from 'workbox-precaching';
import {setCacheNameDetails} from 'workbox-core';
import { CacheFirst  } from 'workbox-strategies';
import { registerRoute } from 'workbox-routing';


setCacheNameDetails({prefix: "app.elcoop.io"});
cleanupOutdatedCaches()

precacheAndRoute(self.__WB_MANIFEST);

registerRoute(
    new RegExp('/images/.*'),
    new CacheFirst({
        cacheName: 'image-cache',
    })
);


registerRoute(
    new RegExp('/assets/.*\.otf'),
    new CacheFirst({
        cacheName: 'font-cache',
    })
);


self.addEventListener('message', (message) => {
    console.log('message', message);
    if (message.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});
