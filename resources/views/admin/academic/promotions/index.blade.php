@extends($layout)

@section('title', 'Kenaikan Kelas & Rombel')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-success overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-arrow-circle-up mr-2 animate__animated animate__fadeInLeft"></i> 
                            Proses Kenaikan Kelas
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Naikkan tingkat siswa dan pindahkan rombel secara massal untuk persiapan tahun pelajaran baru.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-graduation-cap fa-8x opacity-2 shadow-icon"></i>
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
                        <span class="step-num bg-success">1</span>
                        <span class="ml-2 font-weight-bold text-muted small">KELULUSAN</span>
                    </div>
                    <i class="fas fa-chevron-right text-muted mx-2 d-none d-md-block"></i>
                    <div class="step-item d-flex align-items-center mx-3 mb-2 mb-md-0">
                        <span class="step-num bg-primary active-step shadow-primary">2</span>
                        <span class="ml-2 font-weight-bold text-primary small">KENAIKAN KELAS</span>
                    </div>
                    <i class="fas fa-chevron-right text-muted mx-2 d-none d-md-block"></i>
                    <div class="step-item d-flex align-items-center mx-3 mb-2 mb-md-0">
                        <span class="step-num bg-info">3</span>
                        <span class="ml-2 font-weight-bold text-muted small">PENEMPATAN BARU</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: FILTERS & PROMOTIONS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        
        <!-- STEP 1: SOURCE FILTER -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <span class="step-badge mr-2">A</span> Sumber Data Siswa
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Tahun Pelajaran Saat Ini</label>
                    <select id="filter_academic_year" class="form-control select2">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $ay->id == ($currentAY->id ?? '') ? 'selected' : '' }}>
                                {{ $ay->academic_year }} ({{ $ay->semester->semester_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Kelas Saat Ini</label>
                    <select id="filter_class" class="form-control select2">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($sourceClassGroups as $cg)
                            <option value="{{ $cg->id }}" data-level="{{ $cg->class_level }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block shadow-sm font-weight-bold py-2 btn-premium">
                    <i class="fas fa-users mr-2"></i> TAMPILKAN SISWA
                </button>
            </div>
        </div>

        <!-- STEP 2: PROMOTION ACTION -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-success mb-0">
                    <span class="step-badge bg-success mr-2">B</span> Aksi Kenaikan
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="promotionForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">PINDAH KE TAHUN PELAJARAN</label>
                        <select name="target_academic_year_id" id="target_academic_year" class="form-control select2">
                            <option value="">-- Pilih Tahun Tujuan --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->academic_year }} ({{ $ay->semester->semester_name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="custom-control custom-switch mb-3 bg-light p-3 rounded shadow-xs border">
                        <input type="checkbox" class="custom-control-input" id="rolling_mode" name="rolling_mode">
                        <label class="custom-control-label text-sm font-weight-bold" for="rolling_mode">Ploting Kelas Nanti (Rolling)</label>
                        <p class="text-xs text-muted mb-0 mt-1">Aktifkan untuk menaikkan tingkat tanpa menentukan rombel sekarang.</p>
                    </div>

                    <div class="form-group mb-3" id="target_class_container">
                        <label class="text-xs font-weight-bold text-muted uppercase">PINDAH KE ROMBEL</label>
                        <select name="target_class_group_id" id="target_class" class="form-control select2">
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            @foreach($targetClassGroups as $cg)
                                <option value="{{ $cg->id }}" data-year="{{ $cg->academic_year_id }}" data-level="{{ $cg->class_level }}">
                                    {{ $cg->class_group }} - {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">STATUS</label>
                        <select name="status" id="promotion_status" class="form-control border-left-primary" style="border-left-width: 4px;">
                            <option value="promoted" class="font-weight-bold">Naik Kelas</option>
                            <option value="retained">Tinggal Kelas (TA Baru)</option>
                        </select>
                    </div>

                    <button type="button" onclick="submitPromotion()" class="btn btn-success btn-block shadow-lg font-weight-bold py-3 btn-premium">
                        <i class="fas fa-check-double mr-2 text-lg"></i> PROSES AKHIR TAHUN
                    </button>
                    
                    <div class="text-center mt-3">
                        <button type="button" onclick="undoPromotion()" class="btn btn-link btn-sm text-danger font-weight-bold">
                            <i class="fas fa-undo-alt mr-1"></i> Batalkan Proses Terakhir
                        </button>
                    </div>
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
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Siswa Terfilter</h4>
                        <p class="text-muted text-sm mb-0">Pilih siswa yang akan diproses kenaikannya</p>
                    </div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-4 font-weight-bold border-2" id="btnCheckAll">
                            <i class="far fa-check-square mr-2"></i> PILIH SEMUA
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="studentTable" style="width:100%">
                        <thead class="bg-light-success text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                        <label class="custom-control-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th width="140px">Identitas</th>
                                <th>Nama Siswa</th>
                                <th>Kelas (Asal)</th>
                                <th width="180px">Status Terakhir</th>
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
    .bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .border-left-success-thick { border-left: 5px solid #28a745 !important; }
    
    /* Horizontal Step Guide */
    .step-num { 
        width: 32px; height: 32px; border-radius: 50%; display: flex; 
        align-items: center; justify-content: center; color: #fff; font-weight: bold;
    }
    .active-step { transform: scale(1.2); z-index: 2; }
    .shadow-primary { box-shadow: 0 0 15px rgba(0,123,255,0.4); }

    /* Badge & Step UI */
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%; background: #6c757d; color: #fff; font-size: 14px;
    }
    
    /* Table Styling */
    #studentTable { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #studentTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 12px; }
    #studentTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fafffa; }
    #studentTable td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    #studentTable td:first-child { border-radius: 12px 0 0 12px; }
    #studentTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-success { background: #f1f8f3; color: #3b7d4c; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    const allTargetOptions = $('#target_class').html();

    $(function() {
        table = $('#studentTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, paging: false, info: false,
            ajax: {
                url: '{{ route("promotions.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_group_id = $('#filter_class').val();
                }
            },
            columns: [
                { data: 'checkbox', searchable: false, sortable: false, className: 'text-center' },
                { data: 'nis' },
                { 
                    data: 'nama_lengkap',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-success rounded-circle d-flex align-items-center justify-content-center text-success font-weight-bold" style="width:35px;height:35px;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark">' + data + '</span></div>';
                    }
                },
                { data: 'kelas' },
                { 
                    data: 'history_info', 
                    searchable: false,
                    render: function(data) {
                        return '<div class="text-xs">' + data + '</div>';
                    }
                },
            ]
        });

        $('#checkAll').on('click', function() { $('.student-checkbox').prop('checked', this.checked); });
        $('#btnCheckAll').on('click', function() {
            let checked = $('#checkAll').prop('checked');
            $('#checkAll').prop('checked', !checked).trigger('click');
        });

        $('#rolling_mode').on('change', function() {
            if (this.checked) {
                $('#target_class_container').fadeOut();
                $('#target_class').val('').trigger('change.select2');
            } else {
                $('#target_class_container').fadeIn();
            }
        });

        function updateTargetClasses() {
            let targetYearId = $('#target_academic_year').val();
            let sourceLevel = $('#filter_class').find(':selected').data('level');
            let status = $('#promotion_status').val();
            let $targetSelect = $('#target_class');
            
            $targetSelect.html(allTargetOptions);
            let count = 0;
            $targetSelect.find('option').each(function() {
                let optYear = $(this).data('year');
                let optLevel = $(this).data('level');
                let isVisible = true;

                if (targetYearId && optYear != targetYearId) isVisible = false;

                if (isVisible && sourceLevel !== undefined && sourceLevel !== "") {
                    sourceLevel = parseInt(sourceLevel);
                    optLevel = parseInt(optLevel);
                    if (status === 'promoted') {
                        if (optLevel !== (sourceLevel + 1)) isVisible = false;
                    } else {
                        if (optLevel !== sourceLevel) isVisible = false;
                    }
                }

                if (!isVisible && $(this).val()) $(this).remove();
                else if ($(this).val()) count++;
            });
            $targetSelect.trigger('change.select2');
        }

        $('#filter_class, #promotion_status, #target_academic_year').on('change', updateTargetClasses);
        updateTargetClasses();
    });

    function refreshTable() { table.ajax.reload(); }

    function submitPromotion() {
        let studentIds = [];
        $('.student-checkbox:checked').each(function() { studentIds.push($(this).val()); });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Siswa', text: 'Silakan pilih siswa yang akan diproses.' });
            return;
        }

        if (!$('#target_academic_year').val()) {
            Swal.fire({ icon: 'warning', title: 'Target Tahun', text: 'Silakan pilih Tahun Pelajaran tujuan.' });
            return;
        }

        if (!$('#rolling_mode').is(':checked') && !$('#target_class').val()) {
            Swal.fire({ icon: 'warning', title: 'Target Kelas', text: 'Pilih Kelas Tujuan atau aktifkan mode Rolling.' });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Proses',
            text: 'Proses kenaikan/pindah rombel untuk ' + studentIds.length + ' siswa?',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Ya, Jalankan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnPromote').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>');
                $.post('{{ route("promotions.promote") }}', $('#promotionForm').serialize() + '&' + $.param({student_ids: studentIds}))
                    .done(res => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message });
                        table.ajax.reload();
                    })
                    .fail(err => { Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Error' }); })
                    .always(() => { $('#btnPromote').prop('disabled', false).html('<i class="fas fa-check-double mr-1"></i> PROSES AKHIR TAHUN'); });
            }
        });
    }

    function undoPromotion() {
        let studentIds = [];
        $('.student-checkbox:checked').each(function() { studentIds.push($(this).val()); });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Siswa', text: 'Pilih siswa yang prosesnya ingin dibatalkan.' });
            return;
        }

        Swal.fire({
            title: 'Batalkan Proses?',
            text: 'Kembalikan status siswa ke posisi sebelumnya?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("promotions.undo") }}', { _token: '{{ csrf_token() }}', student_ids: studentIds })
                    .done(res => { Swal.fire({ icon: 'success', title: 'Dibatalkan', text: res.message }); table.ajax.reload(); })
                    .fail(err => { Swal.fire({ icon: 'error', title: 'Gagal', text: err.responseJSON?.message || 'Error' }); });
            }
        });
    }
</script>
@endpush
