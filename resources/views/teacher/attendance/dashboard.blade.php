@extends($layout)

@section('title', 'Log Kehadiran')

@section('content')
<div class="min-h-screen bg-slate-50 pb-24">
    <!-- Premium Header Area -->
    <div class="bg-indigo-600 pt-12 pb-24 px-6 rounded-b-[3.5rem] shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>
        
        <div class="flex items-center justify-between relative z-10 mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/30">
                    <i class="fas fa-chevron-left text-sm"></i>
                </a>
                <div>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest opacity-80">Laporan Personal</p>
                    <h1 class="text-white text-xl font-black leading-tight">Riwayat Absensi</h1>
                </div>
            </div>
            <div class="bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/30 text-white text-[10px] font-black uppercase tracking-widest">
                {{ date('F Y') }}
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="grid grid-cols-3 gap-3 relative z-10">
            @php
                $history = \App\Models\Attendance::where('teacher_id', $teacher->id)
                    ->whereMonth('date', date('m'))
                    ->whereYear('date', date('Y'))
                    ->orderBy('date', 'desc')
                    ->get();
                
                $present = $history->where('status', 'present')->count();
                $late = $history->where('status', 'late')->count();
                $absent = $history->where('status', 'absent')->count();
            @endphp
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-3 text-center">
                <p class="text-indigo-100 text-[8px] font-black uppercase tracking-wider mb-1">Hadir</p>
                <p class="text-white font-black text-lg leading-none">{{ $present }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-3 text-center">
                <p class="text-indigo-100 text-[8px] font-black uppercase tracking-wider mb-1">Lambat</p>
                <p class="text-white font-black text-lg leading-none">{{ $late }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-3 text-center">
                <p class="text-indigo-100 text-[8px] font-black uppercase tracking-wider mb-1">Izin/Sakit</p>
                <p class="text-white font-black text-lg leading-none">{{ $absent }}</p>
            </div>
        </div>
    </div>

    <!-- History List -->
    <div class="px-6 -mt-12 relative z-20">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 p-6 mb-8 border border-slate-50 min-h-[400px]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-slate-800 font-black text-base tracking-tight">Log Bulan Ini</h3>
                <i class="fas fa-history text-slate-200 text-xl"></i>
            </div>

            <div class="space-y-4">
                @forelse($history as $h)
                <div class="flex items-start space-x-4 p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 group transition-all active:scale-[0.98]">
                    <div class="w-12 h-12 bg-white rounded-2xl flex flex-col items-center justify-center border border-slate-200 shadow-sm">
                        <span class="text-slate-400 text-[8px] font-black uppercase leading-none mb-1">{{ $h->date->translatedFormat('M') }}</span>
                        <span class="text-slate-800 font-black text-base leading-none">{{ $h->date->translatedFormat('d') }}</span>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h5 class="text-slate-800 font-black text-sm mb-0 leading-tight">{{ $h->date->translatedFormat('l') }}</h5>
                            <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-{{ $h->status == 'present' ? 'emerald-100 text-emerald-600' : ($h->status == 'late' ? 'amber-100 text-amber-600' : 'rose-100 text-rose-600') }}">
                                {{ $h->status == 'present' ? 'Hadir' : ($h->status == 'late' ? 'Terlambat' : 'Absen') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                <p class="text-[10px] text-slate-500 font-bold tracking-tight">In: <span class="text-slate-700">{{ $h->check_in ? \Carbon\Carbon::parse($h->check_in)->format('H:i') : '--:--' }}</span></p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
                                <p class="text-[10px] text-slate-500 font-bold tracking-tight">Out: <span class="text-slate-700">{{ $h->check_out ? \Carbon\Carbon::parse($h->check_out)->format('H:i') : '--:--' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-slate-200">
                        <i class="fas fa-folder-open text-3xl text-slate-200"></i>
                    </div>
                    <p class="text-slate-400 font-black text-xs uppercase tracking-widest">Belum ada data absensi</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Export Action -->
        <button class="w-full bg-slate-800 text-white rounded-3xl py-4 font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 flex items-center justify-center space-x-3 active:scale-95 transition-all">
            <i class="fas fa-file-pdf"></i>
            <span>Unduh Laporan PDF</span>
        </button>
    </div>
</div>

<style>
    body { background-color: #f8fafc; }
    .bg-emerald-100 { background-color: #d1fae5; }
    .text-emerald-600 { color: #059669; }
    .bg-amber-100 { background-color: #fef3c7; }
    .text-amber-600 { color: #d97706; }
    .bg-rose-100 { background-color: #fee2e2; }
    .text-rose-600 { color: #dc2626; }
</style>
@endsection
