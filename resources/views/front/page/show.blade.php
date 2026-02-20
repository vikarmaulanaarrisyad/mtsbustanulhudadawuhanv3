@extends('layouts.front')

@section('title', $page->title)

@push('css')
    <style>
        /* Konten */
        .post-content {
            line-height: 1.8;
            font-size: 1.2rem;
            color: #343a40;
            text-align: justify;
        }

        .breadcrumb-wrapper {
            width: 100%;
            height: 220px;
            /* tinggi breadcrumb */
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 40px;

            /* Background image */
            background: url('{{ Storage::url($setting->path_breadcrumb) }}') no-repeat center center;
            background-size: cover;

            /* Parallax effect */
            background-attachment: fixed;
        }

        /* Gradient overlay untuk kontras teks */
        .breadcrumb-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            z-index: 0;
        }

        /* Container breadcrumb */
        .breadcrumb-wrapper .breadcrumb-container {
            position: relative;
            z-index: 1;
            color: #04d11f;
            width: 100%;
            max-width: 1200px;
            padding: 0 20px;
        }

        /* Breadcrumb list */
        .breadcrumb-wrapper .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
        }

        .breadcrumb-wrapper .breadcrumb a {
            color: #000000;
            text-decoration: none;
        }

        .breadcrumb-wrapper .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb-wrapper .breadcrumb .breadcrumb-item.active {
            color: #ffd700;
            /* item aktif emas */
            font-weight: 600;
        }

        /* Judul halaman */
        .breadcrumb-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-top: 10px;
            color: #000000;
            opacity: 0;
            transform: translateY(20px);
            animation: slideFadeIn 1s forwards;
            animation-delay: 0.3s;
        }

        /* Animasi teks */
        @keyframes slideFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .breadcrumb-title {
                font-size: 1.6rem;
            }

            .breadcrumb-wrapper {
                height: 180px;
            }
        }

        /* Judul */
        .post-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #212529;
        }

        /* Gambar utama */
        .post-image {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
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
    </style>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
        }

        /* Footer selalu di bawah */
        .footer {
            background: #1d1d1d;
            color: rgba(255, 255, 255, 0.7);
            padding: 40px 0;
            text-align: center;
            border-top: 3px solid #28a745;
            margin-top: auto !important;
        }

        /* Hilangkan jarak bawah konten */
        .content-wrapper {
            flex: 1;
        }
    </style>
@endpush

@section('content')
    <section class="">

        <!-- Breadcrumb -->
        {{--  <nav aria-label="breadcrumb" class="px-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Halaman</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->slug }}</li>
            </ol>
        </nav>  --}}

        <!-- Breadcrumb Premium -->
        <div class="breadcrumb-wrapper">
            <div class="breadcrumb-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Halaman</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page->slug }}</li>
                    </ol>
                </nav>

                <div class="breadcrumb-title">
                    {{ $page->title }}
                </div>
            </div>
        </div>

        <div class="row no-gutters px-4">

            <!-- Konten Page -->
            <div class="col-lg-8 pr-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-5">

                        <h1 class="post-title">{{ $page->title }}</h1>

                        @if ($page->image)
                            <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}"
                                class="post-image d-block mx-auto" style="width:80%; height:auto;">
                        @endif

                        <div class="post-content mb-5">
                            {!! $page->body !!}
                        </div>

                        <!-- Tombol kembali -->
                        <a href="{{ url('/') }}" class="btn btn-back mb-5">
                            <i class="fa fa-arrow-left"></i> Kembali ke Beranda
                        </a>

                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 mt-4 mt-lg-0 mb-5">
                <div class="sidebar">
                    <!-- Sambutan Kepala Madrasah -->
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
@endsection
