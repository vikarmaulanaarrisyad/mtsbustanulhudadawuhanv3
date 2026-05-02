@extends($layout)

@section('title', 'Scanner Presensi')

@section('content')
<div class="min-h-screen bg-slate-900 pb-24 overflow-hidden relative">
    <!-- Premium Scanner Header -->
    <div class="absolute top-0 left-0 right-0 z-50 bg-gradient-to-b from-slate-900/80 to-transparent pt-10 pb-12 px-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/20 active:scale-90 transition-all">
                    <i class="fas fa-chevron-left text-sm"></i>
                </a>
                <div>
                    <p class="text-emerald-400 text-[10px] font-black uppercase tracking-widest leading-none mb-1">Mode Kamera</p>
                    <h1 class="text-white text-lg font-black leading-tight">Scan Kartu Siswa</h1>
                </div>
            </div>
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                <i class="fas fa-qrcode"></i>
            </div>
        </div>
    </div>

    <!-- Scanner Viewport -->
    <div class="absolute inset-0 flex items-center justify-center bg-black">
        <div id="reader" class="w-full h-full"></div>
        
        <!-- Scanner Overlay Decoration -->
        <div class="absolute inset-0 z-10 pointer-events-none flex flex-col items-center justify-center">
            <!-- Central Scan Window -->
            <div class="w-72 h-72 border-2 border-white/20 rounded-[3rem] relative overflow-hidden shadow-[0_0_0_2000px_rgba(15,23,42,0.6)]">
                <!-- Moving Line Animation -->
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-emerald-400 shadow-[0_0_15px_rgba(52,211,153,1)] animate-scan-line"></div>
                
                <!-- Corner Borders -->
                <div class="absolute top-6 left-6 w-8 h-8 border-t-4 border-l-4 border-emerald-500 rounded-tl-xl"></div>
                <div class="absolute top-6 right-6 w-8 h-8 border-t-4 border-r-4 border-emerald-500 rounded-tr-xl"></div>
                <div class="absolute bottom-6 left-6 w-8 h-8 border-b-4 border-l-4 border-emerald-500 rounded-bl-xl"></div>
                <div class="absolute bottom-6 right-6 w-8 h-8 border-b-4 border-r-4 border-emerald-500 rounded-br-xl"></div>
            </div>
            
            <p class="mt-12 text-white/60 text-[10px] font-black uppercase tracking-[0.3em] bg-black/40 backdrop-blur-md px-6 py-2 rounded-full border border-white/10" id="scan-status">Arahkan ke QR Code</p>
        </div>
    </div>

    <!-- Floating Result Card (Appears on Success) -->
    <div id="scan-result-card" class="fixed bottom-32 left-6 right-6 z-[100] transform translate-y-64 transition-all duration-500 ease-out opacity-0 pointer-events-none">
        <div class="bg-white rounded-[2.5rem] p-6 shadow-2xl border border-white shadow-emerald-500/10">
            <div class="flex items-center space-x-5">
                <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-emerald-500 text-[9px] font-black uppercase tracking-widest mb-1">Berhasil Tercatat</p>
                    <h4 id="res-nama" class="text-slate-800 font-black text-lg leading-tight mb-0.5">NAMA SISWA</h4>
                    <p id="res-kelas" class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">KELAS 6 - A</p>
                </div>
                <div class="text-right">
                    <span id="res-waktu" class="block text-slate-800 font-black text-sm mb-1 tracking-tighter">10:45</span>
                    <span id="res-status" class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest">Hadir</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Buttons -->
    <div class="fixed bottom-10 left-0 right-0 z-50 flex justify-center space-x-4">
        <button id="btn-start" onclick="startScanner()" class="bg-emerald-500 text-white px-8 py-4 rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-500/40 active:scale-95 transition-all">
            <i class="fas fa-power-off mr-2"></i> Aktifkan Kamera
        </button>
        <button id="btn-stop" onclick="stopScanner()" class="bg-rose-500 text-white px-8 py-4 rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl shadow-rose-500/40 active:scale-95 transition-all d-none">
            <i class="fas fa-stop mr-2"></i> Matikan
        </button>
    </div>

    <audio id="beep-success" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3" preload="auto"></audio>
    <audio id="beep-error" src="https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3" preload="auto"></audio>
</div>

<style>
    @keyframes scan-line {
        0% { top: 0%; opacity: 0; }
        50% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .animate-scan-line {
        animation: scan-line 2.5s infinite linear;
    }
    #reader video {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
    }
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;
    const qrConfig = { fps: 15, qrbox: { width: 250, height: 250 } };

    $(function() {
        // Auto start if possible
        setTimeout(startScanner, 1000);
    });

    function startScanner() {
        $('#btn-start').addClass('d-none');
        $('#btn-stop').removeClass('d-none');
        $('#scan-status').text('Mengaktifkan Kamera...');

        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, 
            qrConfig,
            onScanSuccess,
            onScanError
        ).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Kamera Gagal',
                text: 'Pastikan izin kamera sudah diberikan.',
                customClass: { popup: 'rounded-[2rem]' }
            });
            stopScanner();
        });
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                $('#btn-stop').addClass('d-none');
                $('#btn-start').removeClass('d-none');
                $('#scan-status').text('Kamera Dimatikan');
            });
        }
    }

    let isProcessing = false;
    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        isProcessing = true;

        $('#scan-status').text('Memproses: ' + decodedText);
        
        $.post('{{ route("student-attendances.scan") }}', {
            _token: '{{ csrf_token() }}',
            qr_code: decodedText
        })
        .done(response => {
            document.getElementById('beep-success').play();
            showResult(response.data);
            
            // Notification toast (optional since we have floating card)
            toastr.success(response.message);
        })
        .fail(xhr => {
            document.getElementById('beep-error').play();
            toastr.error(xhr.responseJSON?.message || 'Gagal mengenali kode.');
        })
        .always(() => {
            setTimeout(() => {
                isProcessing = false;
                $('#scan-status').text('Siap Menscan Berikutnya');
            }, 3000); // Cooldown for next scan
        });
    }

    function onScanError(errorMessage) {
        // quiet fail
    }

    function showResult(data) {
        const card = $('#scan-result-card');
        $('#res-nama').text(data.nama);
        $('#res-kelas').text(data.kelas);
        $('#res-waktu').text(data.waktu);
        $('#res-status').text(data.status);
        
        // Dynamic status color
        if (data.status === 'Terlambat') {
            $('#res-status').attr('class', 'bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest');
        } else {
            $('#res-status').attr('class', 'bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest');
        }

        // Show card with animation
        card.removeClass('opacity-0 translate-y-64 pointer-events-none').addClass('opacity-1 translate-y-0 pointer-events-auto');
        
        // Auto hide result after 4 seconds
        setTimeout(() => {
            card.removeClass('opacity-1 translate-y-0 pointer-events-auto').addClass('opacity-0 translate-y-64 pointer-events-none');
        }, 4000);
    }
</script>
@endpush
