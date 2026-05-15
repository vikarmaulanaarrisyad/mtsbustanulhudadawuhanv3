@extends($layout)

@section('title', 'Tabungan Siswa')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-piggy-bank mr-2 animate__animated animate__fadeInLeft"></i> 
                            Tabungan Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola simpanan dan penarikan tunai siswa secara transparan dan akuntabel.
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

<!-- STATS CARDS -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-15 bg-white overflow-hidden group hover-translate-y">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="p-3 bg-light-info rounded-20 text-info">
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-weight-bold text-muted uppercase">Total Saldo Tabungan</span>
                        <h3 class="font-weight-black text-dark mb-0">Rp {{ number_format($totalSavings, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="progress progress-xs mb-0" style="height: 4px;">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-15 bg-white overflow-hidden group hover-translate-y">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="p-3 bg-light-success rounded-20 text-success">
                        <i class="fas fa-arrow-down fa-2x"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-weight-bold text-muted uppercase">Setoran Hari Ini</span>
                        <h3 class="font-weight-black text-dark mb-0">Rp {{ number_format($totalDepositsToday, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="progress progress-xs mb-0" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-15 bg-white overflow-hidden group hover-translate-y">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="p-3 bg-light-warning rounded-20 text-warning">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-weight-bold text-muted uppercase">Transaksi Hari Ini</span>
                        <h3 class="font-weight-black text-dark mb-0">{{ $totalTransactionsToday }} Transaksi</h3>
                    </div>
                </div>
                <div class="progress progress-xs mb-0" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Tabungan Siswa</h4>
                        <p class="text-muted text-sm mb-0">Klik tombol aksi untuk memproses transaksi</p>
                    </div>
                    @if(!$isGuru)
                    <div class="d-flex align-items-center">
                        <select id="filter_class" class="form-control select2 mr-3" style="min-width: 200px;">
                            <option value="">Semua Kelas</option>
                            @foreach(\App\Models\ClassGroup::all() as $class)
                                <option value="{{ $class->id }}">{{ $class->kelas_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="d-flex align-items-center">
                        <span class="badge badge-light border px-3 py-2 text-info font-weight-bold">
                            Kelas: {{ $homeroomClass->kelas_lengkap }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="savingsTable" style="width:100%">
                        <thead class="bg-light text-uppercase">
                            <tr>
                                <th width="50">NO</th>
                                <th>NISN</th>
                                <th>NAMA LENGKAP</th>
                                <th>KELAS</th>
                                <th>SALDO TABUNGAN</th>
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
<div class="modal fade animate__animated animate__fadeInDown" id="modal-transaction" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ $isGuru ? route('guru.savings.store') : route('admin.savings.store') }}" method="post" id="formTransaction">
            @csrf
            <input type="hidden" name="student_id" id="student_id">
            <input type="hidden" name="type" id="transaction_type">
            <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-gradient-info text-white border-0 py-4" id="modalHeader">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-exchange-alt mr-2"></i> Transaksi Tabungan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div class="student-info-box mb-4 p-3 bg-white rounded-15 shadow-sm border-left border-info" style="border-left-width: 5px !important;">
                        <span class="text-xs text-muted font-weight-bold uppercase d-block mb-1">Nama Siswa</span>
                        <h5 class="font-weight-black text-dark mb-0" id="student_name_display">-</h5>
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Jumlah Transaksi (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-money-bill-wave text-info"></i>
                            <input type="number" name="amount" class="form-control font-weight-bold h3 mb-0 py-3" placeholder="0" min="1000" required>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Keterangan (Opsional)</label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-edit"></i>
                            <input type="text" name="description" class="form-control" placeholder="Contoh: Tabungan Mingguan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="submitBtn" class="btn btn-info rounded-pill px-5 font-weight-bold shadow-info-light text-white">
                        <i class="fas fa-check-circle mr-2"></i> PROSES TRANSAKSI
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-gradient-info { background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%) !important; }
    .bg-light-info { background: #e0f2fe; }
    .bg-light-success { background: #f0fdf4; }
    .bg-light-warning { background: #fffbeb; }
    .shadow-info-light { box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4); }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    .bg-circle-1 { position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -100px; right: -50px; }
    .bg-circle-2 { position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; bottom: -50px; left: 10%; }
    .font-weight-black { font-weight: 900; }
    .hover-translate-y:hover { transform: translateY(-5px); transition: 0.3s; }
    
    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #0ea5e9; box-shadow: 0 0 15px rgba(14, 165, 233, 0.1); }
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
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center font-weight-bold' },
                { data: 'nisn', className: 'font-weight-bold' },
                { data: 'nama_lengkap', className: 'font-weight-bold text-dark' },
                { data: 'class', className: 'text-center' },
                { 
                    data: 'balance', 
                    render: function(data) {
                        return '<h6 class="font-weight-black text-info mb-0">Rp ' + new Intl.NumberFormat('id-ID').format(data) + '</h6>';
                    }
                },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });

        $('#filter_class').change(function() {
            table.ajax.reload();
        });

        $('#formTransaction').submit(function(e) {
            e.preventDefault();
            let btn = $('#submitBtn');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MEMPROSES...');

            $.post($(this).attr('action'), $(this).serialize())
                .done(res => {
                    Swal.fire('Berhasil', res.message, 'success');
                    $('#modal-transaction').modal('hide');
                    table.ajax.reload(() => {
                        window.location.reload(); // Reload for stats
                    });
                })
                .fail(xhr => {
                    Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                })
                .always(() => {
                    btn.prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> PROSES TRANSAKSI');
                });
        });
    });

    function transactionForm(id, name, type) {
        $('#student_id').val(id);
        $('#student_name_display').text(name);
        $('#transaction_type').val(type);
        
        if(type === 'debit') {
            $('#modalHeader').removeClass('bg-gradient-warning').addClass('bg-gradient-info');
            $('#modalHeader .modal-title').html('<i class="fas fa-plus-circle mr-2"></i> Setor Tunai (Deposit)');
            $('.student-info-box').removeClass('border-warning').addClass('border-info');
        } else {
            $('#modalHeader').removeClass('bg-gradient-info').addClass('bg-gradient-warning');
            $('#modalHeader .modal-title').html('<i class="fas fa-minus-circle mr-2"></i> Tarik Tunai (Withdrawal)');
            $('.student-info-box').removeClass('border-info').addClass('border-warning');
        }
        
        $('#modal-transaction').modal('show');
    }
</script>
@endpush
