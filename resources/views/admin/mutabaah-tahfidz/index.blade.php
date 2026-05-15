@extends($layout)

@section('title', 'Mutabaah & Tahfidz')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 overflow-hidden position-relative" style="border-radius:15px;background:linear-gradient(135deg,#059669 0%,#047857 100%);">
            <div class="card-body p-4 position-relative" style="z-index:1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1"><i class="fas fa-pray mr-2"></i> Mutabaah & Tahfidz Tracker</h2>
                        <p class="mb-0 opacity-8 font-weight-light">Pantau ibadah harian dan hafalan Al-Qur'an seluruh siswa secara terpusat.</p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block"><i class="fas fa-quran fa-8x" style="opacity:0.15;"></i></div>
                </div>
            </div>
            <div style="position:absolute;width:300px;height:300px;top:-100px;right:-50px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>
            <div style="position:absolute;width:150px;height:150px;bottom:-50px;left:10%;background:rgba(255,255,255,0.1);border-radius:50%;"></div>
        </div>
    </div>
</div>

<!-- STATISTICS -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius:12px;border-left:5px solid #059669 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Mutabaah Hari Ini</p>
                        <h2 class="font-weight-bold mb-0 text-success">{{ $statMutabaahToday }}</h2>
                    </div>
                    <div style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;background:#dcfce7;border-radius:50%;">
                        <i class="fas fa-hands-praying text-success fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius:12px;border-left:5px solid #7c3aed !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Setoran Bulan Ini</p>
                        <h2 class="font-weight-bold mb-0" style="color:#7c3aed;">{{ $statTahfidzMonth }}</h2>
                    </div>
                    <div style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;background:#ede9fe;border-radius:50%;">
                        <i class="fas fa-book-quran fa-lg" style="color:#7c3aed;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius:12px;border-left:5px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Rata-rata Tajwid</p>
                        <h2 class="font-weight-bold mb-0 text-warning">{{ number_format($statAvgTajwid, 0) }}<small class="text-muted">/100</small></h2>
                    </div>
                    <div style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;background:#fef3c7;border-radius:50%;">
                        <i class="fas fa-star text-warning fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT: FILTERS & FORM -->
    <div class="col-xl-4 col-lg-5">
        <!-- FILTER -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius:15px;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <span class="badge badge-success mr-2" style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;font-size:14px;">1</span>
                    Konfigurasi
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Rombongan Belajar</label>
                    <select id="filter_class" class="form-control select2">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classGroups as $cg)
                            <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Tanggal</label>
                    <input type="date" id="filter_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Mode</label>
                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                        <label class="btn btn-outline-success active" onclick="switchTab('mutabaah')">
                            <input type="radio" name="mode" checked> <i class="fas fa-pray mr-1"></i> Mutabaah
                        </label>
                        <label class="btn btn-outline-primary" onclick="switchTab('tahfidz')">
                            <input type="radio" name="mode"> <i class="fas fa-book-quran mr-1"></i> Tahfidz
                        </label>
                    </div>
                </div>
                <button type="button" onclick="loadData()" class="btn btn-success btn-block shadow-sm font-weight-bold py-2" style="border-radius:10px;">
                    <i class="fas fa-search-plus mr-2"></i> MUAT DATA
                </button>
            </div>
        </div>

        <!-- FORM TAHFIDZ -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius:15px;border-left:5px solid #7c3aed !important;" id="tahfidzFormCard">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold mb-0" style="color:#7c3aed;">
                    <span class="badge mr-2" style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;font-size:14px;background:#7c3aed;color:white;">2</span>
                    Input Setoran Tahfidz
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="formTahfidz">
                    @csrf
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted">SISWA</label>
                        <select name="student_id" id="tahfidz_student" class="form-control select2">
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="text-xs font-weight-bold text-muted">NAMA SURAT</label>
                                <input type="text" name="surah_name" class="form-control form-control-sm" placeholder="Al-Baqarah">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="text-xs font-weight-bold text-muted">AYAT</label>
                                <input type="text" name="verse_range" class="form-control form-control-sm" placeholder="1-10">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group mb-2">
                                <label class="text-xs font-weight-bold text-muted">JUZ</label>
                                <input type="number" name="juz" class="form-control form-control-sm" min="1" max="30">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-2">
                                <label class="text-xs font-weight-bold text-muted">TIPE</label>
                                <select name="type" class="form-control form-control-sm">
                                    <option value="ziyadah">Ziyadah</option>
                                    <option value="murojaah">Murojaah</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-2">
                                <label class="text-xs font-weight-bold text-muted">GRADE</label>
                                <select name="grade" class="form-control form-control-sm">
                                    <option value="A">A</option>
                                    <option value="B+" selected>B+</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted">SKOR TAJWID (0-100)</label>
                        <input type="number" name="tajwid_score" class="form-control form-control-sm" value="75" min="0" max="100">
                    </div>
                    <input type="hidden" name="date" id="tahfidz_date" value="{{ date('Y-m-d') }}">
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted">CATATAN</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <button type="button" onclick="submitTahfidz()" class="btn btn-block shadow-lg font-weight-bold py-2" style="border-radius:10px;background:#7c3aed;color:white;">
                        <i class="fas fa-save mr-2"></i> SIMPAN SETORAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT: DATA TABLE -->
    <div class="col-xl-8 col-lg-7">
        <!-- MUTABAAH TAB -->
        <div id="panelMutabaah">
            <div class="card shadow-sm border-0" style="border-radius:15px;">
                <div class="card-header bg-white py-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 font-weight-bold text-dark">Checklist Mutabaah</h4>
                            <p class="text-muted text-sm mb-0" id="mutabaah_subtitle">Pilih kelas dan tanggal terlebih dahulu</p>
                        </div>
                        <button type="button" onclick="saveMutabaah()" class="btn btn-success btn-sm rounded-pill px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> SIMPAN SEMUA
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="mutabaahTable">
                            <thead style="background:#f0fdf4;color:#166534;font-size:0.7rem;font-weight:800;letter-spacing:1px;">
                                <tr>
                                    <th class="py-3" width="30">#</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center" width="50">Shubuh</th>
                                    <th class="text-center" width="50">Zhuhur</th>
                                    <th class="text-center" width="50">Ashar</th>
                                    <th class="text-center" width="50">Maghrib</th>
                                    <th class="text-center" width="50">Isya</th>
                                    <th class="text-center" width="50">Dhuha</th>
                                    <th class="text-center" width="50">Tahajud</th>
                                    <th class="text-center" width="60">Skor</th>
                                </tr>
                            </thead>
                            <tbody id="mutabaahBody">
                                <tr><td colspan="10" class="text-center py-5 text-muted"><i class="fas fa-mosque fa-2x mb-2 d-block" style="opacity:0.3;"></i>Pilih kelas untuk memuat data</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAHFIDZ TAB -->
        <div id="panelTahfidz" style="display:none;">
            <div class="card shadow-sm border-0" style="border-radius:15px;">
                <div class="card-header bg-white py-4 border-bottom">
                    <h4 class="mb-1 font-weight-bold text-dark">Riwayat Setoran Tahfidz</h4>
                    <p class="text-muted text-sm mb-0">Data setoran hafalan bulan ini</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tahfidzTable" style="width:100%">
                            <thead style="background:#f5f3ff;color:#5b21b6;font-size:0.7rem;font-weight:800;letter-spacing:1px;">
                                <tr>
                                    <th class="py-3">Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Surat</th>
                                    <th>Ayat</th>
                                    <th>Juz</th>
                                    <th>Tipe</th>
                                    <th>Grade</th>
                                    <th>Tajwid</th>
                                    <th>Guru</th>
                                    <th width="50">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .premium-check { width:22px;height:22px;cursor:pointer;accent-color:#059669; }
    .skor-badge { display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;font-weight:800;font-size:0.85rem; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
let currentTab = 'mutabaah';
let tahfidzDT = null;
let studentList = [];

function switchTab(tab) {
    currentTab = tab;
    $('#panelMutabaah').toggle(tab === 'mutabaah');
    $('#panelTahfidz').toggle(tab === 'tahfidz');
    $('#tahfidzFormCard').toggle(tab === 'tahfidz');
    if (tab === 'tahfidz') loadTahfidzTable();
}

function loadData() {
    let classId = $('#filter_class').val();
    let date = $('#filter_date').val();
    if (!classId) { Swal.fire({icon:'warning',title:'Pilih Kelas',text:'Silakan pilih rombongan belajar.'}); return; }

    // Load students for tahfidz form
    $.get('{{ route("mutabaah-tahfidz.students") }}', {class_group_id: classId}, function(data) {
        studentList = data;
        let opts = '<option value="">-- Pilih --</option>';
        data.forEach(s => opts += `<option value="${s.id}">${s.nama_lengkap} (${s.nis})</option>`);
        $('#tahfidz_student').html(opts).trigger('change.select2');
    });

    $('#tahfidz_date').val(date);

    if (currentTab === 'mutabaah') loadMutabaahTable(classId, date);
    else loadTahfidzTable();
}

function loadMutabaahTable(classId, date) {
    $.get('{{ route("mutabaah-tahfidz.mutabaah_data") }}', {class_group_id: classId, date: date}, function(res) {
        let rows = '';
        if (res.data && res.data.length) {
            res.data.forEach((s, i) => {
                let m = s.mutabaah;
                let skor = s.skor;
                let bg = skor >= 5 ? '#dcfce7' : (skor >= 3 ? '#fef9c3' : '#fee2e2');
                let color = skor >= 5 ? '#166534' : (skor >= 3 ? '#854d0e' : '#991b1b');
                rows += `<tr>
                    <td>${i+1}</td>
                    <td class="font-weight-bold">${s.nama_lengkap}</td>
                    ${checkboxCell(s.id, 'shubuh', m.shubuh)}
                    ${checkboxCell(s.id, 'zhuhur', m.zhuhur)}
                    ${checkboxCell(s.id, 'ashar', m.ashar)}
                    ${checkboxCell(s.id, 'maghrib', m.maghrib)}
                    ${checkboxCell(s.id, 'isya', m.isya)}
                    ${checkboxCell(s.id, 'dhuha', m.dhuha)}
                    ${checkboxCell(s.id, 'tahajud', m.tahajud)}
                    <td class="text-center"><span class="skor-badge" style="background:${bg};color:${color};">${skor}/7</span></td>
                </tr>`;
            });
            $('#mutabaah_subtitle').text(res.data.length + ' siswa dimuat — ' + date);
        } else {
            rows = '<tr><td colspan="10" class="text-center py-4 text-muted">Tidak ada data siswa</td></tr>';
        }
        $('#mutabaahBody').html(rows);
    });
}

function checkboxCell(sid, field, checked) {
    return `<td class="text-center"><input type="checkbox" class="premium-check" data-student="${sid}" data-field="${field}" ${checked ? 'checked' : ''}></td>`;
}

function saveMutabaah() {
    let date = $('#filter_date').val();
    let students = {};
    $('#mutabaahBody input[type=checkbox]').each(function() {
        let sid = $(this).data('student');
        let field = $(this).data('field');
        if (!students[sid]) students[sid] = {};
        students[sid][field] = $(this).is(':checked') ? 1 : 0;
    });

    if (Object.keys(students).length === 0) { Swal.fire({icon:'warning',title:'Kosong',text:'Tidak ada data untuk disimpan.'}); return; }

    Swal.fire({title:'Simpan Mutabaah?',text:`Data ${Object.keys(students).length} siswa akan disimpan.`,icon:'question',showCancelButton:true,confirmButtonColor:'#059669',confirmButtonText:'Ya, Simpan!'}).then(r => {
        if (r.isConfirmed) {
            $.post('{{ route("mutabaah-tahfidz.store_mutabaah") }}', {_token:'{{ csrf_token() }}', date:date, students:students})
                .done(res => { Swal.fire({icon:'success',title:'Berhasil',text:res.message,timer:1500,showConfirmButton:false}); })
                .fail(err => { Swal.fire({icon:'error',title:'Gagal',text:err.responseJSON?.message||'Error'}); });
        }
    });
}

function loadTahfidzTable() {
    let classId = $('#filter_class').val();
    if (tahfidzDT) { tahfidzDT.ajax.reload(); return; }

    tahfidzDT = $('#tahfidzTable').DataTable({
        processing:true, serverSide:true, pageLength:15,
        ajax: { url:'{{ route("mutabaah-tahfidz.tahfidz_data") }}', data: function(d) { d.class_group_id = $('#filter_class').val(); }},
        columns: [
            {data:'tanggal'},{data:'nama_siswa'},{data:'surah_name'},{data:'verse_range',defaultContent:'-'},
            {data:'juz',defaultContent:'-'},{data:'type_badge'},{data:'grade_badge'},
            {data:'tajwid_score'},{data:'guru'},{data:'aksi',orderable:false,searchable:false}
        ]
    });
}

function submitTahfidz() {
    let form = $('#formTahfidz');
    Swal.fire({title:'Simpan Setoran?',icon:'question',showCancelButton:true,confirmButtonColor:'#7c3aed',confirmButtonText:'Simpan'}).then(r => {
        if (r.isConfirmed) {
            $.post('{{ route("mutabaah-tahfidz.store_tahfidz") }}', form.serialize())
                .done(res => {
                    Swal.fire({icon:'success',title:'Berhasil',text:res.message,timer:1500,showConfirmButton:false});
                    if (tahfidzDT) tahfidzDT.ajax.reload();
                    form[0].reset(); $('#tahfidz_date').val($('#filter_date').val());
                })
                .fail(err => { Swal.fire({icon:'error',title:'Gagal',text:err.responseJSON?.message||'Error'}); });
        }
    });
}

function deleteTahfidz(id) {
    Swal.fire({title:'Hapus?',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',confirmButtonText:'Hapus'}).then(r => {
        if (r.isConfirmed) {
            $.ajax({url:`{{ url('academic/mutabaah-tahfidz') }}/${id}/tahfidz`,method:'DELETE',data:{_token:'{{ csrf_token() }}'},
                success:res => { Swal.fire({icon:'success',title:'Dihapus',timer:1200,showConfirmButton:false}); if(tahfidzDT)tahfidzDT.ajax.reload(); },
                error:() => Swal.fire({icon:'error',title:'Gagal'})
            });
        }
    });
}

$(function() { $('#tahfidzFormCard').hide(); });
</script>
@endpush
