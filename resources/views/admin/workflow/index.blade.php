@extends('layouts.app')

@section('title', 'Peta Jalan Admin (Workflow)')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card bg-gradient-primary border-0 shadow-lg mb-4">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-4 text-white font-weight-bold mb-3">Peta Jalan Administrasi</h1>
                            <p class="lead text-white-50">Panduan langkah demi langkah untuk mengelola operasional madrasah secara berurutan di setiap semester.</p>
                        </div>
                        <div class="col-lg-4 text-right d-none d-lg-block">
                            <i class="fas fa-map-marked-alt fa-10x text-white-50 opacity-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- SEMESTER 1 -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                    <div class="icon-shape bg-soft-primary text-primary rounded-circle mr-3">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="mb-0 font-weight-bold">Semester 1 (Ganjil)</h5>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-one-side">
                        @foreach($semester1 as $index => $item)
                        <div class="timeline-block mb-4">
                            <span class="timeline-step badge-{{ $item['color'] }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="timeline-content card shadow-none border bg-light-{{ $item['color'] }} p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-{{ $item['color'] }} font-weight-bold mb-0">
                                        <i class="{{ $item['icon'] }} mr-2"></i> {{ $item['title'] }}
                                    </h6>
                                    @if(Route::has($item['route']))
                                    <a href="{{ route($item['route']) }}" class="btn btn-xs btn-{{ $item['color'] }} rounded-pill px-3">
                                        Kerjakan <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                    @endif
                                </div>
                                <p class="text-sm text-dark mb-0">{{ $item['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- SEMESTER 2 -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                    <div class="icon-shape bg-soft-success text-success rounded-circle mr-3">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h5 class="mb-0 font-weight-bold">Semester 2 (Genap)</h5>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-one-side">
                        @foreach($semester2 as $index => $item)
                        <div class="timeline-block mb-4">
                            <span class="timeline-step badge-{{ $item['color'] }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="timeline-content card shadow-none border bg-light-{{ $item['color'] }} p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-{{ $item['color'] }} font-weight-bold mb-0">
                                        <i class="{{ $item['icon'] }} mr-2"></i> {{ $item['title'] }}
                                    </h6>
                                    @if(Route::has($item['route']))
                                    <a href="{{ route($item['route']) }}" class="btn btn-xs btn-{{ $item['color'] }} rounded-pill px-3">
                                        Kerjakan <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                    @endif
                                </div>
                                <p class="text-sm text-dark mb-0">{{ $item['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-soft-primary { background-color: rgba(94, 114, 228, 0.1); }
    .bg-soft-success { background-color: rgba(45, 206, 137, 0.1); }
    
    .timeline { position: relative; }
    .timeline:before {
        content: "";
        position: absolute;
        top: 0;
        left: 1rem;
        height: 100%;
        border-right: 2px dashed #e9ecef;
    }
    .timeline-block { position: relative; padding-left: 3rem; }
    .timeline-step {
        position: absolute;
        left: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        z-index: 1;
        font-weight: bold;
    }
    
    .bg-light-primary { background-color: #f6f9ff; }
    .bg-light-success { background-color: #f6fff9; }
    .bg-light-info { background-color: #f6fbff; }
    .bg-light-warning { background-color: #fffaf6; }
    .bg-light-danger { background-color: #fff6f6; }
    .bg-light-secondary { background-color: #fcfcfc; }
    .bg-light-dark { background-color: #f8f9fa; }
    
    .opacity-2 { opacity: 0.2; }
</style>
@endsection
