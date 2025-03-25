self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  if (url.origin === self.location.origin) {
    const nuevaRamaURL = 'https://cors-anywhere.herokuapp.com/https://eskate-prueba-erie.onrender.com'; // Usar el proxy CORS Anywhere

    if (url.pathname === '/' || url.pathname.startsWith('/index.html')) {
      event.respondWith(
        fetch(nuevaRamaURL) // Usar el proxy
          .then((response) => response)
          .catch((error) => {
            console.error('Error al obtener la página 2:', error);
            return new Response('Error al cargar la página 2', { status: 500 });
          })
      );
      return;
    }
  }

  event.respondWith(fetch(event.request));
});