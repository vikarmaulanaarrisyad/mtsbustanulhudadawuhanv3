@extends('layouts.app')

@section('title', 'Hari Libur Nasional')
@section('subtitle', 'Persuratan')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-calendar-times mr-1"></i> Daftar Hari Libur</h3>
                    <button onclick="addForm(`{{ route('holidays.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Tambah Hari Libur
                    </button>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>TANGGAL</th>
                    <th>KETERANGAN / NAMA LIBUR</th>
                    <th width="10%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

<x-modal>
    <x-slot name="title">Form Hari Libur</x-slot>

    @method('POST')
    <div class="form-group">
        <label>Tanggal Libur <span class="text-danger">*</span></label>
        <input type="date" name="holiday_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Nama Libur / Keterangan <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: Idul Fitri 1447 H" required>
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
            ajax: { url: '{{ route("holidays.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'holiday_date' },
                { data: 'name' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Hari Libur');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Hari Libur');
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
            text: 'Apakah Anda yakin ingin menghapus hari libur ' + name + '?',
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
