@extends('layouts.app')
@section('title', 'Proctor Command Center: UJIAN MADRASAH')
@section('subtitle', 'CBT Management')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo position-relative" style="border-radius: 15px;">
            <!-- Background Decoration (Clipped) -->
            <div class="position-absolute w-100 h-100 overflow-hidden" style="top:0; left:0; border-radius: 15px; z-index: 0;">
                <div class="bg-circle-1"></div>
                <div class="bg-circle-2"></div>
            </div>
            
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ route('admin.cbt.exam.index') }}" class="btn btn-xs btn-outline-light rounded-pill px-3 mr-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <span class="badge badge-soft-warning px-3 py-1 rounded-pill font-weight-bold animate__animated animate__pulse animate__infinite">LIVE MONITORING</span>
                        </div>
                        <h2 class="font-weight-bold mb-1 text-white">
                            <i class="fas fa-satellite mr-2 animate__animated animate__fadeInLeft"></i> 
                            UJIAN MADRASAH: {{ $exam->name }}
                        </h2>
                        <div class="d-flex gap-3 text-sm opacity-8">
                            <span><i class="fas fa-calendar mr-1 text-white"></i> {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') : '-' }}</span>
                            <span><i class="fas fa-clock mr-1 text-white"></i> {{ $exam->start_time }} - {{ $exam->end_time }}</span>
                            <span><i class="fas fa-key mr-1 text-white"></i> TOKEN: <strong class="text-warning">{{ $exam->token }}</strong></span>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" onclick="openDutyModal()" class="btn btn-indigo rounded-pill px-4 font-weight-bold shadow-lg btn-premium mr-2">
                            <i class="fas fa-user-shield mr-2"></i> PETUGAS UJIAN
                        </button>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-warning rounded-pill px-4 font-weight-bold dropdown-toggle shadow-lg btn-premium" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-print mr-2"></i> LAPORAN LENGKAP
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg p-3 animate__animated animate__fadeIn" style="border-radius: 15px; min-width: 280px; z-index: 9999;">
                                <h6 class="dropdown-header text-xs text-muted font-weight-bold pl-0 mb-2 uppercase letter-spacing-1">Dokumen Administrasi</h6>
                                <a class="dropdown-item py-2 rounded-10 mb-1" href="javascript:void(0)" onclick="$('#attendanceModal').modal('show')">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-primary rounded p-2 mr-3" style="width:35px; height:35px;"><i class="fas fa-user-check text-primary"></i></div>
                                        <div><span class="d-block font-weight-bold text-dark text-sm">Daftar Hadir</span><small class="text-[10px] text-muted">Filter Sesi & Ruang</small></div>
                                    </div>
                                </a>
                                <a class="dropdown-item py-2 rounded-10 mb-1" href="javascript:void(0)" onclick="$('#beritaAcaraModal').modal('show')">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-success rounded p-2 mr-3" style="width:35px; height:35px;"><i class="fas fa-file-alt text-success"></i></div>
                                        <div><span class="d-block font-weight-bold text-dark text-sm">Berita Acara</span><small class="text-[10px] text-muted">Input Catatan & Absensi</small></div>
                                    </div>
                                </a>
                                <a class="dropdown-item py-2 rounded-10 mb-1" href="{{ route('admin.cbt.exam.print-exam-cards', $exam->id) }}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-warning rounded p-2 mr-3" style="width:35px; height:35px;"><i class="fas fa-id-card text-warning"></i></div>
                                        <div><span class="d-block font-weight-bold text-dark text-sm">Kartu Login</span><small class="text-[10px] text-muted">Cetak Per Ruang</small></div>
                                    </div>
                                </a>
                                
                                <div class="dropdown-divider my-2"></div>
                                <h6 class="dropdown-header text-xs text-muted font-weight-bold pl-0 mb-2 uppercase letter-spacing-1">Rekapitulasi Hasil</h6>
                                <a class="dropdown-item py-2 rounded-10 mb-1" href="{{ route('admin.cbt.exam.export-excel', $exam->id) }}">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-success rounded p-2 mr-3" style="width:35px; height:35px;"><i class="fas fa-file-excel text-success"></i></div>
                                        <div><span class="d-block font-weight-bold text-dark text-sm">Export Excel</span><small class="text-[10px] text-muted">Rekap Nilai Siswa</small></div>
                                    </div>
                                </a>
                                <a class="dropdown-item py-2 rounded-10 mb-1" href="{{ route('admin.cbt.exam.export-pdf', $exam->id) }}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-danger rounded p-2 mr-3" style="width:35px; height:35px;"><i class="fas fa-file-pdf text-danger"></i></div>
                                        <div><span class="d-block font-weight-bold text-dark text-sm">Export PDF</span><small class="text-[10px] text-muted">Daftar Nilai (PDF)</small></div>
                                    </div>
                                </a>
                                <a class="dropdown-item py-2 rounded-10 mb-1" href="{{ route('admin.cbt.exam.item-analysis', $exam->id) }}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-info rounded p-2 mr-3" style="width:35px; height:35px;"><i class="fas fa-chart-pie text-info"></i></div>
                                        <div><span class="d-block font-weight-bold text-dark text-sm">Analisis Soal</span><small class="text-[10px] text-muted">Daya Pembeda & Kesukaran</small></div>
                                    </div>
                                </a>
                                <a class="dropdown-item py-2 rounded-10" href="{{ route('admin.cbt.exam.export-rdm', $exam->id) }}">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-soft-indigo rounded p-2 mr-3" style="width:35px; height:35px;"><i class="fas fa-upload text-indigo"></i></div>
                                        <div><span class="d-block font-weight-bold text-dark text-sm">Format RDM</span><small class="text-[10px] text-muted">Template Raport Digital</small></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <button class="btn btn-white rounded-pill px-4 font-weight-bold shadow-lg btn-premium text-indigo" onclick="location.reload()">
                            <i class="fas fa-sync-alt mr-2"></i> REFRESH
                        </button>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #6610f2 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Peserta</p>
                        <h2 class="font-weight-bold mb-0 text-indigo" id="total-count">-</h2>
                    </div>
                    <div class="icon-shape bg-soft-indigo rounded-circle p-3">
                        <i class="fas fa-users text-indigo fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-indigo" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Mengerjakan</p>
                        <h2 class="font-weight-bold mb-0 text-warning" id="active-count">-</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-pen-nib text-warning fa-lg pulse-warning-icon"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light" id="active-progress-container">
                    <div class="progress-bar bg-warning" id="active-progress-bar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Selesai</p>
                        <h2 class="font-weight-bold mb-0 text-success" id="finished-count">-</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-check-double text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light" id="finished-progress-container">
                    <div class="progress-bar bg-success" id="finished-progress-bar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Pelanggaran</p>
                        <h2 class="font-weight-bold mb-0 text-danger" id="violation-count">-</h2>
                    </div>
                    <div class="icon-shape bg-soft-danger rounded-circle p-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-danger" style="width: 30%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MONITORING MAIN -->
<div class="row">
    <div class="col-xl-9 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 font-weight-bold text-dark">Live Monitoring</h4>
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" id="search-monitor" class="form-control rounded-pill-left px-3 border-2" placeholder="Cari nama...">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light rounded-pill-right border-2"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                        <select id="filter-status" class="form-control form-control-sm rounded-pill px-3 border-2" style="width: 150px;">
                            <option value="all">Semua Status</option>
                            <option value="doing">Mengerjakan</option>
                            <option value="finished">Selesai</option>
                            <option value="not_started">Standby</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="monitor-table" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase text-xs font-weight-bold">
                            <tr>
                                <th class="pl-4 py-3">Siswa</th>
                                <th width="150px">Sesi & Ruang</th>
                                <th width="200px" class="text-center">Progress</th>
                                <th width="150px" class="text-center">Status</th>
                                <th width="100px" class="text-right pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="student-list">
                            {{-- Inject by AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS & LOGS -->
    <div class="col-xl-3 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card mb-4 border-left-indigo-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="font-weight-bold text-indigo mb-0">Quick Control</h6>
            </div>
            <div class="card-body pt-0">
                <button class="btn btn-indigo btn-block btn-premium font-weight-bold py-2 mb-2 shadow-sm" onclick="broadcastMessage()">
                    <i class="fas fa-bullhorn mr-2"></i> Broadcast Pesan
                </button>
                <button class="btn btn-outline-danger btn-block btn-premium font-weight-bold py-2 shadow-sm" onclick="forceFinishAll()">
                    <i class="fas fa-power-off mr-2"></i> Hentikan Semua
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="font-weight-bold mb-0">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body p-0" id="activity-logs" style="max-height: 450px; overflow-y: auto;">
                <div class="p-4 text-center opacity-50">
                    <small>Menunggu aktivitas...</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CETAK DAFTAR HADIR --}}
<div class="modal fade animate__animated animate__fadeInDown" id="attendanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-print mr-2"></i> Cetak Daftar Hadir</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.cbt.exam.print-attendance', $exam->id) }}" target="_blank">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">GELOMBANG</label>
                            <select name="wave" class="form-control rounded-10">
                                <option value="">Semua Gelombang</option>
                                @for($i=1; $i<=4; $i++) <option value="{{$i}}">Gelombang {{$i}}</option> @endfor
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">SESI</label>
                            <select name="session" class="form-control rounded-10">
                                <option value="">Semua Sesi</option>
                                @for($i=1; $i<=4; $i++) <option value="{{$i}}">Sesi {{$i}}</option> @endfor
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="text-xs font-weight-bold text-muted uppercase">RUANG</label>
                            <input type="text" name="room" class="form-control rounded-10" placeholder="Misal: Ruang 1 (Kosongkan jika semua)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-block font-weight-bold py-3 btn-premium shadow-lg">
                        <i class="fas fa-file-pdf mr-2"></i> GENERATE PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL BUAT BERITA ACARA --}}
<div class="modal fade animate__animated animate__fadeInDown" id="beritaAcaraModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px;">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-contract mr-2"></i> Berita Acara Pelaksanaan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.cbt.exam.print-berita-acara', $exam->id) }}" target="_blank">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">GELOMBANG</label>
                            <select name="wave" class="form-control rounded-10">
                                <option value="">Pilih Gelombang</option>
                                @for($i=1; $i<=4; $i++) <option value="{{$i}}">Gelombang {{$i}}</option> @endfor
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">SESI</label>
                            <select name="session" class="form-control rounded-10">
                                <option value="">Pilih Sesi</option>
                                @for($i=1; $i<=4; $i++) <option value="{{$i}}">Sesi {{$i}}</option> @endfor
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">RUANG</label>
                            <input type="text" name="room" class="form-control rounded-10" placeholder="Misal: Ruang 1">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">CATATAN KEJADIAN PENTING</label>
                            <textarea name="notes" class="form-control rounded-10" rows="3" placeholder="Misal: Ujian berjalan lancar tanpa kendala..."></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">SISWA TIDAK HADIR</label>
                            <textarea name="absent_manual" class="form-control rounded-10" rows="2" placeholder="Tulis nama-nama siswa yang tidak hadir (dipisah koma)..."></textarea>
                            <small class="text-muted text-[10px]">Biarkan kosong jika ingin sistem mendeteksi otomatis dari data login.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success btn-block font-weight-bold py-3 btn-premium shadow-lg">
                        <i class="fas fa-print mr-2"></i> CETAK BERITA ACARA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL PETUGAS UJIAN --}}
<div class="modal fade animate__animated animate__fadeInDown" id="dutyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px;">
            <div class="modal-header bg-indigo text-white border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-shield mr-2"></i> Pengaturan Petugas Ujian</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-soft-indigo rounded-10 mb-4">
                    <small><i class="fas fa-info-circle mr-2"></i> Tentukan proktor dan pengawas untuk setiap ruang dan sesi agar tercantum otomatis di Berita Acara.</small>
                </div>
                
                <form id="duty-form">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">SESI</label>
                            <select name="session_number" id="duty-session" class="form-control rounded-10">
                                @for($i=1; $i<=4; $i++) <option value="{{$i}}">Sesi {{$i}}</option> @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">RUANG</label>
                            <select name="room_name" id="duty-room" class="form-control rounded-10">
                                {{-- Will be populated dynamically based on student data --}}
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">PROKTOR</label>
                            <select name="proctor_id" id="duty-proctor" class="form-control rounded-10 select2-duty">
                                <option value="">Pilih Proktor</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">PENGAWAS</label>
                            <select name="supervisor_id" id="duty-supervisor" class="form-control rounded-10 select2-duty">
                                <option value="">Pilih Pengawas</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="button" onclick="saveDuty()" class="btn btn-indigo btn-block font-weight-bold py-3 btn-premium shadow-lg">
                                <i class="fas fa-save mr-2"></i> SIMPAN JADWAL PETUGAS
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <div class="table-responsive">
                    <table class="table table-sm table-hover text-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Sesi</th>
                                <th>Ruang</th>
                                <th>Proktor</th>
                                <th>Pengawas</th>
                            </tr>
                        </thead>
                        <tbody id="duty-list">
                            {{-- Load via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL MESSAGE --}}
<div class="modal fade animate__animated animate__fadeIn" id="messageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px;">
            <div class="modal-header bg-indigo text-white border-0 py-3">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-paper-plane mr-2"></i> Kirim Pesan Proktor</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <p id="message-target" class="badge badge-soft-indigo px-3 py-2 rounded-pill font-weight-bold text-sm mb-3 w-100 text-center"></p>
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Isi Pesan Instruksi</label>
                    <textarea id="admin-message" class="form-control rounded-15 border-2 p-3" rows="3" placeholder="Tulis instruksi di sini..."></textarea>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-xs btn-outline-indigo rounded-pill px-3" onclick="$('#admin-message').val('Harap tenang dan fokus mengerjakan!')">Template 1</button>
                    <button class="btn btn-xs btn-outline-danger rounded-pill px-3" onclick="$('#admin-message').val('Pelanggaran terdeteksi! Ujian Anda akan dihentikan jika berulang.')">Peringatan</button>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-indigo btn-block font-weight-bold py-3 btn-premium shadow-lg" onclick="submitMessage()">
                    <i class="fas fa-send mr-2"></i> KIRIM SEKARANG
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* PREMIUM DESIGN SYSTEM (Penempatan Rombel Style) */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap');
    body { font-family: 'Outfit', sans-serif; }

    .bg-gradient-indigo { background: linear-gradient(135deg, #6610f2 0%, #4338ca 100%) !important; }
    .text-indigo { color: #6610f2 !important; }
    .btn-indigo { background-color: #6610f2; color: white; }
    .btn-indigo:hover { background-color: #520dc2; color: white; }
    .btn-outline-indigo { border-color: #6610f2; color: #6610f2; }
    .btn-outline-indigo:hover { background-color: #6610f2; color: white; }
    
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; border: none !important; }
    .border-left-indigo-thick { border-left: 5px solid #6610f2 !important; }

    #monitor-table { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #monitor-table tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; }
    #monitor-table tbody tr:hover { background: #f8fbff; transform: scale(1.005); }
    #monitor-table td { border: none; padding: 1rem 0.75rem; vertical-align: middle; }
    .bg-light-indigo { background: #f5f3ff; color: #5b21b6; }

    .icon-shape { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-indigo { background: #eef2ff; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-danger { background: #fee2e2; }
    
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .rounded-15 { border-radius: 15px; }
    .rounded-pill-left { border-top-left-radius: 50rem; border-bottom-left-radius: 50rem; }
    .rounded-pill-right { border-top-right-radius: 50rem; border-bottom-right-radius: 50rem; }
    .border-2 { border-width: 2px !important; }

    .progress-premium { height: 8px; border-radius: 10px; background: #f1f5f9; overflow: hidden; }
    .avatar-initial {
        width: 40px; height: 40px; border-radius: 12px; background: #f5f3ff;
        color: #6610f2; display: flex; align-items: center; justify-content: center;
        font-weight: 900; font-size: 1.2rem;
    }

    .pulse-warning-icon { animation: pulse-warn 2s infinite; }
    @keyframes pulse-warn {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .active-row { border-left: 4px solid #6610f2 !important; background: #f5f3ff !important; }
    .badge-soft-indigo { background: #eef2ff; color: #6366f1; }
    .badge-soft-warning { background: #fff7ed; color: #f59e0b; }
    .badge-soft-success { background: #f0fdf4; color: #10b981; }

    .rounded-10 { border-radius: 10px !important; }
    .uppercase { text-transform: uppercase !important; }
    .letter-spacing-1 { letter-spacing: 1px !important; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; }
    .bg-white\/10 { background-color: rgba(255, 255, 255, 0.1) !important; }
</style>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let currentExamId = '{{ $exam->id }}';
    let currentStudentExamId = null;
    let studentsData = [];

    $(document).ready(function() {
        fetchMonitorData();
        setInterval(fetchMonitorData, 15000); // Poll every 15s

        $('#search-monitor, #filter-status').on('input change', function() {
            renderStudents();
        });
    });

    function fetchMonitorData() {
        $.get(`/admin/cbt/exam/${currentExamId}/monitor-data`, function(res) {
            studentsData = res.students;
            updateStats(res.students);
            renderStudents();
        });
    }

    function updateStats(students) {
        let total = students.length;
        let active = students.filter(s => s.status === 'doing').length;
        let finished = students.filter(s => s.status === 'finished').length;
        let violations = students.reduce((acc, s) => acc + s.violations, 0);

        $('#total-count').text(total);
        $('#active-count').text(active);
        $('#finished-count').text(finished);
        $('#violation-count').text(violations);

        if (total > 0) {
            $('#active-progress-bar').css('width', (active / total * 100) + '%');
            $('#finished-progress-bar').css('width', (finished / total * 100) + '%');
        }
    }

    function renderStudents() {
        let search = $('#search-monitor').val().toLowerCase();
        let status = $('#filter-status').val();
        let html = '';

        let filtered = studentsData.filter(s => {
            let matchSearch = s.nama.toLowerCase().includes(search);
            let matchStatus = status === 'all' || s.status === status;
            return matchSearch && matchStatus;
        });

        filtered.forEach(s => {
            let statusBadge = '';
            if (s.status === 'doing') {
                statusBadge = `<span class="badge badge-soft-warning px-3 py-1 rounded-pill animate__animated animate__flash animate__infinite">MENGERJAKAN</span>`;
            } else if (s.status === 'finished') {
                statusBadge = `<span class="badge badge-soft-success px-3 py-1 rounded-pill">SELESAI</span>`;
            } else {
                statusBadge = `<span class="badge badge-light px-3 py-1 rounded-pill text-muted">STANDBY</span>`;
            }

            let progressClass = s.progress === 100 ? 'bg-success' : (s.progress > 70 ? 'bg-indigo' : 'bg-warning');

            html += `
                <tr class="${s.status === 'doing' ? 'active-row' : ''}">
                    <td class="pl-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial mr-3">
                                ${s.nama.charAt(0)}
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold text-dark">${s.nama}</h6>
                                <small class="text-muted text-xs">${s.kelas}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-xs">
                            <span class="badge badge-light mr-1">SESI ${s.session || '-'}</span>
                            <span class="badge badge-light">ROOM ${s.room || '-'}</span>
                            <div class="mt-1 text-muted"><i class="far fa-clock mr-1"></i>${s.is_logged_in ? '<span class="text-success">Aktif Sekarang</span>' : 'Offline'}</div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex flex-column align-items-center px-3">
                            <div class="d-flex justify-content-between w-100 mb-1">
                                <small class="text-xs font-weight-bold text-muted">No. ${s.current_index}</small>
                                <small class="text-xs font-weight-bold text-indigo">${s.progress}%</small>
                            </div>
                            <div class="progress-premium w-100">
                                <div class="progress-bar ${progressClass}" style="width: ${s.progress}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        ${statusBadge}
                        ${s.violations > 0 ? `<div class="text-danger font-weight-bold text-xs mt-1"><i class="fas fa-exclamation-triangle"></i> ${s.violations} Violations</div>` : ''}
                    </td>
                    <td class="text-right pr-4">
                        <div class="btn-group shadow-xs rounded-pill overflow-hidden">
                            <button class="btn btn-xs btn-outline-indigo" onclick="openMessageModal(${s.id}, '${s.nama}', ${s.exam_id})" title="Kirim Pesan">
                                <i class="fas fa-comment"></i>
                            </button>
                            <button class="btn btn-xs btn-outline-danger" onclick="resetStudent(${s.exam_id})" title="Reset Sesi">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        if (filtered.length === 0) {
            html = '<tr><td colspan="5" class="text-center py-5 opacity-50">Data siswa tidak ditemukan</td></tr>';
        }

        $('#student-list').html(html);
    }

    function openMessageModal(studentId, name, examId) {
        currentStudentExamId = examId;
        if (!currentStudentExamId) {
            Swal.fire('Info', 'Siswa belum memulai ujian.', 'info');
            return;
        }
        $('#message-target').text(`PENERIMA: ${name}`);
        $('#messageModal').modal('show');
    }

    function submitMessage() {
        let msg = $('#admin-message').val();
        if (!msg) return;

        $.post(`/admin/cbt/exam/${currentExamId}/send-message/${currentStudentExamId}`, {
            _token: '{{ csrf_token() }}',
            message: msg
        }, function(res) {
            $('#messageModal').modal('hide');
            $('#admin-message').val('');
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: res.message, showConfirmButton: false, timer: 3000
            });
        });
    }

    function resetStudent(examId) {
        if (!examId) return;
        Swal.fire({
            title: 'Reset Sesi?',
            text: 'Seluruh jawaban siswa akan dikosongkan dan waktu dimulai ulang.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, Reset!', confirmButtonColor: '#dc3545'
        }).then((res) => {
            if (res.isConfirmed) {
                $.post(`/admin/cbt/exam/student-exam/${examId}/reset`, { _token: '{{ csrf_token() }}' }, function() {
                    Swal.fire('Berhasil!', 'Sesi siswa telah direset.', 'success');
                    fetchMonitorData();
                });
            }
        });
    }

    function broadcastMessage() {
        Swal.fire({
            title: 'Broadcast Pesan',
            input: 'textarea',
            inputPlaceholder: 'Tulis pesan untuk semua siswa...',
            showCancelButton: true,
            confirmButtonText: 'Kirim Semua',
            confirmButtonColor: '#6610f2'
        }).then((result) => {
            if (result.value) {
                // Implement broadcast logic if needed, or loop through studentsData
                Swal.fire('Info', 'Fitur broadcast segera hadir. Gunakan tombol aksi per siswa untuk saat ini.', 'info');
            }
        });
    }

    function forceFinishAll() {
        Swal.fire({
            title: 'Hentikan Semua Ujian?',
            text: 'Seluruh siswa yang sedang mengerjakan akan dipaksa selesai.',
            icon: 'danger', showCancelButton: true,
            confirmButtonText: 'Ya, Hentikan!', confirmButtonColor: '#dc3545'
        }).then((res) => {
            if (res.isConfirmed) {
                Swal.fire('Info', 'Gunakan dashboard proktor untuk menghentikan siswa secara manual satu per satu demi keamanan data.', 'info');
            }
        });
    }
    // DUTY MANAGEMENT
    function openDutyModal() {
        $('#dutyModal').modal('show');
        loadDutyData();
    }

    function loadDutyData() {
        $.get(`/admin/cbt/exam/${currentExamId}/duty-data`, function(data) {
            // Populate Teachers
            const proctorSelect = $('#duty-proctor');
            const supervisorSelect = $('#duty-supervisor');
            proctorSelect.html('<option value="">Pilih Proktor</option>');
            supervisorSelect.html('<option value="">Pilih Pengawas</option>');
            
            data.teachers.forEach(t => {
                proctorSelect.append(`<option value="${t.id}">${t.name}</option>`);
                supervisorSelect.append(`<option value="${t.id}">${t.name}</option>`);
            });

            // Populate Rooms
            const roomSelect = $('#duty-room');
            roomSelect.empty();
            if(data.rooms.length > 0) {
                data.rooms.forEach(r => {
                    roomSelect.append(`<option value="${r}">${r}</option>`);
                });
            } else {
                roomSelect.append('<option value="">Tidak ada data ruang</option>');
            }

            // Render List
            const list = $('#duty-list');
            list.empty();
            if(data.duties.length === 0) {
                list.append('<tr><td colspan="4" class="text-center py-3 text-muted">Belum ada petugas yang diatur</td></tr>');
            } else {
                data.duties.forEach(d => {
                    list.append(`
                        <tr>
                            <td>Sesi ${d.session_number}</td>
                            <td>${d.room_name}</td>
                            <td class="font-weight-bold text-primary">${d.proctor ? d.proctor.name : '-'}</td>
                            <td class="font-weight-bold text-success">${d.supervisor ? d.supervisor.name : '-'}</td>
                        </tr>
                    `);
                });
            }
        });
    }

    function saveDuty() {
        const formData = {
            session_number: $('#duty-session').val(),
            room_name: $('#duty-room').val(),
            proctor_id: $('#duty-proctor').val(),
            supervisor_id: $('#duty-supervisor').val(),
            _token: '{{ csrf_token() }}'
        };

        if(!formData.room_name) {
            Swal.fire('Peringatan', 'Mohon pilih ruang terlebih dahulu', 'warning');
            return;
        }

        $.post(`/admin/cbt/exam/${currentExamId}/duty-schedule`, formData, function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            loadDutyData();
        }).fail(function(xhr) {
            Swal.fire('Error', 'Gagal menyimpan data petugas', 'error');
        });
    }
</script>
@endpush
