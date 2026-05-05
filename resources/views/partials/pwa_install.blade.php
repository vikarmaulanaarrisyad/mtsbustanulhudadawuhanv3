{{-- ════════════════════════════════════════════ --}}
{{--  NOTIFIKASI INSTALASI PWA (Install Prompt)  --}}
{{-- ════════════════════════════════════════════ --}}
<div id="pwa-install-prompt" style="display:none;">
    <div class="pwa-banner pwa-install-banner">
        <div class="pwa-banner-icon">
            <img src="/icons/icon-192x192.png?v={{ $setting->pwa_version ?? time() }}" alt="App Icon" id="pwa-banner-icon-img">
        </div>
        <div class="pwa-banner-text">
            <h6>Pasang Aplikasi {{ $setting->pwa_short_name ?? 'Madrasah' }}</h6>
            <p>Akses lebih cepat & mudah langsung dari layar utama HP.</p>
        </div>
        <div class="pwa-banner-actions">
            <button id="pwa-install-btn" class="pwa-btn-install">INSTAL</button>
            <button id="pwa-close-install" class="pwa-btn-close">&times;</button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════ --}}
{{--  NOTIFIKASI UPDATE APLIKASI (SW Update)      --}}
{{-- ════════════════════════════════════════════ --}}
<div id="pwa-update-prompt" style="display:none;">
    <div class="pwa-banner pwa-update-banner">
        <div class="pwa-banner-icon pwa-update-icon">
            <i class="fas fa-sync-alt"></i>
        </div>
        <div class="pwa-banner-text">
            <h6>🎉 Versi Baru Tersedia!</h6>
            <p>Ada pembaruan aplikasi. Muat ulang untuk mendapatkan fitur terbaru.</p>
        </div>
        <div class="pwa-banner-actions">
            <button id="pwa-update-btn" class="pwa-btn-update">UPDATE</button>
            <button id="pwa-close-update" class="pwa-btn-close">&times;</button>
        </div>
    </div>
</div>

<style>
/* ─── Banner Base ───────────────────────────────────────── */
.pwa-banner {
    position: fixed;
    bottom: 100px;
    left: 16px;
    right: 16px;
    max-width: 480px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,.18), 0 4px 16px rgba(0,0,0,.08);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 99999;
    border: 1px solid #f1f5f9;
    animation: pwaSlideUp .45s cubic-bezier(.175,.885,.32,1.275) both;
}

/* Install — hijau */
.pwa-install-banner { border-top: 3px solid #10b981; }
/* Update — biru/ungu */
.pwa-update-banner { border-top: 3px solid #6366f1; bottom: 170px; }

@keyframes pwaSlideUp {
    from { transform: translateY(120%); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}

.pwa-banner-icon {
    width: 48px; height: 48px; border-radius: 12px;
    overflow: hidden; flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(16,185,129,.25);
}
.pwa-banner-icon img { width: 100%; height: 100%; object-fit: cover; }

.pwa-update-icon {
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.4rem;
    box-shadow: 0 4px 10px rgba(99,102,241,.35);
}

.pwa-banner-text { flex: 1; min-width: 0; }
.pwa-banner-text h6 {
    margin: 0; font-weight: 800; font-size: .88rem; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.pwa-banner-text p {
    margin: 2px 0 0; font-size: .76rem; color: #64748b; line-height: 1.35;
}

.pwa-banner-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

.pwa-btn-install {
    background: linear-gradient(135deg,#10b981,#059669);
    color: #fff; border: none; padding: 7px 14px;
    border-radius: 10px; font-weight: 800; font-size: .75rem;
    letter-spacing: .5px; cursor: pointer; transition: transform .15s;
    white-space: nowrap;
}
.pwa-btn-install:hover { transform: scale(1.05); }

.pwa-btn-update {
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff; border: none; padding: 7px 14px;
    border-radius: 10px; font-weight: 800; font-size: .75rem;
    letter-spacing: .5px; cursor: pointer; transition: transform .15s;
    white-space: nowrap;
}
.pwa-btn-update:hover { transform: scale(1.05); }

.pwa-btn-close {
    background: #f1f5f9; border: none;
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #94a3b8; font-size: 1.1rem; cursor: pointer;
    transition: background .15s;
}
.pwa-btn-close:hover { background: #e2e8f0; color: #475569; }

@media (max-width: 480px) {
    .pwa-banner { bottom: 110px; left: 12px; right: 12px; }
    .pwa-update-banner { bottom: 178px; }
}
</style>

<script>
(function () {
    let deferredPrompt = null;
    const installBanner = document.getElementById('pwa-install-prompt');
    const updateBanner  = document.getElementById('pwa-update-prompt');

    // ── Registrasi Service Worker ─────────────────────────────
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => {

                // Deteksi SW baru yang sedang menunggu (sudah install tapi belum aktif)
                const checkForWaiting = () => {
                    if (reg.waiting) {
                        showUpdateBanner();
                    }
                };
                checkForWaiting();

                reg.addEventListener('updatefound', () => {
                    const newSW = reg.installing;
                    newSW.addEventListener('statechange', () => {
                        if (newSW.state === 'installed' && navigator.serviceWorker.controller) {
                            showUpdateBanner();
                        }
                    });
                });
            })
            .catch(err => console.warn('SW registration failed:', err));

        // Dengarkan pesan dari SW (SW_UPDATED dikirim setelah activate)
        navigator.serviceWorker.addEventListener('message', event => {
            if (event.data && event.data.type === 'SW_UPDATED') {
                // Reload otomatis jika user sudah menekan tombol Update
                if (sessionStorage.getItem('pwa_update_clicked') === 'true') {
                    sessionStorage.removeItem('pwa_update_clicked');
                    window.location.reload();
                }
            }
        });

        // Reload jika controller berubah (SW baru mengambil alih)
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (sessionStorage.getItem('pwa_update_clicked') === 'true') {
                sessionStorage.removeItem('pwa_update_clicked');
                window.location.reload();
            }
        });
    }

    // ── Install Prompt ────────────────────────────────────────
    window.addEventListener('beforeinstallprompt', e => {
        e.preventDefault();
        deferredPrompt = e;

        if (!sessionStorage.getItem('pwa_install_dismissed')) {
            setTimeout(() => {
                installBanner.style.display = 'block';
            }, 3500);
        }
    });

    document.getElementById('pwa-install-btn')?.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            deferredPrompt = null;
            installBanner.style.display = 'none';
        }
    });

    document.getElementById('pwa-close-install')?.addEventListener('click', () => {
        installBanner.style.display = 'none';
        sessionStorage.setItem('pwa_install_dismissed', 'true');
    });

    window.addEventListener('appinstalled', () => {
        installBanner.style.display = 'none';
    });

    // ── Update Prompt ─────────────────────────────────────────
    function showUpdateBanner() {
        if (!sessionStorage.getItem('pwa_update_dismissed')) {
            updateBanner.style.display = 'block';
        }
    }

    document.getElementById('pwa-update-btn')?.addEventListener('click', () => {
        updateBanner.style.display = 'none';
        sessionStorage.setItem('pwa_update_clicked', 'true');

        // Instruksikan SW waiting untuk skip waiting dan ambil alih
        navigator.serviceWorker.getRegistration('/sw.js').then(reg => {
            if (reg && reg.waiting) {
                reg.waiting.postMessage({ type: 'SKIP_WAITING' });
            } else {
                window.location.reload();
            }
        });
    });

    document.getElementById('pwa-close-update')?.addEventListener('click', () => {
        updateBanner.style.display = 'none';
        sessionStorage.setItem('pwa_update_dismissed', 'true');
    });
})();
</script>
