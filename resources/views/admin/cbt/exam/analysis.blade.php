@extends('layouts.app')
@section('title', 'Analisis Butir Soal: ' . $exam->name)
@section('subtitle', 'CBT Madrasah Digital')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 overflow-hidden position-relative" style="border-radius:20px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <a href="{{ route('admin.cbt.exam.index') }}" class="btn btn-sm btn-glass mb-3 rounded-pill px-3">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                        <h1 class="display-5 font-weight-bold mb-1"><i class="fas fa-chart-bar mr-2 text-warning"></i>Analisis Butir Soal</h1>
                        <p class="mb-0 opacity-80 lead">
                            Evaluasi kualitas soal berdasarkan data statistik nyata dari <strong>{{ $totalStudents }}</strong> peserta ujian.
                        </p>
                    </div>
                </div>
            </div>
            <div class="header-shape-1"></div>
            <div class="header-shape-2"></div>
        </div>
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-body p-4 text-center">
                <div class="icon-shape bg-soft-info rounded-circle mb-3 mx-auto" style="width:60px; height:60px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-brain text-info fa-2x"></i>
                </div>
                <h6 class="text-muted text-uppercase small font-weight-bold">Status Bank Soal</h6>
                @php
                    $goodCount = collect($analysisData)->where('status', 'success')->count();
                    $totalQ = count($analysisData);
                    $percentage = ($totalQ > 0) ? round(($goodCount / $totalQ) * 100) : 0;
                @endphp
                <h2 class="font-weight-bold mb-0 text-{{ $percentage > 70 ? 'success' : 'warning' }}">{{ $percentage }}% Layak</h2>
                <p class="text-xs text-muted mt-2">{{ $goodCount }} dari {{ $totalQ }} soal memiliki kualitas baik.</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-body p-4">
                <h6 class="text-muted text-uppercase small font-weight-bold mb-3">Distribusi Kesukaran</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-xs font-weight-bold">Mudah</span>
                    <span class="text-xs font-weight-bold">{{ collect($analysisData)->where('difficulty_label', 'Mudah')->count() }} Soal</span>
                </div>
                <div class="progress mb-3" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar bg-success" style="width: {{ (collect($analysisData)->where('difficulty_label', 'Mudah')->count() / $totalQ) * 100 }}%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-xs font-weight-bold">Sedang</span>
                    <span class="text-xs font-weight-bold">{{ collect($analysisData)->where('difficulty_label', 'Sedang')->count() }} Soal</span>
                </div>
                <div class="progress mb-3" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar bg-warning" style="width: {{ (collect($analysisData)->where('difficulty_label', 'Sedang')->count() / $totalQ) * 100 }}%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-xs font-weight-bold">Sukar</span>
                    <span class="text-xs font-weight-bold">{{ collect($analysisData)->where('difficulty_label', 'Sukar')->count() }} Soal</span>
                </div>
                <div class="progress" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar bg-danger" style="width: {{ (collect($analysisData)->where('difficulty_label', 'Sukar')->count() / $totalQ) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ANALYSIS TABLE --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-5" style="border-radius:20px;">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                <h4 class="mb-0 font-weight-bold text-dark">Data Statistik Per Butir Soal</h4>
                <div class="badge badge-soft-primary px-3 py-2 rounded-pill font-weight-bold">
                    <i class="fas fa-info-circle mr-1"></i> Data dihitung otomatis dari jawaban siswa
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-xs font-weight-bold text-muted text-uppercase text-center" width="5%">No</th>
                                <th class="text-xs font-weight-bold text-muted text-uppercase" width="40%">Pertanyaan</th>
                                <th class="text-xs font-weight-bold text-muted text-uppercase text-center">Tingkat Kesukaran</th>
                                <th class="text-xs font-weight-bold text-muted text-uppercase text-center">Daya Pembeda</th>
                                <th class="text-xs font-weight-bold text-muted text-uppercase text-center">Status</th>
                                <th class="text-xs font-weight-bold text-muted text-uppercase text-center">Aksi AI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analysisData as $index => $item)
                            <tr>
                                <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                <td class="py-3">
                                    <div class="text-sm font-weight-bold mb-1 text-dark text-truncate" style="max-width: 400px;">
                                        {!! strip_tags($item['question']->question_text) !!}
                                    </div>
                                    <div class="text-xxs text-muted">
                                        <i class="fas fa-check-circle text-success mr-1"></i> {{ $item['correct_count'] }} Benar dari {{ $item['total_answered'] }} Jawaban
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge badge-pill badge-soft-{{ $item['difficulty'] < 0.3 ? 'danger' : ($item['difficulty'] > 0.7 ? 'success' : 'info') }} font-weight-bold mb-1">
                                            {{ $item['difficulty_label'] }}
                                        </span>
                                        <small class="text-muted font-weight-bold">P: {{ number_format($item['difficulty'], 2) }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge badge-pill badge-soft-{{ $item['discrimination'] < 0.2 ? 'danger' : ($item['discrimination'] < 0.4 ? 'warning' : 'success') }} font-weight-bold mb-1">
                                            {{ $item['discrimination_label'] }}
                                        </span>
                                        <small class="text-muted font-weight-bold">D: {{ number_format($item['discrimination'], 2) }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($item['status'] == 'success')
                                        <span class="badge badge-success rounded-pill px-3 font-weight-bold">LAYAK</span>
                                    @elseif($item['status'] == 'warning')
                                        <span class="badge badge-warning rounded-pill px-3 font-weight-bold text-white">REVISI</span>
                                    @else
                                        <span class="badge badge-danger rounded-pill px-3 font-weight-bold">DIBUANG</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-soft-primary rounded-pill px-3 font-weight-bold" 
                                            onclick="aiAnalyze({{ $item['question']->id }}, {{ json_encode($item) }})">
                                        <i class="fas fa-robot mr-1"></i> Saran AI
                                    </button>
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

{{-- MODAL AI ADVICE --}}
<div class="modal fade" id="aiAdviceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-soft-primary border-0 py-3">
                <h5 class="modal-title font-weight-bold text-primary"><i class="fas fa-robot mr-2"></i> Analisis Pedagogis AI</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="p-3 bg-light rounded-lg">
                            <h6 class="text-xs font-weight-bold text-muted text-uppercase mb-2">Soal Terpilih:</h6>
                            <div id="selected_question_text" class="font-weight-bold text-dark"></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="text-center p-2 border rounded-lg">
                            <small class="text-muted d-block mb-1">Kesukaran</small>
                            <span id="stat_p_label" class="badge badge-pill"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 border rounded-lg">
                            <small class="text-muted d-block mb-1">Daya Pembeda</small>
                            <span id="stat_d_label" class="badge badge-pill"></span>
                        </div>
                    </div>
                </div>
                <div class="ai-response-container p-4 rounded-lg position-relative" style="background: #f8fafc; min-height: 200px;">
                    <div id="ai_loading" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="text-muted font-weight-bold">AI sedang menganalisis data statistik dan pedagogi...</p>
                    </div>
                    <div id="ai_result" class="text-dark" style="line-height: 1.6; font-size: 0.95rem; white-space: pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-primary rounded-pill px-4 font-weight-bold" data-dismiss="modal">DIMENGERTI</button>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-glass { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s; }
    .btn-glass:hover { background: rgba(255,255,255,0.25); color: white; transform: translateX(-5px); }
    .header-shape-1 { position: absolute; width: 300px; height: 300px; top: -150px; right: -50px; background: rgba(255, 171, 0, 0.1); border-radius: 50%; }
    .header-shape-2 { position: absolute; width: 200px; height: 200px; bottom: -100px; left: 10%; background: rgba(0, 184, 217, 0.1); border-radius: 50%; }
    .badge-soft-primary { background: #e0f2fe; color: #0369a1; }
    .badge-soft-success { background: #ecfdf5; color: #047857; }
    .badge-soft-warning { background: #fff7ed; color: #c2410c; }
    .badge-soft-danger { background: #fef2f2; color: #b91c1c; }
    .badge-soft-info { background: #e0f2fe; color: #0369a1; }
    .bg-soft-primary { background: rgba(14, 165, 233, 0.1); }
    .bg-soft-info { background: rgba(14, 165, 233, 0.1); }
    .text-xxs { font-size: 0.65rem; }
</style>

@push('scripts')
<script>
    function aiAnalyze(questionId, stats) {
        $('#selected_question_text').html(stats.question.question_text);
        $('#stat_p_label').text(stats.difficulty_label).removeClass().addClass('badge badge-pill badge-soft-' + (stats.difficulty < 0.3 ? 'danger' : (stats.difficulty > 0.7 ? 'success' : 'info')));
        $('#stat_d_label').text(stats.discrimination_label).removeClass().addClass('badge badge-pill badge-soft-' + (stats.discrimination < 0.2 ? 'danger' : (stats.discrimination < 0.4 ? 'warning' : 'success')));
        
        $('#aiAdviceModal').modal('show');
        $('#ai_result').empty();
        $('#ai_loading').removeClass('d-none');

        $.ajax({
            url: `{{ route('admin.cbt.exam.item-analysis.ai', [$exam->id, ':id']) }}`.replace(':id', questionId),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                difficulty: stats.difficulty,
                discrimination: stats.discrimination,
                difficulty_label: stats.difficulty_label,
                discrimination_label: stats.discrimination_label
            },
            success: function(res) {
                $('#ai_result').html(res.advice);
            },
            error: function(err) {
                $('#ai_result').html('<div class="text-danger">Gagal menghubungi AI. Silakan periksa konfigurasi API Anda.</div>');
            },
            complete: function() {
                $('#ai_loading').addClass('d-none');
            }
        });
    }
</script>
@endpush
@endsection
