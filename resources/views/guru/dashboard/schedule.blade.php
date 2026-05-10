@extends('layouts.teacher')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="min-h-screen bg-[#0f172a] text-white pb-24">
    <!-- Header -->
    <div class="px-6 pt-12 pb-8 bg-gradient-to-b from-slate-900 to-[#0f172a]">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-2xl font-black tracking-tight mb-1">Jadwal Mengajar</h1>
                <p class="text-slate-400 text-sm font-medium">Atur waktu dan persiapan materi Anda.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('guru.schedule.print') }}" target="_blank" class="w-12 h-12 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex items-center justify-center hover:bg-indigo-500 transition-all group shadow-lg shadow-indigo-500/10">
                    <i class="fas fa-print text-indigo-400 group-hover:text-white text-xl"></i>
                </a>
                <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-emerald-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Day Selector -->
    <div class="px-6 mb-8 overflow-x-auto no-scrollbar">
        <div class="flex space-x-3 pb-2 min-w-max">
            @php
                $days = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu'
                ];
                $currentDay = request('day', \Carbon\Carbon::now()->dayOfWeekIso);
                if($currentDay > 6) $currentDay = 1;
            @endphp

            @foreach($days as $index => $dayName)
                <a href="{{ route('guru.schedule', ['day' => $index]) }}" 
                   class="px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 {{ $currentDay == $index ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30 scale-105' : 'bg-slate-800/50 text-slate-400 border border-slate-700/50 hover:bg-slate-800' }}">
                    {{ $dayName }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Schedule List -->
    <div class="px-6">
        @if($schedules->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-slate-900/40 border border-slate-800/50 rounded-[2.5rem] border-dashed">
                <div class="w-20 h-20 bg-slate-800/50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-mug-hot text-slate-500 text-3xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-300">Tidak Ada Jadwal</h3>
                <p class="text-slate-500 text-sm mt-1">Hari ini adalah waktu istirahat Anda.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($schedules as $schedule)
                    <div class="relative overflow-hidden group cursor-pointer" onclick="showClassDetail('{{ $schedule->class_group_id }}', '{{ $schedule->subject->name }}')">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="bg-slate-900/60 border border-slate-800/50 rounded-[2rem] p-5 relative z-10 hover:border-emerald-500/30 transition-colors duration-300">
                            <div class="flex items-center space-x-5">
                                <!-- Time Block -->
                                <div class="flex flex-col items-center justify-center w-20 h-20 bg-slate-800/80 rounded-3xl border border-slate-700/50 group-hover:border-emerald-500/50 transition-colors duration-300">
                                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-tighter mb-1">Jam Ke-{{ $schedule->studyPeriod->period_number }}</span>
                                    <span class="text-base font-black tracking-tight text-white">{{ $schedule->studyPeriod->start_time }}</span>
                                    <div class="w-4 h-[1.5px] bg-slate-600 my-0.5"></div>
                                    <span class="text-[11px] font-bold text-slate-400">{{ $schedule->studyPeriod->end_time }}</span>
                                </div>

                                <!-- Info Block -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 text-[9px] font-black rounded-lg border border-emerald-500/20 uppercase tracking-widest">
                                            {{ $schedule->classGroup->kelas_lengkap }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-black text-white leading-tight mb-1 truncate">{{ $schedule->subject->name }}</h3>
                                    <div class="flex items-center text-slate-500 text-xs font-medium">
                                        <i class="fas fa-map-marker-alt mr-1.5 text-slate-600"></i>
                                        Ruang Kelas {{ $schedule->classGroup->name }}
                                    </div>
                                </div>

                                <!-- Action -->
                                <div class="hidden sm:block">
                                    <button class="w-10 h-10 bg-slate-800/80 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-users text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Bottom Info -->
    <div class="px-6 mt-8">
        <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/20 rounded-[2rem] p-6">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-indigo-400"></i>
                </div>
                <div>
                    <h4 class="font-black text-white text-sm mb-1 uppercase tracking-wider">Informasi Akademik</h4>
                    <p class="text-slate-400 text-xs leading-relaxed font-medium">
                        Jadwal ini disesuaikan dengan kalender akademik aktif tahun <b>{{ \App\Models\AcademicYear::where('current_semester', true)->first()->academic_year ?? '2023/2024' }}</b>. Perubahan jadwal sewaktu-waktu dapat diinformasikan melalui menu Pengumuman.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Kelas -->
<div class="modal fade" id="modalClassDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
            <div class="p-8 pb-4">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/20">
                            <i class="fas fa-users text-emerald-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-white" id="detailClassName">Detail Kelas</h4>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest" id="detailSubjectName">Mata Pelajaran</p>
                        </div>
                    </div>
                    <button type="button" class="text-slate-500 hover:text-white transition-colors" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="max-h-[60vh] overflow-y-auto no-scrollbar" id="studentListContainer">
                    <!-- Loading state -->
                </div>
            </div>
            <div class="p-8 pt-0">
                <button type="button" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-black py-4 rounded-2xl transition-all uppercase tracking-widest text-xs" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection

@push('scripts')
<script>
function showClassDetail(classId, subjectName) {
    $('#detailSubjectName').text(subjectName);
    $('#modalClassDetail').modal('show');
    $('#studentListContainer').html(`
        <div class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
            <p class="text-xs font-bold text-slate-500 mt-4 uppercase tracking-widest">Memuat Daftar Siswa...</p>
        </div>
    `);

    $.get(`{{ url('guru/class-students') }}/${classId}`)
        .done(response => {
            $('#detailClassName').text('Siswa Kelas ' + response.class);
            let html = '<div class="space-y-3">';
            if(response.students.length > 0) {
                response.students.forEach((student, index) => {
                    html += `
                        <div class="flex items-center justify-between p-4 bg-slate-800/40 border border-slate-700/50 rounded-2xl hover:bg-slate-800 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-emerald-500/10 text-emerald-400 rounded-lg flex items-center justify-center font-black text-[10px]">
                                    ${index + 1}
                                </div>
                                <div>
                                    <h5 class="text-sm font-black text-white mb-0">${student.nama_lengkap}</h5>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase">NIS: ${student.nis ?? '-'}</p>
                                </div>
                            </div>
                            <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                        </div>
                    `;
                });
            } else {
                html += '<p class="text-center text-slate-500 text-sm font-bold py-4">Tidak ada siswa aktif di kelas ini.</p>';
            }
            html += '</div>';
            $('#studentListContainer').html(html);
        })
        .fail(error => {
            $('#studentListContainer').html('<p class="text-center text-rose-500 text-sm font-bold py-4">Gagal memuat data.</p>');
        });
}
</script>
@endpush
