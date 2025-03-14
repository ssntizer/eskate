self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/'; // URL de la segunda rama

    // Verifica si el botón especial fue usado (ejemplo: "?openApp" en la URL)
    if (url.searchParams.has('openApp')) {
        event.respondWith(Response.redirect(nuevaRamaURL));
        return;
    }

    // Si la PWA está abierta en modo standalone, redirigir a la nueva rama
    event.respondWith(
        clients.matchAll().then((clients) => {
            const isPWA = clients.some(client => client.visibilityState === 'visible' && client.displayMode === 'standalone');

            if (isPWA && url.origin === self.location.origin) {
                return Response.redirect(nuevaRamaURL);
            }

            return fetch(event.request);
        })
    );
});