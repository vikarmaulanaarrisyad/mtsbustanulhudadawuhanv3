@extends($layout)

@section('title', 'Riwayat Tabungan')

@section('content')
<div class="savings-history-wrapper pb-20 font-outfit bg-slate-50/30">
    <!-- TOP HEADER -->
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden rounded-b-[3rem]">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex items-center justify-between">
                <a href="{{ $isGuru ? route('guru.savings.index') : route('admin.savings.index') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="text-center text-white">
                    <h1 class="text-2xl font-black tracking-tight">Riwayat Tabungan</h1>
                    <p class="text-white/60 text-[10px] font-black uppercase tracking-widest mt-1">Detail Transaksi Siswa</p>
                </div>
                <button onclick="window.print()" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-4xl mx-auto px-6 -mt-16 relative z-20">
        <!-- STUDENT MINI PROFILE CARD -->
        <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-8 relative overflow-hidden">
            <div class="flex items-center space-x-6 relative z-10">
                <div class="w-20 h-20 rounded-[2rem] bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl shadow-inner border border-indigo-100">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="flex-grow">
                    <h2 class="text-2xl font-black text-slate-800 mb-1">{{ $student->nama_lengkap }}</h2>
                    <div class="flex items-center space-x-3">
                        <span class="bg-slate-100 text-slate-500 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $student->nisn ?? '---' }}</span>
                        <span class="bg-indigo-50 text-indigo-500 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $student->classGroup->kelas_lengkap ?? '-' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Saldo Saat Ini</span>
                    <h3 class="text-3xl font-black text-emerald-600 mb-0">Rp {{ number_format($student->savings->balance ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
            <!-- Background Shape -->
            <div class="absolute right-[-20px] bottom-[-20px] w-32 h-32 bg-slate-50 rounded-full opacity-50"></div>
        </div>

        <!-- TRANSACTION TIMELINE -->
        <div class="space-y-4">
            <h5 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 ml-4">Timeline Transaksi</h5>
            
            @forelse($transactions as $t)
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/40 border border-slate-50 hover:border-indigo-100 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-5">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm {{ $t->type == 'debit' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }}">
                                <i class="fas fa-{{ $t->type == 'debit' ? 'arrow-down' : 'arrow-up' }} text-xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-1">
                                    <h6 class="font-black text-slate-800 mb-0">{{ $t->type == 'debit' ? 'Setoran Tunai' : 'Penarikan Tunai' }}</h6>
                                    <span class="text-[9px] font-bold text-slate-300">#{{ $t->reference_no }}</span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium mb-0">
                                    {{ $t->created_at->translatedFormat('d F Y, H:i') }} • <span class="text-indigo-400 font-bold">Oleh: {{ $t->creator->name ?? 'System' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h5 class="text-lg font-black mb-1 {{ $t->type == 'debit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->type == 'debit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </h5>
                            <span class="block text-[10px] font-black text-slate-300 uppercase tracking-tighter">Saldo: Rp {{ number_format($t->current_balance, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if($t->description)
                    <div class="mt-4 pt-3 border-t border-slate-50">
                        <p class="text-xs text-slate-500 italic mb-0">
                            <i class="fas fa-quote-left mr-2 opacity-30 text-[10px]"></i> {{ $t->description }}
                        </p>
                    </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-[3rem] p-20 text-center shadow-xl border border-slate-50">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <i class="fas fa-history fa-2x"></i>
                    </div>
                    <p class="font-black text-slate-400">Belum ada riwayat transaksi</p>
                </div>
            @endforelse
        </div>

        @if($transactions->hasPages())
            <div class="mt-10 mb-20 px-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap');
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    
    /* Pagination Styling Custom for Mobile App Look */
    .pagination { justify-content: center; gap: 8px; }
    .page-item .page-link { border: none; border-radius: 12px; font-weight: 900; font-size: 14px; color: #64748b; background: white; padding: 10px 18px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    .page-item.active .page-link { background: #6366f1; color: white; box-shadow: 0 10px 15px rgba(99,102,241,0.3); }

    @media print {
        .header-banner a, .header-banner button, .pagination { display: none !important; }
        .max-w-4xl { max-width: 100% !important; margin: 0 !important; }
        .header-banner { border-radius: 0 !important; padding: 20px !important; }
    }
</style>
@endsection
