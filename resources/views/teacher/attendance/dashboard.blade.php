@extends($layout)

@section('title', ($setting->enable_face_attendance ?? true) ? 'Presensi Wajah AI' : 'Presensi Digital GPS')

@section('content')
<div class="dashboard-wrapper pb-24 min-h-screen bg-slate-50">
    <!-- PREMIUM HEADER AREA -->
    <div class="header-banner bg-grad-indigo pt-10 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 hover:bg-white/20 transition-all">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <span class="bg-white/20 backdrop-blur-md text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-1 inline-block text-white">Smart Attendance</span>
                        <h1 class="text-3xl font-black text-white tracking-tight leading-tight">{{ ($setting->enable_face_attendance ?? true) ? 'Presensi Wajah AI' : 'Presensi Digital GPS' }}</h1>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 text-center hidden md:block">
                    <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Hari Ini</span>
                    <span class="text-xs font-black text-white">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-3 gap-4">
                @php
                    $history = \App\Models\Attendance::where('teacher_id', $teacher->id)
                        ->whereMonth('date', date('m'))
                        ->whereYear('date', date('Y'))
                        ->orderBy('date', 'desc')
                        ->get();
                    $present = $history->where('status', 'present')->count();
                    $late = $history->where('status', 'late')->count();
                    $permits = $history->whereIn('status', ['permit', 'sick'])->count();
                @endphp
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-[1.5rem] p-4 text-center group hover:bg-white/20 transition-all">
                    <p class="text-indigo-200 text-[8px] font-black uppercase tracking-wider mb-1">Hadir</p>
                    <p class="text-white font-black text-2xl">{{ $present }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-[1.5rem] p-4 text-center group hover:bg-white/20 transition-all">
                    <p class="text-rose-200 text-[8px] font-black uppercase tracking-wider mb-1">Lambat</p>
                    <p class="text-white font-black text-2xl">{{ $late }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-[1.5rem] p-4 text-center group hover:bg-white/20 transition-all">
                    <p class="text-amber-200 text-[8px] font-black uppercase tracking-wider mb-1">Izin</p>
                    <p class="text-white font-black text-2xl">{{ $permits }}</p>
                </div>
            </div>
        </div>
        <!-- Decoration -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-30px] bottom-[-30px] w-48 h-48 bg-indigo-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- MAIN CAMERA AREA -->
    <div class="max-w-4xl mx-auto px-6 -mt-20 relative z-20">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 p-1 border border-slate-50 overflow-hidden">
            <div class="p-8">
                @if($onLeave)
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fas fa-umbrella-beach text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-slate-800 mb-2">Status: Sedang Izin</h4>
                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">Anda tidak perlu melakukan absen hari ini.</p>
                    </div>
                @elseif($holiday)
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fas fa-calendar-times text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-slate-800 mb-2">Hari Libur: {{ $holiday->name }}</h4>
                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">Selamat beristirahat dan sampai jumpa besok!</p>
                    </div>
                @elseif($isWeekend)
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fas fa-mug-hot text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-slate-800 mb-2">Libur Akhir Pekan</h4>
                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">Nikmati waktu istirahat Anda.</p>
                    </div>
                @elseif(!$canCheckIn && !$canCheckOut)
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fas fa-clock text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-slate-800 mb-2">Sesi Presensi Ditutup</h4>
                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest leading-relaxed">Saat ini di luar jadwal presensi.<br>Batas Masuk: {{ $setting->check_in_end }} | Batas Pulang: {{ $setting->check_out_end }}</p>
                    </div>
                @else
                    <div id="attendance-app">
                        <!-- Location Status -->
                        <div id="geo-status" class="mb-6 py-3 px-6 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-slate-50 text-slate-400 border border-slate-100 flex items-center justify-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Mendeteksi Lokasi & Geofence...
                        </div>

                        <!-- Camera Viewport / Manual Mode -->
                        @if($setting->enable_face_attendance)
                            <div class="relative mb-10 group mx-auto max-w-[320px]">
                                <div id="face-container" class="relative rounded-[2.5rem] overflow-hidden border-4 border-slate-100 bg-slate-900 aspect-[3/4] flex items-center justify-center shadow-2xl">
                                    <video id="video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                                    <canvas id="overlay" class="absolute top-0 left-0 w-full h-full"></canvas>
                                    
                                    <!-- AI Elements -->
                                    <div class="scan-line"></div>
                                    <div class="face-frame"></div>
                                    
                                    <div id="face-loading" class="absolute inset-0 bg-slate-900 flex flex-col items-center justify-center text-white z-20">
                                        <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em]">Memuat AI Core...</p>
                                    </div>

                                    <div id="face-locked" class="hidden absolute inset-0 bg-emerald-500/80 backdrop-blur-sm flex flex-col items-center justify-center text-white z-20 animate__animated animate__zoomIn">
                                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 border border-white/30">
                                            <i class="fas fa-check text-4xl"></i>
                                        </div>
                                        <p class="text-lg font-black uppercase tracking-widest">Wajah Dikenali!</p>
                                    </div>
                                </div>
                                
                                <!-- Helpful Indicator -->
                                <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-white px-6 py-2 rounded-full shadow-xl border border-slate-100 whitespace-nowrap z-30">
                                    <span id="instruction-text" class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Tatap Kamera Sekarang</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col space-y-4 max-w-xs mx-auto">
                                @if(!$faceDescriptor)
                                    <div class="bg-indigo-50 p-6 rounded-[2rem] border border-indigo-100 text-center mb-4">
                                        <p class="text-[10px] font-black text-indigo-600 uppercase mb-3">Wajah Belum Terdaftar</p>
                                        <button id="btn-register-face" disabled class="w-full bg-indigo-600 text-white rounded-2xl py-4 font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-200 active:scale-95 transition-all">
                                            <i class="fas fa-id-card mr-2"></i> DAFTARKAN WAJAH SAYA
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center pt-4">
                                        <a href="{{ route('teacher.attendance.manual') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">
                                            <i class="fas fa-map-marker-alt mr-1"></i> Masalah Kamera? Gunakan Absen Map & Manual
                                        </a>
                                        <button id="btn-submit-manual" class="hidden w-full bg-slate-900 text-white rounded-2xl py-4 font-black text-xs uppercase tracking-widest shadow-xl active:scale-95 transition-all">
                                            {{ $canCheckIn ? 'Kirim Absen Masuk' : 'Kirim Absen Pulang' }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                                    <i class="fas fa-map-marker-alt text-4xl"></i>
                                </div>
                                <h4 class="text-xl font-black text-slate-800 mb-2">Presensi GPS Aktif</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-8">Validasi wajah dinonaktifkan oleh Admin.</p>
                                
                                <button id="btn-submit-gps" class="w-full bg-emerald-600 text-white rounded-[2rem] py-5 font-black text-sm uppercase tracking-widest shadow-2xl shadow-emerald-200 active:scale-95 transition-all">
                                    <i class="fas fa-fingerprint mr-2 text-lg"></i>
                                    Kirim Presensi Sekarang
                                </button>
                                <a href="{{ route('teacher.attendance.manual') }}" class="mt-6 block text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-map-marked-alt mr-1"></i> Lihat Peta Presensi
                                </a>
                                
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mt-6">
                                    <p class="text-[9px] text-slate-400 font-black uppercase mb-0">Lokasi Anda akan diverifikasi berdasarkan radius kantor.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- LOG HISTORY -->
    <div class="max-w-4xl mx-auto px-6 mt-12">
        <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-50">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-black text-slate-800 uppercase tracking-widest">Log Aktivitas Bulan Ini</h4>
                <div class="w-12 h-1 bg-indigo-100 rounded-full"></div>
            </div>

            <div class="space-y-4">
                @forelse($history as $h)
                    <div class="flex items-center p-5 bg-slate-50 rounded-[2rem] border border-slate-100 hover:bg-white hover:shadow-xl transition-all group">
                        <div class="w-14 h-14 bg-white rounded-2xl flex flex-col items-center justify-center border border-slate-200 shadow-sm mr-6">
                            <span class="text-slate-400 text-[8px] font-black uppercase mb-1">{{ $h->date->translatedFormat('M') }}</span>
                            <span class="text-slate-800 font-black text-lg leading-none">{{ $h->date->translatedFormat('d') }}</span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-2">
                                <h6 class="text-sm font-black text-slate-800 mb-0">{{ $h->date->translatedFormat('l') }}</h6>
                                <span class="badge-status-small {{ $h->status }}">{{ $h->status }}</span>
                            </div>
                            <div class="flex space-x-6">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Masuk: <b class="text-slate-700 ml-1">{{ $h->check_in ? \Carbon\Carbon::parse($h->check_in)->format('H:i') : '--:--' }}</b></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pulang: <b class="text-slate-700 ml-1">{{ $h->check_out ? \Carbon\Carbon::parse($h->check_out)->format('H:i') : '--:--' }}</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada riwayat presensi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Tokens */
    :root {
        --p-indigo: #6366f1;
        --p-emerald: #10b981;
        --p-rose: #f43f5e;
        --p-amber: #f59e0b;
    }

    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }

    /* Camera AI Styles */
    .scan-line {
        position: absolute; top: 0; left: 0; width: 100%; height: 4px;
        background: linear-gradient(to right, transparent, var(--p-indigo), transparent);
        box-shadow: 0 0 20px var(--p-indigo); z-index: 10;
        animation: scan 3s linear infinite; opacity: 0.8;
    }
    @keyframes scan { 0% { top: 0; } 50% { top: 100%; } 100% { top: 0; } }

    .face-frame {
        position: absolute; top: 20%; left: 20%; right: 20%; bottom: 20%;
        border: 2px dashed rgba(255,255,255,0.3); border-radius: 2rem;
        pointer-events: none; z-index: 5;
    }

    .badge-status-small { font-size: 8px; font-weight: 900; text-transform: uppercase; padding: 4px 10px; border-radius: 8px; }
    .badge-status-small.present { background: #dcfce7; color: #166534; }
    .badge-status-small.late { background: #fef3c7; color: #92400e; }
    .badge-status-small.absent { background: #fee2e2; color: #991b1b; }

    /* Hide face elements when locked */
    #face-locked:not(.hidden) ~ .scan-line,
    #face-locked:not(.hidden) ~ .face-frame { display: none; }
</style>

@push('scripts')
@include('partials.offline_sync')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const video = document.getElementById('video');
    const geoStatus = document.getElementById('geo-status');
    const instructionText = document.getElementById('instruction-text');
    const btnManual = document.getElementById('btn-manual');
    const btnSubmitManual = document.getElementById('btn-submit-manual');
    
    let userLat = null;
    let userLng = null;
    let isFaceDetected = false;
    let isAttendanceSent = false;
    
    const OFFICE_LAT = {{ $setting->latitude ?? 0 }};
    const OFFICE_LNG = {{ $setting->longitude ?? 0 }};
    const RADIUS = {{ $setting->radius ?? 100 }};
    const FACE_DESCRIPTOR = {!! $faceDescriptor ? json_encode($faceDescriptor->descriptors) : 'null' !!};
    const MODEL_URL = '/models/';

    // Geolocation
    function initGeo() {
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(pos => {
                const { latitude, longitude, accuracy } = pos.coords;
                
                // --- DETEKSI FAKE GPS / MOCK LOCATION ---
                const isMocked = pos.mocked || (pos.coords && pos.coords.mocked);
                const isSuspiciousAccuracy = accuracy <= 0 || (accuracy > 0 && accuracy < 0.5);

                if (isMocked || isSuspiciousAccuracy) {
                    geoStatus.innerHTML = `<i class="fas fa-exclamation-triangle text-rose-500 mr-2"></i> FAKE GPS TERDETEKSI!`;
                    geoStatus.classList.add('animate-pulse');
                    userLat = null; userLng = null;
                    return;
                }
                // ----------------------------------------

                userLat = latitude;
                userLng = longitude;
                if (OFFICE_LAT && OFFICE_LNG) {
                    const dist = calculateDistance(userLat, userLng, OFFICE_LAT, OFFICE_LNG);
                    if (dist <= RADIUS) {
                        geoStatus.innerHTML = `<i class="fas fa-check-circle text-emerald-500 mr-2"></i> Area Madrasah (${Math.round(dist)}m)`;
                        geoStatus.classList.replace('text-slate-400', 'text-emerald-600');
                        geoStatus.classList.replace('bg-slate-50', 'bg-emerald-50');
                    } else {
                        geoStatus.innerHTML = `<i class="fas fa-exclamation-triangle text-rose-500 mr-2"></i> Luar Jangkauan (${Math.round(dist)}m)`;
                        geoStatus.classList.replace('text-slate-400', 'text-rose-600');
                        geoStatus.classList.replace('bg-slate-50', 'bg-rose-50');
                    }
                }
            }, err => {
                geoStatus.innerHTML = `<i class="fas fa-times-circle text-rose-500 mr-2"></i> GPS Dinonaktifkan`;
            }, { enableHighAccuracy: true });
        }
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
        return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
    }

    // AI Core
    async function initFace() {
        if (!video) return; // Skip if Face Attendance is disabled
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            document.getElementById('face-loading').classList.add('hidden');
            startVideo();
        } catch (err) {
            console.error(err);
            document.getElementById('face-loading').innerHTML = '<p class="text-rose-400">Gagal Memuat AI</p>';
            // Fallback for camera even if AI fails (for manual mode)
            startVideo();
        }
    }

    function startVideo() {
        navigator.mediaDevices.getUserMedia({ video: {} }).then(s => { video.srcObject = s; });
    }

    if (video) {
        video.addEventListener('play', () => {
            const canvas = document.getElementById('overlay');
            const displaySize = { width: video.clientWidth, height: video.clientHeight };
            faceapi.matchDimensions(canvas, displaySize);

            let faceMatcher = null;
            if (FACE_DESCRIPTOR) {
                const labeledDescriptors = new faceapi.LabeledFaceDescriptors('User', [new Float32Array(Object.values(FACE_DESCRIPTOR))]);
                faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);
            }

            const interval = setInterval(async () => {
                if (isAttendanceSent) { clearInterval(interval); return; }
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                
                if (detections.length > 0) {
                    instructionText.innerText = 'Menganalisis Wajah...';
                    instructionText.classList.replace('text-slate-500', 'text-indigo-600');
                    
                    if (!FACE_DESCRIPTOR) {
                        const btnReg = document.getElementById('btn-register-face');
                        if (btnReg) { btnReg.disabled = false; faceDescriptor = detections[0].descriptor; }
                        return;
                    }

                    const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
                    results.forEach((result, i) => {
                        if (result.label === 'User' && result.distance < 0.45) {
                            if (!isFaceDetected) {
                                isFaceDetected = true;
                                instructionText.innerText = 'Verifikasi Berhasil!';
                                document.getElementById('face-locked').classList.remove('hidden');
                                setTimeout(() => submitAttendance('face'), 1500);
                            }
                        }
                    });
                } else {
                    instructionText.innerText = 'Posisikan Wajah Anda';
                    instructionText.classList.replace('text-indigo-600', 'text-slate-500');
                    if (!FACE_DESCRIPTOR) document.getElementById('btn-register-face').disabled = true;
                }
            }, 600);
        });
    }

    // Register logic
    const btnReg = document.getElementById('btn-register-face');
    if (btnReg) {
        btnReg.addEventListener('click', async () => {
            btnReg.disabled = true;
            btnReg.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth; canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            $.post('{{ route('teacher.face.save') }}', { _token: '{{ csrf_token() }}', descriptors: JSON.stringify(Array.from(faceDescriptor)), image: canvas.toDataURL('image/jpeg') })
            .done(() => { Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Wajah terdaftar! Mulai absen sekarang.', timer: 2000, showConfirmButton: false }).then(() => location.reload()); })
            .fail(e => { Swal.fire('Gagal', e.responseJSON?.message || 'Error', 'error'); btnReg.disabled = false; });
        });
    }

    async function submitAttendance(method) {
        if (isAttendanceSent) return;
        if (!userLat || !userLng) {
            Swal.fire('Gagal', 'Lokasi tidak terdeteksi atau Anda menggunakan Fake GPS.', 'error');
            return;
        }
        if (OFFICE_LAT && OFFICE_LNG) {
            const dist = calculateDistance(userLat, userLng, OFFICE_LAT, OFFICE_LNG);
            if (dist > RADIUS) {
                Swal.fire('Gagal', `Anda berada di luar jangkauan (${Math.round(dist)}m).`, 'error');
                isFaceDetected = false; isAttendanceSent = false;
                const faceLocked = document.getElementById('face-locked');
                if (faceLocked) faceLocked.classList.add('hidden');
                return;
            }
        }
        isAttendanceSent = true;
        Swal.fire({ title: 'Mengirim...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
        let imageData = null;
        if (video && video.videoWidth) {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth; 
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            imageData = canvas.toDataURL('image/jpeg');
        }

        const isCheckOut = '{{ ($todayAttendance && !$todayAttendance->check_out) ? '1' : '0' }}' === '1';
        
        $.post('{{ ($todayAttendance && !$todayAttendance->check_out) ? route('teacher.attendance.check-out') : route('teacher.attendance.check-in') }}', {
            _token: '{{ csrf_token() }}', latitude: userLat, longitude: userLng, image: imageData, method: method
        })
        .done(res => { 
            Swal.fire({ 
                icon: 'success', 
                title: 'Berhasil', 
                text: res.message + (isCheckOut ? '' : '\nSilahkan isi jurnal KBM hari ini.'), 
                timer: isCheckOut ? 2000 : 3000, 
                showConfirmButton: !isCheckOut,
                confirmButtonText: 'Isi Jurnal Sekarang',
                customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-2xl px-6' } 
            }).then(() => {
                if (!isCheckOut) {
                    location.href = '{{ route('guru.journal.index') }}';
                } else {
                    location.reload();
                }
            });
        })
        .fail(err => { 
            isAttendanceSent = false; 
            isFaceDetected = false; 
            const faceLocked = document.getElementById('face-locked');
            if (faceLocked) faceLocked.classList.add('hidden'); 

            if (err.status === 0 || !navigator.onLine) {
                OfflineSync.save('{{ ($todayAttendance && !$todayAttendance->check_out) ? route('teacher.attendance.check-out') : route('teacher.attendance.check-in') }}', {
                    _token: '{{ csrf_token() }}', latitude: userLat, longitude: userLng, image: imageData, method: method
                });
                return;
            }

            Swal.fire('Gagal', err.responseJSON?.message || 'Error', 'error'); 
        });
    }

    btnManual?.addEventListener('click', () => btnSubmitManual.classList.toggle('hidden'));
    btnSubmitManual?.addEventListener('click', () => submitAttendance('manual'));
    
    document.getElementById('btn-submit-gps')?.addEventListener('click', function() {
        submitAttendance('gps');
    });

    initGeo(); initFace();
</script>
@endpush
@endsection
