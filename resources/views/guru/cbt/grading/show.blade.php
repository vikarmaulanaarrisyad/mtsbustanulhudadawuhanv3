@extends($layout)

@section('title', 'Daftar Siswa - ' . $exam->name)

@section('content')
<div class="dashboard-wrapper pb-20">
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex items-center space-x-6">
                <a href="{{ route('guru.cbt.grading.index') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all border border-white/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="text-white">
                    <h1 class="text-2xl font-black tracking-tight leading-tight">{{ $exam->name }}</h1>
                    <p class="text-white/70 text-xs font-bold mt-1 uppercase tracking-widest">{{ $exam->bank->subject->name ?? 'Mapel' }} • {{ $students->count() }} Siswa Menyelesaikan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-12 relative z-20">
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Daftar Hasil Ujian</h3>
                    <p class="text-xs font-bold text-slate-400">Pilih siswa untuk mulai mengoreksi jawaban essay.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" id="search-student" class="w-64 pl-12 pr-6 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Cari Nama Siswa...">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Waktu Pengerjaan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Pelanggaran</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Nilai Sementara</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($students as $studentExam)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="relative">
                                            @if($studentExam->student->profile && $studentExam->student->profile->foto)
                                                <img src="{{ Storage::url($studentExam->student->profile->foto) }}" class="w-12 h-12 rounded-2xl object-cover shadow-lg" alt="Foto">
                                            @else
                                                <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg">
                                                    {{ substr($studentExam->student->nama_lengkap, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-800">{{ $studentExam->student->nama_lengkap }}</h4>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIS: {{ $studentExam->student->nis }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-xs font-black text-slate-700">{{ \Carbon\Carbon::parse($studentExam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($studentExam->end_time)->format('H:i') }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($studentExam->start_time)->diffInMinutes($studentExam->end_time) }} Menit</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($studentExam->violation_count > 0)
                                        <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-black rounded-full border border-rose-100 uppercase tracking-widest">
                                            {{ $studentExam->violation_count }} Pelanggaran
                                        </span>
                                    @else
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Aman</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-2xl font-black {{ $studentExam->final_score >= 75 ? 'text-emerald-600' : 'text-slate-800' }}">{{ number_format($studentExam->final_score, 1) }}</span>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-[0.2em]">Total Poin</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <a href="{{ route('guru.cbt.grading.grade', $studentExam->id) }}" class="inline-flex h-11 px-6 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest items-center justify-center hover:bg-indigo-600 transition-all shadow-lg hover:scale-105">
                                        Koreksi <i class="fas fa-edit ml-2"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-grad-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
</style>

<script>
    document.getElementById('search-student').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('h4').textContent.toLowerCase();
            const nis = row.querySelector('p').textContent.toLowerCase();
            if (name.includes(query) || nis.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
