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
@endpush


@section('content')
    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
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
                        <div class="carousel-caption d-none d-md-block">
                            <h5>{{ $slider->caption }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Fallback slider --}}
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
            </ol>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('img/default-slider.jpg') }}" class="d-block w-100" alt="Default Slider">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Selamat Datang di Website Kami</h5>
                        <p>Informasi terbaru akan segera ditampilkan di sini.</p>
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

    <section class="news py-5 bg-light">
        <div class="px-3">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Berita & Informasi</h2>
                <p class="text-muted">Update berita terbaru dan pengumuman sekolah</p>
            </div>

            <div class="d-flex flex-wrap">
                <!-- Kolom Kiri: Berita Utama -->
                <div class="col-md-8 mb-4 px-2">
                    <div class="row">
                        @forelse ($posts as $post)
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <img src="{{ Storage::url($post->post_image) }}" class="card-img-top"
                                        alt="{{ $post->post_slug }}">
                                    <div class="card-body">
                                        <h5 class="card-title font-weight-bold post-title-hover text-justify">
                                            {{ Str::limit(strip_tags($post->post_title), 50, '...') }}
                                        </h5>

                                        <!-- Tanggal dan User Posting -->
                                        <small class="post-meta">
                                            <i class="fa fa-calendar-alt"></i>
                                            {{ $post->created_at->format('Y-m-d H:i:s') }}
                                            | <i class="fa fa-user"></i> {{ $post->user->name ?? 'Admin' }}
                                        </small>

                                        <p class="card-text text-muted text-justify line-height-15">
                                            {!! Str::limit(strip_tags($post->post_content), 150, '...') !!}
                                        </p>

                                        <a href="{{ route('front.post_show', $post->post_slug) }}"
                                            class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
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

                <!-- Kolom Kanan: Breaking News + Pengumuman + Link + Prestasi -->
                <div class="col-md-4 mb-4 px-2">
                    <!-- Breaking News -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-bullhorn mr-2"></i> Breaking News
                        </div>
                        <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                            @include('layouts.partials.breaking-news')
                        </div>
                    </div>

                    <!-- Pengumuman -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-info-circle mr-2"></i> Pengumuman
                        </div>
                        <div class="card-body p-3 overflow-auto" style="max-height: 250px;">
                            @include('layouts.partials.announcements')
                        </div>
                    </div>

                    <!-- Prestasi -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-trophy mr-2"></i> Prestasi
                        </div>
                        <div class="card-body p-3 overflow-auto" style="max-height: 250px;">
                            @include('layouts.partials.prestasi')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Agenda Sekolah --}}
    <section class="py-5 bg-white mb-5">
        <div class="text-center mb-4 px-3">
            <h2 class="font-weight-bold text-success">Agenda Sekolah</h2>
            <p class="text-muted">Jadwal kegiatan dan acara penting sekolah</p>
        </div>

        <div class="px-5">
            <div class="shadow-sm rounded border bg-white" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="bg-success text-white sticky-top">
                        <tr>
                            <th style="width: 20%;">Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agendas as $agenda)
                            <tr>
                                <td>
                                    <i class="fa fa-calendar-alt text-success mr-2"></i>
                                    {{ tanggal_indonesia($agenda->start_date) }}
                                </td>
                                <td>{{ $agenda->title }}</td>
                                <td>{{ $agenda->location ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No agenda available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </section>

    {{-- Statistik Sekolah --}}
    <section class="py-5 bg-success text-white">
        <div class="row text-center m-0">
            <div class="col-md-3 col-6 mb-4">
                <i class="fa fa-users fa-2x mb-2"></i>
                <h3 class="counter" data-count="1200">0</h3>
                <p>Siswa</p>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <i class="fa fa-user-tie fa-2x mb-2"></i>
                <h3 class="counter" data-count="80">0</h3>
                <p>Guru & Staff</p>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <i class="fa fa-graduation-cap fa-2x mb-2"></i>
                <h3 class="counter" data-count="500">0</h3>
                <p>Alumni</p>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <i class="fa fa-trophy fa-2x mb-2"></i>
                <h3 class="counter" data-count="50">0</h3>
                <p>Prestasi</p>
            </div>
        </div>
    </section>

    {{-- Lokasi Sekolah --}}
    {{--  <section class="py-5 bg-light text-center">
        <h2 class="font-weight-bold text-success mb-4">Lokasi Sekolah</h2>
        <div class="px-3">
            <div class="embed-responsive embed-responsive-16by9 shadow rounded overflow-hidden">
                <iframe class="embed-responsive-item"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.044078515333!2d110.41496341477567!3d-7.868699694322847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwNTInMDcuMyJTIDExMMKwMjQnNTQuMCJF!5e0!3m2!1sid!2sid!4v1631010101010!5m2!1sid!2sid"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>  --}}
@endsection

@push('scripts')
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
