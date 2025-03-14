self.addEventListener('install', (event) => {
    self.skipWaiting();  // Fuerza la activación inmediata del service worker
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());  // Asegura que el service worker controle las pestañas abiertas
});

self.addEventListener('fetch', (event) => {
    // No cambiamos nada en el comportamiento de fetch, solo se gestionan las solicitudes de la página principal
    event.respondWith(fetch(event.request));
});

// Manejo de la instalación de la PWA
self.addEventListener('beforeinstallprompt', (event) => {
    // Prevenimos que el evento de instalación se ejecute automáticamente
    event.preventDefault();
    
    // Aquí es donde redirigimos a la segunda rama cuando el usuario decide instalar la PWA
    event.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
            // En lugar de cambiar de página, realizamos la instalación directamente desde la segunda rama
            const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/';  // URL de la segunda rama
            // Instalamos la app usando la segunda rama sin redirigir al usuario
            window.location.href = nuevaRamaURL;
        }
    });
});