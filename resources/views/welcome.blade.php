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
                                            {{ Str::limit(strip_tags($post->post_title), 80, '...') }}
                                        </h5>

                                        <!-- Tanggal dan User Posting -->
                                        <small class="post-meta">
                                            <i class="fa fa-calendar-alt"></i>
                                            {{ $post->created_at->format('Y-m-d H:i:s') }}
                                            | <i class="fa fa-user"></i> {{ $post->user->name ?? 'Admin' }}
                                        </small>

                                        <p class="card-text text-muted text-justify line-height-15">
                                            {!! Str::limit(strip_tags($post->post_content), 400, '...') !!}
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
                            @foreach ($breakingNews as $b)
                                <div class="media mb-3 pb-3 border-bottom">
                                    <img src="{{ Storage::url($b->post_image) }}" class="mr-3" alt="..."
                                        width="80" height="80">
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold post-title-hover">
                                            <a href="{{ route('front.post_show', $b->post_slug) }}" class="text-dark"
                                                style="">
                                                <p class="post-title-hover">
                                                    {{ Str::limit(strip_tags($b->post_title), 80, '...') }}</p>

                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            {{ $b->created_at->format('d M Y') }} &bull;
                                            {!! Str::limit(strip_tags($b->post_content), 300, '...') !!}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Pengumuman -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-info-circle mr-2"></i> Pengumuman
                        </div>
                        <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                            <div class="media mb-3 pb-3 border-bottom">
                                <img src="{{ asset('img/bg.png') }}" class="mr-3 rounded" alt="..." width="70">
                                <div class="media-body">
                                    <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Pengumuman
                                            Jadwal Ujian</a></h6>
                                    <small class="text-muted">Ujian semester akan dimulai bulan depan, siapkan
                                        diri!</small>
                                </div>
                            </div>
                            <div class="media mb-3 pb-3 border-bottom">
                                <img src="{{ asset('img/bg-login.jpg') }}" class="mr-3 rounded" alt="..."
                                    width="70">
                                <div class="media-body">
                                    <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Libur
                                            Nasional</a></h6>
                                    <small class="text-muted">Sekolah libur pada tanggal 17 Agustus untuk peringatan
                                        kemerdekaan.</small>
                                </div>
                            </div>
                            <div class="media">
                                <img src="{{ asset('img/bgcharity1.jpg') }}" class="mr-3 rounded" alt="..."
                                    width="70">
                                <div class="media-body">
                                    <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Rapat Orang
                                            Tua Murid</a></h6>
                                    <small class="text-muted">Undangan rapat orang tua murid kelas IX pada minggu
                                        depan.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prestasi -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-trophy mr-2"></i> Prestasi
                        </div>
                        <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                            <div class="media mb-3 pb-3 border-bottom">
                                <img src="{{ asset('img/bg.png') }}" class="mr-3 rounded" alt="..."
                                    width="70">
                                <div class="media-body">
                                    <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Juara
                                            Olimpiade Matematika</a></h6>
                                    <small class="text-muted">Siswa meraih juara 1 tingkat provinsi.</small>
                                </div>
                            </div>
                            <div class="media mb-3 pb-3 border-bottom">
                                <img src="{{ asset('img/bg-login.jpg') }}" class="mr-3 rounded" alt="..."
                                    width="70">
                                <div class="media-body">
                                    <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Juara
                                            Futsal</a></h6>
                                    <small class="text-muted">Tim futsal sekolah meraih juara 2 tingkat kota.</small>
                                </div>
                            </div>
                            <div class="media">
                                <img src="{{ asset('img/bgcharity1.jpg') }}" class="mr-3 rounded" alt="..."
                                    width="70">
                                <div class="media-body">
                                    <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Lomba
                                            Pidato</a></h6>
                                    <small class="text-muted">Siswa berhasil juara 3 lomba pidato bahasa
                                        Indonesia.</small>
                                </div>
                            </div>
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
                            <th style="width: 150px;">Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success mr-2"></i> 12 Sept 2025</td>
                            <td>Masa Pengenalan Lingkungan Sekolah (MPLS)</td>
                            <td>Aula Utama</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success mr-2"></i> 20 Sept 2025</td>
                            <td>Pentas Seni & Budaya</td>
                            <td>Lapangan Sekolah</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success mr-2"></i> 5 Okt 2025</td>
                            <td>Lomba Olimpiade Sains</td>
                            <td>Laboratorium IPA</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success mr-2"></i> 15 Okt 2025</td>
                            <td>Rapat Orang Tua Siswa</td>
                            <td>Ruang Rapat</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success mr-2"></i> 1 Nov 2025</td>
                            <td>Perkemahan Pramuka</td>
                            <td>Bumi Perkemahan Cibubur</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success mr-2"></i> 10 Nov 2025</td>
                            <td>Workshop Teknologi</td>
                            <td>Lab Komputer</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-alt text-success mr-2"></i> 20 Nov 2025</td>
                            <td>Try Out Ujian Nasional</td>
                            <td>Kelas 9 & 12</td>
                        </tr>
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
    <section class="py-5 bg-light text-center">
        <h2 class="font-weight-bold text-success mb-4">Lokasi Sekolah</h2>
        <div class="px-3">
            <div class="embed-responsive embed-responsive-16by9 shadow rounded overflow-hidden">
                <iframe class="embed-responsive-item"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.044078515333!2d110.41496341477567!3d-7.868699694322847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwNTInMDcuMyJTIDExMMKwMjQnNTQuMCJF!5e0!3m2!1sid!2sid!4v1631010101010!5m2!1sid!2sid"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>
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
