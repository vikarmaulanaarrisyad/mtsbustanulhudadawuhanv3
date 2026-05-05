// Service Worker - Madrasah Digital
// Cache version diambil dari server (akan berubah saat admin update setting)
const CACHE_NAME = 'madrasah-v__SW_VERSION__';
const urlsToCache = [
  '/',
  '/login',
  '/manifest.json',
];

// ─── INSTALL ──────────────────────────────────────────────────────────────────
self.addEventListener('install', event => {
  // Langsung aktif tanpa menunggu tab lama ditutup
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    }).catch(() => {
      // Lanjutkan meski ada resource yang gagal di-cache
    })
  );
});

// ─── ACTIVATE ─────────────────────────────────────────────────────────────────
self.addEventListener('activate', event => {
  // Ambil kontrol semua tab langsung setelah aktivasi
  event.waitUntil(
    Promise.all([
      self.clients.claim(),
      // Hapus cache lama
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames
            .filter(name => name !== CACHE_NAME)
            .map(name => caches.delete(name))
        );
      })
    ]).then(() => {
      // Kirim pesan ke semua tab yang terbuka bahwa ada update
      self.clients.matchAll({ type: 'window' }).then(clients => {
        clients.forEach(client => {
          client.postMessage({ type: 'SW_UPDATED' });
        });
      });
    })
  );
});

// ─── FETCH ────────────────────────────────────────────────────────────────────
self.addEventListener('fetch', event => {
  // Lewati request non-GET dan request admin
  if (event.request.method !== 'GET') return;
  
  const url = new URL(event.request.url);
  
  // Skip caching untuk halaman autentikasi dan API
  if (url.pathname.startsWith('/admin') || 
      url.pathname.startsWith('/setting') ||
      url.pathname.startsWith('/manifest.json')) {
    return;
  }

  event.respondWith(
    caches.match(event.request).then(cached => {
      if (cached) {
        // Kembalikan cache, tapi tetap fetch update di background
        fetch(event.request).then(response => {
          if (response && response.ok && response.type === 'basic') {
            const clone = response.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
          }
        }).catch(() => {});
        return cached;
      }
      return fetch(event.request);
    }).catch(() => {
      // Fallback jika offline dan tidak ada cache
      return caches.match('/');
    })
  );
});

// ─── MESSAGE ──────────────────────────────────────────────────────────────────
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
