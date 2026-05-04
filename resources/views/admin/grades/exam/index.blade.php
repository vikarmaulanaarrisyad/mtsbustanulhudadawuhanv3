@extends($layout)

@section('title', 'Input Nilai Ujian Madrasah')
@section('subtitle', 'Pengolahan Nilai')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-file-signature mr-1"></i> Input Nilai Ujian Madrasah (Final)</h3>
                    @if($selectedLevel && $selectedClassId)
                    <div>
                        <a href="{{ route('student-grades.export_exam', ['class_id' => $selectedClassId, 'level' => $selectedLevel]) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                        <button type="button" class="btn btn-sm btn-info" onclick="$('#modal-import').modal('show')">
                            <i class="fas fa-upload"></i> Import Excel
                        </button>
                    </div>
                    @endif
                </div>
            </x-slot>

            <form action="{{ route('student-grades.exam') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jenjang</label>
                            <select name="level" id="filter-level" class="form-control" onchange="onLevelChange()">
                                <option value="">-- Pilih Jenjang --</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ $selectedLevel == $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kelas (Rombel)</label>
                            <select name="class_id" id="filter-class" class="form-control" onchange="onClassChange()">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classGroups as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->kelas_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <select name="subject_id" id="subject_id" class="form-control select2" onchange="loadGrades()">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $gs)
                                    <option value="{{ $gs->subject_id }}" {{ request('subject_id') == $gs->subject_id ? 'selected' : '' }}>{{ $gs->subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            @if($selectedLevel && $selectedClassId)
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="grade-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">NO</th>
                            <th>NAMA SISWA</th>
                            <th class="text-center" width="20%">NILAI UJIAN MADRASAH</th>
                            <th class="text-center" width="10%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loaded via AJAX -->
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                Silahkan pilih Jenjang, Kelas, dan Mata Pelajaran terlebih dahulu.
            </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modal-import" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('student-grades.import_exam') }}" method="POST" enctype="multipart/form-data" onsubmit="submitImport(event)">
            @csrf
            <input type="hidden" name="level" value="{{ $selectedLevel }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Nilai Ujian Madrasah</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel (.xlsx)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="alert alert-warning">
                        <small>Pastikan format Excel sesuai dengan template yang didownload. Jangan mengubah ID Siswa dan ID Mapel.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnImport">Mulai Import</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('includes.select2')

@push('scripts')
<script>
    function onLevelChange() {
        const level = $('#filter-level').val();
        if (!level) return;

        // Disable filters and show loading
        $('#filter-class, #subject_id').prop('disabled', true);
        Swal.fire({
            title: 'Memproses Jenjang...',
            html: 'Menyiapkan daftar kelas dan mata pelajaran',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.get('{{ route("student-grades.exam") }}', { level: level })
            .done(response => {
                // Update Classes
                let classHtml = '<option value="">-- Pilih Kelas --</option>';
                response.classGroups.forEach(c => {
                    classHtml += `<option value="${c.id}">${c.class_group} ${c.sub_class_group || ''}</option>`;
                });
                $('#filter-class').html(classHtml);

                // Update Subjects
                let subjectHtml = '<option value="">-- Pilih Mata Pelajaran --</option>';
                response.subjects.forEach(s => {
                    subjectHtml += `<option value="${s.subject_id}">${s.subject.name}</option>`;
                });
                $('#subject_id').html(subjectHtml).trigger('change');

                Swal.close();
            })
            .always(() => {
                $('#filter-class, #subject_id').prop('disabled', false);
            });
    }

    function onClassChange() {
        const level = $('#filter-level').val();
        const classId = $('#filter-class').val();
        if (level && classId) {
            Swal.fire({
                title: 'Memuat Rombel...',
                html: 'Menyiapkan struktur penilaian',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });
            window.location.href = `{{ route('student-grades.exam') }}?level=${level}&class_id=${classId}`;
        }
    }

    function loadGrades() {
        const classId = $('select[name=class_id]').val();
        const level = $('select[name=level]').val();
        const subjectId = $('#subject_id').val();

        if (!classId || !level || !subjectId) return;

        Swal.fire({ title: 'Memuat Data...', didOpen: () => Swal.showLoading() });

        $.get('{{ route("student-grades.exam_data") }}', {
            class_id: classId,
            level: level,
            subject_id: subjectId
        }).done(response => {
            Swal.close();
            let html = '';
            response.data.forEach((row, index) => {
                html += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${row.nama_lengkap}</td>
                    <td><input type="number" step="0.01" class="form-control text-center input-score" value="${row.score || 0}" onchange="saveGrade(this, ${row.id})"></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-primary" onclick="saveGrade(this, ${row.id})" title="Simpan Nilai">
                                <i class="fas fa-save"></i>
                            </button>
                            <a href="${'{{ route("student-grades.print_skl", ":id") }}'.replace(':id', row.id)}" target="_blank" class="btn btn-xs btn-info" title="Cetak Daftar Nilai SKL">
                                <i class="fas fa-file-alt"></i>
                            </a>
                            <a href="${'{{ route("student-grades.print_pdum", ":id") }}'.replace(':id', row.id)}" target="_blank" class="btn btn-xs btn-success" title="Cetak Nilai Ijazah PDUM">
                                <i class="fas fa-certificate"></i>
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
        const score = $(el).is('input') ? $(el).val() : $(el).closest('tr').find('.input-score').val();
        const btn = $(el).is('input') ? $(el).closest('tr').find('.btn-primary') : el;

        if ($(el).is('input')) {
            $(el).css('border-color', '#3498db');
        } else {
            $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        }

        $.post('{{ route("student-grades.save_exam") }}', {
            _token: '{{ csrf_token() }}',
            student_id: studentId,
            subject_id: subjectId,
            level: level,
            score: score
        }).done(response => {
            toastr.success(response.message);
            if ($(el).is('input')) {
                $(el).css('border-color', '#2ecc71');
                setTimeout(() => { $(el).css('border-color', ''); }, 2000);
            }
        }).fail(xhr => {
            if ($(el).is('input')) {
                $(el).css('border-color', '#e74c3c');
            } else {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
            }
        }).always(() => {
            if (!$(el).is('input')) {
                $(btn).prop('disabled', false).html('<i class="fas fa-save"></i>');
            }
        });
    }

    function submitImport(e) {
        e.preventDefault();
        let form = e.target;
        let formData = new FormData(form);
        $('#btnImport').prop('disabled', true).text('Sedang Import...');
        
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#modal-import').modal('hide');
                loadGrades();
                Swal.fire('Berhasil', response.message, 'success');
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
            },
            complete: function() {
                $('#btnImport').prop('disabled', false).text('Mulai Import');
            }
        });
    }

    $(function() {
        if($('#subject_id').val()) {
            loadGrades();
        }
    });
</script>
@endpush
