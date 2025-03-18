self.addEventListener('install', (event) => {
    self.skipWaiting(); // Forzar la instalación del service worker
  });
  
  self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim()); // Activar el SW inmediatamente
  });
  
  self.addEventListener('fetch', (event) => {
    // Aquí no necesitamos hacer nada específico, ya que no estamos manipulando la instalación directamente desde este SW
  });