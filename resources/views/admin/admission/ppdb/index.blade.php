@extends($layout)

@section('title', 'Pendaftar PPDB')
@section('subtitle', 'Data Pendaftar')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-gold-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-plus mr-2 animate__animated animate__fadeInLeft"></i> 
                            Penerimaan Peserta Didik Baru
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola data pendaftar, verifikasi berkas, dan pantau statistik penerimaan secara real-time.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('admin.ppdb.committee') }}" class="btn btn-white bg-white/20 border-white/20 text-white rounded-pill px-4 font-weight-bold backdrop-blur-md">
                                <i class="fas fa-users-cog mr-2"></i> KELOLA PANITIA
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-id-badge fa-8x opacity-1 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-shape-gold-1"></div>
            <div class="bg-shape-gold-2"></div>
        </div>
    </div>
</div>

<!-- PREMIUM STATS -->
<div class="row animate__animated animate__fadeInUp">
    <div class="col-lg-3 col-6">
        <div class="ppdb-stat-card glass-indigo">
            <div class="inner text-white">
                <h3 class="font-weight-bold mb-0">{{ $stats['total'] }}</h3>
                <p class="mb-0 opacity-8">Total Pendaftar</p>
            </div>
            <div class="icon-stat"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="ppdb-stat-card glass-warning">
            <div class="inner text-white">
                <h3 class="font-weight-bold mb-0">{{ $stats['pending'] }}</h3>
                <p class="mb-0 opacity-8">Pending Verifikasi</p>
            </div>
            <div class="icon-stat"><i class="fas fa-user-clock"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="ppdb-stat-card glass-success">
            <div class="inner text-white">
                <h3 class="font-weight-bold mb-0">{{ $stats['diterima'] }}</h3>
                <p class="mb-0 opacity-8">Lulus Seleksi</p>
            </div>
            <div class="icon-stat"><i class="fas fa-user-check"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="ppdb-stat-card glass-danger">
            <div class="inner text-white">
                <h3 class="font-weight-bold mb-0">{{ $stats['ditolak'] }}</h3>
                <p class="mb-0 opacity-8">Tidak Lulus</p>
            </div>
            <div class="icon-stat"><i class="fas fa-user-times"></i></div>
        </div>
    </div>
</div>

<!-- CHARTS & ANALYTICS -->
<div class="row mt-2">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-20 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-chart-line mr-2 text-primary"></i> Tren Pendaftaran Harian
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="chart" style="height: 250px;">
                    <canvas id="regTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-20 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-chart-pie mr-2 text-warning"></i> Distribusi Jalur
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="chart" style="height: 250px;">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN TABLE AREA -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex flex-wrap align-items-center" style="gap:10px;">
                        <div class="filter-group">
                            <select id="filter_phase" class="form-control rounded-pill px-3 border-2 text-sm" style="min-width:160px;">
                                <option value="">-- Gelombang --</option>
                                @foreach ($phases as $p)
                                    <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <select id="filter_type" class="form-control rounded-pill px-3 border-2 text-sm" style="min-width:140px;">
                                <option value="">-- Jalur --</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <select id="filter_status" class="form-control rounded-pill px-3 border-2 text-sm" style="min-width:160px;">
                                <option value="">-- Status --</option>
                                <option value="pending">Menunggu Verifikasi</option>
                                <option value="berkas_lengkap">Berkas Lengkap</option>
                                <option value="berkas_tidak_lengkap">Berkas Tidak Lengkap</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <button onclick="applyFilter()" class="btn btn-indigo rounded-pill px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-filter mr-1"></i> FILTER
                        </button>
                    </div>

                    <div class="d-flex flex-wrap mt-2 mt-md-0" style="gap:10px;">
                        <div id="bulk_actions" style="display:none;" class="animate__animated animate__fadeIn">
                            <div class="dropdown">
                                <button class="btn btn-success rounded-pill px-3 font-weight-bold dropdown-toggle" type="button" data-toggle="dropdown">
                                    AKSI MASAL (<span id="selected_count">0</span>)
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-15 overflow-hidden">
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="bulkUpdateStatus('diterima')"><i class="fas fa-check-circle mr-2 text-success"></i> Set DITERIMA</a>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="bulkUpdateStatus('berkas_lengkap')"><i class="fas fa-file-invoice mr-2 text-info"></i> Set BERKAS LENGKAP</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="bulkUpdateStatus('ditolak')"><i class="fas fa-times-circle mr-2"></i> Set DITOLAK</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="dropdown">
                            <button class="btn btn-light border rounded-pill px-3 font-weight-bold dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-print mr-1 text-primary"></i> CETAK
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-15 overflow-hidden">
                                <a class="dropdown-item py-2" href="{{ route('ppdb.print_berita_acara') }}" target="_blank"><i class="fas fa-file-alt mr-2 text-info"></i> Berita Acara</a>
                                <a class="dropdown-item py-2" href="{{ route('ppdb.print_collective_sk') }}" target="_blank"><i class="fas fa-users mr-2 text-success"></i> SK Kolektif Lulus</a>
                            </div>
                        </div>

                        @if ($admission)
                            <a href="{{ route('ppdb.selection') }}" class="btn btn-soft-indigo rounded-pill px-4 font-weight-bold mr-2">
                                <i class="fas fa-tasks mr-1"></i> SELEKSI
                            </a>
                            <button onclick="addForm(`{{ route('ppdb.store') }}`)" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-primary">
                                <i class="fas fa-plus-circle mr-1"></i> PENDAFTAR BARU
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="ppdbTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="40px"><input type="checkbox" id="select_all" class="custom-checkbox"></th>
                                <th width="60px" class="text-center">NO</th>
                                <th>NO. PENDAFTARAN</th>
                                <th>NAMA LENGKAP</th>
                                <th class="text-center">JK</th>
                                <th>ASAL SEKOLAH</th>
                                <th>GELOMBANG</th>
                                <th class="text-center">STATUS</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($admission)
    @include('admin.admission.ppdb.form')
@endif
@include('admin.admission.ppdb.detail')
@include('admin.admission.ppdb.verify')

<style>
    /* PREMIUM DESIGN SYSTEM */
    .bg-gradient-gold-dark { background: linear-gradient(135deg, #b45309 0%, #1e1b4b 100%) !important; }
    .bg-shape-gold-1 { position: absolute; width: 400px; height: 400px; background: rgba(251, 191, 36, 0.1); border-radius: 50%; top: -150px; right: -100px; }
    .bg-shape-gold-2 { position: absolute; width: 200px; height: 200px; background: rgba(251, 191, 36, 0.05); border-radius: 50%; bottom: -50px; left: 10%; }
    
    .ppdb-stat-card {
        padding: 25px; border-radius: 20px; position: relative; overflow: hidden; margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;
    }
    .ppdb-stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
    .ppdb-stat-card .inner { position: relative; z-index: 2; }
    .ppdb-stat-card .icon-stat { 
        position: absolute; right: -10px; bottom: -10px; font-size: 80px; color: rgba(255,255,255,0.15); z-index: 1;
    }

    .glass-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
    .glass-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .glass-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .glass-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .premium-card { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    .rounded-20 { border-radius: 20px; }
    .bg-light-indigo { background: #f8fafc; color: #64748b; font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px; }
    .btn-indigo { background: #4f46e5; color: #fff; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .btn-soft-indigo { background: #e0e7ff; color: #4338ca; }
    .btn-soft-indigo:hover { background: #c7d2fe; }
    
    /* Table Styling */
    #ppdbTable { border-collapse: separate; border-spacing: 0 12px; }
    #ppdbTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #ppdbTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #f8fbff; }
    #ppdbTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #ppdbTable td:first-child { border-radius: 12px 0 0 12px; }
    #ppdbTable td:last-child { border-radius: 0 12px 12px 0; }

    .avatar-sm { width: 35px; height: 35px; font-size: 11px; }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        // CHARTS STYLING
        const trendCtx = document.getElementById('regTrendChart').getContext('2d');
        let grad = trendCtx.createLinearGradient(0, 0, 0, 300);
        grad.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
        grad.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendData->pluck('date')) !!},
                datasets: [{
                    label: 'Pendaftar',
                    data: {!! json_encode($trendData->pluck('total')) !!},
                    borderColor: '#4f46e5',
                    borderWidth: 3,
                    backgroundColor: grad,
                    fill: true, tension: 0.4,
                    pointRadius: 4, pointBackgroundColor: '#fff', pointBorderWidth: 2
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { borderDash: [5,5] } } } }
        });

        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($typeDistribution->pluck('label')) !!},
                datasets: [{
                    data: {!! json_encode($typeDistribution->pluck('value')) !!},
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } } } }
        });

        table = $('#ppdbTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari pendaftar...", search: "" },
            ajax: {
                url: '{{ route("ppdb.data") }}',
                data: (d) => { d.phase_id = $('#filter_phase').val(); d.type_id = $('#filter_type').val(); d.status = $('#filter_status').val(); }
            },
            columns: [
                { data: 'select_checkbox', orderable: false, searchable: false },
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'registration_number', render: (data) => '<span class="badge badge-light border px-3 py-2 text-indigo font-weight-bold">' + data + '</span>' },
                { 
                    data: 'nama_lengkap',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center text-indigo font-weight-bold" style="background:#eef2ff;width:35px;height:35px;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>';
                    }
                },
                { data: 'jk_label', className: 'text-center' },
                { data: 'asal_sekolah', defaultContent: '-' },
                { data: 'gelombang', render: (data) => '<span class="small font-weight-bold">' + data + '</span>' },
                { data: 'status_badge', className: 'text-center' },
                { data: 'action', className: 'text-center' },
            ]
        });

        $('#select_all').on('click', function() { $('.select-row').prop('checked', this.checked); toggleBulkActions(); });
        $(document).on('change', '.select-row', function() { 
            $('#select_all').prop('checked', $('.select-row:checked').length == $('.select-row').length);
            toggleBulkActions(); 
        });
    });

    function toggleBulkActions() {
        let count = $('.select-row:checked').length;
        if (count > 0) { $('#bulk_actions').show(); $('#selected_count').text(count); } 
        else { $('#bulk_actions').hide(); }
    }

    function applyFilter() { table.ajax.reload(); }
    function addForm(url, title = 'Tambah Pendaftar Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
        $(`${modal} .nav-link:first`).tab('show');
    }

    function editForm(url, title = 'Edit Data Pendaftar') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close(); $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`); loopForm(res.data);
            $(`${modal} .nav-link:first`).tab('show');
        });
    }

    function bulkUpdateStatus(status) {
        let ids = []; $('.select-row:checked').each(function() { ids.push($(this).val()); });
        Swal.fire({ title: 'Konfirmasi Masal', text: `Ubah status ${ids.length} pendaftar menjadi ${status.toUpperCase()}?`, icon: 'warning', showCancelButton: true })
        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });
                $.post(`{{ route('ppdb.bulk_update_status') }}`, { _token: '{{ csrf_token() }}', ids: ids, status: status })
                .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 2000, showConfirmButton: false }); table.ajax.reload(); $('#select_all').prop('checked', false); toggleBulkActions(); });
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Pendaftar?', text: 'Yakin ingin menghapus ' + name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
        .then((res) => { if (res.isConfirmed) $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: (r) => { table.ajax.reload(); Swal.fire({ icon: 'success', title: 'Dihapus', text: r.message }); } }); });
    }

    function showDetail(id) {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(`{{ url('/admission/ppdb') }}/${id}`).done(res => {
            Swal.close(); 
            let d = res.data;
            
            // Reset Tabs
            $('#profil-tab').tab('show');
            
            // Header Info
            $('#det_reg_no').text(d.registration_number); 
            $('#det_nama').text(d.nama_lengkap);
            $('#det_asal').text(d.asal_sekolah || '-');
            $('#det_jk').text(d.jk_label);
            $('#det_ttl').text(d.tempat_lahir + ', ' + d.tanggal_lahir);
            if (d.foto) $('#det_foto').attr('src', '/storage/' + d.foto);
            else $('#det_foto').attr('src', '{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}');

            // Personal Data
            $('#det_nisn').text(d.nisn || '-');
            $('#det_nik').text(d.nik || '-');
            $('#det_hp').text(d.no_hp_ortu || '-');
            $('#det_ayah').text(d.nama_ayah || '-');
            $('#det_ibu').text(d.nama_ibu || '-');
            $('#det_alamat').text(d.alamat || '-');
            $('#det_gelombang').text(d.admission_phase ? d.admission_phase.phase_name : '-');
            $('#det_jalur').text(d.admission_type ? d.admission_type.admission_type_name : '-');
            $('#det_verifier').text(d.verifier ? d.verifier.name : 'Belum Diverifikasi');
            $('#det_verified_at').text(d.verified_at || '-');

            // Stepper Logic
            $('.step-item, .step-connector').removeClass('active success danger');
            $('#step_1').addClass('success');
            
            if (d.status === 'pending') {
                $('#line_1').addClass('active'); $('#step_2').addClass('active');
            } else if (d.status === 'berkas_lengkap' || d.status === 'diterima' || d.status.includes('daftar_ulang')) {
                $('#line_1').addClass('success'); $('#step_2').addClass('success');
                $('#line_2').addClass('active'); $('#step_3').addClass('active');
            } else if (d.status === 'berkas_tidak_lengkap' || d.status === 'ditolak') {
                $('#line_1').addClass('danger'); $('#step_2').addClass('danger');
            }

            if (d.status === 'diterima' || d.status.includes('daftar_ulang')) {
                $('#line_2').addClass('success'); $('#step_3').addClass('success');
                $('#line_3').addClass('active'); $('#step_4').addClass('active');
            }

            if (d.status === 'daftar_ulang_terverifikasi' || d.status === 'sudah_masuk_siswa') {
                $('#line_3').addClass('success'); $('#step_4').addClass('success');
            }

            // Documents
            let docsHtml = '';
            const docLabels = {!! json_encode(App\Models\PpdbRegistrant::DOCUMENT_TYPES) !!};
            
            Object.keys(docLabels).forEach(key => {
                let doc = d.documents.find(doc => doc.document_type === key);
                let statusIcon = doc ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-muted"></i>';
                let actionBtn = doc ? `<a href="/storage/${doc.file_path}" target="_blank" class="btn btn-xs btn-indigo rounded-pill px-2">Lihat</a>` : '<span class="text-xs text-muted">Kosong</span>';
                
                docsHtml += `
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-white border rounded-15 shadow-xs h-100 d-flex flex-column justify-content-between">
                            <div class="mb-2 d-flex justify-content-between align-items-start">
                                <span class="text-xs font-weight-bold text-muted uppercase">${docLabels[key]}</span>
                                ${statusIcon}
                            </div>
                            ${actionBtn}
                        </div>
                    </div>
                `;
            });
            $('#det_docs_container').html(docsHtml);

            // === TIMELINE LOGS ===
            const actionMap = {
                'registration':             { label: 'Pendaftaran Mandiri',         icon: 'fa-plus-circle',        color: '#4f46e5' },
                'admin_registration':        { label: 'Pendaftaran oleh Admin',       icon: 'fa-user-edit',          color: '#7c3aed' },
                'upload_berkas':             { label: 'Unggah Berkas',               icon: 'fa-upload',             color: '#0891b2' },
                'verify_berkas':             { label: 'Berkas Diverifikasi ✓',        icon: 'fa-file-signature',     color: '#059669' },
                'verify_berkas_lengkap':     { label: 'Berkas Lengkap ✓',            icon: 'fa-check-double',       color: '#059669' },
                'berkas_incomplete':         { label: 'Berkas Tidak Lengkap ✗',      icon: 'fa-exclamation-circle', color: '#d97706' },
                'reject_ppdb':               { label: 'Pendaftar Ditolak ✗',         icon: 'fa-times-circle',       color: '#dc2626' },
                'verify_payment':            { label: 'Pembayaran Diverifikasi ✓',   icon: 'fa-money-check-alt',    color: '#059669' },
                'verify_payment_admin':      { label: 'Pembayaran Dikonfirmasi Admin', icon: 'fa-check-circle',     color: '#059669' },
                'verify_admin':              { label: 'Status Diperbarui Admin',      icon: 'fa-user-shield',        color: '#4f46e5' },
                'update_data':               { label: 'Data Diperbaharui Admin',      icon: 'fa-pen',                color: '#0284c7' },
                'submit_daftar_ulang':       { label: 'Konfirmasi Daftar Ulang 📋',   icon: 'fa-hand-holding-usd',   color: '#0f766e' },
                'move_to_student':           { label: 'Resmi Menjadi Siswa Aktif 🎉', icon: 'fa-graduation-cap',    color: '#16a34a' },
            };

            let timelineHtml = '';
            const logs = d.logs || [];
            $('#det_timeline_count').text(logs.length + ' catatan');

            if (logs.length > 0) {
                logs.forEach((log, idx) => {
                    const mapKey = actionMap[log.action] || { label: log.action.replace(/_/g,' ').toUpperCase(), icon: 'fa-circle', color: '#94a3b8' };
                    const dateStr = log.created_at || '-';
                    const userName = log.user ? log.user.name : 'Sistem / Pendaftar';
                    const isLast = idx === logs.length - 1;

                    timelineHtml += `
                        <div class="timeline-item">
                            <div class="timeline-icon" style="background: ${mapKey.color}20; border-color: ${mapKey.color}; color: ${mapKey.color};">
                                <i class="fas ${mapKey.icon}"></i>
                            </div>
                            <div class="timeline-content" style="border-left: 3px solid ${mapKey.color}30;">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div class="timeline-title" style="color: ${mapKey.color};">${mapKey.label}</div>
                                    <span class="timeline-date">${dateStr}</span>
                                </div>
                                <div class="timeline-desc">${log.description || '<span class="text-muted font-italic">Tidak ada catatan tambahan.</span>'}</div>
                                ${log.old_status && log.new_status ? `
                                <div class="mt-2">
                                    <span class="badge badge-light text-muted mr-1" style="font-size:0.65rem;">Dari: <b>${log.old_status}</b></span>
                                    <i class="fas fa-arrow-right text-muted" style="font-size:0.6rem;"></i>
                                    <span class="badge badge-light text-dark ml-1" style="font-size:0.65rem;">Ke: <b>${log.new_status}</b></span>
                                </div>` : ''}
                                <div class="timeline-user mt-2">
                                    <i class="fas fa-user-circle mr-1"></i> ${userName}
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                timelineHtml = `
                    <div class="text-center py-5">
                        <div style="width:70px;height:70px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                            <i class="fas fa-history text-muted" style="font-size:1.8rem;"></i>
                        </div>
                        <p class="font-weight-bold text-muted mb-1">Belum Ada Riwayat</p>
                        <small class="text-muted">Aktivitas pada pendaftar ini akan muncul di sini.</small>
                    </div>`;
            }
            $('#det_timeline').html(timelineHtml);

            $('#modal-detail').modal('show');
        });
    }
</script>
@endpush
