@extends('layouts.app')

@section('title', 'Form Penilaian Kinerja')
@section('subtitle', 'E-Kinerja')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-success overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-clipboard-check mr-2 animate__animated animate__fadeInLeft"></i> 
                            Form Penilaian Kinerja
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Evaluasi kompetensi guru berdasarkan indikator standar nasional pendidikan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-star-half-alt fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- TEACHER PROFILE WIDGET -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #5e72e4 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar-circle mr-3">
                        {{ strtoupper(substr($teacher->name, 0, 2)) }}
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-dark mb-0">{{ $teacher->name }}</h5>
                        <small class="text-muted"><i class="fas fa-id-card mr-1"></i> {{ $teacher->nip ?? 'NIP: -' }}</small>
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
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Tahun Pelajaran</p>
                        <h5 class="font-weight-bold mb-0 text-warning">{{ $currentAY->academic_year ?? '-' }}</h5>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-calendar-alt text-warning fa-lg"></i>
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
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Skor Realtime</p>
                        <h5 class="font-weight-bold mb-0 text-success" id="liveScore">0%</h5>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-chart-line text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" id="liveProgressBar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="formAssessment">
    @csrf
    <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
    <input type="hidden" name="academic_year_id" value="{{ $currentAY->id }}">

    <div class="row">
        <!-- LEFT SIDEBAR: SETTINGS -->
        <div class="col-xl-4 col-lg-4 animate__animated animate__fadeInLeft">

            <!-- STEP 1: PERAN PENILAI -->
            <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title font-weight-bold text-primary mb-0">
                        <span class="step-badge bg-primary mr-2">1</span> Peran Penilai
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">TIPE PENILAI</label>
                        <select name="assessor_type" class="form-control custom-select-premium">
                            <option value="headmaster">Kepala Madrasah</option>
                            <option value="peer">Teman Sejawat</option>
                            <option value="student">Survey Siswa</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- STEP 2: CATATAN -->
            <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title font-weight-bold text-success mb-0">
                        <span class="step-badge bg-success mr-2">2</span> Catatan Evaluasi
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">SARAN & REKOMENDASI</label>
                        <textarea name="notes" class="form-control" rows="5" placeholder="Berikan saran pengembangan atau catatan khusus..." style="border-radius: 10px;"></textarea>
                    </div>
                </div>
            </div>

            <!-- STEP 3: RINGKASAN & SUBMIT -->
            <div class="card shadow-sm border-0 mb-4 premium-card sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <span class="step-badge bg-dark mr-2">3</span> Ringkasan
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div class="summary-box mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Total Indikator</span>
                            <span class="font-weight-bold" id="totalIndicators">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Sudah Diisi</span>
                            <span class="font-weight-bold text-success" id="filledCount">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Belum Diisi</span>
                            <span class="font-weight-bold text-danger" id="unfilledCount">0</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted text-sm font-weight-bold">Skor Akhir</span>
                            <span class="font-weight-bold text-primary h5 mb-0" id="finalScoreDisplay">0%</span>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitAssessment" class="btn btn-success btn-block py-3 font-weight-bold shadow-lg btn-premium" disabled>
                        <i class="fas fa-save mr-2"></i> SIMPAN PENILAIAN
                    </button>
                    <p class="text-xs text-muted text-center mt-3 mb-0">
                        <i class="fas fa-info-circle mr-1"></i> Isi semua indikator untuk mengaktifkan tombol simpan.
                    </p>

                    <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary btn-block mt-3 rounded-pill font-weight-bold">
                        <i class="fas fa-arrow-left mr-2"></i> KEMBALI
                    </a>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT: INDICATOR CARDS -->
        <div class="col-xl-8 col-lg-8 animate__animated animate__fadeInRight">
            @php $catIndex = 0; @endphp
            @foreach($indicators as $category => $items)
            @php $catIndex++; @endphp
            <div class="card shadow-sm border-0 premium-card mb-4">
                <div class="card-header bg-white py-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <span class="category-badge mr-3">{{ $catIndex }}</span>
                        <div>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $category }}</h4>
                            <p class="text-muted text-sm mb-0">{{ count($items) }} indikator penilaian</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @foreach($items as $itemIndex => $item)
                    <div class="indicator-row {{ $loop->last ? '' : 'border-bottom' }}">
                        <div class="d-flex justify-content-between align-items-start p-4">
                            <div class="flex-grow-1 mr-4">
                                <div class="d-flex align-items-start">
                                    <span class="indicator-number mr-3">{{ $itemIndex + 1 }}</span>
                                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $item->indicator_text }}</p>
                                </div>
                            </div>
                            <div class="score-buttons flex-shrink-0">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    @for($i=1; $i<=5; $i++)
                                    <label class="btn btn-score mx-1 {{ $i <= 2 ? 'score-low' : ($i <= 3 ? 'score-mid' : 'score-high') }}">
                                        <input type="radio" name="scores[{{ $item->id }}]" value="{{ $i }}" class="score-input" required> {{ $i }}
                                    </label>
                                    @endfor
                                </div>
                                <div class="text-center mt-1">
                                    <small class="text-muted" style="font-size: 9px;">KURANG &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BAIK</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</form>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-success { background: linear-gradient(135deg, #2dce89 0%, #1da36e 100%) !important; }
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
    .premium-card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-success-thick { border-left: 5px solid #2dce89 !important; }
    .border-left-primary-thick { border-left: 5px solid #5e72e4 !important; }

    /* Step Badge */
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        background: #17a2b8; color: #fff; font-size: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 50px; height: 50px; border-radius: 50%;
        background: linear-gradient(135deg, #5e72e4, #825ee4);
        color: #fff; font-weight: 700; font-size: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(94, 114, 228, 0.3);
    }

    /* Category Badge */
    .category-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 40px; height: 40px; border-radius: 12px;
        background: linear-gradient(135deg, #5e72e4, #825ee4);
        color: #fff; font-size: 16px; font-weight: 700;
        box-shadow: 0 4px 10px rgba(94, 114, 228, 0.25);
    }

    /* Indicator Number */
    .indicator-number {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 26px; height: 26px; border-radius: 8px;
        background: #f0f4ff; color: #5e72e4; font-size: 12px; font-weight: 700;
    }

    /* Indicator Row */
    .indicator-row { transition: all 0.2s ease; }
    .indicator-row:hover { background: #fafbff; }

    /* Score Buttons */
    .btn-score {
        width: 38px; height: 38px; border-radius: 10px !important;
        display: inline-flex !important; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px;
        border: 2px solid #e2e8f0 !important;
        background: #fff !important; color: #a0aec0 !important;
        transition: all 0.25s ease !important;
    }
    .btn-score:hover { transform: scale(1.1); }
    .btn-score.active.score-low { 
        background: linear-gradient(135deg, #f5365c, #f56036) !important; 
        color: #fff !important; border-color: #f5365c !important;
        box-shadow: 0 4px 12px rgba(245, 54, 92, 0.35) !important;
    }
    .btn-score.active.score-mid { 
        background: linear-gradient(135deg, #fb6340, #fbb140) !important; 
        color: #fff !important; border-color: #fb6340 !important;
        box-shadow: 0 4px 12px rgba(251, 99, 64, 0.35) !important;
    }
    .btn-score.active.score-high { 
        background: linear-gradient(135deg, #2dce89, #28b76f) !important; 
        color: #fff !important; border-color: #2dce89 !important;
        box-shadow: 0 4px 12px rgba(45, 206, 137, 0.35) !important;
    }

    /* Summary Box */
    .summary-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #e2e8f0;
    }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .progress-xs { height: 4px; border-radius: 2px; }
    .sticky-top { z-index: 100; }

    /* Disabled Button */
    #btnSubmitAssessment:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        const totalIndicators = $('input.score-input').length / 5; // 5 radio per indicator
        $('#totalIndicators').text(totalIndicators);
        $('#unfilledCount').text(totalIndicators);

        // Live score calculation
        $(document).on('change', 'input.score-input', function() {
            let filled = 0;
            let totalScore = 0;
            let maxScore = totalIndicators * 5;

            // Count filled indicators
            let checkedGroups = {};
            $('input.score-input:checked').each(function() {
                let name = $(this).attr('name');
                if (!checkedGroups[name]) {
                    checkedGroups[name] = true;
                    filled++;
                    totalScore += parseInt($(this).val());
                }
            });

            let percentage = maxScore > 0 ? ((totalScore / maxScore) * 100).toFixed(1) : 0;

            $('#filledCount').text(filled);
            $('#unfilledCount').text(totalIndicators - filled);
            $('#liveScore').text(percentage + '%');
            $('#finalScoreDisplay').text(percentage + '%');
            $('#liveProgressBar').css('width', percentage + '%');

            // Enable submit only when all filled
            if (filled >= totalIndicators) {
                $('#btnSubmitAssessment').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
            } else {
                $('#btnSubmitAssessment').prop('disabled', true);
            }
        });

        // Form submission
        $('#formAssessment').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: "Apakah Anda yakin data penilaian ini sudah benar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2dce89',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let btn = $('#btnSubmitAssessment');
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');

                    let formData = $(this).serialize();
                    $.post("{{ route('performance.store') }}", formData)
                        .done(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('performance.index') }}";
                            });
                        })
                        .fail(err => {
                            let msg = err.responseJSON?.message || 'Terjadi kesalahan sistem.';
                            Swal.fire('Gagal!', msg, 'error');
                            btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> SIMPAN PENILAIAN');
                        });
                }
            });
        });
    });
</script>
@endpush
