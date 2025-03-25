self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Si la PWA está abierta, redirigir la solicitud a la URL de la página 2
  if (url.origin === self.location.origin) {
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com/'; // URL de la nueva rama

    // Solo redirigir si la solicitud es para la página principal
    if (url.pathname === '/' || url.pathname.startsWith('/index.html')) {
      // Cambia esto para redirigir las solicitudes de manera que no interrumpa la experiencia del usuario
      event.respondWith(
        fetch(nuevaRamaURL) // Redirige la solicitud sin cambiar la URL del navegador
          .then((response) => {
            return response; // Responde con el contenido de la nueva página
          })
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