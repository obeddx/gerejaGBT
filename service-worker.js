importScripts("https://js.pusher.com/beams/service-worker.js");

// Tambahkan event listener untuk PWA
self.addEventListener('install', (event) => {
  console.log('Service Worker installed');
  self.skipWaiting(); // Aktifkan service worker baru langsung
});

self.addEventListener('activate', (event) => {
  console.log('Service Worker activated');
  event.waitUntil(clients.claim()); // Kontrol semua clients langsung
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});