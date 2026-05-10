@extends('layouts.ppdb')

@section('title', 'Nilai & Rapor Siswa')

@section('content')
<div class="dashboard-wrapper pb-20">
    <!-- TOP HEADER SECTION -->
    <div class="header-banner bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('siswa.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all shadow-xl border border-white/10">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="text-white">
                        <span class="bg-indigo-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg mb-2 inline-block">Hasil Belajar</span>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none">Nilai & Rapor</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Animated Background Elements -->
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        @forelse($grades as $academicYearId => $gradeGroup)
            @php 
                $academicYear = $gradeGroup->first()->academicYear;
            @endphp
            <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-10 overflow-hidden relative">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h4 class="text-2xl font-black text-slate-800 mb-1">Tahun Pelajaran {{ $academicYear->year ?? '-' }}</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Daftar nilai hasil evaluasi belajar</p>
                    </div>
                    <div class="bg-indigo-50 px-6 py-2 rounded-2xl text-indigo-600 text-xs font-black uppercase tracking-widest border border-indigo-100">
                        {{ $gradeGroup->count() }} Mata Pelajaran
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest rounded-l-2xl" width="50">NO</th>
                                <th class="px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">MATA PELAJARAN</th>
                                <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="100">TUGAS</th>
                                <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="100">UTS</th>
                                <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="100">UAS</th>
                                <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="120">NILAI AKHIR</th>
                                <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest rounded-r-2xl" width="100">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($gradeGroup as $index => $grade)
                                @php
                                    $finalGrade = $grade->final_grade ?? (($grade->task_score * 0.4) + ($grade->uts_score * 0.3) + ($grade->uas_score * 0.3));
                                    $status = $finalGrade >= 75 ? 'Lulus' : 'Remidi';
                                    $statusColor = $finalGrade >= 75 ? 'emerald' : 'rose';
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="text-center py-6 font-black text-slate-400 text-sm">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500">
                                                <i class="fas fa-book-open text-xs"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-sm font-black text-slate-700 mb-0">{{ $grade->subject->subject_name ?? '-' }}</h6>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $grade->subject->subject_code ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center font-bold text-slate-600">{{ $grade->task_score ?? '-' }}</td>
                                    <td class="text-center font-bold text-slate-600">{{ $grade->uts_score ?? '-' }}</td>
                                    <td class="text-center font-bold text-slate-600">{{ $grade->uas_score ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="text-xl font-black text-slate-800 tracking-tighter">{{ round($finalGrade) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="bg-{{ $statusColor }}-50 text-{{ $statusColor }}-600 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest border border-{{ $statusColor }}-100 inline-block">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-10 p-8 bg-slate-900 rounded-[2.5rem] text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl relative overflow-hidden">
                    <div class="relative z-10 text-center md:text-left">
                        <h5 class="text-xs font-black uppercase tracking-widest text-indigo-400 mb-1">Rata-rata Semester</h5>
                        <p class="text-lg font-black mb-0">Performa belajar Anda di tahun ini</p>
                    </div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="text-right">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-1">IP Semester</span>
                            <h4 class="text-4xl font-black mb-0">{{ number_format($gradeGroup->avg('final_grade'), 2) }}</h4>
                        </div>
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 backdrop-blur-md">
                            <i class="fas fa-chart-line text-2xl text-indigo-300"></i>
                        </div>
                    </div>
                    <!-- Background Decoration -->
                    <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[3rem] p-20 shadow-2xl shadow-slate-200/50 border border-slate-50 text-center">
                <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <i class="fas fa-folder-open text-4xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800 mb-2">Belum Ada Data Nilai</h4>
                <p class="text-sm text-slate-400 max-w-sm mx-auto">Data nilai Anda akan muncul di sini setelah guru melakukan input nilai pada periode akademik berjalan.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    .table thead th { border: none; }
</style>
@endsection
