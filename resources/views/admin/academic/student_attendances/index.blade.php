@extends('layouts.app')

@section('title', 'Laporan Presensi Siswa')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Presensi</h3>
                    <div>
                        <a href="{{ route('student-attendances.scanner') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-camera mr-1"></i> Buka Scanner
                        </a>
                        <button onclick="printCards()" class="btn btn-sm btn-info">
                            <i class="fas fa-address-card mr-1"></i> Cetak Kartu QR
                        </button>
                    </div>
                </div>
            </x-slot>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" id="filter_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kelas</label>
                        <select id="filter_class" class="form-control select2">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tahun Pelajaran</label>
                        <select id="filter_academic_year" class="form-control select2">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button onclick="refreshTable()" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tampilkan</button>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Log Presensi Siswa</h3>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>NIS</th>
                    <th>NAMA SISWA</th>
                    <th>KELAS</th>
                    <th>JAM</th>
                    <th>STATUS</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('.table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: { 
                url: '{{ route("student-attendances.data") }}',
                data: function(d) {
                    d.class_group_id = $('#filter_class').val();
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.date = $('#filter_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'nis' },
                { data: 'student_name' },
                { data: 'class_name' },
                { data: 'time' },
                { data: 'status_badge' },
            ]
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }

    function printCards() {
        let classId = $('#filter_class').val();
        let url = '{{ route("student-attendances.cards") }}';
        if (classId) {
            url += '?class_group_id=' + classId;
        }
        window.open(url, '_blank');
    }
</script>
@endpush
