<script>
    const OfflineSync = {
        STORAGE_KEY: 'offline_attendance_queue',

        save: function(endpoint, payload) {
            let queue = this.getQueue();
            
            // Add offline_time to payload
            payload.offline_time = new Date().toISOString();
            
            queue.push({
                id: Date.now().toString(),
                endpoint: endpoint,
                payload: payload,
                timestamp: new Date().getTime()
            });
            
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(queue));
            this.showOfflineAlert();
            this.updateBadge();
        },

        getQueue: function() {
            try {
                return JSON.parse(localStorage.getItem(this.STORAGE_KEY)) || [];
            } catch (e) {
                return [];
            }
        },

        clearQueue: function() {
            localStorage.removeItem(this.STORAGE_KEY);
            this.updateBadge();
        },

        sync: async function() {
            if (!navigator.onLine) return;
            
            let queue = this.getQueue();
            if (queue.length === 0) return;

            let successCount = 0;
            let currentQueue = [...queue]; // Copy for iteration

            // Disable UI during sync if needed, but background is better.
            // We'll show a small toast or just a silent background sync with final alert.
            
            for (let i = 0; i < currentQueue.length; i++) {
                let item = currentQueue[i];
                try {
                    await $.post(item.endpoint, item.payload);
                    successCount++;
                    // Remove from actual queue in storage
                    queue = queue.filter(q => q.id !== item.id);
                    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(queue));
                } catch (err) {
                    // If error is 4xx (e.g., already checked in), we should probably discard it to avoid infinite loops,
                    // but if it's 5xx or 0 (network), keep it.
                    if (err.status >= 400 && err.status < 500) {
                        queue = queue.filter(q => q.id !== item.id);
                        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(queue));
                    }
                    console.error('Offline sync failed for item', item, err);
                }
            }

            this.updateBadge();

            if (successCount > 0) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sinkronisasi Berhasil',
                    text: `${successCount} data presensi offline telah berhasil dikirim ke server.`,
                    timer: 4000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-3xl' }
                }).then(() => location.reload());
            }
        },

        showOfflineAlert: function() {
            Swal.fire({
                icon: 'info',
                title: 'Mode Offline Aktif',
                text: 'Sinyal terputus! Data absen Anda telah disimpan dengan aman di memori HP dan akan otomatis terkirim saat sinyal kembali stabil.',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-2xl px-6 btn-info' }
            }).then(() => {
                location.reload(); // Reload UI to reset camera/state
            });
        },

        updateBadge: function() {
            // Optional: Update a UI badge if you want to show pending syncs
            let queue = this.getQueue();
            let badge = document.getElementById('offline-sync-badge');
            if (badge) {
                if (queue.length > 0) {
                    badge.style.display = 'flex';
                    badge.innerText = queue.length;
                } else {
                    badge.style.display = 'none';
                }
            }
        },

        init: function() {
            // Check immediately on load
            this.updateBadge();
            if (navigator.onLine) {
                setTimeout(() => this.sync(), 2000); // Small delay to let page render
            }

            // Listen to network status changes
            window.addEventListener('online', () => {
                console.log('Jaringan kembali online, memulai sinkronisasi...');
                this.sync();
            });
        }
    };

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', () => OfflineSync.init());
</script>
