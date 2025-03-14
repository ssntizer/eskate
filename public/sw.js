self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Verificar si la aplicación está en modo standalone (PWA)
    event.respondWith(
        (async () => {
            // Si la PWA está instalada y la URL es la raíz o index, redirigir a la segunda rama
            const isStandalone = (await self.clients.matchAll()).some(client => client.visibilityState === 'visible');
            
            if (isStandalone && (url.pathname === '/' || url.pathname.startsWith('/index.html'))) {
                const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/'; // URL de la segunda rama
                return Response.redirect(nuevaRamaURL);
            }

            // Si no está en modo PWA o la solicitud no es la raíz, proceder normalmente
            return fetch(event.request);
        })()
    );
});