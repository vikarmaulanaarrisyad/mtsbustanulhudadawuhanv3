<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Scanner - Verifikasi Berkas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981;
            --bg: #0f172a;
        }
        body {
            margin: 0; padding: 0;
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }
        .scanner-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            text-align: center;
        }
        #reader {
            width: 100%;
            background: black;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.1);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        .header {
            margin-bottom: 30px;
        }
        .header h1 { font-size: 1.5rem; margin-bottom: 5px; color: var(--primary); }
        .header p { font-size: 0.9rem; opacity: 0.7; }

        .status-box {
            margin-top: 30px;
            padding: 15px;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            font-size: 0.85rem;
        }
        .btn-logout {
            margin-top: 40px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: #ef4444;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }
        
        /* Custom QR Scanner Overlay */
        #reader__dashboard_section_csr button {
            background: var(--primary) !important;
            color: white !important;
            border: none !important;
            padding: 10px 20px !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            margin-top: 10px !important;
        }
    </style>
</head>
<body>
    <div class="scanner-container">
        <div class="header">
            <i class="fas fa-qrcode fa-3x mb-3" style="color: var(--primary)"></i>
            <h1>PPDB SCANNER</h1>
            <p>Arahkan kamera ke QR Code di Bukti Pendaftaran</p>
        </div>

        <div id="reader"></div>

        <div class="status-box">
            <i class="fas fa-info-circle mr-2"></i> Petugas: <strong>{{ auth()->user()->name }}</strong>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar Aplikasi
            </button>
        </form>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Berhenti scan sementara agar tidak looping
            html5QrcodeScanner.clear();
            
            // Suara bip (optional)
            // let audio = new Audio('https://www.soundjay.com/button/beep-07.wav');
            // audio.play();

            Swal.fire({
                title: 'QR Terdeteksi!',
                text: 'Membuka halaman verifikasi...',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Arahkan ke URL hasil scan (karena QR berisi link route ppdb.check_verify)
                window.location.href = decodedText;
            });
        }

        function onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
                aspectRatio: 1.0
            }, 
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>
</html>
