@extends($layout)

@section('title', 'Gelombang Pendaftaran')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-layer-group mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Gelombang PPDB
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Definisikan tahapan pendaftaran (Gelombang 1, 2, dst) untuk mengatur pembagian waktu seleksi calon siswa.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-stream fa-8x opacity-1 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-shape-1"></div>
            <div class="bg-shape-2"></div>
        </div>
    </div>
</div>

<!-- PERIOD SUMMARY CARDS -->
<div class="row mb-2 animate__animated animate__fadeInUp">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-20 p-4 bg-white h-100 position-relative overflow-hidden">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-sm bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center mr-3 text-indigo">
                    <i class="fas fa-calendar-alt fa-lg"></i>
                </div>
                <h6 class="font-weight-bold text-dark mb-0">Jadwal Pendaftaran Utama</h6>
            </div>
            <div class="row">
                <div class="col-6 border-right">
                    <span class="text-xs font-weight-bold text-muted uppercase d-block mb-1">Mulai</span>
                    <span class="font-weight-bold text-indigo">
                        @if ($studentAdmission) {{ tanggal_indonesia($studentAdmission->admission_start_date) }} @else <em class="text-muted">Belum diatur</em> @endif
                    </span>
                </div>
                <div class="col-6 pl-4">
                    <span class="text-xs font-weight-bold text-muted uppercase d-block mb-1">Selesai</span>
                    <span class="font-weight-bold text-danger">
                        @if ($studentAdmission) {{ tanggal_indonesia($studentAdmission->admission_end_date) }} @else <em class="text-muted">Belum diatur</em> @endif
                    </span>
                </div>
            </div>
            <div class="bg-card-decoration" style="background: rgba(79, 70, 229, 0.03);"></div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-20 p-4 bg-white h-100 position-relative overflow-hidden">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-sm bg-soft-success rounded-circle d-flex align-items-center justify-content-center mr-3 text-success">
                    <i class="fas fa-bullhorn fa-lg"></i>
                </div>
                <h6 class="font-weight-bold text-dark mb-0">Jadwal Pengumuman Utama</h6>
            </div>
            <div class="row">
                <div class="col-6 border-right">
                    <span class="text-xs font-weight-bold text-muted uppercase d-block mb-1">Awal</span>
                    <span class="font-weight-bold text-success">
                        @if ($studentAdmission && $studentAdmission->announcement_start_date) {{ tanggal_indonesia($studentAdmission->announcement_start_date) }} @else <em class="text-muted">Belum diatur</em> @endif
                    </span>
                </div>
                <div class="col-6 pl-4">
                    <span class="text-xs font-weight-bold text-muted uppercase d-block mb-1">Akhir</span>
                    <span class="font-weight-bold text-warning">
                        @if ($studentAdmission && $studentAdmission->announcement_end_date) {{ tanggal_indonesia($studentAdmission->announcement_end_date) }} @else <em class="text-muted">Belum diatur</em> @endif
                    </span>
                </div>
            </div>
            <div class="bg-card-decoration" style="background: rgba(16, 185, 129, 0.03);"></div>
        </div>
    </div>
</div>

<!-- DATA TABLE AREA -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0 text-dark">
                        <i class="fas fa-list-ul mr-2 text-indigo"></i> Daftar Gelombang
                    </h5>
                    <button onclick="addForm(`{{ route('admission-phases.store') }}`)" class="btn btn-indigo rounded-pill px-4 font-weight-bold shadow-indigo-light">
                        <i class="fas fa-plus-circle mr-1"></i> TAMBAH GELOMBANG
                    </button>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="phaseTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="5%" class="text-center py-3">NO</th>
                                <th>TAHUN PELAJARAN</th>
                                <th>NAMA GELOMBANG</th>
                                <th><i class="far fa-calendar-alt mr-1"></i> TANGGAL MULAI</th>
                                <th><i class="far fa-calendar-check mr-1"></i> TANGGAL SELESAI</th>
                                <th><i class="fas fa-bullhorn mr-1"></i> PENGUMUMAN</th>
                                <th width="100px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.admission.admission-phases.form')

<style>
    /* PREMIUM UI STYLES */
    .bg-gradient-indigo-dark { background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%) !important; }
    .bg-shape-1 { position: absolute; width: 400px; height: 400px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; top: -150px; right: -100px; }
    .bg-shape-2 { position: absolute; width: 200px; height: 200px; background: rgba(99, 102, 241, 0.05); border-radius: 50%; bottom: -50px; left: 10%; }
    
    .premium-card { border-radius: 20px; overflow: hidden; }
    .rounded-20 { border-radius: 20px; }
    .bg-light-indigo { background: #f8fafc; color: #64748b; font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px; }
    .bg-soft-indigo { background: #eef2ff; }
    .bg-soft-success { background: #f0fdf4; }
    
    .btn-indigo { background: #4f46e5; color: #fff; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }

    .bg-card-decoration { position: absolute; width: 150px; height: 150px; border-radius: 50%; top: -50px; right: -50px; z-index: 0; }

    /* Table Styling */
    #phaseTable { border-collapse: separate; border-spacing: 0 10px; }
    #phaseTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #phaseTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #fcfaff; }
    #phaseTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #phaseTable td:first-child { border-radius: 12px 0 0 12px; }
    #phaseTable td:last-child { border-radius: 0 12px 12px 0; }

    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }
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
        table = $('#phaseTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari gelombang...", search: "" },
            ajax: { url: '{{ route('admission-phases.data') }}' },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'academic_year', render: (data) => '<span class="badge badge-light border px-3 py-2 text-indigo font-weight-bold">' + data + '</span>' },
                { data: 'phase_name', render: (data) => '<span class="font-weight-bold text-dark h6 mb-0">' + data + '</span>' },
                { data: 'phase_start_date', render: (data) => '<span class="small font-weight-bold text-muted"><i class="far fa-calendar-alt mr-1"></i> ' + data + '</span>' },
                { data: 'phase_end_date', render: (data) => '<span class="small font-weight-bold text-muted"><i class="far fa-calendar-check mr-1 text-danger"></i> ' + data + '</span>' },
                { data: 'announcement_date', render: (data) => '<span class="badge badge-soft-success px-3 py-2 rounded-pill font-weight-bold text-xs"><i class="fas fa-bullhorn mr-1"></i> ' + (data || '---') + '</span>' },
                { data: 'action', className: 'text-center' },
            ]
        });
    });

    function addForm(url, title = 'Tambah Gelombang Pendaftaran') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Edit Gelombang Pendaftaran') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close(); $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);

            // Format dates for native HTML5 date inputs (must be YYYY-MM-DD)
            const dates = ['phase_start_date', 'phase_end_date', 'announcement_date'];
            dates.forEach(field => {
                if(res.data[field]) {
                    res.data[field] = moment(res.data[field]).format('YYYY-MM-DD');
                }
            });

            loopForm(res.data);
        }).fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data tidak ditemukan.' }); });
    }

    function submitForm(originalForm) {
        $(button).prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
        $.ajax({
            url: $(originalForm).attr('action'),
            type: 'POST',
            data: new FormData(originalForm),
            processData: false, contentType: false,
            success: function(res) {
                Swal.close();
                $(modal).modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false });
                table.ajax.reload();
            },
            error: function(xhr) {
                Swal.close(); $(button).prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' });
                if (xhr.status === 422) loopErrors(xhr.responseJSON.errors);
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Gelombang?', text: 'Hapus ' + name + '? Data yang dihapus tidak dapat dikembalikan!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444' })
        .then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', didOpen: () => Swal.showLoading() });
                $.ajax({ type: "DELETE", url: url, data: { _token: '{{ csrf_token() }}' }, success: (r) => { Swal.fire({ icon: 'success', title: 'Dihapus', text: r.message }); table.ajax.reload(); } });
            }
        });
    }
</script>
@endpush
