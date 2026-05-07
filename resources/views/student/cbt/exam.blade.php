@extends('layouts.ppdb')

@section('title', 'CBT: ' . $exam->name)

@section('content')
<!-- ULTRA PREMIUM CBT ENGINE V3 -->
<div class="cbt-container fixed inset-0 z-[150] bg-[#f8fafc] flex flex-col hidden overflow-hidden" id="cbt-engine">
    <!-- TOP NAVIGATION BAR (GLASSMORPHISM) -->
    <div class="h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200 flex items-center justify-between px-6 md:px-12 shadow-sm relative z-30">
        <div class="flex items-center space-x-5">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100 transform rotate-3">
                <i class="fas fa-laptop-code text-xl"></i>
            </div>
            <div class="hidden sm:block">
                <h1 class="text-lg font-black text-slate-800 leading-tight tracking-tight">{{ $exam->name }}</h1>
                <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-[0.2em]">{{ $exam->bank->subject->name ?? 'Mata Pelajaran' }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4 md:space-x-10">
            <div class="flex flex-col items-end">
                <span class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1">Sisa Waktu</span>
                <div class="text-2xl md:text-4xl font-black text-rose-600 font-mono tracking-tighter leading-none" id="timer">--:--:--</div>
            </div>
            <div class="h-10 w-px bg-slate-200 hidden md:block"></div>
            <button onclick="finishExam()" class="px-6 py-3 md:px-10 md:py-4 bg-slate-900 text-white rounded-2xl font-black text-xs md:text-sm uppercase tracking-widest hover:bg-rose-600 transition-all shadow-2xl active:scale-95">
                Selesai
            </button>
            <button onclick="toggleNav()" class="lg:hidden w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner">
                <i class="fas fa-th-large text-lg"></i>
            </button>
        </div>
    </div>

    <!-- MAIN ENGINE AREA -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- LEFT: QUESTION CONTENT -->
        <div class="flex-1 overflow-y-auto p-6 md:p-16 relative pb-40 custom-scrollbar" id="question-area">
            <div class="max-w-4xl mx-auto">
                @foreach($exam->bank->questions as $index => $q)
                    <div class="question-panel hidden animate-fade-in" id="q-panel-{{ $index + 1 }}" data-qid="{{ $q->id }}">
                        <div class="mb-10 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="w-14 h-14 bg-slate-900 text-white flex items-center justify-center rounded-[1.5rem] font-black text-2xl shadow-2xl">
                                    {{ $index + 1 }}
                                </span>
                                <div class="h-1.5 w-12 bg-indigo-100 rounded-full"></div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Butir Pertanyaan</span>
                            </div>
                            
                            <label class="flex items-center space-x-4 cursor-pointer group bg-white px-5 py-2.5 rounded-2xl border border-slate-100 shadow-sm transition-all hover:border-amber-200">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-amber-600 transition-colors">Ragu-ragu?</span>
                                <div class="relative inline-flex items-center">
                                    <input type="checkbox" class="doubt-checkbox sr-only peer" data-no="{{ $index + 1 }}">
                                    <div class="w-11 h-6 bg-slate-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </div>
                            </label>
                        </div>

                        <!-- QUESTION CARD -->
                        <div class="bg-white rounded-[3rem] p-8 md:p-12 border border-slate-100 shadow-2xl shadow-slate-200/40 mb-10">
                            <div class="prose-container mb-10">
                                <div class="text-xl md:text-2xl text-slate-800 font-bold leading-relaxed tracking-tight">
                                    {!! $q->question_text !!}
                                </div>
                            </div>

                            @if($q->question_image)
                                <div class="mb-10 rounded-[2.5rem] overflow-hidden border-8 border-slate-50 shadow-inner bg-slate-50 text-center">
                                    <img src="{{ Storage::url($q->question_image) }}" class="max-h-[600px] w-auto mx-auto object-contain p-4" alt="Gambar Soal">
                                </div>
                            @endif

                            <!-- OPTIONS GRID -->
                            <div class="space-y-5">
                                @if(in_array($q->question_type, ['pilihan_ganda', 'ganda_komplek']))
                                    <div class="grid grid-cols-1 gap-5">
                                        @foreach($q->options as $optIndex => $opt)
                                            @php
                                                $ans = $answers->get($q->id);
                                                $isChecked = $ans && $ans->cbt_option_id == $opt->id;
                                                $letter = chr(65 + $optIndex);
                                            @endphp
                                            <label class="option-label group flex items-start p-6 md:p-8 border-2 {{ $isChecked ? 'border-indigo-600 bg-indigo-50/50 shadow-2xl shadow-indigo-100/50' : 'border-slate-50 bg-slate-50/30 hover:border-indigo-300 hover:bg-white hover:shadow-xl' }} rounded-[2.5rem] cursor-pointer transition-all duration-300">
                                                <div class="flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-2xl {{ $isChecked ? 'bg-indigo-600 text-white' : 'bg-white text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600' }} font-black text-xl transition-all shadow-sm border border-slate-100">
                                                    {{ $letter }}
                                                </div>
                                                
                                                <div class="ml-6 flex-1">
                                                    <div class="hidden">
                                                        @if($q->question_type === 'pilihan_ganda')
                                                            <input type="radio" name="answer_{{ $q->id }}" value="{{ $opt->id }}" {{ $isChecked ? 'checked' : '' }} onchange="saveAnswer({{ $q->id }}, {{ $opt->id }}, {{ $index + 1 }})">
                                                        @else
                                                            <input type="checkbox" name="answer_{{ $q->id }}[]" value="{{ $opt->id }}" {{ $isChecked ? 'checked' : '' }} onchange="saveAnswer({{ $q->id }}, {{ $opt->id }}, {{ $index + 1 }}, true)">
                                                        @endif
                                                    </div>
                                                    
                                                    @if($opt->option_image)
                                                        <div class="mb-4 rounded-2xl overflow-hidden border border-slate-100 shadow-sm bg-white inline-block">
                                                            <img src="{{ Storage::url($opt->option_image) }}" class="max-h-48 w-auto object-contain p-2" alt="Gambar Opsi">
                                                        </div>
                                                    @endif
                                                    <div class="text-slate-700 font-bold text-lg md:text-xl leading-relaxed">{!! $opt->option_text !!}</div>
                                                </div>
                                                
                                                <div class="ml-4 mt-3">
                                                    <div class="w-8 h-8 rounded-full border-2 {{ $isChecked ? 'border-indigo-600 bg-indigo-600' : 'border-slate-300 bg-white' }} flex items-center justify-center transition-all group-hover:border-indigo-400">
                                                        @if($isChecked) <i class="fas fa-check text-xs text-white"></i> @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($q->question_type === 'penjodohan')
                                    <div class="bg-indigo-50/30 rounded-[3rem] p-8 md:p-12 border-2 border-indigo-100/50">
                                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                                            <i class="fas fa-link mr-3 text-lg"></i> MODE PENJODOHAN
                                        </p>
                                        <div class="space-y-8">
                                            @php
                                                $pairs = is_array($q->matching_pairs) ? $q->matching_pairs : [];
                                            @endphp
                                            @foreach($pairs as $premise => $correctResponse)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                                    <div class="p-6 bg-white border border-slate-100 rounded-3xl shadow-xl text-lg font-bold text-slate-700">
                                                        {!! $premise !!}
                                                    </div>
                                                    <div class="relative group">
                                                        <select class="matching-select w-full p-6 bg-white border-2 border-slate-100 rounded-3xl focus:border-indigo-500 focus:ring-8 focus:ring-indigo-50 font-black text-slate-700 appearance-none transition-all shadow-xl" 
                                                                data-question-id="{{ $q->id }}" 
                                                                data-premise="{{ $premise }}"
                                                                onchange="saveMatchingAnswer({{ $q->id }}, {{ $index + 1 }})">
                                                            <option value="">Pilih Pasangan...</option>
                                                            @foreach($pairs as $p => $r)
                                                                <option value="{{ $r }}">{!! strip_tags($r) !!}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-indigo-400 group-hover:scale-125 transition-transform">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif(in_array($q->question_type, ['essay', 'uraian']))
                                    <div class="bg-white rounded-[3rem] border-4 border-slate-50 focus-within:border-indigo-200 focus-within:ring-8 focus-within:ring-indigo-50 transition-all overflow-hidden shadow-2xl">
                                        <textarea name="answer_{{ $q->id }}" 
                                                  class="w-full p-10 text-xl font-bold text-slate-800 focus:ring-0 border-0 placeholder:text-slate-300" 
                                                  placeholder="Tuliskan jawaban lengkap Anda di sini..." 
                                                  rows="10"
                                                  onblur="saveEssayAnswer({{ $q->id }}, {{ $index + 1 }})">{{ $answers->get($q->id)->answer_text ?? '' }}</textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- BOTTOM NAVIGATION (FLOATING) -->
            <div class="fixed bottom-8 left-1/2 -translate-x-1/2 w-[90%] max-w-4xl bg-white/80 backdrop-blur-2xl border border-white/50 p-4 md:p-6 rounded-[2.5rem] shadow-2xl z-20 flex justify-between items-center gap-4">
                <button onclick="prevQuestion()" class="flex-1 md:flex-none px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all disabled:opacity-30 disabled:pointer-events-none active:scale-95" id="btn-prev">
                    <i class="fas fa-arrow-left mr-3"></i> <span class="hidden md:inline">Sebelumnya</span>
                </button>
                
                <div class="hidden md:flex flex-col items-center">
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Kemajuan</span>
                    <span class="text-sm font-black text-indigo-600 uppercase tracking-widest" id="progress-text">Soal 1 / 40</span>
                </div>

                <button onclick="nextQuestion()" class="flex-1 md:flex-none px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-indigo-100 disabled:opacity-30 disabled:pointer-events-none active:scale-95" id="btn-next">
                    <span class="hidden md:inline">Selanjutnya</span> <i class="fas fa-arrow-right ml-3"></i>
                </button>
            </div>
        </div>

        <!-- RIGHT: NUMBER GRID SIDEBAR -->
        <div class="fixed inset-y-0 right-0 w-80 bg-white border-l border-slate-100 p-8 flex flex-col z-40 transform translate-x-full lg:translate-x-0 transition-transform duration-700 ease-in-out shadow-2xl lg:shadow-none" id="sidebar-nav">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] mb-1">Navigasi Soal</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Klik nomor untuk melompat</p>
                </div>
                <button onclick="toggleNav()" class="lg:hidden w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-rose-500 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid grid-cols-4 gap-4">
                    @foreach($exam->bank->questions as $index => $q)
                        @php
                            $ans = $answers->get($q->id);
                            $statusClass = 'bg-slate-50 border-transparent text-slate-300 hover:border-slate-200';
                            if ($ans) {
                                $statusClass = $ans->is_doubtful ? 'bg-amber-500 border-amber-500 text-white shadow-lg shadow-amber-200' : 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-100';
                            }
                        @endphp
                        <button onclick="jumpTo({{ $index + 1 }})" class="q-nav-btn w-14 h-14 rounded-2xl border-2 font-black text-sm flex items-center justify-center transition-all duration-300 {{ $statusClass }}" id="nav-btn-{{ $index + 1 }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 pt-10 border-t border-slate-50 space-y-6">
                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest block">Keterangan Warna</span>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center space-x-4"><div class="w-6 h-6 bg-indigo-600 rounded-lg shadow-sm"></div><span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Sudah Dijawab</span></div>
                    <div class="flex items-center space-x-4"><div class="w-6 h-6 bg-amber-500 rounded-lg shadow-sm"></div><span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Ragu-ragu</span></div>
                    <div class="flex items-center space-x-4"><div class="w-6 h-6 bg-slate-100 rounded-lg"></div><span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Belum Dijawab</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MOBILE OVERLAY -->
<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[140] hidden lg:hidden transition-opacity duration-500" id="nav-overlay" onclick="toggleNav()"></div>

<!-- PRE-EXAM SCREEN (MODERNIZED) -->
<div class="max-w-4xl mx-auto my-20 px-6" id="pre-exam-screen">
    <div class="bg-white rounded-[4rem] p-12 md:p-20 shadow-2xl shadow-slate-200 border border-slate-100 text-center relative overflow-hidden">
        <div class="w-32 h-32 bg-indigo-600/10 rounded-[3rem] flex items-center justify-center mx-auto mb-10 transform rotate-12 shadow-inner">
            <i class="fas fa-user-shield text-5xl text-indigo-600"></i>
        </div>
        
        <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight leading-none">{{ $exam->name }}</h2>
        <p class="text-lg text-slate-500 font-medium mb-12 max-w-xl mx-auto">Selamat datang di sistem CBT Madrasah. Pastikan Anda siap secara mental dan teknis sebelum menekan tombol mulai.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16 text-left">
            <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100">
                <h4 class="font-black text-slate-800 flex items-center mb-6 uppercase tracking-widest text-xs">
                    <i class="fas fa-info-circle mr-3 text-indigo-500 text-lg"></i> Rincian Ujian
                </h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center"><span class="text-sm font-bold text-slate-400">Durasi</span><span class="text-sm font-black text-slate-800">{{ $exam->duration_minutes }} Menit</span></div>
                    <div class="flex justify-between items-center"><span class="text-sm font-bold text-slate-400">Total Soal</span><span class="text-sm font-black text-slate-800">{{ $exam->bank->questions->count() }} Butir</span></div>
                    <div class="flex justify-between items-center"><span class="text-sm font-bold text-slate-400">Tipe</span><span class="text-sm font-black text-slate-800">CAMPURAN</span></div>
                </div>
            </div>
            <div class="p-8 bg-rose-50 rounded-[2.5rem] border border-rose-100">
                <h4 class="font-black text-rose-800 flex items-center mb-6 uppercase tracking-widest text-xs">
                    <i class="fas fa-shield-alt mr-3 text-rose-500 text-lg"></i> Aturan Keamanan
                </h4>
                <ul class="text-xs text-rose-600 space-y-3 list-none font-black uppercase tracking-widest">
                    <li class="flex items-center"><i class="fas fa-lock mr-2"></i> Mode Layar Penuh</li>
                    <li class="flex items-center"><i class="fas fa-eye mr-2"></i> Deteksi Perpindahan Tab</li>
                    <li class="flex items-center"><i class="fas fa-ban mr-2"></i> Maksimal 3 Pelanggaran</li>
                </ul>
            </div>
        </div>

        <button onclick="startCBT()" class="w-full md:w-auto px-16 py-6 bg-indigo-600 text-white rounded-[2rem] font-black text-xl hover:bg-slate-900 transition-all shadow-2xl shadow-indigo-200 transform hover:-translate-y-2 active:scale-95">
            MULAI UJIAN SEKARANG <i class="fas fa-play-circle ml-3"></i>
        </button>
        
        <!-- Decoration -->
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-indigo-50 rounded-full blur-3xl -z-10"></div>
    </div>
</div>

<!-- FORM FINISH -->
<form id="finish-form" action="{{ route('student.cbt.finish', $exam->id) }}" method="POST" class="hidden">@csrf</form>

<style>
    .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection

@push('scripts')
<script>
    const examId = {{ $exam->id }};
    const totalQuestions = {{ $exam->bank->questions->count() }};
    let currentQ = 1;
    let endTime = new Date("{{ $exam->end_time }}").getTime();
    let durationSeconds = {{ $exam->duration_minutes * 60 }};
    let studentStartTime = new Date("{{ $studentExam->start_time }}").getTime();
    let maxAllowedTime = studentStartTime + (durationSeconds * 1000);
    let finalEndTime = Math.min(endTime, maxAllowedTime);

    function startTimer() {
        let x = setInterval(function() {
            let now = new Date().getTime();
            let distance = finalEndTime - now;
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = "HABIS";
                forceSubmitExam();
                return;
            }
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById("timer").innerHTML = (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds);
        }, 1000);
    }

    function toggleNav() {
        $('#sidebar-nav').toggleClass('translate-x-full');
        $('#nav-overlay').toggleClass('hidden');
    }

    function startCBT() {
        document.documentElement.requestFullscreen().then(() => {
            $('#pre-exam-screen').addClass('hidden');
            $('#cbt-engine').removeClass('hidden');
            jumpTo(1);
            startTimer();
            initAntiCheat();
        }).catch(() => {
            Swal.fire({ icon: 'error', title: 'Akses Ditolak', text: 'Harap izinkan mode Fullscreen untuk memulai ujian.', customClass: { popup: 'rounded-[2.5rem]' } });
        });
    }

    function jumpTo(qNo) {
        $('.question-panel').addClass('hidden');
        $(`#q-panel-${qNo}`).removeClass('hidden');
        currentQ = qNo;
        $('#btn-prev').prop('disabled', currentQ === 1);
        $('#btn-next').prop('disabled', currentQ === totalQuestions);
        $('#progress-text').text(`Soal ${currentQ} / ${totalQuestions}`);
        $('.q-nav-btn').removeClass('ring-8 ring-indigo-50 border-indigo-600 scale-110');
        $(`#nav-btn-${qNo}`).addClass('ring-8 ring-indigo-50 border-indigo-600 scale-110');
        if (window.innerWidth < 1024 && !$('#sidebar-nav').hasClass('translate-x-full')) toggleNav();
    }

    function nextQuestion() { if(currentQ < totalQuestions) jumpTo(currentQ + 1); }
    function prevQuestion() { if(currentQ > 1) jumpTo(currentQ - 1); }

    function saveAnswer(questionId, optionId, qNo, isMultiple = false) {
        const isDoubt = $(`.doubt-checkbox[data-no="${qNo}"]`).is(':checked');
        let btn = $(`#nav-btn-${qNo}`);
        btn.removeClass('bg-slate-50 border-transparent text-slate-300').addClass(isDoubt ? 'bg-amber-500 border-amber-500 text-white shadow-amber-200' : 'bg-indigo-600 border-indigo-600 text-white shadow-indigo-100');
        
        let panel = $(`#q-panel-${qNo}`);
        if(!isMultiple) {
            panel.find('.option-label').removeClass('border-indigo-600 bg-indigo-50/50 shadow-2xl shadow-indigo-100/50').addClass('border-slate-50 bg-slate-50/30');
        }
        let input = panel.find(`input[value="${optionId}"]`);
        if(input.is(':checked')) {
            input.closest('.option-label').addClass('border-indigo-600 bg-indigo-50/50 shadow-2xl shadow-indigo-100/50').removeClass('border-slate-50 bg-slate-50/30');
        }
        $.post('{{ route("student.cbt.save-answer", $exam->id) }}', { 
            _token: '{{ csrf_token() }}', 
            question_id: questionId, 
            option_id: optionId, 
            is_doubtful: isDoubt ? 1 : 0 
        }).fail(function() {
            console.error("Gagal menyimpan jawaban");
        });
    }

    function saveMatchingAnswer(questionId, qNo) {
        const isDoubt = $(`.doubt-checkbox[data-no="${qNo}"]`).is(':checked');
        let answers = [];
        $(`#q-panel-${qNo} .matching-select`).each(function() {
            answers.push({
                premise: $(this).data('premise'),
                response: $(this).val()
            });
        });

        $.post('{{ route("student.cbt.save-answer", $exam->id) }}', {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            matching_answers: answers,
            is_doubtful: isDoubt ? 1 : 0
        });
        
        $(`#nav-btn-${qNo}`).addClass('bg-indigo-600 border-indigo-600 text-white shadow-indigo-100');
    }

    function saveEssayAnswer(questionId, qNo) {
        const isDoubt = $(`.doubt-checkbox[data-no="${qNo}"]`).is(':checked');
        const text = $(`textarea[name="answer_${questionId}"]`).val();
        
        $.post('{{ route("student.cbt.save-answer", $exam->id) }}', {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            answer_text: text,
            is_doubtful: isDoubt ? 1 : 0
        });

        if (text.trim() !== '') {
            $(`#nav-btn-${qNo}`).addClass('bg-indigo-600 border-indigo-600 text-white shadow-indigo-100');
        } else {
            $(`#nav-btn-${qNo}`).removeClass('bg-indigo-600 border-indigo-600 text-white shadow-indigo-100').addClass('bg-slate-50 border-transparent text-slate-300');
        }
    }

    function finishExam() {
        Swal.fire({
            title: 'Selesai Ujian?',
            text: "Data yang sudah dikirim tidak dapat diubah kembali.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'YA, SELESAI',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[3rem]' }
        }).then((res) => { if (res.isConfirmed) $('#finish-form').submit(); });
    }

    function forceSubmitExam() { $('#finish-form').submit(); }

    function initAntiCheat() {
        document.addEventListener('contextmenu', e => e.preventDefault());
        window.addEventListener('blur', reportViolation);
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                reportViolation();
                Swal.fire({ icon: 'error', title: 'PELANGGARAN!', text: 'Sistem mendeteksi Anda keluar dari layar penuh.', confirmButtonText: 'KEMBALI UJIAN', allowOutsideClick: false, customClass: { popup: 'rounded-[2.5rem]' } }).then(() => document.documentElement.requestFullscreen());
            }
        });
    }

    function reportViolation() {
        $.post('{{ route("student.cbt.report-violation", $exam->id) }}', { _token: '{{ csrf_token() }}' }).done(res => {
            if (res.action === 'force_submit') {
                Swal.fire({ icon: 'error', title: 'DISKUALIFIKASI', text: res.message, showConfirmButton: false, allowOutsideClick: false, customClass: { popup: 'rounded-[2.5rem]' } });
                setTimeout(() => forceSubmitExam(), 2000);
            }
        });
    }
</script>
@endpush
