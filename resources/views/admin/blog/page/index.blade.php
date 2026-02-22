@extends('layouts.app')

@section('title', 'Halaman')
@section('subtitle', 'Halaman')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Blog</li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <a href="{{ route('pages.create') }}" class="btn btn-sm btn-info"><i class="fas fa-plus-circle"></i>
                        Tambah Data
                    </a>
                    {{--
                    <button id="deleteSelectedBtn" class="btn btn-sm btn-danger ml-2" disabled>
                        <i class="fas fa-trash"></i> Hapus Data Terpilih
                    </button>  --}}
                </x-slot>
                <x-table id="table">
                    <x-slot name="thead">
                        {{--  <th width="5%">
                            <div class="form-check form-check-inline">
                                <input id="selectAll" class="form-check-input" type="checkbox" name="selectAll"
                                    value="true">
                            </div>
                        </th>  --}}
                        <th width="5%">NO</th>
                        <th width="35%">JUDUL HALAMAN</th>
                        <th>SLUG</th>
                        <th>AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatable')


@push('scripts')
    <script>
        let table;
        let button = '#submitBtn';

        table = $('#table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            pageLength: 30, // Default jumlah data yang ditampilkan
            lengthMenu: [
                [10, 30, 50, 100],
                [10, 30, 50, 100]
            ], // Dropdown pilihan
            ajax: {
                url: '{{ route('pages.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'title',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'slug',
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });


        // Ketika checkbox "selectAll" di header diklik
        $('#selectAll').on('click', function() {
            // Set semua checkbox baris sesuai status selectAll
            $('.row-checkbox').prop('checked', $(this).prop('checked'));

            // Enable/disable tombol hapus berdasarkan checkbox yang dipilih
            const anyChecked = $('.row-checkbox:checked').length > 0;
            $('#deleteSelectedBtn').prop('disabled', !anyChecked);
        });

        // Ketika checkbox baris di klik
        $(document).on('click', '.row-checkbox', function() {
            // Jika ada checkbox baris yang tidak dicentang, maka selectAll juga tidak dicentang
            if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });

        // Enable/disable tombol hapus berdasarkan checkbox yang dipilih
        $(document).on('change', '.row-checkbox', function() {
            const anyChecked = $('.row-checkbox:checked').length > 0;
            $('#deleteSelectedBtn').prop('disabled', !anyChecked);
        });

        // Fungsi hapus data terpilih saat tombol diklik
        $('#deleteSelectedBtn').on('click', function() {
            const selectedIds = $('.row-checkbox:checked').map(function() {
                return $(this).data('id'); // Pastikan checkbox row punya atribut data-id
            }).get();

            if (selectedIds.length === 0) {
                Swal.fire('Oops!', 'Tidak ada data yang dipilih.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Hapus Data Terpilih?',
                text: `Apakah Anda yakin ingin menghapus ${selectedIds.length} data? Data yang dihapus tidak bisa dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                        showConfirmButton: false,
                    });

                    $.ajax({
                        url: '{{ route('posts.deleteSelected') }}', // Route untuk delete massal, sesuaikan
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds,
                        },
                        success: function(response) {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                $(button).prop('disabled', false);
                                table.ajax.reload(); // Reload DataTables
                                $('#deleteSelectedBtn').prop('disabled', true);
                                $('#selectAll').prop('checked', false);
                            });
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire('Gagal!', xhr.responseJSON?.message ||
                                'Terjadi kesalahan.', 'error');
                        }
                    });
                }
            });
        });

        function addForm(url, title = 'Postingan Baru') {

        }

        function deleteData(url, name) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            });

            swalWithBootstrapButtons.fire({
                title: 'Delete Data!',
                text: 'Apakah Anda yakin ingin menghapus ' + name +
                    ' ? Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya!',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan Swal loading sebelum menghapus
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                table.ajax.reload(); // Reload DataTables setelah penghapusan
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops! Gagal',
                                text: xhr.responseJSON ? xhr.responseJSON.message :
                                    'Terjadi kesalahan!',
                                showConfirmButton: true,
                            }).then(() => {
                                table.ajax.reload(); // Reload tabel jika terjadi error
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
