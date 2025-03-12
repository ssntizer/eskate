// Nombre de la caché
const CACHE_NAME = 'eskate-pwa-v1';

// URL de la nueva rama
const NEW_BRANCH_URL = 'https://nueva-rama.eskate.com';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        (async () => {
            const clientList = await self.clients.matchAll();
            const isInstalled = clientList.length > 0;
            
            if (isInstalled && event.request.mode === 'navigate') {
                return Response.redirect(NEW_BRANCH_URL, 302);
            }
            return fetch(event.request);
        })()
    );
});
