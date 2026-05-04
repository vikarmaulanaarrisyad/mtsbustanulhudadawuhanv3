@extends($layout)

@section('title', 'Status Peserta Didik')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-tag mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Status Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Definisikan kategori status siswa (Aktif, Lulus, Mutasi, dll) untuk manajemen database yang akurat.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-tags fa-8x opacity-2 shadow-icon"></i>
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
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Status</h4>
                        <p class="text-muted text-sm mb-0">Kelola kategori status untuk profil peserta didik</p>
                    </div>
                    <div class="d-flex align-items-center">
                        @can('class-group.create')
                            <button onclick="addForm(`{{ route('student-status.store') }}`)" class="btn btn-indigo shadow-sm font-weight-bold px-4 btn-premium mr-2">
                                <i class="fas fa-plus-circle mr-1"></i> TAMBAH STATUS
                            </button>
                            <button onclick="confirmImport()" class="btn btn-success shadow-sm font-weight-bold px-4 btn-premium">
                                <i class="fas fa-file-excel mr-1"></i> IMPORT EXCEL
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="statusTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="80px" class="text-center py-3">NO</th>
                                <th>NAMA STATUS</th>
                                <th width="150px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.academic.student_status.form')
@include('admin.academic.student_status.import-excel')

<style>
    /* Premium Design System */
    .bg-gradient-indigo { background: linear-gradient(135deg, #6610f2 0%, #4b0082 100%) !important; }
    .bg-light-indigo { background: #f4f0fa; color: #6610f2; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-indigo { background: #6610f2; color: #fff; }
    .btn-indigo:hover { background: #520dc2; color: #fff; }
    
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    
    /* Table Styling */
    #statusTable { border-collapse: separate; border-spacing: 0 10px; }
    #statusTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 10px; }
    #statusTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fcfaff; }
    #statusTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #statusTable td:first-child { border-radius: 10px 0 0 10px; font-weight: bold; color: #6610f2; }
    #statusTable td:last-child { border-radius: 0 10px 10px 0; }

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
    let importExcel = '#importExcelModal';
    let button = '#submitBtn';

    $(function() {
        table = $('#statusTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari status...", search: "" },
            ajax: { url: '{{ route('student-status.data') }}' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { 
                    data: 'student_status_name',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center text-indigo font-weight-bold" style="width:35px;height:35px;background:#f0eaff;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>';
                    }
                },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    function addForm(url, title = 'Tambah Status Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Edit Status Siswa') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close(); $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            loopForm(res.data);
        }).fail(err => {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Error' });
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

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Status?', text: 'Yakin ingin menghapus status ' + name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({ type: "DELETE", url: url, success: (res) => { table.ajax.reload(); Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message }); } });
            }
        });
    }

    function confirmImport() { $(importExcel).modal('show'); }
</script>
@endpush
