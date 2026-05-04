@extends($layout)

@section('title', 'Daftar Alumni')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Data Alumni</h3>
                    <div class="d-flex align-items-center">
                        <label class="mr-2 mb-0">Tahun Lulus:</label>
                        <select id="filter_academic_year" class="form-control form-control-sm select2" style="width: 200px;" onchange="refreshTable()">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="alumniTable">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th>NIS/NISN</th>
                                <th>NAMA LENGKAP</th>
                                <th>TAHUN LULUS</th>
                                <th>KELAS TERAKHIR</th>
                                <th>TGL KELUAR</th>
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
        table = $('#alumniTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route("alumni.data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_academic_year').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'nis' },
                { data: 'nama_lengkap' },
                { data: 'ta_lulus' },
                { data: 'kelas_terakhir' },
                { data: 'tanggal_keluar' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }
</script>
@endpush
