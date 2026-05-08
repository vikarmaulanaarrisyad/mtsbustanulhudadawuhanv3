@extends('layouts.app')

@section('title', 'Indikator PKG')
@section('subtitle', 'Manajemen SDM')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-purple overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-list-check mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Indikator PKG
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola indikator penilaian kinerja guru melalui import Excel atau input manual.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-tasks fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #5e72e4 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Indikator</p>
                        <h2 class="font-weight-bold mb-0 text-primary">{{ $indicators->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-clipboard-list text-primary fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #2dce89 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Jumlah Kategori</p>
                        <h2 class="font-weight-bold mb-0 text-success">{{ $indicatorsByCategory->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-tags text-success fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Bobot Rata-rata</p>
                        <h2 class="font-weight-bold mb-0 text-warning">{{ $indicators->count() > 0 ? round($indicators->avg('weight'), 1) : 0 }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-balance-scale text-warning fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: IMPORT & ADD -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">

        <!-- STEP 1: IMPORT EXCEL -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-success mb-0">
                    <span class="step-badge bg-success mr-2">1</span> Import dari Excel
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="alert alert-light border text-sm mb-3" style="border-radius: 10px;">
                    <i class="fas fa-info-circle text-info mr-1"></i>
                    Format kolom: <strong>kategori</strong>, <strong>indikator</strong>, <strong>bobot</strong>, <strong>target_role</strong>
                    <br><small class="text-muted">Data duplikat akan otomatis dilewati.</small>
                </div>
                <a href="{{ route('performance.indicators.template') }}" class="btn btn-outline-info btn-block mb-3 font-weight-bold btn-premium">
                    <i class="fas fa-download mr-2"></i> DOWNLOAD TEMPLATE
                </a>
                <form id="formImportIndicator" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">PILIH FILE EXCEL</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="importFile" style="border-radius: 10px;">Pilih file...</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block font-weight-bold py-2 btn-premium" id="btnImportSubmit">
                        <i class="fas fa-upload mr-2"></i> IMPORT SEKARANG
                    </button>
                </form>
            </div>
        </div>

        <!-- STEP 2: TAMBAH MANUAL -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-primary mb-0">
                    <span class="step-badge bg-primary mr-2">2</span> Tambah Manual
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="formAddIndicator">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">KATEGORI</label>
                        <input type="text" name="category" class="form-control" placeholder="misal: Pedagogik" required style="border-radius: 10px;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">INDIKATOR / PERTANYAAN</label>
                        <textarea name="indicator_text" class="form-control" rows="3" placeholder="Teks indikator penilaian..." required style="border-radius: 10px;"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted uppercase">BOBOT</label>
                                <input type="number" name="weight" class="form-control" value="1" min="1" max="5" style="border-radius: 10px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted uppercase">TARGET ROLE</label>
                                <select name="target_role" class="form-control" style="border-radius: 10px;">
                                    <option value="guru">Guru</option>
                                    <option value="tendik">Tendik</option>
                                    <option value="all">Semua</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 btn-premium" id="btnAddSubmit">
                        <i class="fas fa-plus mr-2"></i> TAMBAH INDIKATOR
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN: INDICATOR LIST -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">
                            <i class="fas fa-clipboard-list text-info mr-2"></i> Daftar Indikator Penilaian
                        </h4>
                        <p class="text-muted text-sm mb-0">{{ $indicators->count() }} indikator dalam {{ $indicatorsByCategory->count() }} kategori</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @forelse($indicatorsByCategory as $category => $items)
                <div class="indicator-category-section">
                    <div class="px-4 py-3 bg-light-info d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold text-uppercase small" style="letter-spacing: 1px;">
                            <i class="fas fa-tag mr-2"></i> {{ $category }}
                        </span>
                        <span class="badge badge-pill badge-info">{{ $items->count() }} indikator</span>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($items as $item)
                        <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center justify-content-between indicator-list-item" id="indicator-{{ $item->id }}">
                            <div class="d-flex align-items-start flex-grow-1 mr-3">
                                <span class="indicator-number mr-3">{{ $loop->iteration }}</span>
                                <div>
                                    <p class="mb-0 text-dark">{{ $item->indicator_text }}</p>
                                    <small class="text-muted">
                                        Bobot: <span class="font-weight-bold">{{ $item->weight }}</span> · 
                                        Target: <span class="badge badge-soft-primary rounded-pill px-2">{{ $item->target_role }}</span>
                                    </small>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-light rounded-circle shadow-xs btn-delete-indicator" data-id="{{ $item->id }}" style="width:32px;height:32px;" title="Hapus">
                                <i class="fas fa-trash text-danger" style="font-size:11px;"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted opacity-3 mb-3 d-block"></i>
                    <p class="text-muted font-italic mb-1">Belum ada indikator penilaian.</p>
                    <p class="text-xs text-muted">Import dari Excel atau tambah manual dari panel kiri.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-purple { background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .opacity-3 { opacity: 0.3; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-success-thick { border-left: 5px solid #2dce89 !important; }
    .border-left-primary-thick { border-left: 5px solid #5e72e4 !important; }

    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        background: #17a2b8; color: #fff; font-size: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    .badge-soft-primary { background: rgba(94, 114, 228, 0.1); color: #5e72e4; }

    .indicator-number {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 24px; height: 24px; border-radius: 8px;
        background: #f0f4ff; color: #5e72e4; font-size: 11px; font-weight: 700;
    }
    .indicator-list-item { transition: all 0.2s ease; }
    .indicator-list-item:hover { background: #fafbff; }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        // Custom file input label
        $('#importFile').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').text(fileName || 'Pilih file...');
        });

        // Import form
        $('#formImportIndicator').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let btn = $('#btnImportSubmit');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Mengimport...');

            $.ajax({
                url: "{{ route('performance.indicators.import') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false })
                        .then(() => location.reload());
                },
                error: function(err) {
                    Swal.fire('Gagal!', err.responseJSON?.message || 'Terjadi kesalahan saat import.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-upload mr-2"></i> IMPORT SEKARANG');
                }
            });
        });

        // Add manual indicator
        $('#formAddIndicator').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#btnAddSubmit');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');

            $.ajax({
                url: "{{ route('performance.indicators.store-single') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                },
                error: function(err) {
                    Swal.fire('Gagal!', err.responseJSON?.message || 'Error', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-plus mr-2"></i> TAMBAH INDIKATOR');
                }
            });
        });

        // Delete indicator
        $(document).on('click', '.btn-delete-indicator', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Indikator?',
                text: 'Indikator ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f5365c',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/performance/indicators') }}/" + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            $('#indicator-' + id).slideUp(300, function() { $(this).remove(); });
                            Swal.fire({ icon: 'success', title: 'Dihapus!', text: res.message, timer: 1500, showConfirmButton: false });
                        },
                        error: function(err) {
                            Swal.fire('Gagal!', err.responseJSON?.message || 'Error', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
