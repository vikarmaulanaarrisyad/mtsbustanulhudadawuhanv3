@extends($layout)

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="bg-indigo-600 pt-10 pb-20 px-6 rounded-b-[3rem] shadow-xl">
    <div class="flex items-center space-x-4 mb-6 text-white">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-bold">Jadwal Mengajar</h1>
    </div>
    
    <!-- Weekday Picker -->
    <div class="flex space-x-3 overflow-x-auto pb-4 no-scrollbar">
        @php
            $days = [
                1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 
                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
            ];
            $currentDay = request('day', \Carbon\Carbon::now()->dayOfWeekIso);
            if($currentDay > 6) $currentDay = 1; // Fallback Minggu ke Senin
        @endphp

        @foreach($days as $index => $day)
            <a href="?day={{ $index }}" class="flex-shrink-0 px-6 py-3 rounded-2xl font-bold text-sm transition-all {{ $currentDay == $index ? 'bg-white text-indigo-600 shadow-lg' : 'bg-white/10 text-white' }}">
                {{ $day }}
            </a>
        @endforeach
    </div>
</div>

<div class="px-6 -mt-10 mb-10">
    <div class="space-y-4">
        @forelse($schedules as $schedule)
            <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full {{ $currentDay == \Carbon\Carbon::now()->dayOfWeekIso ? 'bg-indigo-500' : 'bg-slate-200' }}"></div>
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-4">
                        <div class="text-center bg-slate-50 p-2 rounded-xl border border-slate-100 min-w-[60px]">
                            <span class="block text-indigo-600 font-black text-sm">{{ $schedule->studyPeriod->start_time ?? '--:--' }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Jam Ke-{{ $schedule->studyPeriod->period_name ?? '-' }}</span>
                        </div>
                        <div>
                            <h3 class="text-slate-800 font-bold text-base mb-1">{{ $schedule->subject->name ?? '-' }}</h3>
                            <div class="flex items-center space-x-2">
                                <span class="bg-indigo-50 text-indigo-500 text-[10px] font-bold px-3 py-1 rounded-full">Kelas {{ $schedule->classGroup->class_group ?? '-' }}</span>
                                <span class="text-slate-400 text-[10px] font-medium"><i class="far fa-clock mr-1"></i> {{ $schedule->studyPeriod->end_time ?? '--:--' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($currentDay == \Carbon\Carbon::now()->dayOfWeekIso)
                        <a href="{{ route('student-attendances.index') }}" class="bg-emerald-500 text-white p-2 rounded-xl shadow-lg shadow-emerald-100">
                            <i class="fas fa-user-check"></i>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100">
                <i class="fas fa-calendar-times fa-3x text-slate-100 mb-4"></i>
                <p class="text-slate-400 font-bold">Tidak ada jadwal di hari {{ $days[$currentDay] }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
