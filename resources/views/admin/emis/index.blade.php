@extends('layouts.app')

@section('title', 'Sinkronisasi EMIS')
@section('subtitle', 'Sistem & Pengaturan')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-sync-alt mr-2 animate__animated animate__fadeInLeft"></i> 
                            Sinkronisasi EMIS & Dapodik
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Eksport dan Import data master sesuai format EMIS Kemenag / Dapodik Kemdikbud secara cerdas tanpa duplikasi.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-database fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm border-0" style="border-radius: 10px;" role="alert">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" style="border-radius: 10px;" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" style="border-radius: 10px;" role="alert">
    <ul class="mb-0 pl-3">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row animate__animated animate__fadeInUp">
    <!-- Siswa -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden premium-card h-100" style="border-left: 5px solid #007bff !important;">
            <div class="card-body p-4 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="icon-shape bg-soft-primary rounded-circle p-4">
                        <i class="fas fa-user-graduate text-primary fa-2x"></i>
                    </div>
                </div>
                <h5 class="font-weight-bold text-dark mb-2">Data Siswa</h5>
                <p class="text-muted small mb-4">Sinkronisasi data biodata siswa, profil lengkap, serta identitas orang tua.</p>
                
                <a href="{{ route('admin.emis.export.student') }}" class="btn btn-outline-primary btn-block rounded-pill mb-3 btn-premium font-weight-bold">
                    <i class="fas fa-download mr-2"></i> Download (EMIS)
                </a>
                
                <hr class="my-3">
                
                <form action="{{ route('admin.emis.import.student') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3 text-left">
                        <label class="text-xs font-weight-bold text-muted">UPLOAD DATA SISWA</label>
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required style="border-radius: 8px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block rounded-pill shadow-sm btn-premium font-weight-bold">
                        <i class="fas fa-upload mr-2"></i> Upload & Sync
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Guru -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden premium-card h-100" style="border-left: 5px solid #28a745 !important;">
            <div class="card-body p-4 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="icon-shape bg-soft-success rounded-circle p-4">
                        <i class="fas fa-chalkboard-teacher text-success fa-2x"></i>
                    </div>
                </div>
                <h5 class="font-weight-bold text-dark mb-2">Data Pegawai</h5>
                <p class="text-muted small mb-4">Sinkronisasi NUPTK, NPK, data pribadi, serta jabatan dan pendidikan.</p>
                
                <a href="{{ route('admin.emis.export.teacher') }}" class="btn btn-outline-success btn-block rounded-pill mb-3 btn-premium font-weight-bold">
                    <i class="fas fa-download mr-2"></i> Download (EMIS)
                </a>
                
                <hr class="my-3">
                
                <form action="{{ route('admin.emis.import.teacher') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3 text-left">
                        <label class="text-xs font-weight-bold text-muted">UPLOAD DATA PEGAWAI</label>
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required style="border-radius: 8px;">
                    </div>
                    <button type="submit" class="btn btn-success btn-block rounded-pill shadow-sm btn-premium font-weight-bold">
                        <i class="fas fa-upload mr-2"></i> Upload & Sync
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Rombel -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden premium-card h-100" style="border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-4 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="icon-shape bg-soft-warning rounded-circle p-4">
                        <i class="fas fa-school text-warning fa-2x"></i>
                    </div>
                </div>
                <h5 class="font-weight-bold text-dark mb-2">Data Rombel</h5>
                <p class="text-muted small mb-4">Eksport struktur Rombongan Belajar (Rombel) beserta Wali Kelas aktif saat ini.</p>
                
                <a href="{{ route('admin.emis.export.rombel') }}" class="btn btn-outline-warning btn-block rounded-pill mb-3 btn-premium font-weight-bold">
                    <i class="fas fa-download mr-2"></i> Download Rombel
                </a>
                
                <hr class="my-3">
                
                <div class="alert alert-light border-warning mt-4 text-left small shadow-sm" style="border-radius: 10px; border-left: 4px solid #ffc107;">
                    <i class="fas fa-info-circle text-warning mr-1"></i> Import rombel saat ini belum didukung. Harap konfigurasi Rombel secara manual melalui menu <strong>Akademik > Penempatan Rombel</strong>.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Card Styling */
    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }

    /* Soft UI Components */
    .icon-shape { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush
@endsection
