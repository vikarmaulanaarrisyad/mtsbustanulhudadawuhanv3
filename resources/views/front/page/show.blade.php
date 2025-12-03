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
        color: rgba(255,255,255,0.7);
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
<section class="py-5">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="px-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Halaman</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $page->slug }}</li>
        </ol>
    </nav>

    <div class="row no-gutters px-4">

        <!-- Konten Page -->
        <div class="col-lg-8 pr-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-5">

                    <h1 class="post-title">{{ $page->title }}</h1>

                    @if ($page->image)
                        <img src="{{ asset('storage/' . $page->image) }}" 
                             alt="{{ $page->title }}"
                             class="post-image d-block mx-auto" 
                             style="width:80%; height:auto;">
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
                        @include('layouts.partials.announcements')
                    </div>
                </div>

                <!-- Prestasi -->
                <div class="card shadow-sm border-0">
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
@endsection
