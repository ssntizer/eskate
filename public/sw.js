self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// Interceptamos la solicitud de instalación para redirigir a la segunda rama
self.addEventListener('beforeinstallprompt', (event) => {
    // Prevenir que el evento de instalación se ejecute de forma predeterminada
    event.preventDefault();

    // Guardamos el evento para poder instalarla más tarde
    self.deferredPrompt = event;

    // Aquí puedes mostrar el botón de instalación en la interfaz de usuario
    // Cuando el usuario toque el botón para instalar, usamos la segunda rama
    // (esto es un ejemplo, lo puedes ajustar a tus necesidades)
    self.clients.matchAll().then((clients) => {
        for (let client of clients) {
            client.postMessage({
                type: 'SHOW_INSTALL_BUTTON',
                message: 'Puedes instalar la app',
            });
        }
    });
});

// Esperamos a que el usuario acepte la instalación
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'INSTALL_PWA') {
        // Redirigir a la segunda rama para la instalación
        const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/';
        window.location.href = nuevaRamaURL; // Redirigimos a la segunda rama para instalar la PWA
    }
});

// Manejo del fetch, no hay cambios aquí
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    
    // Realizar el fetch normalmente
    event.respondWith(fetch(event.request));
});