@extends('layouts.app')

@section('title', 'Tahun Pelajaran')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Akademik</li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <x-card>
                @can('academic-year.create')
                    <x-slot name="header">
                        <button onclick="addForm(`{{ route('academic-years.store') }}`)" class="btn btn-sm btn-info"><i
                                class="fas fa-plus-circle"></i>
                            Tambah Data</button>
                    </x-slot>
                @endcan
                <x-table>
                    <x-slot name="thead">
                        <th width="5%">NO</th>
                        <th width="25%">TAHUN PELAJARAN</th>
                        <th>SEMESTER</th>
                        <th>SEMESTER AKTIF</th>
                        <th>PPDB/PMB AKTIF</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.academic.academic_year.form')
@endsection

@include('includes.datatable')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('academic-years.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'academic_year',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'semester.semester_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'current_semester',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'admission_semester',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        function addForm(url, title = 'Form Tahun Pelajaran') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Tahun Pelajaran') {
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

        function updateCurrentSemester(id) {
            let _token = $('meta[name="csrf-token"]').attr('content');

            // Tampilkan Swal Konfirmasi terlebih dahulu
            Swal.fire({
                title: 'Anda yakin?',
                text: "Status semester akan diperbarui!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Tampilkan Swal Loading
                    Swal.fire({
                        title: "Memproses...",
                        text: "Mohon tunggu sebentar...",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading(); // Spinner loading
                        }
                    });

                    $.ajax({
                        url: '/academic/academic-years/' + id + '/update/current-semester',
                        type: 'PUT',
                        data: {
                            _token: _token // CSRF Token
                        },
                        success: function(response) {
                            Swal.close(); // Tutup loading

                            toastr.success(response.message, "Berhasil!", {
                                timeOut: 2000,
                                onHidden: function() {
                                    window.location.reload();
                                }
                            });

                            table.ajax.reload();

                            let icon = $('a[id="' + id + '"]').find('i');
                            if (icon.length > 0) {
                                if (response.new_status == 1) {
                                    icon.removeClass('fa-toggle-off text-danger')
                                        .addClass('fa-toggle-on text-success');
                                } else {
                                    icon.removeClass('fa-toggle-on text-success')
                                        .addClass('fa-toggle-off text-danger');
                                }
                            }
                        },
                        error: function(xhr) {
                            Swal.close();

                            if (xhr.status === 400) {
                                toastr.error(xhr.responseJSON.message, "Gagal!", {
                                    timeOut: 2000
                                });
                            } else {
                                toastr.error("Terjadi kesalahan saat memperbarui status.", "Gagal!", {
                                    timeOut: 2000
                                });
                            }
                        }
                    });

                } else {
                    // Jika dibatalkan
                    toastr.info("Aksi dibatalkan.", "Info", {
                        timeOut: 2000
                    });
                }
            });
        }

        function updateAdmissionSemester(id) {
            let _token = $('meta[name="csrf-token"]').attr('content');

            // Tampilkan Swal Konfirmasi terlebih dahulu
            Swal.fire({
                title: 'Perbarui Semester PPDB?',
                text: "Tindakan ini akan menjadikan semester ini sebagai semester aktif untuk penerimaan peserta didik baru (PPDB).",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Tampilkan Swal Loading
                    Swal.fire({
                        title: "Memproses...",
                        text: "Mohon tunggu sebentar...",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading(); // Spinner loading
                        }
                    });

                    $.ajax({
                        url: '/academic/academic-years/' + id + '/update/admission-semester',
                        type: 'PUT',
                        data: {
                            _token: _token // CSRF Token
                        },
                        success: function(response) {
                            Swal.close(); // Tutup loading
                            table.ajax.reload();

                            toastr.success(response.message, "Berhasil!", {
                                timeOut: 2000,
                                onHidden: function() {
                                    window.location.reload();
                                }
                            });

                            let icon = $('a[id="' + id + '"]').find('i');
                            if (icon.length > 0) {
                                if (response.new_status == 1) {
                                    icon.removeClass('fa-toggle-off text-danger')
                                        .addClass('fa-toggle-on text-success');
                                } else {
                                    icon.removeClass('fa-toggle-on text-success')
                                        .addClass('fa-toggle-off text-danger');
                                }
                            }

                        },
                        error: function(xhr) {
                            Swal.close();

                            if (xhr.status === 400) {
                                toastr.error(xhr.responseJSON.message, "Gagal!", {
                                    timeOut: 2000
                                });
                            } else {
                                toastr.error("Terjadi kesalahan saat memperbarui status.", "Gagal!", {
                                    timeOut: 2000
                                });
                            }
                        }
                    });

                } else {
                    // Jika dibatalkan
                    toastr.info("Aksi dibatalkan.", "Info", {
                        timeOut: 2000
                    });
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
    </script>
@endpush
