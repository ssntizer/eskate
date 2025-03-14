self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Solo redirigir a la segunda rama cuando el servicio esté en modo standalone (es decir, cuando sea PWA).
    if (url.origin === self.location.origin && (url.pathname === '/' || url.pathname.startsWith('/index.html'))) {
        // No redirigir la página cuando no sea la PWA
        event.respondWith(fetch(event.request));
    } else {
        // Realizar el fetch normalmente si no es la página principal
        event.respondWith(fetch(event.request));
    }
});