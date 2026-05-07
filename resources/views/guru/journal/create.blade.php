@extends('layouts.teacher')

@section('content')
<div class="min-h-screen pb-32 bg-slate-50">
    <!-- HEADER -->
    <div class="bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10 text-white text-center">
            <h1 class="text-xl font-black tracking-tight uppercase mb-2">Input Jurnal KBM</h1>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl py-2 px-4 inline-block border border-white/20">
                <p class="text-[10px] font-black tracking-widest uppercase mb-0">{{ $schedule->subject->name }} • {{ $schedule->classGroup->kelas_lengkap }}</p>
            </div>
        </div>
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        <form action="{{ route('guru.journal.store') }}" method="POST">
            @csrf
            <input type="hidden" name="class_schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="date" value="{{ date('Y-m-d') }}">

            <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-50">
                <div class="p-8 space-y-8">
                    <!-- Material Summary -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block"><i class="fas fa-book-open mr-2 text-indigo-500"></i> Ringkasan Materi</label>
                        <textarea name="material_summary" rows="4" class="w-full bg-slate-50 border-0 rounded-3xl p-6 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500 shadow-inner" placeholder="Tuliskan pokok bahasan yang diajarkan hari ini..." required></textarea>
                    </div>

                    <!-- Absent Students -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block"><i class="fas fa-user-times mr-2 text-rose-500"></i> Siswa Tidak Hadir</label>
                        <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100 max-h-[300px] overflow-y-auto no-scrollbar">
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($students as $s)
                                    <label class="flex items-center space-x-3 p-3 bg-white rounded-2xl border border-slate-50 shadow-sm cursor-pointer hover:border-rose-200 transition-all">
                                        <input type="checkbox" name="absent_students[]" value="{{ $s->nama_lengkap }}" class="w-5 h-5 rounded-lg border-slate-200 text-rose-500 focus:ring-rose-500">
                                        <span class="text-xs font-bold text-slate-600">{{ $s->nama_lengkap }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-400 font-bold italic">* Centang siswa yang Alpa/Bolos di jam pelajaran ini.</p>
                    </div>

                    <!-- Additional Notes -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block"><i class="fas fa-sticky-note mr-2 text-amber-500"></i> Catatan Khusus (Opsional)</label>
                        <textarea name="student_notes" rows="3" class="w-full bg-slate-50 border-0 rounded-3xl p-6 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500 shadow-inner" placeholder="Contoh: Kejadian khusus di kelas, kendala KBM, dll..."></textarea>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 border-t border-slate-100 flex flex-col space-y-3">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-95 uppercase tracking-widest text-xs">Simpan Jurnal</button>
                    <a href="{{ route('guru.journal.index') }}" class="w-full text-center text-slate-400 font-bold text-[10px] uppercase tracking-widest py-2">Batal & Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
