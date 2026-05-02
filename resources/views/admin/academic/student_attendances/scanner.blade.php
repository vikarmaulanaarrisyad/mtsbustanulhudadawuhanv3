@extends($layout)

@section('title', 'Scanner Presensi Siswa')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-6">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-qrcode mr-1"></i> Scan QR Code Siswa</h3>
            </x-slot>

            <div id="reader" style="width: 100%; border: none;"></div>
            
            <div class="text-center mt-3">
                <p id="scan-status" class="text-muted">Arahkan QR Code ke Kamera</p>
                <button id="btn-stop" class="btn btn-danger btn-sm d-none" onclick="stopScanner()">Stop Kamera</button>
                <button id="btn-start" class="btn btn-success btn-sm" onclick="startScanner()">Mulai Kamera</button>
            </div>
        </x-card>
    </div>

    <div class="col-md-6">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Scan Terakhir</h3>
            </x-slot>

            <div id="scan-result-box" class="text-center py-5 d-none">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h3 id="res-nama" class="mb-0">NAMA SISWA</h3>
                <p id="res-kelas" class="text-muted">KELAS</p>
                <div class="badge badge-success p-2" id="res-status">HADIR</div>
                <p class="mt-2" id="res-waktu">10:00:00</p>
            </div>

            <div id="scan-placeholder" class="text-center py-5">
                <i class="fas fa-camera fa-4x text-light mb-3"></i>
                <p class="text-muted">Belum ada data scan.</p>
            </div>
            
            <audio id="beep-success" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3" preload="auto"></audio>
            <audio id="beep-error" src="https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3" preload="auto"></audio>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;
    const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

    function startScanner() {
        $('#btn-start').addClass('d-none');
        $('#btn-stop').removeClass('d-none');
        $('#scan-status').text('Mencari Kamera...');

        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, 
            qrConfig,
            onScanSuccess,
            onScanError
        ).catch(err => {
            Swal.fire('Error', 'Gagal mengakses kamera: ' + err, 'error');
            stopScanner();
        });
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                $('#btn-stop').addClass('d-none');
                $('#btn-start').removeClass('d-none');
                $('#scan-status').text('Kamera Berhenti');
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
            if (response.status === 'warning') {
                toastr.warning(response.message);
            } else {
                toastr.success(response.message);
            }
        })
        .fail(xhr => {
            document.getElementById('beep-error').play();
            toastr.error(xhr.responseJSON?.message || 'Gagal mengenali kode.');
        })
        .always(() => {
            setTimeout(() => {
                isProcessing = false;
                $('#scan-status').text('Siap Menscan Berikutnya');
            }, 2000); // Cooldown 2 seconds
        });
    }

    function onScanError(errorMessage) {
        // quiet fail for continuous scanning
    }

    function showResult(data) {
        $('#scan-placeholder').addClass('d-none');
        $('#scan-result-box').removeClass('d-none');
        $('#res-nama').text(data.nama);
        $('#res-kelas').text(data.kelas);
        $('#res-waktu').text(data.waktu);
        $('#res-status').text(data.status).attr('class', 'badge p-2 badge-' + (data.status === 'Terlambat' ? 'warning' : 'success'));
        
        // Auto hide result after 5 seconds
        setTimeout(() => {
            $('#scan-result-box').addClass('d-none');
            $('#scan-placeholder').removeClass('d-none');
        }, 5000);
    }
</script>
@endpush
