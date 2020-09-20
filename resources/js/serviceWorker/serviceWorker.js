import { precacheAndRoute } from 'workbox-precaching';

precacheAndRoute(self.__WB_MANIFEST);

precacheAndRouteworkbox.core.setCacheNameDetails({prefix: "app.elcoop.io"});

self.__precacheManifest = [].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});

self.addEventListener('message', (message) => {
    if (message.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});
