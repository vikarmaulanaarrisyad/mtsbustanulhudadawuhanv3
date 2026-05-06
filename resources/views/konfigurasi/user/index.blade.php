@extends($layout)

@section('title', 'Data Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen User</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-slate overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-users-cog mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Pengguna
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola akun pengguna, hak akses (roles), dan keamanan sistem Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-user-shield fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- LEFT SIDEBAR: FILTERS & TOOLS -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0 premium-card mb-4 text-center py-4 bg-white">
            <div class="card-body">
                <div class="user-avatar-large mx-auto mb-3 shadow-sm">
                    <i class="fas fa-user-shield fa-3x text-slate"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1">Sistem Keamanan</h5>
                <p class="text-muted text-xs mb-4 uppercase letter-spacing-1">Administrator Control Panel</p>
                
                @can('user.create')
                <button onclick="addForm(`{{ route('users.store') }}`)" class="btn btn-slate btn-block rounded-pill shadow-sm font-weight-bold py-2 mb-3 text-white">
                    <i class="fas fa-plus-circle mr-2"></i> TAMBAH USER BARU
                </button>
                @endcan
                
                <hr class="my-4">
                
                <div class="text-left">
                    <h6 class="text-xs font-weight-bold text-muted uppercase mb-3">Panduan Cepat</h6>
                    <div class="d-flex mb-2">
                        <div class="icon-sm mr-2 text-primary"><i class="fas fa-check-circle"></i></div>
                        <p class="text-xs text-muted mb-0">Role menentukan hak akses fitur di dalam aplikasi.</p>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="icon-sm mr-2 text-warning"><i class="fas fa-key"></i></div>
                        <p class="text-xs text-muted mb-0">Reset password dapat dilakukan jika user lupa kata sandi.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card bg-slate text-white">
            <div class="card-body p-4 text-center">
                <h2 class="font-weight-bold mb-0 count-users">-</h2>
                <p class="text-xs uppercase font-weight-bold opacity-7 mb-0">Total Pengguna Terdaftar</p>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: USER TABLE -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body p-0">
                <!-- Role Filtering Tabs -->
                <div class="px-4 pt-3">
                    <ul class="nav nav-pills nav-pills-premium mb-0" id="roleTabs" role="tablist">
                        <li class="nav-item mr-2">
                            <a class="nav-link active" data-role="all" data-toggle="pill" href="javascript:void(0)">
                                <i class="fas fa-users mr-2"></i>Semua User
                            </a>
                        </li>
                        <li class="nav-item mr-2">
                            <a class="nav-link" data-role="Admin" data-toggle="pill" href="javascript:void(0)">
                                <i class="fas fa-user-shield mr-2"></i>Admin
                            </a>
                        </li>
                        <li class="nav-item mr-2">
                            <a class="nav-link" data-role="Guru" data-toggle="pill" href="javascript:void(0)">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>Guru
                            </a>
                        </li>
                        <li class="nav-item mr-2">
                            <a class="nav-link" data-role="Siswa" data-toggle="pill" href="javascript:void(0)">
                                <i class="fas fa-user-graduate mr-2"></i>Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-role="ppdb" data-toggle="pill" href="javascript:void(0)">
                                <i class="fas fa-user-plus mr-2"></i>PPDB
                            </a>
                        </li>
                    </ul>
                </div>
                <hr class="mt-2 mb-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="userTable" style="width:100%">
                        <thead class="bg-light-slate text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">#</th>
                                <th>INFORMASI USER</th>
                                <th>EMAIL/USERNAME</th>
                                <th>HAK AKSES / ROLE</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('konfigurasi.user.form')
@include('konfigurasi.user.reset_password')
@endsection

@push('css')
<style>
    /* PREMIUM COLORS - SLATE GOLD */
    :root {
        --slate-primary: #334155;
        --slate-dark: #1e293b;
        --gold-primary: #eab308;
    }
    
    .bg-gradient-slate { background: linear-gradient(135deg, #334155 0%, #0f172a 100%) !important; }
    .bg-slate { background: #334155 !important; }
    .text-slate { color: #334155 !important; }
    .btn-slate { background: #334155; color: #fff; border: none; }
    .btn-slate:hover { background: #1e293b; color: #fff; transform: translateY(-2px); }
    .bg-light-slate { background: #f1f5f9; color: #475569; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .text-slate-gold { color: #334155; border-left: 4px solid #eab308; padding-left: 15px; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .user-avatar-large {
        width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 3px solid #fff; outline: 1px solid #e2e8f0;
    }

    /* Floating Table Rows Refined */
    #userTable { border-collapse: separate; border-spacing: 0 8px; padding: 0 20px 20px 20px; background: transparent !important; }
    #userTable tbody tr { background: #fff; transition: all 0.2s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    #userTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
    #userTable td { border: none; padding: 1rem 1.25rem; vertical-align: middle; }
    #userTable td:first-child { border-radius: 12px 0 0 12px; font-weight: bold; color: #334155; text-align: center; }
    #userTable td:last-child { border-radius: 0 12px 12px 0; }

    /* DataTables Controls Refined */
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter { padding: 15px 25px; margin-bottom: 0; }
    .dataTables_wrapper .dataTables_info { padding: 15px 25px; }
    .dataTables_wrapper .dataTables_paginate { padding: 10px 25px; }

    .user-info-box .name { font-weight: 700; color: #1e293b; font-size: 1rem; }
    .user-info-box .email { font-size: 0.8rem; color: #64748b; }

    .badge-role { background: #fef9c3; color: #854d0e; font-weight: 700; border: 1px solid #fde047; padding: 5px 12px; }
    .letter-spacing-1 { letter-spacing: 1px; }

    /* Action Buttons Soft */
    /* Premium Nav Pills */
    .nav-pills-premium .nav-link {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 700;
        color: #64748b;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        margin-bottom: 10px;
    }
    .nav-pills-premium .nav-link:hover {
        background: #f1f5f9;
        color: #334155;
    }
    .nav-pills-premium .nav-link.active {
        background: #fff !important;
        color: #334155 !important;
        border-color: #eab308;
        box-shadow: 0 4px 12px rgba(234, 179, 8, 0.15);
    }
    .nav-pills-premium .nav-link i {
        opacity: 0.7;
    }
    .nav-pills-premium .nav-link.active i {
        color: #eab308;
        opacity: 1;
    }
</style>
@endpush

@include('konfigurasi.user.scripts')
