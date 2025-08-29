@extends('layouts.app')

@section('title', 'Album Foto')
@section('subtitle', 'Album Foto')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Media</li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <button onclick="addForm(`{{ route('albums.store') }}`)" class="btn btn-sm btn-info"><i
                            class="fas fa-plus-circle"></i>
                        Tambah Data
                    </button>

                    <button id="deleteSelectedBtn" class="btn btn-sm btn-danger ml-2" disabled>
                        <i class="fas fa-trash"></i> Hapus Data Terpilih
                    </button>
                </x-slot>
                <x-table id="table">
                    <x-slot name="thead">
                        <th width="5%">
                            <div class="form-check form-check-inline">
                                <input id="selectAll" class="form-check-input" type="checkbox" name="selectAll"
                                    value="true">
                            </div>
                        </th>
                        <th width="5%">NO</th>
                        <th width="15%">GAMBAR</th>
                        <th>JUDUL</th>
                        <th>KETERANGAN</th>
                        <th>AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.media.album.form')
@endsection

@include('includes.datatable')
@include('includes.summernote')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('#table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('albums.data') }}',
            },
            columns: [{
                    data: 'selectAll',
                    name: 'selectAll',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'album_cover',
                    orderable: false,
                },
                {
                    data: 'album_title',
                    orderable: false,
                },
                {
                    data: 'album_description',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        })

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
                        url: '{{ route('albums.deleteSelected') }}', // Route untuk delete massal, sesuaikan
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

        function addForm(url, title = 'Album Foto') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Album Foto') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan spinner loading
                }
            });

            $.get(url)
                .done(response => {
                    Swal.close(); // Tutup loading setelah sukses
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');

                    resetForm(`${modal} form`);
                    loopForm(response.data);
                })
                .fail(errors => {
                    Swal.close(); // Tutup loading jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errors.responseJSON?.message || 'Terjadi kesalahan saat memuat data.',
                        showConfirmButton: true,
                    });

                    if (errors.status == 422) {
                        loopErrors(errors.responseJSON.errors);
                    }
                });
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);

            // Menampilkan Swal loading
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                }
            });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST', // Gunakan method dari form
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response, textStatus, xhr) {
                    Swal.close(); // Tutup Swal Loading

                    if (xhr.status === 201 || xhr.status === 200) {
                        $(modal).modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            table.ajax.reload(); // Reload DataTables
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close(); // Tutup Swal Loading
                    $(button).prop('disabled', false);

                    let errorMessage = "Terjadi kesalahan!";
                    if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errorMessage,
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (xhr.status === 422) {
                        loopErrors(xhr.responseJSON.errors);
                    }
                }
            });
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
