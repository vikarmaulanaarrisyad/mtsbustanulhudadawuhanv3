@extends($layout)

@section('title', 'Monitoring Jurnal KBM')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-file-signature mr-2 animate__animated animate__fadeInLeft"></i> 
                            Monitoring Jurnal KBM
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pantau ringkasan materi dan catatan pengajaran harian seluruh guru secara terpusat.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-book-open fa-8x opacity-2 shadow-icon"></i>
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
        <div class="card shadow-sm border-0 premium-card mb-4 bg-white">
            <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center">
                <div class="avatar-sm bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center text-indigo mr-3" style="width:40px;height:40px;">
                    <i class="fas fa-filter"></i>
                </div>
                <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Pencarian Jurnal</h5>
            </div>
            <div class="card-body p-4 bg-light-soft">
                <form id="filterForm" action="{{ route('admin.teaching-journals.export-pdf') }}" target="_blank">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Pilih Guru</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-user-tie"></i>
                                <select name="teacher_id" id="teacher_id" class="form-control select2-search border-0">
                                    <option value="">-- Seluruh Guru --</option>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Dari Tanggal</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" name="start_date" id="start_date" class="form-control font-weight-bold" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Sampai Tanggal</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-calendar-check"></i>
                                <input type="date" name="end_date" id="end_date" class="form-control font-weight-bold" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex" style="gap: 10px;">
                                <button type="button" onclick="refreshTable()" class="btn btn-indigo flex-fill rounded-pill font-weight-bold shadow-indigo-light">
                                    <i class="fas fa-search mr-1"></i> CARI
                                </button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4 font-weight-bold shadow-sm" title="Ekspor PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark">Data Jurnal Pengajaran</h4>
                <p class="text-muted text-sm mb-0">List rekapitulasi materi KBM harian guru madrasah</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="journalTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th width="120px">TANGGAL</th>
                                <th>GURU</th>
                                <th>MAPEL</th>
                                <th>KELAS</th>
                                <th>RINGKASAN MATERI</th>
                                <th>SISWA ABSEN</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-indigo { background: linear-gradient(135deg, #4338ca 0%, #312e81 100%) !important; }
    .bg-light-indigo { background: #eef2ff; color: #4338ca; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-indigo { background: #4338ca; color: #fff; border: none; }
    .btn-indigo:hover { background: #3730a3; color: #fff; }
    .text-indigo { color: #4338ca; }
    .bg-soft-indigo { background: #e0e7ff; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(67, 56, 202, 0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 45px;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%;
    }
    .input-group-premium:focus-within { border-color: #4338ca; box-shadow: 0 0 10px rgba(67, 56, 202, 0.1); }
    .input-group-premium:focus-within i { color: #4338ca; }

    #journalTable { border-collapse: separate; border-spacing: 0 8px; }
    #journalTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; }
    #journalTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8faff; }
    #journalTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #journalTable td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #4338ca; }
    #journalTable td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        $('.select2-search').select2({ width: '100%' });

        table = $('#journalTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari jurnal...", search: "" },
            ajax: { 
                url: '{{ route("admin.teaching-journals.data") }}',
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
                        return '<span class="font-weight-bold text-indigo">' + data + '</span>';
                    }
                },
                { data: 'subject_name' },
                { data: 'class_name' },
                { 
                    data: 'material_summary',
                    render: function(data) {
                        return '<small class="text-dark font-weight-bold">' + (data.length > 50 ? data.substring(0, 50) + '...' : data) + '</small>';
                    }
                },
                { 
                    data: 'absent_students',
                    render: function(data) {
                        if(!data) return '<span class="text-muted text-xs">Nihil</span>';
                        return '<small class="text-danger">' + data + '</small>';
                    }
                },
            ]
        });
    });

    function refreshTable() {
        let btn = $('.btn-indigo');
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> MEMUAT...');
        table.ajax.reload(function() {
            btn.html('<i class="fas fa-search mr-1"></i> CARI');
        });
    }
</script>
@endpush
