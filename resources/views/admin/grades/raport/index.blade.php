@extends($layout)

@section('title', 'Input Nilai Raport')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-ocean overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-book mr-2 animate__animated animate__fadeInLeft"></i> 
                            Pusat Pengolahan Nilai Rapor
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola matriks nilai raport siswa selama 6 semester (Kelas 4-6 MI, 7-9 MTs, atau 10-12 MA/SMA) secara komprehensif.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-tasks fa-8x opacity-2 shadow-icon"></i>
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
                    <div class="avatar-sm bg-soft-ocean rounded-circle d-flex align-items-center justify-content-center text-ocean mr-3" style="width:40px;height:40px;">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Parameter Nilai</h5>
                </div>
                
                @if($selectedLevel && $selectedClassId)
                <div class="mt-2 mt-md-0 d-flex flex-wrap" style="gap: 10px;">
                    <a href="{{ route('student-grades.export_raport', ['class_id' => $selectedClassId, 'level' => $selectedLevel]) }}" class="btn btn-success rounded-pill font-weight-bold shadow-sm px-4">
                        <i class="fas fa-file-excel mr-1"></i> UNDUH TEMPLATE
                    </a>
                    <button type="button" class="btn btn-info rounded-pill font-weight-bold shadow-info-light px-4" onclick="$('#modal-import').modal('show')">
                        <i class="fas fa-upload mr-1"></i> IMPORT EXCEL
                    </button>
                </div>
                @endif
            </div>
            
            <div class="card-body p-4 bg-light-soft">
                <form action="{{ route('student-grades.raport') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Jenjang Pendidikan</label>
                            <div class="input-group-premium bg-white border-ocean-light">
                                <i class="fas fa-layer-group text-ocean"></i>
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
                            <div class="input-group-premium bg-white border-ocean-light">
                                <i class="fas fa-school text-ocean"></i>
                                <select name="class_id" id="filter-class" class="form-control select2 border-0" onchange="onClassChange()">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classGroups as $class)
                                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->kelas_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Mata Pelajaran Diinput</label>
                            <div class="input-group-premium bg-white border-ocean-light">
                                <i class="fas fa-book-reader text-ocean"></i>
                                <select name="subject_id" id="subject_id" class="form-control select2 border-0" onchange="loadGrades()">
                                    <option value="">-- Mode Tinjauan (Review) --</option>
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
                <h4 class="mb-1 font-weight-bold text-dark">Matriks Nilai Raport Siswa</h4>
                <p class="text-muted text-sm mb-0">Klik pada kolom untuk mengisi, lalu tekan Enter atau klik Simpan untuk menyimpan nilai per baris.</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="grade-table">
                        <thead class="bg-light-ocean text-uppercase" style="border-bottom: 2px solid #0ea5e9;">
                            <tr>
                                <th rowspan="2" class="align-middle text-center border-right-ocean" width="50px">NO</th>
                                <th rowspan="2" class="align-middle border-right-ocean" width="220px">IDENTITAS SISWA</th>
                                @php
                                    $gradeColClass = request('subject_id') ? '' : 'd-none';
                                @endphp
                                @if($selectedLevel == 'MI')
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 4</th>
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 5</th>
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 6</th>
                                @elseif($selectedLevel == 'MTs')
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 7</th>
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 8</th>
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 9</th>
                                @else
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 10</th>
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 11</th>
                                    <th colspan="2" class="text-center border-right-ocean grade-col {{ $gradeColClass }}">KELAS 12</th>
                                @endif
                                <th rowspan="2" class="align-middle text-center" width="120px">TINDAKAN</th>
                            </tr>
                            <tr class="bg-white">
                                <th class="text-center font-weight-bold text-dark border-right grade-col {{ $gradeColClass }}">S1</th>
                                <th class="text-center font-weight-bold text-dark border-right-ocean grade-col {{ $gradeColClass }}">S2</th>
                                <th class="text-center font-weight-bold text-dark border-right grade-col {{ $gradeColClass }}">S1</th>
                                <th class="text-center font-weight-bold text-dark border-right-ocean grade-col {{ $gradeColClass }}">S2</th>
                                <th class="text-center font-weight-bold text-dark border-right grade-col {{ $gradeColClass }}">S1</th>
                                <th class="text-center font-weight-bold text-dark border-right-ocean grade-col {{ $gradeColClass }}">S2</th>
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
        <div class="alert alert-soft-ocean border-0 rounded-15 shadow-sm p-4 text-center">
            <i class="fas fa-filter text-ocean fa-3x mb-3 d-block"></i>
            <h5 class="font-weight-bold text-ocean-dark">Membutuhkan Parameter Filter</h5>
            <p class="text-muted mb-0">Silahkan pilih Jenjang dan Kelas (Rombel) terlebih dahulu pada panel kontrol di atas untuk melihat matriks nilai.</p>
        </div>
        @endif
    </div>
</div>

<!-- PREMIUM IMPORT MODAL -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-import" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ route('student-grades.import_raport') }}" method="POST" enctype="multipart/form-data" onsubmit="submitImport(event)">
            @csrf
            <input type="hidden" name="level" value="{{ $selectedLevel }}">
            <div class="modal-content border-0 shadow-lg-premium rounded-20">
                <div class="modal-header bg-gradient-ocean text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-file-import mr-2"></i> Import Nilai Raport
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Dokumen Excel (.xlsx)</label>
                        <div class="custom-file-premium border-ocean">
                            <input type="file" name="file" id="file" accept=".xlsx, .xls" required>
                            <label for="file"><i class="fas fa-cloud-upload-alt text-xl d-block mb-2 mt-1"></i> Klik untuk Memilih File Excel Raport</label>
                        </div>
                    </div>
                    <div class="alert alert-warning border-0 rounded-15 shadow-xs mb-0 d-flex align-items-center">
                        <i class="fas fa-exclamation-circle text-warning text-xl mr-3"></i>
                        <small class="font-weight-bold">Dilarang keras mengubah Struktur Kolom, ID Siswa, maupun ID Mapel pada file template yang didownload.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-ocean rounded-pill px-5 font-weight-bold shadow-ocean-light text-white" id="btnImport">
                        <i class="fas fa-bolt mr-2"></i> PROSES IMPORT
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Ocean Design System */
    .bg-gradient-ocean { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important; }
    .bg-light-ocean { background: #f0f9ff; color: #0284c7; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-ocean { background: #0284c7; color: #fff; border: none; }
    .btn-ocean:hover { background: #0369a1; color: #fff; }
    .text-ocean { color: #0284c7; }
    .text-ocean-dark { color: #075985; }
    .bg-soft-ocean { background: #e0f2fe; }
    .alert-soft-ocean { background: #f0f9ff; border: 1px solid #e0f2fe; }
    .shadow-ocean-light { box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3); }

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
    .input-group-premium select { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%; font-weight: 600;
    }
    .border-ocean-light:focus-within { border-color: #0284c7 !important; box-shadow: 0 0 10px rgba(2, 132, 199, 0.1); }

    /* Select2 Tweaks inside input group */
    .select2-container--default .select2-selection--single { border: none !important; background: transparent !important; height: auto !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 0; font-weight: 600; color: #334155; line-height: normal; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }

    /* Dynamic Matrix Table Styling */
    .border-right-ocean { border-right: 2px solid #bae6fd !important; }
    
    #grade-table { border-collapse: separate; border-spacing: 0; }
    #grade-table tbody tr { background: #fff; transition: all 0.1s ease; border-bottom: 1px solid #f1f5f9; }
    #grade-table tbody tr:hover { background: #f0f9ff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transform: scale(1.001); z-index: 10; position: relative;}
    #grade-table td { padding: 0.75rem 0.5rem; vertical-align: middle; border-bottom: 1px solid #e2e8f0; }
    #grade-table td:first-child { font-weight: bold; color: #0284c7; }

    /* Matrix Score Input Styling */
    .input-score {
        border: 1px solid transparent !important; border-radius: 6px; 
        font-weight: 700; font-size: 1rem; color: #1e293b;
        transition: all 0.2s ease; height: 35px; background: transparent;
    }
    .input-score:hover { border: 1px solid #bae6fd !important; background: #fff; }
    .input-score:focus { border: 2px solid #0284c7 !important; box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.2) !important; background: #fff; transform: scale(1.1); z-index: 100; position: relative;}

    /* Dropdown Premium Styling */
    .dropdown-menu-premium { border: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 12px; padding: 10px; }
    .dropdown-menu-premium .dropdown-item { border-radius: 8px; padding: 8px 15px; font-weight: 600; font-size: 0.85rem; color: #334155; transition: all 0.2s; }
    .dropdown-menu-premium .dropdown-item:hover { background: #f0f9ff; color: #0284c7; }
    .dropdown-header-premium { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; padding: 5px 15px; font-size: 0.7rem; }

    /* Custom File Input */
    .custom-file-premium { position: relative; width: 100%; }
    .custom-file-premium input { display: none; }
    .custom-file-premium label { 
        display: block; background: #fff; border: 2px dashed #0284c7; color: #0284c7; 
        text-align: center; padding: 20px; border-radius: 12px; cursor: pointer;
        font-weight: bold; transition: all 0.3s ease; margin: 0;
    }
    .custom-file-premium label:hover { background: #f0f9ff; }
</style>
@endsection

@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        // Initialize simple select2
        $('.select2-no-search').select2({ minimumResultsForSearch: -1, width: '100%' });
        
        const classId = $('select[name=class_id]').val();
        const level = $('select[name=level]').val();
        if(classId && level) { loadGrades(); }

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
            html: 'Menyiapkan matriks semester untuk ' + level,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.get('{{ route("student-grades.raport") }}', { level: level })
            .done(response => {
                let classHtml = '<option value="">-- Pilih Kelas --</option>';
                response.classGroups.forEach(c => { classHtml += `<option value="${c.id}">${c.class_group} ${c.sub_class_group || ''}</option>`; });
                $('#filter-class').html(classHtml).trigger('change.select2');

                let subjectHtml = '<option value="">-- Mode Tinjauan (Review) --</option>';
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
            Swal.fire({ title: 'Memuat Matriks...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            window.location.href = `{{ route('student-grades.raport') }}?level=${level}&class_id=${classId}`;
        }
    }

    function loadGrades() {
        const classId = $('select[name=class_id]').val();
        const level = $('select[name=level]').val();
        const subjectId = $('#subject_id').val();

        if (!classId || !level) return;

        $('#grade-table tbody').html('<tr><td colspan="10" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-ocean mb-3"></i><br>Menganalisis matriks nilai raport...</td></tr>');

        $.get('{{ route("student-grades.raport_data") }}', { class_id: classId, level: level, subject_id: subjectId })
        .done(response => {
            if (subjectId) { $('.grade-col').removeClass('d-none'); } else { $('.grade-col').addClass('d-none'); }

            let html = '';
            response.data.forEach((row, index) => {
                html += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="border-right-ocean">
                        <div class="font-weight-bold text-dark">${row.nama_lengkap}</div>
                        <small class="text-muted">NIS: ${row.nis || '-'}</small>
                    </td>`;
                
                const classLevels = level === 'MI' ? [4, 5, 6] : (level === 'MTs' ? [7, 8, 9] : [10, 11, 12]);
                const gradeColClass = subjectId ? '' : 'd-none';

                classLevels.forEach(cl => {
                    [1, 2].forEach((sem, i) => {
                        const key = `c${cl}s${sem}`;
                        const val = row.grades[key] || '';
                        let borderClass = i === 1 ? 'border-right-ocean' : 'border-right'; // s2 gets the bold ocean border
                        html += `<td class="grade-col ${borderClass} ${gradeColClass}"><input type="number" step="0.01" class="form-control text-center input-score" data-student="${row.id}" data-key="${key}" value="${val}" onchange="saveGrade(this, ${row.id})" onkeyup="if(event.keyCode===13) saveGrade(this, ${row.id})" placeholder="-"></td>`;
                    });
                });

                // Generate Dynamic Dropdown Link for SKNR
                let targetPublic = (level == 'MI') ? 'smp' : 'sma';
                let labelPublic = (level == 'MI') ? 'SMP/SMA Negeri' : 'SMA/SMK Negeri';
                let targetReligious = (level == 'MI') ? 'mts' : 'ma';
                let labelReligious = (level == 'MI') ? 'MTs Negeri' : 'MA Negeri';
                let linkPublic = '{{ route("student-grades.certificate", [":id", "TARGET"]) }}'.replace(':id', row.id).replace('TARGET', targetPublic);
                let linkReligious = '{{ route("student-grades.certificate", [":id", "TARGET"]) }}'.replace(':id', row.id).replace('TARGET', targetReligious);

                html += `<td class="text-center">
                    <div class="d-flex justify-content-center" style="gap:5px;">
                        ${subjectId ? `
                        <button type="button" class="btn btn-sm btn-ocean rounded-pill shadow-xs btn-save-row" onclick="saveGrade(this, ${row.id})" title="Simpan Baris Ini">
                            <i class="fas fa-check"></i>
                        </button>
                        ` : ''}
                        
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill shadow-xs text-primary dropdown-toggle" data-toggle="dropdown" title="Cetak Surat Keterangan Nilai Raport">
                                <i class="fas fa-print"></i> SKNR
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-premium">
                                <h6 class="dropdown-header-premium">Tujuan Penerbitan SKNR</h6>
                                <a class="dropdown-item" href="${linkPublic}" target="_blank">
                                    <i class="fas fa-school text-info mr-2"></i> Pendaftaran ${labelPublic}
                                </a>
                                <a class="dropdown-item" href="${linkReligious}" target="_blank">
                                    <i class="fas fa-mosque text-success mr-2"></i> Pendaftaran ${labelReligious}
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
                </tr>`;
            });
            $('#grade-table tbody').html(html);
        });
    }

    function saveGrade(el, studentId) {
        const subjectId = $('#subject_id').val();
        const row = $(el).closest('tr');
        const inputs = row.find('.input-score');
        const btn = $(el).is('input') ? row.find('.btn-save-row') : el;
        let grades = {};
        
        inputs.each(function() { grades[$(this).data('key')] = $(this).val(); });

        if ($(el).is('input')) {
            $(el).css('background', '#fef08a').css('color', '#000');
        } else {
            $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        }

        $.post('{{ route("student-grades.save_raport") }}', {
            _token: '{{ csrf_token() }}', student_id: studentId, subject_id: subjectId, grades: grades
        }).done(response => {
            toastr.success('Nilai baris tersimpan');
            if ($(el).is('input')) {
                $(el).css('background', '#d1fae5').css('color', '#065f46');
                setTimeout(() => { $(el).css('background', '').css('color', ''); }, 1500);
            }
        }).fail(xhr => {
            if ($(el).is('input')) {
                $(el).css('background', '#fee2e2').css('color', '#991b1b');
            }
            Swal.fire('Gagal Menyimpan', xhr.responseJSON?.message || 'Terjadi kesalahan jaringan', 'error');
        }).always(() => {
            if (!$(el).is('input')) { $(btn).prop('disabled', false).html('<i class="fas fa-check"></i>'); }
        });
    }

    function submitImport(e) {
        e.preventDefault();
        let form = e.target;
        let formData = new FormData(form);
        let btn = $('#btnImport');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MEMPROSES EXCEL...');
        
        $.ajax({
            url: $(form).attr('action'), type: 'POST', data: formData, contentType: false, processData: false,
            success: function(response) {
                $('#modal-import').modal('hide');
                loadGrades();
                Swal.fire({ icon: 'success', title: 'Import Sukses', text: response.message, timer: 2500, showConfirmButton: false });
            },
            error: function(xhr) {
                Swal.fire('Gagal Import', xhr.responseJSON?.message || 'Periksa kembali struktur file Excel Anda.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-bolt mr-2"></i> PROSES IMPORT');
            }
        });
    }
</script>
@endpush
