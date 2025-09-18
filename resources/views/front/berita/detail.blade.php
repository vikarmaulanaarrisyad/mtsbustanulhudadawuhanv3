@extends('layouts.front')

@section('title', $post->post_title)

@push('css')
    <style>
        /* Konten Berita */
        .post-content {
            line-height: 1.8;
            font-size: 1rem;
            color: #343a40;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: #28a745;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Judul */
        .post-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #212529;
        }

        /* Meta info */
        .post-meta span {
            margin-right: 15px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .post-meta i {
            color: #28a745;
            margin-right: 5px;
        }

        /* Gambar utama */
        .post-image {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
        }

        /* Share buttons */
        .share-buttons a {
            margin-right: 12px;
            color: #28a745;
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        .share-buttons a:hover {
            color: #19692c;
        }

        /* Tombol kembali */
        .btn-back {
            background-color: #fff;
            color: #28a745;
            border: 1px solid #28a745;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background-color: #28a745;
            color: #fff;
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 100px;
        }

        /* Berita terkait */
        .related-card {
            transition: 0.3s;
            margin-bottom: 15px;
        }

        .related-card img {
            width: 100%;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.3s;
        }

        .related-card:hover img {
            transform: scale(1.05);
        }

        .related-card .card-title a {
            font-size: 0.95rem;
            color: #343a40;
        }

        .related-card .card-title a:hover {
            color: #28a745;
            text-decoration: underline;
        }

        /* Komentar */
        .comment-card {
            margin-bottom: 15px;
        }

        .comment-card .card-body p {
            margin-bottom: 0;
        }
    </style>
@endpush

@section('content')

    <section class="py-5">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->post_title }}</li>
                </ol>
            </nav>

            <div class="row">
                <!-- Konten Berita -->
                <div class="col-lg-8">
                    <h1 class="post-title">{{ $post->post_title }}</h1>
                    <div class="post-meta mb-4">
                        <span><i class="fa fa-user"></i> {{ $post->author->name ?? 'Admin' }}</span>
                        <span><i class="fa fa-calendar-alt"></i> {{ $post->created_at->format('d M Y') }}</span>
                        <span><i class="fa fa-folder-open"></i>
                            @foreach ($post->categories as $category)
                                <a href="#" class="text-success">{{ $category->category_name }}</a>
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </span>
                    </div>

                    @if ($post->post_image)
                        <img src="{{ asset('storage/' . $post->post_image) }}" alt="{{ $post->post_title }}"
                            class="post-image">
                    @endif

                    <div class="post-content mb-5">
                        {!! $post->post_content !!}
                    </div>

                    <!-- Share -->
                    <div class="share-buttons mb-4">
                        <span>Bagikan:</span>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>

                    <!-- Tombol kembali -->
                    <a href="{{ url('/') }}" class="btn btn-back mb-5">
                        <i class="fa fa-arrow-left"></i> Kembali ke Berita
                    </a>

                    <!-- Komentar -->
                    <div class="comments-section">
                        <h5 class="mb-3">Komentar</h5>

                        <!-- Form komentar -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <form action="{{ route('post.comment', $post->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="comment">Tulis komentar</label>
                                        <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Tulis komentar..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-2">Kirim</button>
                                </form>
                            </div>
                        </div>

                        <!-- Daftar komentar -->
                        @forelse ($post->comments as $comment)
                            <div class="card comment-card shadow-sm p-2">
                                <div class="card-body">
                                    <strong>{{ $comment->user->name ?? 'Guest' }}</strong>
                                    <small
                                        class="text-muted float-right">{{ $comment->created_at->diffForHumans() }}</small>
                                    <p>{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada komentar.</p>
                        @endforelse
                    </div>

                </div>

                <div class="col-md-4 mb-4 px-2">
                    <div class="sidebar">
                        <!-- Breaking News -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-success text-white font-weight-bold">
                                <i class="fa fa-bullhorn mr-2"></i> Breaking News
                            </div>
                            <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                                <div class="media mb-3 pb-3 border-bottom">
                                    <img src="{{ asset('img/bg.png') }}" class="mr-3 rounded" alt="..."
                                        width="70">
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold">
                                            <a href="#" class="text-dark">PPDB
                                                {{ date('Y') }}/{{ date('Y') + 1 }} Telah Dibuka</a>
                                        </h6>
                                        <small class="text-muted">Pendaftaran siswa baru kini dibuka, cek syarat &
                                            prosedurnya.</small>
                                    </div>
                                </div>
                                <div class="media mb-3 pb-3 border-bottom">
                                    <img src="{{ asset('img/bg-login.jpg') }}" class="mr-3 rounded" alt="..."
                                        width="70">
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Siswa Raih
                                                Juara Sains</a></h6>
                                        <small class="text-muted">Prestasi membanggakan di lomba sains tingkat
                                            provinsi.</small>
                                    </div>
                                </div>
                                <div class="media">
                                    <img src="{{ asset('img/bgcharity1.jpg') }}" class="mr-3 rounded" alt="..."
                                        width="70">
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold"><a href="#" class="text-dark">Peringatan
                                                Hari Kemerdekaan</a></h6>
                                        <small class="text-muted">Sekolah mengadakan upacara & lomba untuk siswa.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pengumuman -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-success text-white font-weight-bold">
                                <i class="fa fa-info-circle mr-2"></i> Pengumuman
                            </div>
                            <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                                <div class="media mb-3 pb-3 border-bottom">
                                    <img src="{{ asset('img/bg.png') }}" class="mr-3 rounded" alt="..."
                                        width="70">
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
        </div>
    </section>

@endsection
