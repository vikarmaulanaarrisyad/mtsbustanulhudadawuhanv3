<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Website Sekolah') }}</title>

    {{-- Bootstrap & Font Awesome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
        }

        /* Header Atas */
        .topbar {
            background: #28a745;
            color: #fff;
            font-size: 14px;
            padding: 5px 0;
        }

        .topbar a {
            color: #fff;
            margin-left: 15px;
            transition: 0.3s;
        }

        .topbar a:hover {
            color: #d4ffd6;
        }

        /* Navbar */
        .navbar {
            transition: all .3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        #navbar1 .nav-link {
            font-weight: 500;
            position: relative;
            margin-left: 10px;
        }

        #navbar1 .nav-link::after {
            content: '';
            position: absolute;
            width: 0%;
            height: 2px;
            background: #28a745;
            bottom: -5px;
            left: 0;
            transition: 0.3s;
        }

        #navbar1 .nav-link:hover::after,
        #navbar1 .nav-link.active::after {
            width: 100%;
        }

        /* Dropdown hover */
        .navbar .dropdown:hover>.dropdown-menu {
            display: block;
            animation: fadeIn .4s ease-in-out;
        }

        .dropdown-menu {
            margin-top: 0;
            border-radius: 0 0 8px 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Carousel */
        .carousel-item img {
            height: 70vh;
            object-fit: cover;
        }

        .carousel-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1;
        }

        .carousel-caption {
            z-index: 2;
        }

        .carousel-caption h5,
        .carousel-caption p {
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
        }

        /* Breaking News */
        .breaking-news {
            background: #28a745;
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .breaking-news .news-text {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%;
            animation: scroll-left 15s linear infinite;
            font-weight: 500;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* Section Berita */
        .news .card {
            transition: 0.3s;
            border-radius: 8px;
            overflow: hidden;
        }

        .news .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .news .card img {
            height: 200px;
            object-fit: cover;
        }

        /* Footer */
        .footer {
            background: #1d1d1d;
            color: rgba(255, 255, 255, 0.7);
            padding: 40px 0;
            text-align: center;
            border-top: 3px solid #28a745;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            transition: .3s;
        }

        .footer a:hover {
            color: #fff;
        }
    </style>
</head>

<body>

    {{-- Header Atas --}}
    <div class="topbar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="fa fa-map-marker-alt"></i> Jl. Pendidikan No. 123, Jakarta
                <span class="ml-3"><i class="fa fa-phone"></i> (021) 123-4567</span>
                <span class="ml-3"><i class="fa fa-envelope"></i> info@sekolahkita.sch.id</span>
            </div>
            <div>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-sm sticky-top navbar-light bg-white border-bottom" style="top: -1px;">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-success" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height:40px;">
                {{ config('app.name', 'Sekolah Kita') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1"
                aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar1">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link @if (request()->is('/')) active @endif"
                            href="{{ url('/') }}">Home</a>
                    </li>

                    {{-- Dropdown Profil --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profilDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Profil
                        </a>
                        <div class="dropdown-menu" aria-labelledby="profilDropdown">
                            <a class="dropdown-item" href="#">Visi & Misi</a>
                            <a class="dropdown-item" href="#">Sejarah</a>
                            <a class="dropdown-item" href="#">Guru & Staff</a>
                        </div>
                    </li>

                    {{-- Dropdown Akademik --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="akademikDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Akademik
                        </a>
                        <div class="dropdown-menu" aria-labelledby="akademikDropdown">
                            <a class="dropdown-item" href="#">Kurikulum</a>
                            <a class="dropdown-item" href="#">Jadwal Pelajaran</a>
                            <a class="dropdown-item" href="#">Kalender Akademik</a>
                        </div>
                    </li>

                    {{-- Dropdown Ekstrakurikuler --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="ekstraDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Ekstrakurikuler
                        </a>
                        <div class="dropdown-menu" aria-labelledby="ekstraDropdown">
                            <a class="dropdown-item" href="#">Olahraga</a>
                            <a class="dropdown-item" href="#">Seni</a>
                            <a class="dropdown-item" href="#">Pramuka</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if (request()->is('contact')) active @endif"
                            href="{{ url('/contact') }}">Kontak</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if (request()->is('ppdb')) active @endif"
                            href="{{ url('/ppdb') }}">PPDB</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Carousel --}}
    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
            <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('img/bg.png') }}" class="d-block w-100" alt="Slider 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Kegiatan Belajar</h5>
                    <p>Suasana kelas yang interaktif dan menyenangkan.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/bg-login.jpg') }}" class="d-block w-100" alt="Slider 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Kegiatan Ekstrakurikuler</h5>
                    <p>Mengembangkan bakat dan minat siswa.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/bgcharity1.jpg') }}" class="d-block w-100" alt="Slider 3">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Prestasi Siswa</h5>
                    <p>Meraih prestasi di tingkat daerah dan nasional.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>

    {{-- Breaking News --}}
    <div class="breaking-news py-2">
        <div class="container">
            <div class="news-text">
                📢 Penerimaan Peserta Didik Baru (PPDB) tahun ajaran {{ date('Y') }}/{{ date('Y') + 1 }} telah
                dibuka! |
                🏆 Selamat kepada siswa-siswi yang meraih juara lomba sains tingkat provinsi. |
                🎉 Terima kasih kepada seluruh orang tua yang mendukung kegiatan sekolah.
            </div>
        </div>
    </div>

    {{-- Section Sambutan & Statistik --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                {{-- Kolom Kiri: Statistik Sekolah --}}
                <div class="col-md-6 mb-4">
                    <h2 class="font-weight-bold text-success mb-4">Statistik Sekolah</h2>
                    <div class="row text-center">
                        <div class="col-6 mb-4">
                            <div class="p-4 shadow-sm border rounded">
                                <h3 class="text-success font-weight-bold">850+</h3>
                                <p class="text-muted mb-0">Siswa</p>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="p-4 shadow-sm border rounded">
                                <h3 class="text-success font-weight-bold">55</h3>
                                <p class="text-muted mb-0">Guru & Staff</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 shadow-sm border rounded">
                                <h3 class="text-success font-weight-bold">25</h3>
                                <p class="text-muted mb-0">Kelas</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 shadow-sm border rounded">
                                <h3 class="text-success font-weight-bold">30+</h3>
                                <p class="text-muted mb-0">Prestasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Sambutan Kepala Sekolah --}}
                <div class="col-md-6 mb-4">
                    <h2 class="font-weight-bold text-success mb-4">Sambutan Kepala Sekolah</h2>
                    <div class="media">
                        <img src="{{ asset('img/kepala-sekolah.jpg') }}" alt="Kepala Sekolah"
                            class="mr-3 rounded-circle shadow" width="100">
                        <div class="media-body">
                            <p class="text-muted">
                                Assalamu’alaikum warahmatullahi wabarakatuh,<br><br>
                                Selamat datang di website resmi <strong>{{ config('app.name') }}</strong>.
                                Kami berkomitmen memberikan pendidikan berkualitas, membentuk karakter,
                                dan menyiapkan generasi unggul untuk masa depan bangsa.
                            </p>
                            <p class="mb-0 font-italic">- Kepala Sekolah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Berita --}}
    <section class="news py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Berita Terbaru</h2>
                <p class="text-muted">Informasi, kegiatan, dan pengumuman terbaru dari sekolah kami</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/bg.png') }}" class="card-img-top" alt="Berita 1">
                        <div class="card-body">
                            <h5 class="card-title">Judul Berita 1</h5>
                            <p class="card-text text-muted">Ringkasan singkat berita atau kegiatan sekolah...</p>
                            <a href="#" class="btn btn-sm btn-success">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/bg-login.jpg') }}" class="card-img-top" alt="Berita 2">
                        <div class="card-body">
                            <h5 class="card-title">Judul Berita 2</h5>
                            <p class="card-text text-muted">Ringkasan singkat berita atau kegiatan sekolah...</p>
                            <a href="#" class="btn btn-sm btn-success">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/bgcharity1.jpg') }}" class="card-img-top" alt="Berita 3">
                        <div class="card-body">
                            <h5 class="card-title">Judul Berita 3</h5>
                            <p class="card-text text-muted">Ringkasan singkat berita atau kegiatan sekolah...</p>
                            <a href="#" class="btn btn-sm btn-success">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Kegiatan --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Kegiatan Sekolah</h2>
                <p class="text-muted">Berbagai kegiatan rutin dan acara khusus yang diadakan sekolah</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/kegiatan1.jpg') }}" class="card-img-top" alt="Kegiatan 1">
                        <div class="card-body">
                            <h5 class="card-title">Upacara Bendera</h5>
                            <p class="card-text text-muted">Kegiatan rutin setiap hari Senin sebagai bentuk
                                kedisiplinan siswa.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/kegiatan2.jpg') }}" class="card-img-top" alt="Kegiatan 2">
                        <div class="card-body">
                            <h5 class="card-title">Pentas Seni</h5>
                            <p class="card-text text-muted">Ajang kreativitas siswa dalam bidang seni musik, tari, dan
                                drama.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/kegiatan3.jpg') }}" class="card-img-top" alt="Kegiatan 3">
                        <div class="card-body">
                            <h5 class="card-title">Lomba Sains</h5>
                            <p class="card-text text-muted">Partisipasi aktif siswa dalam lomba sains tingkat daerah &
                                nasional.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Ekstrakurikuler --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Ekstrakurikuler</h2>
                <p class="text-muted">Kegiatan pengembangan bakat dan minat siswa</p>
            </div>
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="fas fa-futbol fa-3x text-success mb-3"></i>
                            <h5>Olahraga</h5>
                            <p class="text-muted">Sepakbola, basket, voli, bulu tangkis, dan lainnya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="fas fa-paint-brush fa-3x text-success mb-3"></i>
                            <h5>Seni</h5>
                            <p class="text-muted">Kegiatan seni rupa, musik, tari, dan teater.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="fas fa-campground fa-3x text-success mb-3"></i>
                            <h5>Pramuka</h5>
                            <p class="text-muted">Membentuk karakter disiplin, mandiri, dan kebersamaan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="fas fa-robot fa-3x text-success mb-3"></i>
                            <h5>Robotik</h5>
                            <p class="text-muted">Melatih kreativitas dan keterampilan teknologi modern.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Agenda Sekolah --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Agenda Sekolah</h2>
                <p class="text-muted">Jadwal kegiatan dan acara penting sekolah</p>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-success text-white">
                        <tr>
                            <th style="width: 120px;">Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success"></i> 12 Sept 2025</td>
                            <td>Masa Pengenalan Lingkungan Sekolah (MPLS)</td>
                            <td>Aula Utama</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success"></i> 20 Sept 2025</td>
                            <td>Pentas Seni & Budaya</td>
                            <td>Lapangan Sekolah</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success"></i> 5 Okt 2025</td>
                            <td>Lomba Olimpiade Sains</td>
                            <td>Laboratorium IPA</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success"></i> 15 Okt 2025</td>
                            <td>Rapat Orang Tua Siswa</td>
                            <td>Ruang Rapat</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success"></i> 1 Nov 2025</td>
                            <td>Perkemahan Pramuka</td>
                            <td>Bumi Perkemahan Cibubur</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Sambutan Kepala Sekolah --}}
    <section class="py-5 bg-white text-center">
        <div class="container">
            <h2 class="font-weight-bold text-success mb-4">Sambutan Kepala Sekolah</h2>
            <img src="{{ asset('img/kepsek.jpg') }}" alt="Kepala Sekolah" class="rounded-circle mb-3"
                width="150">
            <p class="text-muted">
                Assalamualaikum Wr. Wb. Selamat datang di website resmi {{ config('app.name', 'Sekolah Kita') }}.
                Kami berkomitmen memberikan pendidikan terbaik, membentuk karakter, serta mencetak generasi berprestasi.
            </p>
            <h5 class="font-weight-bold mt-3">Drs. Budi Santoso</h5>
            <p>Kepala Sekolah</p>
        </div>
    </section>

    {{-- Statistik Sekolah --}}
    <section class="py-5 bg-success text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <i class="fa fa-users fa-2x mb-2"></i>
                    <h3 class="counter">1200+</h3>
                    <p>Siswa</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="fa fa-user-tie fa-2x mb-2"></i>
                    <h3 class="counter">80+</h3>
                    <p>Guru & Staff</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="fa fa-graduation-cap fa-2x mb-2"></i>
                    <h3 class="counter">500+</h3>
                    <p>Alumni</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="fa fa-trophy fa-2x mb-2"></i>
                    <h3 class="counter">50+</h3>
                    <p>Prestasi</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Prestasi Siswa --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Prestasi Siswa</h2>
                <p class="text-muted">Beberapa prestasi terbaru yang diraih siswa-siswi kami</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/prestasi1.jpg') }}" class="card-img-top" alt="Prestasi 1">
                        <div class="card-body">
                            <h5 class="card-title">Juara 1 Olimpiade Sains</h5>
                            <p class="card-text">Diraih oleh siswa kelas IX dalam lomba tingkat nasional.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/prestasi2.jpg') }}" class="card-img-top" alt="Prestasi 2">
                        <div class="card-body">
                            <h5 class="card-title">Juara 2 Basket</h5>
                            <p class="card-text">Tim basket sekolah meraih prestasi di kejuaraan provinsi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset('img/prestasi3.jpg') }}" class="card-img-top" alt="Prestasi 3">
                        <div class="card-body">
                            <h5 class="card-title">Juara 3 Lomba Musik</h5>
                            <p class="card-text">Ekstrakurikuler musik mendapat penghargaan tingkat kota.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Lokasi Sekolah --}}
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="font-weight-bold text-success mb-4">Lokasi Sekolah</h2>
            <div class="embed-responsive embed-responsive-16by9 shadow-sm">
                <iframe class="embed-responsive-item"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.044078515333!2d110.41496341477567!3d-7.868699694322847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwNTInMDcuMyJTIDExMMKwMjQnNTQuMCJF!5e0!3m2!1sid!2sid!4v1631010101010!5m2!1sid!2sid"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    {{-- Formulir PPDB Online --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="font-weight-bold text-success">Formulir PPDB Online</h2>
                <p class="text-muted">Isi formulir berikut untuk mendaftar siswa baru</p>
            </div>
            <form class="col-md-8 mx-auto shadow p-4 bg-light rounded">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                </div>
                <div class="form-group">
                    <label>Email / No. HP</label>
                    <input type="text" class="form-control" placeholder="Masukkan email atau nomor HP">
                </div>
                <div class="form-group">
                    <label>Asal Sekolah</label>
                    <input type="text" class="form-control" placeholder="Masukkan asal sekolah">
                </div>
                <div class="form-group">
                    <label>Pilihan Jurusan</label>
                    <select class="form-control">
                        <option>IPA</option>
                        <option>IPS</option>
                        <option>Bahasa</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success btn-block">Daftar Sekarang</button>
            </form>
        </div>
    </section>


    {{-- Footer --}}
    <div class="footer">
        <div class="container">
            <p class="mb-1">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ url('/about') }}">Tentang Kami</a> |
                <a href="{{ url('/contact') }}">Kontak</a> |
                <a href="{{ url('/ppdb') }}">PPDB</a>
            </p>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
