self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Si la PWA está abierta, redirigir la solicitud para obtener el contenido de la página 2, sin cambiar la URL visible
  if (url.origin === self.location.origin) {
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/'; // URL de la nueva rama

    // Solo redirigir la solicitud si es para la página principal
    if (url.pathname === '/' || url.pathname.startsWith('/index.html')) {
      // Fetch el contenido de la página 2, pero no cambiar la URL del navegador
      event.respondWith(
        fetch(nuevaRamaURL) // Obtener el contenido de la página 2
          .then((response) => response) // Devuelve el contenido de la página 2
          .catch((error) => {
            console.error('Error al obtener la página 2:', error);
            return new Response('Error al cargar la página 2', { status: 500 });
          })
      );
      return;
    }
  }

  // Continuar con la solicitud normal para otras rutas
  event.respondWith(fetch(event.request));
});