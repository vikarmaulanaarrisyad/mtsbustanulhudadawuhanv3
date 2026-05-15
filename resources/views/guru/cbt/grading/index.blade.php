@extends($layout)

@section('title', 'Koreksi Essay CBT')

@section('content')
<div class="dashboard-wrapper pb-20">
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-white">
                    <h1 class="text-3xl font-black tracking-tight leading-tight">Koreksi Essay & Uraian</h1>
                    <p class="text-white/70 text-sm mt-1">Pilih jadwal ujian untuk mulai melakukan penilaian manual.</p>
                </div>
                <div class="mt-6 md:mt-0">
                    <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 text-white flex items-center space-x-3">
                        <i class="fas fa-pen-nib text-xl"></i>
                        <span class="font-bold uppercase tracking-widest text-xs">Penilaian Manual</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-12 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($exams as $exam)
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-50 hover:-translate-y-2 transition-all duration-500 group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest block mb-1">Status</span>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100 uppercase tracking-widest">AKTIF</span>
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-slate-800 mb-2 leading-tight">{{ $exam->name }}</h3>
                    <p class="text-xs font-bold text-slate-400 mb-6 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i> {{ $exam->exam_date->format('d M Y') }} 
                        <span class="mx-2">•</span> 
                        <i class="fas fa-book mr-2"></i> {{ $exam->bank->subject->name ?? 'Mapel' }}
                    </p>

                    <div class="bg-slate-50 rounded-2xl p-4 mb-8 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Penyelesaian</span>
                            <span class="text-lg font-black text-slate-800">{{ $exam->student_exams_count }} <small class="text-xs text-slate-400 uppercase">Siswa</small></span>
                        </div>
                        <i class="fas fa-users text-slate-200 text-2xl"></i>
                    </div>

                    <a href="{{ route('guru.cbt.grading.show', $exam->id) }}" class="w-full h-14 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest flex items-center justify-center hover:bg-slate-900 transition-all shadow-xl shadow-indigo-100 group-hover:shadow-indigo-200">
                        Pilih Ujian <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-[3rem] p-20 text-center border-4 border-dashed border-slate-100">
                        <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-800 mb-2">Belum Ada Ujian</h4>
                        <p class="text-slate-400 font-bold">Belum ada jadwal ujian yang dibuat atau perlu dikoreksi.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .bg-grad-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
</style>
@endsection
