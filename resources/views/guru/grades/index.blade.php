@extends($layout)
@section('title', 'Input Nilai Siswa')

@section('content')
<div class="dashboard-wrapper pb-20">

    {{-- HEADER BANNER --}}
    <div class="header-banner bg-grad-emerald pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center border border-white/30">
                        <i class="fas fa-book-open text-white text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <span class="bg-white/20 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-1 inline-block">Portal Nilai</span>
                        <h1 class="text-2xl font-black leading-tight">Input Nilai Siswa</h1>
                        <p class="text-white/70 text-xs font-bold mt-1">
                            <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $teacher->name }}
                            @if($activeYear)
                                &nbsp;•&nbsp; TA {{ $activeYear->year ?? '' }}
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-2 bg-white/15 hover:bg-white/25 text-white text-xs font-black px-5 py-3 rounded-2xl border border-white/20 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
        <div class="absolute right-[-50px] top-[-30px] w-56 h-56 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-20px] bottom-[-30px] w-40 h-40 bg-emerald-400/10 rounded-full blur-2xl"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 -mt-12 relative z-20">

        {{-- FILTER CARD --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-50 mb-6 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center space-x-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-emerald-600 text-sm"></i>
                </div>
                <div>
                    <h5 class="font-black text-slate-800 text-sm mb-0">Pilih Kelas & Mata Pelajaran</h5>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0">Hanya menampilkan kelas & mapel yang Anda ampu</p>
                </div>
            </div>
            <div class="p-6">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Rombongan Belajar</label>
                        <select id="sel-class" class="form-control rounded-xl border-slate-200 font-bold text-sm" style="height:46px">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($myClasses as $cls)
                                <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->kelas_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Mata Pelajaran</label>
                        <select id="sel-subject" class="form-control rounded-xl border-slate-200 font-bold text-sm" style="height:46px" {{ !$selectedClassId ? 'disabled' : '' }}>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mySubjects as $gs)
                                <option value="{{ $gs->subject_id }}" {{ $selectedSubjectId == $gs->subject_id ? 'selected' : '' }}>
                                    {{ $gs->subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Semester</label>
                        <select id="sel-semester" class="form-control rounded-xl border-slate-200 font-bold text-sm" style="height:46px">
                            <option value="1" {{ $selectedSemester == 1 ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ $selectedSemester == 2 ? 'selected' : '' }}>Semester 2 (Genap)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button onclick="loadGrades()" id="btn-load" class="w-100 btn btn-emerald font-black text-sm rounded-xl px-4" style="height:46px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none">
                            <i class="fas fa-search mr-2"></i> Tampilkan Nilai
                        </button>
                    </div>
                </div>

                {{-- INFO KELAS --}}
                <div id="class-info" class="mt-4 {{ ($selectedClassId && $selectedSubjectId) ? '' : 'd-none' }}">
                    <div class="flex flex-wrap gap-3">
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-black px-3 py-2 rounded-xl border border-emerald-100">
                            <i class="fas fa-school mr-1"></i>
                            <span id="lbl-class">{{ $selectedClass?->kelas_lengkap ?? '-' }}</span>
                        </span>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-black px-3 py-2 rounded-xl border border-indigo-100">
                            <i class="fas fa-book mr-1"></i>
                            <span id="lbl-subject">-</span>
                        </span>
                        <span class="bg-amber-50 text-amber-700 text-[10px] font-black px-3 py-2 rounded-xl border border-amber-100">
                            <i class="fas fa-calendar mr-1"></i>
                            Semester <span id="lbl-semester">{{ $selectedSemester }}</span>
                        </span>
                        <span id="lbl-count" class="bg-slate-50 text-slate-600 text-[10px] font-black px-3 py-2 rounded-xl border border-slate-100">
                            <i class="fas fa-users mr-1"></i> 0 Siswa
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRADE TABLE CARD --}}
        <div id="grade-card" class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden {{ ($selectedClassId && $selectedSubjectId) ? '' : 'd-none' }}">
            {{-- Card Header --}}
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-list-ol text-emerald-600 text-sm"></i>
                    </div>
                    <div>
                        <h5 class="font-black text-slate-800 text-sm mb-0">Daftar Nilai Siswa</h5>
                        <p class="text-[10px] text-slate-400 font-bold mb-0">Klik kolom nilai untuk mengedit langsung</p>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button onclick="saveAll()" id="btn-save-all" class="btn btn-sm font-black text-xs rounded-xl px-4 py-2" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;display:none">
                        <i class="fas fa-save mr-1"></i> Simpan Semua
                    </button>
                    <button onclick="exportGrades()" class="btn btn-sm btn-light border font-black text-xs rounded-xl px-4 py-2 text-slate-600">
                        <i class="fas fa-file-excel text-emerald-600 mr-1"></i> Export Excel
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table id="grade-table" class="table align-middle mb-0">
                    <thead style="background:#f0fdf4;border-bottom:2px solid #6ee7b7">
                        <tr>
                            <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="50">NO</th>
                            <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">IDENTITAS SISWA</th>
                            <th class="text-center px-4 py-3 text-[10px] font-black text-emerald-700 uppercase tracking-widest" width="150">NILAI (0-100)</th>
                            <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="120">KATEGORI</th>
                            <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="80">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="grade-tbody">
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <i class="fas fa-filter text-slate-200 fa-3x mb-3 d-block"></i>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0">Pilih kelas & mata pelajaran untuk memuat data</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Progress Bar --}}
            <div id="progress-bar-wrap" class="px-6 pb-4 d-none">
                <div class="d-flex justify-between mb-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Progres Pengisian</span>
                    <span id="progress-label" class="text-[10px] font-black text-emerald-600">0 / 0</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full" style="height:6px">
                    <div id="progress-bar" class="rounded-full transition-all" style="height:6px;background:linear-gradient(90deg,#10b981,#34d399);width:0%"></div>
                </div>
            </div>
        </div>

        {{-- EMPTY STATE --}}
        <div id="empty-state" class="{{ ($selectedClassId && $selectedSubjectId) ? 'd-none' : '' }} text-center py-16">
            <div class="w-24 h-24 bg-emerald-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-book-open text-emerald-400 text-3xl"></i>
            </div>
            <h5 class="font-black text-slate-700 mb-2">Mulai Input Nilai</h5>
            <p class="text-sm text-slate-400 max-w-sm mx-auto">Pilih Kelas, Mata Pelajaran, dan Semester dari panel di atas, lalu klik <strong>Tampilkan Nilai</strong>.</p>
        </div>

    </div>
</div>

<style>
body { background:#f8fafc; font-family:'Outfit',sans-serif; }
.bg-grad-emerald { background:linear-gradient(135deg,#064e3b 0%,#059669 100%); }
.header-banner { padding-top:40px; padding-bottom:80px; }

/* Table rows */
#grade-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background 0.15s; }
#grade-table tbody tr:hover { background:#f0fdf4; }
#grade-table td { padding:0.85rem 1rem; vertical-align:middle; }

/* Score input */
.input-score {
    border:2px solid #e2e8f0 !important;
    border-radius:12px; font-weight:900; font-size:1rem;
    color:#1e293b; text-align:center; height:44px;
    transition:all 0.2s; background:#f8fafc; width:100px;
}
.input-score:hover { border-color:#6ee7b7 !important; background:#fff; }
.input-score:focus { border-color:#10b981 !important; box-shadow:0 0 0 3px rgba(16,185,129,.15) !important; background:#fff; transform:scale(1.05); }
.input-score.score-changed { border-color:#f59e0b !important; background:#fefce8; }
.input-score.score-saved  { border-color:#10b981 !important; background:#dcfce7; }
.input-score.score-error  { border-color:#ef4444 !important; background:#fee2e2; }

/* Grade badge */
.grade-badge {
    display:inline-block; padding:3px 10px; border-radius:8px;
    font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:.5px;
}
.grade-A { background:#dcfce7; color:#166534; }
.grade-B { background:#dbeafe; color:#1e40af; }
.grade-C { background:#fef9c3; color:#854d0e; }
.grade-D { background:#fee2e2; color:#991b1b; }

/* Btn save row */
.btn-save-row {
    width:36px; height:36px; border-radius:10px; background:#10b981;
    color:#fff; border:none; display:flex; align-items:center; justify-content:center;
    font-size:13px; transition:all .2s; cursor:pointer;
}
.btn-save-row:hover { background:#059669; transform:scale(1.1); }
.btn-save-row.saving { background:#6ee7b7; pointer-events:none; }

@media(max-width:768px){
    .header-banner { padding-top:30px; padding-bottom:70px; }
}
</style>
@endsection

@include('includes.select2')
@push('scripts')
<script>
const ROUTES = {
    data:       '{{ route("guru.grades.data") }}',
    save:       '{{ route("guru.grades.save") }}',
    saveBulk:   '{{ route("guru.grades.save_bulk") }}',
    subjects:   '{{ route("guru.grades.subjects") }}',
};
const CSRF = '{{ csrf_token() }}';

// ——— Load subjects when class changes ———
$('#sel-class').on('change', function(){
    const classId = $(this).val();
    $('#sel-subject').html('<option value="">Memuat mapel...</option>').prop('disabled', true);

    if (!classId) {
        $('#sel-subject').html('<option value="">-- Pilih Mapel --</option>').prop('disabled', true);
        return;
    }

    $.get(ROUTES.subjects, { class_id: classId }, function(res){
        let opts = '<option value="">-- Pilih Mapel --</option>';
        res.forEach(s => opts += `<option value="${s.id}">${s.name}</option>`);
        $('#sel-subject').html(opts).prop('disabled', false);
    }).fail(function(){
        $('#sel-subject').html('<option value="">-- Tidak ada mapel --</option>').prop('disabled', true);
    });
});

// ——— Load Grades ———
function loadGrades() {
    const classId    = $('#sel-class').val();
    const subjectId  = $('#sel-subject').val();
    const semester   = $('#sel-semester').val();

    if (!classId || !subjectId) {
        Swal.fire({ icon:'warning', title:'Filter Belum Lengkap', text:'Pilih Kelas dan Mata Pelajaran terlebih dahulu.', customClass:{popup:'rounded-[2rem]'} });
        return;
    }

    const className   = $('#sel-class option:selected').text().trim();
    const subjectName = $('#sel-subject option:selected').text().trim();

    $('#lbl-class').text(className);
    $('#lbl-subject').text(subjectName);
    $('#lbl-semester').text(semester);
    $('#class-info').removeClass('d-none');
    $('#grade-card').removeClass('d-none');
    $('#empty-state').addClass('d-none');

    $('#grade-tbody').html(`
        <tr><td colspan="5" class="text-center py-16">
            <i class="fas fa-spinner fa-spin text-emerald-500 fa-2x mb-3 d-block"></i>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0">Memuat data nilai...</p>
        </td></tr>`);

    $('#btn-save-all').hide();
    $('#progress-bar-wrap').addClass('d-none');

    $.get(ROUTES.data, { class_id: classId, subject_id: subjectId, semester: semester }, function(res){
        const rows = res.data;
        if (!rows.length) {
            $('#grade-tbody').html(`<tr><td colspan="5" class="text-center py-12"><i class="fas fa-users-slash text-slate-200 fa-2x mb-3 d-block"></i><p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0">Tidak ada siswa di kelas ini</p></td></tr>`);
            return;
        }

        let html = '';
        rows.forEach((row, i) => {
            const score = parseInt(row.grade) || 0;
            const { cls, label } = getGradeCategory(score);
            html += `
            <tr data-student-id="${row.id}">
                <td class="text-center">
                    <span class="text-xs font-black text-slate-400">${i+1}</span>
                </td>
                <td>
                    <div class="flex items-center space-x-3">
                        <img src="${row.photo}" class="w-10 h-10 rounded-xl object-cover shadow-sm" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(row.nama_lengkap)}&background=6366f1&color=fff&bold=true'">
                        <div>
                            <div class="text-sm font-black text-slate-700">${row.nama_lengkap}</div>
                            <div class="text-[10px] font-bold text-slate-400">NIS: ${row.nis || '-'}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <input type="number" min="0" max="100" step="1"
                        class="input-score mx-auto"
                        value="${score}"
                        data-student="${row.id}"
                        data-original="${score}"
                        onchange="onScoreChange(this)"
                        onkeyup="if(event.keyCode===13) saveSingle(this)">
                </td>
                <td class="text-center">
                    <span class="grade-badge grade-${cls}" data-badge="${row.id}">${label}</span>
                </td>
                <td class="text-center">
                    <button class="btn-save-row" onclick="saveSingle(this.closest('tr').querySelector('.input-score'))" title="Simpan">
                        <i class="fas fa-check"></i>
                    </button>
                </td>
            </tr>`;
        });

        $('#grade-tbody').html(html);
        $('#lbl-count').html(`<i class="fas fa-users mr-1"></i> ${rows.length} Siswa`);
        $('#btn-save-all').show();
        updateProgress();
        $('#progress-bar-wrap').removeClass('d-none');
    }).fail(function(xhr){
        Swal.fire('Gagal Memuat', xhr.responseJSON?.message || 'Terjadi kesalahan jaringan', 'error');
    });
}

// ——— Score change handler ———
function onScoreChange(input) {
    let val = parseInt($(input).val()) || 0;
    if (val > 100) { $(input).val(100); val = 100; }
    if (val < 0)   { $(input).val(0);   val = 0; }
    $(input).addClass('score-changed').removeClass('score-saved score-error');

    // Update badge live
    const { cls, label } = getGradeCategory(val);
    const studentId = $(input).data('student');
    $(`[data-badge="${studentId}"]`).attr('class', `grade-badge grade-${cls}`).text(label);
    updateProgress();
}

// ——— Save single row ———
function saveSingle(input) {
    const $input   = $(input);
    const studentId = $input.data('student');
    const classId   = $('#sel-class').val();
    const subjectId = $('#sel-subject').val();
    const semester  = $('#sel-semester').val();
    const score     = $input.val();
    const $btn      = $input.closest('tr').find('.btn-save-row');

    $btn.addClass('saving').html('<i class="fas fa-spinner fa-spin"></i>');

    $.post(ROUTES.save, {
        _token: CSRF, student_id: studentId, subject_id: subjectId,
        class_id: classId, semester: semester, score: score
    }).done(function(){
        $input.addClass('score-saved').removeClass('score-changed score-error');
        $input.data('original', score);
        setTimeout(() => $input.removeClass('score-saved'), 2000);
        toastr.success('Nilai disimpan');
    }).fail(function(xhr){
        $input.addClass('score-error').removeClass('score-changed');
        Swal.fire('Gagal', xhr.responseJSON?.message || 'Kesalahan jaringan', 'error');
    }).always(function(){
        $btn.removeClass('saving').html('<i class="fas fa-check"></i>');
    });
}

// ——— Save ALL ———
function saveAll() {
    const classId   = $('#sel-class').val();
    const subjectId = $('#sel-subject').val();
    const semester  = $('#sel-semester').val();
    if (!classId || !subjectId) return;

    let grades = {};
    $('.input-score').each(function(){
        grades[$(this).data('student')] = $(this).val() || 0;
    });

    Swal.fire({
        title: 'Simpan Semua Nilai?',
        html: `Akan menyimpan nilai <strong>${Object.keys(grades).length} siswa</strong>.`,
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#10b981', confirmButtonText: 'Ya, Simpan Semua',
        cancelButtonText: 'Batal', customClass: {popup: 'rounded-[2rem]'}
    }).then(r => {
        if (!r.isConfirmed) return;
        Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.post(ROUTES.saveBulk, {
            _token: CSRF, class_id: classId, subject_id: subjectId, semester: semester, grades: grades
        }).done(function(res){
            Swal.fire({ icon:'success', title:'Berhasil!', text: res.message, timer:2000, showConfirmButton:false });
            $('.input-score').removeClass('score-changed score-error').addClass('score-saved');
            setTimeout(() => $('.input-score').removeClass('score-saved'), 2000);
        }).fail(function(xhr){
            Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
        });
    });
}

// ——— Export Excel (simple print) ———
function exportGrades() {
    const classId = $('#sel-class').val(), subjectId = $('#sel-subject').val(), semester = $('#sel-semester').val();
    if (!classId || !subjectId) { Swal.fire({icon:'warning', title:'Pilih kelas & mapel terlebih dahulu'}); return; }
    const className = $('#sel-class option:selected').text().trim();
    const subjectName = $('#sel-subject option:selected').text().trim();

    let rows = '<tr><th>No</th><th>Nama Siswa</th><th>NIS</th><th>Nilai</th><th>Kategori</th></tr>';
    $('#grade-tbody tr').each(function(i){
        const name  = $(this).find('.text-sm.font-black').text().trim();
        const nis   = $(this).find('.text-slate-400').text().replace('NIS:','').trim();
        const score = $(this).find('.input-score').val();
        const badge = $(this).find('.grade-badge').text().trim();
        if (name) rows += `<tr><td>${i+1}</td><td>${name}</td><td>${nis}</td><td>${score}</td><td>${badge}</td></tr>`;
    });

    const win = window.open('', '_blank');
    win.document.write(`<html><head><title>Nilai ${subjectName} - ${className}</title>
    <style>table{border-collapse:collapse;width:100%}th,td{border:1px solid #ccc;padding:8px;font-family:Arial}th{background:#f0fdf4}</style>
    </head><body><h3>Daftar Nilai: ${subjectName}</h3><h4>Kelas: ${className} | Semester: ${semester}</h4><table>${rows}</table></body></html>`);
    win.document.close(); win.print();
}

// ——— Progress ———
function updateProgress() {
    const total = $('.input-score').length;
    const filled = $('.input-score').filter(function(){ return parseInt($(this).val()) > 0; }).length;
    const pct = total ? Math.round(filled / total * 100) : 0;
    $('#progress-bar').css('width', pct + '%');
    $('#progress-label').text(`${filled} / ${total} diisi`);
}

// ——— Grade Category ———
function getGradeCategory(score) {
    if (score >= 90) return { cls:'A', label:'Sangat Baik' };
    if (score >= 75) return { cls:'B', label:'Baik' };
    if (score >= 60) return { cls:'C', label:'Cukup' };
    return { cls:'D', label:'Kurang' };
}

// ——— Init: auto-load jika filter sudah ada ———
$(function(){
    @if($selectedClassId && $selectedSubjectId)
        loadGrades();
    @endif
});
</script>
@endpush
