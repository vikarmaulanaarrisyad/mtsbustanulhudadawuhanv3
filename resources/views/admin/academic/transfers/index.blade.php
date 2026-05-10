@extends($layout)

@section('title', 'Mutasi & Pindah Sekolah')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-exchange-alt mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Mutasi & Pindah Sekolah
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola alur mutasi keluar dan masuk siswa secara formal dengan pencetakan dokumen standar nasional.
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

<!-- STATISTICS WIDGETS -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #e74a3b !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Mutasi Keluar</p>
                        <h2 class="font-weight-bold mb-0 text-danger counter-value">{{ $stats['total_out'] }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-danger rounded-circle p-3">
                        <i class="fas fa-sign-out-alt text-danger fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Mutasi Masuk</p>
                        <h2 class="font-weight-bold mb-0 text-success counter-value">{{ $stats['total_in'] }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-sign-in-alt text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #4e73df !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Siswa Aktif</p>
                        <h2 class="font-weight-bold mb-0 text-primary counter-value">{{ $stats['total_active'] }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-users text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: PROCESS FORM -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-danger-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-danger mb-0">
                    <span class="step-badge bg-danger mr-2">1</span> Proses Mutasi Keluar
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="formOut">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">PILIH SISWA AKTIF</label>
                        <select name="student_id" id="student_id" class="form-control select2 w-100" required>
                            <option value="">-- Cari Nama Siswa --</option>
                            @foreach(\App\Models\Student::where('is_active', true)->orderBy('nama_lengkap')->get() as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nis }}) - {{ $s->kelas_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">TANGGAL MUTASI</label>
                        <input type="date" name="exit_date" class="form-control border-2" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">SEKOLAH TUJUAN</label>
                        <input type="text" name="pindah_ke" class="form-control border-2" placeholder="Contoh: MTsN 1 Blitar" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">ALASAN PINDAH</label>
                        <textarea name="alasan_pindah" class="form-control border-2" rows="3" placeholder="Tuliskan alasan kepindahan..." required></textarea>
                    </div>
                    <button type="submit" id="btnSubmitOut" class="btn btn-danger btn-block shadow-lg font-weight-bold py-2 btn-premium">
                        <i class="fas fa-print mr-2"></i> PROSES & CETAK SURAT
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body">
                <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-info-circle text-info mr-2"></i> Petunjuk Mutasi</h6>
                <ul class="text-sm text-muted pl-3 mb-0">
                    <li class="mb-2"><b>Mutasi Keluar:</b> Gunakan form di atas untuk memproses siswa yang pindah sekolah.</li>
                    <li><b>Mutasi Masuk:</b> Tambahkan siswa melalui menu <b>Data Induk Siswa</b> dengan status <b>"Pindahan Masuk"</b>.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-pills" id="transferTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold px-4" id="out-tab" data-toggle="pill" href="#out-content" role="tab">
                                <i class="fas fa-sign-out-alt mr-2"></i> KELUAR
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold px-4" id="in-tab" data-toggle="pill" href="#in-content" role="tab">
                                <i class="fas fa-sign-in-alt mr-2"></i> MASUK
                            </a>
                        </li>
                    </ul>
                    <div class="form-group mb-0" style="width: 200px;">
                        <select id="filter_academic_year" class="form-control form-control-sm select2">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $ay->id == $currentAY->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="transferTabsContent">
                    {{-- TAB MUTASI KELUAR --}}
                    <div class="tab-pane fade show active" id="out-content" role="tabpanel">
                        <div class="table-responsive">
                            <table id="tableOut" class="table table-hover align-middle mb-0 w-100">
                                <thead class="bg-light-danger">
                                    <tr>
                                        <th width="5%" class="text-center py-3">NO</th>
                                        <th>IDENTITAS SISWA</th>
                                        <th>TUJUAN & ALASAN</th>
                                        <th>TANGGAL</th>
                                        <th width="15%" class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    {{-- TAB MUTASI MASUK --}}
                    <div class="tab-pane fade" id="in-content" role="tabpanel">
                        <div class="table-responsive">
                            <table id="tableIn" class="table table-hover align-middle mb-0 w-100">
                                <thead class="bg-light-success">
                                    <tr>
                                        <th width="5%" class="text-center py-3">NO</th>
                                        <th>IDENTITAS SISWA</th>
                                        <th>ASAL SEKOLAH</th>
                                        <th>TANGGAL MASUK</th>
                                        <th width="15%" class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-danger-thick { border-left: 5px solid #e74a3b !important; }
    
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        color: #fff; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Table Styling like Placements */
    .table thead th { border: none; padding: 1rem 0.75rem; }
    .table tbody td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    .table-hover tbody tr { transition: all 0.2s ease; border-radius: 12px; }
    .table-hover tbody tr:hover { background: #f8fbff; transform: scale(1.005); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .bg-light-danger { background: #fff5f5; color: #c53030; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
    .bg-light-success { background: #f0fff4; color: #276749; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }

    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-danger { background: #fff5f5; }
    .bg-soft-success { background: #f0fff4; }
    .bg-soft-primary { background: #ebf8ff; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    
    .nav-pills .nav-link { border-radius: 50px; color: #718096; font-size: 0.85rem; }
    .nav-pills .nav-link.active { background: #4e73df; color: white; box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3); }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let tableOut, tableIn;

    $(function() {
        $('.select2').select2();

        tableOut = $('#tableOut').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: {
                url: '{{ route("transfers.data") }}',
                data: function(d) { d.type = 'out'; d.academic_year_id = $('#filter_academic_year').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { 
                    data: 'nama_lengkap', 
                    render: function(data, type, row) { 
                        return `<div><span class="font-weight-bold text-dark d-block">${data}</span><small class="text-muted">${row.nis} | ${row.kelas}</small></div>`;
                    } 
                },
                { 
                    data: 'pindah_ke',
                    render: function(data, type, row) {
                        return `<div><span class="badge badge-soft-danger mb-1">${data}</span><br><small class="text-italic">"${row.alasan_pindah}"</small></div>`;
                    }
                },
                { data: 'tanggal' },
                { data: 'action', className: 'text-center' }
            ]
        });

        tableIn = $('#tableIn').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: {
                url: '{{ route("transfers.data") }}',
                data: function(d) { d.type = 'in'; d.academic_year_id = $('#filter_academic_year').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { 
                    data: 'nama_lengkap', 
                    render: function(data, type, row) { 
                        return `<div><span class="font-weight-bold text-dark d-block">${data}</span><small class="text-muted">${row.nis} | ${row.kelas}</small></div>`;
                    } 
                },
                { data: 'asal_sekolah' },
                { data: 'tanggal' },
                { data: 'action', className: 'text-center' }
            ]
        });

        $('#filter_academic_year').on('change', function() {
            tableOut.ajax.reload();
            tableIn.ajax.reload();
        });

        $('#formOut').on('submit', function(e) {
            e.preventDefault();
            $('#btnSubmitOut').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> MEMPROSES...');
            
            $.post('{{ route("transfers.out") }}', $(this).serialize())
                .done(res => {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message });
                    $('#formOut')[0].reset();
                    $('#student_id').val('').trigger('change');
                    tableOut.ajax.reload();
                    window.open('{{ url("transfers") }}/' + res.id + '/print', '_blank');
                })
                .fail(err => {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Terjadi kesalahan' });
                })
                .always(() => {
                    $('#btnSubmitOut').prop('disabled', false).html('<i class="fas fa-print mr-2"></i> PROSES & CETAK SURAT');
                });
        });
    });

    function undoTransfer(id) {
        Swal.fire({
            title: 'Batalkan Mutasi?',
            text: 'Data siswa akan dikembalikan ke daftar aktif.',
            icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("transfers.undo") }}', { _token: '{{ csrf_token() }}', id: id })
                    .done(res => { 
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message }); 
                        tableOut.ajax.reload(); 
                        tableIn.ajax.reload(); 
                    });
            }
        });
    }
</script>
<style>
    .badge-soft-danger { background-color: #fff5f5; color: #c53030; border: 1px solid #feb2b2; }
</style>
@endpush
