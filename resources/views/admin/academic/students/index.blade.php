@extends($layout)

@section('title', 'Data Siswa Aktif')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-friends mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Data Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pusat pengelolaan data profil siswa, filter akademik, dan ekspor data administratif.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-id-card fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- PREMIUM STATISTICS WIDGETS -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm premium-card info-card" style="border-left: 5px solid #17a2b8 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Siswa Lulus (Alumni)</p>
                        <h3 class="font-weight-bold mb-0 text-info">{{ $alumniCount }}</h3>
                    </div>
                    <div class="icon-shape bg-soft-info rounded-circle"><i class="fas fa-user-graduate text-info"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm premium-card info-card" style="border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Siswa Aktif</p>
                        <h3 class="font-weight-bold mb-0 text-success">{{ $activeStudents }}</h3>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle"><i class="fas fa-user-check text-success"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm premium-card info-card" style="border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Laki-laki</p>
                        <h3 class="font-weight-bold mb-0 text-primary">{{ $male }}</h3>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle"><i class="fas fa-male text-primary text-lg"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm premium-card info-card" style="border-left: 5px solid #dc3545 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Perempuan</p>
                        <h3 class="font-weight-bold mb-0 text-danger">{{ $female }}</h3>
                    </div>
                    <div class="icon-shape bg-soft-danger rounded-circle"><i class="fas fa-female text-danger text-lg"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <h4 class="font-weight-bold text-dark mb-1">Data Induk Siswa</h4>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-soft-info px-2 py-1 mr-2"><i class="fas fa-filter mr-1"></i> Mode Filter Aktif</span>
                        </div>
                    </div>
                    <div class="col-md-8 text-right">
                        <div class="d-flex flex-wrap justify-content-end" style="gap:10px;">
                            <button onclick="addForm(`{{ route('students.store') }}`)" class="btn btn-info shadow-sm font-weight-bold px-3 btn-premium">
                                <i class="fas fa-user-plus mr-1"></i> TAMBAH SISWA
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle shadow-sm font-weight-bold px-3 btn-premium" type="button" data-toggle="dropdown">
                                    <i class="fas fa-file-export mr-1"></i> EKSPOR / IMPOR
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" style="border-radius:12px;">
                                    <a class="dropdown-item py-2" href="#" data-toggle="modal" data-target="#importExcelModal"><i class="fas fa-file-import mr-2 text-primary"></i> Import dari Excel</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="exportExcel()"><i class="fas fa-file-excel mr-2 text-success"></i> Export ke Excel</a>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="exportPDF()"><i class="fas fa-file-pdf mr-2 text-danger"></i> Export ke PDF</a>
                                </div>
                            </div>
                            <button onclick="deleteSelected()" class="btn btn-outline-danger shadow-sm font-weight-bold px-3 btn-premium">
                                <i class="fas fa-trash-alt mr-1"></i> HAPUS TERPILIH
                            </button>
                        </div>
                    </div>
                </div>

                <!-- DYNAMIC FILTERS -->
                <div class="row mt-4 pt-3 border-top bg-light-soft rounded-pill px-3 py-2 mx-0 align-items-center">
                    <div class="col-md-2 col-12 mb-2 mb-md-0">
                        <select id="filter_academic_year" class="form-control form-control-sm select2-no-search">
                            <option value="">-- Thn Pelajaran --</option>
                            @foreach ($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <select id="filter_class_group" class="form-control form-control-sm select2">
                            <option value="">-- Semua Rombel --</option>
                            <option value="none" style="color: #d81b60; font-weight: bold;">[!] Tanpa Rombel</option>
                            @foreach ($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->class_group }} {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-12 mb-2 mb-md-0">
                        <select id="filter_status" class="form-control form-control-sm select2-no-search">
                            <option value="">-- Status Aktif --</option>
                            @foreach($studentStatuses as $st)
                                <option value="{{ $st->id }}">{{ $st->student_status_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-12 mb-2 mb-md-0">
                        <select id="filter_jk" class="form-control form-control-sm select2-no-search">
                            <option value="">-- Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="d-flex" style="gap:5px;">
                            <button onclick="applyFilter()" class="btn btn-primary btn-sm flex-fill font-weight-bold rounded-pill shadow-xs">FILTER</button>
                            <button onclick="resetFilter()" class="btn btn-light btn-sm flex-fill font-weight-bold rounded-pill border">RESET</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="studentTable" style="width:100%">
                        <thead class="bg-light-primary text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select-all">
                                        <label class="custom-control-label" for="select-all"></label>
                                    </div>
                                </th>
                                <th width="200px">Siswa</th>
                                <th>Detail Identitas</th>
                                <th>Rombel & TA</th>
                                <th width="100px" class="text-center">Status</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important; }
    .bg-soft-info { background: #e0f7fa; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .bg-soft-danger { background: #ffebee; }
    .bg-light-soft { background: #f8f9fa; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .icon-shape { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
    
    /* Table Enhancements */
    #studentTable { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #studentTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 12px; }
    #studentTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8faff; }
    #studentTable td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    #studentTable td:first-child { border-radius: 12px 0 0 12px; }
    #studentTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-primary { background: #f1f7ff; color: #0056b3; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .badge-soft-info { background: #e0f7fa; color: #00838f; border: 1px solid #b2ebf2; }
</style>

@include('admin.academic.students.form')
@include('admin.academic.students.detail')
@include('admin.academic.students.import-excel')
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let modalDetail = '#modal-detail';
    let button = '#submitBtn';

    $(function() {
        table = $('#studentTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, responsive: true,
            language: { searchPlaceholder: "Cari nama atau NIS...", search: "" },
            ajax: {
                url: '{{ route("students.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_group_id = $('#filter_class_group').val();
                    d.status_id = $('#filter_status').val();
                    d.jenis_kelamin = $('#filter_jk').val();
                }
            },
            columns: [
                { data: 'select_checkbox', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'nis',
                    render: function(data, type, row) {
                        return '<div class="d-flex flex-column"><span class="font-weight-bold text-dark mb-1">' + row.nama_lengkap + '</span><span class="text-xs text-muted">NIS: ' + data + '</span></div>';
                    }
                },
                { 
                    data: 'nisn',
                    render: function(data, type, row) {
                        let jk = row.jk_badge;
                        return '<div class="d-flex flex-column align-items-start"><span class="text-sm mb-1 text-muted">NISN: ' + (data || '-') + '</span>' + jk + '</div>';
                    }
                },
                { data: 'kelas' },
                { data: 'status', className: 'text-center' },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });

        $('#select-all').on('change', function() { $('.select-row').prop('checked', this.checked); });
    });

    function applyFilter() { table.ajax.reload(); }
    function resetFilter() { $('#filter_academic_year, #filter_class_group, #filter_status, #filter_jk').val('').trigger('change.select2'); table.ajax.reload(); }

    function addForm(url, title = 'Tambah Siswa Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
        $(`${modal} .nav-link:first`).tab('show');
    }

    function editForm(url, title = 'Edit Data Siswa') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close(); $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            let d = response.data;
            loopForm(d);
            if (d.profile) loopForm(d.profile);
            if (d.parents) loopForm(d.parents);
            $('#is_active').prop('checked', d.is_active);
            $(`${modal} .nav-link:first`).tab('show');
        }).fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat data.' }); });
    }

    function showDetail(id) {
        Swal.fire({ title: "Memuat...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.get(`{{ url('/academic/students') }}/${id}`).done(response => {
            Swal.close(); let d = response.data;
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
            if (d.profile) {
                $('#det_alamat').text(d.profile.alamat || '-');
                $('#det_no_hp').text(d.profile.no_hp || '-');
                $('#det_email').text(d.profile.email || '-');
                $('#det_foto').attr('src', d.profile.foto ? '/storage/' + d.profile.foto : '').toggle(!!d.profile.foto);
            }
            if (d.parents) {
                $('#det_ayah').text(d.parents.father_name || '-');
                $('#det_ibu').text(d.parents.mother_name || '-');
            }
            $('#btn-print-nisn').attr('onclick', `printCard(${id})`);
            $(modalDetail).modal('show');
        }).fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat detail.' }); });
    }

    function submitForm(originalForm) {
        $(button).prop('disabled', true);
        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: $(originalForm).attr('action'), type: 'POST',
            data: new FormData(originalForm), dataType: 'JSON', contentType: false, cache: false, processData: false,
            success: function(res) {
                Swal.close(); $(modal).modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 3000, showConfirmButton: false });
                table.ajax.reload();
            },
            error: function(xhr) {
                Swal.close(); $(button).prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' });
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Data?', text: 'Yakin ingin menghapus ' + name + '?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Iya, Hapus!' })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({ type: "DELETE", url: url, success: (res) => { Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message }); table.ajax.reload(); } });
            }
        });
    }

    function deleteSelected() {
        let ids = []; $('.select-row:checked').each(function() { ids.push($(this).val()); });
        if (ids.length === 0) { Swal.fire({ icon: 'warning', title: 'Pilih Data', text: 'Pilih minimal satu data.' }); return; }
        Swal.fire({ title: 'Hapus ' + ids.length + ' Data?', text: 'Data tidak dapat dikembalikan!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Iya, Hapus!' })
        .then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("students.deleteSelected") }}', { ids: ids }, (res) => { 
                    Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message }); 
                    $('#select-all').prop('checked', false); table.ajax.reload(); 
                });
            }
        });
    }

    function exportExcel() { window.open('{{ route("students.export_excel") }}?' + $.param({academic_year_id: $('#filter_academic_year').val(), class_group_id: $('#filter_class_group').val(), jenis_kelamin: $('#filter_jk').val()}), '_blank'); }
    function exportPDF() { window.open('{{ route("students.export_pdf") }}?' + $.param({academic_year_id: $('#filter_academic_year').val(), class_group_id: $('#filter_class_group').val(), jenis_kelamin: $('#filter_jk').val()}), '_blank'); }
    
    function printCard(id) {
        window.open(`{{ url('/academic/students') }}/${id}/card-pdf`, '_blank');
    }
</script>
@endpush
