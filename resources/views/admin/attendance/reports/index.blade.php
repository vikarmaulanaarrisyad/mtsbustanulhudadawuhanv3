@extends('layouts.app')

@section('title', 'Laporan Presensi Guru')
@section('subtitle', 'Persuratan')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-file-alt mr-1"></i> Filter Laporan</h3>
            </x-slot>

            <form id="filterForm" action="{{ route('attendance-reports.print') }}" target="_blank">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Guru / Staf</label>
                            <select name="teacher_id" id="teacher_id" class="form-control select2">
                                <option value="">-- Semua Guru --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ date('Y-m-01') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ date('Y-m-t') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="button" onclick="refreshTable()" class="btn btn-info flex-grow-1 mr-2">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-print"></i> Cetak PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </x-card>

        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Log Presensi</h3>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>TANGGAL</th>
                    <th>NAMA GURU</th>
                    <th>JAM MASUK</th>
                    <th>JAM PULANG</th>
                    <th>STATUS</th>
                    <th>IP ADDRESS</th>
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
                url: '{{ route("attendance-reports.data") }}',
                data: function(d) {
                    d.teacher_id = $('#teacher_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'date' },
                { data: 'teacher_name' },
                { data: 'check_in' },
                { data: 'check_out' },
                { data: 'status_badge' },
                { data: 'check_in_ip' },
            ]
        });
    });

    function refreshTable() {
        table.ajax.reload();
    }
</script>
@endpush
