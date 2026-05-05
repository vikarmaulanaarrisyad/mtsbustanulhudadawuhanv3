@extends($layout)

@section('title', 'Riwayat Izin Saya')
@section('subtitle', 'Kepegawaian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-success overflow-hidden position-relative animate__animated animate__fadeInDown" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-history mr-2"></i> 
                            Riwayat Pengajuan Izin
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pantau status semua pengajuan izin, sakit, dan cuti Anda secara transparan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block text-white opacity-2">
                        <i class="fas fa-paper-plane fa-8x"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 animate__animated animate__fadeInUp">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark">Data Semua Pengajuan</h5>
                <button onclick="window.history.back()" class="btn btn-light btn-sm rounded-pill px-4 font-weight-bold">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-md-4 p-2">
                    <table class="table table-hover align-middle mb-0" id="myPermitTable" style="width:100%">
                        <thead class="bg-light text-uppercase">
                            <tr>
                                <th>Jenis</th>
                                <th class="d-none d-md-table-cell">Tanggal</th>
                                <th class="d-none d-lg-table-cell">Alasan / Keperluan</th>
                                <th width="120px" class="text-center">Status</th>
                                <th class="d-none d-md-table-cell">Catatan Kepala</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permits as $permit)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-sm">{{ $permit->type }}</div>
                                    <div class="text-[10px] text-muted d-md-none">
                                        {{ \Carbon\Carbon::parse($permit->start_date)->translatedFormat('d/m/y') }}
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="text-sm font-weight-bold">
                                        {{ \Carbon\Carbon::parse($permit->start_date)->translatedFormat('d M Y') }}
                                    </div>
                                    @if($permit->end_date)
                                        <div class="text-[10px] text-muted">s/d {{ \Carbon\Carbon::parse($permit->end_date)->translatedFormat('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="d-none d-lg-table-cell" style="max-width: 250px;">
                                    <p class="mb-0 text-sm font-weight-500">{{ $permit->reason }}</p>
                                    @if($permit->attachment)
                                        <a href="{{ asset('storage/' . $permit->attachment) }}" target="_blank" class="text-info text-[10px] font-bold uppercase mt-1 d-block">
                                            <i class="fas fa-paperclip mr-1"></i> Lihat Lampiran
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-pill px-3 py-2 text-[8px] md:text-[10px] uppercase font-weight-black
                                        @if($permit->status == 'approved') badge-success @elseif($permit->status == 'rejected') badge-danger @else badge-warning @endif">
                                        @if($permit->status == 'approved') Disetujui @elseif($permit->status == 'rejected') Ditolak @else Menunggu @endif
                                    </span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if($permit->note)
                                        <p class="mb-0 text-xs font-italic text-muted">"{{ $permit->note }}"</p>
                                    @else
                                        <span class="text-xs text-slate-300 font-italic">Tidak ada catatan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <p class="text-muted font-weight-bold text-xs uppercase tracking-widest">Belum ada pengajuan izin.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
    .premium-card { border-radius: 20px; overflow: hidden; border: none; }
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }
    
    #myPermitTable td { padding: 1.25rem 0.75rem; vertical-align: middle; }
    .badge-soft-dark { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

    @media (max-width: 768px) {
        #myPermitTable td { padding: 0.75rem 0.5rem; }
        .bg-circle-1 { width: 150px; height: 150px; }
        .bg-circle-2 { width: 80px; height: 80px; }
        h2.font-weight-bold { font-size: 1.4rem !important; }
    }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<script>
    $(function() {
        $('#myPermitTable').DataTable({
            autoWidth: false,
            language: { searchPlaceholder: "Cari riwayat...", search: "" }
        });
    });
</script>
@endpush
