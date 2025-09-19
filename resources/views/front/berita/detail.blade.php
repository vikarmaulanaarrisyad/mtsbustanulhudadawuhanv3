@extends('layouts.front')

@section('title', $post->post_title)

@push('css')
    <style>
        /* Konten Berita */
        .post-content {
            line-height: 1.8;
            font-size: 1.2rem;
            color: #343a40;
            text-align: justify;
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
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #212529;
        }

        /* Meta info */
        .post-meta span {
            margin-right: 15px;
            color: #6c757d;
            font-size: 1.0rem;
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
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="px-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Berita</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $post->post_slug }}</li>
            </ol>
        </nav>

        <div class="row no-gutters px-4">
            <!-- Konten Berita -->
            <div class="col-lg-8 pr-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-5">
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
                                class="post-image d-block mx-auto" style="width:80%; height:auto;">
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
                        <div class="comments-section mt-5">
                            <h5 class="mb-3">Komentar</h5>

                            <!-- Daftar komentar -->
                            @php
                                $totalComments = $post->comments->count();
                                $latestComments = $post->comments->sortByDesc('created_at');
                                $visibleComments = $latestComments->take(10);
                            @endphp

                            <div id="comment-list">
                                @foreach ($visibleComments as $comment)
                                    <div class="card comment-card shadow-sm p-2 mb-3">
                                        <div class="card-body">
                                            <strong>{{ $comment->name ?? 'Guest' }}</strong>
                                            <small
                                                class="text-muted float-right">{{ $comment->created_at->diffForHumans() }}</small>
                                            <p>{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($totalComments > 10)
                                <div class="text-center mt-3 mb-2">
                                    <button id="show-all-comments" class="btn btn-outline-success btn-sm">
                                        Lihat semua komentar ({{ $totalComments }})
                                    </button>
                                </div>
                            @endif

                            <!-- Komentar tersembunyi -->
                            <div id="all-comments" class="d-none">
                                @foreach ($latestComments as $comment)
                                    <div class="card comment-card shadow-sm p-2 mb-3">
                                        <div class="card-body">
                                            <strong>{{ $comment->name ?? 'Guest' }}</strong>
                                            <small
                                                class="text-muted float-right">{{ $comment->created_at->diffForHumans() }}</small>
                                            <p>{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="sidebar">
                    <!-- Breaking News -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-bullhorn mr-2"></i> Breaking News
                        </div>
                        <div class="card-body p-3 overflow-auto" style="max-height: 250px;">
                            @include('layouts.partials.breaking-news')
                        </div>
                    </div>

                    <!-- Pengumuman -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-info-circle mr-2"></i> Pengumuman
                        </div>
                        <div class="card-body p-3 overflow-auto" style="max-height: 250px;">
                            {{-- @include('partials.announcements') --}}
                        </div>
                    </div>

                    <!-- Prestasi -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fa fa-trophy mr-2"></i> Prestasi
                        </div>
                        <div class="card-body p-3 overflow-auto" style="max-height: 250px;">
                            {{-- @include('partials.achievements') --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        document.getElementById('show-all-comments')?.addEventListener('click', function() {
            document.getElementById('comment-list').classList.add('d-none');
            document.getElementById('all-comments').classList.remove('d-none');
            this.style.display = 'none';
        });
    </script>
@endpush
