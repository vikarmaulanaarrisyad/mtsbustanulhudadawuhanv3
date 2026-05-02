@extends('layouts.front')
@push('css')
    <style>
        #quote-fade {
            font-size: 1.2rem;
            line-height: 1.6;
            font-weight: 500;
        }

        .fade-quote {
            opacity: 0;
            display: inline-block;
            transition: opacity 1s ease-in-out;
            margin-right: 15px;
            /* Jeda antar quote */
        }

        .fade-quote.visible {
            opacity: 1;
        }

        /* public/css/custom.css atau di <style> */
        .carousel-inner,
        .carousel-item,
        .carousel-item img {
            height: 55%;
            /* tinggi carousel */
        }

        .carousel-item img {
            object-fit: cover;
            /* menjaga gambar proporsional */
        }

        /* Membatasi z-index tabel agar tidak menutupi navbar */
        section.py-5.bg-white.mb-5 {
            position: relative;
            z-index: 1;
            /* pastikan tabel tidak lebih tinggi dari navbar */
        }

        /* Menjaga header tabel tetap terlihat hanya di dalam kotak scroll, bukan di luar */
        .table thead.sticky-top th {
            top: 0;
            z-index: 2;
            /* agar header di atas isi tabel, tapi di bawah navbar */
            background-color: #198754;
            /* warna hijau Bootstrap success */
            color: white;
        }
    </style>

    <style>
        .hover-effect {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: default;
        }

        .hover-effect:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        /* Card hover effect */
        .hover-effect {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: default;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
        }

        .hover-effect:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        /* Gradient border effect */
        .gradient-border {
            position: relative;
            border: 3px solid;
            border-radius: 15px;
            background-clip: padding-box;
            border-color: #04a30f;
        }

        /* Gallery hover */
        .masonry-item:hover .hover-zoom {
            transform: scale(1.05);
        }
        .masonry-item:hover .overlay-text {
            opacity: 1 !important;
        }

        /* Hero Premium Styles */
        .carousel-item {
            height: 85vh !important;
            min-height: 400px;
            background: #000;
        }
        .carousel-item img {
            height: 100% !important;
            opacity: 0.6;
            filter: brightness(0.8);
        }
        .carousel-caption {
            bottom: 30% !important;
            text-align: center;
            z-index: 10;
        }
        .carousel-caption h5 {
            font-size: 3.5rem;
            font-weight: 800;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
            animation: fadeInUp 1s ease-out;
        }
        .carousel-caption p {
            font-size: 1.2rem;
            animation: fadeInUp 1.2s ease-out;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Feature Cards */
        .feature-icon {
            width: 70px;
            height: 70px;
            background: rgba(14, 170, 166, 0.1);
            color: #0eaaa6;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
            font-size: 30px;
            transition: 0.3s;
        }
        .feature-card:hover .feature-icon {
            background: #0eaaa6;
            color: #fff;
            transform: scale(1.1);
        }

        /* Modern Post Cards */
        .post-card {
            border-radius: 20px !important;
            overflow: hidden;
            transition: 0.4s;
        }
        .post-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        .post-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .post-date-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #198754;
            color: #fff;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 5;
        }
    </style>
@endpush


@section('content')
    <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-ride="carousel">
        @if ($sliders->isNotEmpty())
            {{-- Indicators --}}
            <ol class="carousel-indicators">
                @foreach ($sliders as $key => $slider)
                    <li data-target="#carouselExampleCaptions" data-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ol>

            {{-- Slides --}}
            <div class="carousel-inner">
                @foreach ($sliders as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ Storage::url($slider->image) }}" class="d-block w-100" alt="{{ $slider->slug }}">
                        <div class="carousel-caption">
                            <h5 class="display-3 mb-3">{{ $slider->caption }}</h5>
                            <p class="lead mb-4">Membangun Generasi Cerdas, Berakhlak Mulia, dan Berdaya Saing Global.</p>
                            <div class="d-flex justify-content-center" style="gap: 15px;">
                                <a href="#news" class="btn btn-success btn-lg px-4 shadow">Jelajahi Berita</a>
                                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 shadow">Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Fallback slider --}}
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070" class="d-block w-100" alt="Default Slider">
                    <div class="carousel-caption">
                        <h5 class="display-3 mb-3">Selamat Datang di Madrasah Kami</h5>
                        <p class="lead mb-4">Pusat Pendidikan Berkualitas dengan Nilai-Nilai Keislaman.</p>
                        <a href="{{ route('register') }}" class="btn btn-success btn-lg px-5 shadow">Daftar PPDB</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Controls --}}
        <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    {{-- Breaking News --}}
    <div class="breaking-news">
        <div class="news-text">
            @if ($quetes->isNotEmpty())
                <p id="quote-fade">
                    @foreach ($quetes as $q)
                        <span class="fade-quote">{{ $q->quote }}</span>
                    @endforeach
                </p>
            @endif

        </div>
    </div>

    <section class="ppdb-banner py-4 bg-light mt-3">
        <div class="container text-center rounded shadow-sm p-4" style="border-left: 5px solid #198754; background-color: #fff;">
            <h3 class="font-weight-bold text-success mb-3">Informasi PPDB</h3>
            @if(isset($ppdbOpen) && $ppdbOpen)
                <p class="lead mb-4">Pendaftaran Peserta Didik Baru (PPDB) Tahun Ajaran {{ $academicYear->academic_year ?? '' }} <strong>Telah Dibuka!</strong> Segera daftarkan diri Anda dan lengkapi biodata serta berkas persyaratan.</p>
                <div class="d-flex justify-content-center flex-wrap" style="gap: 15px;">
                    <a href="{{ route('register') }}" class="btn btn-success btn-lg shadow-sm">
                        <i class="fa fa-user-plus mr-2"></i> Daftar Akun PPDB Baru
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg shadow-sm">
                        <i class="fa fa-sign-in-alt mr-2"></i> Login Pendaftar
                    </a>
                </div>
            @else
                <div class="alert alert-warning mb-0 border-0 shadow-sm text-dark d-inline-block px-5" role="alert" style="background-color: #fff3cd;">
                    <i class="fa fa-info-circle mr-2 text-warning"></i> Pendaftaran Peserta Didik Baru (PPDB) saat ini sedang <strong>Ditutup</strong>. Silakan pantau terus informasi terbaru.
                </div>
            @endif
        </div>
    </section>

    <section class="features py-5 bg-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="feature-icon"><i class="fa fa-mosque"></i></div>
                        <h4 class="font-weight-bold">Karakter Religius</h4>
                        <p class="text-muted">Menanamkan nilai-nilai keislaman dan akhlakul karimah dalam setiap pembelajaran.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="feature-icon"><i class="fa fa-laptop-code"></i></div>
                        <h4 class="font-weight-bold">Fasilitas IT Modern</h4>
                        <p class="text-muted">Laboratorium komputer lengkap dan akses internet untuk mendukung literasi digital.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="feature-icon"><i class="fa fa-award"></i></div>
                        <h4 class="font-weight-bold">Prestasi Unggul</h4>
                        <p class="text-muted">Terbukti melahirkan siswa berprestasi di bidang akademik maupun non-akademik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="news" class="news py-5 bg-light">
        <div class="px-3">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Berita & Informasi</h2>
                <p class="text-muted">Update berita terbaru dan pengumuman sekolah</p>
            </div>

            <div class="d-flex flex-wrap">
                <!-- Kolom Kiri: Berita Utama -->
                <div class="col-md-8 px-2">
                    <div class="row">
                        @forelse ($posts as $post)
                            <div class="col-md-6 mb-4">
                                <div class="card post-card shadow-sm border-0 h-100">
                                    <div class="post-date-badge">
                                        {{ $post->created_at->format('d M Y') }}
                                    </div>
                                    <img src="{{ Storage::url($post->post_image) }}" class="card-img-top"
                                        alt="{{ $post->post_slug }}">
                                    <div class="card-body">
                                        <h5 class="card-title font-weight-bold text-success mb-3">
                                            {{ Str::limit(strip_tags($post->post_title), 55, '...') }}
                                        </h5>

                                        <p class="card-text text-muted text-justify small line-height-15">
                                            {!! Str::limit(strip_tags($post->post_content), 120, '...') !!}
                                        </p>

                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted"><i class="fa fa-user mr-1"></i> {{ $post->user->name ?? 'Admin' }}</small>
                                            <a href="{{ route('front.post_show', $post->post_slug) }}"
                                                class="btn btn-sm btn-link text-success font-weight-bold p-0">Selengkapnya <i class="fa fa-arrow-right ml-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">Belum ada berita terbaru.</p>
                            </div>
                        @endforelse

                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links('pagination::bootstrap-4') }}
                    </div>
                    <!-- Tombol Lihat Semua -->
                    {{--  <div class="text-center mt-3">
                        <a href="#" class="btn btn-success px-4">
                            Lihat Semua Berita
                        </a>
                    </div>  --}}
                </div>

                <div class="col-lg-4">
                    @include('layouts.partials.sambutan-kepala')

                    <!-- Breaking News -->
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fa fa-bullhorn"></i>
                            <span>Breaking News</span>
                        </div>
                        <div class="sidebar-body">
                            @include('layouts.partials.breaking-news')
                        </div>
                    </div>

                    <!-- Pengumuman -->
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fa fa-info-circle"></i>
                            <span>Pengumuman</span>
                        </div>
                        <div class="sidebar-body">
                            @include('layouts.partials.announcements')
                        </div>
                    </div>

                    <!-- Prestasi -->
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fa fa-trophy"></i>
                            <span>Prestasi</span>
                        </div>
                        <div class="sidebar-body">
                            @include('layouts.partials.prestasi')
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- Galeri dan Video Profil --}}
    <section class="gallery-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-white">Galeri & Profil Sekolah</h2>
                <p class="text-muted mt-3">Dokumentasi kegiatan dan profil video sekolah kami</p>
            </div>
            <div class="row">
                <!-- Video Section -->
                <div class="col-lg-5 mb-4">
                    <div class="card border-0 shadow-lg h-100 rounded overflow-hidden" style="border: 4px solid #fff;">
                        <div class="card-body p-0 d-flex flex-column">
                            <div class="embed-responsive embed-responsive-16by9 flex-grow-1">
                                <iframe class="embed-responsive-item" src="{{ $site_setting->youtube_link ?? 'https://www.youtube.com/embed/ScMzIvxBSi4' }}" allowfullscreen></iframe>
                            </div>
                            <div class="p-3 bg-success text-white text-center">
                                <h5 class="mb-0 font-weight-bold"><i class="fa-brands fa-youtube mr-2 text-danger"></i> Video Profil Sekolah</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Masonry Photo Section -->
                <div class="col-lg-7 mb-4">
                    <div class="card border-0 shadow-sm rounded p-3 h-100 bg-white">
                        <div class="masonry-grid" style="column-count: 2; column-gap: 15px;">
                            @forelse($albums ?? [] as $album)
                                <div class="masonry-item mb-3 shadow-sm" style="break-inside: avoid; position: relative; overflow: hidden; border-radius: 8px;">
                                    <img src="{{ Storage::url($album->album_cover) }}" alt="{{ $album->album_title }}" class="img-fluid w-100 hover-zoom" style="transition: transform 0.3s ease;">
                                    <div class="overlay-text position-absolute w-100 text-center" style="bottom: 0; background: rgba(0,0,0,0.7); color: #fff; padding: 10px; opacity: 0; transition: opacity 0.3s ease;">
                                        <small class="font-weight-bold">{{ $album->album_title }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="w-100 text-center text-muted py-5" style="column-span: all;">
                                    <i class="fa fa-image fa-3x text-light mb-3"></i>
                                    <p>Belum ada foto galeri.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--  Section Statistik Sekolah Profesional  --}}
    <section id="statistik-sekolah" class="py-5" style="background: linear-gradient(135deg, #0eaaa6, #17ccc6);">
        <div class="container">
            <div class="text-center mb-5" style="color: rgb(255, 254, 254);">
                <h2 class="fw-bold text-white shadow-sm d-inline-block px-4 py-2" style="border-radius: 10px; background: rgba(0,0,0,0.1);">Statistik Sekolah</h2>
                <p class="lead mt-3">Beberapa data penting yang menunjukkan prestasi dan kekuatan sekolah kami</p>
            </div>
            <div class="row g-4 justify-content-center">

                <!-- Card Statistik -->
                <div class="col-md-3 mb-4">
                    <div class="card gradient-border bg-white bg-opacity-10 border-0 shadow-lg text-center py-4 px-3 hover-effect h-100">
                        <i class="fa fa-users display-4 mb-3 text-white" style="text-shadow: 0 4px 10px rgba(0,0,0,0.2);"></i>
                        <h3 class="counter text-white font-weight-bold" data-target="1200">0</h3>
                        <p class="fw-bold text-white-50">Siswa Aktif</p>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card gradient-border bg-white bg-opacity-10 border-0 shadow-lg text-center py-4 px-3 hover-effect h-100">
                        <i class="fa fa-chalkboard-teacher display-4 mb-3 text-white" style="text-shadow: 0 4px 10px rgba(0,0,0,0.2);"></i>
                        <h3 class="counter text-white font-weight-bold" data-target="75">0</h3>
                        <p class="fw-bold text-white-50">Tenaga Pendidik</p>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card gradient-border bg-white bg-opacity-10 border-0 shadow-lg text-center py-4 px-3 hover-effect h-100">
                        <i class="fa fa-school display-4 mb-3 text-white" style="text-shadow: 0 4px 10px rgba(0,0,0,0.2);"></i>
                        <h3 class="counter text-white font-weight-bold" data-target="36">0</h3>
                        <p class="fw-bold text-white-50">Ruang Kelas</p>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card gradient-border bg-white bg-opacity-10 border-0 shadow-lg text-center py-4 px-3 hover-effect h-100">
                        <i class="fa fa-trophy display-4 mb-3 text-white" style="text-shadow: 0 4px 10px rgba(0,0,0,0.2);"></i>
                        <h3 class="counter text-white font-weight-bold" data-target="150">0</h3>
                        <p class="fw-bold text-white-50">Prestasi Gemilang</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--  End Section  --}}

    <section class="py-5 bg-white mb-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-light">Agenda Sekolah</h2>
                <p class="text-muted mt-3">Jadwal kegiatan dan acara penting sekolah</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @forelse ($agendas as $agenda)
                        <div class="card border-0 shadow-sm mb-3 hover-effect" style="border-left: 5px solid #198754 !important;">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="calendar-icon text-center text-white rounded mr-4 shadow-sm" style="width: 80px; flex-shrink: 0; overflow: hidden; background: #198754;">
                                    <div class="month text-uppercase" style="font-size: 13px; font-weight: 700; background: #146c43; padding: 4px;">{{ \Carbon\Carbon::parse($agenda->start_date)->translatedFormat('M') }}</div>
                                    <div class="date" style="font-size: 26px; font-weight: 800; line-height: 1.2; padding: 6px 0;">{{ \Carbon\Carbon::parse($agenda->start_date)->format('d') }}</div>
                                </div>
                                <div class="event-details flex-grow-1">
                                    <h5 class="mb-2 font-weight-bold text-dark">{{ $agenda->title }}</h5>
                                    <div class="text-muted" style="font-size: 14px;">
                                        <span class="mr-3"><i class="fa fa-map-marker-alt text-danger mr-1"></i> {{ $agenda->location ?? 'Lokasi belum ditentukan' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5 shadow-sm rounded border">
                            <i class="fa fa-calendar-times fa-3x mb-3 text-light"></i>
                            <p>Belum ada agenda sekolah terdekat.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Lokasi Sekolah --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-white">Hubungi & Lokasi Kami</h2>
                <p class="text-muted mt-3">Silakan kunjungi kampus kami atau hubungi kami melalui kontak di bawah ini</p>
            </div>
            
            <div class="row">
                <!-- Kolom Kontak -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-xl h-100 p-4" style="border-top: 5px solid #198754 !important;">
                        <h4 class="font-weight-bold text-success mb-4">Informasi Kontak</h4>
                        
                        <div class="d-flex mb-4">
                            <div class="contact-icon text-success mr-3"><i class="fa fa-map-marker-alt fa-lg"></i></div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Alamat Madrasah</h6>
                                <p class="text-muted small mb-0">{{ $site_setting->address ?? 'Alamat belum diatur' }}, {{ $site_setting->city ?? '' }}, {{ $site_setting->province ?? '' }}</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="contact-icon text-success mr-3"><i class="fa fa-phone-alt fa-lg"></i></div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Telepon / WhatsApp</h6>
                                <p class="text-muted small mb-0">{{ $site_setting->phone ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="contact-icon text-success mr-3"><i class="fa fa-envelope fa-lg"></i></div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Email Resmi</h6>
                                <p class="text-muted small mb-0">{{ $site_setting->email ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="contact-icon text-success mr-3"><i class="fa fa-clock fa-lg"></i></div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Jam Operasional</h6>
                                <p class="text-muted small mb-0">{{ $site_setting->phone_hours ?? '07:30 - 14:00 WIB' }}</p>
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold mb-3 text-success">Ikuti Kami</h6>
                        <div class="d-flex" style="gap: 10px;">
                            @if($site_setting->instagram_link && $site_setting->instagram_link != '-')
                                <a href="{{ $site_setting->instagram_link }}" class="btn btn-outline-success btn-sm rounded-circle shadow-sm" target="_blank"><i class="fab fa-instagram"></i></a>
                            @endif
                            @if($site_setting->fanpage_link && $site_setting->fanpage_link != '-')
                                <a href="{{ $site_setting->fanpage_link }}" class="btn btn-outline-success btn-sm rounded-circle shadow-sm" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if($site_setting->twitter_link && $site_setting->twitter_link != '-')
                                <a href="{{ $site_setting->twitter_link }}" class="btn btn-outline-success btn-sm rounded-circle shadow-sm" target="_blank"><i class="fab fa-twitter"></i></a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Map -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-lg rounded-xl overflow-hidden h-100" style="border: 4px solid #fff;">
                        <iframe class="w-100 h-100" style="min-height: 400px; border:0;"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.044078515333!2d110.41496341477567!3d-7.868699694322847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwNTInMDcuMyJTIDExMMKwMjQnNTQuMCJF!5e0!3m2!1sid!2sid!4v1631010101010!5m2!1sid!2sid"
                            allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / 200;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = target;
                }
            }
            updateCount();
        });
    </script>
    <script>
        $('#carouselExampleCaptions').carousel({
            interval: 3000, // 3 detik
            ride: 'carousel'
        });

        document.addEventListener('DOMContentLoaded', function() {
            const quotes = document.querySelectorAll('.fade-quote');
            let index = 0;

            function showNextQuote() {
                if (index < quotes.length) {
                    quotes[index].classList.add('visible');
                    index++;
                    setTimeout(showNextQuote, 1500); // jeda antar quote (1,5 detik)
                }
            }

            showNextQuote();
        });
    </script>
@endpush
