@extends('layouts.app')

@section('title', 'Manajemen Kelulusan')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tahun Pelajaran</label>
                    <select id="filter_academic_year" class="form-control select2">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <select id="filter_class" class="form-control select2">
                        <option value="">-- Semua Kelas (Tingkat Akhir) --</option>
                        @foreach($classGroups as $cg)
                            <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Status Kelulusan</label>
                    <select id="filter_is_graduated" class="form-control">
                        <option value="0">Belum Lulus (Siswa Aktif)</option>
                        <option value="1">Sudah Lulus</option>
                    </select>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> Tampilkan</button>
            </div>
        </div>

        <div class="card card-outline card-success" id="graduateBox">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Proses Lulus</h3>
            </div>
            <div class="card-body">
                <form id="graduationForm">
                    @csrf
                    <div class="form-group">
                        <label>Tanggal Lulus / Keluar</label>
                        <input type="date" name="exit_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Lulus Tahun Ajaran 2025/2026">
                    </div>
                    <hr>
                    <button type="button" onclick="submitGraduation()" class="btn btn-success btn-block" id="btnGraduate">
                        <i class="fas fa-check-circle mr-1"></i> Set Lulus Siswa Terpilih
                    </button>
                </form>
            </div>
        </div>

        <div class="card card-outline card-danger d-none" id="undoBox">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-undo mr-1"></i> Batal Lulus</h3>
            </div>
            <div class="card-body">
                <p class="text-sm text-muted">Gunakan fitur ini untuk mengembalikan status siswa menjadi Aktif kembali jika terjadi kesalahan input.</p>
                <button type="button" onclick="undoGraduation()" class="btn btn-danger btn-block">
                    <i class="fas fa-history mr-1"></i> Batalkan Kelulusan
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info-circle"></i> Petunjuk Cetak SKL</h5>
            Untuk mencetak SKL, silakan gunakan filter <strong>Status Kelulusan: "Sudah Lulus"</strong> kemudian klik tombol <strong>"Tampilkan"</strong>. Tombol cetak akan muncul di kolom aksi.
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Daftar Siswa</h3>
                <div class="card-tools">
                    <div class="custom-control custom-checkbox d-inline mr-2">
                        <input class="custom-control-input" type="checkbox" id="checkAll">
                        <label for="checkAll" class="custom-control-label">Pilih Semua</label>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="studentTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>NIS/NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas Terakhir</th>
                                <th width="10%">AKSI</th>
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
                url: '{{ route("graduations.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.class_group_id = $('#filter_class').val();
                    d.is_graduated = $('#filter_is_graduated').val();
                }
            },
            columns: [
                { data: 'checkbox', searchable: false, sortable: false },
                { data: 'nis' },
                { data: 'nama_lengkap' },
                { data: 'kelas' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });

        $('#filter_is_graduated').on('change', function() {
            if ($(this).val() == '1') {
                $('#graduateBox').addClass('d-none');
                $('#undoBox').removeClass('d-none');
            } else {
                $('#graduateBox').removeClass('d-none');
                $('#undoBox').addClass('d-none');
            }
            refreshTable();
        });

        $('#checkAll').on('click', function() {
            $('.student-checkbox').prop('checked', this.checked);
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }

    function submitGraduation() {
        let formData = $('#graduationForm').serialize();
        let studentIds = [];
        $('.student-checkbox:checked').each(function() {
            studentIds.push($(this).val());
        });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih minimal satu siswa.' });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Kelulusan',
            text: 'Apakah Anda yakin ingin meluluskan ' + studentIds.length + ' siswa terpilih?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnGraduate').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                
                $.post('{{ route("graduations.graduate") }}', formData + '&' + $.param({student_ids: studentIds}))
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    })
                    .always(() => {
                        $('#btnGraduate').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Set Lulus Siswa Terpilih');
                    });
            }
        });
    }

    function undoGraduation() {
        let studentIds = [];
        $('.student-checkbox:checked').each(function() {
            studentIds.push($(this).val());
        });

        if (studentIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan pilih siswa yang kelulusannya ingin dibatalkan.' });
            return;
        }

        Swal.fire({
            title: 'Batal Lulus?',
            text: 'Status siswa akan dikembalikan menjadi Aktif. Lanjutkan?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("graduations.undo") }}', {
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
