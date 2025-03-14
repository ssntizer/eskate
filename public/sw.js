self.addEventListener('install', (event) => {
    self.skipWaiting(); // Se activa el service worker de inmediato
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim()); // Reclama el control de todas las pestañas abiertas
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com'; // Cambia esto por la URL de la nueva rama

    // Si la PWA está abierta desde la raíz o index.html, redirigir a la nueva rama
    if (url.origin === self.location.origin) {
        if (url.pathname === '/' || url.pathname.startsWith('/index.html')) {
            event.respondWith(Response.redirect(nuevaRamaURL));
            return;
        }
    }

    // Permitir que otras solicitudes se procesen normalmente
    event.respondWith(fetch(event.request));
});