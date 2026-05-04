@extends($layout)

@section('title', 'Tahun Pelajaran')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-blue-cool overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-calendar-check mr-2 animate__animated animate__fadeInLeft"></i> 
                            Kalender Akademik & Tahun Pelajaran
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Atur periode aktif pembelajaran dan tentukan semester berjalan untuk operasional Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-history fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Data Periode</h4>
                        <p class="text-muted text-sm mb-0">Kelola tahun ajaran dan status semester aktif</p>
                    </div>
                    <div class="d-flex align-items-center">
                        @can('academic-year.create')
                            <button onclick="addForm(`{{ route('academic-years.store') }}`)" class="btn btn-primary shadow-sm font-weight-bold px-4 btn-premium">
                                <i class="fas fa-plus-circle mr-1"></i> TAMBAH PERIODE
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="academicTable" style="width:100%">
                        <thead class="bg-light-primary text-uppercase">
                            <tr>
                                <th width="80px" class="text-center py-3">NO</th>
                                <th>TAHUN PELAJARAN</th>
                                <th>SEMESTER</th>
                                <th class="text-center">SEMESTER AKTIF</th>
                                <th class="text-center">PPDB AKTIF</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.academic.academic_year.form')

<style>
    /* Premium Design System */
    .bg-gradient-blue-cool { background: linear-gradient(135deg, #007bff 0%, #00d2ff 100%) !important; }
    .bg-light-primary { background: #eef7ff; color: #007bff; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    
    /* Table Styling */
    #academicTable { border-collapse: separate; border-spacing: 0 12px; }
    #academicTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #academicTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 6px 15px rgba(0,0,0,0.06); background: #f8fbff; }
    #academicTable td { border: none; padding: 1.5rem 0.75rem; vertical-align: middle; }
    #academicTable td:first-child { border-radius: 12px 0 0 12px; font-weight: bold; color: #007bff; }
    #academicTable td:last-child { border-radius: 0 12px 12px 0; }

    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        table = $('#academicTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari tahun...", search: "" },
            ajax: { url: '{{ route('academic-years.data') }}' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'academic_year',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary font-weight-bold" style="width:35px;height:35px;background:#eef7ff;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>';
                    }
                },
                { 
                    data: 'semester.semester_name',
                    render: function(data) {
                        return '<span class="badge badge-light border px-3 py-2 rounded-pill font-weight-bold"><i class="fas fa-clock mr-1 text-info"></i> ' + data + '</span>';
                    }
                },
                { data: 'current_semester', className: 'text-center' },
                { data: 'admission_semester', className: 'text-center' },
            ]
        });
    });

    function addForm(url, title = 'Tambah Tahun Pelajaran') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Edit Tahun Pelajaran') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close(); $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            loopForm(res.data);
        }).fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Error' }); });
    }

    function updateCurrentSemester(id) {
        Swal.fire({ title: 'Aktifkan Semester?', text: "Status semester ini akan diatur sebagai aktif!", icon: 'warning', showCancelButton: true })
        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: "Memproses...", didOpen: () => Swal.showLoading() });
                $.ajax({
                    url: '/academic/academic-years/' + id + '/update/current-semester',
                    type: 'PUT', data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.close(); toastr.success(res.message);
                        table.ajax.reload();
                        setTimeout(() => window.location.reload(), 1000);
                    },
                    error: function(xhr) { Swal.close(); toastr.error("Gagal memperbarui status."); }
                });
            }
        });
    }

    function updateAdmissionSemester(id) {
        Swal.fire({ title: 'Aktifkan PPDB?', text: "Jadikan semester ini sebagai periode aktif PPDB?", icon: 'info', showCancelButton: true })
        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: "Memproses...", didOpen: () => Swal.showLoading() });
                $.ajax({
                    url: '/academic/academic-years/' + id + '/update/admission-semester',
                    type: 'PUT', data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.close(); toastr.success(res.message);
                        table.ajax.reload();
                        setTimeout(() => window.location.reload(), 1000);
                    },
                    error: function(xhr) { Swal.close(); toastr.error("Gagal memperbarui status."); }
                });
            }
        });
    }

    function submitForm(originalForm) {
        $(button).prop('disabled', true);
        Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });
        $.ajax({
            url: $(originalForm).attr('action'), type: 'POST',
            data: new FormData(originalForm), dataType: 'JSON', contentType: false, cache: false, processData: false,
            success: function(res) {
                Swal.close(); $(modal).modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 2000, showConfirmButton: false });
                table.ajax.reload();
            },
            error: function(xhr) {
                Swal.close(); $(button).prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' });
                if (xhr.status === 422) loopErrors(xhr.responseJSON.errors);
            }
        });
    }
</script>
@endpush
