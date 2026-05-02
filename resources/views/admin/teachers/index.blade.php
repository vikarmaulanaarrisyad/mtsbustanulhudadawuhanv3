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
                    <button onclick="addForm(`{{ route('teachers.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Tambah Guru/Staf
                    </button>
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

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary" id="submitBtn">Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
    </x-slot>
</x-modal>
@endsection

@include('includes.datatable')

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
</script>
@endpush
