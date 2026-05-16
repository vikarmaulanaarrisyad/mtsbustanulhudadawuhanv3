@extends('layouts.app')

@section('title', 'Sinkronisasi Sesi CBT')
@section('subtitle', 'CBT Management')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-sync-alt mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Sesi & Gelombang
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Atur distribusi siswa ke dalam gelombang, sesi, dan ruang secara otomatis dengan algoritma pembagian merata.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-bolt fa-8x opacity-2 shadow-icon"></i>
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
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Siswa</p>
                        <h2 class="font-weight-bold mb-0 text-indigo">{{ number_format($stats['total_students']) }}</h2>
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
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Terjadwal</p>
                        <h2 class="font-weight-bold mb-0 text-success">{{ number_format($stats['assigned_students']) }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-calendar-check text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    @php $percent = $stats['total_students'] > 0 ? ($stats['assigned_students'] / $stats['total_students']) * 100 : 0; @endphp
                    <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Belum Diatur</p>
                        <h2 class="font-weight-bold mb-0 text-warning">{{ number_format($stats['unassigned_students']) }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-user-clock text-warning fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    @php $percentUn = $stats['total_students'] > 0 ? ($stats['unassigned_students'] / $stats['total_students']) * 100 : 0; @endphp
                    <div class="progress-bar bg-warning" style="width: {{ $percentUn }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #17a2b8 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Ruang Ujian</p>
                        <h2 class="font-weight-bold mb-0 text-info">{{ number_format($stats['total_rooms']) }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-info rounded-circle p-3">
                        <i class="fas fa-door-open text-info fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-info" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <!-- STEP 1: AUTO DISTRIBUTE -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-indigo-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-indigo mb-0">
                    <i class="fas fa-magic mr-2"></i> Plotting Otomatis
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="autoDistributeForm" action="{{ route('admin.cbt.session-sync.auto-distribute') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Tingkat Kelas Sasaran</label>
                        <select name="class_level[]" class="form-control select2 custom-select-premium" multiple required data-placeholder="Pilih Tingkat">
                            <optgroup label="Madrasah Ibtidaiyah (MI)">
                                @for($i=1; $i<=6; $i++) <option value="{{ $i }}">Kelas {{ $i }}</option> @endfor
                            </optgroup>
                            <optgroup label="Madrasah Tsanawiyah (MTs)">
                                @for($i=7; $i<=9; $i++) <option value="{{ $i }}">Kelas {{ $i }}</option> @endfor
                            </optgroup>
                            <optgroup label="Madrasah Aliyah (MA)">
                                @for($i=10; $i<=12; $i++) <option value="{{ $i }}">Kelas {{ $i }}</option> @endfor
                            </optgroup>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Gelombang</label>
                                <select name="wave_count" class="form-control rounded-pill px-3">
                                    @for($i=1; $i<=4; $i++) <option value="{{ $i }}">{{ $i }} Gelombang</option> @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Sesi / Hari</label>
                                <select name="session_count" id="session_count" class="form-control rounded-pill px-3">
                                    @for($i=1; $i<=5; $i++) <option value="{{ $i }}" {{ $i==3?'selected':'' }}>{{ $i }} Sesi</option> @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Durasi Per Sesi (Menit)</label>
                        <input type="number" id="plotting_duration" value="90" class="form-control rounded-pill px-3" placeholder="Menit">
                    </div>

                    <div id="session_times_container" class="bg-light p-3 rounded-15 mb-3">
                        <!-- Dynamic times will show here -->
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="text-xs font-weight-bold text-muted uppercase">Jumlah Ruang</label>
                            <input type="number" name="room_count" value="1" min="1" class="form-control rounded-pill px-3">
                        </div>
                        <div class="col-6">
                            <label class="text-xs font-weight-bold text-muted uppercase">PC / Ruang</label>
                            <input type="number" name="pc_per_room" value="20" min="1" class="form-control rounded-pill px-3">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-indigo btn-block shadow-lg font-weight-bold py-3 btn-premium">
                        <i class="fas fa-bolt mr-2"></i> JALANKAN PLOTTING
                    </button>
                </form>
            </div>
        </div>

        <!-- NEW: SESSION TIME CONFIGURATION (KEEP FOR INDEPENDENT UPDATE) -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-warning-thick">
            <div class="card-header bg-white py-2 border-bottom-0">
                <h6 class="card-title font-weight-bold text-warning mb-0 text-xs">
                    <i class="fas fa-clock mr-2"></i> Update Cepat Jam Sesi
                </h6>
            </div>
            <div class="card-body pt-0">
                <form id="updateSessionTimesForm" action="{{ route('admin.cbt.session-sync.update-session-times') }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_count" id="session_count_sync">
                    <div id="sync_container_hidden" style="display:none"></div>
                    <p class="text-[10px] text-muted mb-2">Jam sesi akan diperbarui otomatis saat Anda menekan tombol Simpan di bawah atau saat menjalankan Plotting.</p>
                    <button type="submit" class="btn btn-warning btn-sm btn-block font-weight-bold py-2 btn-premium text-dark shadow-sm">
                        <i class="fas fa-save mr-1"></i> UPDATE JAM SAJA
                    </button>
                </form>
            </div>
        </div>

        <!-- STEP 2: DANGER ZONE -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-danger-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-danger mb-0">
                    <span class="step-badge bg-danger mr-2">2</span> Danger Zone
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="resetForm" action="{{ route('admin.cbt.session-sync.reset') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target" value="level">
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">Reset Berdasarkan Tingkat</label>
                        <select name="class_level[]" class="form-control select2" multiple data-placeholder="Pilih Tingkat untuk Reset">
                            @for($i=1; $i<=12; $i++) <option value="{{ $i }}">Kelas {{ $i }}</option> @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-danger btn-block font-weight-bold py-2 btn-premium">
                        <i class="fas fa-trash-alt mr-2"></i> RESET PENEMPATAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Preview Penempatan</h4>
                        <p class="text-muted text-sm mb-0">Sinkronisasi Tahun Pelajaran: {{ $activeYear->academic_year ?? '-' }}</p>
                    </div>
                    <button onclick="refreshTable()" class="btn btn-outline-indigo btn-sm rounded-pill px-4 font-weight-bold border-2 shadow-xs">
                        <i class="fas fa-sync-alt mr-1"></i> REFRESH DATA
                    </button>
                </div>
                <div class="row g-2">
                    <div class="col-md-4">
                        <select id="filter_level" class="form-control select2">
                            <option value="">Semua Tingkat</option>
                            @for($i=1; $i<=12; $i++) <option value="{{ $i }}">Kelas {{ $i }}</option> @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filter_class" class="form-control select2">
                            <option value="">Semua Rombel</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->class_group }} {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filter_status" class="form-control select2">
                            <option value="all">Semua Status</option>
                            <option value="assigned">Terjadwal</option>
                            <option value="unassigned">Belum Diatur</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="assignTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase text-xs font-weight-bold">
                            <tr>
                                <th width="50px" class="text-center py-3">No</th>
                                <th>Nama Lengkap Siswa</th>
                                <th width="120px">Kelas</th>
                                <th width="80px" class="text-center">Gel</th>
                                <th width="150px" class="text-center">Sesi & Waktu</th>
                                <th width="100px" class="text-center">Ruang</th>
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
    .bg-gradient-indigo { background: linear-gradient(135deg, #6610f2 0%, #4338ca 100%) !important; }
    .text-indigo { color: #6610f2 !important; }
    .btn-indigo { background-color: #6610f2; color: white; }
    .btn-indigo:hover { background-color: #520dc2; color: white; }
    .btn-outline-indigo { border-color: #6610f2; color: #6610f2; }
    .btn-outline-indigo:hover { background-color: #6610f2; color: white; }
    
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; border: none !important; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-indigo-thick { border-left: 5px solid #6610f2 !important; }
    .border-left-danger-thick { border-left: 5px solid #dc3545 !important; }

    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        color: #fff; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    #assignTable { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #assignTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 10px; }
    #assignTable tbody tr:hover { background: #f8fbff; transform: scale(1.005); }
    #assignTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    .bg-light-indigo { background: #f5f3ff; color: #5b21b6; }

    .icon-shape { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-indigo { background: #eef2ff; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-info { background: #e0f7fa; }
    
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .rounded-15 { border-radius: 15px; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>

@push('scripts')
@include('includes.datatable')
@include('includes.select2')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        // 1. Inisialisasi Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        // 2. Fungsi Kalkulasi Waktu Berakhir
        window.calculateEndTime = function(idx) {
            const startVal = $(`#s_${idx}_start`).val();
            const duration = parseInt($('#plotting_duration').val()) || 0;
            if(startVal && duration > 0) {
                try {
                    const [h, m] = startVal.split(':');
                    const date = new Date();
                    date.setHours(parseInt(h), parseInt(m), 0);
                    const newDate = new Date(date.getTime() + duration * 60000);
                    const hours = String(newDate.getHours()).padStart(2, '0');
                    const minutes = String(newDate.getMinutes()).padStart(2, '0');
                    $(`#s_${idx}_end`).val(`${hours}:${minutes}`);
                } catch(e) { console.error("Error calc time:", e); }
            }
        }

        // 3. Fungsi Generate Input Sesi
        window.generateSessionInputs = function() {
            const count = parseInt($('#session_count').val()) || 0;
            const container = $('#session_times_container');
            const existingTimes = @json($sessionTimes);
            
            container.empty();
            if (count > 0) {
                container.append('<h6 class="text-[10px] font-weight-bold text-muted uppercase mb-3"><i class="far fa-clock mr-1"></i> Atur Jam Per Sesi</h6>');
                
                for (let i = 1; i <= count; i++) {
                    let defaultStart = (i == 1 ? '07:30' : (i == 2 ? '09:30' : (i == 3 ? '13:00' : (i == 4 ? '15:00' : '07:00'))));
                    let defaultEnd = '';

                    if(existingTimes && Array.isArray(existingTimes)) {
                        const found = existingTimes.find(t => t.session_number == i);
                        if(found) {
                            defaultStart = found.start_time.substring(0, 5);
                            defaultEnd = found.end_time.substring(0, 5);
                        }
                    }

                    container.append(`
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-3"><span class="text-xs font-weight-bold">Sesi ${i}</span></div>
                            <div class="col-9">
                                <div class="input-group input-group-sm">
                                    <input type="time" name="session_${i}_start" id="s_${i}_start" value="${defaultStart}" class="form-control rounded-left-10 time-input" data-index="${i}">
                                    <div class="input-group-append"><span class="input-group-text bg-white border-left-0 border-right-0 text-muted px-1"><i class="fas fa-arrow-right fa-xs"></i></span></div>
                                    <input type="time" name="session_${i}_end" id="s_${i}_end" value="${defaultEnd}" class="form-control rounded-right-10 time-input">
                                </div>
                            </div>
                        </div>
                    `);
                    
                    if(!defaultEnd) window.calculateEndTime(i);
                }

                $('.time-input').on('change', function() {
                    const idx = $(this).data('index');
                    if(idx) window.calculateEndTime(idx);
                });
            }
        }

        // 4. Inisialisasi DataTable
        window.assignTable = $('#assignTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari nama...", search: "" },
            ajax: {
                url: '{{ route('admin.cbt.session-sync.list-data') }}',
                data: function(d) {
                    d.level = $('#filter_level').val();
                    d.class_group_id = $('#filter_class').val();
                    d.status = $('#filter_status').val();
                }
            },
            columns: [
                { data: 'id', render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'nama_lengkap', render: function(data) { return `<span class="font-weight-bold text-dark">${data}</span>`; } },
                { data: 'kelas' },
                { data: 'cbt_wave', className: 'text-center', render: function(data) { 
                    return data ? `<span class="badge badge-primary px-3 rounded-pill">G${data}</span>` : `<span class="text-muted small">Belum Set</span>`; 
                }},
                { data: 'cbt_session', className: 'text-center', render: function(data, type, row) { 
                    if (!data) return `<span class="text-muted small">Belum Set</span>`;
                    let html = `<span class="badge badge-warning px-3 rounded-pill text-dark d-block mb-1">Sesi ${data}</span>`;
                    if (row.waktu_sesi && row.waktu_sesi !== '-') {
                        html += `<span class="text-[10px] font-weight-bold text-muted">${row.waktu_sesi}</span>`;
                    }
                    return html;
                }},
                { data: 'cbt_room', className: 'text-center', render: function(data) { 
                    return data ? `<span class="badge badge-dark px-3 rounded-pill">${data}</span>` : `<span class="text-muted">-</span>`; 
                }},
            ]
        });

        // 5. Event Listeners
        $('#filter_level, #filter_class, #filter_status').on('change', function() {
            window.assignTable.ajax.reload();
        });

        $('#session_count').on('change', function() {
            window.generateSessionInputs();
        });

        $('#plotting_duration').on('input change', function() {
            const count = $('#session_count').val();
            for(let i=1; i<=count; i++) {
                window.calculateEndTime(i);
            }
        });

        // 6. Form Submissions
        $('#autoDistributeForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Plotting Otomatis',
                text: "Sistem akan membagi siswa ke dalam sesi secara merata. Lanjutkan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Jalankan!',
                confirmButtonColor: '#6610f2'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            Swal.close();
                            if (res.status) {
                                Swal.fire('Berhasil!', res.message, 'success');
                                window.assignTable.ajax.reload();
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        }
                    });
                }
            });
        });

        $('#updateSessionTimesForm').on('submit', function(e) {
            e.preventDefault();
            const count = $('#session_count').val();
            $('#session_count_sync').val(count);
            const hidden = $('#sync_container_hidden');
            hidden.empty();
            for(let i=1; i<=count; i++) {
                hidden.append(`<input type="hidden" name="session_${i}_start" value="${$(`#s_${i}_start`).val()}">`);
                hidden.append(`<input type="hidden" name="session_${i}_end" value="${$(`#s_${i}_end`).val()}">`);
            }

            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    Swal.close();
                    if (res.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        window.assignTable.ajax.reload();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }
            });
        });

        $('#resetForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Reset',
                text: "Data penempatan siswa akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Reset!',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            Swal.fire('Berhasil!', res.message, 'success');
                            window.assignTable.ajax.reload();
                            setTimeout(() => location.reload(), 1000);
                        }
                    });
                }
            });
        });

        // 7. Jalankan Inisialisasi Awal
        window.generateSessionInputs();
    });
</script>
@endpush
@endsection
