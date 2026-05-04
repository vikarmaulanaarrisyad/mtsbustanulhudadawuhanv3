@extends($layout)

@section('title', 'Penempatan Rombel')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> 1. Cari Siswa</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tahun Pelajaran (Target Penempatan)</label>
                    <select id="filter_academic_year" class="form-control select2">
                        <option value="">-- Semua Tahun Pelajaran --</option>
                        <option value="none">-- Belum Memiliki Tahun --</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $ay->academic_year }} ({{ $ay->semester->semester_name }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted italic">Pilih tahun ajaran baru tempat siswa akan bersekolah.</small>
                </div>
                <div class="form-group">
                    <label>Tingkat Kelas</label>
                    <select id="filter_class_level" class="form-control select2">
                        <option value="">-- Semua Tingkat --</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}">Tingkat {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>Status Siswa</label>
                    <select id="filter_status" class="form-control select2">
                        <option value="">-- Semua Status --</option>
                        @foreach($studentStatuses as $ss)
                            <option value="{{ $ss->id }}">{{ $ss->student_status_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> Tampilkan Siswa</button>
            </div>
        </div>

        <div class="card card-outline card-success shadow-sm border-0">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-success"><i class="fas fa-arrow-right mr-1"></i> 2. Penempatan Manual</h3>
            </div>
            <div class="card-body">
                <form id="placementForm">
                    @csrf
                    <div class="form-group">
                        <label>Tahun Pelajaran Tujuan</label>
                        <select name="target_academic_year_id" id="target_academic_year" class="form-control select2" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Kelas Tujuan</label>
                        <select name="target_class_group_id" id="target_class" class="form-control select2" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" data-year="{{ $cg->academic_year_id }}" data-level="{{ $cg->class_level }}">
                                    {{ $cg->class_group }} - {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Penempatan Siswa Pindahan">
                    </div>
                    <hr>
                    <button type="button" onclick="submitPlacement()" class="btn btn-success btn-block shadow-sm" id="btnSubmit">
                        <i class="fas fa-check-circle mr-1"></i> Simpan Penempatan
                    </button>
                </form>
            </div>
        </div>

        {{-- PLOTTING OTOMATIS --}}
        <div class="card card-outline card-primary shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title font-weight-bold"><i class="fas fa-magic mr-1"></i> 2. Plotting Otomatis</h3>
            </div>
            <div class="card-body">
                <form id="autoPlacementForm">
                    @csrf
                    <div class="form-group">
                        <label>Tahun Pelajaran Tujuan</label>
                        <select name="academic_year_id" id="auto_target_academic_year" class="form-control select2" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Kelas Tujuan (Multi)</label>
                        <select name="class_group_ids[]" id="auto_target_classes" class="form-control select2" multiple required data-placeholder="Pilih beberapa kelas...">
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" data-year="{{ $cg->academic_year_id }}" data-level="{{ $cg->class_level }}">
                                    {{ $cg->class_group }} - {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kapasitas Maksimal Per Kelas</label>
                        <input type="number" name="max_capacity" class="form-control" value="32" min="1" required>
                        <small class="text-muted italic">Sistem akan memperhitungkan jumlah siswa yang sudah ada di kelas tersebut.</small>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="gender_balanced" name="gender_balanced" value="1" checked>
                            <label class="custom-control-label text-sm" for="gender_balanced">Bagi Merata Laki-laki & Perempuan</label>
                        </div>
                    </div>
                    <hr>
                    <button type="button" onclick="submitAutoPlacement()" class="btn btn-primary btn-block shadow-sm" id="btnAutoSubmit">
                        <i class="fas fa-bolt mr-1"></i> Mulai Plotting Otomatis
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Daftar Siswa Tanpa Rombel</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" id="btnCheckAll"><i class="far fa-check-square mr-1"></i> Pilih Semua</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="studentTable">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox" id="checkAll"></th>
                                <th>NIS/NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
    let table;
    const allTargetOptions = $('#target_class').html();
    const allAutoOptions = $('#auto_target_classes').html();

    $(function() {
        table = $('#studentTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, paging: false, info: true,
            ajax: {
                url: '{{ route("student-placements.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_level = $('#filter_class_level').val();
                    d.status_id = $('#filter_status').val();
                }
            },
            columns: [
                { data: 'checkbox', searchable: false, sortable: false },
                { data: 'nis' },
                { data: 'nama_lengkap' },
                { data: 'kelas_info', searchable: false },
                { data: 'status' },
            ]
        });

        $('#checkAll').on('change', function() {
            $('.student-checkbox').prop('checked', $(this).prop('checked'));
        });

        $('#btnCheckAll').on('click', function() {
            let target = !$('#checkAll').prop('checked');
            $('#checkAll').prop('checked', target).trigger('change');
        });

        // Filter Target Classes (Manual)
        function updateTargetClasses() {
            let targetYearId = $('#target_academic_year').val();
            let filterLevel = $('#filter_class_level').val();
            let $targetSelect = $('#target_class');
            
            $targetSelect.html(allTargetOptions);

            $targetSelect.find('option').each(function() {
                let optYear = $(this).data('year');
                let optLevel = $(this).data('level');
                let val = $(this).val();

                if (val === "") return;

                let isVisible = true;
                if (targetYearId && optYear != targetYearId) isVisible = false;
                if (isVisible && filterLevel && optLevel != filterLevel) isVisible = false;

                if (!isVisible) $(this).remove();
            });
            
            $targetSelect.trigger('change.select2');
        }

        // Filter Target Classes (Auto)
        function updateAutoTargetClasses() {
            let targetYearId = $('#auto_target_academic_year').val();
            let filterLevel = $('#filter_class_level').val();
            let $targetSelect = $('#auto_target_classes');
            
            $targetSelect.html(allAutoOptions);

            $targetSelect.find('option').each(function() {
                let optYear = $(this).data('year');
                let optLevel = $(this).data('level');
                let isVisible = true;

                if (targetYearId && optYear != targetYearId) isVisible = false;
                if (isVisible && filterLevel && optLevel != filterLevel) isVisible = false;

                if (!isVisible) $(this).remove();
            });
            
            $targetSelect.trigger('change.select2');
        }

        $('#filter_class_level, #target_academic_year').on('change', updateTargetClasses);
        $('#filter_class_level, #auto_target_academic_year').on('change', updateAutoTargetClasses);

        // Sync Target Years with Filter Year to avoid confusion
        $('#filter_academic_year').on('change', function() {
            let val = $(this).val();
            if (val && val !== 'none') {
                $('#target_academic_year').val(val).trigger('change.select2');
                $('#auto_target_academic_year').val(val).trigger('change.select2');
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
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin memproses penempatan rombel untuk ' + studentIds.length + ' siswa terpilih?',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                
                $.post('{{ route("student-placements.store") }}', formData + '&' + $.param({student_ids: studentIds}))
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    })
                    .always(() => {
                        $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Simpan Penempatan');
                    });
            }
        });
    }

    function submitAutoPlacement() {
        let formData = $('#autoPlacementForm').serialize();
        
        if (!$('#auto_target_classes').val() || $('#auto_target_classes').val().length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih minimal satu kelas tujuan.' });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Plotting Otomatis',
            text: 'Sistem akan membagi siswa (tanpa rombel) ke dalam kelas terpilih. Lanjutkan?',
            icon: 'info', showCancelButton: true, confirmButtonColor: '#007bff'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnAutoSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                
                $.post('{{ route("student-placements.auto") }}', formData)
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    })
                    .always(() => {
                        $('#btnAutoSubmit').prop('disabled', false).html('<i class="fas fa-bolt mr-1"></i> Mulai Plotting Otomatis');
                    });
            }
        });
    }
</script>
@endpush
