@extends('layouts.ppdb')

@section('title', 'Ujian: ' . $exam->name)

@section('content')
<div class="cbt-container fixed inset-0 z-50 bg-slate-50 flex flex-col hidden" id="cbt-engine">
    <!-- Topbar -->
    <div class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold">
                CBT
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-800">{{ $exam->name }}</h1>
                <p class="text-xs text-slate-500 font-medium">{{ $exam->bank->subject->name ?? 'Mata Pelajaran' }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="flex flex-col items-end">
                <span class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-1">Sisa Waktu</span>
                <div class="text-2xl font-black text-rose-600 font-mono tracking-wider" id="timer">--:--:--</div>
            </div>
            <button onclick="finishExam()" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                Selesai Ujian
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Left: Question Area -->
        <div class="flex-1 overflow-y-auto p-8 relative" id="question-area">
            @foreach($exam->bank->questions as $index => $q)
                <div class="question-panel hidden" id="q-panel-{{ $index + 1 }}" data-qid="{{ $q->id }}">
                    <div class="mb-6 flex items-center justify-between">
                        <span class="px-4 py-1.5 bg-slate-100 text-slate-600 font-black rounded-lg text-sm">Soal No. {{ $index + 1 }}</span>
                        
                        <label class="flex items-center space-x-2 cursor-pointer bg-amber-50 px-4 py-1.5 rounded-lg border border-amber-200 hover:bg-amber-100 transition-colors">
                            <input type="checkbox" class="doubt-checkbox form-checkbox h-4 w-4 text-amber-500 rounded border-amber-300 focus:ring-amber-500" data-no="{{ $index + 1 }}">
                            <span class="text-sm font-bold text-amber-700">Ragu-ragu</span>
                        </label>
                    </div>

                    <div class="prose max-w-none mb-8 text-lg text-slate-800 font-medium">
                        {!! $q->question_text !!}
                    </div>

                    <div class="space-y-4">
                        @foreach($q->options as $opt)
                            @php
                                $ans = $answers->get($q->id);
                                $isChecked = $ans && $ans->cbt_option_id == $opt->id;
                            @endphp
                            <label class="option-label flex items-center p-4 border-2 {{ $isChecked ? 'border-indigo-600 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300' }} rounded-2xl cursor-pointer transition-all">
                                <input type="radio" name="answer_{{ $q->id }}" value="{{ $opt->id }}" class="form-radio h-5 w-5 text-indigo-600 border-slate-300 focus:ring-indigo-600" {{ $isChecked ? 'checked' : '' }} onchange="saveAnswer({{ $q->id }}, {{ $opt->id }}, {{ $index + 1 }})">
                                <span class="ml-4 text-slate-700 font-medium">{!! $opt->option_text !!}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Navigation Buttons -->
            <div class="absolute bottom-8 left-8 right-8 flex justify-between">
                <button onclick="prevQuestion()" class="px-6 py-2.5 bg-white border-2 border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 transition-colors" id="btn-prev">
                    <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                </button>
                <button onclick="nextQuestion()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200" id="btn-next">
                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Right: Number Grid -->
        <div class="w-80 bg-white border-l border-slate-200 p-6 flex flex-col">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6">Navigasi Soal</h3>
            
            <div class="grid grid-cols-5 gap-3 overflow-y-auto pb-4">
                @foreach($exam->bank->questions as $index => $q)
                    @php
                        $ans = $answers->get($q->id);
                        $statusClass = 'bg-white border-slate-200 text-slate-600 hover:border-indigo-400';
                        if ($ans) {
                            $statusClass = $ans->is_doubtful ? 'bg-amber-100 border-amber-400 text-amber-700' : 'bg-indigo-600 border-indigo-600 text-white';
                        }
                    @endphp
                    <button onclick="jumpTo({{ $index + 1 }})" class="q-nav-btn w-10 h-10 rounded-lg border-2 font-bold flex items-center justify-center transition-all {{ $statusClass }}" id="nav-btn-{{ $index + 1 }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <div class="mt-auto pt-6 border-t border-slate-100 space-y-3">
                <div class="flex items-center space-x-3"><div class="w-4 h-4 bg-white border-2 border-slate-200 rounded"></div><span class="text-xs text-slate-500 font-medium">Belum Dijawab</span></div>
                <div class="flex items-center space-x-3"><div class="w-4 h-4 bg-indigo-600 rounded"></div><span class="text-xs text-slate-500 font-medium">Sudah Dijawab</span></div>
                <div class="flex items-center space-x-3"><div class="w-4 h-4 bg-amber-100 border-2 border-amber-400 rounded"></div><span class="text-xs text-slate-500 font-medium">Ragu-ragu</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Pre-Exam Screen -->
<div class="max-w-3xl mx-auto my-12" id="pre-exam-screen">
    <div class="bg-white rounded-[2.5rem] p-10 shadow-xl shadow-slate-200/50 border border-slate-100 text-center relative overflow-hidden">
        <div class="w-24 h-24 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 transform rotate-3">
            <i class="fas fa-laptop-code text-4xl text-indigo-600"></i>
        </div>
        
        <h2 class="text-3xl font-black text-slate-800 mb-2">{{ $exam->name }}</h2>
        <p class="text-slate-500 font-medium mb-8">Anda akan memasuki Mode Ujian (Fullscreen). Pastikan koneksi internet Anda stabil.</p>

        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 text-left mb-8">
            <h4 class="font-bold text-rose-800 flex items-center mb-3"><i class="fas fa-exclamation-triangle mr-2"></i> Peringatan Sistem Anti-Nyontek</h4>
            <ul class="text-sm text-rose-600 space-y-2 list-disc list-inside font-medium">
                <li>Layar akan dikunci dalam mode Fullscreen.</li>
                <li>Jika Anda keluar dari mode Fullscreen, pindah Tab, atau membuka aplikasi lain, sistem akan mencatat pelanggaran.</li>
                <li>Ujian akan dihentikan paksa (Otomatis Submit) jika Anda melakukan pelanggaran 3 kali.</li>
            </ul>
        </div>

        <button onclick="startCBT()" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 w-full md:w-auto">
            MULAI UJIAN SEKARANG
        </button>
    </div>
</div>

<!-- Form Submit Finish -->
<form id="finish-form" action="{{ route('student.cbt.finish', $exam->id) }}" method="POST" class="hidden">
    @csrf
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const examId = {{ $exam->id }};
    const totalQuestions = {{ $exam->bank->questions->count() }};
    let currentQ = 1;
    let endTime = new Date("{{ $exam->end_time }}").getTime();
    let durationSeconds = {{ $exam->duration_minutes * 60 }};
    
    // Timer Logic
    // In real app, consider the exact start_time of the student + duration, capped by exam end_time.
    let studentStartTime = new Date("{{ $studentExam->start_time }}").getTime();
    let maxAllowedTime = studentStartTime + (durationSeconds * 1000);
    // Which one is earlier? The overall exam end time or student's allotted duration?
    let finalEndTime = Math.min(endTime, maxAllowedTime);

    function startTimer() {
        let x = setInterval(function() {
            let now = new Date().getTime();
            let distance = finalEndTime - now;

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = "WAKTU HABIS";
                forceSubmitExam();
                return;
            }

            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("timer").innerHTML = 
                (hours < 10 ? "0" + hours : hours) + ":" + 
                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                (seconds < 10 ? "0" + seconds : seconds);
        }, 1000);
    }

    // Engine UI
    function startCBT() {
        document.documentElement.requestFullscreen().then(() => {
            document.getElementById('pre-exam-screen').classList.add('hidden');
            document.getElementById('cbt-engine').classList.remove('hidden');
            jumpTo(1);
            startTimer();
            initAntiCheat();
        }).catch(err => {
            Swal.fire('Error', 'Browser tidak mendukung Fullscreen. Harap izinkan Fullscreen.', 'error');
        });
    }

    function jumpTo(qNo) {
        document.querySelectorAll('.question-panel').forEach(el => el.classList.add('hidden'));
        document.getElementById(`q-panel-${qNo}`).classList.remove('hidden');
        currentQ = qNo;
        
        document.getElementById('btn-prev').disabled = currentQ === 1;
        document.getElementById('btn-next').disabled = currentQ === totalQuestions;
        
        // Update nav UI active state
        document.querySelectorAll('.q-nav-btn').forEach(btn => btn.classList.remove('ring-4', 'ring-indigo-200', 'border-indigo-600'));
        document.getElementById(`nav-btn-${qNo}`).classList.add('ring-4', 'ring-indigo-200', 'border-indigo-600');
    }

    function nextQuestion() { if(currentQ < totalQuestions) jumpTo(currentQ + 1); }
    function prevQuestion() { if(currentQ > 1) jumpTo(currentQ - 1); }

    // Logic Save Answer
    function saveAnswer(questionId, optionId, qNo) {
        const isDoubt = document.querySelector(`.doubt-checkbox[data-no="${qNo}"]`).checked;
        
        // Update UI
        let btn = document.getElementById(`nav-btn-${qNo}`);
        btn.className = `q-nav-btn w-10 h-10 rounded-lg border-2 font-bold flex items-center justify-center transition-all ${isDoubt ? 'bg-amber-100 border-amber-400 text-amber-700' : 'bg-indigo-600 border-indigo-600 text-white'}`;
        
        // Update radio styles
        let panel = document.getElementById(`q-panel-${qNo}`);
        panel.querySelectorAll('.option-label').forEach(lbl => {
            lbl.classList.remove('border-indigo-600', 'bg-indigo-50');
            lbl.classList.add('border-slate-200');
        });
        panel.querySelector(`input[value="${optionId}"]`).closest('.option-label').classList.add('border-indigo-600', 'bg-indigo-50');

        // AJAX POST
        $.post(`{{ url('/siswa/cbt/${examId}/save-answer') }}`, {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            option_id: optionId,
            is_doubtful: isDoubt ? 1 : 0
        }).fail(() => {
            console.error("Gagal menyimpan jawaban. Offline Sync dibutuhkan di sini nanti.");
        });
    }

    // Toggle doubt
    document.querySelectorAll('.doubt-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            let qNo = this.dataset.no;
            let panel = document.getElementById(`q-panel-${qNo}`);
            let checkedRadio = panel.querySelector('input[type="radio"]:checked');
            if(checkedRadio) {
                saveAnswer(panel.dataset.qid, checkedRadio.value, qNo);
            }
        });
    });

    // Finish Exam
    function finishExam() {
        Swal.fire({
            title: 'Selesai Ujian?',
            text: "Pastikan semua soal telah terjawab. Anda tidak bisa kembali setelah menekan Selesai.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#f43f5e',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('finish-form').submit();
            }
        });
    }

    function forceSubmitExam() {
        document.getElementById('finish-form').submit();
    }

    // ANTI-CHEAT ENGINE
    function initAntiCheat() {
        // Disable Right Click
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Focus Tracking
        window.addEventListener('blur', reportViolation);
        
        // Fullscreen Change Tracking
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                reportViolation();
                // Force fullscreen back if possible, or show modal
                Swal.fire({
                    icon: 'error',
                    title: 'PELANGGARAN',
                    text: 'Anda keluar dari mode Fullscreen. Ini dicatat sebagai pelanggaran.',
                    confirmButtonText: 'Kembali Ujian',
                    allowOutsideClick: false,
                    customClass: { popup: 'rounded-3xl' }
                }).then(() => {
                    document.documentElement.requestFullscreen();
                });
            }
        });
    }

    function reportViolation() {
        $.post(`{{ url('/siswa/cbt/${examId}/report-violation') }}`, {
            _token: '{{ csrf_token() }}'
        }).done(res => {
            if (res.action === 'force_submit') {
                Swal.fire({
                    icon: 'error',
                    title: 'DISKUALIFIKASI',
                    text: res.message,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    customClass: { popup: 'rounded-3xl' }
                });
                setTimeout(() => forceSubmitExam(), 3000);
            }
        });
    }
</script>
@endpush
