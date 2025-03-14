self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// Interceptar solicitudes y redirigir la PWA instalada a la segunda rama
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/';

    // Verifica si es una navegación dentro de la PWA instalada
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            // Si es la pantalla de inicio de la PWA, forzar la redirección
            if (url.origin === self.location.origin && (url.pathname === '/' || url.pathname.startsWith('/index.html'))) {
                return Response.redirect(nuevaRamaURL);
            }

            return fetch(event.request);
        })
    );
});