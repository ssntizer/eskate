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
            const isPWA = (await self.clients.matchAll()).some(client => client.visibilityState === 'visible');
            
            // Si está en modo PWA y la URL es la raíz o index, redirigir a la otra rama
            if (isPWA && (url.pathname === '/' || url.pathname.startsWith('/index.html'))) {
                const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/'; // URL de la nueva rama
                return Response.redirect(nuevaRamaURL);
            }

            // Si no es una PWA o no es la página raíz, proceder normalmente
            return fetch(event.request);
        })()
    );
});