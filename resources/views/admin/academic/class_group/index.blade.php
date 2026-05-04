@extends($layout)

@section('title', 'Kelas')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Akademik</li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <x-card>
                @can('class-group.create')
                    <x-slot name="header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <button onclick="addForm(`{{ route('class-groups.store') }}`)" class="btn btn-sm btn-info mb-2"><i class="fas fa-plus-circle"></i> Tambah Data</button>
                                <button onclick="syncClasses()" class="btn btn-sm btn-success mb-2 ml-1" id="btnSync"><i class="fas fa-sync-alt"></i> Sinkron dari Ganjil</button>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <label class="mr-2 mb-0">Filter TA:</label>
                                <select id="filter_academic_year_id" class="form-control form-control-sm select2" style="width: 200px;" onchange="refreshTable()">
                                    <option value="">-- Semua TA --</option>
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }} - {{ $ay->semester->semester_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-slot>
                @endcan
                <x-table>
                    <x-slot name="thead">
                        <th width="5%">NO</th>
                        <th>TA / SEMESTER</th>
                        <th>NAMA KELAS</th>
                        <th>ROMBEL KELAS</th>
                        <th>TINGKAT KELAS</th>
                        <th>WALI KELAS</th>
                        <th>AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.academic.class_group.form')
@endsection

@include('includes.datatable')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('class-groups.data') }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year_id').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ta_semester',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'class_group',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'sub_class_group',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'class_level',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'wali_kelas',
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

        function refreshTable() {
            table.ajax.reload();
        }

        function syncClasses() {
            let targetAyId = $('#filter_academic_year_id').val();
            if (!targetAyId) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih Tahun Pelajaran (Genap) tujuan sinkronisasi di filter.' });
                return;
            }

            Swal.fire({
                title: 'Sinkronisasi Kelas?',
                text: 'Sistem akan menyalin data kelas dari Semester Ganjil ke Semester Genap pada tahun pelajaran yang dipilih. Lanjutkan?',
                icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnSync').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Sinkronisasi...');
                    
                    $.post('{{ route("class-groups.sync") }}', {
                        _token: '{{ csrf_token() }}',
                        target_academic_year_id: targetAyId
                    }).done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                    }).fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    }).always(() => {
                        $('#btnSync').prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Sinkron dari Ganjil');
                    });
                }
            });
        }

        function addForm(url, title = 'Form Kelas') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Kelas') {
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
