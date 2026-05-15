@extends('layouts.app')
@section('title', 'Koreksi Jawaban: ' . $studentExam->student->name)
@section('subtitle', $studentExam->exam->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 overflow-hidden position-relative" style="border-radius:20px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
            <div class="card-body p-4 text-white position-relative" style="z-index: 2;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <a href="{{ route('admin.cbt.exam.monitor', $studentExam->cbt_exam_id) }}" class="btn btn-sm btn-glass mb-3 rounded-pill px-3">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Monitoring
                        </a>
                        <h2 class="font-weight-bold mb-1">Koreksi Jawaban Siswa</h2>
                        <p class="mb-0 opacity-80">
                            <i class="fas fa-user-graduate mr-1"></i> {{ $studentExam->student->name }} | 
                            <i class="fas fa-chalkboard mr-1"></i> {{ $studentExam->student->classGroup->class_group ?? '-' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <small class="text-uppercase font-weight-bold opacity-70">SKOR AKHIR SAAT INI</small>
                        <h1 class="display-4 font-weight-black mb-0" id="totalScoreDisplay">{{ number_format($studentExam->final_score, 0) }}</h1>
                    </div>
                </div>
            </div>
            <div class="header-shape-1"></div>
            <div class="header-shape-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @foreach($studentExam->answers->whereIn('question.question_type', ['essay', 'uraian']) as $idx => $answer)
        <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius:18px;">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <span class="badge badge-primary rounded-pill px-3">SOAL #{{ $idx + 1 }} (Essay)</span>
                <span class="text-muted small font-weight-bold">Bobot: {{ $answer->question->score_weight }}</span>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <h6 class="text-uppercase text-xs font-weight-bold text-muted mb-2 tracking-widest">PERTANYAAN:</h6>
                    <div class="h6 font-weight-bold text-dark-blue">{!! nl2br(e($answer->question->question_text)) !!}</div>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-xs font-weight-bold text-success mb-2 tracking-widest">KUNCI JAWABAN REFERENSI:</h6>
                    <div class="bg-soft-success p-3 rounded-xl text-dark-blue small border-left-success-4">
                        {!! nl2br(e($answer->question->answer_key ?? 'Belum ada kunci jawaban.')) !!}
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                <div class="row">
                    <div class="col-md-7">
                        <h6 class="text-uppercase text-xs font-weight-bold text-primary mb-2 tracking-widest">JAWABAN SISWA:</h6>
                        <div class="p-4 bg-light rounded-2xl border min-height-100 font-italic text-dark" style="min-height: 120px;">
                            {{ $answer->student_answer ?: '(Siswa tidak menjawab)' }}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="bg-white p-4 rounded-2xl border h-100 shadow-sm">
                            <h6 class="text-uppercase text-xs font-weight-bold text-dark mb-3 tracking-widest">PENILAIAN:</h6>
                            
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">SKOR (MAKS: {{ $answer->question->score_weight }})</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.1" id="score-{{ $answer->id }}" class="form-control font-weight-black text-center rounded-xl" value="{{ $answer->score }}" max="{{ $answer->question->score_weight }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary px-4 rounded-xl ml-2 font-weight-bold" onclick="aiGrade({{ $answer->id }})" title="Koreksi Otomatis dengan AI">
                                            <i class="fas fa-brain mr-1"></i> AI
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">FEEDBACK / CATATAN GURU</label>
                                <textarea id="feedback-{{ $answer->id }}" class="form-control form-control-sm rounded-xl" rows="3" placeholder="Berikan komentar jika perlu...">{{ $answer->feedback }}</textarea>
                            </div>

                            <button class="btn btn-success btn-block rounded-xl font-weight-bold py-2 shadow-sm" onclick="saveScore({{ $answer->id }})">
                                <i class="fas fa-save mr-2"></i> SIMPAN NILAI
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if($studentExam->answers->whereIn('question.question_type', ['essay', 'uraian'])->count() == 0)
        <div class="card border-0 shadow-sm p-5 text-center" style="border-radius:20px;">
            <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
            <h4 class="font-weight-bold">Tidak Ada Soal Essay</h4>
            <p class="text-muted">Semua soal pada ujian ini adalah pilihan ganda dan telah dinilai otomatis oleh sistem.</p>
            <a href="{{ route('admin.cbt.exam.monitor', $studentExam->cbt_exam_id) }}" class="btn btn-primary rounded-pill px-5">Kembali</a>
        </div>
        @endif
    </div>
</div>

<style>
/* PREMIUM UI TOKENS */
.btn-glass { background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); }
.btn-glass:hover { background: rgba(255,255,255,0.3); color: white; }
.rounded-xl { border-radius: 12px; }
.rounded-2xl { border-radius: 20px; }
.font-weight-black { font-weight: 900; }
.text-dark-blue { color: #1e293b; }
.bg-soft-success { background: #ecfdf5; }
.border-left-success-4 { border-left: 4px solid #10b981; }
.header-shape-1 { position: absolute; width: 300px; height: 300px; top: -150px; right: -50px; background: rgba(255,255,255,0.1); border-radius: 50%; }
.header-shape-2 { position: absolute; width: 150px; height: 150px; bottom: -80px; left: 5%; background: rgba(255,255,255,0.06); border-radius: 50%; }
.tracking-widest { letter-spacing: 0.1em; }
</style>

@push('scripts')
<script>
function saveScore(answerId) {
    const score = $(`#score-${answerId}`).val();
    const feedback = $(`#feedback-${answerId}`).val();

    Swal.fire({
        title: 'Menyimpan Nilai...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.post(`/admin/cbt/exam/answer/${answerId}/score`, {
        _token: '{{ csrf_token() }}',
        score: score,
        feedback: feedback
    }).done(res => {
        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 1500, showConfirmButton: false });
        $('#totalScoreDisplay').text(Math.round(res.new_total_score));
    }).fail(err => {
        Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Terjadi kesalahan sistem.' });
    });
}

function aiGrade(answerId) {
    Swal.fire({
        title: 'AI Sedang Memproses...',
        text: 'Menganalisis jawaban siswa terhadap kunci jawaban referensi.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.post(`/admin/cbt/exam/answer/${answerId}/ai-grade`, {
        _token: '{{ csrf_token() }}'
    }).done(res => {
        if (res.success) {
            $(`#score-${answerId}`).val(res.score);
            $(`#feedback-${answerId}`).val(res.feedback);
            $('#totalScoreDisplay').text(Math.round(res.new_total_score));
            Swal.fire({ icon: 'success', title: 'Koreksi AI Selesai', text: 'Nilai dan feedback telah diisi otomatis. Jangan lupa klik SIMPAN.', customClass: { popup: 'rounded-2xl' } });
        } else {
            Swal.fire({ icon: 'error', title: 'AI Gagal', text: res.message });
        }
    }).fail(err => {
        Swal.fire({ icon: 'error', title: 'AI Error', text: err.responseJSON?.message || 'Gagal menghubungi server AI.' });
    });
}
</script>
@endpush
@endsection
