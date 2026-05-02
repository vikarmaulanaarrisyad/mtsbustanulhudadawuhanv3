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

        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-arrow-right mr-1"></i> Tujuan Penempatan</h3>
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
                    <button type="button" onclick="submitPlacement()" class="btn btn-success btn-block" id="btnSubmit">
                        <i class="fas fa-check-circle mr-1"></i> Simpan Penempatan
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
</script>
@endpush
