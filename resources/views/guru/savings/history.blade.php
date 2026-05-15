@extends($layout)

@section('title', 'Riwayat Tabungan')

@section('content')
<!-- ANDROID-STYLE UI FOR GURU SAVINGS HISTORY -->
<div class="savings-history-wrapper pb-20 font-outfit">
    <!-- TOP HEADER -->
    <div class="header-banner bg-grad-rose pt-10 pb-24 px-6 relative overflow-hidden rounded-b-[3rem]">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('guru.savings.index') }}" class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition-all">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="text-center text-white">
                    <h1 class="text-xl font-black tracking-tight">Riwayat Tabungan</h1>
                    <p class="text-white/60 text-[9px] font-black uppercase tracking-widest mt-1">Detail Transaksi Siswa</p>
                </div>
                <button onclick="window.print()" class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition-all">
                    <i class="fas fa-print text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-md mx-auto px-6 -mt-16 relative z-20">
        <!-- STUDENT MINI PROFILE CARD -->
        <div class="bg-white rounded-[2.5rem] p-6 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-8 relative overflow-hidden">
            <div class="flex items-center space-x-5 relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl shadow-inner border border-indigo-100">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-black text-slate-800 mb-1">{{ $student->nama_lengkap }}</h2>
                    <span class="bg-indigo-50 text-indigo-500 text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">{{ $student->classGroup->kelas_lengkap ?? '-' }}</span>
                </div>
            </div>
            <hr class="my-4 border-slate-50">
            <div class="text-center">
                <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">Saldo Saat Ini</span>
                <h3 class="text-2xl font-black text-emerald-600 mb-0">Rp {{ number_format($student->savings->balance ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- TRANSACTION TIMELINE -->
        <div class="space-y-4">
            <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-4">Timeline Transaksi</h5>
            
            @forelse($transactions as $t)
                <div class="bg-white rounded-[1.8rem] p-5 shadow-xl shadow-slate-200/40 border border-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm {{ $t->type == 'debit' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }}">
                                <i class="fas fa-{{ $t->type == 'debit' ? 'arrow-down' : 'arrow-up' }} text-sm"></i>
                            </div>
                            <div>
                                <h6 class="text-xs font-black text-slate-800 mb-0">{{ $t->type == 'debit' ? 'Setoran' : 'Penarikan' }}</h6>
                                <p class="text-[9px] text-slate-400 font-bold mb-0">
                                    {{ $t->created_at->format('d/m/Y, H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h5 class="text-sm font-black mb-0 {{ $t->type == 'debit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->type == 'debit' ? '+' : '-' }} {{ number_format($t->amount, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2rem] p-10 text-center shadow-xl border border-slate-50">
                    <p class="font-black text-slate-400 text-xs">Belum ada transaksi</p>
                </div>
            @endforelse
        </div>

        @if($transactions->hasPages())
            <div class="mt-8 mb-20">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap');
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .bg-grad-rose { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }
    
    @media print {
        .header-banner a, .header-banner button, .pagination { display: none !important; }
        .max-w-md { max-width: 100% !important; margin: 0 !important; }
    }
</style>
@endsection
