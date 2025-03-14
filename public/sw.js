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

    // Si la URL es la del dominio actual, redirigir a la segunda rama
    if (url.origin === self.location.origin) {
        const segundaRamaURL = 'https://eskate-prueba-erie.onrender.com/';  // URL de la segunda rama

        // Solo redirigir cuando la solicitud sea hacia la raíz o index
        if (url.pathname === '/' || url.pathname.startsWith('/index.html')) {
            event.respondWith(Response.redirect(segundaRamaURL));
            return;
        }
    }

    // Continuar con la solicitud normal
    event.respondWith(fetch(event.request));
});