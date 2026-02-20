@extends('layouts.front')

@section('title', $category->category_name)

@push('css')
    <style>
        /* Breadcrumb */
        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: #0eaaa6;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .post-hero {
                padding: 80px 15px;
            }

            .post-hero h1 {
                font-size: 1.6rem;
            }

            .post-content {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="post-hero"
        style="background-image: url('{{ $setting->path_breadcrumb ? asset('storage/' . $setting->path_breadcrumb) : asset('images/default-banner.jpg') }}');">
    </div>
    <section class="news py-5">
        <div class="px-3">
            <div class="d-flex flex-wrap">
                <!-- Kolom Kiri: Berita Utama -->
                <div class="row">
                    @forelse ($posts as $post)
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <img src="{{ Storage::url($post->post_image) }}" class="card-img-top"
                                    alt="{{ $post->post_slug }}">

                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold post-title-hover text-justify">
                                        {{ Str::limit(strip_tags($post->post_title), 50, '...') }}
                                    </h5>

                                    <small class="post-meta">
                                        <i class="fa fa-calendar-alt"></i>
                                        {{ $post->created_at->format('Y-m-d H:i') }}
                                        | <i class="fa fa-user"></i>
                                        {{ $post->user->name ?? 'Admin' }}
                                    </small>

                                    <p class="card-text text-muted text-justify line-height-15">
                                        {!! Str::limit(strip_tags($post->post_content), 150, '...') !!}
                                    </p>

                                    <a href="{{ route('front.post_show', $post->post_slug) }}"
                                        class="btn btn-sm btn-outline-success">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Belum ada berita terbaru.</p>
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
@endsection
