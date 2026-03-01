<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Maintenance Mode</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Lottie --}}
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            color: white;
        }

        .maintenance-container {
            text-align: center;
            max-width: 600px;
            padding: 40px;
        }

        .logo {
            width: 90px;
            margin-bottom: 20px;
        }

        .card-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        .countdown {
            font-size: 28px;
            font-weight: bold;
            margin-top: 20px;
            letter-spacing: 2px;
        }

        .small-text {
            opacity: 0.8;
            font-size: 14px;
        }

        .btn-refresh {
            margin-top: 25px;
            border-radius: 50px;
            padding: 10px 30px;
        }
    </style>
</head>

<body>

    <div class="maintenance-container">

        <div class="card-box">

            {{-- LOTTIE ANIMATION --}}
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_usmfx6bp.json" background="transparent"
                speed="1" style="width: 200px; height: 200px; margin:auto;" loop autoplay>
            </lottie-player>

            <h3 class="mt-3 fw-bold">Sistem Sedang Dalam Perbaikan</h3>

            <p class="mt-3">
                Kami sedang melakukan peningkatan sistem untuk meningkatkan kualitas layanan akademik.
            </p>

            {{-- COUNTDOWN --}}
            <div class="countdown" id="countdown">
                00 : 00 : 00
            </div>

            <div class="small-text mt-2">
                Estimasi selesai: <strong id="finish-date"></strong>
            </div>

            <a href="/" class="btn btn-light btn-refresh">
                Refresh Halaman
            </a>

            <div class="mt-4 small-text">
                © {{ date('Y') }} Sistem Manajemen Sekolah
            </div>

        </div>
    </div>

    <script>
        // ======= SET WAKTU SELESAI =======
        const finishTime = new Date("{{ now()->addHours(3)->format('Y-m-d H:i:s') }}").getTime();

        const finishText = new Date(finishTime);
        document.getElementById("finish-date").innerHTML =
            finishText.toLocaleString("id-ID");

        const countdownElement = document.getElementById("countdown");

        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = finishTime - now;

            if (distance < 0) {
                clearInterval(timer);
                countdownElement.innerHTML = "SELESAI";
                return;
            }

            const hours = Math.floor((distance / (1000 * 60 * 60)));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownElement.innerHTML =
                String(hours).padStart(2, '0') + " : " +
                String(minutes).padStart(2, '0') + " : " +
                String(seconds).padStart(2, '0');

        }, 1000);
    </script>

</body>

</html>
