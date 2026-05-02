@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Pengumuman</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <x-card>
            <x-slot name="header">
                <button onclick="addForm()" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Tambah Pengumuman</button>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="10%"><i class="fa fa-cog"></i></th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

@include('admin.announcement.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('announcements.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'title'},
                {data: 'type'},
                {data: 'is_active'},
                {data: 'created_at', render: function(data) {
                    return new Date(data).toLocaleDateString('id-ID');
                }},
                {data: 'action', searchable: false, sortable: false},
            ]
        });

        $(document).on('submit', '#modal-form form', function(e) {
            e.preventDefault();
            let id = $('#id').val();
            let url = id ? '{{ url('admin/announcements') }}/' + id : '{{ route('announcements.store') }}';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    let message = 'Tidak dapat menyimpan data';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                }
            });

            return false;
        });
    });

    function addForm() {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Pengumuman');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', '{{ route('announcements.store') }}');
        $('#modal-form [name=_method]').val('post');
        $('#id').val('');
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Pengumuman');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');

        $.get(url)
            .done(response => {
                const data = response.data;
                $('#id').val(data.id);
                $('#title').val(data.title);
                $('#type').val(data.type);
                $('#content').val(data.content);
                $('#is_active').val(data.is_active);
            })
            .fail(() => toastr.error('Tidak dapat menampilkan data'));
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data ini?')) {
            $.post(url, {
                '_token': '{{ csrf_token() }}',
                '_method': 'delete'
            })
            .done(response => {
                table.ajax.reload();
                toastr.success(response.message);
            })
            .fail(() => toastr.error('Tidak dapat menghapus data'));
        }
    }
</script>
@endpush
