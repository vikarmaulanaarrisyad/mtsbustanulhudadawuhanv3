@extends($layout)

@section('title', 'Proses Seleksi PPDB')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-trophy mr-2 animate__animated animate__fadeInLeft"></i> 
                            Penetapan Seleksi & Perankingan
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tentukan kelulusan pendaftar berdasarkan skor prestasi, zonasi, dan kuota yang tersedia secara otomatis.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-balance-scale fa-8x opacity-1 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-shape-1"></div>
            <div class="bg-shape-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- SELECTION CONTROL CENTER -->
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="filter-title-icon mr-3">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-black mb-0 text-dark" style="font-size: 1rem;">Parameter Seleksi</h5>
                        <p class="text-muted mb-0" style="font-size: 11px;">Tentukan gelombang & jalur untuk menampilkan peringkat pendaftar</p>
                    </div>
                </div>
                <div class="row align-items-end">
                    <!-- FILTER GELOMBANG -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="filter-label">Gelombang Pendaftaran</label>
                        <div class="filter-select-box">
                            <div class="filter-select-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <select id="filter_phase" class="filter-select">
                                <option value="">Semua Gelombang</option>
                                @foreach ($phases as $p)
                                    <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- FILTER JALUR -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="filter-label">Jalur Penerimaan</label>
                        <div class="filter-select-box">
                            <div class="filter-select-icon">
                                <i class="fas fa-route"></i>
                            </div>
                            <select id="filter_type" class="filter-select">
                                <option value="">Semua Jalur</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- TOMBOL FILTER -->
                    <div class="col-md-4">
                        <button onclick="applyFilter()" class="btn btn-indigo btn-block font-weight-bold shadow-indigo" style="padding: 13px 20px; border-radius: 12px; font-size: 13px; letter-spacing: 0.4px;">
                            <i class="fas fa-search mr-2"></i> TAMPILKAN PERINGKAT
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RANKING BOARD -->
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Leaderboard Pendaftar</h4>
                        <p class="text-muted text-sm mb-0">Daftar peringkat berdasarkan skor validasi berkas</p>
                    </div>
                    <div class="d-flex flex-wrap" style="gap:10px;">
                        <button onclick="confirmProcess()" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-primary">
                            <i class="fas fa-check-double mr-1"></i> TETAPKAN LULUS
                        </button>
                        <button onclick="confirmBulkMove()" class="btn btn-success rounded-pill px-4 font-weight-bold shadow-success">
                            <i class="fas fa-user-check mr-1"></i> PINDAHKAN KE DATA INDUK
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="alert alert-soft-indigo rounded-20 border-0 shadow-sm mb-4 p-3 animate__animated animate__fadeIn">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-indigo rounded-circle d-flex align-items-center justify-content-center mr-3 text-white">
                            <i class="fas fa-info"></i>
                        </div>
                        <div class="small">
                            Peringkat ini bersifat dinamis. Gunakan tombol <strong>"Tetapkan Lulus"</strong> untuk memproses status pendaftar sesuai kuota.
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table-selection" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="80px" class="text-center py-3">RANK</th>
                                <th>NO. PENDAFTARAN</th>
                                <th>NAMA LENGKAP</th>
                                <th class="text-center">SKOR SELEKSI</th>
                                <th>GELOMBANG</th>
                                <th>JALUR</th>
                                <th class="text-center">STATUS</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- LOADING OVERLAY -->
                <div id="table-loading-overlay">
                    <div class="spinner-ring"></div>
                    <p>Memuat data peringkat...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* PREMIUM UI STYLES */
    .bg-gradient-indigo-dark { background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%) !important; }
    .bg-shape-1 { position: absolute; width: 400px; height: 400px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; top: -150px; right: -100px; }
    .bg-shape-2 { position: absolute; width: 200px; height: 200px; background: rgba(99, 102, 241, 0.05); border-radius: 50%; bottom: -50px; left: 10%; }
    
    .premium-card { border-radius: 20px; overflow: hidden; }
    .bg-light-indigo { background: #f8fafc; color: #64748b; font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px; }
    .alert-soft-indigo { background: #eef2ff; color: #4338ca; border-left: 5px solid #4f46e5; }
    .text-indigo { color: #4f46e5 !important; }
    
    .btn-indigo { background: #4f46e5; color: #fff; border: none; }
    .btn-indigo:hover { background: #4338ca; color: #fff; transform: translateY(-1px); }
    .shadow-indigo { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35); }

    /* Filter Title Icon */
    .filter-title-icon {
        width: 44px; height: 44px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 17px;
        box-shadow: 0 4px 12px rgba(79,70,229,0.3);
    }

    /* Filter Label */
    .filter-label {
        display: block;
        font-size: 11px; font-weight: 700;
        color: #64748b; text-transform: uppercase;
        letter-spacing: 0.7px; margin-bottom: 8px;
    }

    /* Filter Select Box - Flex icon + select */
    .filter-select-box {
        display: flex;
        align-items: center;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        overflow: hidden;
        transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
    }
    .filter-select-box:focus-within {
        border-color: #4f46e5;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    .filter-select-icon {
        display: flex; align-items: center; justify-content: center;
        width: 46px; height: 100%; min-height: 48px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 14px;
        flex-shrink: 0;
        border-right: 2px solid #e2e8f0;
        transition: background 0.2s;
    }
    .filter-select-box:focus-within .filter-select-icon {
        background: #e0e7ff;
        border-right-color: #c7d2fe;
    }
    .filter-select {
        flex: 1;
        border: none;
        background: transparent;
        padding: 12px 14px;
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 38px;
    }
    .filter-select:focus { outline: none; }
    .filter-select option { font-weight: 500; color: #334155; }

    /* Loading Overlay */
    #table-loading-overlay {
        display: none;
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(3px);
        border-radius: 0 0 20px 20px;
        z-index: 10;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 14px;
    }
    #table-loading-overlay .spinner-ring {
        width: 52px; height: 52px;
        border: 4px solid #e0e7ff;
        border-top-color: #4f46e5;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #table-loading-overlay p {
        font-size: 13px; font-weight: 700;
        color: #4f46e5; margin: 0; letter-spacing: 0.5px;
    }
    .table-wrapper { position: relative; }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    $(function() {
        // Auto-select gelombang pertama jika ada
        if ($('#filter_phase option').length > 1) {
            $('#filter_phase').val($('#filter_phase option:nth-child(2)').val());
        }

        table = $('#table-selection').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            deferLoading: 0, // Jangan load data sampai filter diterapkan
            language: {
                searchPlaceholder: "Cari pendaftar...",
                search: "",
                emptyTable: '<div class="text-center py-5"><i class="fas fa-filter fa-3x text-muted mb-3 d-block opacity-50"></i><p class="text-muted font-weight-bold">Pilih filter dan klik TAMPILKAN PERINGKAT</p></div>',
                zeroRecords: '<div class="text-center py-5"><i class="fas fa-search fa-3x text-muted mb-3 d-block opacity-50"></i><p class="text-muted font-weight-bold">Tidak ada data pendaftar untuk filter ini</p></div>',
            },
            ajax: {
                url: '{{ route("ppdb.selection_data") }}',
                data: (d) => {
                    d.phase_id = $('#filter_phase').val();
                    d.type_id  = $('#filter_type').val();
                }
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    render: (data) => '<div class="rank-number ' + (data == 1 ? 'rank-1' : '') + '">' + data + '</div>'
                },
                {
                    data: 'registration_number',
                    render: (data) => '<span class="badge badge-light border px-3 py-2 text-indigo font-weight-bold">' + (data || '-') + '</span>'
                },
                {
                    data: 'nama_lengkap',
                    render: (data) => '<span class="font-weight-bold text-dark h6 mb-0">' + (data || '-') + '</span>'
                },
                {
                    data: 'selection_score',
                    className: 'text-center',
                    render: (data) => '<span class="badge badge-soft-indigo px-3 py-2 rounded-pill font-weight-bold">' + (data || '0.00') + '</span>'
                },
                {
                    data: 'admission_phase',
                    render: (data) => '<span class="small font-weight-bold text-muted">' + (data ? data.phase_name : '-') + '</span>'
                },
                {
                    data: 'admission_type',
                    render: (data) => '<span class="small font-weight-bold text-muted">' + (data ? data.admission_type_name : '-') + '</span>'
                },
                { data: 'status_badge', className: 'text-center' },
            ],
            order: [[3, 'desc']]
        });
    });

    function applyFilter() {
        // Tampilkan loading overlay
        const overlay = document.getElementById('table-loading-overlay');
        overlay.style.display = 'flex';

        // Reload table, sembunyikan overlay saat selesai
        table.ajax.reload(function() {
            overlay.style.display = 'none';
        });
    }

    function confirmProcess() {
        let phaseId = $('#filter_phase').val();
        let typeId = $('#filter_type').val();
        if (!phaseId || !typeId) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Silakan pilih Gelombang dan Jalur terlebih dahulu.' }); return; }

        Swal.fire({
            title: 'Proses Penetapan Lulus',
            text: `Sistem akan meluluskan pendaftar peringkat teratas sesuai kuota yang tersedia pada gelombang & jalur terpilih. Lanjutkan?`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#4f46e5', confirmButtonText: 'Ya, Tetapkan Lulus!'
        }).then((res) => { if (res.isConfirmed) executeProcess(); });
    }

    function executeProcess() {
        Swal.fire({ title: 'Memproses Seleksi...', didOpen: () => Swal.showLoading() });
        $.post('{{ route("ppdb.process_selection") }}', { _token: '{{ csrf_token() }}', phase_id: $('#filter_phase').val(), type_id: $('#filter_type').val() })
        .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message }); table.ajax.reload(); })
        .fail(xhr => { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' }); });
    }

    function confirmBulkMove() {
        let phaseId = $('#filter_phase').val();
        let typeId = $('#filter_type').val();
        if (!phaseId || !typeId) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih parameter seleksi terlebih dahulu.' }); return; }

        let classOptions = '';
        @foreach($classGroups as $cg)
            classOptions += `<option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>`;
        @endforeach

        Swal.fire({
            title: 'Pindahkan ke Data Induk',
            html: `<div class="text-left mb-3"><p class="small text-muted">Pilih kelas tujuan untuk seluruh pendaftar yang telah Lulus/Daftar Ulang:</p><select id="swal_class_id" class="form-control rounded-pill border-2 px-3">${classOptions}</select></div>`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#10b981', confirmButtonText: 'Iya, Pindahkan!',
            preConfirm: () => { const id = Swal.getPopup().querySelector('#swal_class_id').value; if (!id) Swal.showValidationMessage('Pilih kelas!'); return { id: id }; }
        }).then((res) => { if (res.isConfirmed) executeBulkMove(res.value.id); });
    }

    function executeBulkMove(classId) {
        Swal.fire({ title: 'Memproses Pemindahan...', didOpen: () => Swal.showLoading() });
        $.post('{{ route("ppdb.bulk_move_to_student") }}', { _token: '{{ csrf_token() }}', phase_id: $('#filter_phase').val(), type_id: $('#filter_type').val(), class_group_id: classId })
        .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message }); table.ajax.reload(); })
        .fail(xhr => { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' }); });
    }
</script>
@endpush
