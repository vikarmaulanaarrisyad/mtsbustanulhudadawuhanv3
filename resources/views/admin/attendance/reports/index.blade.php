@extends($layout)

@section('title', 'Laporan Presensi Guru')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-emerald overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-chart-bar mr-2 animate__animated animate__fadeInLeft"></i> 
                            Analitik & Laporan Presensi
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pantau riwayat kehadiran staf, ekspor data bulanan, dan evaluasi kedisiplinan secara real-time.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-file-invoice fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <div class="col-12">
        <!-- PREMIUM FILTER PANEL -->
        <div class="card shadow-sm border-0 premium-card mb-4 bg-white">
            <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center">
                <div class="avatar-sm bg-soft-emerald rounded-circle d-flex align-items-center justify-content-center text-emerald mr-3" style="width:40px;height:40px;">
                    <i class="fas fa-filter"></i>
                </div>
                <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Parameter Laporan</h5>
            </div>
            <div class="card-body p-4 bg-light-soft">
                <form id="filterForm" action="{{ route('attendance-reports.print') }}" target="_blank">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Pilih Guru / Staf</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-user-tie"></i>
                                <select name="teacher_id" id="teacher_id" class="form-control select2-no-search border-0">
                                    <option value="">-- Seluruh Staf --</option>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Periode Awal</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" name="start_date" id="start_date" class="form-control font-weight-bold" value="{{ date('Y-m-01') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Periode Akhir</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-calendar-check"></i>
                                <input type="date" name="end_date" id="end_date" class="form-control font-weight-bold" value="{{ date('Y-m-t') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex" style="gap: 10px;">
                                <button type="button" onclick="refreshTable()" class="btn btn-emerald flex-fill rounded-pill font-weight-bold shadow-emerald-light">
                                    <i class="fas fa-search mr-1"></i> TAMPILKAN
                                </button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4 font-weight-bold shadow-sm" title="Cetak PDF">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MAIN DATA TABLE -->
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark">Riwayat Log Kehadiran</h4>
                <p class="text-muted text-sm mb-0">Detail rekaman waktu dan pemindaian IP Address presensi</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="reportTable" style="width:100%">
                        <thead class="bg-light-emerald text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th width="150px">TANGGAL</th>
                                <th>NAMA GURU / STAF</th>
                                <th class="text-center">MASUK</th>
                                <th class="text-center">PULANG</th>
                                <th class="text-center">STATUS</th>
                                <th>LOG IP</th>
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
    .bg-gradient-emerald { background: linear-gradient(135deg, #059669 0%, #047857 100%) !important; }
    .bg-light-emerald { background: #ecfdf5; color: #059669; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-emerald { background: #059669; color: #fff; border: none; }
    .btn-emerald:hover { background: #047857; color: #fff; }
    .text-emerald { color: #059669; }
    .bg-soft-emerald { background: #d1fae5; }
    .shadow-emerald-light { box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 45px;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%;
    }
    .input-group-premium:focus-within { border-color: #059669; box-shadow: 0 0 10px rgba(5, 150, 105, 0.1); }
    .input-group-premium:focus-within i { color: #059669; }

    /* Select2 Tweaks inside input group */
    .select2-container--default .select2-selection--single { border: none !important; background: transparent !important; height: auto !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 0; font-weight: 600; color: #334155; line-height: normal; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }

    /* Table Enhancements */
    #reportTable { border-collapse: separate; border-spacing: 0 8px; }
    #reportTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; }
    #reportTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8fffa; }
    #reportTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #reportTable td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #059669; }
    #reportTable td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        // Initialize simple select2 without search
        $('.select2-no-search').select2({ minimumResultsForSearch: -1, width: '100%' });

        table = $('#reportTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari log presensi...", search: "" },
            ajax: { 
                url: '{{ route("attendance-reports.data") }}',
                data: function(d) {
                    d.teacher_id = $('#teacher_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center' },
                { 
                    data: 'date',
                    render: function(data) {
                        return '<div class="font-weight-bold text-dark"><i class="far fa-calendar-alt text-muted mr-1"></i> ' + data + '</div>';
                    }
                },
                { 
                    data: 'teacher_name',
                    render: function(data) {
                        return '<span class="font-weight-bold text-emerald">' + data + '</span>';
                    }
                },
                { 
                    data: 'check_in',
                    className: 'text-center',
                    render: function(data) {
                        if(data === '-' || !data) return '<span class="text-muted">-</span>';
                        return '<span class="badge badge-light border border-success px-2 py-1 text-success shadow-sm"><i class="fas fa-sign-in-alt mr-1"></i> ' + data + '</span>';
                    }
                },
                { 
                    data: 'check_out',
                    className: 'text-center',
                    render: function(data) {
                        if(data === '-' || !data) return '<span class="text-muted">-</span>';
                        return '<span class="badge badge-light border border-info px-2 py-1 text-info shadow-sm"><i class="fas fa-sign-out-alt mr-1"></i> ' + data + '</span>';
                    }
                },
                { data: 'status_badge', className: 'text-center' },
                { 
                    data: 'check_in_ip',
                    render: function(data) {
                        if(data === '-' || !data) return '<span class="text-muted">-</span>';
                        return '<small class="text-muted"><i class="fas fa-network-wired mr-1"></i>' + data + '</small>';
                    }
                },
            ]
        });
    });

    function refreshTable() {
        let btn = $('.btn-emerald');
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> MEMUAT...');
        table.ajax.reload(function() {
            btn.html('<i class="fas fa-search mr-1"></i> TAMPILKAN');
        });
    }
</script>
@endpush
