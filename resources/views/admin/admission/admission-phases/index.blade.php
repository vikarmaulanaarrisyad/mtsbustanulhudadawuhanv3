@extends('layouts.app')

@section('title', 'Gelombang Pendaftaran')
@section('subtitle', 'Gelombang Pendaftaran')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">PPDB</li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    Informasi Gelombang Pendaftaran
                </x-slot>

                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td style="width: 25%;">
                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                <strong>Tanggal Mulai Pendaftaran</strong>
                            </td>
                            <td style="width: 25%;">
                                @if ($studentAdmission)
                                    {{ tanggal_indonesia($studentAdmission->admission_start_date) }}
                                @else
                                    <a href="{{ route('student-admissions.index') }}">
                                        <i class="text-muted">Belum diatur</i>
                                    </a>
                                @endif
                            </td>

                            <td style="width: 25%;">
                                <i class="bi bi-megaphone text-primary me-2"></i>
                                <strong>Tanggal Mulai Pengumuman</strong>
                            </td>
                            <td style="width: 25%;">
                                @if ($studentAdmission && $studentAdmission->announcement_start_date)
                                    {{ tanggal_indonesia($studentAdmission->announcement_start_date) }}
                                @else
                                    <a href="{{ route('student-admissions.index') }}">
                                        <i class="text-muted">Belum diatur</i>
                                    </a>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <i class="bi bi-calendar-x text-primary me-2"></i>
                                <strong>Tanggal Selesai Pendaftaran</strong>
                            </td>
                            <td>
                                @if ($studentAdmission)
                                    {{ tanggal_indonesia($studentAdmission->admission_end_date) }}
                                @else
                                    <a href="{{ route('student-admissions.index') }}">
                                        <i class="text-muted">Belum diatur</i>
                                    </a>
                                @endif
                            </td>

                            <td>
                                <i class="bi bi-megaphone text-primary me-2"></i>
                                <strong>Tanggal Selesai Pengumuman</strong>
                            </td>
                            <td>
                                @if ($studentAdmission && $studentAdmission->announcement_end_date)
                                    {{ tanggal_indonesia($studentAdmission->announcement_end_date) }}
                                @else
                                    <a href="{{ route('student-admissions.index') }}">
                                        <i class="text-muted">Belum diatur</i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <button onclick="addForm(`{{ route('admission-phases.store') }}`)" class="btn btn-sm btn-info"><i
                            class="fas fa-plus-circle"></i>
                        Tambah Data
                    </button>
                </x-slot>
                <x-table id="table">
                    <x-slot name="thead">
                        <th width="5%">NO</th>
                        <th width="25%">TAHUN PELAJARAN</th>
                        <th>GELOMBANG PENDAFTARAN</th>
                        <th>TANGGAL MULAI</th>
                        <th>TANGGAL SELESAI</th>
                        <th>AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.admission.admission-phases.form')
@endsection


@include('includes.datatable')
@include('includes.datepicker')

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
                url: '{{ route('admission-phases.data') }}',
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
                    data: 'phase_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'phase_start_date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'phase_end_date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        function addForm(url, title = 'Gelombang Pendaftaran') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Gelombang Pendaftaran') {
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
