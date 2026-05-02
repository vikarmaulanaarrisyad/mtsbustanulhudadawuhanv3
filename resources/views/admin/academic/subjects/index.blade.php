@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-book mr-1"></i> Daftar Mata Pelajaran</h3>
                    <div>
                        <button onclick="importForm()" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Import Mapel
                        </button>
                        <button onclick="addForm(`{{ route('subjects.store') }}`)" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle"></i> Tambah Mapel
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>KODE MAPEL</th>
                    <th>NAMA MATA PELAJARAN</th>
                    <th>KATEGORI</th>
                    <th width="10%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

<x-modal>
    <x-slot name="title">Form Mata Pelajaran</x-slot>

    @method('POST')
    <div class="form-group">
        <label>Nama Mata Pelajaran <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: Matematika" required>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Kode Mapel</label>
                <input type="text" name="code" class="form-control" placeholder="Contoh: MTK01">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Kategori</label>
                <select name="category" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Kelompok A (Wajib)">Kelompok A (Wajib)</option>
                    <option value="Kelompok B (Wajib)">Kelompok B (Wajib)</option>
                    <option value="Keagamaan">Keagamaan</option>
                    <option value="Muatan Lokal">Muatan Lokal</option>
                    <option value="Lainnya">Lainnya</option>
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
        <form action="{{ route('subjects.import_excel') }}" method="POST" enctype="multipart/form-data" onsubmit="submitImport(event)">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Mata Pelajaran</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel (.xlsx)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="alert alert-info">
                        <small>Gunakan header berikut pada Excel: <b>nama_mapel, kode_mapel</b></small>
                        <br>
                        <a href="{{ route('subjects.download_template') }}" class="badge badge-light mt-2"><i class="fas fa-download"></i> Download Template Excel</a>
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

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('.table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: { url: '{{ route("subjects.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'code' },
                { data: 'name' },
                { data: 'category_badge' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Mata Pelajaran');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
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
            $('#modal-form .modal-title').text('Edit Mata Pelajaran');
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
            text: 'Apakah Anda yakin ingin menghapus mapel ' + name + '?',
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
