{{-- PWA Install Prompt Notification --}}
<div id="pwa-install-prompt" class="pwa-install-banner" style="display: none;">
    <div class="pwa-install-content">
        <div class="pwa-install-icon">
            <img src="/icons/icon-192x192.png" alt="App Icon">
        </div>
        <div class="pwa-install-text">
            <h6>Pasang Aplikasi Madrasah</h6>
            <p>Akses lebih cepat & mudah dari layar utama HP Anda.</p>
        </div>
        <div class="pwa-install-actions">
            <button id="pwa-install-btn" class="btn btn-install">INSTAL</button>
            <button id="pwa-close-btn" class="btn-close-pwa">&times;</button>
        </div>
    </div>
</div>

<style>
    .pwa-install-banner {
        position: fixed;
        bottom: 100px; /* Di atas bottom nav */
        left: 20px;
        right: 20px;
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        z-index: 9999;
        padding: 15px 20px;
        animation: slideUpPwa 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #e2e8f0;
        max-width: 500px;
        margin: 0 auto;
    }

    @keyframes slideUpPwa {
        from { transform: translateY(150%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .pwa-install-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .pwa-install-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }

    .pwa-install-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pwa-install-text {
        flex: 1;
    }

    .pwa-install-text h6 {
        margin: 0;
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
    }

    .pwa-install-text p {
        margin: 2px 0 0 0;
        font-size: 11px;
        color: #64748b;
        line-height: 1.3;
    }

    .pwa-install-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-install {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 12px;
        letter-spacing: 0.5px;
        transition: all 0.2s;
    }

    .btn-install:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-close-pwa {
        background: #f1f5f9;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 20px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .pwa-install-banner {
            bottom: 110px; /* Sedikit lebih tinggi di mobile karena bottom nav */
        }
    }
</style>

<script>
    let deferredPrompt;
    const installBanner = document.getElementById('pwa-install-prompt');
    const installBtn = document.getElementById('pwa-install-btn');
    const closeBtn = document.getElementById('pwa-close-btn');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent the mini-infobar from appearing on mobile
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        
        // Cek apakah user sudah menutup banner sesi ini
        if (!sessionStorage.getItem('pwa_banner_closed')) {
            // Update UI notify the user they can install the PWA
            setTimeout(() => {
                installBanner.style.display = 'block';
            }, 3000); // Tampilkan setelah 3 detik agar tidak terlalu mengganggu
        }
    });

    installBtn.addEventListener('click', async () => {
        if (deferredPrompt) {
            // Show the install prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response to the install prompt: ${outcome}`);
            // We've used the prompt, and can't use it again
            deferredPrompt = null;
            // Hide the install banner
            installBanner.style.display = 'none';
        }
    });

    closeBtn.addEventListener('click', () => {
        installBanner.style.display = 'none';
        // Jangan tampilkan lagi di sesi ini
        sessionStorage.setItem('pwa_banner_closed', 'true');
    });

    window.addEventListener('appinstalled', (event) => {
        console.log('PWA was installed');
        installBanner.style.display = 'none';
    });
</script>
