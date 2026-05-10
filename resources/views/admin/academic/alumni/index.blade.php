@extends($layout)

@section('title', 'Data Alumni')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-graduate mr-2 animate__animated animate__fadeInLeft"></i> 
                            Database Alumni Madrasah
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pantau riwayat kelulusan dan jejak alumni dari berbagai angkatan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-university fa-8x opacity-2 shadow-icon"></i>
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
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Alumni</h4>
                        <p class="text-muted text-sm mb-0">Total siswa yang telah menyelesaikan pendidikan</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="input-group" style="width: 320px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-graduation-cap text-muted"></i></span>
                            </div>
                            <select id="filter_academic_year" class="form-control select2 border-left-0" onchange="refreshTable()">
                                <option value="">Semua Angkatan Lulus</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="alumniTable" style="width:100%">
                        <thead class="bg-light-dark text-uppercase text-white">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>Nama Lengkap</th>
                                <th>NIS/NISN</th>
                                <th>Tahun Lulus</th>
                                <th>Tanggal Keluar</th>
                                <th>Catatan Kelulusan</th>
                                <th width="100px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes */
    .bg-gradient-dark { background: linear-gradient(135deg, #343a40 0%, #1d2124 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-dark { background: #343a40; color: #fff; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Table Styling */
    #alumniTable { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #alumniTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 12px; border: 1px solid #eee; }
    #alumniTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fdfdfd; }
    #alumniTable td { border: none; padding: 1.5rem 0.75rem; vertical-align: middle; }
    #alumniTable td:first-child { border-radius: 12px 0 0 12px; color: #343a40; font-weight: bold; }
    #alumniTable td:last-child { border-radius: 0 12px 12px 0; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    $(function() {
        table = $('#alumniTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari alumni...", search: "" },
            ajax: {
                url: '{{ route("alumni.data") }}',
                data: function(d) { d.academic_year_id = $('#filter_academic_year').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'nama_lengkap',
                    render: function(data) { return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-dark rounded-circle d-flex align-items-center justify-content-center text-dark font-weight-bold" style="width:35px;height:35px;background:#eee;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>'; }
                },
                { data: 'nis' },
                { 
                    data: 'ta_lulus',
                    render: function(data) {
                        return '<span class="badge badge-dark px-3 py-2 rounded-pill">' + data + '</span>';
                    }
                },
                { data: 'exit_date' },
                { data: 'notes' },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });
    function refreshTable() { table.ajax.reload(); }
</script>
@endpush
