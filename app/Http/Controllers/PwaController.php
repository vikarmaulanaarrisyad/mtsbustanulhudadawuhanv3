<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PwaController extends Controller
{
    /**
     * Serve the service worker JS with dynamic version from DB.
     * Must be served without auth middleware.
     */
    public function serviceWorker()
    {
        $setting = Setting::first();
        $version = $setting->pwa_version ?? '1.0.0';

        $swContent = <<<JS
// Service Worker - Madrasah Digital v{$version}
const CACHE_NAME = 'madrasah-v{$version}';
const urlsToCache = ['/', '/login'];

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(urlsToCache);
        }).catch(() => {})
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        Promise.all([
            self.clients.claim(),
            caches.keys().then(cacheNames => {
                return Promise.all(
                    cacheNames
                        .filter(name => name !== CACHE_NAME)
                        .map(name => caches.delete(name))
                );
            })
        ]).then(() => {
            self.clients.matchAll({ type: 'window' }).then(clients => {
                clients.forEach(client => {
                    client.postMessage({ type: 'SW_UPDATED', version: '{$version}' });
                });
            });
        })
    );
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') return;
    const url = new URL(event.request.url);
    
    // Jangan ganggu halaman admin/setting
    if (url.pathname.startsWith('/setting') || url.pathname.startsWith('/admin')) return;

    // 1. Strategi Network-First untuk Navigasi (HTML), Manifest, dan Gambar
    // Ini memastikan halaman & branding selalu yang paling baru
    if (event.request.mode === 'navigate' || url.pathname.endsWith('manifest.json') || event.request.destination === 'image') {
        event.respondWith(
            fetch(event.request).then(response => {
                if (response && response.ok) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
                }
                return response;
            }).catch(() => caches.match(event.request) || caches.match('/'))
        );
        return;
    }

    // 2. Strategi Stale-While-Revalidate untuk aset statis (CSS, JS, Fonts)
    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            const fetchPromise = fetch(event.request).then(networkResponse => {
                if (networkResponse && networkResponse.ok) {
                    const cacheClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, cacheClone));
                }
                return networkResponse;
            }).catch(() => {});
            return cachedResponse || fetchPromise;
        })
    );
});

self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
JS;

        return response($swContent, 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Upload PWA icon and update all required icon sizes.
     */
    public function uploadIcon(Request $request)
    {
        $request->validate([
            'pwa_icon' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $setting = Setting::first();

        // Ensure directory exists
        $iconDir = public_path('icons');
        if (!file_exists($iconDir)) {
            mkdir($iconDir, 0755, true);
        }

        $file = $request->file('pwa_icon');

        // Save original
        $originalPath = 'pwa_icon_' . time() . '.png';

        // Resize & save all sizes using GD (built-in PHP)
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        $sizes = [
            'icon-192x192.png'          => 192,
            'icon-512x512.png'          => 512,
            'icon-192x192-maskable.png' => 192,
        ];

        foreach ($sizes as $filename => $size) {
            $resized = imagecreatetruecolor($size, $size);
            // Support transparency
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefill($resized, 0, 0, $transparent);

            $srcW = imagesx($image);
            $srcH = imagesy($image);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $size, $size, $srcW, $srcH);
            imagepng($resized, $iconDir . '/' . $filename, 9);
            imagedestroy($resized);
        }
        imagedestroy($image);

        // Store reference path in settings and increment version to trigger SW update
        $currentVersion = $setting->pwa_version ?? '1.0.0';
        $parts = explode('.', $currentVersion);
        $parts[2] = isset($parts[2]) ? (int)$parts[2] + 1 : 1;
        $newVersion = implode('.', $parts);

        $setting->update([
            'pwa_icon' => '/icons/icon-192x192.png?v=' . time(),
            'pwa_version' => $newVersion
        ]);

        return back()->with([
            'message' => 'Ikon PWA berhasil diperbarui ke versi ' . $newVersion . '! Semua ukuran ikon telah diregenerasi.',
            'success' => true,
        ]);
    }
}
