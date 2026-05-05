@extends('layouts.app')

@section('title', 'Verifikasi Izin Guru')
@section('subtitle', 'Kepegawaian')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-emerald overflow-hidden position-relative animate__animated animate__fadeInDown" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-check-double mr-2 animate__animated animate__fadeInLeft"></i> 
                            Verifikasi Izin & Cuti Guru
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tinjau dan proses pengajuan izin guru secara efisien dengan sistem pemfilteran cerdas.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-file-signature fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- FILTERS & STATS SECTION -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-xl-8 col-lg-7">
        <div class="card border-0 shadow-sm premium-card">
            <div class="card-body p-4">
                <div class="row align-items-end">
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <label class="text-[10px] font-black text-muted uppercase tracking-widest mb-2 block">Status Verifikasi</label>
                        <select id="filter_status" class="form-control select2">
                            <option value="">Semua Status</option>
                            <option value="pending" selected>Menunggu (Pending)</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <label class="text-[10px] font-black text-muted uppercase tracking-widest mb-2 block">Jenis Izin</label>
                        <select id="filter_type" class="form-control select2">
                            <option value="">Semua Jenis</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Cuti">Cuti</option>
                            <option value="Perjalanan Dinas">Perjalanan Dinas</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-12 mb-3 mb-md-0">
                        <label class="text-[10px] font-black text-muted uppercase tracking-widest mb-2 block">Rentang Tanggal</label>
                        <div class="input-group">
                            <input type="date" id="filter_date_start" class="form-control border-light text-xs rounded-left">
                            <input type="date" id="filter_date_end" class="form-control border-light text-xs rounded-right">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <button type="button" onclick="refreshTable()" class="btn btn-emerald btn-block shadow-sm font-weight-bold py-2 btn-premium">
                            <i class="fas fa-filter mr-2 d-md-none"></i> <span class="d-none d-md-inline"><i class="fas fa-filter"></i></span> <span class="d-md-none">FILTER</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-5 mt-3 mt-lg-0">
        <div class="row h-100">
            <div class="col-6">
                <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius: 15px; border-bottom: 4px solid #f59e0b;">
                    <p class="text-[10px] font-black text-muted uppercase tracking-tighter mb-1">Menunggu</p>
                    <h3 class="font-weight-bold text-warning mb-0" id="stat_pending">-</h3>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius: 15px; border-bottom: 4px solid #10b981;">
                    <p class="text-[10px] font-black text-muted uppercase tracking-tighter mb-1">Disetujui</p>
                    <h3 class="font-weight-bold text-success mb-0" id="stat_approved">-</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA TABLE SECTION -->
<div class="row">
    <div class="col-12 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body p-0">
                <div class="table-responsive p-md-4 p-2">
                    <table class="table table-hover align-middle mb-0" id="permitTable" style="width:100%">
                        <thead class="bg-light-emerald text-uppercase">
                            <tr>
                                <th width="250px">Identitas Guru</th>
                                <th class="d-none d-md-table-cell">Jenis & Tanggal</th>
                                <th class="d-none d-lg-table-cell">Alasan</th>
                                <th width="150px" class="text-center">Status</th>
                                <th width="120px" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Review -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
            <div class="bg-gradient-emerald p-4 text-white text-center">
                <h5 class="modal-title font-weight-bold mb-0" id="teacherName">Review Izin Guru</h5>
            </div>
            <form id="formReview">
                @csrf
                <input type="hidden" id="permitId">
                <div class="modal-body p-4 bg-light">
                    <div class="form-group mb-4">
                        <label class="text-[11px] font-black uppercase tracking-widest text-muted mb-3 d-block text-center">Keputusan Akhir</label>
                        <div class="row">
                            <div class="col-6">
                                <label class="w-100 mb-0">
                                    <input type="radio" name="status" value="approved" class="d-none peer" checked>
                                    <div class="p-3 p-md-4 text-center rounded-24 border-2 cursor-pointer bg-white transition-all border-light hover:border-emerald peer-checked:border-emerald peer-checked:bg-soft-emerald shadow-sm">
                                        <i class="fas fa-check-circle text-emerald fa-2x mb-2"></i>
                                        <span class="text-[9px] md:text-[10px] font-black uppercase d-block text-emerald">Terima</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="w-100 mb-0">
                                    <input type="radio" name="status" value="rejected" class="d-none peer">
                                    <div class="p-3 p-md-4 text-center rounded-24 border-2 cursor-pointer bg-white transition-all border-light hover:border-rose peer-checked:border-danger peer-checked:bg-soft-danger shadow-sm">
                                        <i class="fas fa-times-circle text-danger fa-2x mb-2"></i>
                                        <span class="text-[9px] md:text-[10px] font-black uppercase d-block text-danger">Tolak</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-[11px] font-black uppercase tracking-widest text-muted mb-2">Catatan Kepala Madrasah</label>
                        <textarea name="note" rows="4" class="form-control border-0 shadow-sm rounded-24 p-3 text-sm" placeholder="Berikan alasan atau pesan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-light">
                    <button type="button" onclick="submitReview()" class="btn btn-emerald btn-block rounded-pill py-3 font-weight-black text-[11px] uppercase tracking-widest shadow-lg btn-premium">
                        SIMPAN KEPUTUSAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-gradient-emerald { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
    .bg-light-emerald { background: #f0fdf4; color: #166534; font-size: 0.65rem; font-weight: 800; letter-spacing: 1px; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 20px; overflow: hidden; transition: all 0.3s ease; border: none !important; }
    
    #permitTable { border-collapse: separate; border-spacing: 0 10px; }
    #permitTable tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.01); 
        transition: all 0.2s ease;
        border-radius: 15px;
    }
    #permitTable tbody tr:hover { 
        box-shadow: 0 8px 15px rgba(0,0,0,0.05); 
        transform: scale(1.005);
    }
    #permitTable td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    #permitTable td:first-child { border-radius: 15px 0 0 15px; }
    #permitTable td:last-child { border-radius: 0 15px 15px 0; }

    .rounded-24 { border-radius: 24px !important; }
    .text-emerald { color: #10b981 !important; }
    .bg-emerald { background: #10b981 !important; }
    .btn-emerald { background: #10b981 !important; color: white !important; }
    .bg-soft-emerald { background: #ecfdf5 !important; }
    .badge-soft-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-soft-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .badge-soft-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-soft-info { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }

    .peer:checked + div {
        border-color: #10b981 !important;
        background: #f0fdf4 !important;
        transform: translateY(-5px);
    }
    .peer[value="rejected"]:checked + div {
        border-color: #ef4444 !important;
        background: #fef2f2 !important;
    }
    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }

    /* MOBILE OPTIMIZATIONS */
    @media (max-width: 768px) {
        .card-body.p-4 { padding: 1.25rem !important; }
        h2.font-weight-bold { font-size: 1.4rem !important; }
        #permitTable { border-spacing: 0 8px; }
        #permitTable td { padding: 0.75rem 0.5rem; }
        .avatar-sm { width: 32px !important; height: 32px !important; margin-right: 8px !important; }
        .font-weight-bold.text-dark { font-size: 0.85rem !important; }
        .text-xs { font-size: 0.65rem !important; }
        .badge { font-size: 7px !important; padding: 4px 8px !important; }
        .btn-premium { font-size: 0.65rem !important; padding: 0.5rem 1rem !important; }
        .bg-circle-1 { width: 150px; height: 150px; }
        .bg-circle-2 { width: 80px; height: 80px; }
    }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        table = $('#permitTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Nama...", search: "" },
            ajax: {
                url: '{{ route("teacher.permits.data") }}',
                data: function(d) {
                    d.status = $('#filter_status').val();
                    d.type = $('#filter_type').val();
                    d.date_start = $('#filter_date_start').val();
                    d.date_end = $('#filter_date_end').val();
                }
            },
            columns: [
                { data: 'teacher_info', name: 'teacher.name' },
                { data: 'permit_info', name: 'type', className: 'd-none d-md-table-cell' },
                { data: 'reason_info', name: 'reason', className: 'd-none d-lg-table-cell' },
                { data: 'status_badge', name: 'status', className: 'text-center' },
                { data: 'action', searchable: false, sortable: false, className: 'text-right' }
            ],
            drawCallback: function(settings) {
                // Counts could be fetched via separate API for accuracy
            }
        });

        $('#filter_status, #filter_type, #filter_date_start, #filter_date_end').on('change', refreshTable);
    });

    function refreshTable() { table.ajax.reload(); }

    function reviewPermit(id, name) {
        $('#permitId').val(id);
        $('#teacherName').text('Review: ' + name);
        $('#reviewModal').modal('show');
    }

    function submitReview() {
        const id = $('#permitId').val();
        const data = $('#formReview').serialize();

        Swal.fire({
            title: 'SIMPAN?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'YA, SIMPAN',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                $.post('{{ url("admin/teacher/permits") }}/' + id + '/approve', data)
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'BERHASIL', text: response.message, showConfirmButton: false, timer: 1500 })
                            .then(() => { refreshTable(); $('#reviewModal').modal('hide'); });
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Error' });
                    });
            }
        });
    }
</script>
@endpush
