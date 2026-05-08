@extends($layout)

@section('title', 'Input Nilai Ujian Madrasah')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-fuchsia overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-award mr-2 animate__animated animate__fadeInLeft"></i> 
                            Pusat Penilaian Ujian Madrasah
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Input nilai final ujian siswa secara presisi untuk keperluan penerbitan Surat Keterangan Lulus (SKL) dan Ijazah PDUM.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-file-signature fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <div class="col-12">
        <!-- PREMIUM CONTROL PANEL -->
        <div class="card shadow-sm border-0 premium-card mb-4 bg-white">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm bg-soft-fuchsia rounded-circle d-flex align-items-center justify-content-center text-fuchsia mr-3" style="width:40px;height:40px;">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Kelas & Mata Pelajaran</h5>
                </div>
                
                @if($selectedLevel && $selectedClassId)
                <div class="mt-2 mt-md-0 d-flex flex-wrap" style="gap: 10px;">
                    <a href="{{ route('student-grades.export_exam', ['class_id' => $selectedClassId, 'level' => $selectedLevel]) }}" class="btn btn-success rounded-pill font-weight-bold shadow-sm px-4">
                        <i class="fas fa-file-excel mr-1"></i> TEMPLATE NILAI
                    </a>
                    <button type="button" class="btn btn-info rounded-pill font-weight-bold shadow-info-light px-4" onclick="$('#modal-import').modal('show')">
                        <i class="fas fa-upload mr-1"></i> IMPORT EXCEL
                    </button>
                </div>
                @endif
            </div>
            <div class="card-body p-4 bg-light-soft">
                <form action="{{ route('student-grades.exam') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Jenjang Pendidikan</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-layer-group"></i>
                                <select name="level" id="filter-level" class="form-control select2-no-search border-0" onchange="onLevelChange()">
                                    <option value="">-- Pilih Jenjang --</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level }}" {{ $selectedLevel == $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Rombongan Belajar</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-school"></i>
                                <select name="class_id" id="filter-class" class="form-control select2 border-0" onchange="onClassChange()">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classGroups as $class)
                                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->kelas_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Mata Pelajaran Ujian</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-book-open"></i>
                                <select name="subject_id" id="subject_id" class="form-control select2 border-0" onchange="loadGrades()">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $gs)
                                        <option value="{{ $gs->subject_id }}" {{ request('subject_id') == $gs->subject_id ? 'selected' : '' }}>{{ $gs->subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MAIN DATA TABLE -->
        @if($selectedLevel && $selectedClassId)
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark">Formulir Input Nilai</h4>
                <p class="text-muted text-sm mb-0">Tekan "Enter" atau klik tombol "Simpan" pada setiap baris untuk memperbarui nilai</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="grade-table">
                        <thead class="bg-light-fuchsia text-uppercase">
                            <tr>
                                <th width="60px" class="text-center py-3 rounded-left-10">NO</th>
                                <th>NAMA LENGKAP SISWA</th>
                                <th width="200px" class="text-center grade-col {{ request('subject_id') ? '' : 'd-none' }}">NILAI FINAL</th>
                                <th width="200px" class="text-center rounded-right-10">AKSI TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-soft-fuchsia border-0 rounded-15 shadow-sm p-4 text-center">
            <i class="fas fa-hand-pointer text-fuchsia fa-3x mb-3 d-block"></i>
            <h5 class="font-weight-bold text-fuchsia-dark">Membutuhkan Parameter</h5>
            <p class="text-muted mb-0">Silahkan pilih Jenjang dan Kelas terlebih dahulu pada panel kontrol di atas untuk melihat data siswa.</p>
        </div>
        @endif
    </div>
</div>

<!-- PREMIUM IMPORT MODAL -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-import" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ route('student-grades.import_exam') }}" method="POST" enctype="multipart/form-data" onsubmit="submitImport(event)">
            @csrf
            <input type="hidden" name="level" value="{{ $selectedLevel }}">
            <div class="modal-content border-0 shadow-lg-premium rounded-20">
                <div class="modal-header bg-gradient-fuchsia text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-file-import mr-2"></i> Import Nilai Excel
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Dokumen Excel (.xlsx)</label>
                        <div class="custom-file-premium">
                            <input type="file" name="file" id="file" accept=".xlsx, .xls" required>
                            <label for="file"><i class="fas fa-cloud-upload-alt text-xl d-block mb-2 mt-1"></i> Klik untuk Memilih File Excel</label>
                        </div>
                    </div>
                    <div class="alert alert-warning border-0 rounded-15 shadow-xs mb-0 d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-warning text-xl mr-3"></i>
                        <small class="font-weight-bold">Pastikan format Excel sesuai template. Dilarang keras mengubah ID Siswa maupun ID Mapel pada kolom tersembunyi.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-fuchsia rounded-pill px-5 font-weight-bold shadow-fuchsia-light text-white" id="btnImport">
                        <i class="fas fa-bolt mr-2"></i> PROSES IMPORT
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-fuchsia { background: linear-gradient(135deg, #c026d3 0%, #a21caf 100%) !important; }
    .bg-light-fuchsia { background: #fdf4ff; color: #c026d3; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-fuchsia { background: #c026d3; color: #fff; border: none; }
    .btn-fuchsia:hover { background: #a21caf; color: #fff; }
    .text-fuchsia { color: #c026d3; }
    .text-fuchsia-dark { color: #86198f; }
    .bg-soft-fuchsia { background: #fae8ff; }
    .alert-soft-fuchsia { background: #fdf4ff; border: 1px solid #fae8ff; }
    .shadow-fuchsia-light { box-shadow: 0 4px 15px rgba(192, 38, 211, 0.3); }
    .shadow-info-light { box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    .bg-light-soft { background: #f8fafc; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 45px;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%; font-weight: 600;
    }
    .input-group-premium:focus-within { border-color: #c026d3; box-shadow: 0 0 10px rgba(192, 38, 211, 0.1); }
    .input-group-premium:focus-within i { color: #c026d3; }

    /* Select2 Tweaks inside input group */
    .select2-container--default .select2-selection--single { border: none !important; background: transparent !important; height: auto !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 0; font-weight: 600; color: #334155; line-height: normal; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }

    /* Dynamic Table Styling */
    #grade-table { border-collapse: separate; border-spacing: 0 8px; }
    #grade-table tbody tr { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    #grade-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #fafafa; }
    #grade-table td { border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 1rem 0.75rem; vertical-align: middle; }
    #grade-table td:first-child { border-left: 1px solid #f1f5f9; border-radius: 10px 0 0 10px; font-weight: bold; color: #c026d3; }
    #grade-table td:last-child { border-right: 1px solid #f1f5f9; border-radius: 0 10px 10px 0; }
    
    .rounded-left-10 { border-radius: 10px 0 0 10px; }
    .rounded-right-10 { border-radius: 0 10px 10px 0; }

    /* Score Input Styling */
    .input-score {
        border: 2px solid #e2e8f0 !important; border-radius: 10px; 
        font-weight: 800; font-size: 1.1rem; color: #1e293b;
        transition: all 0.3s ease; height: 45px; background: #f8fafc;
    }
    .input-score:focus { border-color: #c026d3 !important; box-shadow: 0 0 0 0.2rem rgba(192, 38, 211, 0.25) !important; background: #fff; }

    /* Custom File Input */
    .custom-file-premium { position: relative; width: 100%; }
    .custom-file-premium input { display: none; }
    .custom-file-premium label { 
        display: block; background: #fff; border: 2px dashed #c026d3; color: #c026d3; 
        text-align: center; padding: 20px; border-radius: 12px; cursor: pointer;
        font-weight: bold; transition: all 0.3s ease; margin: 0;
    }
    .custom-file-premium label:hover { background: #fdf4ff; }
</style>
@endsection

@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        // Initialize simple select2
        $('.select2-no-search').select2({ minimumResultsForSearch: -1, width: '100%' });
        
        if($('#filter-class').val()) {
            loadGrades();
        }

        // Custom File Upload display
        $('#file').change(function() {
            let fileName = $(this).val().split('\\').pop();
            if(fileName) {
                $(this).next('label').html(`<i class="fas fa-file-excel text-success text-xl d-block mb-2 mt-1"></i> ${fileName}`);
            }
        });
    });

    function onLevelChange() {
        const level = $('#filter-level').val();
        if (!level) return;

        $('#filter-class, #subject_id').prop('disabled', true);
        Swal.fire({
            title: 'Sinkronisasi...',
            html: 'Menyiapkan struktur kelas sesuai jenjang',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.get('{{ route("student-grades.exam") }}', { level: level })
            .done(response => {
                let classHtml = '<option value="">-- Pilih Rombel --</option>';
                response.classGroups.forEach(c => { classHtml += `<option value="${c.id}">${c.class_group} ${c.sub_class_group || ''}</option>`; });
                $('#filter-class').html(classHtml).trigger('change.select2');

                let subjectHtml = '<option value="">-- Pilih Mapel --</option>';
                response.subjects.forEach(s => { subjectHtml += `<option value="${s.subject_id}">${s.subject.name}</option>`; });
                $('#subject_id').html(subjectHtml).trigger('change.select2');

                Swal.close();
            })
            .always(() => { $('#filter-class, #subject_id').prop('disabled', false); });
    }

    function onClassChange() {
        const level = $('#filter-level').val();
        const classId = $('#filter-class').val();
        if (level && classId) {
            Swal.fire({ title: 'Memuat Lembar Penilaian...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            window.location.href = `{{ route('student-grades.exam') }}?level=${level}&class_id=${classId}`;
        }
    }

    function loadGrades() {
        const classId = $('select[name=class_id]').val();
        const level = $('select[name=level]').val();
        const subjectId = $('#subject_id').val();

        if (!classId || !level) return;

        $('#grade-table tbody').html('<tr><td colspan="4" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-fuchsia mb-3"></i><br>Menganalisis data siswa...</td></tr>');

        $.get('{{ route("student-grades.exam_data") }}', { class_id: classId, level: level, subject_id: subjectId })
        .done(response => {
            if (subjectId) { $('.grade-col').removeClass('d-none'); } else { $('.grade-col').addClass('d-none'); }
            
            let html = '';
            response.data.forEach((row, index) => {
                const gradeColClass = subjectId ? '' : 'd-none';
                html += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td><div class="font-weight-bold text-dark">${row.nama_lengkap}</div><small class="text-muted">NIS: ${row.nis || '-'}</small></td>
                    <td class="grade-col ${gradeColClass}">
                        <input type="number" min="0" max="100" step="1" class="form-control text-center input-score" value="${parseInt(row.score) || 0}" 
                        onchange="saveGrade(this, ${row.id})" onkeyup="if(event.keyCode===13) saveGrade(this, ${row.id})" placeholder="0">
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center" style="gap:5px;">
                            ${subjectId ? `
                            <button type="button" class="btn btn-sm btn-fuchsia rounded-pill shadow-xs btn-save-row" onclick="saveGrade(this, ${row.id})" title="Simpan">
                                <i class="fas fa-check"></i>
                            </button>
                            ` : ''}
                            <a href="${'{{ route("student-grades.print_skl", ":id") }}'.replace(':id', row.id)}" target="_blank" class="btn btn-sm btn-light rounded-pill border shadow-xs text-info" title="Cetak SKL">
                                <i class="fas fa-file-alt"></i> SKL
                            </a>
                            <a href="${'{{ route("student-grades.print_pdum", ":id") }}'.replace(':id', row.id)}" target="_blank" class="btn btn-sm btn-light rounded-pill border shadow-xs text-success" title="Cetak Ijazah PDUM">
                                <i class="fas fa-certificate"></i> PDUM
                            </a>
                        </div>
                    </td>
                </tr>`;
            });
            $('#grade-table tbody').html(html);
        });
    }

    function saveGrade(el, studentId) {
        const subjectId = $('#subject_id').val();
        const level = $('select[name=level]').val();
        const inputEl = $(el).is('input') ? $(el) : $(el).closest('tr').find('.input-score');
        let score = parseFloat(inputEl.val()) || 0;
        if (score > 100) {
            toastr.error('Nilai tidak boleh lebih dari 100. Sistem mereset ke 0.');
            inputEl.val(0);
            score = 0;
        }
        const btn = $(el).is('input') ? $(el).closest('tr').find('.btn-save-row') : el;

        inputEl.css('border-color', '#3498db').css('background', '#ebf5fb');
        $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.post('{{ route("student-grades.save_exam") }}', {
            _token: '{{ csrf_token() }}', student_id: studentId, subject_id: subjectId, level: level, score: score
        }).done(response => {
            toastr.success('Tersimpan otomatis');
            inputEl.css('border-color', '#10b981').css('background', '#d1fae5');
            setTimeout(() => { inputEl.css('border-color', '').css('background', ''); }, 1500);
        }).fail(xhr => {
            inputEl.css('border-color', '#ef4444').css('background', '#fee2e2');
            Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan sistem', 'error');
        }).always(() => {
            $(btn).prop('disabled', false).html('<i class="fas fa-check"></i>');
        });
    }

    function submitImport(e) {
        e.preventDefault();
        let form = e.target;
        let formData = new FormData(form);
        let btn = $('#btnImport');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MENGUNGGAH...');
        
        $.ajax({
            url: $(form).attr('action'), type: 'POST', data: formData, contentType: false, processData: false,
            success: function(response) {
                $('#modal-import').modal('hide');
                loadGrades();
                Swal.fire({ icon: 'success', title: 'Import Sukses', text: response.message, timer: 2000, showConfirmButton: false });
            },
            error: function(xhr) {
                Swal.fire('Gagal Import', xhr.responseJSON?.message || 'Pastikan file sesuai template.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-bolt mr-2"></i> PROSES IMPORT');
            }
        });
    }
</script>
@endpush
