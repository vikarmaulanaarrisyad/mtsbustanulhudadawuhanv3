@extends($layout)

@section('title', 'Jam Pelajaran')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-clock mr-1"></i> Pengaturan Jam Pelajaran</h3>
                    <button onclick="addForm(`{{ route('study-periods.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Tambah Jam
                    </button>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>JAM KE-</th>
                    <th>JAM MULAI</th>
                    <th>JAM SELESAI</th>
                    <th>TIPE</th>
                    <th width="10%"><i class="fas fa-cog"></i></th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

<x-modal id="modal-form" size="modal-md">
    @csrf
    <input type="hidden" name="_method" value="POST">
    <div class="form-group">
        <label>Jam Ke-</label>
        <input type="number" name="period_number" class="form-control" required>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Jam Mulai</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Jam Selesai</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Status</label>
        <select name="is_break" class="form-control">
            <option value="0">Jam Pelajaran</option>
            <option value="1">Istirahat</option>
        </select>
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
            ajax: { url: '{{ route("study-periods.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'period_number' },
                { data: 'start_time' },
                { data: 'end_time' },
                { data: 'is_break_label' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Jam Pelajaran');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Jam Pelajaran');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            
            loopForm(response.data);
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true).text('Menyimpan...');
        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                $('#modal-form').modal('hide');
                table.ajax.reload();
                toastr.success(response.message);
            })
            .fail(xhr => {
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    loopErrors(errors);
                    for (let key in errors) toastr.error(errors[key][0]);
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
                }
            })
            .always(() => $('#submitBtn').prop('disabled', false).text('Simpan'));
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus data ' + name + '?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, { '_method': 'DELETE', '_token': '{{ csrf_token() }}' })
                    .done(response => {
                        table.ajax.reload();
                        toastr.success(response.message);
                    })
                    .fail(xhr => toastr.error(xhr.responseJSON?.message || 'Gagal menghapus data.'));
            }
        });
    }
</script>
@endpush
