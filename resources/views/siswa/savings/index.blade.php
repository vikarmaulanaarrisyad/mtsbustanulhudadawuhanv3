@extends('layouts.ppdb')

@section('title', 'Tabungan Saya')

@section('content')
<!-- STUDENT SAVINGS - MOBILE APP STYLE -->
<div class="savings-wrapper pb-20 font-outfit bg-slate-50/50 min-h-screen">
    <!-- TOP HEADER -->
    <div class="header-banner bg-grad-indigo pt-10 pb-28 px-6 relative overflow-hidden rounded-b-[3.5rem]">
        <div class="max-w-7xl mx-auto relative z-10 text-white">
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('siswa.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-black tracking-tight">Tabungan Saya</h1>
                <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white">
                    <i class="fas fa-piggy-bank"></i>
                </div>
            </div>

            <div class="text-center">
                <span class="block text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-2">Saldo Terkumpul</span>
                <h2 class="text-5xl font-black text-white tracking-tighter mb-0">Rp {{ number_format($totalSavings, 0, ',', '.') }}</h2>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute right-[-100px] top-[-100px] w-80 h-80 bg-white/5 rounded-full blur-[100px]"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-xl mx-auto px-6 -mt-12 relative z-20">
        <!-- QUICK ACTIONS -->
        <div class="grid grid-cols-2 gap-4 mb-10">
            <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Setoran Terakhir</span>
                <h5 class="text-sm font-black text-slate-800 mb-0">Rp {{ number_format($totalDepositsToday, 0, ',', '.') }}</h5>
            </div>
            <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                    <i class="fas fa-history"></i>
                </div>
                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</span>
                <h5 class="text-sm font-black text-slate-800 mb-0">{{ $totalTransactionsToday }} <small class="text-slate-400">Log</small></h5>
            </div>
        </div>

        <!-- TRANSACTION HISTORY TIMELINE -->
        <div class="space-y-6">
            <div class="flex items-center justify-between px-2">
                <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest">Riwayat Transaksi</h5>
                <span class="w-12 h-1 bg-indigo-100 rounded-full"></span>
            </div>

            @forelse($transactions as $t)
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/30 border border-slate-50 hover:border-indigo-100 transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-5">
                            <div class="w-14 h-14 rounded-[1.2rem] flex items-center justify-center shadow-sm transition-all group-hover:scale-110 {{ $t->type == 'debit' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }}">
                                <i class="fas fa-{{ $t->type == 'debit' ? 'arrow-down' : 'arrow-up' }} text-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-black text-slate-800 mb-1">{{ $t->type == 'debit' ? 'Setoran Tunai' : 'Penarikan Tunai' }}</h6>
                                <p class="text-[10px] text-slate-400 font-bold mb-0">
                                    {{ $t->created_at->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h5 class="text-md font-black mb-1 {{ $t->type == 'debit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->type == 'debit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </h5>
                            <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest">Selesai</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] p-16 text-center shadow-xl border border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                        <i class="fas fa-piggy-bank fa-2x"></i>
                    </div>
                    <p class="font-black text-slate-400 text-xs">Belum ada aktivitas tabungan</p>
                </div>
            @endforelse

            @if($transactions->hasPages())
                <div class="pt-6 pb-10">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap');
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    
    /* Custom Pagination for App Look */
    .pagination { justify-content: center; gap: 8px; }
    .page-item .page-link { border: none; border-radius: 12px; font-weight: 900; font-size: 12px; color: #64748b; background: white; padding: 10px 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    .page-item.active .page-link { background: #6366f1; color: white; box-shadow: 0 10px 15px rgba(99,102,241,0.3); }
</style>
@endsection
