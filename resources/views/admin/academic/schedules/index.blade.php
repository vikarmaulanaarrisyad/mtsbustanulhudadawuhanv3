@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-12">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Jadwal</h3>
            </x-slot>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tahun Pelajaran</label>
                        <select id="filter_academic_year" class="form-control select2">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kelas</label>
                        <select id="filter_class" class="form-control select2">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button onclick="refreshTable()" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> Filter Data</button>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Daftar Jadwal Mingguan</h3>
                    <div>
                        <button onclick="importForm()" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel mr-1"></i> Import Jadwal
                        </button>
                        <button onclick="addForm(`{{ route('class-schedules.store') }}`)" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Jadwal
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>HARI</th>
                    <th>WAKTU</th>
                    <th>MATA PELAJARAN</th>
                    <th>GURU PENGAJAR</th>
                    <th>KELAS</th>
                    <th width="10%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

<x-modal>
    <x-slot name="title">Form Jadwal Pelajaran</x-slot>

    @method('POST')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Tahun Pelajaran <span class="text-danger">*</span></label>
                <select name="academic_year_id" class="form-control select2" required>
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Kelas <span class="text-danger">*</span></label>
                <select name="class_group_id" class="form-control select2" required>
                    @foreach($classGroups as $cg)
                        <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Mata Pelajaran <span class="text-danger">*</span></label>
        <select name="subject_id" class="form-control select2" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Guru Pengajar <span class="text-danger">*</span></label>
        <select name="teacher_id" class="form-control select2" required>
            <option value="">-- Pilih Guru --</option>
            @foreach($teachers as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Hari <span class="text-danger">*</span></label>
                <select name="day" class="form-control" required>
                    <option value="1">Senin</option>
                    <option value="2">Selasa</option>
                    <option value="3">Rabu</option>
                    <option value="4">Kamis</option>
                    <option value="5">Jumat</option>
                    <option value="6">Sabtu</option>
                    <option value="7">Minggu</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Jam Pelajaran <span class="text-danger">*</span></label>
                <select name="study_period_id" class="form-control select2" required>
                    <option value="">-- Pilih Jam Pelajaran --</option>
                    @foreach($studyPeriods as $sp)
                        <option value="{{ $sp->id }}">Jam ke-{{ $sp->period_number }} ({{ $sp->start_time }} - {{ $sp->end_time }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary" id="submitBtn">Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
    </x-slot>
</x-modal>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('class-schedules.import_excel') }}" method="POST" enctype="multipart/form-data" onsubmit="submitImport(event)">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Jadwal Pelajaran</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel (.xlsx)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="alert alert-info">
                        <small>Gunakan header Excel: <b>mata_pelajaran, nama_guru, kelas, tahun_pelajaran, hari, jam_mulai, jam_selesai</b></small>
                        <br>
                        <a href="{{ route('class-schedules.download_template') }}" class="badge badge-light mt-2"><i class="fas fa-download"></i> Download Template Excel</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnImport">Mulai Import</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('.table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: { 
                url: '{{ route("class-schedules.data") }}',
                data: function(d) {
                    d.class_group_id = $('#filter_class').val();
                    d.academic_year_id = $('#filter_academic_year').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'day_name' },
                { data: 'time' },
                { data: 'subject_name' },
                { data: 'teacher_name' },
                { data: 'class_group.class_group' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Jadwal Pelajaran');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        $('.select2').trigger('change');
    }

    function importForm() {
        $('#modal-import').modal('show');
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
                table.ajax.reload();
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

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Jadwal Pelajaran');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
            $('.select2').trigger('change');
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            })
            .fail(xhr => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            })
            .always(() => $('#submitBtn').prop('disabled', false));
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: 'Apakah Anda yakin ingin menghapus jadwal ' + name + '?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(response => {
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message });
                });
            }
        });
    }
</script>
@endpush
