@extends($layout)

@section('title', 'Penempatan Rombel')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Siswa</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tahun Pelajaran</label>
                    <select id="filter_academic_year" class="form-control select2">
                        <option value="">-- Semua Tahun Pelajaran --</option>
                        <option value="none">-- Belum Memiliki Tahun --</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                        @endforeach
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
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle mr-1"></i> Menampilkan siswa yang <strong>belum memiliki kelas</strong>.
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> Tampilkan Siswa</button>
            </div>
        </div>

        <div class="card card-outline card-success shadow-sm border-0">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-success"><i class="fas fa-arrow-right mr-1"></i> Penempatan Manual</h3>
            </div>
            <div class="card-body">
                <form id="placementForm">
                    @csrf
                    <div class="form-group">
                        <label>Pilih Kelas Tujuan</label>
                        <select name="target_class_group_id" class="form-control select2" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun Pelajaran</label>
                        <select name="target_academic_year_id" class="form-control select2" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
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
                <h3 class="card-title font-weight-bold"><i class="fas fa-magic mr-1"></i> Plotting Otomatis</h3>
            </div>
            <div class="card-body">
                <form id="autoPlacementForm">
                    @csrf
                    <div class="form-group">
                        <label>Tahun Pelajaran</label>
                        <select name="academic_year_id" class="form-control select2" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Kelas Tujuan (Multi)</label>
                        <select name="class_group_ids[]" class="form-control select2" multiple required data-placeholder="Pilih beberapa kelas...">
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
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

    $(function() {
        table = $('#studentTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, paging: false, info: true,
            ajax: {
                url: '{{ route("student-placements.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.status_id = $('#filter_status').val();
                }
            },
            columns: [
                { data: 'checkbox', searchable: false, sortable: false },
                { data: 'nis' },
                { data: 'nama_lengkap' },
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
        
        Swal.fire({
            title: 'Konfirmasi Plotting Otomatis',
            text: 'Sistem akan membagi siswa baru (tanpa rombel) ke dalam kelas terpilih dengan pembagian gender yang merata. Lanjutkan?',
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
