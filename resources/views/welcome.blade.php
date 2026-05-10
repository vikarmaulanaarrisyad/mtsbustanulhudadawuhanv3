@extends('layouts.front')
@push('css')
    <style>
        /* Glassmorphism Variables */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --primary-gradient: linear-gradient(135deg, #0b8c89, #14b8a6);
        }

        /* Animated Counter Enhancements */
        .stat-box {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }
        .stat-box:hover {
            transform: translateY(-15px) scale(1.05);
            background: rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        /* Why Choose Us Section */
        .choice-card {
            background: #fff;
            border-radius: 24px;
            padding: 40px 30px;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #f1f5f9;
        }
        .choice-card:hover {
            box-shadow: 0 20px 40px rgba(11, 140, 137, 0.1);
            transform: translateY(-10px);
            border-color: #0b8c89;
        }
        .choice-icon-box {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: #f1f5f9;
            color: #0b8c89;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 25px;
            transition: 0.3s;
        }
        .choice-card:hover .choice-icon-box {
            background: #0b8c89;
            color: #fff;
            transform: rotate(10deg);
        }

        /* Hero Text Shadow */
        .carousel-caption h1 {
            text-shadow: 0 10px 30px rgba(0,0,0,0.5);
            font-weight: 900;
        }

        /* Section Divider Path Color */
        .section-divider .shape-fill {
            fill: #f8f9fa;
        }
    </style>
@endpush


@section('content')
    <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-ride="carousel" data-interval="6000">
        @if ($sliders->isNotEmpty())
            {{-- Indicators --}}
            <ol class="carousel-indicators" style="bottom: 80px;">
                @foreach ($sliders as $key => $slider)
                    <li data-target="#carouselExampleCaptions" data-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}" style="width: 40px; height: 5px; border-radius: 5px; margin: 0 5px;"></li>
                @endforeach
            </ol>

            {{-- Slides --}}
            <div class="carousel-inner">
                @foreach ($sliders as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ Storage::url($slider->image) }}" class="d-block w-100" alt="{{ $slider->slug }}">
                        <div class="carousel-caption">
                            <div class="container text-center">
                                <h1 class="display-3 font-weight-bold mb-4 animate__animated animate__fadeInDown">{{ $slider->caption }}</h1>
                                <p class="lead mb-5 animate__animated animate__fadeInUp animate__delay-1s" style="font-size: 1.4rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Membangun Generasi Cerdas, Berakhlak Mulia, dan Berdaya Saing Global.</p>
                                <div class="d-flex justify-content-center hero-buttons animate__animated animate__fadeInUp animate__delay-2s" style="gap: 20px;">
                                    <a href="#news" class="btn btn-hero btn-hero-primary"><i class="fas fa-newspaper mr-2"></i> Jelajahi Berita</a>
                                    <a href="{{ route('register') }}" class="btn btn-hero btn-hero-outline"><i class="fas fa-user-plus mr-2"></i> Daftar PPDB</a>
                                </div>
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
                        <h5>Selamat Datang di Madrasah Kami</h5>
                        <p>Pusat Pendidikan Berkualitas dengan Nilai-Nilai Keislaman.</p>
                        <div class="d-flex justify-content-center hero-buttons" style="gap: 20px;">
                            <a href="#news" class="btn btn-hero btn-hero-primary"><i class="fas fa-newspaper mr-2"></i> Jelajahi Berita</a>
                            <a href="{{ route('register') }}" class="btn btn-hero btn-hero-outline"><i class="fas fa-user-plus mr-2"></i> Daftar PPDB</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="scroll-down-indicator" onclick="window.scrollBy({top: window.innerHeight, behavior: 'smooth'});">
            <i class="fas fa-chevron-down"></i>
        </div>

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

    <section class="ppdb-banner py-4 bg-light mt-3" data-aos="fade-up">
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

            @if(isset($ppdbRegistrants) && $ppdbRegistrants->count() > 0)
                <div class="mt-5 text-left">
                    <h5 class="font-weight-bold mb-4 text-success border-bottom pb-2"><i class="fa fa-users mr-2"></i> Pendaftar Terbaru</h5>
                    <div class="table-responsive px-2" data-aos="fade-up">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama Pendaftar</th>
                                    <th>Asal Sekolah</th>
                                    <th width="15%" class="text-center">Status</th>
                                    <th width="15%" class="text-center">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ppdbRegistrants as $index => $registrant)
                                    <tr>
                                        <td class="text-center text-muted font-weight-bold">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 40px; height: 40px; flex-shrink: 0; font-size: 1rem; font-weight: bold; background: linear-gradient(135deg, var(--primary-color), var(--primary-light));">
                                                    {{ strtoupper(substr($registrant->masked_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-weight-bold text-dark d-block" style="font-size: 1.05rem;">{{ $registrant->masked_name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted"><i class="fa fa-school mr-2 text-success opacity-75"></i> {{ $registrant->asal_sekolah ?? 'Tidak diketahui' }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $registrant->public_status_color }} badge-pill px-3 py-2 shadow-sm" style="font-size: 0.8rem;">{{ $registrant->public_status_label }}</span>
                                        </td>
                                        <td class="text-center text-muted small">
                                            <i class="fa fa-clock mr-1 text-success opacity-75"></i> {{ $registrant->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </section>

    </section>

    {{-- Why Choose Us Section --}}
    <section class="why-us py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge badge-success px-3 py-2 rounded-pill mb-3" style="background: rgba(11, 140, 137, 0.1); color: #0b8c89; font-weight: 700; letter-spacing: 1px;">KEUNGGULAN KAMI</span>
                <h2 class="font-weight-bold text-dark mt-2" style="font-size: 2.5rem;">Mengapa Memilih Madrasah Kami?</h2>
                <p class="text-muted">Kami berdedikasi untuk mencetak generasi yang unggul dalam ilmu dan mulia dalam akhlak.</p>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="choice-card">
                        <div class="choice-icon-box">
                            <i class="fas fa-mosque"></i>
                        </div>
                        <h4 class="font-weight-bold mb-3">Pendidikan Islami</h4>
                        <p class="text-muted mb-0">Kurikulum yang mengintegrasikan nilai-nilai keislaman dengan ilmu pengetahuan modern secara harmonis.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="choice-card">
                        <div class="choice-icon-box">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <h4 class="font-weight-bold mb-3">Fasilitas Modern</h4>
                        <p class="text-muted mb-0">Lingkungan belajar yang kondusif didukung dengan fasilitas laboratorium dan teknologi digital terbaru.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="choice-card">
                        <div class="choice-icon-box">
                            <i class="fas fa-award"></i>
                        </div>
                        <h4 class="font-weight-bold mb-3">Prestasi Unggul</h4>
                        <p class="text-muted mb-0">Terbukti mencetak santri-santri berprestasi baik di tingkat regional maupun nasional dalam berbagai bidang.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Organic Divider --}}
    <div class="section-divider">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>

    {{-- Statistics Animated Counter Section --}}
    <section class="statistics py-5" style="background: var(--primary-gradient); color: white; margin: 0; position: relative; overflow: hidden;">
        {{-- Decorative background shapes --}}
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge px-4 py-2 rounded-pill mb-3 shadow-sm" style="background: rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2); font-size: 1rem; color: white; font-weight: 700; letter-spacing: 2px;">DATA & FAKTA</span>
                <h2 class="font-weight-bold text-white">Statistik Keberhasilan Kami</h2>
            </div>

            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-box p-4 rounded-xl" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); height: 100%;">
                        <i class="fa fa-chalkboard-teacher fa-3x mb-3 text-white opacity-90"></i>
                        <h2 class="font-weight-bold counter mb-1" style="font-size: 3rem;" data-target="{{ $stats['teacher_count'] ?? 45 }}">0</h2>
                        <p class="mb-0 font-weight-bold small text-uppercase" style="letter-spacing: 2px; color: rgba(255,255,255,0.8);">Guru & Staff</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-box p-4 rounded-xl" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); height: 100%;">
                        <i class="fa fa-user-graduate fa-3x mb-3 text-white opacity-90"></i>
                        <h2 class="font-weight-bold counter mb-1" style="font-size: 3rem;" data-target="{{ $stats['student_count'] ?? 1200 }}">0</h2>
                        <p class="mb-0 font-weight-bold small text-uppercase" style="letter-spacing: 2px; color: rgba(255,255,255,0.8);">Siswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-box p-4 rounded-xl" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); height: 100%;">
                        <i class="fa fa-running fa-3x mb-3 text-white opacity-90"></i>
                        <h2 class="font-weight-bold counter mb-1" style="font-size: 3rem;" data-target="{{ $stats['extracurricular_count'] ?? 15 }}">0</h2>
                        <p class="mb-0 font-weight-bold small text-uppercase" style="letter-spacing: 2px; color: rgba(255,255,255,0.8);">Ekstrakurikuler</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="400">
                    <div class="stat-box p-4 rounded-xl" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); height: 100%;">
                        <i class="fa fa-trophy fa-3x mb-3 text-white opacity-90"></i>
                        <h2 class="font-weight-bold counter mb-1" style="font-size: 3rem;" data-target="{{ $stats['achievement_count'] ?? 85 }}">0</h2>
                        <p class="mb-0 font-weight-bold small text-uppercase" style="letter-spacing: 2px; color: rgba(255,255,255,0.8);">Prestasi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Organic Divider Reverse --}}
    <div class="section-divider" style="transform: rotate(180deg); margin-top: -50px; z-index: 1;">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill" style="fill: #f8f9fa;"></path>
        </svg>
    </div>

    {{-- Dynamic Extracurricular Section --}}
    <section class="ekskul py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-white">Ekstrakurikuler</h2>
                <p class="text-muted mt-3">Wadah pengembangan bakat dan minat siswa Madrasah</p>
            </div>
            <div class="row">
                @forelse($extracurriculars as $ekskul)
                    <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="ekskul-card p-4 text-center h-100 shadow-sm">
                            <div class="feature-icon mb-3">
                                <i class="fa {{ $ekskul->icon ?? 'fa-users' }}"></i>
                            </div>
                            <h5 class="font-weight-bold mb-2">{{ $ekskul->name }}</h5>
                            <p class="text-muted small mb-0">{{ Str::limit($ekskul->description, 60) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">
                        <p>Data ekstrakurikuler belum tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="news" class="news py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="font-weight-bold text-success">Berita & Informasi</h2>
                <p class="text-muted">Update berita terbaru dan pengumuman sekolah</p>
            </div>

            <div class="row">
                <!-- Berita Utama Full Width -->
                <div class="col-12 px-2">
                    <div class="row">
                        @forelse ($posts as $post)
                            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                                <div class="card post-card shadow-lg border-0 h-100 overflow-hidden" style="border-radius: 20px;">
                                    <div class="post-image-wrapper position-relative">
                                        <div class="post-date-badge">
                                            <i class="fa fa-calendar-day mr-1"></i> {{ $post->created_at->format('d M Y') }}
                                        </div>
                                        @php
                                            $imagePath = $post->post_image ? Storage::url($post->post_image) : asset('images/no-image.png');
                                        @endphp
                                        <img src="{{ $imagePath }}" class="card-img-top" 
                                            alt="{{ $post->post_slug }}" style="height: 220px; object-fit: cover; transition: 0.5s;">
                                        <div class="post-category-tag">
                                            @foreach($post->categories->take(1) as $cat)
                                                <span class="badge badge-success px-3 py-2" style="border-radius: 50px; background: var(--primary-color);">{{ $cat->category_name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <h5 class="card-title font-weight-bold text-dark mb-3" style="line-height: 1.5; min-height: 3rem;">
                                            <a href="{{ route('front.post_show', $post->post_slug) }}" class="text-dark post-title-hover" style="text-decoration: none;">
                                                {{ Str::limit(strip_tags($post->post_title), 60, '...') }}
                                            </a>
                                        </h5>

                                        <p class="card-text text-muted mb-4" style="font-size: 0.9rem; line-height: 1.6;">
                                            {!! Str::limit(strip_tags($post->post_content), 100, '...') !!}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle mr-2 bg-light text-success d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 50%; font-size: 12px; font-weight: bold;">
                                                    {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                                </div>
                                                <small class="text-muted font-weight-bold">{{ $post->user->name ?? 'Admin' }}</small>
                                            </div>
                                            <a href="{{ route('front.post_show', $post->post_slug) }}"
                                                class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">Baca <i class="fa fa-arrow-right ml-1" style="font-size: 10px;"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5 bg-white rounded shadow-sm border">
                                    <i class="fa fa-newspaper fa-3x text-light mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada berita terbaru yang tersedia.</p>
                                </div>
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
                </div>
            </div>
        </div>
    </section>

    {{-- Galeri dan Video Profil --}}
    <section class="gallery-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-white">Galeri & Profil Sekolah</h2>
                <p class="text-muted mt-3">Dokumentasi kegiatan dan profil video sekolah kami</p>
            </div>
            <div class="row">
                <!-- Video Section -->
                <div class="col-lg-5 mb-4" data-aos="fade-right">
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
                <div class="col-lg-7 mb-4" data-aos="fade-left">
                    <div class="card border-0 shadow-sm rounded p-3 h-100 bg-white">
                        <div class="masonry-grid">
                            @forelse($albums ?? [] as $album)
                                <a href="{{ Storage::url($album->album_cover) }}" class="glightbox masonry-item" data-gallery="gallery1" data-title="{{ $album->album_title }}" data-description="{{ $album->album_description ?? '' }}">
                                    <img src="{{ Storage::url($album->album_cover) }}" alt="{{ $album->album_title }}">
                                    <div class="overlay-gallery">
                                        <div>
                                            <i class="fa fa-expand"></i>
                                            <h6 class="font-weight-bold mb-0">{{ $album->album_title }}</h6>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="w-100 text-center text-muted py-5">
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

    {{-- Dynamic Achievements Section --}}
    <section class="achievements py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-light">Prestasi Siswa Terbaru</h2>
                <p class="text-muted mt-3">Bangga atas pencapaian luar biasa santri-santri kami</p>
            </div>
            <div class="row justify-content-center">
                @forelse($achievements as $prestasi)
                    <div class="col-md-4 mb-4" data-aos="flip-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="achievement-card card h-100 p-4 border-light bg-light">
                            <div class="d-flex align-items-center mb-3">
                                <div class="medal-badge mr-3">
                                    <i class="fa fa-trophy"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-0 text-success">{{ $prestasi->title }}</h6>
                                    <small class="text-muted">Tahun {{ $prestasi->year }}</small>
                                </div>
                            </div>
                            <div class="student-info border-top pt-3 mt-auto">
                                <p class="mb-0 font-weight-bold text-dark"><i class="fa fa-user-graduate mr-2 text-success"></i> {{ $prestasi->student_name }}</p>
                                <p class="mb-0 text-muted small">Juara {{ $prestasi->rank }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">
                        <p>Data prestasi belum tersedia.</p>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('front.achievements') }}" class="btn btn-success btn-lg rounded-pill px-5 shadow-lg font-weight-bold tracking-wider">
                    <i class="fas fa-medal mr-2"></i> LIHAT SEMUA PRESTASI
                </a>
            </div>
        </div>
    </section>


    <section class="py-5 bg-white mb-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-light">Agenda Sekolah</h2>
                <p class="text-muted mt-3">Jadwal kegiatan dan acara penting sekolah</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @forelse ($agendas as $agenda)
                        <div class="card border-0 shadow-sm mb-3 hover-effect" style="border-left: 5px solid #198754 !important;" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
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
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="font-weight-bold text-success d-inline-block shadow-sm px-4 py-2 rounded bg-white">Hubungi & Lokasi Kami</h2>
                <p class="text-muted mt-3">Silakan kunjungi kampus kami atau hubungi kami melalui kontak di bawah ini</p>
            </div>
            
            <div class="row">
                <!-- Kolom Kontak -->
                <div class="col-lg-4 mb-4" data-aos="fade-right">
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
                <div class="col-lg-8 mb-4" data-aos="fade-left">
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
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.counter');
            const speed = 100; // Semakin kecil, semakin cepat

            const animateCounters = () => {
                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        const count = +counter.innerText;

                        const inc = target / speed;

                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            setTimeout(updateCount, 20);
                        } else {
                            counter.innerText = target;
                        }
                    };

                    updateCount();
                });
            };

            // Gunakan Intersection Observer untuk memulai animasi saat di-scroll
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            const statsSection = document.querySelector('.statistics');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });
    </script>
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
    <script>
        const lightbox = GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
    </script>
@endpush
