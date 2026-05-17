@extends($layout)

@section('title', 'Koreksi Essay - ' . $studentExam->student->nama_lengkap)

@section('content')
<div class="dashboard-wrapper pb-20">
    <!-- HEADER -->
    <div class="header-banner bg-grad-indigo pt-10 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('guru.cbt.grading.show', $studentExam->cbt_exam_id) }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all border border-white/10">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="text-white">
                        <h1 class="text-2xl font-black tracking-tight leading-tight">{{ $studentExam->student->nama_lengkap }}</h1>
                        <p class="text-white/70 text-xs font-bold mt-1 uppercase tracking-widest">{{ $studentExam->exam->name }} • {{ $essayAnswers->count() }} Pertanyaan Essay</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/20 text-center min-w-[140px]">
                        <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Skor Akhir</span>
                        <h2 class="text-2xl font-black text-white" id="display-total-score">{{ number_format($studentExam->final_score, 1) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN GRADING INTERFACE -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        <div class="space-y-10">
            @foreach($essayAnswers as $index => $ans)
                <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden group" id="answer-container-{{ $ans->id }}">
                    <div class="flex flex-col lg:flex-row">
                        <!-- LEFT: QUESTION & KEY (40%) -->
                        <div class="lg:w-5/12 p-8 md:p-12 bg-slate-50 border-r border-slate-100">
                            <div class="flex items-center space-x-4 mb-8">
                                <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-black text-lg shadow-lg shadow-indigo-100">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest block mb-0.5">Tipe</span>
                                    <span class="text-xs font-black text-indigo-600 uppercase tracking-widest">{{ $ans->question->question_type }}</span>
                                </div>
                            </div>

                            <div class="mb-10">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Pertanyaan</h4>
                                <div class="text-lg font-bold text-slate-800 leading-relaxed">
                                    {!! $ans->question->question_text !!}
                                </div>
                                @if($ans->question->question_image)
                                    <img src="{{ Storage::url($ans->question->question_image) }}" class="mt-4 rounded-2xl border border-slate-200 shadow-sm max-h-48 object-contain">
                                @endif
                            </div>

                            <div class="bg-indigo-50/50 rounded-3xl p-6 border border-indigo-100/50">
                                <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                                    <i class="fas fa-key mr-2"></i> Kunci Jawaban
                                </h4>
                                <div class="text-sm font-bold text-indigo-800 leading-relaxed">
                                    {{ $ans->question->answer_key ?? 'Kunci jawaban tidak tersedia.' }}
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT: STUDENT ANSWER & GRADING (60%) -->
                        <div class="lg:w-7/12 p-8 md:p-12">
                            <div class="mb-10">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center justify-between">
                                    <span>Jawaban Siswa</span>
                                    <span class="text-slate-300">Max Score: {{ $ans->question->score_weight }}</span>
                                </h4>
                                <div class="bg-slate-900 rounded-3xl p-8 text-white min-h-[200px] shadow-inner text-lg font-medium leading-relaxed">
                                    @if($ans->answer_text)
                                        {!! nl2br(e($ans->answer_text)) !!}
                                    @else
                                        <div class="flex flex-col items-center justify-center h-full text-slate-500 italic py-10">
                                            <i class="fas fa-comment-slash text-4xl mb-4"></i>
                                            <span>Siswa tidak memberikan jawaban teks.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Grading Form -->
                            <div class="space-y-6">
                                <div class="row g-6">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Berikan Skor</label>
                                            <div class="relative">
                                                <input type="number" step="0.5" max="{{ $ans->question->score_weight }}" 
                                                       class="score-input w-full h-16 bg-white border-2 border-slate-100 rounded-2xl px-6 text-xl font-black text-slate-800 focus:border-indigo-500 focus:ring-8 focus:ring-indigo-50 transition-all" 
                                                       value="{{ $ans->score ?? 0 }}" data-id="{{ $ans->id }}" data-qid="{{ $ans->cbt_question_id }}" data-max="{{ $ans->question->score_weight }}">
                                                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 font-black text-xs uppercase tracking-widest">
                                                    / {{ $ans->question->score_weight }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Catatan Guru (Feedback)</label>
                                            <textarea class="feedback-input w-full bg-white border-2 border-slate-100 rounded-2xl p-6 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-8 focus:ring-indigo-50 transition-all" 
                                                      rows="2" placeholder="Tuliskan saran perbaikan untuk siswa..." data-id="{{ $ans->id }}" data-qid="{{ $ans->cbt_question_id }}">{{ $ans->feedback }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-slate-50">
                                    <button onclick="triggerAiGrading('{{ $ans->id }}', '{{ $ans->cbt_question_id }}')" class="w-full sm:w-auto px-8 py-4 bg-emerald-50 text-emerald-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center border border-emerald-100">
                                        <i class="fas fa-robot mr-3"></i> Minta Saran AI
                                    </button>
                                    <button onclick="saveSingleGrade('{{ $ans->id }}', '{{ $ans->cbt_question_id }}')" class="save-btn w-full sm:w-auto px-12 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-indigo-100 flex items-center justify-center">
                                        Simpan Nilai <i class="fas fa-save ml-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('guru.cbt.grading.show', $studentExam->cbt_exam_id) }}" class="inline-flex h-16 px-16 bg-slate-900 text-white rounded-3xl font-black text-xs uppercase tracking-widest items-center justify-center hover:bg-indigo-600 transition-all shadow-2xl">
                Selesai Koreksi & Kembali
            </a>
        </div>
    </div>
</div>

<style>
    .bg-grad-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<script>
    function saveSingleGrade(answerId, questionId) {
        const score = $(`.score-input[data-qid="${questionId}"]`).val();
        const feedback = $(`.feedback-input[data-qid="${questionId}"]`).val();
        const max = $(`.score-input[data-qid="${questionId}"]`).data('max');

        if (score > max) {
            Swal.fire({ icon: 'error', title: 'Skor Melebihi Maksimal', text: `Skor maksimal untuk soal ini adalah ${max}`, customClass: { popup: 'rounded-[2rem]' } });
            return;
        }

        const btn = $(`#answer-container-${answerId} .save-btn`);
        const originalContent = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.post(`{{ route('guru.cbt.grading.save') }}`, {
            _token: '{{ csrf_token() }}',
            score: score,
            feedback: feedback,
            student_exam_id: '{{ $studentExam->id }}',
            question_id: questionId
        }).done(res => {
            btn.html(originalContent).prop('disabled', false);
            $('#display-total-score').text(parseFloat(res.new_total_score).toFixed(1));
            
            // Success animation on card
            $(`#answer-container-${answerId}`).addClass('ring-4 ring-emerald-500/20');
            setTimeout(() => $(`#answer-container-${answerId}`).removeClass('ring-4 ring-emerald-500/20'), 3000);

            toastr.success('Nilai berhasil disimpan');
        }).fail(err => {
            btn.html(originalContent).prop('disabled', false);
            toastr.error('Gagal menyimpan nilai');
        });
    }

    function triggerAiGrading(answerId, questionId) {
        Swal.fire({
            title: 'Minta Bantuan AI?',
            text: "AI akan menganalisis jawaban siswa berdasarkan kunci jawaban. Hasilnya bisa Anda edit kembali.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'YA, ANALISIS',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[3rem]' }
        }).then(res => {
            if (res.isConfirmed) {
                Swal.fire({
                    title: 'AI Sedang Berpikir...',
                    html: 'Menganalisis kualitas jawaban...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                    customClass: { popup: 'rounded-[3rem]' }
                });

                $.post(`{{ route('guru.cbt.grading.ai') }}`, {
                    _token: '{{ csrf_token() }}',
                    student_exam_id: '{{ $studentExam->id }}',
                    question_id: questionId
                }).done(res => {
                    Swal.close();
                    if (res.success) {
                        $(`.score-input[data-qid="${questionId}"]`).val(res.score).addClass('ring-8 ring-emerald-100 border-emerald-500');
                        $(`.feedback-input[data-qid="${questionId}"]`).val(res.feedback).addClass('ring-8 ring-emerald-100 border-emerald-500');
                        
                        setTimeout(() => {
                            $(`.score-input[data-qid="${questionId}"], .feedback-input[data-qid="${questionId}"]`).removeClass('ring-8 ring-emerald-100 border-emerald-500');
                        }, 2000);

                        toastr.success('Analisis AI Berhasil');
                    } else {
                        toastr.error(res.message);
                    }
                }).fail(err => {
                    Swal.close();
                    toastr.error('Gagal menghubungi layanan AI');
                });
            }
        });
    }
</script>
@endsection
