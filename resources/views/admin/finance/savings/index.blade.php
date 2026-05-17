@extends($layout)

@section('title', 'Tabungan Siswa')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-piggy-bank mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Tabungan Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola simpanan dan penarikan tunai siswa secara transparan dan akuntabel {{ $isGuru ? 'untuk kelas perwalian Anda' : 'seluruh madrasah' }}.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-coins fa-8x opacity-2 shadow-icon"></i>
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
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Saldo</p>
                        <h2 class="font-weight-bold mb-0 text-primary">Rp {{ number_format($totalSavings, 0, ',', '.') }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-wallet text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Setoran Hari Ini</p>
                        <h2 class="font-weight-bold mb-0 text-success">Rp {{ number_format($totalDepositsToday, 0, ',', '.') }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-arrow-down text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Transaksi</p>
                        <h2 class="font-weight-bold mb-0 text-warning">{{ $totalTransactionsToday }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-exchange-alt text-warning fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDE: CONFIGURATION & FILTER -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <span class="step-badge mr-2">1</span> Konfigurasi & Filter
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-4">
                    <label class="text-xs font-weight-bold text-muted uppercase">Pilih Kelas</label>
                    @if(!$isGuru)
                    <select id="filter_class" class="form-control select2">
                        <option value="">Semua Kelas</option>
                        @foreach(\App\Models\ClassGroup::all() as $class)
                            <option value="{{ $class->id }}">{{ $class->kelas_lengkap }}</option>
                        @endforeach
                    </select>
                    @else
                    <div class="p-3 bg-light rounded-12 border">
                        <i class="fas fa-users text-info mr-2"></i>
                        <span class="font-weight-bold text-dark">{{ $homeroomClass->kelas_lengkap }}</span>
                    </div>
                    <input type="hidden" id="filter_class" value="{{ $homeroomClass->id }}">
                    @endif
                </div>

                <div class="form-group mb-0">
                    <label class="text-xs font-weight-bold text-muted uppercase">Pencarian Siswa</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="student_search" class="form-control border-left-0 bg-light" placeholder="Nama / NISN...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card bg-gradient-dark text-white overflow-hidden position-relative">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <h6 class="font-weight-bold mb-2">Pusat Bantuan</h6>
                <p class="text-xs opacity-7 font-weight-light mb-0">
                    Gunakan fitur ini untuk mencatat tabungan harian siswa. Pastikan nominal yang dimasukkan sudah sesuai sebelum konfirmasi.
                </p>
            </div>
            <i class="fas fa-lightbulb fa-5x position-absolute" style="bottom: -10px; right: -10px; opacity: 0.1;"></i>
        </div>
    </div>

    <!-- RIGHT SIDE: STUDENT LIST -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card h-100">
            <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1">Daftar Tabungan Siswa</h5>
                    <p class="text-xs text-muted mb-0">Hasil pencarian berdasarkan filter aktif</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle mb-0 custom-table" id="savingsTable" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th width="40" class="text-center">NO</th>
                                <th>SISWA</th>
                                <th class="text-center">KELAS</th>
                                <th class="text-right">SALDO</th>
                                <th width="150" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TRANSACTION MODAL -->
<div class="modal fade" id="modal-transaction" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ $isGuru ? route('guru.savings.store') : route('admin.savings.store') }}" method="post" id="formTransaction" class="w-full">
            @csrf
            <input type="hidden" name="student_id" id="student_id">
            <input type="hidden" name="type" id="transaction_type">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-white border-0 pt-4 px-4">
                    <h5 class="modal-title font-weight-bold text-dark" id="modalTitle">Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-15">
                        <div class="avatar-sm bg-white rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 45px; height: 45px;">
                            <i class="fas fa-user-graduate text-info"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-muted font-weight-bold uppercase d-block">Nama Siswa</span>
                            <h6 class="font-weight-bold mb-0 text-dark" id="student_name_display">-</h6>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Nominal (Rp)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white font-weight-bold border-right-0">Rp</span>
                            </div>
                            <input type="text" id="amount_mask" class="form-control form-control-lg font-weight-bold text-dark border-left-0 h3 mb-0" placeholder="0" required>
                            <input type="hidden" name="amount" id="amount_raw">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Keterangan</label>
                        <input type="text" name="description" class="form-control bg-light" placeholder="Contoh: Tabungan rutin">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-primary">
                        KONFIRMASI
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-gradient-info { background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%) !important; }
    .bg-gradient-dark { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; }
    .bg-soft-primary { background: rgba(0, 123, 255, 0.1); }
    .bg-soft-success { background: rgba(40, 167, 69, 0.1); }
    .bg-soft-warning { background: rgba(255, 193, 7, 0.1); }
    .rounded-15 { border-radius: 15px; }
    .rounded-12 { border-radius: 12px; }
    .step-badge { width: 25px; height: 25px; background: #e0f2fe; color: #0ea5e9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; }
    .bg-circle-1 { position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -100px; right: -50px; }
    .bg-circle-2 { position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; bottom: -50px; left: 10%; }
    .shadow-icon { position: absolute; top: -20px; right: -20px; }
    .premium-card { border-radius: 15px; overflow: hidden; }

    /* Custom Table Styling */
    .custom-table thead th {
        padding: 15px 10px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 800;
        color: #64748b;
        border-top: none;
    }
    .custom-table tbody td {
        padding: 18px 10px;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9;
    }
    
    /* DataTable Controls Styling */
    .dataTables_wrapper .dataTables_length select {
        border-radius: 10px;
        padding: 5px 10px;
        border-color: #e2e8f0;
        margin: 0 8px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 20px;
        padding: 8px 15px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        margin-left: 10px;
        width: 200px;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 15px;
        font-size: 12px;
        color: #94a3b8;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 15px;
    }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<script>
    let table;
    $(function() {
        table = $('#savingsTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: {
                url: '{{ $isGuru ? route("guru.savings.data") : route("admin.savings.data") }}',
                data: function(d) {
                    d.class_group_id = $('#filter_class').val();
                    d.search.value = $('#student_search').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center font-weight-bold pl-4' },
                { 
                    data: 'nama_lengkap', 
                    render: function(data, type, row) {
                        return '<div class="d-flex align-items-center"><div class="avatar-xs bg-light rounded-circle flex-shrink-0 mr-2 d-flex align-items-center justify-content-center" style="width:30px;height:30px;"><i class="fas fa-user-graduate text-muted text-[10px]"></i></div><div><span class="font-weight-bold text-dark d-block">' + data + '</span><small class="text-muted">' + (row.nisn || '-') + '</small></div></div>';
                    }
                },
                { data: 'class', className: 'text-center' },
                { 
                    data: 'balance', 
                    className: 'text-right font-weight-bold text-primary',
                    render: function(data) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                { data: 'action', searchable: false, sortable: false, className: 'text-center pr-4' },
            ]
        });

        $('#filter_class, #student_search').on('change keyup', function() {
            table.ajax.reload();
        });

        $('#formTransaction').submit(function(e) {
            e.preventDefault();
            let btn = $('#submitBtn');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MEMPROSES...');

            $.post($(this).attr('action'), $(this).serialize())
                .done(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .fail(xhr => {
                    Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                })
                .always(() => {
                    btn.prop('disabled', false).html('KONFIRMASI');
                });
        });
    });

    function transactionForm(id, name, type) {
        $('#student_id').val(id);
        $('#student_name_display').text(name);
        $('#transaction_type').val(type);
        
        // Reset nominal inputs
        $('#amount_mask').val('');
        $('#amount_raw').val('');
        
        const title = $('#modalTitle');
        const btn = $('#submitBtn');
        
        if(type === 'debit') {
            title.html('<i class="fas fa-plus-circle text-success mr-2"></i> Setor Tunai');
            btn.removeClass('btn-warning').addClass('btn-primary');
        } else {
            title.html('<i class="fas fa-minus-circle text-warning mr-2"></i> Tarik Tunai');
            btn.removeClass('btn-primary').addClass('btn-warning');
        }
        
        $('#modal-transaction').modal('show');
    }

    // Mask nominal input to Indonesian Currency format
    document.getElementById('amount_mask').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        document.getElementById('amount_raw').value = value;
        
        if (value) {
            this.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            this.value = '';
        }
    });
</script>
@endpush
