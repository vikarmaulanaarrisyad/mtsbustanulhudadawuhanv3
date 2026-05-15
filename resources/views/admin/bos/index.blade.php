@extends($layout)

@section('title', 'Manajemen Dana BOS')
@section('subtitle', 'Keuangan')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-success overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-hand-holding-usd mr-2 animate__animated animate__fadeInLeft"></i> 
                            Pengelolaan Dana BOS
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pantau saldo, catat pendapatan, dan kelola realisasi anggaran (e-RKAM) secara transparan dan akuntabel.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-chart-line fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS (GLASSMORPHISM STYLE) -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Pendapatan</p>
                        <h2 class="font-weight-bold mb-0 text-success">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-plus-circle text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Pengeluaran</p>
                        <h2 class="font-weight-bold mb-0 text-danger">Rp {{ number_format($stats['total_expenditure'], 0, ',', '.') }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-danger rounded-circle p-3">
                        <i class="fas fa-minus-circle text-danger fa-lg"></i>
                    </div>
                </div>
                @php
                    $percentage = $stats['total_income'] > 0 ? ($stats['total_expenditure'] / $stats['total_income']) * 100 : 0;
                @endphp
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-danger" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Sisa Saldo</p>
                        <h2 class="font-weight-bold mb-0 text-primary">Rp {{ number_format($stats['balance'], 0, ',', '.') }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-wallet text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-primary" style="width: {{ 100 - $percentage }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: FILTERS & TOOLS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <!-- CONFIGURATION CARD -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-filter mr-2 text-info"></i> Filter Data
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Tahun Pelajaran</label>
                    <select id="filter_academic_year" class="form-control select2">
                        <option value="">Semua Tahun</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Jenjang Madrasah</label>
                    <select id="filter_level" class="form-control select2">
                        <option value="">Semua Jenjang</option>
                        <option value="MI">MI</option>
                        <option value="MTs">MTs</option>
                        <option value="MA">MA</option>
                    </select>
                </div>
                <button type="button" onclick="refreshTables()" class="btn btn-info btn-block shadow-sm font-weight-bold py-2 btn-premium">
                    <i class="fas fa-sync-alt mr-2"></i> PERBARUI TAMPILAN
                </button>
            </div>
        </div>


        <!-- ACTIONS CARD -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-primary mb-0">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Data
                </h5>
            </div>
            <div class="card-body pt-0">
                <button onclick="addIncome()" class="btn btn-success btn-block shadow-sm font-weight-bold py-2 mb-2 btn-premium">
                    <i class="fas fa-plus-circle mr-2"></i> CATAT PENDAPATAN
                </button>
                <button onclick="addExpenditure()" class="btn btn-danger btn-block shadow-sm font-weight-bold py-2 btn-premium">
                    <i class="fas fa-minus-circle mr-2"></i> CATAT PENGELUARAN
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: DATA TABLES -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white p-0">
                <ul class="nav nav-tabs nav-justified premium-tabs" id="bosTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active py-3 font-weight-bold" id="income-tab" data-toggle="tab" href="#income" role="tab">
                            <i class="fas fa-long-arrow-alt-up text-success mr-1"></i> PENDAPATAN
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 font-weight-bold" id="expenditure-tab" data-toggle="tab" href="#expenditure" role="tab">
                            <i class="fas fa-long-arrow-alt-down text-danger mr-1"></i> PENGELUARAN
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    <!-- INCOME TAB -->
                    <div class="tab-pane fade show active" id="income" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="table-income" style="width:100%">
                                <thead class="bg-light-info text-uppercase">
                                    <tr>
                                        <th width="50px" class="text-center">NO</th>
                                        <th>TANGGAL</th>
                                        <th>JENJANG</th>
                                        <th>SUMBER / KETERANGAN</th>
                                        <th>JUMLAH</th>
                                        <th width="80px">AKSI</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <!-- EXPENDITURE TAB -->
                    <div class="tab-pane fade" id="expenditure" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="table-expenditure" style="width:100%">
                                <thead class="bg-light-info text-uppercase">
                                    <tr>
                                        <th width="50px" class="text-center">NO</th>
                                        <th>NO. BUKTI</th>
                                        <th>REALISASI</th>
                                        <th>KATEGORI</th>
                                        <th>JUMLAH</th>
                                        <th width="100px">AKSI</th>
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

<!-- MODALS (SAME AS BEFORE) -->
<!-- MODAL INCOME -->
<div class="modal fade" id="modal-income" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold" id="income-title">Catat Pendapatan BOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-income">
                @csrf
                <input type="hidden" name="id" id="income-id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase">Tahun Pelajaran</label>
                                <select name="academic_year_id" class="form-control rounded-pill border-2" required>
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase">Jenjang</label>
                                <select name="level" class="form-control rounded-pill border-2" required>
                                    <option value="MI">MI</option>
                                    <option value="MTs">MTs</option>
                                    <option value="MA">MA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold uppercase">Tanggal Terima</label>
                        <input type="date" name="date" class="form-control rounded-pill border-2" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold uppercase">Sumber Pendapatan</label>
                        <input type="text" name="source" class="form-control rounded-pill border-2" placeholder="Contoh: BOS Reguler Tahap 1" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Jumlah (Rp)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-2" style="border-radius: 50px 0 0 50px;">Rp</span>
                            </div>
                            <input type="text" id="income_amount_display" class="form-control border-2" style="border-radius: 0 50px 50px 0;" placeholder="0" required onkeyup="formatRupiah(this, '#income_amount')">
                            <input type="hidden" name="amount" id="income_amount">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold uppercase">Keterangan</label>
                        <textarea name="description" class="form-control border-2" rows="2" style="border-radius: 15px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-success btn-block rounded-pill py-2 font-weight-bold">SIMPAN PENDAPATAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EXPENDITURE -->
<div class="modal fade" id="modal-expenditure" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold" id="expenditure-title">Catat Pengeluaran BOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-expenditure">
                @csrf
                <input type="hidden" name="id" id="expenditure-id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase text-dark">Pencarian Jenis Belanja</label>
                                <select id="select-expense-type" class="form-control select2" style="width: 100%;">
                                    <option value="">Cari Jenis Belanja...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 bg-light p-3" style="border-radius: 15px; margin: 0 1px;">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-3 text-xs font-weight-bold text-muted">KATEGORI</div>
                                <div class="col-9 text-xs">
                                    <span id="display-kode-kate" class="badge badge-secondary"></span> 
                                    <span id="display-kategori" class="font-weight-bold"></span>
                                    <input type="hidden" name="kode_kate" id="exp_kode_kate_val">
                                    <input type="hidden" name="kategori" id="exp_kategori_val">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-xs font-weight-bold text-muted">JENIS</div>
                                <div class="col-9 text-xs">
                                    <span id="display-kode-jenis" class="badge badge-info"></span> 
                                    <span id="display-jenis" class="text-info font-weight-bold"></span>
                                    <input type="hidden" name="kode_jenis" id="exp_kode_jenis_val">
                                    <input type="hidden" name="jenis" id="exp_jenis_val">
                                    <input type="hidden" name="deskripsi" id="exp_deskripsi_val">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase text-dark">Pencarian RKAM (Cari Sub Kegiatan)</label>
                                <select id="select-rkam" class="form-control select2" style="width: 100%;">
                                    <option value="">Cari Sub Kegiatan...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 bg-light p-3" style="border-radius: 15px; margin: 0 1px;">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-3 text-xs font-weight-bold text-muted">SNP</div>
                                <div class="col-9 text-xs">
                                    <span id="display-kode-snp" class="badge badge-secondary"></span> 
                                    <span id="display-snp" class="font-weight-bold"></span>
                                    <input type="hidden" name="kode_snp" id="exp_kode_snp_val">
                                    <input type="hidden" name="snp" id="exp_snp_val">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-3 text-xs font-weight-bold text-muted">KEGIATAN</div>
                                <div class="col-9 text-xs">
                                    <span id="display-kode-kegiatan" class="badge badge-info"></span> 
                                    <span id="display-nama-kegiatan"></span>
                                    <input type="hidden" name="kode_kegiatan" id="exp_kode_kegiatan_val">
                                    <input type="hidden" name="nama_kegiatan" id="exp_nama_kegiatan_val">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-xs font-weight-bold text-muted">SUB KEGIATAN</div>
                                <div class="col-9 text-xs">
                                    <span id="display-kode-sub-kegiatan" class="badge badge-primary"></span> 
                                    <span id="display-sub-kegiatan" class="text-primary font-weight-bold"></span>
                                    <input type="hidden" name="kode_sub_kegiatan" id="exp_kode_sub_kegiatan_val">
                                    <input type="hidden" name="sub_kegiatan" id="exp_sub_kegiatan_val">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase">Tahun Pelajaran</label>
                                <select name="academic_year_id" class="form-control rounded-pill border-2" required>
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase">Jenjang</label>
                                <select name="level" class="form-control rounded-pill border-2" required>
                                    <option value="MI">MI</option>
                                    <option value="MTs" selected>MTs</option>
                                    <option value="MA">MA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase">No. Bukti</label>
                                <input type="text" name="receipt_number" class="form-control rounded-pill border-2" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase text-info">Kategori Kegiatan</label>
                                <select name="category" id="select-activity" class="form-control select2" required style="width: 100%;">
                                    <option value="">Cari/Pilih Kategori...</option>
                                </select>
                                <div id="display-activity-box" class="mt-2 bg-light p-2 d-none" style="border-radius: 10px; border: 1px dashed #ced4da;">
                                    <div class="text-xs font-weight-bold text-info">
                                        <span id="display-activity-code" class="badge badge-info mr-1"></span>
                                        <span id="display-activity-name"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase">Tgl Nota</label>
                                <input type="date" name="noted_at" class="form-control rounded-pill border-2" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold uppercase">Tgl Realisasi</label>
                                <input type="date" name="realized_at" class="form-control rounded-pill border-2" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Jumlah (Rp)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-2" style="border-radius: 50px 0 0 50px;">Rp</span>
                            </div>
                            <input type="text" id="exp_amount_display" class="form-control border-2" style="border-radius: 0 50px 50px 0;" placeholder="0" required onkeyup="formatRupiah(this, '#exp_amount')">
                            <input type="hidden" name="amount" id="exp_amount">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold uppercase text-dark">Pencarian Komponen Biaya</label>
                        <select id="select-item" class="form-control select2 mb-2" style="width: 100%;">
                            <option value="">Cari Komponen Biaya...</option>
                        </select>
                        <div class="row mb-3 bg-light p-3" style="border-radius: 15px; margin: 0 1px;">
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <div class="col-3 text-xs font-weight-bold text-muted">KOMPONEN</div>
                                    <div class="col-9 text-xs">
                                        <span id="display-item-code" class="badge badge-secondary"></span> 
                                        <span id="display-item-name" class="font-weight-bold"></span>
                                        <input type="hidden" name="item_name" id="exp_item_name_val">
                                        <input type="hidden" name="item_code" id="exp_item_code_val">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 text-xs font-weight-bold text-muted">SPESIFIKASI</div>
                                    <div class="col-9 text-xs">
                                        <span id="display-item-spec" class="text-muted italic"></span>
                                        <input type="hidden" name="item_specification" id="exp_item_spec_val">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 text-xs font-weight-bold text-muted">SATUAN</div>
                                    <div class="col-3 text-xs font-weight-bold text-primary">
                                        <span id="display-item-unit"></span>
                                        <input type="hidden" name="item_unit" id="exp_item_unit_val">
                                        <input type="hidden" name="item_payment_type" id="exp_item_payment_type_val">
                                    </div>
                                    <div class="col-3 text-xs font-weight-bold text-muted text-right">ESTIMASI</div>
                                    <div class="col-3 text-xs font-weight-bold text-success text-right">
                                        Rp <span id="display-item-price-1">0</span>
                                        <input type="hidden" id="exp_item_price_1_val" name="item_price_1">
                                        <input type="hidden" id="exp_item_price_2_val" name="item_price_2">
                                        <input type="hidden" id="exp_item_price_3_val" name="item_price_3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <textarea name="description" id="exp_description" class="form-control border-2" rows="2" style="border-radius: 15px;" required placeholder="Keterangan Keperluan..."></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold uppercase">Penerima</label>
                        <input type="text" name="receiver" class="form-control rounded-pill border-2" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-danger btn-block rounded-pill py-2 font-weight-bold">SIMPAN PENGELUARAN</button>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
    /* PREMIUM THEMES & EFFECTS */
    .bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #1c7430 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Card Styling */
    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-success-thick { border-left: 5px solid #28a745 !important; }
    .border-left-primary-thick { border-left: 5px solid #007bff !important; }

    /* Tabs Styling */
    .premium-tabs { border-bottom: none; background: #f8fafc; padding: 0 10px; }
    .premium-tabs .nav-link { border: none; color: #64748b; transition: all 0.3s; margin-right: 5px; border-radius: 10px 10px 0 0; }
    .premium-tabs .nav-link.active { background: #fff !important; color: #28a745 !important; box-shadow: 0 -5px 15px rgba(0,0,0,0.02); }
    #expenditure-tab.active { color: #dc3545 !important; }

    /* Table Styling - SEPARATE ROWS LIKE PLACEMENT */
    .table { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; background: transparent !important; }
    .table thead th { border: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; padding: 1rem; background: transparent !important; }
    .table tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .table tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transform: scale(1.005);
    }
    .table td { border: none; vertical-align: middle; padding: 1.5rem 1rem; }
    .table td:first-child { border-radius: 12px 0 0 12px; }
    .table td:last-child { border-radius: 0 12px 12px 0; }

    /* Soft UI Components */
    .icon-shape { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-danger { background: #ffebee; }
    .bg-soft-primary { background: #e3f2fd; }
    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); }
    
    .select2-container--bootstrap4 .select2-selection--single {
        border-radius: 50px !important;
        height: calc(2.25rem + 2px) !important;
        border: 2px solid #dee2e6 !important;
    }

    /* Force Select2 Dropdown Downwards */
    .select2-container--open .select2-dropdown {
        border-radius: 15px !important;
        border: 2px solid #dee2e6 !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        margin-top: 5px !important;
        overflow: hidden !important;
    }
    .select2-dropdown--above {
        margin-top: 40px !important; /* Hack to push it down if it tries to go up */
    }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let tableIncome, tableExpenditure;

    $(function() {
        tableIncome = $('#table-income').DataTable({
            processing: true, serverSide: true,
            ajax: {
                url: "{{ route('admin.bos.income.data') }}",
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.level = $('#filter_level').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'date', name: 'date'},
                {data: 'level', name: 'level', render: d => `<span class="badge badge-light border px-3 py-1">${d}</span>`},
                {data: 'source', name: 'source'},
                {data: 'amount', name: 'amount', className: 'font-weight-bold text-success'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ]
        });

        tableExpenditure = $('#table-expenditure').DataTable({
            processing: true, serverSide: true,
            ajax: {
                url: "{{ route('admin.bos.expenditure.data') }}",
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.level = $('#filter_level').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'receipt_number', name: 'receipt_number', render: d => `<span class="badge badge-dark px-2">${d}</span>`},
                {data: 'realized_at', name: 'realized_at'},
                {data: 'category', name: 'category', render: d => `<small class="font-weight-bold">${d}</small>`},
                {data: 'amount', name: 'amount', className: 'font-weight-bold text-danger'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ]
        });

        $('#filter_academic_year, #filter_level').select2({ theme: 'bootstrap4' });

        // Global Force Dropdown Below
        $(document).on('select2:open', function() {
            setTimeout(function() {
                $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
            }, 0);
        });

        $('#filter_academic_year, #filter_level').on('change', function() {
            refreshTables();
        });

        $('#select-activity').select2({
            theme: 'bootstrap4',
            placeholder: 'Cari/Pilih Kategori...',
            minimumInputLength: 0,
            dropdownParent: $('#modal-expenditure'),
            ajax: {
                url: "{{ route('admin.bos.search_activity') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { search: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.name,
                                text: item.code + ' - ' + item.name,
                                data: item
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            let item = e.params.data.data;
            $('#display-activity-box').removeClass('d-none');
            $('#display-activity-code').text(item.code);
            $('#display-activity-name').text(item.name);
        });

        $('#form-expenditure [name="level"]').on('change', function() {
            updateReceiptNumber();
        });

        $('#select-rkam').select2({
            theme: 'bootstrap4',
            placeholder: 'Cari Sub Kegiatan...',
            minimumInputLength: 0,
            dropdownParent: $('#modal-expenditure'),
            ajax: {
                url: "{{ route('admin.bos.search_rkam') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { search: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.kode_sub_kegiatan + ' - ' + item.sub_kegiatan,
                                data: item
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            let item = e.params.data.data;
            $('#display-kode-snp').text(item.kode_snp);
            $('#display-snp').text(item.snp);
            $('#display-kode-kegiatan').text(item.kode_kegiatan);
            $('#display-nama-kegiatan').text(item.nama_kegiatan);
            $('#display-kode-sub-kegiatan').text(item.kode_sub_kegiatan);
            $('#display-sub-kegiatan').text(item.sub_kegiatan);

            $('#exp_kode_snp_val').val(item.kode_snp);
            $('#exp_snp_val').val(item.snp);
            $('#exp_kode_kegiatan_val').val(item.kode_kegiatan);
            $('#exp_nama_kegiatan_val').val(item.nama_kegiatan);
            $('#exp_kode_sub_kegiatan_val').val(item.kode_sub_kegiatan);
            $('#exp_sub_kegiatan_val').val(item.sub_kegiatan);
        });

        $('#select-expense-type').select2({
            theme: 'bootstrap4',
            placeholder: 'Cari Jenis Belanja...',
            minimumInputLength: 0,
            dropdownParent: $('#modal-expenditure'),
            ajax: {
                url: "{{ route('admin.bos.search_expense_type') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { search: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.jenis,
                                data: item
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            let item = e.params.data.data;
            $('#display-kode-kate').text(item.kode_kate);
            $('#display-kategori').text(item.kategori);
            $('#display-kode-jenis').text(item.kode_jenis);
            $('#display-jenis').text(item.jenis);

            $('#exp_kode_kate_val').val(item.kode_kate);
            $('#exp_kategori_val').val(item.kategori);
            $('#exp_kode_jenis_val').val(item.kode_jenis);
            $('#exp_jenis_val').val(item.jenis);
            $('#exp_deskripsi_val').val(item.deskripsi);
            
            // Legacy mapping for compatibility if needed
            $('#exp_category_val').val(item.kategori);
            $('#exp_type_val').val(item.jenis);
        });

        $('#select-item').select2({
            theme: 'bootstrap4',
            placeholder: 'Cari Komponen Biaya...',
            minimumInputLength: 0,
            dropdownParent: $('#modal-expenditure'),
            ajax: {
                url: "{{ route('admin.bos.search_item') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { search: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.name + (item.spesifikasi ? ' (' + item.spesifikasi + ')' : ''),
                                data: item
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            let item = e.params.data.data;
            $('#display-item-code').text(item.code);
            $('#display-item-name').text(item.name);
            $('#display-item-spec').text(item.spesifikasi || '-');
            $('#display-item-unit').text(item.satuan);
            $('#display-item-price-1').text(new Intl.NumberFormat('id-ID').format(item.harga_1));

            $('#exp_item_name_val').val(item.name);
            $('#exp_item_code_val').val(item.code);
            $('#exp_item_spec_val').val(item.spesifikasi);
            $('#exp_item_unit_val').val(item.satuan);
            $('#exp_item_payment_type_val').val(item.jenis_pemb);
            $('#exp_item_price_1_val').val(item.harga_1);
            $('#exp_item_price_2_val').val(item.harga_2);
            $('#exp_item_price_3_val').val(item.harga_3);

            $('#exp_amount').val(item.harga_1);
            $('#exp_amount_display').val(new Intl.NumberFormat('id-ID').format(item.harga_1));
            
            let current = $('#exp_description').val();
            let separator = current ? '\n' : '';
            $('#exp_description').val(current + separator + item.name);
        });
    });

    function refreshTables() {
        tableIncome.ajax.reload();
        tableExpenditure.ajax.reload();
    }

    function addIncome() {
        $('#form-income')[0].reset();
        $('#income-id').val('');
        $('#income_amount').val('');
        $('#income-title').text('Catat Pendapatan BOS');
        $('#modal-income').modal('show');
    }

    function addExpenditure() {
        $('#form-expenditure')[0].reset();
        $('#expenditure-id').val('');
        $('#exp_amount').val('');
        $('#expenditure-title').text('Catat Pengeluaran BOS');
        updateReceiptNumber();
        $('#modal-expenditure').modal('show');
    }

    function updateReceiptNumber() {
        let level = $('#form-expenditure [name="level"]').val();
        $.get("{{ route('admin.bos.expenditure.receipt-number') }}", {level: level}, function(res) {
            $('[name="receipt_number"]').val(res.receipt_number);
        });
    }

    function editIncome(id) {
        $.get("{{ url('admin/bos/income') }}/" + id, function(res) {
            $('#income-id').val(res.data.id);
            $('[name="academic_year_id"]').val(res.data.academic_year_id);
            $('[name="level"]').val(res.data.level);
            $('[name="date"]').val(res.data.date);
            $('[name="source"]').val(res.data.source);
            $('[name="description"]').val(res.data.description);
            $('#income_amount').val(res.data.amount);
            $('#income_amount_display').val(new Intl.NumberFormat('id-ID').format(res.data.amount));
            $('#income-title').text('Edit Pendapatan BOS');
            $('#modal-income').modal('show');
        });
    }

    function editExpenditure(id) {
        $.get("{{ url('admin/bos/expenditure') }}/" + id, function(res) {
            $('#expenditure-id').val(res.data.id);
            $('[name="academic_year_id"]').val(res.data.academic_year_id);
            $('[name="level"]').val(res.data.level);
            $('[name="receipt_number"]').val(res.data.receipt_number);
            $('[name="noted_at"]').val(res.data.noted_at);
            $('[name="realized_at"]').val(res.data.realized_at);
            $('[name="description"]').val(res.data.description);
            $('[name="receiver"]').val(res.data.receiver);
            $('#exp_amount').val(res.data.amount);
            $('#exp_amount_display').val(new Intl.NumberFormat('id-ID').format(res.data.amount));
            
            $('#display-kode-snp').text(res.data.kode_snp);
            $('#display-snp').text(res.data.snp);
            $('#display-kode-kegiatan').text(res.data.kode_kegiatan);
            $('#display-nama-kegiatan').text(res.data.nama_kegiatan);
            $('#display-kode-sub-kegiatan').text(res.data.kode_sub_kegiatan);
            $('#display-sub-kegiatan').text(res.data.sub_kegiatan);

            $('#exp_kode_snp_val').val(res.data.kode_snp);
            $('#exp_snp_val').val(res.data.snp);
            $('#exp_kode_kegiatan_val').val(res.data.kode_kegiatan);
            $('#exp_nama_kegiatan_val').val(res.data.nama_kegiatan);
            $('#exp_kode_sub_kegiatan_val').val(res.data.kode_sub_kegiatan);
            $('#exp_sub_kegiatan_val').val(res.data.sub_kegiatan);

            $('#display-kode-kate').text(res.data.kode_kate);
            $('#display-kategori').text(res.data.kategori);
            $('#display-kode-jenis').text(res.data.kode_jenis);
            $('#display-jenis').text(res.data.jenis);

            $('#exp_kode_kate_val').val(res.data.kode_kate);
            $('#exp_kategori_val').val(res.data.kategori);
            $('#exp_kode_jenis_val').val(res.data.kode_jenis);
            $('#exp_jenis_val').val(res.data.jenis);
            $('#exp_deskripsi_val').val(res.data.deskripsi);

            $('#display-item-code').text(res.data.item_code);
            $('#display-item-name').text(res.data.item_name);
            $('#display-item-spec').text(res.data.item_specification || '-');
            $('#display-item-unit').text(res.data.item_unit);
            $('#display-item-price-1').text(new Intl.NumberFormat('id-ID').format(res.data.item_price_1));

            $('#exp_item_name_val').val(res.data.item_name);
            $('#exp_item_code_val').val(res.data.item_code);
            $('#exp_item_spec_val').val(res.data.item_specification);
            $('#exp_item_unit_val').val(res.data.item_unit);
            $('#exp_item_payment_type_val').val(res.data.item_payment_type);
            $('#exp_item_price_1_val').val(res.data.item_price_1);
            $('#exp_item_price_2_val').val(res.data.item_price_2);
            $('#exp_item_price_3_val').val(res.data.item_price_3);

            $('#exp_category_val').val(res.data.expense_category);
            $('#exp_type_val').val(res.data.expense_type);

            // Set category Select2
            if(res.data.category) {
                let option = new Option(res.data.category, res.data.category, true, true);
                $('#select-activity').append(option).trigger('change');
            }

            $('#expenditure-title').text('Edit Pengeluaran BOS');
            $('#modal-expenditure').modal('show');
        });
    }

    $('#form-income, #form-expenditure').on('submit', function(e) {
        e.preventDefault();
        let formId = $(this).attr('id');
        let url = formId === 'form-income' ? "{{ route('admin.bos.income.store') }}" : "{{ route('admin.bos.expenditure.store') }}";
        
        $.post(url, $(this).serialize(), function(res) {
            $('.modal').modal('hide');
            refreshTables();
            Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
        });
    });

    function deleteIncome(id) {
        Swal.fire({ title: 'Hapus data?', icon: 'warning', showCancelButton: true }).then(res => {
            if(res.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/bos/income') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        refreshTables();
                        Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                    }
                });
            }
        });
    }

    function deleteExpenditure(id) {
        Swal.fire({ title: 'Hapus data?', icon: 'warning', showCancelButton: true }).then(res => {
            if(res.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/bos/expenditure') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        refreshTables();
                        Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                    }
                });
            }
        });
    }

    function formatRupiah(input, target) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
            $(target).val(value);
        } else {
            input.value = '';
            $(target).val('');
        }
    }

</script>
@endpush
