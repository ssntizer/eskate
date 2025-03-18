self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const segundaRamaURL = 'https://eskate-prueba-erie.onrender.com/';
    
    event.respondWith(
        (async () => {
            const clientList = await self.clients.matchAll();
            const isPWA = clientList.length > 0; // Si hay clientes, es una PWA
            
            if (isPWA && event.request.mode === 'navigate') {
                return Response.redirect(segundaRamaURL);
            }
            
            return fetch(event.request);
        })()
    );
});