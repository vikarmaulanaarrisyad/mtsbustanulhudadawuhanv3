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
                            <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
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
                        <select name="target_academic_year_id" class="form-control select2" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pindah ke Kelas</label>
                        <select name="target_class_group_id" id="target_class" class="form-control select2" required>
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            @foreach($targetClassGroups as $cg)
                                <option value="{{ $cg->id }}" data-level="{{ $cg->class_level }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
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

        // Filter Target Class based on Source Class & Status (Select2 Compatible)
        const allTargetOptions = $('#target_class').html();
        
        function updateTargetClasses() {
            let sourceLevel = $('#filter_class').find(':selected').data('level');
            let status = $('select[name=status]').val();
            let $targetSelect = $('#target_class');
            
            // Revert to all options first
            $targetSelect.html(allTargetOptions);

            if (sourceLevel !== undefined && sourceLevel !== "") {
                sourceLevel = parseInt(sourceLevel);
                
                $targetSelect.find('option').each(function() {
                    let targetLevel = $(this).data('level');
                    if (targetLevel !== undefined && targetLevel !== "") {
                        targetLevel = parseInt(targetLevel);
                        
                        let isMatch = false;
                        if (status === 'promoted') {
                            // Only show level + 1
                            isMatch = (targetLevel === (sourceLevel + 1));
                        } else {
                            // Only show same level (retained)
                            isMatch = (targetLevel === sourceLevel);
                        }

                        if (!isMatch && $(this).val() !== "") {
                            $(this).remove();
                        }
                    }
                });
            }
            
            // Refresh Select2
            $targetSelect.trigger('change.select2');
        }

        $('#filter_class, select[name=status]').on('change', function() {
            updateTargetClasses();
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }

    function submitPromotion(force = false) {
        let formData = $('#promotionForm').serialize();
        let studentIds = [];
        $('.student-checkbox:checked').each(function() {
            studentIds.push($(this).val());
        });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih minimal satu siswa.' });
            return;
        }

        const proceed = () => {
            $('#btnPromote').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
            
            let data = formData + '&' + $.param({student_ids: studentIds});
            if (force) data += '&force=1';

            $.post('{{ route("promotions.promote") }}', data)
                .done(response => {
                    if (response.status === 'warning') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: response.message,
                            showCancelButton: true,
                            confirmButtonText: 'Tetap Lanjutkan',
                            cancelButtonText: 'Batalkan',
                            confirmButtonColor: '#ffc107',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                submitPromotion(true); // Retry with force=1
                            } else {
                                $('#btnPromote').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Proses Kenaikan / Pindah');
                            }
                        });
                    } else {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                        $('#btnPromote').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Proses Kenaikan / Pindah');
                    }
                })
                .fail(xhr => {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    $('#btnPromote').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Proses Kenaikan / Pindah');
                });
        };

        if (force) {
            proceed();
        } else {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin memproses kenaikan/pindah rombel untuk ' + studentIds.length + ' siswa terpilih?',
                icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    proceed();
                }
            });
        }
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
