@extends('layouts.ppdb')

@section('title', 'Ujian: ' . $exam->name)

@section('content')
<div class="cbt-container fixed inset-0 z-50 bg-slate-50 flex flex-col hidden" id="cbt-engine">
    <!-- Topbar -->
    <div class="h-16 md:h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 shadow-sm relative z-30">
        <div class="flex items-center space-x-3 md:space-x-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-indigo-200">
                CBT
            </div>
            <div class="hidden sm:block">
                <h1 class="text-sm md:text-lg font-black text-slate-800 leading-tight">{{ $exam->name }}</h1>
                <p class="text-[10px] md:text-xs text-slate-500 font-bold uppercase tracking-widest">{{ $exam->bank->subject->name ?? 'Mata Pelajaran' }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 md:space-x-8">
            <div class="flex flex-col items-end">
                <span class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-0.5">Sisa Waktu</span>
                <div class="text-xl md:text-3xl font-black text-rose-600 font-mono tracking-tighter" id="timer">--:--:--</div>
            </div>
            <div class="h-10 w-px bg-slate-200 hidden md:block"></div>
            <button onclick="finishExam()" class="px-4 py-2 md:px-8 md:py-3 bg-indigo-600 text-white rounded-xl font-black text-xs md:text-sm uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                Selesai
            </button>
            <!-- Mobile Nav Toggle -->
            <button onclick="toggleNav()" class="lg:hidden w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                <i class="fas fa-th-large"></i>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- Left: Question Area -->
        <div class="flex-1 overflow-y-auto p-4 md:p-12 relative pb-32" id="question-area">
            <div class="max-w-4xl mx-auto">
                @foreach($exam->bank->questions as $index => $q)
                    <div class="question-panel hidden animate-in fade-in slide-in-from-bottom-4 duration-500" id="q-panel-{{ $index + 1 }}" data-qid="{{ $q->id }}">
                        <div class="mb-8 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="w-12 h-12 bg-slate-800 text-white flex items-center justify-center rounded-2xl font-black text-xl shadow-lg">
                                    {{ $index + 1 }}
                                </span>
                                <div class="h-1 w-8 bg-slate-200 rounded-full"></div>
                                <span class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Pertanyaan</span>
                            </div>
                            
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest group-hover:text-amber-600 transition-colors">Ragu-ragu?</span>
                                <div class="relative inline-flex items-center">
                                    <input type="checkbox" class="doubt-checkbox sr-only peer" data-no="{{ $index + 1 }}">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </div>
                            </label>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-6 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/40 mb-8">
                            <div class="prose prose-slate max-w-none mb-8 text-lg md:text-xl text-slate-800 font-bold leading-relaxed">
                                {!! $q->question_text !!}
                            </div>

                            @if($q->question_image)
                                <div class="mb-8 rounded-3xl overflow-hidden border-4 border-slate-50 shadow-inner bg-white text-center">
                                    <img src="{{ Storage::url($q->question_image) }}" class="max-h-[500px] w-auto mx-auto object-contain p-2" alt="Gambar Soal">
                                </div>
                            @endif

                            <div class="space-y-4">
                                @if(in_array($q->question_type, ['pilihan_ganda', 'ganda_komplek']))
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($q->options as $optIndex => $opt)
                                            @php
                                                $ans = $answers->get($q->id);
                                                $isChecked = $ans && $ans->cbt_option_id == $opt->id;
                                                $letter = chr(65 + $optIndex);
                                            @endphp
                                            <label class="option-label group flex items-start p-4 md:p-6 border-2 {{ $isChecked ? 'border-indigo-600 bg-indigo-50 shadow-lg shadow-indigo-100' : 'border-slate-100 hover:border-indigo-300 hover:bg-slate-50' }} rounded-[2rem] cursor-pointer transition-all duration-300">
                                                <div class="flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-2xl {{ $isChecked ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-indigo-100 group-hover:text-indigo-600' }} font-black text-lg transition-colors shadow-sm">
                                                    {{ $letter }}
                                                </div>
                                                
                                                <div class="ml-4 md:ml-6 flex-1">
                                                    <div class="hidden">
                                                        @if($q->question_type === 'pilihan_ganda')
                                                            <input type="radio" name="answer_{{ $q->id }}" value="{{ $opt->id }}" {{ $isChecked ? 'checked' : '' }} onchange="saveAnswer({{ $q->id }}, {{ $opt->id }}, {{ $index + 1 }})">
                                                        @else
                                                            <input type="checkbox" name="answer_{{ $q->id }}[]" value="{{ $opt->id }}" {{ $isChecked ? 'checked' : '' }} onchange="saveAnswer({{ $q->id }}, {{ $opt->id }}, {{ $index + 1 }}, true)">
                                                        @endif
                                                    </div>
                                                    
                                                    @if($opt->option_image)
                                                        <div class="mb-3 rounded-2xl overflow-hidden border border-slate-100 shadow-sm bg-white inline-block">
                                                            <img src="{{ Storage::url($opt->option_image) }}" class="max-h-40 w-auto object-contain p-1" alt="Gambar Opsi">
                                                        </div>
                                                    @endif
                                                    <div class="text-slate-700 font-bold text-base md:text-lg leading-relaxed">{!! $opt->option_text !!}</div>
                                                </div>
                                                
                                                <div class="ml-2 mt-2">
                                                    <div class="w-6 h-6 rounded-full border-2 {{ $isChecked ? 'border-indigo-600 bg-indigo-600' : 'border-slate-200' }} flex items-center justify-center transition-all">
                                                        @if($isChecked) <i class="fas fa-check text-[10px] text-white"></i> @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($q->question_type === 'penjodohan')
                                    <div class="bg-indigo-50/40 rounded-[2.5rem] p-6 md:p-10 border-2 border-indigo-100/50">
                                        <p class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                                            <i class="fas fa-link mr-2"></i> Mode Penjodohan
                                        </p>
                                        <div class="space-y-6">
                                            @php
                                                $pairs = is_array($q->matching_pairs) ? $q->matching_pairs : [];
                                                $ans = $answers->get($q->id);
                                            @endphp
                                            @foreach($pairs as $premise => $correctResponse)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                                    <div class="p-5 bg-white border-2 border-white rounded-2xl shadow-sm text-base md:text-lg font-bold text-slate-700">
                                                        {!! preg_replace('/\[IMG\](.*?)\[\/IMG\]/', '<div class="mt-2 text-center"><img src="'.Storage::url('$1').'" class="max-h-32 mx-auto rounded-xl shadow-sm border border-slate-100"></div>', $premise) !!}
                                                    </div>
                                                    <div class="relative">
                                                        <select class="matching-select w-full p-5 bg-white border-2 border-slate-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 font-bold text-slate-700 appearance-none transition-all" 
                                                                data-question-id="{{ $q->id }}" 
                                                                data-premise="{{ $premise }}"
                                                                onchange="saveMatchingAnswer({{ $q->id }}, {{ $index + 1 }})">
                                                            <option value="">-- Pilih Pasangan --</option>
                                                            @foreach($pairs as $p => $r)
                                                                <option value="{{ $r }}">{!! strip_tags(preg_replace('/\[IMG\](.*?)\[\/IMG\]/', '[Gambar]', $r)) !!}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif(in_array($q->question_type, ['essay', 'uraian']))
                                    <div class="bg-white rounded-[2.5rem] border-2 border-slate-100 focus-within:border-indigo-400 focus-within:ring-8 focus-within:ring-indigo-50 transition-all overflow-hidden shadow-inner">
                                        <textarea name="answer_{{ $q->id }}" 
                                                  class="w-full p-8 text-lg md:text-xl font-bold text-slate-700 focus:ring-0 border-0 placeholder:text-slate-300" 
                                                  placeholder="Tuliskan jawaban lengkap Anda di sini..." 
                                                  rows="8"
                                                  onblur="saveEssayAnswer({{ $q->id }}, {{ $index + 1 }})">{{ $answers->get($q->id)->answer_text ?? '' }}</textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Buttons -->
            <div class="fixed bottom-0 left-0 right-0 p-4 md:p-8 bg-white/80 backdrop-blur-xl border-t border-slate-200 z-20">
                <div class="max-w-4xl mx-auto flex justify-between items-center gap-4">
                    <button onclick="prevQuestion()" class="flex-1 md:flex-none px-6 py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-50 transition-all disabled:opacity-30" id="btn-prev">
                        <i class="fas fa-arrow-left mr-2"></i> <span class="hidden md:inline">Sebelumnya</span>
                    </button>
                    
                    <div class="hidden md:flex items-center space-x-2">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest" id="progress-text">Soal 1 dari 40</span>
                    </div>

                    <button onclick="nextQuestion()" class="flex-1 md:flex-none px-10 py-4 bg-slate-800 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-slate-200 disabled:opacity-30" id="btn-next">
                        <span class="hidden md:inline">Selanjutnya</span> <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: Number Grid Sidebar -->
        <div class="fixed inset-y-0 right-0 w-80 bg-white border-l border-slate-200 p-8 flex flex-col z-40 transform translate-x-full lg:translate-x-0 transition-transform duration-500 ease-in-out shadow-2xl lg:shadow-none" id="sidebar-nav">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Navigasi Soal</h3>
                <button onclick="toggleNav()" class="lg:hidden text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid grid-cols-4 gap-3">
                    @foreach($exam->bank->questions as $index => $q)
                        @php
                            $ans = $answers->get($q->id);
                            $statusClass = 'bg-slate-50 border-transparent text-slate-400 hover:border-slate-300';
                            if ($ans) {
                                $statusClass = $ans->is_doubtful ? 'bg-amber-500 border-amber-500 text-white shadow-lg shadow-amber-200' : 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-100';
                            }
                        @endphp
                        <button onclick="jumpTo({{ $index + 1 }})" class="q-nav-btn w-12 h-12 rounded-xl border-2 font-black text-sm flex items-center justify-center transition-all duration-300 {{ $statusClass }}" id="nav-btn-{{ $index + 1 }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-100 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Legenda</span>
                </div>
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex items-center space-x-3"><div class="w-5 h-5 bg-indigo-600 rounded-lg shadow-sm"></div><span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Sudah Dijawab</span></div>
                    <div class="flex items-center space-x-3"><div class="w-5 h-5 bg-amber-500 rounded-lg shadow-sm"></div><span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Ragu-ragu</span></div>
                    <div class="flex items-center space-x-3"><div class="w-5 h-5 bg-slate-50 rounded-lg"></div><span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Belum Dijawab</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Nav Overlay -->
<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden lg:hidden" id="nav-overlay" onclick="toggleNav()"></div>
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
    function toggleNav() {
        const sidebar = document.getElementById('sidebar-nav');
        const overlay = document.getElementById('nav-overlay');
        sidebar.classList.toggle('translate-x-full');
        overlay.classList.toggle('hidden');
    }

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
        document.getElementById('progress-text').innerText = `Soal ${currentQ} dari ${totalQuestions}`;
        
        // Update nav UI active state
        document.querySelectorAll('.q-nav-btn').forEach(btn => btn.classList.remove('ring-4', 'ring-indigo-100', 'border-indigo-600', 'scale-110'));
        document.getElementById(`nav-btn-${qNo}`).classList.add('ring-4', 'ring-indigo-100', 'border-indigo-600', 'scale-110');

        // On mobile, close nav after jump
        if (window.innerWidth < 1024) {
            const sidebar = document.getElementById('sidebar-nav');
            if(!sidebar.classList.contains('translate-x-full')) toggleNav();
        }
    }

    function nextQuestion() { if(currentQ < totalQuestions) jumpTo(currentQ + 1); }
    function prevQuestion() { if(currentQ > 1) jumpTo(currentQ - 1); }

    // Logic Save Answer
    function saveAnswer(questionId, optionId, qNo, isMultiple = false) {
        const isDoubt = document.querySelector(`.doubt-checkbox[data-no="${qNo}"]`).checked;
        
        // Update UI
        let btn = document.getElementById(`nav-btn-${qNo}`);
        btn.className = `q-nav-btn w-10 h-10 rounded-lg border-2 font-bold flex items-center justify-center transition-all ${isDoubt ? 'bg-amber-100 border-amber-400 text-amber-700' : 'bg-indigo-600 border-indigo-600 text-white'}`;
        
        // Update radio/checkbox styles
        let panel = document.getElementById(`q-panel-${qNo}`);
        if(!isMultiple) {
            panel.querySelectorAll('.option-label').forEach(lbl => {
                lbl.classList.remove('border-indigo-600', 'bg-indigo-50');
                lbl.classList.add('border-slate-200');
            });
        }
        
        let input = panel.querySelector(`input[value="${optionId}"]`);
        if(input.checked) {
            input.closest('.option-label').classList.add('border-indigo-600', 'bg-indigo-50');
            input.closest('.option-label').classList.remove('border-slate-200');
        } else {
            input.closest('.option-label').classList.remove('border-indigo-600', 'bg-indigo-50');
            input.closest('.option-label').classList.add('border-slate-200');
        }

        // AJAX POST
        // For PGK, we should ideally send all selected options. 
        // For now, we'll keep it simple as V1.
        $.post(`{{ url('/siswa/cbt/${examId}/save-answer') }}`, {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            option_id: optionId,
            is_doubtful: isDoubt ? 1 : 0
        });
    }

    function saveMatchingAnswer(questionId, qNo) {
        const isDoubt = document.querySelector(`.doubt-checkbox[data-no="${qNo}"]`).checked;
        let btn = document.getElementById(`nav-btn-${qNo}`);
        btn.className = `q-nav-btn w-10 h-10 rounded-lg border-2 font-bold flex items-center justify-center transition-all ${isDoubt ? 'bg-amber-100 border-amber-400 text-amber-700' : 'bg-indigo-600 border-indigo-600 text-white'}`;

        let panel = document.getElementById(`q-panel-${qNo}`);
        let matchingData = {};
        panel.querySelectorAll('.matching-select').forEach(select => {
            if(select.value) matchingData[select.dataset.premise] = select.value;
        });

        $.post(`{{ url('/siswa/cbt/${examId}/save-answer') }}`, {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            matching_answers: matchingData,
            is_doubtful: isDoubt ? 1 : 0
        });
    }

    function saveEssayAnswer(questionId, qNo) {
        const isDoubt = document.querySelector(`.doubt-checkbox[data-no="${qNo}"]`).checked;
        let btn = document.getElementById(`nav-btn-${qNo}`);
        
        let panel = document.getElementById(`q-panel-${qNo}`);
        let text = panel.querySelector('textarea').value;

        if(text.trim() !== "") {
            btn.className = `q-nav-btn w-10 h-10 rounded-lg border-2 font-bold flex items-center justify-center transition-all ${isDoubt ? 'bg-amber-100 border-amber-400 text-amber-700' : 'bg-indigo-600 border-indigo-600 text-white'}`;
        }

        $.post(`{{ url('/siswa/cbt/${examId}/save-answer') }}`, {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            answer_text: text,
            is_doubtful: isDoubt ? 1 : 0
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
