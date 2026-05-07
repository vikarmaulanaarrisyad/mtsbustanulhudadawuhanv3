@extends($layout)

@section('title', 'Registrasi Wajah AI')
@section('subtitle', 'Presensi')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12 text-center text-md-left">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-shield mr-2 animate__animated animate__fadeInLeft"></i> 
                            Registrasi Biometrik Wajah
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Daftarkan data biometrik wajah Anda untuk sistem presensi cerdas yang lebih aman dan akurat.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-camera-retro fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 24px;">
            <div class="row no-gutters">
                <!-- Camera Column -->
                <div class="col-lg-6 bg-slate-50 p-4 p-md-5 d-flex flex-col justify-content-center border-right">
                    <div id="camera-container" class="hidden">
                        <div class="video-wrapper shadow-2xl mb-4 bg-black mx-auto" style="border-radius: 20px; max-width: 400px;">
                            <video id="video" autoplay muted playsinline class="w-100 h-auto" style="border-radius: 20px;"></video>
                            <canvas id="overlay" class="absolute top-0 left-0 w-100 h-100"></canvas>
                            <div class="scan-line-simple"></div>
                        </div>
                        
                        <div id="status-text" class="text-center text-slate-500 font-bold text-xs uppercase tracking-widest py-3 bg-white rounded-xl shadow-sm border border-slate-100">
                            Posisikan Wajah Anda
                        </div>
                    </div>

                    @if(!$teacher)
                        @if(auth()->user()->hasRole(['Admin', 'Super Admin']))
                            <div id="admin-simulation-notice" class="bg-indigo-50 p-6 rounded-[2rem] border border-indigo-100 text-center mb-4">
                                <i class="fas fa-vial fa-3x text-indigo-400 mb-4"></i>
                                <h5 class="font-black text-indigo-800 text-sm uppercase tracking-widest mb-2">Mode Simulasi Admin</h5>
                                <p class="text-[10px] text-indigo-600 font-bold leading-relaxed mb-0">Akun Anda adalah Admin. Anda dapat mencoba fitur kamera & AI di sini, namun penyimpanan data hanya berlaku jika profil Guru terhubung.</p>
                            </div>
                            <div id="loading-models" class="text-center py-10">
                                <div class="spinner-grow text-indigo-400 mb-3" role="status"></div>
                                <p class="text-sm font-bold text-slate-500">Menyiapkan AI...</p>
                            </div>
                        @else
                            <div class="text-center py-20">
                                <i class="fas fa-user-lock fa-3x text-slate-200 mb-4"></i>
                                <h5 class="font-bold text-slate-800">Akses Dibatasi</h5>
                                <p class="text-xs text-slate-400">Profil Anda belum terdaftar sebagai staf.</p>
                            </div>
                        @endif
                    @else
                        <div id="loading-models" class="text-center py-20">
                            <div class="spinner-grow text-indigo-400 mb-3" role="status"></div>
                            <p class="text-sm font-bold text-slate-500">Menyiapkan Sistem...</p>
                        </div>
                    @endif
                </div>

                <!-- Content Column -->
                <div class="col-lg-6 p-4 p-md-5 bg-white">
                    <div class="mb-8">
                        <span class="badge badge-soft-indigo mb-3 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest">Biometrik AI</span>
                        <h2 class="font-black text-slate-800 tracking-tight mb-2">Registrasi Wajah</h2>
                        <p class="text-sm text-slate-500 leading-relaxed">Daftarkan data wajah Anda untuk mempermudah proses presensi harian secara otomatis dan aman.</p>
                    </div>

                    <!-- Simplified Steps -->
                    <div class="space-y-4 mb-8">
                        <div class="d-flex align-items-center p-3 rounded-2xl bg-slate-50 border border-slate-100 transition-all" id="step-1-box">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo flex items-center justify-center mr-4">
                                <i class="fas fa-video text-xs"></i>
                            </div>
                            <div class="flex-grow">
                                <h6 class="mb-0 text-xs font-black text-slate-700">Akses Kamera</h6>
                                <p class="text-[9px] text-slate-400 mb-0">Pastikan izin kamera sudah diberikan</p>
                            </div>
                            <i class="fas fa-check-circle text-emerald-500 hidden" id="step-1-check"></i>
                        </div>
                        <div class="d-flex align-items-center p-3 rounded-2xl bg-white border border-slate-50 transition-all" id="step-2-box">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mr-4">
                                <i class="fas fa-search text-xs"></i>
                            </div>
                            <div class="flex-grow">
                                <h6 class="mb-0 text-xs font-black text-slate-400">Deteksi Wajah</h6>
                                <p class="text-[9px] text-slate-300 mb-0">Hadap ke kamera dengan posisi tegak</p>
                            </div>
                            <i class="fas fa-check-circle text-emerald-500 hidden" id="step-2-check"></i>
                        </div>
                    </div>

                    <div class="border-top pt-6">
                        <button id="btn-capture" disabled class="btn btn-indigo btn-block py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 transition-all active:scale-95">
                            DAFTARKAN WAJAH
                        </button>
                    </div>

                    <div id="success-container" class="hidden mt-6 animate__animated animate__fadeInUp">
                        <div class="p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 flex items-center space-x-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-500 shadow-sm">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-black text-xs uppercase">Berhasil Tersimpan</h6>
                                <p class="text-[10px] font-bold opacity-80 mb-0">Halaman akan dimuat ulang...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Helpful Tips -->
        <div class="row mt-4 justify-content-center">
            <div class="col-md-8 text-center">
                <div class="d-inline-flex align-items-center space-x-6 text-slate-400 py-3 px-6 bg-white/50 rounded-full border border-white">
                    <span class="text-[9px] font-bold uppercase tracking-widest"><i class="fas fa-sun mr-2 text-warning"></i> Cahaya Terang</span>
                    <span class="text-[9px] font-bold uppercase tracking-widest"><i class="fas fa-user mr-2 text-indigo"></i> Tanpa Masker</span>
                    <span class="text-[9px] font-bold uppercase tracking-widest"><i class="fas fa-crosshairs mr-2 text-danger"></i> Posisi Tengah</span>
                </div>
            </div>
        </div>
    </div>
</div>

        @if($faceDescriptor)
        <!-- RE-REGISTRATION NOTICE -->
        <div class="card shadow-sm border-0 premium-card bg-soft-warning">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="mr-3 p-3 bg-white rounded-xl shadow-xs">
                        <i class="fas fa-sync-alt text-warning fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-1">Butuh Update Wajah?</h6>
                        <p class="text-xs text-muted mb-0">Jika Anda merasa akurasi deteksi kurang baik (misal karena perubahan penampilan), Anda dapat melakukan registrasi ulang kapan saja.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

<style>
    /* Premium Themes & Effects */
    .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; }
    .text-indigo { color: #6366f1 !important; }
    .bg-indigo { background-color: #6366f1 !important; }
    .btn-indigo { background-color: #6366f1; color: #fff; border: none; }
    .btn-indigo:hover { background-color: #4f46e5; color: #fff; }
    .bg-indigo-50 { background-color: #eef2ff; }
    .bg-soft-indigo { background-color: #f5f3ff; color: #8b5cf6; }
    .border-left-indigo-thick { border-left: 5px solid #6366f1 !important; }
    
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Card Styling */
    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }

    /* Badge Styling */
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%;
        background: #6366f1; color: #fff; font-size: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-premium { border-radius: 12px; letter-spacing: 1px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 8px 20px rgba(99,102,241,0.2); }
    
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .rounded-xl { border-radius: 15px !important; }
    /* Simplified Scan Line */
    .scan-line-simple {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: #6366f1;
        box-shadow: 0 0 10px #6366f1;
        z-index: 10;
        animation: scan-simple 3s linear infinite;
        opacity: 0.5;
        display: none;
    }
    #camera-container:not(.hidden) .scan-line-simple { display: block; }
    @keyframes scan-simple {
        0% { top: 0; }
        100% { top: 100%; }
    }

    .badge-soft-indigo { background-color: #eef2ff; color: #4f46e5; }
    .bg-soft-success { background-color: #ecfdf5; color: #059669; }
    .bg-soft-warning { background-color: #fffbeb; }
    .bg-soft-danger { background-color: #fef2f2; }
    .badge-soft-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-soft-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    
    .hidden { display: none !important; }
    .absolute { position: absolute; }
    .relative { position: relative; }
    .top-0 { top: 0; }
    .left-0 { left: 0; }
    .w-100 { width: 100%; }
    .h-100 { height: 100%; }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const video = document.getElementById('video');
    const statusText = document.getElementById('status-text');
    const btnCapture = document.getElementById('btn-capture');
    let isModelsLoaded = false;
    let faceDescriptor = null;

    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
    ]).then(startApp);

    async function startApp() {
        try {
            isModelsLoaded = true;
            document.getElementById('loading-models').classList.add('hidden');
            const cameraContainer = document.getElementById('camera-container');
            if (cameraContainer) cameraContainer.classList.remove('hidden');
            
            // Step 1 Complete
            const step1Box = document.getElementById('step-1-box');
            const step1Check = document.getElementById('step-1-check');
            if(step1Box) {
                step1Box.classList.add('bg-indigo-50/50', 'border-indigo-100');
                step1Check.classList.remove('hidden');
            }

            startVideo();
        } catch (err) {
            console.error(err);
            Swal.fire('Gagal', 'Gagal memuat model AI.', 'error');
        }
    }

    function startVideo() {
        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => { video.srcObject = stream; })
            .catch(err => { 
                console.error(err);
                Swal.fire('Error', 'Kamera tidak dapat diakses.', 'error');
            });
    }

    if(video) {
        video.addEventListener('play', () => {
            const canvas = document.getElementById('overlay');
            const displaySize = { width: video.clientWidth, height: video.clientHeight };
            faceapi.matchDimensions(canvas, displaySize);

            setInterval(async () => {
                if (!isModelsLoaded) return;
                
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptors();
                
                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                
                if (detections.length > 0) {
                    statusText.innerText = 'Wajah Terdeteksi';
                    statusText.classList.replace('text-slate-500', 'text-indigo-600');
                    
                    // Step 2 Complete
                    const step2Box = document.getElementById('step-2-box');
                    const step2Check = document.getElementById('step-2-check');
                    if(step2Box && step2Check.classList.contains('hidden')) {
                        step2Box.classList.add('bg-indigo-50/50', 'border-indigo-100');
                        step2Check.classList.remove('hidden');
                    }

                    btnCapture.disabled = false;
                    faceDescriptor = detections[0].descriptor;
                } else {
                    statusText.innerText = 'Posisikan Wajah Anda';
                    statusText.classList.replace('text-indigo-600', 'text-slate-500');
                    btnCapture.disabled = true;
                }
            }, 500);
        });
    }

    btnCapture.addEventListener('click', async () => {
        if (!faceDescriptor) return;

        btnCapture.disabled = true;
        btnCapture.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

        // Capture Image from Video
        const canvasImage = document.createElement('canvas');
        canvasImage.width = video.videoWidth;
        canvasImage.height = video.videoHeight;
        canvasImage.getContext('2d').drawImage(video, 0, 0);
        const imageData = canvasImage.toDataURL('image/jpeg');

        try {
            const response = await fetch('{{ route("teacher.face.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    descriptors: JSON.stringify(Array.from(faceDescriptor)),
                    image: imageData
                })
            });

            const result = await response.json();
            if (response.ok) {
                document.getElementById('success-container').classList.remove('hidden');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(result.message || 'Gagal menyimpan data.');
            }
        } catch (err) {
            console.error(err);
            Swal.fire('Gagal', err.message || 'Terjadi kesalahan saat menyimpan data.', 'error');
            btnCapture.disabled = false;
            btnCapture.innerHTML = 'DAFTARKAN WAJAH';
        }
    });
</script>
@endpush
@endsection
