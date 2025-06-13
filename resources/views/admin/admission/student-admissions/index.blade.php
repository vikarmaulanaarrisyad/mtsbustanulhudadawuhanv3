@extends('layouts.app')

@section('title', 'Pengaturan Penerimaan Peserta Didik Baru')
@section('subtitle', 'Pengaturan PPDB')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">PPDB</li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                @if ($studentAdmissions == 0)
                    <x-slot name="header">
                        <button id="btn-tambah-data" onclick="addForm(`{{ route('student-admissions.store') }}`)"
                            class="btn btn-sm btn-info">
                            <i class="fas fa-plus-circle"></i> Tambah Data
                        </button>
                    </x-slot>
                @endif

                <x-table>
                    <x-slot name="thead">
                        <th width="5%">NO</th>
                        <th>Tanggal Mulai PPDB</th>
                        <th>Tanggal Selesai PPDB</th>
                        <th>Status PPDB</th>
                        <th>Tahun PPDB</th>
                        <th>Tanggal Mulai Pengumuman</th>
                        <th>Tanggal Selesai Pengumuman</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.admission.student-admissions.form')
@endsection

@include('includes.datatable')
@include('includes.datepicker')

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
                url: '{{ route('student-admissions.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'admission_start_date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'admission_end_date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'admission_status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'admission_year',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'announcement_start_date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'announcement_end_date',
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

        function addForm(url, title = 'Pengaturan Penerimaan Peserta Didik Baru') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Pengaturan Penerimaan Peserta Didik Baru') {
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

                            // Sembunyikan tombol "Tambah Data"
                            $('#btn-tambah-data').remove();
                            $('.card-header').remove();
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

    <script>
        $(function() {
            // Inisialisasi datetimepicker
            $('#admission_start_date').datetimepicker({
                format: 'DD-MM-YYYY'
            });

            $('#admission_end_date').datetimepicker({
                useCurrent: false,
                format: 'DD-MM-YYYY'
            });

            $('#announcement_start_date').datetimepicker({
                useCurrent: false,
                format: 'DD-MM-YYYY'
            });

            $('#announcement_end_date').datetimepicker({
                useCurrent: false,
                format: 'DD-MM-YYYY'
            });

            // Reset dan atur minDate setelah admission_start_date berubah
            $('#admission_start_date').on('change.datetimepicker', function(e) {
                $('#admission_end_date').datetimepicker('minDate', e.date);
                $('#admission_end_date input').val('');
                $('#announcement_start_date input').val('');
                $('#announcement_end_date input').val('');
                $('#announcement_start_date').datetimepicker('minDate', false);
                $('#announcement_end_date').datetimepicker('minDate', false);
            });

            // Reset dan atur minDate untuk announcement_start_date
            $('#admission_end_date').on('change.datetimepicker', function(e) {
                $('#announcement_start_date').datetimepicker('minDate', e.date);
                $('#announcement_start_date input').val('');
                $('#announcement_end_date input').val('');
                $('#announcement_end_date').datetimepicker('minDate', false);
            });

            // Reset dan atur minDate untuk announcement_end_date
            $('#announcement_start_date').on('change.datetimepicker', function(e) {
                $('#announcement_end_date').datetimepicker('minDate', e.date);
                $('#announcement_end_date input').val('');
            });
        });
    </script>
@endpush
