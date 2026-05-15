@extends('layouts.teacher')

@section('title', 'Mutabaah & Tahfidz')

@section('content')
<div class="dashboard-wrapper pb-20">
    <!-- HEADER -->
    <div class="header-banner bg-grad-emerald pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
                <div class="text-white">
                    <span class="bg-white/20 backdrop-blur-md text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-2 inline-block">Wali Kelas {{ $homeroomClass?->kelas_lengkap ?? '-' }}</span>
                    <h1 class="text-3xl font-black tracking-tight leading-tight"><i class="fas fa-pray mr-3"></i>Mutabaah & Tahfidz</h1>
                    <p class="text-white/70 text-xs font-bold mt-1">Pantau ibadah harian dan hafalan Al-Qur'an siswa Anda</p>
                </div>
                <div class="flex space-x-3">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/10 text-center min-w-[100px]">
                        <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Mutabaah Hari Ini</span>
                        <h3 class="text-2xl font-black text-white mb-0">{{ $statToday }}</h3>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/10 text-center min-w-[100px]">
                        <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Tahfidz Bulan Ini</span>
                        <h3 class="text-2xl font-black text-white mb-0">{{ $statTahfidz }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-12 relative z-20">
        @if(!$homeroomClass)
            <div class="bg-white rounded-[3rem] p-10 shadow-2xl text-center">
                <div class="w-24 h-24 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800 mb-2">Belum Menjadi Wali Kelas</h4>
                <p class="text-sm text-slate-400">Anda belum ditugaskan sebagai wali kelas. Hubungi admin untuk penugasan.</p>
            </div>
        @else
            <!-- TAB NAVIGATION -->
            <div class="bg-white rounded-[2rem] p-2 shadow-xl mb-8 flex">
                <button onclick="switchGuruTab('mutabaah')" id="tabBtnMutabaah" class="flex-1 py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest transition-all bg-emerald-600 text-white shadow-lg">
                    <i class="fas fa-pray mr-2"></i> Mutabaah Harian
                </button>
                <button onclick="switchGuruTab('tahfidz')" id="tabBtnTahfidz" class="flex-1 py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest transition-all text-slate-400">
                    <i class="fas fa-book-quran mr-2"></i> Setoran Tahfidz
                </button>
            </div>

            <!-- MUTABAAH PANEL -->
            <div id="guruPanelMutabaah">
                <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 space-y-4 md:space-y-0">
                        <div>
                            <h4 class="text-2xl font-black text-slate-800 mb-1">Checklist Ibadah Harian</h4>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $homeroomClass->kelas_lengkap }} • {{ $students->count() }} Siswa</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="date" id="guru_date" value="{{ $today->format('Y-m-d') }}" class="bg-slate-50 border-0 rounded-2xl py-3 px-4 text-xs font-bold text-slate-600 outline-none focus:ring-2 focus:ring-emerald-500" onchange="loadGuruMutabaah()">
                            <button onclick="saveGuruMutabaah()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-6 rounded-2xl shadow-xl transition-all active:scale-95 uppercase tracking-widest text-xs">
                                <i class="fas fa-save mr-2"></i> SIMPAN
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="guruMutabaahTable">
                            <thead>
                                <tr class="bg-emerald-50 text-emerald-800">
                                    <th class="py-3 px-4 text-left text-[10px] font-black uppercase tracking-widest">#</th>
                                    <th class="py-3 px-4 text-left text-[10px] font-black uppercase tracking-widest">Nama Siswa</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Shubuh</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Zhuhur</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Ashar</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Maghrib</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Isya</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Dhuha</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Tahajud</th>
                                    <th class="py-3 px-2 text-center text-[10px] font-black uppercase tracking-widest">Skor</th>
                                </tr>
                            </thead>
                            <tbody id="guruMutabaahBody">
                                @foreach($students as $i => $s)
                                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors" data-sid="{{ $s->id }}">
                                    <td class="py-4 px-4 text-xs text-slate-400">{{ $i+1 }}</td>
                                    <td class="py-4 px-4">
                                        <h6 class="text-sm font-black text-slate-700 mb-0">{{ $s->nama_lengkap }}</h6>
                                        <span class="text-[9px] font-bold text-slate-400">{{ $s->nis }}</span>
                                    </td>
                                    @foreach(['shubuh','zhuhur','ashar','maghrib','isya','dhuha','tahajud'] as $field)
                                    <td class="py-4 px-2 text-center">
                                        <label class="ibadah-toggle">
                                            <input type="checkbox" class="mutabaah-cb" data-student="{{ $s->id }}" data-field="{{ $field }}">
                                            <span class="ibadah-slider"></span>
                                        </label>
                                    </td>
                                    @endforeach
                                    <td class="py-4 px-2 text-center">
                                        <span class="skor-display text-sm font-black text-slate-300" data-student="{{ $s->id }}">0/7</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAHFIDZ PANEL -->
            <div id="guruPanelTahfidz" style="display:none;">
                <div class="row g-4 mb-8">
                    <!-- FORM INPUT -->
                    <div class="col-lg-5">
                        <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50">
                            <h5 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] mb-6"><i class="fas fa-book-quran mr-2 text-purple-600"></i> Input Setoran</h5>
                            <form id="guruFormTahfidz">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Siswa</label>
                                        <select name="student_id" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500">
                                            @foreach($students as $s)
                                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Surat</label>
                                            <input type="text" name="surah_name" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500" placeholder="Al-Baqarah">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Ayat</label>
                                            <input type="text" name="verse_range" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500" placeholder="1-10">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Juz</label>
                                            <input type="number" name="juz" min="1" max="30" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Tipe</label>
                                            <select name="type" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500">
                                                <option value="ziyadah">Ziyadah</option>
                                                <option value="murojaah">Murojaah</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Grade</label>
                                            <select name="grade" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500">
                                                <option value="A">A</option>
                                                <option value="B+" selected>B+</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Skor Tajwid (0-100)</label>
                                        <input type="number" name="tajwid_score" value="75" min="0" max="100" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500">
                                    </div>
                                    <input type="hidden" name="date" value="{{ $today->format('Y-m-d') }}">
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Catatan</label>
                                        <textarea name="notes" rows="2" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-purple-500" placeholder="Opsional..."></textarea>
                                    </div>
                                    <button type="button" onclick="submitGuruTahfidz()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-black py-5 rounded-[1.5rem] shadow-2xl transition-all active:scale-95 uppercase tracking-widest text-xs">
                                        <i class="fas fa-save mr-2"></i> Simpan Setoran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIWAYAT -->
                    <div class="col-lg-7">
                        <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50">
                            <h5 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] mb-6">Riwayat Setoran</h5>
                            <div class="table-responsive">
                                <table class="table table-hover" id="guruTahfidzTable" style="width:100%">
                                    <thead><tr>
                                        <th>Tanggal</th><th>Siswa</th><th>Surat</th><th>Tipe</th><th>Grade</th><th>Tajwid</th>
                                    </tr></thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-grad-emerald { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); }
    .ibadah-toggle { position:relative; display:inline-block; width:36px; height:20px; }
    .ibadah-toggle input { opacity:0; width:0; height:0; }
    .ibadah-slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#e2e8f0; transition:0.3s; border-radius:20px; }
    .ibadah-slider:before { position:absolute; content:""; height:16px; width:16px; left:2px; bottom:2px; background:white; transition:0.3s; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.2); }
    .ibadah-toggle input:checked + .ibadah-slider { background:#059669; }
    .ibadah-toggle input:checked + .ibadah-slider:before { transform:translateX(16px); }
</style>
@endsection

@push('scripts')
<script>
let guruTahfidzDT = null;

function switchGuruTab(tab) {
    if (tab === 'mutabaah') {
        $('#guruPanelMutabaah').show(); $('#guruPanelTahfidz').hide();
        $('#tabBtnMutabaah').addClass('bg-emerald-600 text-white shadow-lg').removeClass('text-slate-400');
        $('#tabBtnTahfidz').removeClass('bg-emerald-600 text-white shadow-lg').addClass('text-slate-400');
    } else {
        $('#guruPanelMutabaah').hide(); $('#guruPanelTahfidz').show();
        $('#tabBtnTahfidz').addClass('bg-emerald-600 text-white shadow-lg').removeClass('text-slate-400');
        $('#tabBtnMutabaah').removeClass('bg-emerald-600 text-white shadow-lg').addClass('text-slate-400');
        if (!guruTahfidzDT) initGuruTahfidzDT();
    }
}

function loadGuruMutabaah() {
    let date = $('#guru_date').val();
    $.get('{{ route("guru.mutabaah.data") }}', {date: date}, function(logs) {
        $('.mutabaah-cb').prop('checked', false);
        Object.keys(logs).forEach(sid => {
            let log = logs[sid];
            ['shubuh','zhuhur','ashar','maghrib','isya','dhuha','tahajud'].forEach(f => {
                if (log[f]) $(`.mutabaah-cb[data-student="${sid}"][data-field="${f}"]`).prop('checked', true);
            });
        });
        updateAllScores();
    });
}

function updateAllScores() {
    let students = {};
    $('.mutabaah-cb').each(function() {
        let sid = $(this).data('student');
        if (!students[sid]) students[sid] = 0;
        if ($(this).is(':checked')) students[sid]++;
    });
    Object.keys(students).forEach(sid => {
        let s = students[sid];
        let color = s >= 5 ? '#059669' : (s >= 3 ? '#d97706' : '#dc2626');
        $(`.skor-display[data-student="${sid}"]`).text(s + '/7').css('color', color);
    });
}

$(document).on('change', '.mutabaah-cb', updateAllScores);

function saveGuruMutabaah() {
    let date = $('#guru_date').val();
    let students = {};
    $('.mutabaah-cb').each(function() {
        let sid = $(this).data('student');
        let field = $(this).data('field');
        if (!students[sid]) students[sid] = {};
        students[sid][field] = $(this).is(':checked') ? 1 : 0;
    });

    Swal.fire({title:'Simpan Mutabaah?',icon:'question',showCancelButton:true,confirmButtonColor:'#059669',confirmButtonText:'Ya, Simpan!'}).then(r => {
        if (r.isConfirmed) {
            $.post('{{ route("guru.mutabaah.store_mutabaah") }}', {_token:'{{ csrf_token() }}', date:date, students:students})
                .done(res => Swal.fire({icon:'success',title:'Berhasil',text:res.message,timer:1500,showConfirmButton:false}))
                .fail(err => Swal.fire({icon:'error',title:'Gagal',text:err.responseJSON?.message||'Error'}));
        }
    });
}

function initGuruTahfidzDT() {
    guruTahfidzDT = $('#guruTahfidzTable').DataTable({
        processing:true, serverSide:true, pageLength:10,
        ajax:'{{ route("guru.mutabaah.tahfidz_data") }}',
        columns:[{data:'tanggal'},{data:'nama_siswa'},{data:'surah_name'},{data:'type_badge'},{data:'grade_badge'},{data:'tajwid_score'}]
    });
}

function submitGuruTahfidz() {
    Swal.fire({title:'Simpan Setoran?',icon:'question',showCancelButton:true,confirmButtonColor:'#7c3aed',confirmButtonText:'Simpan'}).then(r => {
        if (r.isConfirmed) {
            $.post('{{ route("guru.mutabaah.store_tahfidz") }}', $('#guruFormTahfidz').serialize())
                .done(res => {
                    Swal.fire({icon:'success',title:'Berhasil',text:res.message,timer:1500,showConfirmButton:false});
                    if (guruTahfidzDT) guruTahfidzDT.ajax.reload();
                    $('#guruFormTahfidz')[0].reset();
                })
                .fail(err => Swal.fire({icon:'error',title:'Gagal',text:err.responseJSON?.message||'Error'}));
        }
    });
}

$(function() { loadGuruMutabaah(); });
</script>
@endpush
