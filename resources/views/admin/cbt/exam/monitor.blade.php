@extends('layouts.app')
@section('title', 'Live Monitoring: ' . $exam->name)
@section('subtitle', 'CBT Madrasah Digital')

@section('content')
{{-- PREMIUM HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 overflow-hidden position-relative" style="border-radius:20px; background: linear-gradient(135deg, #0f172a 0%, #334155 100%);">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-md-7 text-white">
                        <a href="{{ route('admin.cbt.exam.index') }}" class="btn btn-sm btn-glass mb-3 rounded-pill px-3">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                        <h1 class="display-5 font-weight-bold mb-1"><i class="fas fa-desktop mr-2 text-info"></i>Live Monitoring</h1>
                        <p class="mb-0 opacity-80 lead">
                            <span class="mr-3"><i class="fas fa-file-signature mr-1 text-warning"></i> {{ $exam->name }}</span>
                            <span><i class="fas fa-calendar-alt mr-1 text-info"></i> {{ $exam->exam_date ? $exam->exam_date->format('d M Y') : '-' }}</span>
                        </p>
                    </div>
                    <div class="col-md-5 text-right d-none d-md-block">
                        <div class="token-card shadow-sm animate__animated animate__pulse animate__infinite">
                            <small class="text-uppercase font-weight-bold opacity-70">TOKEN UJIAN</small>
                            <h2 class="font-weight-black mb-0 letter-spacing-2">{{ $exam->token }}</h2>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.cbt.exam.export-excel', $exam->id) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm border-0 font-weight-bold">
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </a>
                            <a href="{{ route('admin.cbt.exam.export-pdf', $exam->id) }}" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm border-0 font-weight-bold">
                                <i class="fas fa-file-pdf mr-1"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-shape-1"></div>
            <div class="header-shape-2"></div>
        </div>
    </div>
</div>

{{-- STATS SECTION --}}
<div class="row mb-4">
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-primary mr-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Total Peserta</span>
                </div>
                <h2 class="font-weight-bold mb-0" id="stat-total">{{ $exam->studentExams->count() }}</h2>
                <div class="text-xs text-muted mt-2">Siswa telah login ke ujian</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-warning mr-3">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Aktif</span>
                </div>
                <h2 class="font-weight-bold mb-0 text-warning" id="stat-doing">{{ $exam->studentExams->where('status', 'doing')->count() }}</h2>
                <div class="text-xs text-muted mt-2">Sedang mengerjakan soal</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-success mr-3">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Selesai</span>
                </div>
                <h2 class="font-weight-bold mb-0 text-success" id="stat-finished">{{ $exam->studentExams->where('status', 'finished')->count() }}</h2>
                <div class="text-xs text-muted mt-2">Telah mengirimkan jawaban</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-danger mr-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Pelanggaran</span>
                </div>
                <h2 class="font-weight-bold mb-0 text-danger" id="stat-violations">{{ $exam->studentExams->sum('violation_count') }}</h2>
                <div class="text-xs text-muted mt-2">Deteksi kecurangan sistem</div>
            </div>
        </div>
    </div>
</div>

{{-- MONITORING TABLE --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-5" style="border-radius:20px;">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">Daftar Real-time Peserta</h4>
                    <p class="text-muted text-sm mb-0 mt-1"><i class="fas fa-sync-alt fa-spin mr-1 text-info"></i> Auto-refresh aktif setiap 30 detik</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.cbt.exam.print-exam-cards', $exam->id) }}" target="_blank" class="btn btn-dark rounded-xl px-4 py-2 font-weight-bold shadow-sm mr-2">
                        <i class="fas fa-id-card mr-2 text-warning"></i> CETAK KARTU
                    </a>
                    <button class="btn btn-glass-dark rounded-xl px-4 py-2 font-weight-bold shadow-sm mr-2" onclick="openSpecialSessionModal()">
                        <i class="fas fa-plus-circle mr-2 text-warning"></i> SESI KHUSUS
                    </button>
                    <button class="btn btn-primary rounded-xl px-4 py-2 font-weight-bold shadow-sm" onclick="location.reload()">
                        <i class="fas fa-sync mr-2"></i> REFRESH
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0 premium-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pr-3 pl-4">Peserta</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">Status Login</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">Anti-Cheat</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 text-center">Nilai</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 text-center">Progress</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pl-2 pr-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exam->studentExams as $se)
                            <tr>
                                <td class="pl-4">
                                    <div class="d-flex px-0 py-1">
                                        <div class="avatar-premium-sm mr-3">
                                            <span>{{ substr($se->student->name, 0, 1) }}</span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-bold">{{ $se->student->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">NISN: {{ $se->student->nisn }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($se->status == 'finished')
                                        <span class="badge badge-pill badge-soft-success font-weight-bold">SELESAI</span>
                                    @elseif($se->status == 'doing')
                                        <span class="badge badge-pill badge-soft-warning font-weight-bold animate__animated animate__flash animate__infinite">MENGERJAKAN</span>
                                    @else
                                        <span class="badge badge-pill badge-soft-secondary font-weight-bold">STANDBY</span>
                                    @endif
                                </td>
                                <td>
                                    @if($se->violation_count == 0)
                                        <span class="text-success text-xs font-weight-bold"><i class="fas fa-shield-alt mr-1"></i> Aman</span>
                                    @else
                                        <span class="badge badge-pill badge-soft-danger font-weight-bold">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $se->violation_count }} Pelanggaran
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($se->status == 'finished')
                                        <span class="h5 font-weight-black text-primary mb-0">{{ number_format($se->final_score, 0) }}</span>
                                    @else
                                        <span class="text-xs text-muted font-italic">In Progress</span>
                                    @endif
                                </td>
                                <td class="text-center" style="min-width: 180px;">
                                    @php
                                        $totalQ = $exam->bank->questions_count ?? 0;
                                        $answeredQ = $se->answers_count ?? 0;
                                        $percent = ($totalQ > 0) ? round(($answeredQ / $totalQ) * 100) : 0;
                                        if($se->status == 'finished') $percent = 100;
                                    @endphp
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="d-flex align-items-center justify-content-between w-100 mb-1 px-2">
                                            <span class="text-xxs font-weight-bold text-muted">{{ $answeredQ }}/{{ $totalQ }} Soal</span>
                                            <span class="text-xxs font-weight-black text-{{ $percent == 100 ? 'success' : 'info' }}">{{ $percent }}%</span>
                                        </div>
                                        <div class="progress shadow-none w-100" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                                            <div class="progress-bar {{ $percent == 100 ? 'bg-success' : ($percent > 70 ? 'bg-info' : 'bg-warning') }}" role="progressbar" style="width: {{ $percent }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center pr-4">
                                    <div class="btn-group">
                                        @if($se->status == 'finished')
                                            <a href="{{ route('admin.cbt.exam.grading.show', $se->id) }}" class="btn btn-xs btn-primary rounded-pill font-weight-bold px-3 mr-1">
                                                <i class="fas fa-check-circle mr-1"></i> Koreksi
                                            </a>
                                            <a href="{{ route('admin.cbt.exam.export-student-pdf', $se->id) }}" class="btn btn-xs btn-outline-danger rounded-pill font-weight-bold px-3">
                                                <i class="fas fa-file-pdf mr-1"></i> Cetak
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-xs btn-soft-warning rounded-pill px-3 mr-1" onclick="resetExam({{ $se->id }}, '{{ $se->student->nama_lengkap }}')">
                                                <i class="fas fa-sync-alt mr-1"></i> Reset
                                            </button>
                                            <button type="button" class="btn btn-xs btn-soft-danger rounded-pill px-3" onclick="forceFinish({{ $se->id }}, '{{ $se->student->nama_lengkap }}')">
                                                <i class="fas fa-stop mr-1"></i> Selesai
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="opacity-50">
                                        <i class="fas fa-user-slash fa-3x mb-3 text-muted"></i>
                                        <p class="font-weight-bold">Belum ada peserta yang aktif.</p>
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

<style>
/* PREMIUM DESIGN TOKENS */
.letter-spacing-1 { letter-spacing: 1px; }
.letter-spacing-2 { letter-spacing: 2px; }
.font-weight-black { font-weight: 900; }
.rounded-xl { border-radius: 12px; }

/* HEADER */
.btn-glass { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s; }
.btn-glass:hover { background: rgba(255,255,255,0.25); color: white; transform: translateX(-5px); }
.header-shape-1 { position: absolute; width: 300px; height: 300px; top: -150px; right: -50px; background: rgba(0, 184, 217, 0.15); border-radius: 50%; }
.header-shape-2 { position: absolute; width: 200px; height: 200px; bottom: -100px; left: 10%; background: rgba(255, 171, 0, 0.1); border-radius: 50%; }

.token-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 15px 30px;
    display: inline-block;
    color: white;
    text-align: center;
}

/* STAT CARDS */
.stat-card-premium { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
.stat-card-premium:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
.icon-shape-premium {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* BADGES SOFT */
.btn-soft-info { background: #e0f2fe; color: #0369a1; border: none; }
.btn-soft-warning { background: #fef3c7; color: #92400e; border: none; }
.btn-soft-danger { background: #fee2e2; color: #b91c1c; border: none; }
.btn-soft-info:hover, .btn-soft-warning:hover, .btn-soft-danger:hover { filter: brightness(0.95); }

.badge-soft-primary { background: #eef2ff; color: #4f46e5; }
.badge-soft-success { background: #ecfdf5; color: #10b981; }
.badge-soft-warning { background: #fffbeb; color: #f59e0b; }
.badge-soft-danger { background: #fef2f2; color: #ef4444; }
.badge-soft-secondary { background: #f8fafc; color: #64748b; }

.bg-soft-primary { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
.bg-soft-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.bg-soft-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.bg-soft-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

/* TABLE PREMIUM */
.premium-table thead th {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 15px 20px;
}
.premium-table tbody td {
    padding: 18px 20px;
    vertical-align: middle;
}
.avatar-premium-sm {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.text-xxs { font-size: 0.65rem; }

/* AUTO REFRESH ANIMATION */
@keyframes flash-warning {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setTimeout(function(){
        // location.reload();
    }, 30000);

    function openSpecialSessionModal() {
        $('#specialSessionModal').modal('show');
    }

    function toggleStudentSelectionByKKM() {
        let kkm = $('#kkm_threshold').val() || 75;
        $('.student-checkbox').prop('checked', false);
        
        // Loop through student results in the table to identify those below KKM
        @foreach($exam->studentExams as $se)
            @if($se->status == 'finished')
                if ({{ $se->final_score }} < kkm) {
                    $('#student_chk_{{ $se->student_id }}').prop('checked', true);
                }
            @endif
        @endforeach
    }

    function selectSusulanStudents() {
        $('.student-checkbox').prop('checked', false);
        
        // Select students who are not in studentExams or status is not finished/doing
        let joinedStudentIds = [@foreach($exam->studentExams as $se) {{ $se->student_id }}, @endforeach];
        
        @foreach($allStudents as $student)
            if (!joinedStudentIds.includes({{ $student->id }})) {
                $('#student_chk_{{ $student->id }}').prop('checked', true);
            }
        @endforeach
    }

    $('#type').on('change', function() {
        if ($(this).val() === 'remedial') {
            $('#kkm_container').removeClass('d-none');
            $('#session_name').val('Remedial: {{ $exam->name }}');
            toggleStudentSelectionByKKM();
        } else {
            $('#kkm_container').addClass('d-none');
            $('#session_name').val('Susulan: {{ $exam->name }}');
            selectSusulanStudents();
        }
    });

    $('#specialSessionForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $('#btnSubmitSpecial').html('<i class="fas fa-spinner fa-spin mr-2"></i> MEMPROSES...').prop('disabled', true);

        $.ajax({
            url: '{{ route('admin.cbt.exam.store-special-session', $exam->id) }}',
            type: 'POST',
            data: formData,
            success: function(res) {
                $('#specialSessionModal').modal('hide');
                Swal.fire({
                    title: 'Sukses',
                    text: res.message,
                    icon: 'success',
                    confirmButtonText: 'Lihat Jadwal Baru'
                }).then(() => {
                    window.location.href = '{{ route('admin.cbt.exam.index') }}';
                });
            },
            error: function(err) {
                Swal.fire('Gagal', 'Terjadi kesalahan saat membuat sesi.', 'error');
            },
            complete: function() {
                $('#btnSubmitSpecial').html('<i class="fas fa-save mr-2"></i> BUAT SESI KHUSUS').prop('disabled', false);
            }
        });
    });

    function resetExam(id, name) {
        Swal.fire({
            title: 'Reset Ujian?',
            text: `Yakin ingin mereset sesi ujian ${name}? Semua jawaban akan dihapus!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route('admin.cbt.exam.student-exam.reset', '') }}/' + id, { _token: '{{ csrf_token() }}' }, function(res) {
                    location.reload();
                });
            }
        });
    }

    function forceFinish(id, name) {
        Swal.fire({
            title: 'Selesaikan Paksa?',
            text: `Hentikan sesi ujian ${name} sekarang?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesaikan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route('admin.cbt.exam.student-exam.force-finish', '') }}/' + id, { _token: '{{ csrf_token() }}' }, function(res) {
                    location.reload();
                });
            }
        });
    }
</script>
@endpush

{{-- MODAL SESI KHUSUS --}}
<div class="modal fade" id="specialSessionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-plus-circle mr-2 text-warning"></i> Buat Sesi Khusus (Remedial/Susulan)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="specialSessionForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted">TIPE SESI</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="susulan">Ujian Susulan (Bagi yang belum ikut)</option>
                                    <option value="remedial">Ujian Remedial (Bagi yang nilai rendah)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted">NAMA SESI</label>
                                <input type="text" name="name" id="session_name" class="form-control" required placeholder="Contoh: Remedial Matematika">
                            </div>
                        </div>
                    </div>

                    <div id="kkm_container" class="d-none animate__animated animate__fadeIn">
                        <div class="alert alert-info border-0 shadow-sm py-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle mr-3 fa-lg"></i>
                                <div>
                                    <label class="text-xs font-weight-bold mb-0">AMBANG BATAS NILAI (KKM)</label>
                                    <div class="input-group input-group-sm mt-1" style="width: 150px;">
                                        <input type="number" name="kkm" id="kkm_threshold" class="form-control" value="75" onchange="toggleStudentSelectionByKKM()">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Poin</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted">TANGGAL</label>
                                <input type="date" name="exam_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted">JAM MULAI</label>
                                <input type="time" name="start_time" class="form-control" required value="{{ date('H:i') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted">JAM SELESAI</label>
                                <input type="time" name="end_time" class="form-control" required value="{{ date('H:i', strtotime('+2 hours')) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">DURASI (MENIT)</label>
                        <input type="number" name="duration_minutes" class="form-control" required value="{{ $exam->duration_minutes }}">
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted mb-2">DAFTAR PESERTA TERPILIH</label>
                        <div class="student-list-container border rounded p-3" style="max-height: 250px; overflow-y: auto; background: #f8fafc;">
                            <div class="row">
                                @foreach($allStudents as $student)
                                <div class="col-md-6 mb-2">
                                    <div class="custom-control custom-checkbox student-item-card">
                                        <input type="checkbox" name="student_ids[]" class="custom-control-input student-checkbox" id="student_chk_{{ $student->id }}" value="{{ $student->id }}">
                                        <label class="custom-control-label text-sm d-flex flex-column" for="student_chk_{{ $student->id }}">
                                            <span class="font-weight-bold">{{ $student->name }}</span>
                                            <small class="text-muted">{{ $student->nisn }} | {{ $student->classGroup->class_group ?? '-' }}</small>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 font-weight-bold" id="btnSubmitSpecial">BUAT SESI KHUSUS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.btn-glass-dark { background: rgba(0,0,0,0.05); color: #334155; border: 1px solid rgba(0,0,0,0.1); transition: 0.3s; }
.btn-glass-dark:hover { background: rgba(0,0,0,0.1); color: #000; transform: translateY(-2px); }
.student-list-container::-webkit-scrollbar { width: 6px; }
.student-list-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.student-item-card { background: white; padding: 8px 12px; border-radius: 10px; border: 1px solid #e2e8f0; transition: 0.2s; }
.student-item-card:hover { border-color: #3b82f6; background: #eff6ff; }
.custom-control-input:checked ~ .student-item-card { border-color: #3b82f6; background: #eff6ff; }
</style>
@endsection
