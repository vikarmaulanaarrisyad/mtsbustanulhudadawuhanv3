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
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-filter mr-2 text-indigo"></i> Parameter Seleksi
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Gelombang Pendaftaran</label>
                            <div class="input-group-premium shadow-sm">
                                <i class="fas fa-layer-group text-indigo"></i>
                                <select id="filter_phase" class="form-control select2">
                                    <option value="" disabled selected>-- Pilih Gelombang --</option>
                                    @foreach ($phases as $p)
                                        <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Jalur Penerimaan</label>
                            <div class="input-group-premium shadow-sm">
                                <i class="fas fa-route text-indigo"></i>
                                <select id="filter_type" class="form-control select2">
                                    <option value="" disabled selected>-- Pilih Jalur --</option>
                                    @foreach ($types as $t)
                                        <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button onclick="applyFilter()" class="btn btn-indigo btn-block font-weight-bold btn-premium shadow-indigo py-2">
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
    
    .btn-indigo { background: #4f46e5; color: #fff; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .shadow-indigo { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4); }

    /* Ranking Table Styling */
    #table-selection { border-collapse: separate; border-spacing: 0 10px; }
    #table-selection tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #table-selection tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #fcfaff; }
    #table-selection td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #table-selection td:first-child { border-radius: 12px 0 0 12px; }
    #table-selection td:last-child { border-radius: 0 12px 12px 0; }

    .rank-number { 
        width: 35px; height: 35px; border-radius: 50%; background: #eef2ff; color: #4f46e5; 
        display: flex; align-items: center; justify-content: center; font-weight: 900; margin: 0 auto;
    }
    .rank-1 .rank-number { background: #fef3c7; color: #d97706; box-shadow: 0 0 10px rgba(217, 119, 6, 0.2); }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #4f46e5; box-shadow: 0 0 15px rgba(79, 70, 229, 0.1); }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    $(function() {
        table = $('#table-selection').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari pendaftar...", search: "" },
            ajax: {
                url: '{{ route("ppdb.selection_data") }}',
                data: (d) => { d.phase_id = $('#filter_phase').val(); d.type_id = $('#filter_type').val(); }
            },
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    render: (data) => '<div class="rank-number ' + (data == 1 ? 'rank-1' : '') + '">' + data + '</div>'
                },
                { data: 'registration_number', render: (data) => '<span class="badge badge-light border px-3 py-2 text-indigo font-weight-bold">' + data + '</span>' },
                { 
                    data: 'nama_lengkap',
                    render: (data) => '<span class="font-weight-bold text-dark h6 mb-0">' + data + '</span>'
                },
                { 
                    data: 'selection_score', 
                    className: 'text-center',
                    render: (data) => '<span class="badge badge-soft-indigo px-3 py-2 rounded-pill font-weight-bold">' + (data || '0.00') + '</span>'
                },
                { data: 'admission_phase.phase_name', render: (data) => '<span class="small font-weight-bold text-muted">' + data + '</span>' },
                { data: 'admission_type.admission_type_name', render: (data) => '<span class="small font-weight-bold text-muted">' + data + '</span>' },
                { data: 'status_badge', className: 'text-center' },
            ],
            order: [[3, 'desc']]
        });
    });

    function applyFilter() { 
        if (!$('#filter_phase').val() || !$('#filter_type').val()) {
            Swal.fire({ icon: 'info', title: 'Parameter Belum Lengkap', text: 'Pilih Gelombang dan Jalur untuk memproses perankingan.' });
            return;
        }
        table.ajax.reload(); 
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
