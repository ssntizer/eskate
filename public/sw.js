self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Permitir que se navegue normalmente por la primera rama
    if (url.origin === self.location.origin) {
        event.respondWith(fetch(event.request));
    } else {
        // Si la URL no es de la rama principal, hacer el fetch normalmente
        event.respondWith(fetch(event.request));
    }
});

// Capturar el evento de instalación para redirigirlo a la segunda rama
self.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    
    event.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
            // Redirigir a la segunda rama solo cuando el usuario acepte la instalación
            const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/'; // Segunda rama
            window.location.href = nuevaRamaURL;
        }
    });
});