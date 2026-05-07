@extends('layouts.app')
@section('title', 'Jadwal Ujian CBT')
@section('subtitle', 'Computer Based Test')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-laptop-code mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Jadwal Ujian CBT
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola jadwal ujian, alokasi waktu, token akses, dan pemantauan ujian secara terpusat dengan antarmuka cerdas.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-calendar-alt fa-8x opacity-2 shadow-icon"></i>
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
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Jadwal</p>
                        <h2 class="font-weight-bold mb-0 text-primary counter-value" id="stat_total">{{\App\Models\CbtExam::count()}}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-list text-primary fa-lg"></i>
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
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Jadwal Aktif</p>
                        <h2 class="font-weight-bold mb-0 text-success counter-value" id="stat_active">{{\App\Models\CbtExam::where('is_active', true)->count()}}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-check-circle text-success fa-lg"></i>
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
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Jadwal Ditutup</p>
                        <h2 class="font-weight-bold mb-0 text-danger counter-value" id="stat_inactive">{{\App\Models\CbtExam::where('is_active', false)->count()}}</h2>
                    </div>
                    <div class="icon-shape bg-soft-danger rounded-circle p-3">
                        <i class="fas fa-times-circle text-danger fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: FORM JADWAL -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
            <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-primary mb-0" id="formTitle">
                    <i class="fas fa-plus-circle mr-2"></i> Buat Jadwal Baru
                </h5>
                <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="btnCancelEdit" onclick="resetForm()">Batal Edit</button>
            </div>
            <div class="card-body pt-0">
                <form id="examForm">
                    @csrf
                    <input type="hidden" name="id" id="exam_id">
                    
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">NAMA UJIAN</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Misal: PAS Semester Ganjil 2026">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">BANK SOAL CBT</label>
                        <select name="cbt_bank_id" id="cbt_bank_id" class="form-control select2" style="width: 100%" required>
                            <option value="">-- Pilih Bank Soal --</option>
                            @foreach(\App\Models\CbtBank::all() as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }} (Kelas {{ $bank->class_level }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">TANGGAL UJIAN</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted">JAM MULAI</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted">JAM SELESAI</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">DURASI (MENIT)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control form-control-sm rounded-pill px-3" required min="10" max="300" value="90">
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted">KELAS PESERTA</label>
                        <select name="classes[]" id="classes" class="form-control select2" multiple required style="width: 100%" data-placeholder="Pilih rombel yang diizinkan ikut...">
                            @foreach(\App\Models\ClassGroup::all() as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->class_group }} - {{ $cls->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="custom-control custom-switch mb-4">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                        <label class="custom-control-label text-xs text-muted font-weight-bold" for="is_active">Status Jadwal Aktif / Buka</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block shadow-lg font-weight-bold py-2 btn-premium" id="btnSubmit">
                        <i class="fas fa-save mr-2"></i> SIMPAN JADWAL UJIAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Jadwal Ujian</h4>
                        <p class="text-muted text-sm mb-0">Kelola dan pantau seluruh jadwal ujian yang terdaftar.</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="examTable" style="width:100%">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="5%">No</th>
                                <th>Informasi Ujian</th>
                                <th width="20%">Waktu & Durasi</th>
                                <th width="15%">Token</th>
                                <th width="15%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
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
    .border-left-primary-thick { border-left: 5px solid #007bff !important; }

    /* Table Styling */
    #examTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #examTable tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    #examTable tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transform: scale(1.005);
    }
    #examTable td { border: none; padding: 1rem 0.75rem; vertical-align: middle; }
    #examTable td:first-child { border-radius: 12px 0 0 12px; }
    #examTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-danger { background: #ffebee; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'classic' });

        window.table = $('#examTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari jadwal...", search: "" },
            ajax: '{{ route('admin.cbt.exam.data') }}',
            columns: [
                {data: 'id', render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }},
                {data: 'name', render: function(data, type, row) {
                    return `<strong>${data}</strong><br><small class="text-muted"><i class="fas fa-book mr-1"></i> ${row.bank.name}</small><br><small class="text-info"><i class="fas fa-users mr-1"></i> ${row.classes}</small>`;
                }},
                {data: 'exam_date', render: function(data, type, row){ 
                    return `<span class="badge badge-light border border-secondary text-dark"><i class="far fa-calendar-alt"></i> ${row.exam_date}</span><br>
                            <small class="text-muted"><i class="far fa-clock"></i> ${row.start_time} - ${row.end_time}</small><br>
                            <small class="text-primary font-weight-bold"><i class="fas fa-hourglass-half"></i> ${row.duration_minutes} Menit</small>`; 
                }},
                {data: 'token', render: function(data, type, row){ 
                    return `<div class="text-center">
                                <span class="badge badge-warning text-lg p-2 font-weight-bold" style="letter-spacing: 2px;">${data}</span><br>
                                <button class="btn btn-xs btn-outline-secondary mt-1 rounded-pill" onclick="refreshToken(${row.id})"><i class="fas fa-sync-alt"></i> Refresh</button>
                            </div>`; 
                }},
                {data: 'is_active', className: 'text-center', render: function(data){ 
                    return data ? '<span class="badge badge-success px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-play-circle mr-1"></i> AKTIF</span>' : '<span class="badge badge-danger px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-stop-circle mr-1"></i> DITUTUP</span>'; 
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ],
            drawCallback: function() {
                // Keep stats updated could be done via AJAX if needed, or just let page refresh do it
            }
        });

        $('#examForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#exam_id').val();
            let url = id ? `/admin/cbt/exam/${id}` : `{{ route('admin.cbt.exam.store') }}`;
            let type = id ? 'PUT' : 'POST';

            $('#btnSubmit').html('<i class="fas fa-spinner fa-spin mr-2"></i> MENYIMPAN...').prop('disabled', true);

            $.ajax({
                url: url, type: type, data: $(this).serialize(),
                success: function(res) {
                    resetForm();
                    table.ajax.reload();
                    Swal.fire('Sukses', res.message, 'success');
                },
                error: function(err) {
                    Swal.fire('Gagal', 'Periksa kembali input Anda.', 'error');
                },
                complete: function() {
                    $('#btnSubmit').html('<i class="fas fa-save mr-2"></i> SIMPAN JADWAL UJIAN').prop('disabled', false);
                }
            });
        });
    });

    function resetForm() {
        $('#examForm')[0].reset();
        $('#exam_id').val('');
        $('#classes').val([]).trigger('change');
        $('#cbt_bank_id').val('').trigger('change');
        $('#formTitle').html('<i class="fas fa-plus-circle mr-2"></i> Buat Jadwal Baru');
        $('#btnSubmit').html('<i class="fas fa-save mr-2"></i> SIMPAN JADWAL UJIAN').removeClass('btn-warning').addClass('btn-primary');
        $('#btnCancelEdit').addClass('d-none');
    }

    function editExam(id) {
        Swal.fire({ title: 'Memuat Data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        $.get(`/admin/cbt/exam/${id}/edit`, function(data) {
            $('#exam_id').val(data.id);
            $('#name').val(data.name);
            $('#cbt_bank_id').val(data.cbt_bank_id).trigger('change');
            $('#exam_date').val(data.exam_date);
            $('#start_time').val(data.start_time);
            $('#end_time').val(data.end_time);
            $('#duration_minutes').val(data.duration_minutes);
            $('#is_active').prop('checked', data.is_active);
            
            let classes = data.classes.map(c => c.id);
            $('#classes').val(classes).trigger('change');
            
            $('#formTitle').html('<i class="fas fa-edit mr-2"></i> Edit Jadwal Ujian');
            $('#btnSubmit').html('<i class="fas fa-save mr-2"></i> UPDATE JADWAL').removeClass('btn-primary').addClass('btn-warning');
            $('#btnCancelEdit').removeClass('d-none');
            
            Swal.close();
        }).fail(function() {
            Swal.fire('Error', 'Gagal memuat data jadwal', 'error');
        });
    }

    function deleteExam(id) {
        Swal.fire({
            title: 'Hapus Jadwal?', text: 'Data hasil ujian siswa yang terhubung dengan jadwal ini akan ikut terhapus permanen!', icon: 'warning', 
            showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Ya, hapus permanen!', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/cbt/exam/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                    success: function(res) { window.table.ajax.reload(); Swal.fire('Terhapus!', res.message, 'success'); }
                });
            }
        });
    }

    function refreshToken(id) {
        $.post(`/admin/cbt/exam/${id}/refresh-token`, { _token: '{{ csrf_token() }}' }, function(res) {
            window.table.ajax.reload(null, false);
            Swal.fire('Token Diperbarui', `Token akses ujian telah diubah menjadi: <br><h2 class="mt-3 font-weight-bold text-warning">${res.token}</h2>`, 'success');
        });
    }
</script>
@endpush
