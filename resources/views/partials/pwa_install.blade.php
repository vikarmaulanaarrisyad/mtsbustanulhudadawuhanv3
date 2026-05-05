{{-- ════════════════════════════════════════════ --}}
{{--  OVERLAY LOCK (Wajib Instal untuk Guru/Siswa) --}}
{{-- ════════════════════════════════════════════ --}}
@php
    $isRestricted = auth()->check() && (auth()->user()->hasRole('Guru') || auth()->user()->hasRole('Siswa'));
@endphp

@if($isRestricted)
<div id="pwa-force-install-overlay" style="display:none;">
    <div class="pwa-lock-content">
        <div class="pwa-lock-card">
            <div class="pwa-lock-header">
                <div class="pwa-lock-logo">
                    <img src="/icons/icon-192x192.png?v={{ $setting->pwa_version ?? time() }}" alt="Logo">
                </div>
                <h4>Aktivasi Aplikasi Digital</h4>
                <p>Silakan pasang aplikasi ke HP Anda untuk melanjutkan. Ini akan memberikan akses lebih cepat, stabil, dan aman.</p>
            </div>

            <div id="pwa-platform-info" class="my-4">
                {{-- Android Info --}}
                <div class="android-only pwa-status-box">
                    <div id="pwa-android-status" class="pwa-status-text">
                        <i class="fas fa-info-circle mr-2"></i> Siap dipasang ke layar utama.
                    </div>
                </div>

                {{-- iOS Info (Technical Necessity) --}}
                <div class="ios-only pwa-status-box">
                    <div class="pwa-status-text">
                        <i class="fas fa-share-square mr-2"></i> Klik ikon <b>Share</b> Safari, lalu <b>"Add to Home Screen"</b>.
                    </div>
                </div>
            </div>

            <div class="pwa-lock-footer">
                <button id="pwa-force-install-btn" class="pwa-btn-primary">
                    <span id="pwa-btn-text"><i class="fas fa-download mr-2"></i> PASANG APLIKASI SEKARANG</span>
                </button>
                <div class="mt-3">
                    <a href="javascript:void(0)" onclick="window.location.reload()" class="text-sm text-muted" style="text-decoration:underline;">
                        Sudah Pasang? Klik Segarkan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Banner Normal (Hanya untuk Admin/Lainnya) --}}
@if(!$isRestricted)
<div id="pwa-install-prompt" style="display:none;">
    <div class="pwa-banner pwa-install-banner">
        <div class="pwa-banner-icon">
            <img src="/icons/icon-192x192.png?v={{ $setting->pwa_version ?? time() }}" alt="App Icon">
        </div>
        <div class="pwa-banner-text">
            <h6>Pasang Aplikasi {{ $setting->pwa_short_name ?? 'Madrasah' }}</h6>
            <p>Akses lebih cepat & mudah dari layar utama HP.</p>
        </div>
        <div class="pwa-banner-actions">
            <button id="pwa-install-btn" class="pwa-btn-install">INSTAL</button>
            <button id="pwa-close-install" class="pwa-btn-close">&times;</button>
        </div>
    </div>
</div>
@endif

{{-- Update Banner --}}
<div id="pwa-update-prompt" style="display:none;">
    <div class="pwa-banner pwa-update-banner">
        <div class="pwa-banner-icon pwa-update-icon"><i class="fas fa-sync-alt"></i></div>
        <div class="pwa-banner-text">
            <h6>🎉 Versi Baru Tersedia!</h6>
            <p>Ada pembaruan. Klik update untuk fitur terbaru.</p>
        </div>
        <div class="pwa-banner-actions">
            <button id="pwa-update-btn" class="pwa-btn-update">UPDATE</button>
            <button id="pwa-close-update" class="pwa-btn-close">&times;</button>
        </div>
    </div>
</div>

{{-- Overlay Proses Update --}}
<div id="pwa-updating-overlay" style="display:none;">
    <div class="pwa-update-modal">
        <div class="pwa-update-spinner">
            <div class="spinner-inner"></div>
            <i class="fas fa-sync-alt fa-spin"></i>
        </div>
        <h4>Memasang Pembaruan...</h4>
        <p>Sedang mengunduh aset dan fitur terbaru. Mohon tunggu sebentar.</p>
        <div class="pwa-progress-container">
            <div id="pwa-progress-bar" class="pwa-progress-bar"></div>
        </div>
    </div>
</div>

<style>
#pwa-force-install-overlay, #pwa-updating-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.98); backdrop-filter: blur(10px);
    z-index: 100000; display: flex; align-items: center; justify-content: center; padding: 20px;
}
#pwa-updating-overlay { z-index: 200000; backdrop-filter: blur(15px); }

.pwa-lock-card, .pwa-update-modal {
    background: #fff; border-radius: 24px; width: 100%; max-width: 380px;
    padding: 40px 25px; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}
.pwa-lock-logo {
    width: 90px; height: 90px; margin: 0 auto 20px; border-radius: 20px;
    overflow: hidden; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
}
.pwa-lock-logo img { width: 100%; height: 100%; object-fit: cover; }
.pwa-lock-header h4, .pwa-update-modal h4 { font-weight: 800; color: #1e293b; margin-bottom: 8px; font-size: 1.25rem; }
.pwa-lock-header p, .pwa-update-modal p { color: #64748b; font-size: 0.85rem; line-height: 1.6; }

.pwa-status-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; }
.pwa-status-text { font-size: 0.8rem; color: #475569; font-weight: 600; }

.pwa-btn-primary {
    width: 100%; background: linear-gradient(135deg, #10b981, #059669);
    color: #fff; border: none; padding: 16px; border-radius: 14px;
    font-weight: 800; font-size: 1rem; cursor: pointer; transition: all 0.2s;
}
.pwa-btn-primary:active { transform: scale(0.98); }

.android-only, .ios-only { display: none; }
body.pwa-android .android-only { display: block; }
body.pwa-ios .ios-only { display: block; }

/* Banner Styles */
.pwa-banner {
    position: fixed; bottom: 100px; left: 16px; right: 16px; max-width: 480px;
    margin: 0 auto; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
    border-radius: 24px; z-index: 99999;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15); padding: 16px 20px;
    display: flex; align-items: center; gap: 16px; border: 1px solid rgba(255,255,255,0.3);
    animation: pwaSlideUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
}
.pwa-install-banner { border-left: 5px solid #10b981; }
.pwa-update-banner { border-left: 5px solid #6366f1; bottom: 180px; }

@keyframes pwaSlideUp { 
    from { transform: translateY(100px) scale(0.9); opacity: 0; } 
    to { transform: translateY(0) scale(1); opacity: 1; } 
}

.pwa-banner-icon { 
    width: 56px; height: 56px; border-radius: 14px; overflow: hidden; flex-shrink: 0; 
    box-shadow: 0 8px 16px rgba(0,0,0,0.1); border: 2px solid #fff;
    animation: pwaIconPop 0.6s 0.2s both;
}
@keyframes pwaIconPop { from { transform: scale(0); } to { transform: scale(1); } }

.pwa-banner-icon img { width: 100%; height: 100%; object-fit: cover; }
.pwa-btn-install, .pwa-btn-update { background: #10b981; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.7rem; cursor: pointer; }
.pwa-btn-update { background: #6366f1; }
.pwa-btn-close { background: none; border: none; color: #94a3b8; font-size: 1.2rem; cursor: pointer; }

/* Progress & Spinner */
.pwa-update-spinner { position: relative; width: 60px; height: 60px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #6366f1; }
.spinner-inner { position: absolute; width: 100%; height: 100%; border: 3px solid #f1f5f9; border-top-color: #6366f1; border-radius: 50%; animation: pwaSpin 1s linear infinite; }
@keyframes pwaSpin { to { transform: rotate(360deg); } }
.pwa-progress-container { width: 100%; height: 6px; background: #f1f5f9; border-radius: 10px; margin-top: 20px; overflow: hidden; }
.pwa-progress-bar { width: 0%; height: 100%; background: linear-gradient(90deg, #6366f1, #8b5cf6); transition: width 3s ease-in-out; }
</style>

<script>
(function () {
    let deferredPrompt = null;
    const restricted = {{ $isRestricted ? 'true' : 'false' }};
    const forceOverlay = document.getElementById('pwa-force-install-overlay');
    const installBanner = document.getElementById('pwa-install-prompt');
    const updateBanner = document.getElementById('pwa-update-prompt');
    const updatingOverlay = document.getElementById('pwa-updating-overlay');
    const progressBar = document.getElementById('pwa-progress-bar');
    const btnText = document.getElementById('pwa-btn-text');

    const ua = navigator.userAgent.toLowerCase();
    const isAndroid = ua.indexOf("android") > -1;
    const isIos = /ipad|iphone|ipod/.test(ua) && !window.MSStream;
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone || document.referrer.includes('android-app://');

    if (isAndroid) document.body.classList.add('pwa-android');
    else if (isIos) document.body.classList.add('pwa-ios');

    if (restricted && !isStandalone && forceOverlay) forceOverlay.style.display = 'flex';

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

    document.getElementById('pwa-update-btn')?.addEventListener('click', () => {
        if (updateBanner) updateBanner.style.display = 'none';
        if (updatingOverlay) updatingOverlay.style.display = 'flex';
        setTimeout(() => { if (progressBar) progressBar.style.width = '100%'; }, 50);
        sessionStorage.setItem('pwa_update_clicked', 'true');
        navigator.serviceWorker.getRegistration().then(reg => {
            if (reg) reg.update();
            if (reg?.waiting) reg.waiting.postMessage({ type: 'SKIP_WAITING' });
            else setTimeout(() => { window.location.reload(); }, 3000);
        });
        setTimeout(() => { if (sessionStorage.getItem('pwa_update_clicked')) window.location.reload(); }, 5000);
    });

    document.getElementById('pwa-close-update')?.addEventListener('click', () => {
        if (updateBanner) updateBanner.style.display = 'none';
        sessionStorage.setItem('pwa_update_dismissed', 'true');
    });
})();
</script>
