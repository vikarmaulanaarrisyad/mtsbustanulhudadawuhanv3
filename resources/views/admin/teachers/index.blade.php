@extends($layout)

@section('title', 'Data Guru & Staf')
@section('subtitle', 'Master Data')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-tie mr-2 animate__animated animate__fadeInLeft"></i> 
                            Direktori Guru & Tenaga Kependidikan
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola profil profesional, kualifikasi, dan data kepegawaian tenaga pendidik Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-chalkboard-teacher fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Data Guru</h4>
                        <p class="text-muted text-sm mb-0">Manajemen seluruh staf pengajar Madrasah</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <button onclick="addForm(`{{ route('teachers.store') }}`)" class="btn btn-info shadow-sm font-weight-bold px-4 btn-premium">
                            <i class="fas fa-plus-circle mr-1"></i> TAMBAH GURU
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="teacherTable" style="width:100%">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>Nama Lengkap</th>
                                <th>NIP / Identitas</th>
                                <th>Jabatan / Tugas</th>
                                <th>Pangkat / Golongan</th>
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
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Table Styling */
    #teacherTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #teacherTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #teacherTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 6px 15px rgba(0,0,0,0.06); background: #f8fbff; }
    #teacherTable td { border: none; padding: 1.5rem 0.75rem; vertical-align: middle; }
    #teacherTable td:first-child { border-radius: 12px 0 0 12px; font-weight: bold; color: #17a2b8; }
    #teacherTable td:last-child { border-radius: 0 12px 12px 0; }

    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
</style>

@include('admin.teachers.form')
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        table = $('#teacherTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari guru...", search: "" },
            ajax: { url: '{{ route("teachers.data") }}' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'name',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-info rounded-circle d-flex align-items-center justify-content-center text-info font-weight-bold" style="width:40px;height:40px;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>';
                    }
                },
                { data: 'nip', defaultContent: '-' },
                { data: 'position', defaultContent: '-' },
                { 
                    data: 'rank',
                    render: function(data) {
                        return data ? '<span class="badge badge-light border px-3 py-2 rounded-pill shadow-xs">' + data + '</span>' : '-';
                    }
                },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    function addForm(url, title = 'Tambah Guru Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Edit Data Guru') {
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
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Guru?', text: 'Yakin ingin menghapus ' + name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({ type: "DELETE", url: url, success: (res) => { table.ajax.reload(); Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message }); } });
            }
        });
    }
</script>
@endpush
