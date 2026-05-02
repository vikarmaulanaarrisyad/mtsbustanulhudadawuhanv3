@extends($layout)

@section('title', 'Data Siswa')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Akademik</li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    {{-- STATISTIK --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalStudents }}</h3>
                    <p>Total Siswa</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activeStudents }}</h3>
                    <p>Siswa Aktif</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $male }}</h3>
                    <p>Laki-laki</p>
                </div>
                <div class="icon"><i class="fas fa-male"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $female }}</h3>
                    <p>Perempuan</p>
                </div>
                <div class="icon"><i class="fas fa-female"></i></div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex flex-wrap" style="gap:8px;">
                            <select id="filter_academic_year" class="form-control form-control-sm" style="width:180px;">
                                <option value="">-- Tahun Pelajaran --</option>
                                @foreach ($academicYears as $ay)
                                    <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                                @endforeach
                            </select>
                            <select id="filter_class_group" class="form-control form-control-sm" style="width:150px;">
                                <option value="">-- Kelas --</option>
                                @foreach ($classGroups as $cg)
                                    <option value="{{ $cg->id }}">{{ $cg->class_group }} {{ $cg->sub_class_group }}</option>
                                @endforeach
                            </select>
                            <select id="filter_jk" class="form-control form-control-sm" style="width:140px;">
                                <option value="">-- Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <button onclick="applyFilter()" class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>
                            <button onclick="resetFilter()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-sync"></i> Reset</button>
                        </div>
                        <div class="d-flex flex-wrap mt-2 mt-md-0" style="gap:8px;">
                            <button onclick="addForm(`{{ route('students.store') }}`)" class="btn btn-sm btn-info">
                                <i class="fas fa-plus-circle"></i> Tambah Siswa
                            </button>
                            <button data-toggle="modal" data-target="#importExcelModal" class="btn btn-sm btn-success">
                                <i class="fas fa-file-import"></i> Import Excel
                            </button>
                            <button onclick="exportExcel()" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                            <button onclick="exportPDF()" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                            <button onclick="deleteSelected()" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus Terpilih
                            </button>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="3%"><input type="checkbox" id="select-all"></th>
                        <th width="4%">NO</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>NAMA LENGKAP</th>
                        <th>JK</th>
                        <th>KELAS</th>
                        <th>STATUS</th>
                        <th width="10%">AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.academic.students.form')
    @include('admin.academic.students.detail')
    @include('admin.academic.students.import-excel')
@endsection

@include('includes.datatable')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let modalDetail = '#modal-detail';
        let button = '#submitBtn';

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: '{{ route("students.data") }}',
                    data: function(d) {
                        d.academic_year_id = $('#filter_academic_year').val();
                        d.class_group_id = $('#filter_class_group').val();
                        d.jenis_kelamin = $('#filter_jk').val();
                    }
                },
                columns: [
                    { data: 'select_checkbox', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nis' },
                    { data: 'nisn', defaultContent: '-' },
                    { data: 'nama_lengkap' },
                    { data: 'jk_badge', orderable: false, searchable: false },
                    { data: 'kelas', orderable: false, searchable: false },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'action', orderable: false, searchable: false },
                ]
            });

            // Select all checkbox
            $('#select-all').on('change', function() {
                $('.select-row').prop('checked', this.checked);
            });
        });

        function applyFilter() {
            table.ajax.reload();
        }

        function resetFilter() {
            $('#filter_academic_year, #filter_class_group, #filter_jk').val('');
            table.ajax.reload();
        }

        function addForm(url, title = 'Tambah Siswa Baru') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');
            resetForm(`${modal} form`);
            // Reset tabs to first
            $(`${modal} .nav-link:first`).tab('show');
        }

        function editForm(url, title = 'Edit Data Siswa') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            $.get(url)
                .done(response => {
                    Swal.close();
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');
                    resetForm(`${modal} form`);

                    let d = response.data;
                    // Student fields
                    loopForm(d);

                    // Profile fields
                    if (d.profile) {
                        $('#profile_nik').val(d.profile.nik);
                        $('#profile_no_kk').val(d.profile.no_kk);
                        $('#alamat').val(d.profile.alamat);
                        $('#rt').val(d.profile.rt);
                        $('#rw').val(d.profile.rw);
                        $('#desa').val(d.profile.desa);
                        $('#kecamatan').val(d.profile.kecamatan);
                        $('#kabupaten').val(d.profile.kabupaten);
                        $('#provinsi').val(d.profile.provinsi);
                        $('#kode_pos').val(d.profile.kode_pos);
                        $('#no_hp').val(d.profile.no_hp);
                        $('#email').val(d.profile.email);
                        $('#transportasi').val(d.profile.transportasi);
                        $('#jarak_rumah').val(d.profile.jarak_rumah);
                        $('#tinggi_badan').val(d.profile.tinggi_badan);
                        $('#berat_badan').val(d.profile.berat_badan);
                        $('#golongan_darah').val(d.profile.golongan_darah);
                    }

                    // Parent fields
                    if (d.parents) {
                        $('#father_name').val(d.parents.father_name);
                        $('#father_nik').val(d.parents.father_nik);
                        $('#father_education_id').val(d.parents.father_education_id);
                        $('#father_income_id').val(d.parents.father_income_id);
                        $('#father_phone').val(d.parents.father_phone);
                        $('#mother_name').val(d.parents.mother_name);
                        $('#mother_nik').val(d.parents.mother_nik);
                        $('#mother_education_id').val(d.parents.mother_education_id);
                        $('#mother_income_id').val(d.parents.mother_income_id);
                        $('#mother_phone').val(d.parents.mother_phone);
                    }

                    // Checkboxes
                    $('#is_active').prop('checked', d.is_active);

                    // First tab
                    $(`${modal} .nav-link:first`).tab('show');
                })
                .fail(errors => {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: errors.responseJSON?.message || 'Gagal memuat data.' });
                });
        }

        function showDetail(id) {
            Swal.fire({ title: "Memuat...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.get(`{{ url('/academic/students') }}/${id}`)
                .done(response => {
                    Swal.close();
                    let d = response.data;

                    // Populate detail modal
                    $('#det_nama').text(d.nama_lengkap);
                    $('#det_nis').text(d.nis);
                    $('#det_nisn').text(d.nisn || '-');
                    $('#det_nik').text(d.nik || '-');
                    $('#det_jk').text(d.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
                    $('#det_ttl').text((d.tempat_lahir || '-') + ', ' + (d.tanggal_lahir ? new Date(d.tanggal_lahir).toLocaleDateString('id-ID') : '-'));
                    $('#det_kelas').text(d.class_group ? d.class_group.class_group + ' ' + d.class_group.sub_class_group : '-');
                    $('#det_tahun').text(d.academic_year ? d.academic_year.academic_year : '-');
                    $('#det_status').text(d.student_status ? d.student_status.student_status_name : '-');
                    $('#det_asal_sekolah').text(d.asal_sekolah || '-');
                    $('#det_tgl_masuk').text(d.tanggal_masuk ? new Date(d.tanggal_masuk).toLocaleDateString('id-ID') : '-');

                    // Profile
                    if (d.profile) {
                        $('#det_alamat').text(d.profile.alamat || '-');
                        $('#det_no_hp').text(d.profile.no_hp || '-');
                        $('#det_email').text(d.profile.email || '-');
                        if (d.profile.foto) {
                            $('#det_foto').attr('src', '/storage/' + d.profile.foto).show();
                        } else {
                            $('#det_foto').hide();
                        }
                    }

                    // Parents
                    if (d.parents) {
                        $('#det_ayah').text(d.parents.father_name || '-');
                        $('#det_ibu').text(d.parents.mother_name || '-');
                        $('#det_hp_ayah').text(d.parents.father_phone || '-');
                        $('#det_hp_ibu').text(d.parents.mother_phone || '-');
                    }

                    $(modalDetail).modal('show');
                })
                .fail(() => {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat detail siswa.' });
                });
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);
            Swal.fire({ title: 'Mohon Tunggu...', text: 'Sedang memproses data', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST',
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response, textStatus, xhr) {
                    Swal.close();
                    if (xhr.status === 201 || xhr.status === 200) {
                        $(modal).modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: false, timer: 3000 })
                            .then(() => { $(button).prop('disabled', false); table.ajax.reload(); });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    $(button).prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!', showConfirmButton: false, timer: 3000 });
                    if (xhr.status === 422) loopErrors(xhr.responseJSON.errors);
                }
            });
        }

        function deleteData(url, name) {
            Swal.fire({
                title: 'Hapus Data!',
                text: 'Apakah Anda yakin ingin menghapus ' + name + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya!',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 3000 })
                                .then(() => table.ajax.reload());
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' })
                                .then(() => table.ajax.reload());
                        }
                    });
                }
            });
        }

        function deleteSelected() {
            let ids = [];
            $('.select-row:checked').each(function() { ids.push($(this).val()); });

            if (ids.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih minimal satu data untuk dihapus.' });
                return;
            }

            Swal.fire({
                title: 'Hapus ' + ids.length + ' Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Iya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        type: "POST",
                        url: '{{ route("students.deleteSelected") }}',
                        data: { ids: ids },
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 3000 })
                                .then(() => { $('#select-all').prop('checked', false); table.ajax.reload(); });
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                        }
                    });
                }
            });
        }
        function exportExcel() {
            let params = {
                academic_year_id: $('#filter_academic_year').val(),
                class_group_id: $('#filter_class_group').val(),
                jenis_kelamin: $('#filter_jk').val()
            };
            let url = '{{ route("students.export_excel") }}?' + $.param(params);
            window.open(url, '_blank');
        }

        function exportPDF() {
            let params = {
                academic_year_id: $('#filter_academic_year').val(),
                class_group_id: $('#filter_class_group').val(),
                jenis_kelamin: $('#filter_jk').val()
            };
            let url = '{{ route("students.export_pdf") }}?' + $.param(params);
            window.open(url, '_blank');
        }
    </script>
@endpush
