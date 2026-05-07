@extends('layouts.teacher')

@section('content')
<div class="min-h-screen pb-32 bg-slate-50">
    <!-- PREMIUM TOP BAR -->
    <div class="bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('teacher.attendance.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 hover:bg-white/20 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="text-right">
                    <span class="bg-emerald-500/20 backdrop-blur-md text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-1 inline-block text-emerald-300 border border-emerald-500/30">GPS Verified</span>
                    <h1 class="text-xl font-black text-white tracking-tight leading-tight">Presensi Lokasi</h1>
                </div>
            </div>
            
            <!-- Quick Status Alert -->
            <div id="geo-alert" class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 flex items-center space-x-4 animate__animated animate__fadeIn">
                <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i id="status-icon" class="fas fa-location-arrow fa-spin"></i>
                </div>
                <div>
                    <p id="geo-status-text" class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-0">Sistem Mencari Lokasi...</p>
                    <p id="geo-detail-text" class="text-xs font-bold text-white mb-0">Mohon aktifkan GPS & Izin Kamera</p>
                </div>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-30px] bottom-[-30px] w-48 h-48 bg-indigo-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- MAIN INTERACTIVE AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-20 relative z-20">
        
        <!-- MAP CARD -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden mb-6 group transition-all hover:shadow-indigo-100/50">
            <div class="p-2">
                <div id="map" class="w-full h-72 rounded-[2rem] z-10 shadow-inner"></div>
            </div>
            <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between space-x-2">
                <div class="flex items-center space-x-2 overflow-hidden">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex-shrink-0 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-map-marked-alt text-xs"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest truncate">Visualisasi Geofencing</span>
                </div>
                <div id="distance-badge" class="flex-shrink-0 px-3 py-2 rounded-xl bg-slate-200 text-slate-500 text-[8px] font-black uppercase tracking-widest whitespace-nowrap">
                    -- m ke Kantor
                </div>
            </div>
        </div>

        <!-- CAMERA CARD -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 p-2 mb-8 relative group transition-all hover:shadow-indigo-100/50">
            <div class="relative rounded-[2rem] overflow-hidden aspect-video bg-slate-900 border-4 border-white shadow-lg">
                <video id="video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                
                <!-- Camera Overlay -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-48 border-2 border-white/30 rounded-3xl border-dashed animate-pulse"></div>
                </div>
                
                <!-- Bottom Label -->
                <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none">
                    <span class="bg-black/40 backdrop-blur-md px-4 py-1 rounded-full text-[8px] font-black text-white uppercase tracking-widest border border-white/20">Preview Kamera Aktif</span>
                </div>
            </div>
            
            <!-- Floating Indicator -->
            <div class="absolute -top-4 -right-4 w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-indigo-600 border border-slate-100 animate-bounce">
                <i class="fas fa-camera"></i>
            </div>
        </div>

        <!-- ACTION AREA -->
        <div class="sticky bottom-10 left-0 right-0 px-6 pb-4">
            @if($canCheckIn || $canCheckOut)
                <button id="btn-submit" class="w-full bg-grad-indigo text-white rounded-3xl py-6 font-black text-sm uppercase tracking-widest shadow-2xl shadow-indigo-200 active:scale-95 transition-all group overflow-hidden relative">
                    <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-fingerprint mr-3 text-lg"></i>
                        {{ $canCheckIn ? 'Kirim Presensi Masuk' : 'Kirim Presensi Pulang' }}
                    </span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform"></div>
                </button>
                <p class="text-center mt-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                    <i class="fas fa-shield-alt mr-1"></i> Data Terenkripsi & Dilindungi GPS Anti-Fake
                </p>
            @else
                <div class="bg-white rounded-3xl p-6 border border-rose-100 text-center shadow-xl">
                    <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="text-[10px] font-black text-rose-600 uppercase mb-1">Sesi Presensi Berakhir</p>
                    <p class="text-xs font-bold text-slate-400 mb-0">Sudah absen atau di luar jam operasional.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    #map { z-index: 10; }
    .leaflet-control-attribution { display: none; }
    
    /* Animation for the button */
    #btn-submit:not(:disabled):hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(49, 46, 129, 0.4);
    }
</style>

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { z-index: 10; }
    .leaflet-control-attribution { display: none; }
</style>
@endpush

@push('scripts')
@include('partials.offline_sync')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    /**
     * AttendanceManager - Professional Logic for Location-Based Attendance
     * Handles Map, Camera, Geolocation, and Secure Submission
     */
    const AttendanceManager = {
        config: {
            office: { lat: {{ $setting->latitude ?? 0 }}, lng: {{ $setting->longitude ?? 0 }} },
            radius: {{ $setting->radius ?? 100 }},
            endpoints: {
                submit: '{{ ($todayAttendance && !$todayAttendance->check_out) ? route('teacher.attendance.check-out') : route('teacher.attendance.check-in') }}'
            }
        },
        state: {
            userLoc: { lat: null, lng: null },
            map: null,
            markers: { user: null, office: null },
            isSubmitting: false,
            hasInitialFocus: false
        },

        init() {
            this.initMap();
            this.initGeo();
            this.initCamera();
            this.bindEvents();
            console.log("Attendance Manager Initialized");
        },

        initMap() {
            if (this.state.map) return;
            
            this.state.map = L.map('map', { 
                zoomControl: false, 
                attributionControl: false 
            }).setView([this.config.office.lat, this.config.office.lng], 18);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.state.map);

            const officeIcon = L.divIcon({
                html: `<div class="w-10 h-10 bg-indigo-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white"><i class="fas fa-school text-xs"></i></div>`,
                className: '', iconSize: [40, 40], iconAnchor: [20, 20]
            });

            this.state.markers.office = L.marker([this.config.office.lat, this.config.office.lng], { icon: officeIcon })
                .addTo(this.state.map)
                .bindPopup('<b class="text-xs uppercase font-black">Area Madrasah</b>');

            L.circle([this.config.office.lat, this.config.office.lng], {
                color: '#6366f1', fillColor: '#6366f1', fillOpacity: 0.1, radius: this.config.radius
            }).addTo(this.state.map);

            setTimeout(() => this.state.map.invalidateSize(), 500);
        },

        initGeo() {
            if (!navigator.geolocation) {
                this.updateStatus('Incompatible', 'Browser tidak mendukung GPS', 'times-circle', 'rose');
                return;
            }

            navigator.geolocation.watchPosition(
                (pos) => this.handleGeoSuccess(pos),
                (err) => this.handleGeoError(err),
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        },

        handleGeoSuccess(pos) {
            const { latitude, longitude, accuracy } = pos.coords;
            const isMocked = pos.mocked || (pos.coords && pos.coords.mocked);

            if (isMocked || (accuracy > 0 && accuracy < 1)) {
                this.updateStatus('FAKE GPS DETECTED', 'Gunakan lokasi asli Anda', 'exclamation-triangle', 'rose');
                this.state.userLoc = { lat: null, lng: null };
                return;
            }

            this.state.userLoc = { lat: latitude, lng: longitude };
            const distance = this.calculateDistance(latitude, longitude, this.config.office.lat, this.config.office.lng);
            const isWithin = distance <= this.config.radius;

            this.updateUI(distance, isWithin);
            this.updateUserMarker(latitude, longitude);
        },

        handleGeoError(err) {
            let msg = 'Gagal mengakses GPS';
            if (err.code === 1) msg = 'Izin lokasi ditolak';
            else if (err.code === 3) msg = 'Waktu GPS habis (Timeout)';
            this.updateStatus('GPS Bermasalah', msg, 'exclamation-circle', 'rose');
        },

        updateUI(distance, isWithin) {
            if (isWithin) {
                this.updateStatus('Lokasi Terverifikasi', 'Anda berada dalam jangkauan', 'check-circle', 'emerald');
            } else {
                this.updateStatus('Di Luar Jangkauan', 'Silahkan mendekat ke kantor', 'map-marker-alt', 'amber');
            }

            const badge = document.getElementById('distance-badge');
            if (badge) {
                badge.innerText = `${Math.round(distance)} m ke Kantor`;
                badge.className = `flex-shrink-0 px-3 py-2 rounded-xl text-[8px] font-black uppercase tracking-widest whitespace-nowrap ${isWithin ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'}`;
            }
        },

        updateStatus(title, detail, icon, color) {
            const textElem = document.getElementById('geo-status-text');
            const detailElem = document.getElementById('geo-detail-text');
            const iconElem = document.getElementById('status-icon');

            if (textElem) textElem.innerText = title;
            if (detailElem) detailElem.innerText = detail;
            if (iconElem) {
                iconElem.className = `fas fa-${icon}`;
                iconElem.parentElement.className = `w-10 h-10 bg-${color}-500 rounded-xl flex items-center justify-center text-white shadow-lg transition-all`;
            }
        },

        updateUserMarker(lat, lng) {
            const userIcon = L.divIcon({
                html: `<div class="w-8 h-8 bg-rose-500 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white animate-pulse"><i class="fas fa-user text-[10px]"></i></div>`,
                className: '', iconSize: [32, 32], iconAnchor: [16, 16]
            });

            if (this.state.markers.user) {
                this.state.markers.user.setLatLng([lat, lng]);
            } else {
                this.state.markers.user = L.marker([lat, lng], { icon: userIcon }).addTo(this.state.map);
            }

            if (!this.state.hasInitialFocus) {
                this.state.map.setView([this.config.office.lat, this.config.office.lng], 18);
                this.state.hasInitialFocus = true;
            }
        },

        initCamera() {
            const video = document.getElementById('video');
            if (!video) return;

            navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                .then(stream => video.srcObject = stream)
                .catch(err => {
                    console.error("Camera Error:", err);
                    Swal.fire({ icon: 'warning', title: 'Kamera Gagal', text: 'Pastikan izin kamera diberikan agar dapat melakukan absen.', customClass: { popup: 'rounded-3xl' } });
                });
        },

        calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3;
            const φ1 = lat1 * Math.PI/180;
            const φ2 = lat2 * Math.PI/180;
            const Δφ = (lat2-lat1) * Math.PI/180;
            const Δλ = (lon2-lon1) * Math.PI/180;
            const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
            return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
        },

        bindEvents() {
            const btn = document.getElementById('btn-submit');
            if (btn) {
                btn.addEventListener('click', () => this.submitAttendance());
            }
        },

        async submitAttendance() {
            if (this.state.isSubmitting) return;

            if (!this.state.userLoc.lat || !this.state.userLoc.lng) {
                return Swal.fire({ icon: 'error', title: 'Gagal', text: 'Lokasi belum terdeteksi sempurna.', customClass: { popup: 'rounded-3xl' } });
            }

            const distance = this.calculateDistance(this.state.userLoc.lat, this.state.userLoc.lng, this.config.office.lat, this.config.office.lng);
            if (distance > this.config.radius) {
                return Swal.fire({ icon: 'warning', title: 'Di Luar Radius', text: `Anda berjarak ${Math.round(distance)}m dari kantor.`, customClass: { popup: 'rounded-3xl' } });
            }

            this.state.isSubmitting = true;
            const btn = document.getElementById('btn-submit');
            if(btn) btn.disabled = true;

            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-3xl' } });

            try {
                const video = document.getElementById('video');
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth; 
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                const imageData = canvas.toDataURL('image/jpeg', 0.8);

                const response = await $.post(this.config.endpoints.submit, {
                    _token: '{{ csrf_token() }}',
                    latitude: this.state.userLoc.lat,
                    longitude: this.state.userLoc.lng,
                    image: imageData,
                    method: 'manual'
                });

                const isCheckOut = this.config.endpoints.submit.includes('check-out');

                Swal.fire({ 
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: response.message + (isCheckOut ? '' : '\nSilahkan isi jurnal KBM hari ini.'), 
                    timer: isCheckOut ? 2000 : 3000, 
                    showConfirmButton: !isCheckOut,
                    confirmButtonText: 'Isi Jurnal Sekarang',
                    customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-2xl px-6' } 
                }).then(() => {
                    if (!isCheckOut) {
                        location.href = '{{ route('guru.journal.index') }}';
                    } else {
                        location.href = '{{ route('teacher.attendance.dashboard') }}';
                    }
                });

            } catch (err) {
                this.state.isSubmitting = false;
                if(btn) btn.disabled = false;

                if (err.status === 0 || !navigator.onLine) {
                    OfflineSync.save(this.config.endpoints.submit, {
                        _token: '{{ csrf_token() }}',
                        latitude: this.state.userLoc.lat,
                        longitude: this.state.userLoc.lng,
                        image: imageData,
                        method: 'manual'
                    });
                    return;
                }

                Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Terjadi kesalahan sistem.', customClass: { popup: 'rounded-3xl' } });
            }
        }
    };

    // Auto Start
    document.addEventListener('DOMContentLoaded', () => AttendanceManager.init());
</script>
@endpush
@endsection
