self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const segundaRamaURL = 'https://eskate-prueba-erie.onrender.com/';

    // Si es una instalación, forzar a la segunda URL
    if (event.request.mode === 'navigate') {
        event.respondWith(Response.redirect(segundaRamaURL));
    } else {
        event.respondWith(fetch(event.request));
    }
});