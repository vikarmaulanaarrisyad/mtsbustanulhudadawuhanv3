@extends($layout)

@section('title', 'Surat Keterangan Diterima')
@section('subtitle', 'Persuratan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card>
                <x-slot name="header">
                    <button onclick="addForm()" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle mr-1"></i> Buat Surat Baru</button>
                </x-slot>

                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="acceptanceTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>No. Surat</th>
                                <th>Tgl Surat</th>
                                <th>Nama Siswa</th>
                                <th>Sekolah Asal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </x-card>
        </div>
    </div>

    @include('admin.mail.acceptances.form')
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#acceptanceTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('student-acceptances.data') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'acceptance_number'},
                    {data: 'acceptance_date'},
                    {data: 'student_name'},
                    {data: 'origin_school'},
                    {data: 'action', searchable: false, sortable: false},
                ]
            });

            $('#formAcceptance').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    let id = $('#id').val();
                    let url = id ? "{{ url('admin/mail/student-acceptances') }}/" + id : "{{ route('student-acceptances.store') }}";
                    let method = id ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#modalForm').modal('hide');
                            table.ajax.reload();
                            Swal.fire({icon: 'success', title: 'Berhasil', text: response.message});
                        },
                        error: function(xhr) {
                            Swal.fire({icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan'});
                        }
                    });
                    return false;
                }
            });
        });

        function addForm() {
            $('#modalForm').modal('show');
            $('.modal-title').text('Tambah Surat Diterima');
            $('#formAcceptance')[0].reset();
            $('#id').val('');
            $('#student_id').val('').trigger('change');
            $('#acceptance_number').val('');
        }

        function editForm(url) {
            $('#modalForm').modal('show');
            $('.modal-title').text('Edit Surat Diterima');
            $('#formAcceptance')[0].reset();

            $.get(url).done(response => {
                let data = response.data;
                $('#id').val(data.id);
                $('#student_id').val(data.student_id).trigger('change');
                $('#acceptance_number').val(data.acceptance_number);
                $('#acceptance_date').val(data.acceptance_date);
                $('#origin_school').val(data.origin_school);
                $('#origin_class').val(data.origin_class);
                $('#signer_name').val(data.signer_name);
                $('#signer_position').val(data.signer_position);
                $('#signer_nip').val(data.signer_nip);
            });
        }

        function deleteData(url, number) {
            Swal.fire({
                title: 'Hapus Surat?',
                text: 'Apakah Anda yakin ingin menghapus surat nomor ' + number + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'DELETE'
                    }).done(response => {
                        table.ajax.reload();
                        Swal.fire({icon: 'success', title: 'Dihapus', text: response.message});
                    });
                }
            });
        }
    </script>
@endpush
