@extends($layout)

@section('title', 'Riwayat Tabungan')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-lg rounded-20 overflow-hidden mb-4">
            <div class="bg-gradient-info p-5 text-center text-white position-relative">
                <div class="position-relative z-index-1">
                    <div class="avatar-xl mx-auto mb-4 bg-white/20 backdrop-blur-md rounded-circle d-flex align-items-center justify-content-center border border-white/30" style="width: 100px; height: 100px;">
                        <i class="fas fa-user-graduate fa-3x"></i>
                    </div>
                    <h4 class="font-weight-black mb-1">{{ $student->nama_lengkap }}</h4>
                    <span class="badge badge-light px-3 py-2 rounded-pill font-weight-bold shadow-sm">
                        {{ $student->classGroup->kelas_lengkap ?? '-' }}
                    </span>
                </div>
                <div class="bg-circle-1" style="width: 200px; height: 200px; top: -50px; right: -50px;"></div>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="mb-4">
                    <span class="text-xs text-muted font-weight-bold uppercase d-block mb-1">Saldo Saat Ini</span>
                    <h2 class="font-weight-black text-info mb-0">Rp {{ number_format($student->savings->balance ?? 0, 0, ',', '.') }}</h2>
                </div>
                <hr class="my-4 border-light">
                <div class="row g-0">
                    <div class="col-6 pr-2">
                        <div class="p-3 bg-light rounded-15 text-center">
                            <span class="text-[10px] text-muted font-weight-bold uppercase d-block">NISN</span>
                            <span class="font-weight-bold text-dark">{{ $student->nisn ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-6 pl-2">
                        <div class="p-3 bg-light rounded-15 text-center">
                            <span class="text-[10px] text-muted font-weight-bold uppercase d-block">Status</span>
                            <span class="badge badge-success px-2 py-1">AKTIF</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.savings.index') }}" class="btn btn-outline-info btn-block rounded-pill mt-4 font-weight-bold">
                    <i class="fas fa-arrow-left mr-2"></i> KEMBALI
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-20 premium-card">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold mb-0 text-dark">Log Transaksi Tabungan</h5>
                <button onclick="window.print()" class="btn btn-light btn-sm rounded-pill px-3 font-weight-bold">
                    <i class="fas fa-print mr-1"></i> CETAK
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">TANGGAL</th>
                                <th>REF NO</th>
                                <th>JENIS</th>
                                <th class="text-right">JUMLAH</th>
                                <th class="text-right">SALDO AKHIR</th>
                                <th class="pr-4 text-center">PETUGAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                                <tr>
                                    <td class="pl-4 py-3">
                                        <span class="font-weight-bold text-dark d-block">{{ $t->created_at->format('d/m/Y') }}</span>
                                        <small class="text-muted">{{ $t->created_at->format('H:i') }}</small>
                                    </td>
                                    <td><code class="text-xs">{{ $t->reference_no }}</code></td>
                                    <td>
                                        @if($t->type == 'debit')
                                            <span class="badge badge-info-soft text-info font-weight-bold px-3 py-1">
                                                <i class="fas fa-arrow-down mr-1"></i> SETORAN
                                            </span>
                                        @else
                                            <span class="badge badge-warning-soft text-warning font-weight-bold px-3 py-1">
                                                <i class="fas fa-arrow-up mr-1"></i> PENARIKAN
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold {{ $t->type == 'debit' ? 'text-success' : 'text-danger' }}">
                                        {{ $t->type == 'debit' ? '+' : '-' }} {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-right font-weight-black text-dark">
                                        {{ number_format($t->current_balance, 0, ',', '.') }}
                                    </td>
                                    <td class="pr-4 text-center">
                                        <span class="badge badge-light border text-muted">{{ $t->creator->name ?? 'System' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transactions->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .badge-info-soft { background: #e0f2fe; }
    .badge-warning-soft { background: #fffbeb; }
    .z-index-1 { z-index: 1; }
</style>
@endsection
