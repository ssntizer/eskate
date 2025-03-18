self.addEventListener('install', (event) => {
    self.skipWaiting(); // Forzar la instalación del service worker
  });
  
  self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim()); // Activar el SW inmediatamente
  });
  
  self.addEventListener('fetch', (event) => {
    // Aquí no necesitamos hacer nada, solo aseguramos que la app esté lista para ser instalada
  });