@extends($layout)

@section('title', 'Penempatan Rombel')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-layer-group mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Penempatan Kelas
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola alokasi siswa ke dalam rombongan belajar secara cerdas dengan fitur Plotting Otomatis & Promosi Berjenjang.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-user-shield fa-8x opacity-2 shadow-icon"></i>
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
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Belum Ada Kelas</p>
                        <h2 class="font-weight-bold mb-0 text-warning counter-value" id="stat_unassigned">-</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-user-clock text-warning fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Sudah Berkelas</p>
                        <h2 class="font-weight-bold mb-0 text-success counter-value" id="stat_assigned">-</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-user-check text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" style="width: 45%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Terfilter</p>
                        <h2 class="font-weight-bold mb-0 text-primary counter-value" id="stat_total">-</h2>
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
    <!-- LEFT SIDEBAR: FILTERS & TOOLS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        
        <!-- STEP 1: CONFIGURATION -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <span class="step-badge mr-2">1</span> Konfigurasi Filter
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Tahun Pelajaran Target</label>
                    <select id="filter_academic_year" class="form-control select2 custom-select-premium">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }} data-text="{{ $ay->academic_year }}">
                                {{ $ay->academic_year }} ({{ $ay->semester->semester_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Tingkat Kelas</label>
                            <select id="filter_class_level" class="form-control select2">
                                <option value="">Semua</option>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" data-text="Kelas {{ $i }}">Kelas {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Status Plotting</label>
                            <select id="filter_placement" class="form-control select2">
                                <option value="unassigned" selected>Belum Plotting</option>
                                <option value="assigned">Sudah Plotting</option>
                                <option value="all">Semua</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block shadow-sm font-weight-bold py-2 btn-premium">
                    <i class="fas fa-search-plus mr-2"></i> CARI DATA SISWA
                </button>
            </div>
        </div>

        <!-- STEP 2: MANUAL PLACEMENT -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-success mb-0">
                    <span class="step-badge bg-success mr-2">2</span> Penempatan Manual
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="placementForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">TAHUN PELAJARAN TUJUAN</label>
                        <select name="target_academic_year_id" id="target_academic_year" class="form-control select2">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted">ROMBONGAN BELAJAR TUJUAN</label>
                        <select name="target_class_group_id" id="target_class" class="form-control select2">
                            <option value="">-- Pilih Rombel --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" data-year="{{ $cg->academic_year_id }}" data-level="{{ $cg->class_level }}">
                                    {{ $cg->class_group }} - {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="submitPlacement()" class="btn btn-success btn-block shadow-lg font-weight-bold py-2 btn-premium">
                        <i class="fas fa-check-double mr-2"></i> SIMPAN PENEMPATAN
                    </button>
                </form>
            </div>
        </div>

        <!-- STEP 3: AUTO PLOTTING -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-primary mb-0">
                    <span class="step-badge bg-primary mr-2">3</span> Plotting Otomatis
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="autoPlacementForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">TAHUN TUJUAN</label>
                        <select name="academic_year_id" id="auto_target_academic_year" class="form-control select2">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">MULTI ROMBEL TARGET</label>
                        <select name="class_group_ids[]" id="auto_target_classes" class="form-control select2" multiple data-placeholder="Pilih beberapa rombel...">
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" data-year="{{ $cg->academic_year_id }}" data-level="{{ $cg->class_level }}">
                                    {{ $cg->class_group }} - {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="text-xs font-weight-bold text-muted">MAKS. SISWA / KELAS</label>
                            <input type="number" name="max_capacity" class="form-control form-control-sm rounded-pill px-3" value="32" min="1">
                        </div>
                    </div>
                    <div class="custom-control custom-switch mb-4">
                        <input type="checkbox" class="custom-control-input" id="gender_balanced" name="gender_balanced" value="1" checked>
                        <label class="custom-control-label text-xs text-muted" for="gender_balanced">Seimbangkan Laki-laki & Perempuan</label>
                    </div>
                    <button type="button" onclick="submitAutoPlacement()" class="btn btn-primary btn-block shadow-lg font-weight-bold py-2 btn-premium">
                        <i class="fas fa-bolt mr-2"></i> JALANKAN PLOTTING
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
                        <h4 class="mb-1 font-weight-bold text-dark" id="table_title">Daftar Siswa</h4>
                        <p class="text-muted text-sm mb-0" id="table_subtitle">Memuat data...</p>
                    </div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-4 font-weight-bold border-2 shadow-xs" id="btnCheckAll">
                            <i class="far fa-check-square mr-2"></i> PILIH SEMUA
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="studentTable" style="width:100%">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                        <label class="custom-control-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th width="120px">Identitas</th>
                                <th>Informasi Siswa</th>
                                <th width="150px">Tingkat & Riwayat</th>
                                <th width="180px">Status Plotting</th>
                            </tr>
                        </thead>
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
    .border-left-success-thick { border-left: 5px solid #28a745 !important; }
    .border-left-primary-thick { border-left: 5px solid #007bff !important; }

    /* Badge Styling */
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        background: #17a2b8; color: #fff; font-size: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Table Styling */
    #studentTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #studentTable tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    #studentTable tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transform: scale(1.005);
    }
    #studentTable td { border: none; padding: 1.5rem 0.75rem; vertical-align: middle; }
    #studentTable td:first-child { border-radius: 12px 0 0 12px; }
    #studentTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .badge-soft-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-soft-danger { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    const allTargetOptions = $('#target_class').html();
    const allAutoOptions = $('#auto_target_classes').html();

    $(function() {
        table = $('#studentTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, paging: false, info: true,
            language: { searchPlaceholder: "Cari nama siswa...", search: "" },
            ajax: {
                url: '{{ route("student-placements.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_level = $('#filter_class_level').val();
                    d.placement_status = $('#filter_placement').val();
                }
            },
            columns: [
                { data: 'checkbox', searchable: false, sortable: false, className: 'text-center' },
                { data: 'nis' },
                { data: 'nama_lengkap' },
                { data: 'kelas_info', searchable: false },
                { data: 'placement_status', searchable: false },
            ],
            drawCallback: function(settings) {
                let json = settings.json;
                if (json) {
                    $('#stat_total').text(json.recordsDisplay || 0);
                    let assigned = json.data.filter(i => i.placement_status.includes('check')).length;
                    let unassigned = json.data.length - assigned;
                    $('#stat_assigned').text(assigned);
                    $('#stat_unassigned').text(unassigned);
                }

                let yearText = $('#filter_academic_year option:selected').data('text') || 'Semua Tahun';
                let levelText = $('#filter_class_level option:selected').val() ? ' - ' + $('#filter_class_level option:selected').data('text') : '';
                let statusText = $('#filter_placement option:selected').text();
                
                $('#table_title').text('Data Siswa ' + yearText + levelText);
                $('#table_subtitle').text('Filter: ' + statusText);
            }
        });

        table.on('draw', function() { $('#checkAll').prop('checked', false); });
        $('#checkAll').on('click', function() { $('.student-checkbox').prop('checked', this.checked); });
        $('#btnCheckAll').on('click', function() {
            let target = !$('#checkAll').prop('checked');
            $('#checkAll').prop('checked', target).trigger('click');
        });

        // PROMOTION LOGIC FILTERS
        function updateTargetClasses() {
            let targetYearId = $('#target_academic_year').val();
            let filterLevel = $('#filter_class_level').val();
            let $targetSelect = $('#target_class');
            let currentVal = $targetSelect.val();
            let targetLevel = filterLevel ? parseInt(filterLevel) + 1 : null;
            
            $targetSelect.html(allTargetOptions);
            let count = 0;
            $targetSelect.find('option').each(function() {
                let isVisible = true;
                if (targetYearId && $(this).data('year') != targetYearId) isVisible = false;
                if (isVisible && targetLevel && $(this).data('level') != targetLevel) isVisible = false;

                if (!isVisible && $(this).val()) $(this).remove();
                else if ($(this).val()) count++;
            });
            
            if (currentVal && !$targetSelect.find('option[value="' + currentVal + '"]').length) $targetSelect.val('').trigger('change.select2');
            if (count === 0 && targetLevel) $targetSelect.append('<option value="" disabled>-- Tidak ada kelas Tingkat ' + targetLevel + ' --</option>');
            $targetSelect.trigger('change.select2');
        }

        function updateAutoTargetClasses() {
            let targetYearId = $('#auto_target_academic_year').val();
            let filterLevel = $('#filter_class_level').val();
            let $targetSelect = $('#auto_target_classes');
            let targetLevel = filterLevel ? parseInt(filterLevel) + 1 : null;
            
            $targetSelect.html(allAutoOptions);
            $targetSelect.find('option').each(function() {
                let isVisible = true;
                if (targetYearId && $(this).data('year') != targetYearId) isVisible = false;
                if (isVisible && targetLevel && $(this).data('level') != targetLevel) isVisible = false;
                if (!isVisible && $(this).val()) $(this).remove();
            });
            $targetSelect.trigger('change.select2');
        }

        $('#target_academic_year, #filter_class_level').on('change', updateTargetClasses);
        $('#auto_target_academic_year, #filter_class_level').on('change', updateAutoTargetClasses);
        $('#filter_placement').on('change', refreshTable);

        $('#filter_academic_year').on('change', function() {
            let val = $(this).val();
            if (val) {
                $('#target_academic_year, #auto_target_academic_year').val(val).trigger('change.select2');
                updateTargetClasses(); updateAutoTargetClasses();
                refreshTable();
            }
        });

        // Initialize filters
        updateTargetClasses();
        updateAutoTargetClasses();
    });

    function refreshTable() { table.ajax.reload(); }

    function submitPlacement() {
        let studentIds = [];
        $('.student-checkbox:checked').each(function() { studentIds.push($(this).val()); });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Siswa', text: 'Silakan pilih minimal satu siswa dari tabel.' });
            return;
        }

        if (!$('#target_class').val()) {
            Swal.fire({ icon: 'warning', title: 'Pilih Rombel', text: 'Silakan pilih Rombongan Belajar tujuan.' });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Penempatan',
            text: 'Anda akan menempatkan ' + studentIds.length + ' siswa ke kelas terpilih.',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Ya, Proses!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                $.post('{{ route("student-placements.store") }}', $('#placementForm').serialize() + '&' + $.param({student_ids: studentIds}))
                    .done(res => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 1500, showConfirmButton: false });
                        table.ajax.reload();
                    })
                    .fail(err => { Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Error' }); })
                    .always(() => { $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-check-double mr-2"></i> SIMPAN PENEMPATAN'); });
            }
        });
    }

    function submitAutoPlacement() {
        if (!$('#auto_target_classes').val() || $('#auto_target_classes').val().length === 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Rombel', text: 'Pilih minimal satu rombel tujuan untuk plotting otomatis.' });
            return;
        }

        Swal.fire({
            title: 'Plotting Otomatis',
            text: 'Sistem akan membagi siswa yang terfilter secara merata. Lanjutkan?',
            icon: 'info', showCancelButton: true, confirmButtonColor: '#007bff', confirmButtonText: 'Ya, Jalankan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnAutoSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                let fullData = $('#autoPlacementForm').serialize() + '&' + $.param({
                    filter_academic_year_id: $('#filter_academic_year').val(),
                    filter_class_level: $('#filter_class_level').val()
                });

                $.post('{{ route("student-placements.auto") }}', fullData)
                    .done(res => {
                        Swal.fire({ icon: 'success', title: 'Plotting Berhasil', text: res.message });
                        table.ajax.reload();
                    })
                    .fail(err => { Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Error' }); })
                    .always(() => { $('#btnAutoSubmit').prop('disabled', false).html('<i class="fas fa-bolt mr-2"></i> JALANKAN PLOTTING'); });
            }
        });
    }
</script>
@endpush
