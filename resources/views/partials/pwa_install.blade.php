{{-- ════════════════════════════════════════════ --}}
{{--  OVERLAY LOCK (Wajib Instal untuk Non-Admin)  --}}
{{-- ════════════════════════════════════════════ --}}
@php
    $user = auth()->user();
    $isAdmin = $user && $user->hasRole(['Admin', 'Super Admin']);
    // Selain Admin/Super Admin, semua user (Guru, Siswa, PPDB) wajib install PWA
    $isRestricted = auth()->check() && !$isAdmin;
@endphp

@if($isRestricted)
<div id="pwa-force-overlay" class="pwa-force-overlay" style="display:none;">
    <div class="pwa-force-card">
        <img src="{{ $setting->pwa_icon ?? '/storage/pwa/icons/icon-192x192.png' }}?v={{ $setting->pwa_version ?? time() }}" alt="Logo" class="pwa-force-logo">
        <h1 class="pwa-force-title">Aktivasi Aplikasi Digital</h1>
        <p class="pwa-force-desc">Silakan pasang aplikasi Smart Madrasah di HP Anda untuk akses yang lebih cepat, aman, dan fitur lengkap.</p>
        
        <div class="pwa-device-guide android-only">
            <div class="guide-item"><i class="fas fa-check-circle"></i> Siap dipasang ke layar utama.</div>
        </div>
        <div class="pwa-device-guide ios-only">
            <div class="guide-item"><i class="fas fa-share-square"></i> Klik <b>Share</b> di Safari, lalu <b>"Add to Home Screen"</b>.</div>
        </div>

        <button id="pwa-force-install-btn" class="pwa-btn-main pwa-btn-install pwa-btn-force">
            <span id="pwa-btn-text"><i class="fas fa-cloud-download-alt mr-2"></i> PASANG SEKARANG</span>
        </button>
        <a href="javascript:void(0)" onclick="window.location.reload()" class="pwa-refresh-link">Sudah pasang? Klik Segarkan</a>
    </div>
</div>
@endif

<!-- Normal Install Banner (Hanya untuk tamu/guest yang belum login) -->
@if(!auth()->check())
<div id="pwa-install-prompt" class="pwa-banner" style="display:none;">
    <div class="pwa-banner-icon">
        <img src="{{ $setting->pwa_icon ?? '/storage/pwa/icons/icon-192x192.png' }}?v={{ $setting->pwa_version ?? time() }}" alt="Logo">
    </div>
    <div class="pwa-banner-text">
        <h6>Calon Siswa Baru?</h6>
        <p>Pasang aplikasi untuk pendaftaran & pantau status PPDB lebih mudah</p>
    </div>
    <div class="pwa-actions">
        <button id="pwa-install-btn" class="pwa-btn-main pwa-btn-install">
            <i class="fas fa-download"></i> PASANG
        </button>
        <button id="pwa-close-install" class="pwa-btn-close">&times;</button>
    </div>
</div>
@endif

<!-- Update Banner (Tidak muncul untuk Admin) -->
@if(!$isAdmin)
<div id="pwa-update-prompt" class="pwa-banner" style="display:none;">
    <div class="pwa-banner-icon">
        <img src="{{ $setting->pwa_icon ?? '/storage/pwa/icons/icon-192x192.png' }}?v={{ $setting->pwa_version ?? time() }}" alt="Logo">
    </div>
    <div class="pwa-banner-text">
        <h6>Versi Baru Tersedia</h6>
        <p>Pembaruan fitur & keamanan</p>
    </div>
    <div class="pwa-actions">
        <button id="pwa-update-btn" class="pwa-btn-main pwa-btn-update">
            <i class="fas fa-sync-alt"></i> UPDATE
        </button>
        <button id="pwa-close-update" class="pwa-btn-close">&times;</button>
    </div>
</div>
@endif

<!-- Updating Overlay -->
<div id="id-updating-overlay" class="pwa-updating-overlay" style="display:none;">
    <div class="pwa-updating-card">
        <div class="pwa-updating-logo">
            <img src="{{ $setting->pwa_icon ?? '/storage/pwa/icons/icon-192x192.png' }}?v={{ $setting->pwa_version ?? time() }}" alt="Logo">
            <div class="pwa-spinner-ring"></div>
        </div>
        <h4>Memperbarui Aplikasi...</h4>
        <p>Sedang mengunduh aset terbaru, mohon tunggu sebentar.</p>
        <div class="pwa-progress-container">
            <div id="pwa-progress-bar" class="pwa-progress-bar"></div>
        </div>
    </div>
</div>

<style>
/* Premium PWA Design System */
:root {
    --pwa-emerald: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --pwa-indigo: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    --pwa-slate: #1e293b;
    --pwa-glass: rgba(255, 255, 255, 0.88);
    --pwa-blur: blur(25px);
    --pwa-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.3);
}

/* Base Utility */
.android-only, .ios-only { display: none; }
body.pwa-android .android-only { display: block; }
body.pwa-ios .ios-only { display: block; }

/* Banner Styling */
.pwa-banner {
    position: fixed; bottom: 32px; left: 16px; right: 16px; max-width: 500px;
    margin: 0 auto; background: var(--pwa-glass); backdrop-filter: var(--pwa-blur);
    -webkit-backdrop-filter: var(--pwa-blur); border-radius: 30px; z-index: 999999;
    box-shadow: var(--pwa-shadow); padding: 18px 24px;
    display: flex; align-items: center; gap: 16px; border: 1px solid rgba(255, 255, 255, 0.5);
    animation: pwaBounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
}

@keyframes pwaBounceIn { 
    0% { transform: translateY(150%) scale(0.8); opacity: 0; } 
    100% { transform: translateY(0) scale(1); opacity: 1; } 
}

.pwa-banner-icon { 
    width: 64px; height: 64px; border-radius: 20px; overflow: hidden; flex-shrink: 0; 
    box-shadow: 0 10px 20px rgba(0,0,0,0.12); border: 2.5px solid #fff;
    background: #fff; display: flex; align-items: center; justify-content: center;
}
.pwa-banner-icon img { width: 100%; height: 100%; object-fit: cover; }

.pwa-banner-text { flex: 1; min-width: 0; }
.pwa-banner-text h6 { 
    margin: 0; font-weight: 800; font-size: 1.1rem; color: var(--pwa-slate);
    letter-spacing: -0.02em; line-height: 1.2;
}
.pwa-banner-text p { 
    margin: 4px 0 0; font-size: 0.85rem; color: #64748b; font-weight: 500;
}

.pwa-actions { display: flex; align-items: center; gap: 10px; }

.pwa-btn-main {
    padding: 12px 22px; border-radius: 16px; border: none;
    color: #fff; font-weight: 750; font-size: 0.9rem; cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    white-space: nowrap; display: flex; align-items: center; gap: 8px;
}
.pwa-btn-install { background: var(--pwa-emerald); }
.pwa-btn-update { background: var(--pwa-indigo); }
.pwa-btn-main:active { transform: scale(0.92); filter: brightness(0.9); }

.pwa-btn-close {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,0.06); border-radius: 50%; color: #64748b;
    border: none; cursor: pointer; font-size: 1.4rem; transition: 0.2s;
}

/* Premium Overlays */
.pwa-force-overlay, .pwa-updating-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(circle at top right, #1e293b, #0f172a);
    z-index: 9999999; display: flex; align-items: center; justify-content: center; padding: 25px;
    overflow: hidden;
}
/* Lock body scroll when overlay is active */
body:has(.pwa-force-overlay[style*="display: flex"]) {
    overflow: hidden !important;
    height: 100vh !important;
}

.pwa-force-card, .pwa-updating-card {
    background: #fff; border-radius: 35px; width: 100%; max-width: 400px;
    padding: 45px 30px; text-align: center; box-shadow: 0 30px 70px rgba(0,0,0,0.4);
    animation: pwaFadeScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
}
@keyframes pwaFadeScale { 0% { opacity: 0; transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }

.pwa-force-logo, .pwa-updating-logo { 
    width: 80px; height: 80px; border-radius: 20px; 
    margin: 0 auto 35px; border: 3px solid #f1f5f9;
    position: relative; background: #fff;
}
.pwa-force-logo img, .pwa-updating-logo img { width: 100%; height: 100%; object-fit: contain; padding: 5px; }

.pwa-force-title { font-size: 1.5rem; font-weight: 850; color: var(--pwa-slate); margin-bottom: 12px; }
.pwa-force-desc { font-size: 0.95rem; color: #64748b; margin-bottom: 35px; line-height: 1.6; }
.pwa-device-guide { background: #f8fafc; border-radius: 20px; padding: 15px; margin-bottom: 30px; }
.guide-item { font-size: 0.85rem; color: #475569; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; }

.pwa-btn-force { width: 100%; font-size: 1.1rem; padding: 18px; border-radius: 20px; justify-content: center; }
.pwa-refresh-link { display: block; margin-top: 20px; font-size: 0.85rem; color: #94a3b8; text-decoration: underline; font-weight: 600; }

/* Progress Styling */
.pwa-spinner-ring {
    position: absolute; top: -10px; left: -10px; right: -10px; bottom: -10px;
    border: 4px solid #f1f5f9; border-top-color: #6366f1; border-radius: 50%;
    animation: pwaSpin 1.2s linear infinite;
}
@keyframes pwaSpin { to { transform: rotate(360deg); } }
.pwa-progress-container { width: 100%; height: 8px; background: #f1f5f9; border-radius: 20px; margin-top: 25px; overflow: hidden; }
.pwa-progress-bar { width: 0%; height: 100%; background: var(--pwa-indigo); transition: width 3s ease; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4); }
</style>

<script>
(function () {
    let deferredPrompt = null;
    const restricted = {{ $isRestricted ? 'true' : 'false' }};
    const forceOverlay = document.getElementById('pwa-force-overlay');
    const installBanner = document.getElementById('pwa-install-prompt');
    const updateBanner = document.getElementById('pwa-update-prompt');
    const updatingOverlay = document.getElementById('id-updating-overlay');
    const progressBar = document.getElementById('pwa-progress-bar');
    const btnText = document.getElementById('pwa-btn-text');

    const ua = navigator.userAgent.toLowerCase();
    const isAndroid = ua.indexOf("android") > -1;
    const isIos = /ipad|iphone|ipod/.test(ua) && !window.MSStream;
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone || document.referrer.includes('android-app://');

    if (isAndroid) document.body.classList.add('pwa-android');
    else if (isIos) document.body.classList.add('pwa-ios');

    if (restricted && !isStandalone && forceOverlay) {
        forceOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        document.body.style.height = '100vh';
    }

    if ('serviceWorker' in navigator) {
        const swUrl = '/sw.js?v={{ $setting->pwa_version ?? time() }}';
        navigator.serviceWorker.register(swUrl).then(reg => {
            // Cek update segera saat aplikasi dibuka
            reg.update();

            // Background Check: Cek update setiap 60 detik
            setInterval(() => {
                reg.update().catch(() => {});
            }, 60000);

            // Jika sudah ada yang menunggu (waiting), langsung munculkan banner
            if (reg.waiting) showUpdateBanner();

            reg.addEventListener('updatefound', () => {
                const newSW = reg.installing;
                newSW.addEventListener('statechange', () => {
                    // Tampilkan banner hanya jika versi baru sudah terdownload sepenuhnya
                    if (newSW.state === 'installed' && navigator.serviceWorker.controller) {
                        showUpdateBanner();
                    }
                });
            });
        });

        let refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (refreshing) return;
            refreshing = true;
            if (sessionStorage.getItem('pwa_update_clicked')) {
                sessionStorage.removeItem('pwa_update_clicked');
                window.location.reload();
            }
        });
    }

    window.addEventListener('beforeinstallprompt', e => {
        e.preventDefault();
        deferredPrompt = e;
        if (btnText) btnText.innerHTML = '<i class="fas fa-check-circle mr-2"></i> PASANG APLIKASI SEKARANG';
        if (!isStandalone && !restricted && !sessionStorage.getItem('pwa_install_dismissed') && installBanner) {
            setTimeout(() => { installBanner.style.display = 'flex'; }, 1000);
        }
    });

    const triggerInstall = async (banner) => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            await deferredPrompt.userChoice;
            deferredPrompt = null;
            if (banner) banner.style.display = 'none';
        } else {
            if (isAndroid) {
                if (btnText) btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyiapkan Sistem...';
                setTimeout(() => { if (!deferredPrompt && btnText) btnText.innerHTML = '<i class="fas fa-redo mr-2"></i> Klik Lagi Untuk Memasang'; }, 1500);
            } else if (isIos) {
                alert('Khusus iPhone: Klik ikon Share di Safari, lalu pilih "Add to Home Screen".');
            }
        }
    };

    document.getElementById('pwa-force-install-btn')?.addEventListener('click', () => triggerInstall(null));
    document.getElementById('pwa-install-btn')?.addEventListener('click', () => triggerInstall(installBanner));
    document.getElementById('pwa-close-install')?.addEventListener('click', () => {
        if (installBanner) installBanner.style.display = 'none';
        sessionStorage.setItem('pwa_install_dismissed', 'true');
    });

    window.addEventListener('appinstalled', () => {
        if (forceOverlay) forceOverlay.style.display = 'none';
        if (installBanner) installBanner.style.display = 'none';
    });

    function showUpdateBanner() {
        if (!sessionStorage.getItem('pwa_update_dismissed') && updateBanner) updateBanner.style.display = 'flex';
    }

    document.getElementById('pwa-update-btn')?.addEventListener('click', async () => {
        if (updateBanner) updateBanner.style.display = 'none';
        if (updatingOverlay) updatingOverlay.style.display = 'flex';
        
        // Progress Bar Animation
        if (progressBar) progressBar.style.width = '100%';
        
        try {
            // 1. Bersihkan Cache Storage
            if ('caches' in window) {
                const cacheNames = await caches.keys();
                await Promise.all(cacheNames.map(name => caches.delete(name)));
            }

            // 2. Unregister Service Worker
            if ('serviceWorker' in navigator) {
                const registrations = await navigator.serviceWorker.getRegistrations();
                for (let registration of registrations) {
                    await registration.unregister();
                }
            }

            // 3. Beri jeda sedikit untuk visual progress
            setTimeout(() => {
                sessionStorage.setItem('pwa_update_done', 'true');
                window.location.href = window.location.pathname + '?v=' + Date.now();
            }, 1500);

        } catch (error) {
            console.error('Update failed:', error);
            window.location.reload();
        }
    });

    document.getElementById('pwa-close-update')?.addEventListener('click', () => {
        if (updateBanner) updateBanner.style.display = 'none';
        sessionStorage.setItem('pwa_update_dismissed', 'true');
    });
})();
</script>
