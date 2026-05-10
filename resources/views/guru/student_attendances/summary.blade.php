@extends($layout)
@section('title', 'Rekap Absensi Per Siswa')

@section('content')
<div class="dashboard-wrapper pb-20">
    {{-- HEADER --}}
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center border border-white/30 shadow-xl">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <span class="bg-white/20 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-1 inline-block">Analitik Kehadiran</span>
                        <h1 class="text-2xl font-black leading-tight">Rekap Absensi Per Siswa</h1>
                        <p class="text-white/70 text-xs font-bold mt-1">Pantau tingkat kehadiran individu siswa Anda.</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('guru.student-attendances.index') }}" class="bg-white/10 hover:bg-white/20 text-white text-xs font-black px-5 py-3 rounded-2xl border border-white/20 transition-all">
                        <i class="fas fa-calendar-alt mr-2"></i> Presensi Harian
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute right-[-50px] top-[-30px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    {{-- CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 -mt-12 relative z-20">
        {{-- FILTER --}}
        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-slate-50 mb-6">
            <form action="{{ route('guru.student-attendances.summary') }}" method="GET" class="row g-3 items-end">
                <div class="col-md-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Pilih Kelas</label>
                    <select name="class_id" class="form-control rounded-2xl border-slate-100 bg-slate-50 font-bold text-sm" style="height:50px" onchange="this.form.submit()">
                        @foreach($myClasses as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->kelas_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 text-right">
                    <div class="flex justify-end space-x-4">
                        <div class="text-right">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Total Siswa</span>
                            <span class="text-xl font-black text-slate-800">{{ $students->count() }}</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- STUDENT GRID --}}
        <div class="row g-4">
            @forelse($students as $student)
                @php
                    $color = 'emerald';
                    if($student->percentage < 75) $color = 'rose';
                    elseif($student->percentage < 90) $color = 'amber';
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="bg-white rounded-[2rem] shadow-lg border border-slate-50 p-6 hover:shadow-2xl transition-all duration-300 group">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="relative">
                                <img src="{{ $student->profile && $student->profile->foto ? asset('storage/' . $student->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($student->nama_lengkap) . '&background=6366f1&color=fff&bold=true' }}" 
                                     class="w-16 h-16 rounded-[1.2rem] object-cover shadow-md group-hover:scale-105 transition-transform">
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-{{ $color }}-500 border-4 border-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-[8px] text-white"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-black text-slate-800 truncate mb-1">{{ $student->nama_lengkap }}</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIS: {{ $student->nis ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-end">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tingkat Kehadiran</span>
                                <span class="text-sm font-black text-{{ $color }}-600">{{ $student->percentage }}%</span>
                            </div>
                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden border border-slate-50 p-[2px]">
                                <div class="h-full bg-{{ $color }}-500 rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $student->percentage }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-2 mb-6">
                            <div class="text-center p-2 bg-slate-50 rounded-xl">
                                <span class="text-[8px] font-black text-slate-400 block uppercase">Hadir</span>
                                <span class="text-xs font-black text-slate-700">{{ $student->present_count }}</span>
                            </div>
                            <div class="text-center p-2 bg-slate-50 rounded-xl">
                                <span class="text-[8px] font-black text-slate-400 block uppercase">Izin</span>
                                <span class="text-xs font-black text-slate-700">{{ $student->permit_count }}</span>
                            </div>
                            <div class="text-center p-2 bg-slate-50 rounded-xl">
                                <span class="text-[8px] font-black text-slate-400 block uppercase">Sakit</span>
                                <span class="text-xs font-black text-slate-700">{{ $student->sick_count }}</span>
                            </div>
                            <div class="text-center p-2 bg-slate-50 rounded-xl">
                                <span class="text-[8px] font-black text-slate-400 block uppercase">Alpa</span>
                                <span class="text-xs font-black text-rose-500">{{ $student->absent_count }}</span>
                            </div>
                        </div>

                        <a href="{{ route('guru.student-attendances.detail', $student->id) }}" class="w-full block text-center py-3 bg-{{ $color }}-50 text-{{ $color }}-600 hover:bg-{{ $color }}-500 hover:text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                            Lihat Detail Log <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-slate-200">
                        <i class="fas fa-user-friends text-slate-200 fa-4x mb-4"></i>
                        <h3 class="text-lg font-black text-slate-400">Pilih Kelas untuk melihat data</h3>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.bg-grad-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
.text-emerald-600 { color: #059669; }
.bg-emerald-500 { background-color: #10b981; }
.bg-emerald-50 { background-color: #ecfdf5; }
.text-emerald-600 { color: #059669; }
.hover\:bg-emerald-500:hover { background-color: #10b981; }

.text-amber-600 { color: #d97706; }
.bg-amber-500 { background-color: #f59e0b; }
.bg-amber-50 { background-color: #fffbeb; }
.hover\:bg-amber-500:hover { background-color: #f59e0b; }

.text-rose-600 { color: #e11d48; }
.bg-rose-500 { background-color: #f43f5e; }
.bg-rose-50 { background-color: #fff1f2; }
.hover\:bg-rose-500:hover { background-color: #f43f5e; }
</style>
@endsection
