@extends($layout)

@section('title', 'Detail Gaji Guru')
@section('subtitle', 'Rincian Penggajian')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Informasi Pegawai -->
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-gradient-info text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-user-tie mr-2"></i> Info Pegawai</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto bg-soft-info rounded-circle d-flex align-items-center justify-content-center text-info font-weight-bold shadow-sm" style="width:80px;height:80px;background:#e0f7fa;font-size:2rem;">
                        {{ substr($payroll->teacher->name, 0, 1) }}
                    </div>
                    <h5 class="mt-3 font-weight-bold">{{ $payroll->teacher->name }}</h5>
                    <p class="text-muted mb-0">{{ $payroll->teacher->position ?? 'Guru' }}</p>
                </div>
                
                <ul class="list-group list-group-flush text-sm">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">NIP/NIK</span>
                        <span class="font-weight-bold">{{ $payroll->teacher->nip ?? ($payroll->teacher->nik ?? '-') }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <span class="font-weight-bold">{{ $payroll->teacher->employment_status ?? '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Periode Gaji</span>
                        <span class="font-weight-bold">{{ date('F', mktime(0,0,0,$payroll->month,1)) }} {{ $payroll->year }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Status Pembayaran</span>
                        @if($payroll->payment_status == 'Paid')
                            <span class="badge badge-success px-2 py-1">Dibayar tgl {{ \Carbon\Carbon::parse($payroll->payment_date)->format('d M Y') }}</span>
                        @else
                            <span class="badge badge-warning px-2 py-1">Menunggu Pembayaran</span>
                        @endif
                    </li>
                </ul>

                @if($payroll->payment_status == 'Pending')
                <div class="mt-4 text-center">
                    <form action="{{ route('payrolls.pay', $payroll->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block rounded-pill font-weight-bold shadow-sm" onclick="return confirm('Tandai gaji ini telah dibayar?')">
                            <i class="fas fa-check-circle mr-1"></i> Tandai Dibayar
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Rincian Gaji -->
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-file-invoice-dollar text-info mr-2"></i> Rincian Komponen Gaji</h6>
                <a href="{{ route('payrolls.print', $payroll->id) }}" class="btn btn-sm btn-outline-primary rounded-pill mr-2">
                    <i class="fas fa-print mr-1"></i> Cetak Slip
                </a>
                <a href="{{ route('payrolls.download_pdf', $payroll->id) }}" target="_blank" class="btn btn-sm btn-danger rounded-pill">
                    <i class="fas fa-file-pdf mr-1"></i> Preview PDF
                </a>
            </div>
            <div class="card-body">
                
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table">
                        <thead class="bg-light">
                            <tr>
                                <th>Keterangan</th>
                                <th class="text-right">Nominal</th>
                                @if($payroll->payment_status == 'Pending')
                                <th width="50" class="text-center"><i class="fas fa-cog"></i></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Base Salary -->
                            <tr>
                                <td class="font-weight-bold text-dark"><i class="fas fa-money-bill-wave text-success mr-2"></i> Gaji Pokok</td>
                                <td class="text-right font-weight-bold text-success">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                                @if($payroll->payment_status == 'Pending')
                                <td></td>
                                @endif
                            </tr>

                            <!-- Allowances -->
                            @if($payroll->details->where('type', 'allowance')->count() > 0)
                                <tr><td colspan="3" class="bg-light text-muted small font-weight-bold uppercase">TUNJANGAN</td></tr>
                                @foreach($payroll->details->where('type', 'allowance') as $d)
                                <tr>
                                    <td><i class="fas fa-plus-circle text-info mr-2"></i> {{ $d->name }}</td>
                                    <td class="text-right">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                                    @if($payroll->payment_status == 'Pending')
                                    <td class="text-center">
                                        <form action="{{ route('payrolls.destroyDetail', $d->id) }}" method="POST" class="d-inline">
                                            @csrf @method('delete')
                                            <button class="btn btn-xs btn-outline-danger" onclick="return confirm('Hapus komponen?')"><i class="fas fa-times"></i></button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            @endif

                            <!-- Deductions -->
                            @if($payroll->details->where('type', 'deduction')->count() > 0)
                                <tr><td colspan="3" class="bg-light text-muted small font-weight-bold uppercase">POTONGAN</td></tr>
                                @foreach($payroll->details->where('type', 'deduction') as $d)
                                <tr>
                                    <td><i class="fas fa-minus-circle text-danger mr-2"></i> {{ $d->name }}</td>
                                    <td class="text-right text-danger">- Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                                    @if($payroll->payment_status == 'Pending')
                                    <td class="text-center">
                                        <form action="{{ route('payrolls.destroyDetail', $d->id) }}" method="POST" class="d-inline">
                                            @csrf @method('delete')
                                            <button class="btn btn-xs btn-outline-danger" onclick="return confirm('Hapus komponen?')"><i class="fas fa-times"></i></button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot class="bg-light-info">
                            <tr>
                                <th class="text-right uppercase">TAKE HOME PAY :</th>
                                <th class="text-right text-success font-weight-bold" style="font-size: 1.25rem;">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</th>
                                @if($payroll->payment_status == 'Pending')
                                <th></th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($payroll->payment_status == 'Pending')
                <hr>
                <h6 class="font-weight-bold text-dark mt-4 mb-3"><i class="fas fa-plus-circle mr-1"></i> Tambah Komponen Gaji</h6>
                <form action="{{ route('payrolls.storeDetail', $payroll->id) }}" method="POST" class="row">
                    @csrf
                    <div class="col-md-3">
                        <select name="type" class="form-control rounded-10" required>
                            <option value="allowance">Tunjangan (+)</option>
                            <option value="deduction">Potongan (-)</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="name" class="form-control rounded-10" placeholder="Keterangan (Cth: Lembur)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="amount" class="form-control rounded-10" placeholder="Nominal (Rp)" required min="1">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary rounded-10 btn-block"><i class="fas fa-plus"></i></button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-info { background: #f0f7f9; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .rounded-10 { border-radius: 10px; }
</style>

@endsection
