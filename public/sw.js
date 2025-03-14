self.addEventListener('install', (event) => {
    // Saltarse la espera y activar el SW inmediatamente
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    // Reclamar el control de la página abierta
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Verificar si el cliente está en modo "standalone" (PWA instalada)
    event.respondWith(
        (async () => {
            const isStandalone = (await self.clients.matchAll()).some(client => client.visibilityState === 'visible');

            // Si la PWA está instalada y la URL es la raíz o index, redirigir a la segunda rama
            if (isStandalone && (url.pathname === '/' || url.pathname.startsWith('/index.html'))) {
                const segundaRamaURL = 'https://eskate-prueba-erie.onrender.com/';  // URL de la segunda rama
                return Response.redirect(segundaRamaURL);
            }

            // Si no está en modo PWA o la solicitud no es la raíz, proceder normalmente
            return fetch(event.request);
        })()
    );
});