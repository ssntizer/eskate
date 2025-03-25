self.addEventListener('install', (event) => {
  self.skipWaiting();  // Asegura que el Service Worker se active inmediatamente
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());  // Controlar todas las páginas de esta aplicación
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Si la solicitud es para la página principal, descargar el contenido de la página 2
  if (url.origin === self.location.origin) {
    const nuevaRamaURL = 'https://eskate-prueba-erie.onrender.com';  // PWA de la página 2

    // Asegurarse de que solo estamos interceptando las solicitudes a la página principal
    if (url.pathname === '/' || url.pathname.startsWith('/index.html')) {
      event.respondWith(
        fetch(nuevaRamaURL)  // Descargar la PWA de la página 2
          .then((response) => response)
          .catch((error) => {
            console.error('Error al obtener la página 2:', error);
            return new Response('Error al cargar la PWA', { status: 500 });
          })
      );
      return;
    }
  }

  // Continuar con la solicitud normal para otras rutas
  event.respondWith(fetch(event.request));
});

// Escuchar el mensaje del frontend de la página 1 para iniciar la instalación
self.addEventListener('message', (event) => {
  if (event.data.action === 'install') {
    // Aquí iniciamos la instalación de la PWA de la página 2
    console.log('Iniciando instalación de la PWA desde la página 2...');
  }
});