@extends('layouts.app')

@section('title', 'Data Guru & Staf')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-users mr-1"></i> Daftar Guru & Staf</h3>
                    <div class="btn-group">
                        <button onclick="importForm()" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                        <button onclick="addForm(`{{ route('teachers.store') }}`)" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle"></i> Tambah Guru/Staf
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>NAMA LENGKAP</th>
                    <th>NIP</th>
                    <th>JABATAN</th>
                    <th>PANGKAT/GOL</th>
                    <th width="10%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

<x-modal>
    <x-slot name="title">Form Guru/Staf</x-slot>

    @method('POST')
    <div class="form-group">
        <label>Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control" placeholder="Kosongkan jika tidak ada">
    </div>
    <div class="form-group">
        <label>Jabatan</label>
        <input type="text" name="position" class="form-control" placeholder="Contoh: Guru Madya / Bendahara">
    </div>
    <div class="form-group">
        <label>Pangkat / Golongan</label>
        <input type="text" name="rank" class="form-control" placeholder="Contoh: Penata / III.c">
    </div>
    <div class="form-group">
        <label>Hubungkan Akun Login</label>
        <select name="user_id" class="form-control select2">
            <option value="">-- Tanpa Akun / Belum Ada --</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
        <small class="text-muted">Pilih akun user agar guru ini bisa melakukan presensi mandiri.</small>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary" id="submitBtn">Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
    </x-slot>
</x-modal>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('teachers.import_excel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Guru & Staf</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required accept=".xlsx, .xls, .csv">
                        <small class="text-muted">Format file: .xlsx, .xls, .csv</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> Belum punya template?
                        <a href="{{ route('teachers.download_template') }}" class="font-weight-bold">Download Template Disini</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="submitImport(this.form)" class="btn btn-primary" id="importSubmitBtn">Import</button>
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
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
            ajax: { url: '{{ route("teachers.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'name' },
                { data: 'nip' },
                { data: 'position' },
                { data: 'rank' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Guru & Staf');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Guru & Staf');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
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
            title: 'Hapus Data?',
            text: 'Apakah Anda yakin ingin menghapus data ' + name + '?',
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

    function importForm() {
        $('#modal-import').modal('show');
        $('#modal-import form')[0].reset();
    }

    function submitImport(form) {
        let formData = new FormData(form);
        $('#importSubmitBtn').prop('disabled', true).text('Sedang mengimport...');

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#modal-import').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan saat import' });
            },
            complete: function() {
                $('#importSubmitBtn').prop('disabled', false).text('Import');
            }
        });
    }
</script>
@endpush
