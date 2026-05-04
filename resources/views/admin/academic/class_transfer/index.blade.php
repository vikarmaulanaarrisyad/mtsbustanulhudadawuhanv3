@extends($layout)

@section('title', 'Mutasi Rombel (Internal)')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Sumber</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tahun Pelajaran</label>
                    <input type="text" class="form-control" value="{{ $currentAy->academic_year }}" disabled>
                    <input type="hidden" id="filter_academic_year" value="{{ $currentAy->id }}">
                </div>
                <div class="form-group">
                    <label>Kelas Asal</label>
                    <select id="filter_class" class="form-control select2">
                        <option value="">-- Pilih Kelas Asal --</option>
                        @foreach($classGroups as $cg)
                            <option value="{{ $cg->id }}" data-level="{{ $cg->class_level }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> Tampilkan Siswa</button>
            </div>
        </div>

        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exchange-alt mr-1"></i> Proses Mutasi</h3>
            </div>
            <div class="card-body">
                <form id="transferForm">
                    @csrf
                    <div class="form-group">
                        <label>Pindah ke Rombel (Target)</label>
                        <select name="target_class_group_id" id="target_class" class="form-control select2" required>
                            <option value="">-- Pilih Rombel Tujuan --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" data-level="{{ $cg->class_level }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Pindah ke Rombel B">
                    </div>
                    <hr>
                    <button type="button" onclick="submitTransfer()" class="btn btn-success btn-block" id="btnTransfer">
                        <i class="fas fa-check-circle mr-1"></i> Proses Pindah Rombel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users mr-1"></i> Daftar Siswa</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="studentTable">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox" id="checkAll"></th>
                                <th>NIS / NISN</th>
                                <th>Nama Lengkap</th>
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
                url: '{{ route("class-transfers.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_group_id = $('#filter_class').val();
                }
            },
            columns: [
                { data: 'checkbox', searchable: false, sortable: false },
                { data: 'nis_nisn' },
                { data: 'nama_lengkap' },
            ]
        });

        $('#checkAll').on('click', function() {
            $('.student-checkbox').prop('checked', this.checked);
        });

        // Filter Target Class based on Source Class (Same Level)
        const allTargetOptions = $('#target_class').html();
        
        $('#filter_class').on('change', function() {
            let sourceLevel = $(this).find(':selected').data('level');
            let $targetSelect = $('#target_class');
            let sourceId = $(this).val();
            
            $targetSelect.html(allTargetOptions);

            if (sourceLevel !== undefined && sourceLevel !== "") {
                sourceLevel = parseInt(sourceLevel);
                
                $targetSelect.find('option').each(function() {
                    let targetLevel = $(this).data('level');
                    let targetId = $(this).val();

                    if (targetLevel !== undefined && targetLevel !== "") {
                        targetLevel = parseInt(targetLevel);
                        // Only show same level AND different class
                        if (targetLevel !== sourceLevel || targetId == sourceId) {
                            if (targetId !== "") $(this).remove();
                        }
                    }
                });
            }
            $targetSelect.trigger('change.select2');
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }

    function submitTransfer() {
        let formData = $('#transferForm').serialize();
        let studentIds = [];
        $('.student-checkbox:checked').each(function() {
            studentIds.push($(this).val());
        });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih minimal satu siswa.' });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Mutasi',
            text: 'Apakah Anda yakin ingin memindahkan ' + studentIds.length + ' siswa ke rombel tujuan?',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnTransfer').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                
                $.post('{{ route("class-transfers.transfer") }}', formData + '&' + $.param({student_ids: studentIds}))
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    })
                    .always(() => {
                        $('#btnTransfer').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Proses Pindah Rombel');
                    });
            }
        });
    }
</script>
@endpush
