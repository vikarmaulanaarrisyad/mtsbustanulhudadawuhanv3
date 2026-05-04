@extends($layout)

@section('title', 'Manajemen Kelulusan')
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
                            <i class="fas fa-user-graduate mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Kelulusan Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Proses kelulusan untuk siswa kelas akhir dan kelola pencetakan SKL secara profesional.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-certificate fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STEP GUIDELINE (HORIZONTAL) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body p-3">
                <div class="d-flex align-items-center flex-wrap justify-content-center">
                    <div class="step-item d-flex align-items-center mx-3 mb-2 mb-md-0">
                        <span class="step-num bg-primary active-step shadow-primary">1</span>
                        <span class="ml-2 font-weight-bold text-primary small">KELULUSAN</span>
                    </div>
                    <i class="fas fa-chevron-right text-muted mx-2 d-none d-md-block"></i>
                    <div class="step-item d-flex align-items-center mx-3 mb-2 mb-md-0">
                        <span class="step-num bg-secondary">2</span>
                        <span class="ml-2 font-weight-bold text-muted small">KENAIKAN KELAS</span>
                    </div>
                    <i class="fas fa-chevron-right text-muted mx-2 d-none d-md-block"></i>
                    <div class="step-item d-flex align-items-center mx-3 mb-2 mb-md-0">
                        <span class="step-num bg-secondary">3</span>
                        <span class="ml-2 font-weight-bold text-muted small">PENEMPATAN BARU</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: FILTERS & ACTIONS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        
        <!-- FILTER BOX -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-filter mr-2 text-info"></i> Filter Data Kelulusan
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-muted uppercase">TAHUN PELAJARAN</label>
                    <select id="filter_academic_year" class="form-control select2" onchange="checkSemester(this)">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" 
                                    data-semester="{{ $ay->semester->semester_name }}" 
                                    data-semester-id="{{ $ay->semester_id }}"
                                    {{ $ay->current_semester ? 'selected' : '' }}>
                                {{ $ay->academic_year }} - {{ $ay->semester->semester_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div id="semesterWarning" class="alert alert-soft-warning mb-3 d-none">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
                        <span class="small font-weight-bold">Perhatian: Semester Ganjil terdeteksi. Kelulusan biasanya di Semester Genap.</span>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-muted uppercase">KELAS (TINGKAT AKHIR)</label>
                    <select id="filter_class" class="form-control select2">
                        <option value="">-- Semua Kelas Akhir --</option>
                        @foreach($classGroups as $cg)
                            <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label class="text-xs font-weight-bold text-muted uppercase">STATUS SISWA</label>
                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                        <label class="btn btn-outline-primary btn-sm active flex-fill">
                            <input type="radio" name="grad_status" id="status_active" value="0" checked onchange="toggleBox()"> BELUM LULUS
                        </label>
                        <label class="btn btn-outline-success btn-sm flex-fill">
                            <input type="radio" name="grad_status" id="status_graduated" value="1" onchange="toggleBox()"> SUDAH LULUS
                        </label>
                    </div>
                </div>

                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block shadow-sm font-weight-bold py-2 btn-premium">
                    <i class="fas fa-search mr-2"></i> TAMPILKAN DATA
                </button>
            </div>
        </div>

        <!-- GRADUATION ACTION BOX -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick" id="graduateBox">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-success mb-0">
                    <i class="fas fa-check-circle mr-2"></i> Proses Kelulusan
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="graduationForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">TANGGAL LULUS</label>
                        <input type="date" name="exit_date" class="form-control rounded-pill px-3" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">CATATAN</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Lulus TA 2025/2026"></textarea>
                    </div>
                    <button type="button" onclick="submitGraduation()" class="btn btn-success btn-block shadow-lg font-weight-bold py-3 btn-premium">
                        <i class="fas fa-graduation-cap mr-2"></i> SET LULUS SEKARANG
                    </button>
                </form>
            </div>
        </div>

        <!-- UNDO BOX -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-danger-thick d-none" id="undoBox">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-danger mb-0">
                    <i class="fas fa-undo mr-2"></i> Pembatalan
                </h5>
            </div>
            <div class="card-body pt-0">
                <p class="text-sm text-muted">Gunakan tombol di bawah untuk mengembalikan status siswa yang terpilih menjadi <b>Aktif</b> kembali.</p>
                <button type="button" onclick="undoGraduation()" class="btn btn-danger btn-block shadow-sm font-weight-bold py-2 btn-premium">
                    <i class="fas fa-history mr-1"></i> BATALKAN KELULUSAN
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <!-- PRINT SKL ALERT -->
        <div class="alert bg-soft-info border-0 shadow-sm mb-4" style="border-left: 5px solid #17a2b8 !important;">
            <div class="d-flex">
                <div class="mr-3"><i class="fas fa-print fa-2x text-info"></i></div>
                <div>
                    <h6 class="font-weight-bold text-info mb-1">Cetak Surat Keterangan Lulus (SKL)</h6>
                    <p class="text-sm text-muted mb-0">Ubah filter menjadi <b>"Sudah Lulus"</b> untuk menampilkan tombol cetak SKL pada kolom aksi siswa.</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Siswa Akhir</h4>
                        <p class="text-muted text-sm mb-0">Data siswa tingkat akhir yang siap diproses</p>
                    </div>
                    <div class="card-tools">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="checkAll">
                            <label for="checkAll" class="custom-control-label font-weight-bold text-primary">PILIH SEMUA</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="studentTable" style="width:100%">
                        <thead class="bg-light-primary text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">#</th>
                                <th width="140px">Identitas</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas Terakhir</th>
                                <th width="120px" class="text-center">AKSI</th>
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
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important; }
    .bg-soft-info { background: #e3f2fd; }
    .alert-soft-warning { background: #fff8e1; color: #856404; border-left: 5px solid #ffc107 !important; }
    
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .border-left-success-thick { border-left: 5px solid #28a745 !important; }
    .border-left-danger-thick { border-left: 5px solid #dc3545 !important; }
    
    /* Horizontal Step Guide */
    .step-num { 
        width: 32px; height: 32px; border-radius: 50%; display: flex; 
        align-items: center; justify-content: center; color: #fff; font-weight: bold;
    }
    .active-step { transform: scale(1.2); z-index: 2; box-shadow: 0 0 15px rgba(0,123,255,0.4); }

    /* Table Styling */
    #studentTable { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #studentTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 12px; }
    #studentTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8faff; }
    #studentTable td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    #studentTable td:first-child { border-radius: 12px 0 0 12px; }
    #studentTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-primary { background: #f1f7ff; color: #0056b3; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    function checkSemester(el) {
        let semesterId = $(el).find(':selected').data('semester-id');
        if (semesterId == 1) { // Ganjil
            $('#semesterWarning').removeClass('d-none');
        } else {
            $('#semesterWarning').addClass('d-none');
        }
    }

    function toggleBox() {
        let val = $('input[name="grad_status"]:checked').val();
        if (val == '1') {
            $('#graduateBox').fadeOut(() => $('#undoBox').fadeIn());
        } else {
            $('#undoBox').fadeOut(() => $('#graduateBox').fadeIn());
        }
    }

    $(function() {
        checkSemester($('#filter_academic_year'));

        table = $('#studentTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, paging: false, info: false,
            ajax: {
                url: '{{ route("graduations.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_group_id = $('#filter_class').val();
                    d.is_graduated = $('input[name="grad_status"]:checked').val();
                }
            },
            columns: [
                { data: 'checkbox', searchable: false, sortable: false, className: 'text-center' },
                { data: 'nis' },
                { 
                    data: 'nama_lengkap',
                    render: function(data) { return '<span class="font-weight-bold text-dark">' + data + '</span>'; }
                },
                { data: 'kelas' },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });

        $('#checkAll').on('click', function() { $('.student-checkbox').prop('checked', this.checked); });
    });

    function refreshTable() { table.ajax.reload(); }

    function submitGraduation() {
        let studentIds = [];
        $('.student-checkbox:checked').each(function() { studentIds.push($(this).val()); });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Siswa', text: 'Silakan pilih siswa yang akan diluluskan.' });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Kelulusan',
            text: 'Proses kelulusan untuk ' + studentIds.length + ' siswa terpilih?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Ya, Luluskan'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnGraduate').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>');
                $.post('{{ route("graduations.graduate") }}', $('#graduationForm').serialize() + '&' + $.param({student_ids: studentIds}))
                    .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message }); table.ajax.reload(); })
                    .fail(err => { Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Error' }); })
                    .always(() => { $('#btnGraduate').prop('disabled', false).html('<i class="fas fa-graduation-cap mr-2"></i> SET LULUS SEKARANG'); });
            }
        });
    }

    function undoGraduation() {
        let studentIds = [];
        $('.student-checkbox:checked').each(function() { studentIds.push($(this).val()); });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Siswa', text: 'Pilih siswa yang akan dibatalkan kelulusannya.' });
            return;
        }

        Swal.fire({
            title: 'Batalkan Kelulusan?',
            text: 'Kembalikan status siswa menjadi Aktif?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("graduations.undo") }}', { _token: '{{ csrf_token() }}', student_ids: studentIds })
                    .done(res => { Swal.fire({ icon: 'success', title: 'Dibatalkan', text: res.message }); table.ajax.reload(); })
                    .fail(err => { Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Error' }); });
            }
        });
    }
</script>
@endpush
