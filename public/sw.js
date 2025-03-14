self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com';

    // Si la instalación está ocurriendo, redirigir a la nueva rama
    if (url.origin === self.location.origin && (url.pathname === '/' || url.pathname.startsWith('/index.html'))) {
        event.respondWith(Response.redirect(nuevaRamaURL));
        return;
    }

    // Seguir con la solicitud normal
    event.respondWith(fetch(event.request));
});