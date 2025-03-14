self.addEventListener('install', (event) => {
    self.skipWaiting(); // Asegura que el SW se active inmediatamente.
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim()); // Toma el control de los clientes abiertos inmediatamente.
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

// Manejo del evento de instalación para redirigir a la segunda rama cuando el usuario elija instalar la PWA
self.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault(); // Prevenir la instalación predeterminada

    // Guardar el evento para dispararlo más tarde
    self.deferredPrompt = event;
});

self.addEventListener('appinstalled', (event) => {
    // Limpiar el evento de instalación cuando la PWA se instala
    self.deferredPrompt = null;
});