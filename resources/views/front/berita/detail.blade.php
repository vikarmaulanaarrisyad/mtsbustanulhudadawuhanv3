@extends('layouts.front')

@section('title', $post->post_title)

@push('css')
    <style>
        .post-hero {
            position: relative;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
        }

        .post-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.3));
            z-index: 1;
        }

        .hero-bg-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .hero-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(5px) scale(1.1);
            opacity: 0.8;
        }

        .post-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 1000px;
            padding: 0 20px;
        }

        .post-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 5px 20px rgba(0,0,0,0.4);
            line-height: 1.2;
        }

        .breadcrumb-detail {
            display: inline-flex;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 8px 25px;
            border-radius: 50px;
            margin-top: 15px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .breadcrumb-detail a, .breadcrumb-detail span {
            color: #fff;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .post-main-container {
            margin-top: -120px;
            position: relative;
            z-index: 10;
        }

        .post-main-card {
            border-radius: 30px;
            overflow: hidden;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            background: #fff;
        }

        .post-featured-image {
            width: 100%;
            max-height: 550px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .post-content {
            font-size: 1.25rem;
            line-height: 2;
            color: #2c3e50;
            text-align: justify;
        }

        .post-content p {
            margin-bottom: 30px;
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            margin: 40px 0;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
        }

        .post-meta-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            padding: 20px 0;
            border-bottom: 2px solid #f8f9fa;
            margin-bottom: 45px;
            justify-content: center;
        }

        .meta-box {
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: #636e72;
            font-weight: 500;
        }

        .meta-box i {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-right: 10px;
        }



        /* Related Posts at Bottom */
        .related-section {
            background: #f8f9fa;
            border-radius: 30px;
            padding: 50px;
            margin-top: 60px;
        }

        .related-card {
            border-radius: 20px;
            overflow: hidden;
            border: none;
            transition: 0.3s;
            background: #fff;
        }

        .related-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        @media (max-width: 768px) {
            .post-hero { height: 400px; }
            .post-hero h1 { font-size: 2rem; }
            .post-main-container { margin-top: -60px; }
            .related-section { padding: 30px 20px; }
        }
    </style>
@endpush

@section('content')


    <div class="post-hero">
        <div class="hero-bg-wrapper">
            <img src="{{ Storage::url($post->post_image) }}" alt="{{ $post->post_title }}" class="hero-bg-img">
        </div>
        <div class="post-hero-content">
            <h1 class="animate__animated animate__fadeInDown">{!! $post->post_title !!}</h1>
            <div class="breadcrumb-detail animate__animated animate__fadeInUp">
                <a href="{{ url('/') }}">Beranda</a>
                <span class="mx-2"><i class="fa fa-chevron-right small opacity-50"></i></span>
                <a href="{{ route('front.berita') }}">Berita</a>
                <span class="mx-2"><i class="fa fa-chevron-right small opacity-50"></i></span>
                <span>Detail Berita</span>
            </div>
        </div>
    </div>

    <div class="container post-main-container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card post-main-card mb-5">
                    <div class="card-body p-4 p-md-5">
                        
                        {{-- Meta Strip --}}
                        <div class="post-meta-strip">
                            <div class="meta-box"><i class="fa fa-user-circle"></i> {{ $post->user->name ?? 'Administrator' }}</div>
                            <div class="meta-box"><i class="fa fa-calendar-day"></i> {{ $post->created_at->format('d F Y') }}</div>
                            <div class="meta-box"><i class="fa fa-clock"></i> {{ $post->created_at->format('H:i') }} WIB</div>
                            <div class="meta-box"><i class="fa fa-folder-open"></i> 
                                @foreach ($post->categories as $cat)
                                    <span class="badge badge-light px-3 py-2 ml-1" style="border-radius: 50px;">{{ $cat->category_name }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Main Content --}}
                        <div class="post-body">
                            @if($post->post_image)
                                <img src="{{ Storage::url($post->post_image) }}" class="post-featured-image" alt="Featured Image">
                            @endif

                            <div class="post-content">
                                {!! $post->post_content !!}
                            </div>
                        </div>

                        <hr class="my-5 opacity-10">

                        {{-- Footer Action --}}
                        <div class="d-flex flex-wrap align-items-center justify-content-between bg-light p-4 rounded-xl">
                            <div class="d-flex align-items-center mb-3 mb-md-0">
                                <h6 class="font-weight-bold mb-0 mr-3 text-dark">Bagikan:</h6>
                                <div class="share-buttons d-flex" style="gap: 10px;">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-primary rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://api.whatsapp.com/send?text={{ url()->current() }}" target="_blank" class="btn btn-success rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                            <a href="{{ route('front.berita') }}" class="btn btn-success rounded-pill px-4 font-weight-bold shadow-sm">
                                <i class="fa fa-arrow-left mr-2"></i> Kembali ke Berita
                            </a>
                        </div>

                        {{-- Comments Section --}}
                        <div class="mt-5 pt-5 border-top">
                            <div class="text-center mb-5">
                                <h3 class="font-weight-bold text-dark"><i class="fa fa-comments text-success mr-2"></i> Diskusi & Komentar</h3>
                                <p class="text-muted">Suarakan pendapat Anda mengenai berita ini</p>
                            </div>

                            <div class="card border-0 bg-light rounded-xl mb-5 shadow-sm">
                                <div class="card-body p-4">
                                    <form action="{{ route('post.comment', $post->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-4">
                                            <textarea name="comment" class="form-control border-0 shadow-sm p-4" rows="4" placeholder="Tulis komentar Anda di sini..." style="border-radius: 20px; font-size: 1.1rem;" required></textarea>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm font-weight-bold">
                                                Kirim Komentar <i class="fa fa-paper-plane ml-2"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="comment-list">
                                @forelse($post->comments->sortByDesc('created_at')->take(15) as $comment)
                                    <div class="d-flex mb-4 p-3 rounded-xl transition-all hover-bg-light">
                                        <div class="comment-avatar mr-3 bg-success text-white shadow-sm" style="width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                                            {{ strtoupper(substr($comment->user->name ?? 'G', 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1 p-4 bg-white shadow-sm border border-light" style="border-radius: 0 25px 25px 25px;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="font-weight-bold mb-0 text-dark">{{ $comment->user->name ?? 'Pengunjung' }}</h6>
                                                <span class="badge badge-light text-muted font-weight-normal">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="mb-0 text-secondary" style="font-size: 1.05rem;">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-muted">
                                        <div class="mb-3 opacity-20"><i class="fa fa-comment-dots fa-4x"></i></div>
                                        <p class="font-italic">Belum ada komentar. Mari mulai diskusinya!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Related Posts at Bottom --}}
                <div class="related-section shadow-sm">
                    <h4 class="font-weight-bold text-dark mb-4 border-left pl-3" style="border-width: 5px !important; border-color: var(--primary-color) !important;">Berita Terkait Lainnya</h4>
                    <div class="row">
                        @foreach($related ?? [] as $rel)
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('front.post_show', $rel->post_slug) }}" class="text-decoration-none">
                                    <div class="card related-card h-100">
                                        <img src="{{ Storage::url($rel->post_image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                        <div class="card-body p-3">
                                            <h6 class="font-weight-bold text-dark mb-1" style="line-height: 1.4;">{{ Str::limit($rel->post_title, 50) }}</h6>
                                            <small class="text-muted">{{ $rel->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    </script>
@endpush
