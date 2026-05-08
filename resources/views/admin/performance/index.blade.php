@extends('layouts.app')

@section('title', 'E-Kinerja & PKG')
@section('subtitle', 'Manajemen SDM')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-chart-line mr-2 animate__animated animate__fadeInLeft"></i> 
                            Penilaian Kinerja Guru (PKG)
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Monitor, evaluasi, dan tingkatkan kompetensi pendidik madrasah secara terukur dan transparan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-award fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS (GLASSMORPHISM STYLE) -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Guru</p>
                        <h2 class="font-weight-bold mb-0 text-primary">{{ $totalTeachers }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-chalkboard-teacher text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Sudah Dinilai</p>
                        <h2 class="font-weight-bold mb-0 text-success">{{ $assessedCount }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-user-check text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" style="width: {{ $totalTeachers > 0 ? round(($assessedCount / $totalTeachers) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Rata-rata Skor</p>
                        <h2 class="font-weight-bold mb-0 text-warning">{{ $avgScore }}%</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-trophy text-warning fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-warning" style="width: {{ $avgScore }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: TOOLS & ASSESSMENT FORM -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        
        <!-- STEP 1: FILTER TAHUN AJARAN -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <span class="step-badge mr-2">1</span> Konfigurasi
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">TAHUN PELAJARAN</label>
                    <select id="filter_academic_year" class="form-control select2 custom-select-premium">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ ($currentAY && $ay->id == $currentAY->id) ? 'selected' : '' }}>
                                {{ $ay->academic_year }} ({{ $ay->semester->semester_name ?? 'Semester' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- STEP 2: PENILAIAN BARU -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-success mb-0">
                    <span class="step-badge bg-success mr-2">2</span> Penilaian Baru
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-muted">PILIH GURU</label>
                    <select id="selectTeacher" class="form-control select2" style="width: 100%">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" id="btnGoToForm" class="btn btn-success btn-block shadow-sm font-weight-bold py-2 btn-premium">
                    <i class="fas fa-clipboard-check mr-2"></i> BUAT FORM PENILAIAN
                </button>
            </div>
        </div>

        <!-- STEP 3: DAFTAR GURU -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-primary mb-0">
                    <span class="step-badge bg-primary mr-2">3</span> Daftar Guru
                </h5>
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <div class="list-group list-group-flush">
                    @foreach($teachers as $teacher)
                    <a href="{{ route('performance.create', ['teacher_id' => $teacher->id]) }}" class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center justify-content-between teacher-item">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm mr-3 bg-soft-info rounded-circle d-flex align-items-center justify-content-center text-info font-weight-bold" style="width:35px;height:35px;font-size:13px;">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="font-weight-medium text-dark">{{ $teacher->name }}</span>
                                <br><small class="text-muted">{{ $teacher->nip ?? 'NIP: -' }}</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-muted"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN RANKING TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">
                            <i class="fas fa-trophy text-warning mr-2"></i> Peringkat Kinerja Guru
                        </h4>
                        <p class="text-muted text-sm mb-0">TA {{ $currentAY->academic_year ?? '-' }} — {{ $assessedCount }} guru sudah dinilai</p>
                    </div>
                    <div class="d-flex align-items-center">
                <a href="{{ route('performance.export-excel') }}" class="btn btn-premium btn-success rounded-pill px-4 font-weight-bold mr-2">
                    <i class="fas fa-file-excel mr-2"></i> EXCEL
                </a>
                <a href="{{ route('performance.export-pdf') }}" class="btn btn-premium btn-danger rounded-pill px-4 font-weight-bold">
                    <i class="fas fa-file-pdf mr-2"></i> PDF
                </a>
            </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="rankingTable">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th class="text-center" width="60">RANK</th>
                                <th>GURU / TENAGA PENDIDIK</th>
                                <th class="text-center" width="120">SKOR AKHIR</th>
                                <th class="text-center" width="150">PREDIKAT</th>
                                <th class="text-center" width="120">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rankings as $index => $rank)
                            <tr>
                                <td class="text-center">
                                    @if($index == 0) 
                                        <span class="rank-badge rank-gold"><i class="fas fa-crown"></i></span>
                                    @elseif($index == 1) 
                                        <span class="rank-badge rank-silver">2</span>
                                    @elseif($index == 2) 
                                        <span class="rank-badge rank-bronze">3</span>
                                    @else 
                                        <span class="rank-badge rank-default">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-3 bg-soft-info rounded-circle d-flex align-items-center justify-content-center text-info font-weight-bold" style="width:40px;height:40px;font-size:14px;">
                                            {{ strtoupper(substr($rank->teacher->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $rank->teacher->name ?? '-' }}</div>
                                            <div class="text-xs text-muted">{{ $rank->teacher->nip ?? 'NIP: -' }} · {{ $rank->teacher->position ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <h5 class="mb-0 font-weight-bold text-{{ $rank->final_score >= 80 ? 'success' : ($rank->final_score >= 60 ? 'warning' : 'danger') }}">
                                        {{ number_format($rank->final_score, 1) }}%
                                    </h5>
                                </td>
                                <td class="text-center">
                                    @php
                                        $predikat = 'Kurang';
                                        $color = 'danger';
                                        if($rank->final_score >= 90) { $predikat = 'Amat Baik'; $color = 'success'; }
                                        elseif($rank->final_score >= 75) { $predikat = 'Baik'; $color = 'primary'; }
                                        elseif($rank->final_score >= 60) { $predikat = 'Cukup'; $color = 'warning'; }
                                    @endphp
                                    <span class="badge badge-soft-{{ $color }} rounded-pill px-3 py-2 text-uppercase small font-weight-bold">{{ $predikat }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <button class="btn btn-sm btn-soft-primary rounded-circle shadow-none btn-view-detail mr-2" 
                                                data-id="{{ $rank->teacher_id }}" 
                                                title="Lihat Detail Kinerja" style="width:35px;height:35px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('performance.export-teacher-pdf', $rank->teacher_id) }}" 
                                           class="btn btn-sm btn-soft-danger rounded-circle shadow-none" 
                                           title="Download Laporan PDF (PKG)" style="width:35px;height:35px;display:inline-flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-clipboard-list fa-4x text-muted opacity-3 mb-3"></i>
                                        <p class="text-muted font-italic mb-0">Belum ada data penilaian di semester ini.</p>
                                        <p class="text-xs text-muted">Pilih guru dari panel kiri untuk memulai penilaian baru.</p>
                                    </div>
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

<!-- INDICATOR MANAGEMENT SECTION -->
<!-- <div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">
                            <i class="fas fa-list-check text-info mr-2"></i> Manajemen Indikator PKG
                        </h4>
                        <p class="text-muted text-sm mb-0">{{ $indicators->count() }} indikator dalam {{ $indicatorsByCategory->count() }} kategori</p>
                    </div>
                    <div>
                        <a href="{{ route('performance.indicators.template') }}" class="btn btn-outline-info btn-sm rounded-pill px-3 mr-2 font-weight-bold">
                            <i class="fas fa-download mr-1"></i> Download Template
                        </a>
                        <button class="btn btn-primary btn-sm rounded-pill px-3 font-weight-bold" data-toggle="modal" data-target="#modalImportIndicator">
                            <i class="fas fa-file-excel mr-1"></i> Import Excel
                        </button>
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
                                        Bobot: {{ $item->weight }} · Target: <span class="badge badge-soft-primary rounded-pill px-2">{{ $item->target_role }}</span>
                                    </small>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-light rounded-circle shadow-xs btn-delete-indicator" data-id="{{ $item->id }}" style="width:30px;height:30px;">
                                <i class="fas fa-trash text-danger" style="font-size:11px;"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted opacity-3 mb-3"></i>
                    <p class="text-muted font-italic mb-0">Belum ada indikator penilaian.</p>
                    <p class="text-xs text-muted">Import dari Excel untuk menambahkan indikator.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div> -->

<!-- MODAL DETAIL PERFORMANCE -->
<div class="modal fade" id="modalDetailPerformance" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 bg-light-info">
                <h5 class="modal-title font-weight-bold text-dark">
                    <i class="fas fa-chart-pie text-info mr-2"></i> Detail Capaian Kinerja
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="detailContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Mengambil data...</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold" data-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .opacity-3 { opacity: 0.3; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Card Styling */
    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-success-thick { border-left: 5px solid #28a745 !important; }
    .border-left-primary-thick { border-left: 5px solid #007bff !important; }

    /* Badge Styling */
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        background: #17a2b8; color: #fff; font-size: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Rank Badges */
    .rank-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%;
        font-size: 12px; font-weight: 700;
    }
    .rank-gold { background: linear-gradient(135deg, #ffd700, #ffb300); color: #fff; box-shadow: 0 3px 8px rgba(255,215,0,0.4); }
    .rank-silver { background: linear-gradient(135deg, #c0c0c0, #a0a0a0); color: #fff; box-shadow: 0 3px 8px rgba(192,192,192,0.4); }
    .rank-bronze { background: linear-gradient(135deg, #cd7f32, #b06820); color: #fff; box-shadow: 0 3px 8px rgba(205,127,50,0.4); }
    .rank-default { background: #f0f0f0; color: #666; }
    
    /* Table Styling */
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }
    #rankingTable tbody tr { transition: all 0.2s ease; }
    #rankingTable tbody tr:hover { background: #f8fbff; transform: scale(1.005); }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .bg-soft-info { background: rgba(17, 205, 239, 0.1); }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .btn-soft-primary { background: rgba(94, 114, 228, 0.1); color: #5e72e4; border: none; transition: all 0.2s ease; }
    .btn-soft-primary:hover { background: #5e72e4; color: #fff; transform: translateY(-2px); }
    .btn-soft-danger { background: rgba(245, 54, 92, 0.1); color: #f5365c; border: none; transition: all 0.2s ease; }
    .btn-soft-danger:hover { background: #f5365c; color: #fff; transform: translateY(-2px); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    /* Soft Badges */
    .badge-soft-success { background: rgba(45, 206, 137, 0.1); color: #2dce89; }
    .badge-soft-primary { background: rgba(94, 114, 228, 0.1); color: #5e72e4; }
    .badge-soft-warning { background: rgba(251, 175, 64, 0.15); color: #e69500; }
    .badge-soft-danger { background: rgba(245, 54, 92, 0.1); color: #f5365c; }

    /* Teacher List */
    .teacher-item { transition: all 0.2s ease; }
    .teacher-item:hover { background: #f8fbff; transform: translateX(5px); }

    /* Indicator List */
    .indicator-number {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 24px; height: 24px; border-radius: 8px;
        background: #f0f4ff; color: #5e72e4; font-size: 11px; font-weight: 700;
    }
    .indicator-list-item { transition: all 0.2s ease; }
    .indicator-list-item:hover { background: #fafbff; }

    /* Progress */
    .progress-xs { height: 4px; border-radius: 2px; }
</style>
@endsection

@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function() {
        // Go to form
        $('#btnGoToForm').on('click', function() {
            let id = $('#selectTeacher').val();
            if(!id) {
                Swal.fire('Peringatan', 'Silakan pilih guru terlebih dahulu.', 'warning');
                return;
            }
            window.location.href = "{{ route('performance.create') }}?teacher_id=" + id;
        });

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
                    $('#modalImportIndicator').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false })
                        .then(() => location.reload());
                },
                error: function(err) {
                    let msg = err.responseJSON?.message || 'Terjadi kesalahan saat import.';
                    Swal.fire('Gagal!', msg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-upload mr-2"></i> IMPORT SEKARANG');
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

        // View detail modal
        $(document).on('click', '.btn-view-detail', function() {
            let teacherId = $(this).data('id');
            let btn = $(this);
            let originalContent = btn.html();
            
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin text-primary"></i>');
            
            $('#modalDetailPerformance').modal('show');
            $('#detailContent').html('<div class="text-center py-5"><div class="spinner-border text-info" role="status"></div><p class="mt-2 text-muted">Mengambil data...</p></div>');
            $.ajax({
                url: "{{ url('admin/performance') }}/" + teacherId + "/show",
                type: 'GET',
                success: function(res) {
                    let summaryHtml = '';
                    let detailsHtml = '';
                    
                    // Summary cards
                    Object.keys(res.summary).forEach(key => {
                        let data = res.summary[key];
                        let icon = key == 'headmaster' ? 'user-tie' : (key == 'peer' ? 'users' : 'user-graduate');
                        let color = key == 'headmaster' ? 'primary' : (key == 'peer' ? 'info' : 'warning');
                        let title = key.charAt(0).toUpperCase() + key.slice(1);
                        
                        summaryHtml += `
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-soft-${color} h-100" style="border-radius:12px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-${icon} text-${color} mr-2"></i>
                                            <small class="font-weight-bold text-uppercase">${title}</small>
                                        </div>
                                        <h4 class="mb-0 font-weight-bold text-${color}">${data.avg_score}%</h4>
                                        <small class="text-muted">${data.count} Penilaian</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    // Details categories
                    res.details.forEach(item => {
                        let avgScore = parseFloat(item.avg_score) || 0;
                        let colorClass = avgScore >= 4 ? 'success' : (avgScore >= 3 ? 'primary' : (avgScore >= 2 ? 'warning' : 'danger'));
                        let percentage = (avgScore / 5) * 100;
                        
                        detailsHtml += `
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-sm font-weight-bold text-dark">${item.category}</span>
                                    <span class="badge badge-soft-${colorClass}">${avgScore.toFixed(1)} / 5.0</span>
                                </div>
                                <div class="progress progress-xs bg-light">
                                    <div class="progress-bar bg-${colorClass}" style="width: ${percentage}%"></div>
                                </div>
                            </div>
                        `;
                    });

                    let content = `
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="avatar-lg bg-soft-info rounded-circle d-flex align-items-center justify-content-center text-info font-weight-bold" style="width:60px;height:60px;font-size:20px;">
                                    ${res.teacher.name.substring(0, 1).toUpperCase()}
                                </div>
                            </div>
                            <div class="col">
                                <h4 class="mb-0 font-weight-bold text-dark">${res.teacher.name}</h4>
                                <p class="text-muted mb-0">${res.teacher.nip ?? 'NIP: -'} · ${res.teacher.position ?? '-'}</p>
                                <span class="badge badge-soft-info mt-1">TA ${res.academic_year}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 border-right">
                                <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3" style="letter-spacing:1px;">Radar Kompetensi</h6>
                                <div style="height: 250px;">
                                    <canvas id="radarChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3" style="letter-spacing:1px;">Rekomendasi PKB</h6>
                                <div id="recommendationContent" class="card border-0 shadow-none bg-soft-info p-3" style="border-radius:12px; min-height: 250px;">
                                    <div class="text-center py-5">
                                        <div class="spinner-border spinner-border-sm text-info"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3 mt-4" style="letter-spacing:1px;">Rangkuman Penilai</h6>
                        <div class="row mb-4">${summaryHtml}</div>
                        
                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3" style="letter-spacing:1px;">Capaian per Kategori Indikator</h6>
                        <div class="card border-0 shadow-none bg-light p-3" style="border-radius:12px;">
                            ${detailsHtml || '<p class="text-muted italic mb-0">Belum ada detail penilaian.</p>'}
                        </div>
                    `;
                    
                    $('#detailContent').html(content);

                    // Initialize Radar Chart
                    setTimeout(() => {
                        let labels = res.details.map(d => d.category);
                        let scores = res.details.map(d => parseFloat(d.avg_score));
                        
                        new Chart(document.getElementById('radarChart'), {
                            type: 'radar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Skor Kompetensi',
                                    data: scores,
                                    fill: true,
                                    backgroundColor: 'rgba(94, 114, 228, 0.2)',
                                    borderColor: 'rgb(94, 114, 228)',
                                    pointBackgroundColor: 'rgb(94, 114, 228)',
                                    pointBorderColor: '#fff',
                                    pointHoverBackgroundColor: '#fff',
                                    pointHoverBorderColor: 'rgb(94, 114, 228)'
                                }]
                            },
                            options: {
                                maintainAspectRatio: false,
                                elements: { line: { borderWidth: 3 } },
                                scales: { r: { angleLines: { display: false }, suggestMin: 0, suggestMax: 5 } },
                                plugins: { legend: { display: false } }
                            }
                        });

                        // Generate Recommendations
                        let lowScores = res.details.filter(d => parseFloat(d.avg_score) < 4);
                        let recHtml = '<ul class="pl-3 mb-0 text-sm text-info">';
                        
                        const recommendations = {
                            'Pedagogik': 'Direkomendasikan mengikuti Workshop Strategi Pembelajaran Aktif dan Inovatif.',
                            'Profesional': 'Direkomendasikan mengikuti Pelatihan Penguasaan Materi Bidang Studi dan Literasi Digital.',
                            'Kepribadian': 'Direkomendasikan mengikuti Seminar Pengembangan Etika dan Kepribadian Pendidik.',
                            'Sosial': 'Direkomendasikan mengikuti Workshop Komunikasi Efektif dan Pengelolaan Hubungan Masyarakat.',
                            'Perencanaan': 'Direkomendasikan mengikuti Bimtek Penyusunan Administrasi Pembelajaran (RPP/Modul Ajar).',
                            'Pelaksanaan': 'Direkomendasikan mengikuti Observasi Pembelajaran Sejawat (Peer Observation).',
                            'Penilaian': 'Direkomendasikan mengikuti Pelatihan Teknik Evaluasi dan Analisis Hasil Belajar Siswa.'
                        };

                        if(lowScores.length > 0) {
                            lowScores.forEach(d => {
                                let advice = recommendations[d.category] || 'Direkomendasikan mengikuti diklat fungsional atau workshop relevan.';
                                recHtml += `<li class="mb-2"><b>${d.category}</b>: ${advice}</li>`;
                            });
                        } else {
                            recHtml += '<li>Luar biasa! Seluruh aspek kompetensi telah melampaui standar. Pertahankan kinerja dan teruslah menjadi inspirasi bagi rekan sejawat.</li>';
                        }
                        recHtml += '</ul>';
                        $('#recommendationContent').html(recHtml);
                    }, 100);
                },
                error: function(err) {
                    $('#detailContent').html('<div class="alert alert-danger">Gagal memuat data. Silakan coba lagi.</div>');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalContent);
                }
            });
        });
    });
</script>
@endpush
