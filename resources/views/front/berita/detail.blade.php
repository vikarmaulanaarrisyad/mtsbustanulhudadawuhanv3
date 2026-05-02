@extends('layouts.front')

@section('title', $post->post_title)

@push('css')
    <style>
        .post-hero {
            position: relative;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            color: #fff;
            overflow: hidden;
        }

        .post-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4));
            z-index: 1;
        }

        .post-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            padding: 0 20px;
        }

        .post-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            display: inline-flex;
            padding: 8px 20px;
            border-radius: 50px;
            margin-top: 10px;
        }

        .breadcrumb-item a, .breadcrumb-item.active {
            color: #fff !important;
            font-weight: 500;
        }

        .post-card-container {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .post-main-card {
            border-radius: 25px;
            overflow: hidden;
            border: none;
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
        }

        .post-content {
            font-size: 1.25rem;
            line-height: 1.9;
            color: #2c3e50;
            text-align: justify;
        }

        .post-meta-v2 {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
            margin-bottom: 35px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: #7f8c8d;
        }

        .meta-item i {
            font-size: 1.1rem;
            color: #0eaaa6;
            margin-right: 8px;
        }

        /* Comments Styling */
        .comment-bubble {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 20px;
            position: relative;
            margin-bottom: 25px;
            border: 1px solid #eee;
            transition: 0.3s;
        }

        .comment-bubble:hover {
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #0eaaa6;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(14, 170, 166, 0.3);
        }

        .comment-input-area {
            background: #fff;
            border: 2px solid #eee;
            border-radius: 20px;
            padding: 15px;
            transition: 0.3s;
        }

        .comment-input-area:focus-within {
            border-color: #0eaaa6;
            box-shadow: 0 0 0 4px rgba(14, 170, 166, 0.1);
        }

        .comment-input-area textarea {
            border: none !important;
            box-shadow: none !important;
            resize: none;
            font-size: 1.05rem;
        }

        .share-pill {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: #f1f2f6;
            border-radius: 50px;
            color: #2f3542;
            text-decoration: none;
            margin-right: 10px;
            transition: 0.3s;
            font-weight: 600;
        }

        .share-pill:hover {
            background: #0eaaa6;
            color: #fff;
            text-decoration: none;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .post-hero h1 { font-size: 2rem; }
            .post-content { font-size: 1.1rem; }
        }
    </style>
@endpush

@section('content')
    <div class="post-hero" style="background-image: url('{{ Storage::url($post->post_image) }}');">
        <div class="post-hero-content">
            <h1 class="animate__animated animate__fadeInDown">{!! $post->post_title !!}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="#">Berita</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($post->post_title, 20) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container post-card-container mb-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card post-main-card mb-4">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="post-meta-v2">
                            <div class="meta-item"><i class="fa fa-user-circle"></i> {{ $post->user->name ?? 'Admin' }}</div>
                            <div class="meta-item"><i class="fa fa-calendar-check"></i> {{ $post->created_at->format('d F Y') }}</div>
                            <div class="meta-item"><i class="fa fa-tag"></i> 
                                @foreach ($post->categories as $cat)
                                    <span class="badge badge-light px-2">{{ $cat->category_name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="post-content">
                            {!! $post->post_content !!}
                        </div>

                        <hr class="my-5">

                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="mb-3">
                                <h6 class="font-weight-bold mb-3">Bagikan Berita:</h6>
                                <a href="#" class="share-pill"><i class="fab fa-facebook-f mr-2"></i> Facebook</a>
                                <a href="#" class="share-pill"><i class="fab fa-whatsapp mr-2"></i> WhatsApp</a>
                            </div>
                            <div class="mb-3">
                                <a href="{{ url('/') }}" class="btn btn-outline-success btn-lg rounded-pill px-4">
                                    <i class="fa fa-chevron-left mr-2"></i> Kembali
                                </a>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="mt-5 pt-4 border-top">
                            <h4 class="font-weight-bold mb-4 text-dark"><i class="fa fa-comments text-success mr-2"></i> {{ $post->comments->count() }} Komentar</h4>

                            <div class="comment-form mb-5">
                                <div class="card border-0 bg-light rounded-xl shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="font-weight-bold mb-3">Tinggalkan Jejak Anda</h5>
                                        <form action="{{ route('post.comment', $post->id) }}" method="POST">
                                            @csrf
                                            <div class="comment-input-area mb-3">
                                                <textarea name="comment" class="form-control" rows="3" placeholder="Apa pendapat Anda tentang berita ini?" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm">
                                                Kirim Komentar <i class="fa fa-paper-plane ml-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id="comment-container">
                                @forelse($post->comments->sortByDesc('created_at')->take(10) as $comment)
                                    <div class="d-flex mb-4">
                                        <div class="comment-avatar mr-3">
                                            {{ strtoupper(substr($comment->user->name ?? 'G', 0, 1)) }}
                                        </div>
                                        <div class="comment-bubble flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="font-weight-bold mb-0">{{ $comment->user->name ?? 'Pengunjung' }}</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 text-dark">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-muted">
                                        <i class="fa fa-comment-slash fa-3x mb-3 opacity-20"></i>
                                        <p>Belum ada komentar. Jadilah yang pertama memberikan pendapat!</p>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar sticky-top" style="top: 100px; z-index: 1;">
                    @include('layouts.partials.sambutan-kepala')
                    
                    <div class="card border-0 shadow-sm rounded-xl mb-4 overflow-hidden">
                        <div class="card-header bg-success text-white py-3 border-0">
                            <h6 class="mb-0 font-weight-bold"><i class="fa fa-fire mr-2"></i> Berita Terkait</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($related ?? [] as $rel)
                                    <a href="{{ route('front.post_show', $rel->post_slug) }}" class="list-group-item list-group-item-action py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ Storage::url($rel->post_image) }}" class="rounded mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1 font-weight-bold small text-dark">{{ Str::limit($rel->post_title, 45) }}</h6>
                                                <small class="text-muted">{{ $rel->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @include('layouts.partials.announcements')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Smooth scroll for anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endpush
