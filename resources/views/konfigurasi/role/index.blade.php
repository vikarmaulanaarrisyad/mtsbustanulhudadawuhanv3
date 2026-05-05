@extends($layout)

@section('title', 'Konfigurasi')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Role</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-crimson overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-tag mr-2 animate__animated animate__fadeInLeft"></i> 
                            Hak Akses & Role
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Definisikan peran pengguna dan atur izin akses modul secara mendetail.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-key fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- LEFT SIDEBAR: TOOLS -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0 premium-card mb-4 text-center py-4 bg-white">
            <div class="card-body">
                <div class="role-icon-box mx-auto mb-3 shadow-sm">
                    <i class="fas fa-shield-alt fa-3x text-crimson"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1">Kontrol Keamanan</h5>
                <p class="text-muted text-xs mb-4 uppercase letter-spacing-1">Role-Based Access Control</p>
                
                @can('role.create')
                <button onclick="addFormRole(`{{ route('role.store') }}`)" class="btn btn-crimson btn-block rounded-pill shadow-sm font-weight-bold py-2 mb-3 text-white">
                    <i class="fas fa-plus-circle mr-2"></i> TAMBAH ROLE BARU
                </button>
                @endcan
                
                <hr class="my-4">
                
                <div class="text-left">
                    <h6 class="text-xs font-weight-bold text-muted uppercase mb-3">Panduan Izin</h6>
                    <div class="d-flex mb-3">
                        <div class="icon-sm mr-3 text-crimson"><i class="fas fa-check-double"></i></div>
                        <div>
                            <p class="text-xs font-weight-bold text-dark mb-0">Granular Permissions</p>
                            <p class="text-xs text-muted mb-0">Setiap role dapat memiliki banyak izin akses modul.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="icon-sm mr-3 text-warning"><i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <p class="text-xs font-weight-bold text-dark mb-0">Hati-hati</p>
                            <p class="text-xs text-muted mb-0">Perubahan pada role akan berdampak langsung pada akses user.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: ROLE TABLE -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0 premium-card h-100">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark text-crimson-gold">Daftar Role Tersedia</h4>
                <p class="text-muted text-sm mb-0">Kelola guard name dan izin untuk masing-masing role</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="roleTable" style="width:100%">
                        <thead class="bg-light-crimson text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">#</th>
                                <th>NAMA ROLE</th>
                                <th>GUARD</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('konfigurasi.role.form')
@endsection

@push('css')
<style>
    /* PREMIUM COLORS - CRIMSON */
    :root {
        --crimson-primary: #dc2626;
        --crimson-dark: #991b1b;
        --gold-light: #fef9c3;
    }
    
    .bg-gradient-crimson { background: linear-gradient(135deg, #dc2626 0%, #7f1d1d 100%) !important; }
    .bg-crimson { background: #dc2626 !important; }
    .text-crimson { color: #dc2626 !important; }
    .btn-crimson { background: #dc2626; color: #fff; border: none; }
    .btn-crimson:hover { background: #b91c1c; color: #fff; transform: translateY(-2px); }
    .bg-light-crimson { background: #fef2f2; color: #991b1b; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .text-crimson-gold { color: #7f1d1d; border-left: 4px solid #f59e0b; padding-left: 15px; }

    .premium-card { border-radius: 20px; overflow: hidden; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .role-icon-box {
        width: 80px; height: 80px; background: #fef2f2; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 3px solid #fff; outline: 1px solid #fee2e2;
    }

    /* Floating Table Rows Refined */
    #roleTable { border-collapse: separate; border-spacing: 0 8px; padding: 0 20px 20px 20px; background: transparent !important; }
    #roleTable tbody tr { background: #fff; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    #roleTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
    #roleTable td { border: none; padding: 1.25rem 1rem; vertical-align: middle; }
    #roleTable td:first-child { border-radius: 12px 0 0 12px; font-weight: bold; color: #dc2626; text-align: center; }
    #roleTable td:last-child { border-radius: 0 12px 12px 0; }

    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter { padding: 15px 25px; margin-bottom: 0; }
    .dataTables_wrapper .dataTables_info { padding: 15px 25px; }
    .dataTables_wrapper .dataTables_paginate { padding: 10px 25px; }

    .letter-spacing-1 { letter-spacing: 1px; }

    /* Action Buttons Soft */
    .btn-soft-info { background: #e0f2fe; color: #0ea5e9; border: none; }
    .btn-soft-primary { background: #e0e7ff; color: #4338ca; border: none; }
    .btn-soft-danger { background: #fee2e2; color: #b91c1c; border: none; }
</style>
@endpush

@include('konfigurasi.role.scripts')
