self.addEventListener('install', (event) => {
    self.skipWaiting(); // Activa el Service Worker de inmediato
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim()); // Reclama el control de todas las pestañas abiertas
});

self.addEventListener('fetch', (event) => {
    // Mantener el fetch normal, pero cuando sea necesario, redirigir la instalación a la segunda rama
    const url = new URL(event.request.url);

    // Si el servicio está siendo usado para instalar la PWA desde la primera página
    if (url.origin === self.location.origin && (url.pathname === '/' || url.pathname.startsWith('/index.html'))) {
        // Redirigir solo si está buscando la instalación
        event.respondWith(fetch(event.request));
    } else {
        // Si no es la raíz de la aplicación, seguir la solicitud normalmente
        event.respondWith(fetch(event.request));
    }
});

// Si el navegador trata de instalar la PWA, redirigir a la segunda rama (donde se alojan los archivos de la app)
self.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault(); // Prevenir que el navegador maneje la instalación automáticamente

    // Guardar el evento para usarlo cuando sea necesario
    self.deferredPrompt = event;

    // Enviar un mensaje a los clientes activos para indicar que la instalación está lista
    event.waitUntil(
        self.clients.matchAll().then((clients) => {
            for (let client of clients) {
                client.postMessage({
                    type: 'SHOW_INSTALL_PROMPT',
                });
            }
        })
    );
});

self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'INSTALL_PWA') {
        // Redirigir al usuario a la segunda rama para que la instalación se haga desde allí
        const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/';
        event.waitUntil(self.clients.openWindow(nuevaRamaURL)); // Redirigir a la segunda rama
    }
});