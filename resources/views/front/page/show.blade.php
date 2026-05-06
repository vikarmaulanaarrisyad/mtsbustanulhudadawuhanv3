@extends('layouts.front')

@section('title', $page->title)

@push('css')
    <style>
        /* Content styling similar to news */
        .post-content {
            font-size: 1.2rem;
            line-height: 2;
            color: #2c3e50;
            text-align: justify;
        }
        
        .post-content p { margin-bottom: 25px; }
        
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            margin: 30px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .post-hero {
            position: relative;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            overflow: hidden;
            margin-bottom: 0;
        }

        .post-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2));
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
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease-out forwards;
        }

        .post-card-container {
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        .post-main-card {
            border-radius: 25px;
            overflow: hidden;
            border: none;
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            display: inline-flex;
            padding: 8px 20px;
            border-radius: 50px;
            margin-bottom: 20px;
        }

        .breadcrumb-item a, .breadcrumb-item.active {
            color: #fff !important;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .post-hero { height: 250px; }
            .post-hero h1 { font-size: 2rem; }
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
    <div class="post-hero" style="background-image: url('{{ Storage::url($setting->path_breadcrumb) }}');">
        <div class="post-hero-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">{{ $page->title }}</li>
                </ol>
            </nav>
            <h1>{{ $page->title }}</h1>
        </div>
    </div>

    <div class="container post-card-container mb-5">
        <div class="row">
            <!-- Konten Page -->
            <div class="col-lg-8">
                <div class="card post-main-card mb-4">
                    <div class="card-body p-4 p-md-5">

                        @if ($page->image)
                            <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}"
                                class="post-image d-block mx-auto mb-5 rounded shadow-sm" style="max-width:100%; height:auto;">
                        @endif

                        <div class="post-content">
                            {!! $page->body !!}
                        </div>

                        <hr class="my-5">

                        <!-- Tombol kembali -->
                        <a href="{{ url('/') }}" class="btn btn-outline-success rounded-pill px-4">
                            <i class="fa fa-arrow-left mr-2"></i> Kembali ke Beranda
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
    </div>
@endsection
