@extends($layout)

@section('title', 'Penempatan Rombel')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4 bg-gradient-info overflow-hidden">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h3 class="font-weight-bold mb-1"><i class="fas fa-layer-group mr-2"></i> Penempatan Rombongan Belajar</h3>
                        <p class="mb-0 opacity-8">Halaman ini digunakan untuk memploting siswa ke dalam kelas (Rombel) untuk tahun pelajaran yang akan datang.</p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-user-check fa-5x opacity-2" style="position:absolute; right:20px; top:-20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="info-box shadow-sm border-0">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted font-weight-bold">Belum Ada Kelas</span>
                <span class="info-box-number h4 mb-0 text-warning" id="stat_unassigned">-</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box shadow-sm border-0">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted font-weight-bold">Sudah Berkelas</span>
                <span class="info-box-number h4 mb-0 text-success" id="stat_assigned">-</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box shadow-sm border-0">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-school"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted font-weight-bold">Total Siswa Terfilter</span>
                <span class="info-box-number h4 mb-0 text-primary" id="stat_total">-</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- SIDEBAR: FILTERS & TOOLS -->
    <div class="col-xl-4 col-lg-5">
        <!-- STEP 1: FIND STUDENTS -->
        <div class="card card-outline card-info shadow-sm border-top-3 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-3">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-filter mr-2"></i> 1. Konfigurasi Filter
                </h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="text-sm font-weight-bold text-uppercase text-muted mb-1">Tahun Pelajaran Target</label>
                    <select id="filter_academic_year" class="form-control select2">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }} data-text="{{ $ay->academic_year }}">
                                {{ $ay->academic_year }} ({{ $ay->semester->semester_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="text-sm font-weight-bold text-uppercase text-muted mb-1">Tingkat Kelas</label>
                            <select id="filter_class_level" class="form-control select2">
                                <option value="">Semua</option>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" data-text="Kelas {{ $i }}">Kelas {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="text-sm font-weight-bold text-uppercase text-muted mb-1">Status Plotting</label>
                            <select id="filter_placement" class="form-control select2">
                                <option value="unassigned" selected>Belum Plotting</option>
                                <option value="assigned">Sudah Plotting</option>
                                <option value="all">Semua</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block shadow-sm font-weight-bold">
                    <i class="fas fa-search mr-2"></i> TERAPKAN FILTER
                </button>
            </div>
        </div>

        <!-- STEP 2: MANUAL PLACEMENT -->
        <div class="card shadow-sm border-0 mb-4 overflow-hidden border-left-success">
            <div class="card-header bg-white border-bottom-0 pt-3">
                <h3 class="card-title font-weight-bold text-success">
                    <i class="fas fa-sign-in-alt mr-2"></i> 2. Penempatan Manual
                </h3>
            </div>
            <div class="card-body">
                <form id="placementForm">
                    @csrf
                    <div class="form-group">
                        <label class="text-sm font-weight-bold mb-1 text-muted">Tahun Pelajaran Tujuan</label>
                        <select name="target_academic_year_id" id="target_academic_year" class="form-control select2" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-sm font-weight-bold mb-1 text-muted">Rombongan Belajar (Rombel)</label>
                        <select name="target_class_group_id" id="target_class" class="form-control select2" required>
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" data-year="{{ $cg->academic_year_id }}" data-level="{{ $cg->class_level }}">
                                    {{ $cg->class_group }} - {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="submitPlacement()" class="btn btn-success btn-block shadow font-weight-bold py-2" id="btnSubmit">
                        <i class="fas fa-check-circle mr-2"></i> SIMPAN PENEMPATAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 font-weight-bold text-dark" id="table_title">Daftar Siswa</h4>
                        <p class="text-muted text-sm mb-0" id="table_subtitle">Menampilkan siswa yang belum memiliki kelas</p>
                    </div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 font-weight-bold" id="btnCheckAll">
                            <i class="far fa-check-square mr-1"></i> PILIH SEMUA
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="studentTable" style="width:100%">
                        <thead class="bg-light text-uppercase text-xs">
                            <tr>
                                <th width="40px" class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                        <label class="custom-control-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th width="120px">NIS / NISN</th>
                                <th>Nama Siswa</th>
                                <th width="150px">Tingkat & Asal</th>
                                <th width="180px">Status Rombel</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-info { background: linear-gradient(45deg, #17a2b8, #117a8b) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.15; }
    .border-top-3 { border-top: 3px solid #17a2b8 !important; }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .text-xs { font-size: 0.75rem; }
    .info-box { min-height: 80px; }
    .info-box .info-box-icon { width: 60px; font-size: 1.5rem; }
    
    /* Premium Table Styling */
    #studentTable { border-collapse: separate; border-spacing: 0 8px; background: transparent; }
    #studentTable thead th { border: none; background: #f8f9fa; color: #6c757d; font-weight: 700; letter-spacing: 0.5px; }
    #studentTable tbody tr { 
        background: #fff; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        transition: all 0.2s ease; 
        border-radius: 8px;
    }
    #studentTable tbody tr:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
        background: #fdfdfd;
    }
    #studentTable td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    #studentTable td:first-child { border-radius: 8px 0 0 8px; }
    #studentTable td:last-child { border-radius: 0 8px 8px 0; }
    
    .badge-soft-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-soft-danger { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
    let table;
    const allTargetOptions = $('#target_class').html();

    $(function() {
        table = $('#studentTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, paging: false, info: true,
            language: {
                searchPlaceholder: "Cari nama siswa...",
                search: ""
            },
            ajax: {
                url: '{{ route("student-placements.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_level = $('#filter_class_level').val();
                    d.placement_status = $('#filter_placement').val();
                    d.status_id = $('#filter_status').val();
                }
            },
            columns: [
                { 
                    data: 'checkbox', 
                    searchable: false, 
                    sortable: false,
                    className: 'text-center'
                },
                { data: 'nis' },
                { data: 'nama_lengkap' },
                { data: 'kelas_info', searchable: false },
                { data: 'placement_status', searchable: false },
            ],
            drawCallback: function(settings) {
                // Update Statistics
                let json = settings.json;
                if (json) {
                    $('#stat_total').text(json.recordsDisplay || 0);
                    let assigned = json.data.filter(i => i.placement_status.includes('check')).length;
                    let unassigned = json.data.length - assigned;
                    $('#stat_assigned').text(assigned);
                    $('#stat_unassigned').text(unassigned);
                }

                // Update Header Title
                let yearText = $('#filter_academic_year option:selected').data('text') || 'Semua Tahun';
                let levelText = $('#filter_class_level option:selected').val() ? ' - ' + $('#filter_class_level option:selected').data('text') : '';
                let statusText = $('#filter_placement option:selected').text();
                
                $('#table_title').text('Siswa ' + yearText + levelText);
                $('#table_subtitle').text('Filter Aktif: ' + statusText);
            }
        });

        table.on('draw', function() {
            $('#checkAll').prop('checked', false);
        });

        $('#checkAll').on('click', function() {
            $('.student-checkbox').prop('checked', this.checked);
        });

        $('#btnCheckAll').on('click', function() {
            let target = !$('#checkAll').prop('checked');
            $('#checkAll').prop('checked', target).trigger('click');
        });

        // Filter Target Classes (Manual)
        function updateTargetClasses() {
            let targetYearId = $('#target_academic_year').val();
            let $targetSelect = $('#target_class');
            let currentVal = $targetSelect.val();
            
            // Revert to original full list before filtering
            $targetSelect.html(allTargetOptions);

            let count = 0;
            $targetSelect.find('option').each(function() {
                let optYear = $(this).data('year');
                let val = $(this).val();

                if (val === "") return; // Skip placeholder

                if (targetYearId && optYear != targetYearId) {
                    $(this).remove();
                } else {
                    count++;
                }
            });
            
            // If the previously selected class is no longer in the list, reset it
            if (currentVal && !$targetSelect.find('option[value="' + currentVal + '"]').length) {
                $targetSelect.val('').trigger('change.select2');
            }

            // If no classes found for this year
            if (count === 0) {
                $targetSelect.append('<option value="" disabled>-- Tidak ada kelas di tahun ini --</option>');
            }
            
            $targetSelect.trigger('change.select2');
        }

        // Trigger filter when target year changes
        $('#target_academic_year').on('change', function() {
            updateTargetClasses();
        });

        // Trigger on load
        updateTargetClasses();
        $('#filter_placement').on('change', refreshTable);

        // Sync Target Years with Filter Year to avoid confusion
        $('#filter_academic_year').on('change', function() {
            let val = $(this).val();
            if (val && val !== 'none') {
                $('#target_academic_year').val(val).trigger('change.select2');
                updateTargetClasses();
            }
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }

    function submitPlacement() {
        let formData = $('#placementForm').serialize();
        let studentIds = [];
        $('.student-checkbox:checked').each(function() {
            studentIds.push($(this).val());
        });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih minimal satu siswa.' });
            return;
        }

        if (!$('#target_class').val()) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih Kelas Tujuan.' });
            return;
        }

        Swal.fire({
            title: 'Simpan Penempatan?',
            text: 'Konfirmasi penempatan untuk ' + studentIds.length + ' siswa terpilih.',
            icon: 'question', 
            showCancelButton: true, 
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                
                $.post('{{ route("student-placements.store") }}', formData + '&' + $.param({student_ids: studentIds}))
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 2000, showConfirmButton: false });
                        table.ajax.reload();
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    })
                    .always(() => {
                        $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> Simpan Penempatan');
                    });
            }
        });
    }
</script>
@endpush
