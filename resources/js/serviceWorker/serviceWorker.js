import { precacheAndRoute } from 'workbox-precaching';
import {setCacheNameDetails} from 'workbox-core';

setCacheNameDetails({prefix: "app.elcoop.io"});
precacheAndRoute(self.__WB_MANIFEST);

self.addEventListener('message', (message) => {
    if (message.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});
