@extends('layouts.front')

@section('title', 'Berita & Informasi')

@push('css')
    <style>
        .news-hero {
            position: relative;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            color: #fff;
            margin-bottom: 50px;
        }

        .news-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(11, 140, 137, 0.9), rgba(29, 29, 29, 0.8));
            z-index: 1;
        }

        .news-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .news-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        /* Post Card Premium */
        .post-card {
            border-radius: 20px !important;
            overflow: hidden;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #f0f0f0 !important;
        }

        .post-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(11, 140, 137, 0.15) !important;
        }

        .post-image-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .post-card .card-img-top {
            height: 100%;
            object-fit: cover;
            transition: 0.6s;
        }

        .post-card:hover .card-img-top {
            transform: scale(1.1);
        }

        .post-date-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            color: #fff;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 5;
            display: flex;
            align-items: center;
        }

        .post-category-tag {
            position: absolute;
            bottom: 15px;
            right: 15px;
            z-index: 5;
        }

        .post-title-hover {
            color: #1c2c4c;
            text-decoration: none !important;
            transition: 0.3s;
        }

        .post-title-hover:hover {
            color: var(--primary-color);
        }

        .avatar-circle {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Breadcrumb Glass */
        .breadcrumb-news {
            display: inline-flex;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 8px 25px;
            border-radius: 50px;
            margin-top: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .breadcrumb-news a, .breadcrumb-news span {
            color: #fff;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .breadcrumb-news .sep {
            margin: 0 10px;
            opacity: 0.5;
        }
    </style>
@endpush

@section('content')
    <div class="news-hero" style="background-image: url('{{ $setting->path_breadcrumb ? asset('storage/' . $setting->path_breadcrumb) : asset('images/default-banner.jpg') }}');">
        <div class="news-hero-content">
            <h1 class="animate__animated animate__fadeInDown">Berita & Informasi</h1>
            <div class="breadcrumb-news animate__animated animate__fadeInUp">
                <a href="{{ url('/') }}">Beranda</a>
                <span class="sep"><i class="fa fa-chevron-right small"></i></span>
                <span>Berita</span>
                @if(isset($category))
                    <span class="sep"><i class="fa fa-chevron-right small"></i></span>
                    <span>{{ $category->category_name }}</span>
                @endif
            </div>
        </div>
    </div>

    <section class="news-list py-5">
        <div class="container">
            <div class="row">
                @forelse ($posts as $post)
                    <div class="col-lg-3 col-md-6 mb-5" data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 100 }}">
                        <div class="card post-card shadow-sm border-0 h-100">
                            <div class="post-image-wrapper">
                                <div class="post-date-badge">
                                    <i class="fa fa-calendar-day mr-2"></i> {{ $post->created_at->format('d M Y') }}
                                </div>
                                @php
                                    $imagePath = $post->post_image ? Storage::url($post->post_image) : asset('images/no-image.png');
                                @endphp
                                <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $post->post_slug }}">
                                <div class="post-category-tag">
                                    @foreach($post->categories->take(1) as $cat)
                                        <span class="badge badge-success px-3 py-2 shadow-sm" style="border-radius: 50px; background: var(--primary-color);">{{ $cat->category_name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="card-title font-weight-bold mb-3" style="line-height: 1.5; min-height: 4.5rem;">
                                    <a href="{{ route('front.post_show', $post->post_slug) }}" class="post-title-hover">
                                        {{ Str::limit(strip_tags($post->post_title), 60, '...') }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted mb-4" style="font-size: 0.9rem; line-height: 1.6;">
                                    {!! Str::limit(strip_tags($post->post_content), 100, '...') !!}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; font-size: 11px; font-weight: bold;">
                                            {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <small class="text-muted font-weight-bold">{{ $post->user->name ?? 'Admin' }}</small>
                                    </div>
                                    <a href="{{ route('front.post_show', $post->post_slug) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                        Baca <i class="fa fa-arrow-right ml-1" style="font-size: 10px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded-xl shadow-sm border border-light">
                            <i class="fa fa-newspaper fa-4x text-light mb-3"></i>
                            <h4 class="text-muted">Belum ada berita terbaru</h4>
                            <p class="text-muted small">Silakan kembali lagi nanti untuk pembaruan terkini.</p>
                            <a href="{{ url('/') }}" class="btn btn-success rounded-pill px-4 mt-3">Kembali ke Beranda</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>
@endsection
