import {precacheAndRoute, cleanupOutdatedCaches} from 'workbox-precaching';
import {setCacheNameDetails} from 'workbox-core';


setCacheNameDetails({prefix: "app.elcoop.io"});
cleanupOutdatedCaches()


self.__precacheManifest = [
    {"revision":null,"url":"/"},
    {"revision":null,"url":"accounting"},
    {"revision":null,"url":"entities"},
    {"revision":null,"url":"debts"},
    {"revision":null,"url":"database"},
].concat(self.__WB_MANIFEST || []);
precacheAndRoute(self.__precacheManifest, {});

self.addEventListener('message', (message) => {
    console.log('message', message);
    if (message.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});
