@extends($layout)

@section('title', 'Backup & Restore')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Backup & Restore</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-midnight overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-database mr-2 animate__animated animate__fadeInLeft"></i> 
                            Backup & Restore Center
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Amankan data institusi Anda dengan sistem cadangan multi-jalur yang cerdas.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-shield-alt fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- LEFT NAVIGATION: TABS -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-body p-2">
                <div class="nav flex-column nav-pills premium-nav-vertical">
                    <a class="nav-link active mb-2 d-flex align-items-center" data-toggle="pill" href="#tab-backups">
                        <div class="nav-icon-box bg-primary-soft text-primary"><i class="fas fa-list-ul"></i></div>
                        <span>Daftar Cadangan</span>
                    </a>
                    <a class="nav-link active d-flex align-items-center" data-toggle="pill" href="#tab-backups">
                        <div class="nav-icon-box bg-primary-soft text-primary"><i class="fas fa-list-ul"></i></div>
                        <span>Daftar Cadangan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT CONTENT: TAB PANES -->
    <div class="col-md-9">
        <div class="tab-content">
            <!-- TAB DAFTAR BACKUP -->
            <div class="tab-pane fade show active" id="tab-backups">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm border-0 premium-card h-100 bg-white overflow-hidden">
                            <div class="card-body p-4 text-center">
                                <div class="icon-circle bg-light-primary text-primary mb-3 mx-auto">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h5 class="font-weight-bold mb-1">Backup Database</h5>
                                <p class="text-muted small mb-3">Ringan & Cepat. Hanya mencadangkan skema database SQL.</p>
                                
                                <button type="button" class="btn btn-primary btn-block btn-round btn-backup-trigger mt-3" data-type="db">
                                    <i class="fas fa-bolt mr-2"></i> MULAI BACKUP
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm border-0 premium-card h-100 bg-white overflow-hidden">
                            <div class="card-body p-4 text-center border-success-soft">
                                <div class="icon-circle bg-light-success text-success mb-3 mx-auto">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="font-weight-bold mb-1">Backup Lengkap</h5>
                                <p class="text-muted small mb-3">Aman & Menyeluruh. Mencadangkan Database + Seluruh File.</p>

                                <button type="button" class="btn btn-success btn-block btn-round btn-backup-trigger mt-3" data-type="full">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> MULAI BACKUP
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 premium-card">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title font-weight-bold mb-0 text-midnight">Arsip Cadangan Lokal</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 pl-4">Berkas</th>
                                        <th class="border-0 text-center">Ukuran</th>
                                        <th class="border-0 text-center">Tgl Pembuatan</th>
                                        <th class="border-0 text-right pr-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($backups as $backup)
                                    <tr>
                                        <td class="pl-4">
                                            <div class="d-flex align-items-center">
                                                <div class="file-icon-box mr-3"><i class="fas fa-file-archive text-warning"></i></div>
                                                <div class="font-weight-bold text-midnight small">{{ $backup['file_name'] }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light-secondary font-weight-600">{{ $backup['file_size'] }}</span>
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $backup['last_modified'] }}
                                        </td>
                                        <td class="text-right pr-4">
                                            <div class="btn-group">
                                                <a href="{{ route('backup.download', $backup['file_name']) }}" class="btn btn-sm btn-light-primary btn-icon-only" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>

                                                <form action="{{ route('backup.restore', $backup['file_name']) }}" method="POST" class="d-inline restore-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light-success btn-icon-only btn-restore-backup" title="Pulihkan Database">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('backup.destroy', $backup['file_name']) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger btn-icon-only btn-delete-backup" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.3;" class="mb-3">
                                            <p class="text-muted font-weight-bold">Belum ada file cadangan yang tersimpan.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- LOADING OVERLAY --}}
<div id="backup-loading" class="backup-loading-overlay" style="display:none;">
    <div class="loading-content text-center">
        <div class="loader-box mb-4">
            <div class="dot-spinner">
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
            </div>
            <i class="fas fa-database center-icon text-white"></i>
        </div>
        <h4 class="font-weight-bold text-white mb-2">Memproses Cadangan Data...</h4>
        <p class="text-white opacity-7">Sistem sedang merangkum database dan file.<br><span class="text-warning font-weight-bold">Mohon jangan segarkan atau tutup halaman ini.</span></p>
        
        <div class="progress mt-4 mx-auto" style="width: 300px; height: 12px; border-radius: 20px; background: rgba(255,255,255,0.1);">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-success" style="width: 100%"></div>
        </div>
    </div>
</div>

@push('css_vendor')
<style>
    :root { --midnight: #0f172a; --midnight-soft: #1e293b; }
    .bg-gradient-midnight { background: linear-gradient(135deg, var(--midnight) 0%, #000000 100%); }
    .bg-midnight { background: var(--midnight) !important; color: #fff; }
    .btn-midnight { background: var(--midnight); color: #fff; }
    .btn-midnight:hover { background: var(--midnight-soft); color: #fff; }
    .text-midnight { color: var(--midnight); }
    
    .premium-card { border-radius: 18px; border: 1px solid rgba(0,0,0,0.05); }
    .nav-icon-box { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 1.1rem; }
    .bg-primary-soft { background: rgba(0, 123, 255, 0.1); }
    .bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
    .bg-info-soft { background: rgba(23, 162, 184, 0.1); }
    .bg-light-warning { background: rgba(255, 193, 7, 0.05); color: #856404; }
    
    .premium-nav-vertical .nav-link { border-radius: 14px; padding: 12px 15px; font-weight: 700; color: #64748b; margin-bottom: 8px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid transparent; }
    .premium-nav-vertical .nav-link.active { background: #fff !important; color: var(--midnight) !important; border-color: #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); transform: scale(1.02); }
    .btn-round { border-radius: 50px; padding: 12px 25px; font-weight: 800; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.8px; }
    
    .icon-circle { width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
    .file-icon-box { width: 35px; height: 35px; border-radius: 8px; background: #fff8e1; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .btn-icon-only { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: none; }
    
    /* VERTICAL STEPS */
    .vertical-steps { border-left: 2px dashed #e2e8f0; margin-left: 20px; padding-left: 30px; }
    .step-item { position: relative; }
    .step-number { position: absolute; left: -46px; top: 0; width: 32px; height: 32px; border-radius: 50%; background: var(--midnight); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; border: 4px solid #fff; }
    
    /* LOADER ANIMATION */
    .backup-loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.95); z-index: 9999; display: flex; align-items: center; justify-content: center; }
    .loader-box { position: relative; display: inline-block; }
    .center-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 2rem; }
    .bg-gradient-success { background: linear-gradient(90deg, #28a745 0%, #1e7e34 100%); }

    /* DOT SPINNER */
    .dot-spinner { position: relative; width: 6rem; height: 6rem; }
    .dot { position: absolute; width: 100%; height: 100%; transform: rotate(var(--rotation)); }
    .dot::before { content: ''; display: block; width: 15%; height: 15%; background-color: #fff; border-radius: 50%; animation: dot-fade 1.2s infinite ease-in-out; }
    .dot:nth-child(1) { --rotation: 0deg; } .dot:nth-child(1)::before { animation-delay: -1.1s; }
    .dot:nth-child(2) { --rotation: 45deg; } .dot:nth-child(2)::before { animation-delay: -1.0s; }
    .dot:nth-child(3) { --rotation: 90deg; } .dot:nth-child(3)::before { animation-delay: -0.9s; }
    .dot:nth-child(4) { --rotation: 135deg; } .dot:nth-child(4)::before { animation-delay: -0.8s; }
    .dot:nth-child(5) { --rotation: 180deg; } .dot:nth-child(5)::before { animation-delay: -0.7s; }
    .dot:nth-child(6) { --rotation: 225deg; } .dot:nth-child(6)::before { animation-delay: -0.6s; }
    .dot:nth-child(7) { --rotation: 270deg; } .dot:nth-child(7)::before { animation-delay: -0.5s; }
    .dot:nth-child(8) { --rotation: 315deg; } .dot:nth-child(8)::before { animation-delay: -0.4s; }
    @keyframes dot-fade { 0%, 39%, 100% { opacity: 0.2; } 40% { opacity: 1; } }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Custom File Label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Session Alerts
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Oke',
            customClass: { confirmButton: 'btn btn-success btn-round' },
            buttonsStyling: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Eror!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'Tutup',
            customClass: { confirmButton: 'btn btn-danger btn-round' },
            buttonsStyling: false
        });
    @endif

    // Backup Trigger
    $('.btn-backup-trigger').on('click', function() {
        let type = $(this).data('type');
        let url = type === 'db' ? "{{ route('backup.create') }}" : "{{ route('backup.create-full') }}";
        
        let confirmText = type === 'db' ? "Mencadangkan database SQL ke penyimpanan lokal." : "Mencadangkan database dan seluruh file (Lama) ke penyimpanan lokal.";

        Swal.fire({
            title: 'Mulai Cadangkan?',
            text: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Jalankan!',
            cancelButtonText: 'Batal',
            customClass: { confirmButton: 'btn btn-primary btn-round mx-2', cancelButton: 'btn btn-light btn-round mx-2' },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $('#backup-loading').fadeIn();
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#backup-loading').fadeOut();
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Oke',
                            customClass: { confirmButton: 'btn btn-success btn-round' },
                            buttonsStyling: false
                        }).then(() => { location.reload(); });
                    },
                    error: function(xhr) {
                        $('#backup-loading').fadeOut();
                        let errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem.';
                        Swal.fire({
                            title: 'Eror!',
                            text: errorMsg,
                            icon: 'error',
                            confirmButtonText: 'Tutup',
                            customClass: { confirmButton: 'btn btn-danger btn-round' },
                            buttonsStyling: false
                        });
                    }
                });
            }
        });
    });



    // Restore Confirmation
    $('.btn-restore-backup').on('click', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        
        Swal.fire({
            title: 'Pulihkan Database?',
            text: "PERINGATAN: Database saat ini akan ditimpa dengan data dari file cadangan ini. Proses ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Pulihkan Sekarang!',
            cancelButtonText: 'Batal',
            customClass: { confirmButton: 'btn btn-success btn-round mx-2', cancelButton: 'btn btn-light btn-round mx-2' },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $('#backup-loading').fadeIn();
                form.submit();
            }
        });
    });

    // Delete Confirmation
    $('.btn-delete-backup').on('click', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus Berkas?',
            text: "Data cadangan lokal ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { confirmButton: 'btn btn-danger btn-round mx-2', cancelButton: 'btn btn-light btn-round mx-2' },
            buttonsStyling: false
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    });
});
</script>
@endpush
@endsection
