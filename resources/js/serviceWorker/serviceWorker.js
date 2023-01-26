import {precacheAndRoute, cleanupOutdatedCaches} from 'workbox-precaching';
import {setCacheNameDetails} from 'workbox-core';


setCacheNameDetails({prefix: "app.elcoop.io"});
cleanupOutdatedCaches()


self.__precacheManifest = [
    {"revision":null,"url":"/"},
].concat(self.__WB_MANIFEST || []);
precacheAndRoute(self.__precacheManifest, {});

self.addEventListener('message', (message) => {
    console.log('message', message);
    if (message.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});
