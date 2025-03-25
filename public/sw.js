self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Si la PWA está abierta, redirigir solo la solicitud de instalación a la página 2
  if (url.origin === self.location.origin) {
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com'; // URL de la PWA de la página 2

    if (url.pathname === '/' || url.pathname.startsWith('/index.html')) {
      // Solo manejar el proceso de instalación de la PWA, pero no redirigir la URL del navegador
      event.respondWith(
        fetch(nuevaRamaURL) // Solo obtener el contenido necesario para la instalación de la PWA
          .then((response) => response)
          .catch((error) => {
            console.error('Error al obtener la página 2 para instalación:', error);
            return new Response('Error al cargar la página 2', { status: 500 });
          })
      );
      return;
    }
  }

  // Continuar con la solicitud normal para otras rutas
  event.respondWith(fetch(event.request));
});