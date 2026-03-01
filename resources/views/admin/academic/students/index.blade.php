@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

    <style>
        .maintenance-box {
            max-width: 700px;
            margin: 60px auto;
            padding: 50px 40px;
            border: 1px solid #d6e0f0;
            border-top: 5px solid #1e3a8a;
            border-radius: 8px;
            background: #ffffff;
            text-align: center;
        }

        .maintenance-logo {
            width: 80px;
            margin-bottom: 20px;
        }

        .maintenance-title {
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .maintenance-desc {
            color: #444;
            line-height: 1.7;
        }

        .countdown-box {
            margin-top: 25px;
            padding: 12px 30px;
            border: 1px solid #1e3a8a;
            border-radius: 6px;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 2px;
            color: #1e3a8a;
            display: inline-block;
            background: #f8fbff;
        }

        .btn-back {
            margin-top: 30px;
            padding: 8px 25px;
            border-radius: 4px;
        }

        .footer-text {
            margin-top: 35px;
            font-size: 13px;
            color: #777;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">

            <x-card class="border-0 shadow-sm">

                <div class="maintenance-box">

                    {{-- LOGO SEKOLAH --}}
                    <img src="{{ asset('images/logo-sekolah.png') }}" class="maintenance-logo" alt="Logo Sekolah">

                    <h4 class="maintenance-title">
                        PEMBERITAHUAN PEMELIHARAAN SISTEM
                    </h4>

                    <p class="maintenance-desc">
                        Modul <strong>Data Siswa</strong> saat ini sedang dalam proses
                        pemeliharaan dan peningkatan sistem guna mendukung
                        layanan administrasi akademik yang lebih optimal.
                        <br><br>
                        Kami mohon maaf atas ketidaknyamanan yang terjadi.
                    </p>

                    {{-- COUNTDOWN --}}
                    <div class="countdown-box" id="countdown">
                        00:00:00
                    </div>

                    <div>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-back">
                            Kembali ke Halaman Sebelumnya
                        </a>
                    </div>

                    <div class="footer-text">
                        © {{ date('Y') }} Sistem Informasi Manajemen Sekolah
                    </div>

                </div>

            </x-card>

        </div>
    </div>

    <script>
        const finishTime = new Date("{{ now()->addHours(2)->format('Y-m-d H:i:s') }}").getTime();
        const countdown = document.getElementById("countdown");

        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = finishTime - now;

            if (distance < 0) {
                clearInterval(timer);
                countdown.innerHTML = "SEGERA AKTIF";
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdown.innerHTML =
                String(hours).padStart(2, '0') + ":" +
                String(minutes).padStart(2, '0') + ":" +
                String(seconds).padStart(2, '0');
        }, 1000);
    </script>

@endsection
