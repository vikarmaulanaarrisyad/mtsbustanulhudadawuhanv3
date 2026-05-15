@extends($layout)

@section('title', 'Panitia & Verifikator PPDB')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ppdb.index') }}">PPDB Online</a></li>
    <li class="breadcrumb-item active">Panitia</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-users-cog mr-2 animate__animated animate__fadeInLeft"></i> 
                            Delegasi Panitia & Verifikator
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tentukan hak akses khusus bagi guru untuk memproses verifikasi berkas dan pembayaran pendaftar PPDB.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-user-shield fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Background Shapes -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #6366f1 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Guru</p>
                        <h2 class="font-weight-bold mb-0 text-indigo">{{ $teachers->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-indigo rounded-circle p-3">
                        <i class="fas fa-users text-indigo fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Verifikator Berkas</p>
                        <h2 class="font-weight-bold mb-0 text-success">
                            {{ $teachers->filter(fn($t) => $t->user->hasPermissionTo('ppdb.verify.berkas'))->count() }}
                        </h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-file-signature text-success fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Verifikator Daftar Ulang</p>
                        <h2 class="font-weight-bold mb-0 text-warning">
                            {{ $teachers->filter(fn($t) => $t->user->hasPermissionTo('ppdb.verify.daftar_ulang'))->count() }}
                        </h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-money-check-alt text-warning fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDE: INSTRUCTIONS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-info-circle mr-2 text-primary"></i> Panduan Peran
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="role-info-box mb-3 p-3 rounded-lg bg-light border-left-indigo">
                    <h6 class="font-weight-bold text-indigo mb-1"><i class="fas fa-file-alt mr-1"></i> Verifikator Berkas</h6>
                    <p class="text-xs text-muted mb-0">Bertugas memeriksa kelengkapan dokumen (KK, Ijazah, dll) dan menentukan kelulusan administrasi.</p>
                </div>
                <div class="role-info-box p-3 rounded-lg bg-light border-left-warning">
                    <h6 class="font-weight-bold text-warning mb-1"><i class="fas fa-receipt mr-1"></i> Verifikator Daftar Ulang</h6>
                    <p class="text-xs text-muted mb-0">Bertugas memvalidasi bukti pembayaran dan mengaktifkan status siswa sebagai pendaftar resmi.</p>
                </div>
                
                <div class="mt-4 p-3 rounded-lg border border-dashed border-primary bg-primary-soft">
                    <p class="text-xs text-primary mb-0 font-italic">
                        <i class="fas fa-lightbulb mr-1"></i> <strong>Tips:</strong> Guru yang diberikan akses akan secara otomatis memiliki menu "Verifikasi PPDB" di dashboard mereka.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE: TEACHER LIST -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-0 font-weight-bold text-dark">Daftar Guru & Hak Akses</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="committeeTable">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th class="pl-4 py-3">Identitas Guru</th>
                                <th class="text-center">Berkas</th>
                                <th class="text-center">Daftar Ulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td class="pl-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-3 bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center text-indigo font-weight-bold shadow-sm" style="width:40px;height:40px;border:2px solid #fff;">
                                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $teacher->name }}</div>
                                            <div class="text-xs text-muted">{{ $teacher->nip ?? 'NIP -' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input verify-toggle" 
                                               id="berkas_{{ $teacher->user_id }}" 
                                               data-user="{{ $teacher->user_id }}" 
                                               data-permission="ppdb.verify.berkas"
                                               {{ $teacher->user->hasPermissionTo('ppdb.verify.berkas') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="berkas_{{ $teacher->user_id }}"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input verify-toggle" 
                                               id="ulang_{{ $teacher->user_id }}" 
                                               data-user="{{ $teacher->user_id }}" 
                                               data-permission="ppdb.verify.daftar_ulang"
                                               {{ $teacher->user->hasPermissionTo('ppdb.verify.daftar_ulang') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="ulang_{{ $teacher->user_id }}"></label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* PREMIUM THEMES FROM PENEMPATAN ROMBEL */
    .bg-gradient-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    
    .border-left-indigo { border-left: 4px solid #4f46e5 !important; }
    .border-left-warning { border-left: 4px solid #f59e0b !important; }
    .bg-primary-soft { background: #eff6ff; }

    /* Table Styling */
    #committeeTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #committeeTable tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    #committeeTable tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transform: scale(1.005);
    }
    #committeeTable td { border: none; padding: 1rem 0.75rem; vertical-align: middle; }
    #committeeTable td:first-child { border-radius: 12px 0 0 12px; }
    #committeeTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-indigo { background: #f0f3ff; color: #5c6ba1; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-indigo { background: #eef2ff; }
    .bg-soft-success { background: #ecfdf5; }
    .bg-soft-warning { background: #fffbeb; }

    /* Custom Switch Sizes */
    .custom-switch .custom-control-label::before { height: 1.5rem; width: 2.5rem; border-radius: 1rem; cursor: pointer; }
    .custom-switch .custom-control-label::after { width: calc(1.5rem - 4px); height: calc(1.5rem - 4px); border-radius: 1rem; cursor: pointer; }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before { background-color: #4f46e5; border-color: #4f46e5; }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.verify-toggle').on('change', function() {
            const userId = $(this).data('user');
            const permission = $(this).data('permission');
            const action = $(this).prop('checked') ? 'assign' : 'revoke';
            const checkbox = $(this);

            $.post('{{ route("admin.ppdb.committee.update") }}', {
                _token: '{{ csrf_token() }}',
                user_id: userId,
                permission: permission,
                action: action
            })
            .done(res => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: res.message
                });
            })
            .fail(xhr => {
                checkbox.prop('checked', !checkbox.prop('checked')); // Revert
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            });
        });
    });
</script>
@endpush
