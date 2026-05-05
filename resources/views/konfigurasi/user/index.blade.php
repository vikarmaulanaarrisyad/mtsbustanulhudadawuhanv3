@extends($layout)

@section('title', 'Data Pengguna')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Data Pengguna</li>
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
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark text-slate-gold">Daftar Pengguna Sistem</h4>
                <p class="text-muted text-sm mb-0">Gunakan kolom pencarian untuk menemukan user dengan cepat</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="userTable" style="width:100%">
                        <thead class="bg-light-slate text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">#</th>
                                <th>INFORMASI USER</th>
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
    .btn-soft-info { background: #e0f2fe; color: #0ea5e9; border: none; }
    .btn-soft-primary { background: #e0e7ff; color: #4338ca; border: none; }
    .btn-soft-warning { background: #fef3c7; color: #d97706; border: none; }
    .btn-soft-danger { background: #fee2e2; color: #b91c1c; border: none; }
</style>
@endpush

@include('konfigurasi.user.scripts')
