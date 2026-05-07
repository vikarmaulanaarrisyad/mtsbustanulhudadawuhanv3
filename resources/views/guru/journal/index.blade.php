@extends('layouts.teacher')

@section('content')
<div class="min-h-screen pb-32 bg-slate-50">
    <!-- HEADER -->
    <div class="bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10 text-white">
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('guru.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/10 hover:bg-white/20 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-black tracking-tight uppercase">Jurnal KBM Harian</h1>
                <div class="w-12"></div>
            </div>

            <div class="text-center mb-8">
                <p class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em] mb-2">Jadwal Mengajar Hari Ini</p>
                <h2 class="text-2xl font-black leading-none">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h2>
            </div>
        </div>
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        <div class="space-y-4">
            @forelse($schedules as $s)
                @php $isFilled = in_array($s->id, $filledJournals); @endphp
                <div class="bg-white rounded-[2rem] p-6 shadow-xl border {{ $isFilled ? 'border-emerald-100' : 'border-slate-50' }} flex items-center justify-between group hover:shadow-2xl transition-all">
                    <div class="flex items-center space-x-5">
                        <div class="w-16 h-16 {{ $isFilled ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400' }} rounded-2xl flex flex-col items-center justify-center shadow-sm">
                            <span class="text-[10px] font-black uppercase tracking-tighter">Jam</span>
                            <span class="text-lg font-black leading-none">{{ $s->studyPeriod->period_name }}</span>
                        </div>
                        <div>
                            <h4 class="text-base font-black text-slate-800 mb-1 group-hover:text-indigo-600 transition-colors">{{ $s->subject->name }}</h4>
                            <div class="flex items-center space-x-3 text-slate-400">
                                <span class="text-[10px] font-bold uppercase tracking-widest"><i class="fas fa-door-open mr-1"></i> {{ $s->classGroup->kelas_lengkap }}</span>
                                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                <span class="text-[10px] font-bold uppercase tracking-widest"><i class="fas fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($s->studyPeriod->start_time)->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($isFilled)
                        <div class="flex flex-col items-end">
                            <span class="bg-emerald-50 text-emerald-600 text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest border border-emerald-100 mb-2">
                                <i class="fas fa-check-circle mr-1"></i> Terisi
                            </span>
                            <button class="text-slate-300 text-xs font-bold" disabled>Lihat Detail</button>
                        </div>
                    @else
                        <a href="{{ route('guru.journal.create', ['schedule_id' => $s->id]) }}" class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100 hover:scale-110 transition-all active:scale-95">
                            <i class="fas fa-edit"></i>
                        </a>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-[2.5rem] p-12 text-center shadow-xl border border-slate-50">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 text-3xl">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">Tidak ada jadwal hari ini</h3>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
</style>
@endsection
