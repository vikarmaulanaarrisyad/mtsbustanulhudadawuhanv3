@extends($layout)

@section('title', 'Kenaikan Kelas & Rombel')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="alert alert-default-info border-info shadow-sm mb-4">
            <h5><i class="icon fas fa-info-circle text-info"></i> Alur Akhir Tahun Pelajaran</h5>
            <p class="mb-1">Untuk menjaga integritas data, disarankan mengikuti urutan berikut:</p>
            <ol class="mb-0">
                <li><strong>Proses Kelulusan</strong>: Luluskan siswa kelas akhir (6, 9, 12) terlebih dahulu agar status mereka menjadi "Alumni".</li>
                <li><strong class="text-primary">Proses Kenaikan Kelas</strong>: Setelah kelas akhir kosong, barulah naikkan siswa ke tingkat berikutnya (misal: 5 ke 6).</li>
                <li><strong>Penempatan Siswa Baru</strong>: Terakhir, masukkan siswa baru dari PPDB ke kelas awal (1, 7, 10).</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Sumber Data</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tahun Pelajaran Saat Ini</label>
                    <select id="filter_academic_year" class="form-control select2">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $ay->id == ($currentAY->id ?? '') ? 'selected' : '' }}>
                                {{ $ay->academic_year }} ({{ $ay->semester->semester_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kelas Saat Ini</label>
                    <select id="filter_class" class="form-control select2">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($sourceClassGroups as $cg)
                            <option value="{{ $cg->id }}" data-level="{{ $cg->class_level }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> Tampilkan Siswa</button>
            </div>
        </div>

        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-arrow-right mr-1"></i> Proses Tujuan</h3>
            </div>
            <div class="card-body">
                <form id="promotionForm">
                    @csrf
                    <div class="form-group">
                        <label>Pindah ke Tahun Pelajaran</label>
                        <select name="target_academic_year_id" id="target_academic_year" class="form-control select2" required>
                            <option value="">-- Pilih Tahun Ajaran Tujuan --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->academic_year }} ({{ $ay->semester->semester_name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="rolling_mode" name="rolling_mode">
                            <label class="custom-control-label" for="rolling_mode">Ploting Kelas Nanti (Rolling)</label>
                        </div>
                        <small class="text-muted">Aktifkan jika ingin menaikkan tingkat siswa tanpa menentukan kelas sekarang.</small>
                    </div>

                    <div class="form-group" id="target_class_container">
                        <label>Pindah ke Kelas</label>
                        <select name="target_class_group_id" id="target_class" class="form-control select2">
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            @foreach($targetClassGroups as $cg)
                                <option value="{{ $cg->id }}" data-year="{{ $cg->academic_year_id }}" data-level="{{ $cg->class_level }}">
                                    {{ $cg->class_group }} - {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="promotion_status" class="form-control">
                            <option value="promoted">Naik Kelas</option>
                            <option value="retained">Tinggal Kelas (Di Tahun Ajaran Baru)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Kenaikan Kelas Reguler">
                    </div>
                    <hr>
                    <button type="button" onclick="submitPromotion()" class="btn btn-success btn-block" id="btnPromote">
                        <i class="fas fa-check-circle mr-1"></i> Proses Akhir Tahun
                    </button>
                    <button type="button" onclick="undoPromotion()" class="btn btn-outline-danger btn-block btn-sm mt-2">
                        <i class="fas fa-undo mr-1"></i> Batalkan Proses Terakhir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Daftar Siswa</h3>
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
                                <th>Kelas (Filter)</th>
                                <th>Status Proses</th>
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
                { data: 'checkbox', searchable: false, sortable: false },
                { data: 'nis' },
                { data: 'nama_lengkap' },
                { data: 'kelas' },
                { data: 'history_info', searchable: false },
            ]
        });

        $('#checkAll').on('click', function() {
            $('.student-checkbox').prop('checked', this.checked);
        });

        $('#btnCheckAll').on('click', function() {
            let checked = $('#checkAll').prop('checked');
            $('#checkAll').prop('checked', !checked).trigger('click');
        });

        // Handle Rolling Mode Switch
        $('#rolling_mode').on('change', function() {
            if (this.checked) {
                $('#target_class_container').slideUp();
                $('#target_class').val('').trigger('change.select2');
            } else {
                $('#target_class_container').slideDown();
            }
        });

        // Filter Target Class based on Year, Source Class & Status
        function updateTargetClasses() {
            let targetYearId = $('#target_academic_year').val();
            let sourceLevel = $('#filter_class').find(':selected').data('level');
            let status = $('#promotion_status').val();
            let $targetSelect = $('#target_class');
            
            // Revert to all options first
            $targetSelect.html(allTargetOptions);

            $targetSelect.find('option').each(function() {
                let optYear = $(this).data('year');
                let optLevel = $(this).data('level');
                let val = $(this).val();

                if (val === "") return; // Skip placeholder

                let isVisible = true;

                // 1. Filter by Academic Year
                if (targetYearId && optYear != targetYearId) {
                    isVisible = false;
                }

                // 2. Filter by Level (if source class is selected)
                if (isVisible && sourceLevel !== undefined && sourceLevel !== "") {
                    sourceLevel = parseInt(sourceLevel);
                    optLevel = parseInt(optLevel);
                    
                    if (status === 'promoted') {
                        if (optLevel !== (sourceLevel + 1)) isVisible = false;
                    } else {
                        if (optLevel !== sourceLevel) isVisible = false;
                    }
                }

                if (!isVisible) {
                    $(this).remove();
                }
            });
            
            $targetSelect.trigger('change.select2');
        }

        $('#filter_class, #promotion_status, #target_academic_year').on('change', function() {
            updateTargetClasses();
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }

    function submitPromotion() {
        let formData = $('#promotionForm').serialize();
        let studentIds = [];
        $('.student-checkbox:checked').each(function() {
            studentIds.push($(this).val());
        });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih minimal satu siswa.' });
            return;
        }

        // Validate target year
        if (!$('#target_academic_year').val()) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih Tahun Pelajaran tujuan.' });
            return;
        }

        // Validate target class if not in rolling mode
        if (!$('#rolling_mode').is(':checked') && !$('#target_class').val()) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih Kelas Tujuan atau aktifkan mode Rolling.' });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin memproses kenaikan/pindah rombel untuk ' + studentIds.length + ' siswa terpilih?',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnPromote').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                
                let data = formData + '&' + $.param({student_ids: studentIds});

                $.post('{{ route("promotions.promote") }}', data)
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                        $('#btnPromote').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Proses Akhir Tahun');
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                        $('#btnPromote').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Proses Akhir Tahun');
                    });
            }
        });
    }

    function undoPromotion() {
        let studentIds = [];
        $('.student-checkbox:checked').each(function() {
            studentIds.push($(this).val());
        });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih minimal satu siswa yang ingin dibatalkan proses terakhirnya.' });
            return;
        }

        Swal.fire({
            title: 'Batalkan Proses?',
            text: 'Siswa akan dikembalikan ke posisi (Kelas & Tahun Ajaran) sebelum proses terakhir dilakukan. Lanjutkan?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("promotions.undo") }}', {
                    _token: '{{ csrf_token() }}',
                    student_ids: studentIds
                }).done(response => {
                    Swal.fire({ icon: 'success', title: 'Dibatalkan', text: response.message });
                    table.ajax.reload();
                }).fail(xhr => {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                });
            }
        });
    }
</script>
@endpush
