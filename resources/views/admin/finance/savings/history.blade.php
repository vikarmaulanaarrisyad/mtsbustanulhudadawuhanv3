@extends($layout)

@section('title', 'Riwayat Tabungan')
@section('subtitle', 'Keuangan Siswa')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- PREMIUM HEADER BANNER (Penempatan Rombel style, upgraded) -->
<div class="row" style="font-family: 'Outfit', sans-serif;">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative animate__animated animate__fadeIn" style="border-radius: 20px;">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-black mb-1" style="font-weight: 800; font-size: 2.2rem; letter-spacing: -0.5px;">
                            <i class="fas fa-history mr-2 animate__animated animate__bounceIn"></i> 
                            Riwayat Tabungan Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light" style="font-size: 1.1rem;">
                            Detail transaksi setoran, penarikan, dan mutasi saldo tabungan siswa secara real-time.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block position-relative" style="z-index: 3;">
                        <i class="fas fa-piggy-bank fa-8x text-white opacity-2 shadow-icon floating-piggy"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Background Glow & Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row" style="font-family: 'Outfit', sans-serif;">
    <!-- LEFT COLUMN: STUDENT PROFILE & ACTIONS (Penempatan Rombel style, upgraded) -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft mb-4">
        
        <!-- STEP 1: STUDENT PROFILE -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-4 border-bottom-0">
                <h5 class="card-title font-weight-black text-dark mb-0" style="font-weight: 800; font-size: 1.15rem;">
                    <span class="step-badge mr-2">1</span> Informasi Siswa
                </h5>
            </div>
            <div class="card-body pt-0 text-center">
                <div class="avatar-lg mx-auto mb-3 bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center text-indigo shadow-sm font-weight-bold" style="width: 80px; height: 80px; border: 4px solid #fff; font-size: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    {{ substr($student->nama_lengkap, 0, 1) }}
                </div>
                
                <h4 class="font-weight-black text-dark mb-1" style="font-weight: 800; font-size: 1.3rem;">{{ $student->nama_lengkap }}</h4>
                <p class="text-muted text-xs font-weight-bold mb-3">NIS/NISN: {{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge badge-soft-primary py-2 px-3 rounded-pill font-weight-bold" style="font-size: 0.75rem;">
                        <i class="fas fa-layer-group mr-1"></i> {{ $student->classGroup->kelas_lengkap ?? '-' }}
                    </span>
                </div>
                
                <!-- EMERALD GLASSMORPHIC BALANCE BLOCK -->
                <div class="text-left p-3 rounded-lg border-left-success-thick" style="background: #ecfdf5; border-radius: 12px; border-left: 5px solid #10b981 !important;">
                    <span class="text-[10px] font-weight-bold text-muted uppercase tracking-wider d-block mb-1">Saldo Tabungan Aktif</span>
                    <h3 class="font-weight-black text-emerald mb-0" style="font-size: 1.8rem; font-weight: 900; letter-spacing: -0.5px;">
                        Rp {{ number_format($student->savings->balance ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- STEP 2: NAVIGATION & ACTIONS -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-4 border-bottom-0">
                <h5 class="card-title font-weight-black text-dark mb-0" style="font-weight: 800; font-size: 1.15rem;">
                    <span class="step-badge bg-success mr-2">2</span> Aksi & Cetak
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ $isGuru ? route('guru.savings.index') : route('admin.savings.index') }}" class="btn btn-outline-primary btn-block rounded-lg font-weight-bold py-3 shadow-xs" style="border-radius: 12px; border-width: 2px;">
                            <i class="fas fa-arrow-left mr-2"></i> KEMBALI
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ $isGuru ? route('guru.savings.print', $student->id) : route('admin.savings.print', $student->id) }}" target="_blank" class="btn btn-indigo btn-block rounded-lg font-weight-bold py-3 shadow-xs btn-premium d-flex align-items-center justify-content-center" style="border-radius: 12px; height: 50px;">
                            <i class="fas fa-print mr-2"></i> CETAK
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TRANSACTION HISTORY TABLE (Penempatan Rombel style, upgraded) -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight mb-4">
        <div class="card shadow-sm border-0 premium-card h-100">
            <div class="card-header bg-white py-4 px-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                <div>
                    <h4 class="mb-1 font-weight-black text-dark" style="font-weight: 800; font-size: 1.35rem; letter-spacing: -0.3px;">
                        <i class="fas fa-list text-indigo mr-2"></i> Riwayat Transaksi
                    </h4>
                    <p class="text-muted text-sm mb-0">Menampilkan mutasi saldo masuk dan keluar secara kronologis.</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableTransactions" style="width: 100%;">
                        <thead class="bg-light-info text-uppercase text-xs font-weight-bold">
                            <tr>
                                <th width="140px" class="text-center py-3">Tipe</th>
                                <th>Detail Mutasi</th>
                                <th class="text-right" width="160px">Jumlah</th>
                                <th class="text-right" width="160px">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                                <tr>
                                    <td class="text-center">
                                        @if($t->type == 'debit')
                                            <span class="badge badge-soft-success py-2 px-3 rounded-pill font-weight-bold" style="font-size: 0.75rem;">
                                                <i class="fas fa-arrow-down mr-1"></i> SETORAN
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger py-2 px-3 rounded-pill font-weight-bold" style="font-size: 0.75rem;">
                                                <i class="fas fa-arrow-up mr-1"></i> PENARIKAN
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="py-1">
                                            <div class="font-weight-black text-dark" style="font-size: 1.05rem; font-weight: 700;">
                                                {{ $t->type == 'debit' ? 'Setoran Tunai' : 'Penarikan Tunai' }}
                                                <span class="text-xs text-muted font-weight-normal ml-2">#{{ $t->reference_no }}</span>
                                            </div>
                                            <div class="text-xs text-muted font-weight-bold mt-1">
                                                {{ $t->created_at->translatedFormat('d F Y, H:i') }}
                                                <span class="text-indigo mx-1">•</span>
                                                <span>Oleh: {{ $t->creator->name ?? 'System' }}</span>
                                            </div>
                                            @if($t->description)
                                                <div class="mt-2 text-xs text-slate-500 font-italic bg-light p-2 rounded" style="border-radius: 8px;">
                                                    <i class="fas fa-quote-left mr-1 opacity-50"></i> {{ $t->description }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <h5 class="mb-0 font-weight-black {{ $t->type == 'debit' ? 'text-emerald' : 'text-danger' }}" style="font-weight: 800; font-size: 1.15rem;">
                                            {{ $t->type == 'debit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                        </h5>
                                    </td>
                                    <td class="text-right">
                                        <span class="font-weight-black text-dark text-sm" style="font-weight: 700;">Rp {{ number_format($t->current_balance, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="opacity-50 my-4">
                                            <i class="fas fa-history fa-4x mb-3 text-muted"></i>
                                            <h5 class="font-weight-bold text-muted" style="font-size: 1.15rem;">Belum Ada Riwayat</h5>
                                            <p class="text-sm px-5 text-muted">Siswa ini belum memiliki transaksi penyetoran atau penarikan tabungan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="py-4 px-4 border-top d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects (Aligning with placements UI) */
    .bg-gradient-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
    }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.25)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.08); border-radius: 50%; z-index: 1;
    }
    .bg-circle-1 { width: 320px; height: 320px; top: -110px; right: -60px; }
    .bg-circle-2 { width: 160px; height: 160px; bottom: -60px; left: 8%; }

    /* Card Styling */
    .premium-card {
        border-radius: 20px !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.06) !important;
    }
    .border-left-success-thick { border-left: 5px solid #10b981 !important; }

    /* Numbered Steps Badge (Penempatan Rombel style) */
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        background: #6366f1; color: #fff; font-size: 13px; font-weight: 800;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }
    
    /* Table Styling (Exact Penempatan Rombel Spacious Layout) */
    #tableTransactions { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #tableTransactions tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.015); 
        transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
        border-radius: 16px;
    }
    #tableTransactions tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.07); 
        transform: scale(1.006) translateY(-2px);
    }
    #tableTransactions td { border: none; padding: 1.35rem 0.75rem; vertical-align: middle; }
    #tableTransactions td:first-child { border-radius: 16px 0 0 16px; }
    #tableTransactions td:last-child { border-radius: 0 16px 16px 0; }
    .bg-light-info { background: #f4f6fc; color: #4f46e5; font-size: 0.75rem; font-weight: 800; letter-spacing: 1.2px; }

    /* Soft UI Components */
    .bg-soft-indigo { background: #e0e7ff; }
    .btn-premium { border-radius: 12px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(99, 102, 241, 0.2); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    .btn-indigo {
        background-color: #6366f1;
        border-color: #6366f1;
        color: #fff;
    }
    .btn-indigo:hover {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: #fff;
    }

    .text-indigo { color: #6366f1 !important; }
    .text-emerald { color: #10b981 !important; }
    .text-danger { color: #ef4444 !important; }
    
    .bg-gradient-indigo {
        background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%) !important;
    }
    .bg-emerald {
        background-color: #10b981 !important;
    }

    .badge-soft-success { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-soft-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
    .badge-soft-primary { background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }

    /* floating effect */
    .floating-piggy {
        animation: float 4s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(3deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }

    /* Print styles */
    @media print {
        .premium-card { box-shadow: none !important; border: none !important; }
        .col-xl-4 { display: none !important; }
        .col-xl-8 { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; }
        .bg-gradient-indigo { background: none !important; color: #000 !important; }
        .bg-gradient-indigo h2, .bg-gradient-indigo p { color: #000 !important; }
        .bg-circle-1, .bg-circle-2, .floating-piggy { display: none !important; }
        #tableTransactions tbody tr { box-shadow: none !important; border-bottom: 1px solid #e2e8f0 !important; border-radius: 0 !important; }
        #tableTransactions td { padding: 0.75rem !important; }
    }
</style>
@endsection
