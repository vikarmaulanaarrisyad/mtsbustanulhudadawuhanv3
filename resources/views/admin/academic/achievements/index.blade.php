@extends($layout)

@section('title', 'Prestasi Siswa')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-warning overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-trophy mr-2 animate__animated animate__bounceIn"></i> 
                            Prestasi Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Dokumentasikan dan apresiasi pencapaian terbaik siswa MTs Bustanul Huda.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-medal fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Prestasi</h4>
                        <p class="text-muted text-sm mb-0">Kelola riwayat kompetisi dan penghargaan siswa</p>
                    </div>
                    <div class="d-flex align-items-center flex-wrap mt-2 mt-md-0">
                        <button onclick="addForm()" class="btn btn-warning shadow-sm font-weight-bold px-4 mr-2 btn-premium text-white">
                            <i class="fas fa-plus-circle mr-1"></i> TAMBAH PRESTASI
                        </button>
                        <button onclick="printReport()" class="btn btn-danger shadow-sm font-weight-bold px-4 mr-3 btn-premium">
                            <i class="fas fa-file-pdf mr-1"></i> CETAK REKAP
                        </button>
                        <div class="input-group mr-2" style="width: 200px;">
                            <select id="filter_status" class="form-control select2" onchange="refreshTable()">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="achievementTable" style="width:100%">
                        <thead class="bg-light-warning text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>Siswa</th>
                                <th>Prestasi</th>
                                <th>Kategori</th>
                                <th>Peringkat</th>
                                <th>Tahun</th>
                                <th class="text-center">Status</th>
                                <th width="150px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important; }
    .bg-light-warning { background: #fffdf5; color: #856404; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }
    .premium-card { border-radius: 15px; overflow: hidden; }
    #achievementTable { border-collapse: separate; border-spacing: 0 12px; }
    #achievementTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; }
    #achievementTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 6px 15px rgba(0,0,0,0.06); }
    #achievementTable td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
</style>

@include('admin.academic.achievements.form')
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';

    $(function() {
        table = $('#achievementTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: {
                url: '{{ route('admin.achievements.data') }}',
                data: function(d) {
                    d.status = $('#filter_status').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'student_info' },
                { data: 'achievement_info' },
                { data: 'category' },
                { data: 'rank' },
                { data: 'year' },
                { data: 'status', className: 'text-center' },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    function refreshTable() { table.ajax.reload(); }

    function addForm() {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text('Tambah Prestasi Siswa');
        $(`${modal} form`)[0].reset();
        $(`${modal} [name=_method]`).val('post');
        $(`${modal} form`).attr('action', '{{ route('admin.achievements.store') }}');
        $('#student_id').val('').trigger('change');
    }

    function editData(id) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(`{{ url('admin/achievements') }}/${id}`)
            .done(res => {
                Swal.close();
                $(modal).modal('show');
                $(`${modal} .modal-title`).text('Edit Prestasi Siswa');
                $(`${modal} [name=_method]`).val('put');
                $(`${modal} form`).attr('action', `{{ url('admin/achievements') }}/${id}`);
                
                // Fill form
                Object.keys(res).forEach(key => {
                    let el = $(`${modal} [name="${key}"]`);
                    if (el.length) el.val(res[key]);
                });
                $('#student_id').val(res.student_id).trigger('change');
                $('#academic_year_id').val(res.academic_year_id).trigger('change');
            })
            .fail(() => { Swal.close(); Swal.fire('Gagal', 'Gagal memuat data', 'error'); });
    }

    function updateStatus(id, status) {
        Swal.fire({
            title: 'Update Status?',
            text: `Yakin ingin mengubah status menjadi ${status}?`,
            icon: 'question', showCancelButton: true
        }).then(result => {
            if (result.isConfirmed) {
                $.post(`{{ url('admin/achievements') }}/${id}/status`, { _token: '{{ csrf_token() }}', status: status })
                    .done(res => { Swal.fire('Berhasil', res.message, 'success'); table.ajax.reload(); })
                    .fail(xhr => Swal.fire('Gagal', xhr.responseJSON?.message || 'Error', 'error'));
            }
        });
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/achievements') }}/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: res => { Swal.fire('Dihapus', res.message, 'success'); table.ajax.reload(); },
                    error: xhr => Swal.fire('Gagal', xhr.responseJSON?.message || 'Error', 'error')
                });
            }
        });
    }

    function printReport() {
        let params = $('#filter_status').val();
        window.open(`{{ route('admin.achievements.print') }}?status=${params}`, '_blank');
    }

    $('#form-achievement').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false, processData: false,
            success: res => {
                $(modal).modal('hide');
                Swal.fire('Berhasil', res.message, 'success');
                table.ajax.reload();
            },
            error: xhr => Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error')
        });
    });
</script>
@endpush
