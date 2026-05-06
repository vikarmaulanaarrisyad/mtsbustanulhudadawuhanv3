@extends($layout)

@section('title', 'Manajemen Penggajian')
@section('subtitle', 'Data Gaji Guru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body bg-gradient-info text-white p-4" style="border-radius: 15px;">
                <h3 class="font-weight-bold mb-1"><i class="fas fa-money-check-alt mr-2"></i> Penggajian Bulanan</h3>
                <p class="mb-0 opacity-8">Kelola dan cetak slip gaji guru setiap bulannya.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-3 border-bottom">
                <form action="{{ route('payrolls.index') }}" method="GET" class="row align-items-center">
                    <div class="col-md-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Bulan</label>
                        <select name="month" class="form-control" onchange="this.form.submit()">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Tahun</label>
                        <select name="year" class="form-control" onchange="this.form.submit()">
                            @for($i=date('Y')-2; $i<=date('Y')+1; $i++)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 text-right mt-4">
                        <button type="button" onclick="generatePayroll()" class="btn btn-primary font-weight-bold shadow-sm">
                            <i class="fas fa-cogs mr-1"></i> Generate Gaji Bulan Ini
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="payrollTable" style="width:100%">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>Nama Guru</th>
                                <th>NIP</th>
                                <th>Take Home Pay</th>
                                <th>Status</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }
    #payrollTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #payrollTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #payrollTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 6px 15px rgba(0,0,0,0.06); background: #f8fbff; }
    #payrollTable td { border: none; padding: 1rem 0.75rem; vertical-align: middle; }
    #payrollTable td:first-child { border-radius: 12px 0 0 12px; }
    #payrollTable td:last-child { border-radius: 0 12px 12px 0; }
</style>

@endsection

@include('includes.datatable')

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('#payrollTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: { 
                url: '{{ route("payrolls.data") }}',
                data: function(d) {
                    d.month = $('select[name=month]').val();
                    d.year = $('select[name=year]').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'teacher_name', name: 'teacher.name', className: 'font-weight-bold' },
                { data: 'teacher_nip', name: 'teacher.nip' },
                { data: 'net_salary_formatted', name: 'net_salary', className: 'text-success font-weight-bold' },
                { data: 'status_badge', name: 'payment_status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    function generatePayroll() {
        let month = $('select[name=month]').val();
        let year = $('select[name=year]').val();
        
        Swal.fire({
            title: 'Generate Gaji?',
            text: "Draf gaji akan dibuat untuk semua guru aktif pada bulan ini.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Generate!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });
                $.post('{{ route("payrolls.generate") }}', {
                    _token: '{{ csrf_token() }}',
                    month: month,
                    year: year
                }).done((res) => {
                    Swal.fire('Berhasil!', res.message, 'success');
                    table.ajax.reload();
                }).fail((err) => {
                    Swal.fire('Gagal!', err.responseJSON.message || 'Terjadi kesalahan', 'error');
                });
            }
        });
    }
</script>
@endpush
