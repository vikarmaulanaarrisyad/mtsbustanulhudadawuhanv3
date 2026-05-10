@extends($layout)
@section('title', 'Detail Absensi - ' . $student->nama_lengkap)

@section('content')
<div class="dashboard-wrapper pb-20">
    {{-- HEADER --}}
    <div class="header-banner bg-grad-slate pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex items-center space-x-6">
                <a href="{{ route('guru.student-attendances.summary') }}" class="w-12 h-12 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 text-white transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="flex items-center space-x-4">
                    <img src="{{ $student->profile && $student->profile->foto ? asset('storage/' . $student->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($student->nama_lengkap) . '&background=6366f1&color=fff&bold=true' }}" 
                         class="w-16 h-16 rounded-2xl object-cover border-2 border-white/30 shadow-2xl">
                    <div class="text-white">
                        <h1 class="text-2xl font-black leading-tight">{{ $student->nama_lengkap }}</h1>
                        <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">
                            <i class="fas fa-door-open mr-1"></i> {{ $student->classGroup->kelas_lengkap }} &bull; NIS: {{ $student->nis ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    {{-- STATS CARDS --}}
    <div class="max-w-7xl mx-auto px-4 -mt-12 relative z-20">
        <div class="row g-4 mb-8">
            <div class="col-md-3">
                <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50 text-center relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Total Kehadiran</span>
                        <div class="text-3xl font-black text-indigo-600 mb-1">{{ $percentage }}%</div>
                        <div class="text-[10px] font-bold text-slate-400 italic">Dari {{ $total }} Hari Tercatat</div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 text-slate-50 opacity-50">
                        <i class="fas fa-percent text-6xl"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50">
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="bg-emerald-50 rounded-2xl p-4 text-center border border-emerald-100">
                                <span class="text-[9px] font-black text-emerald-600 uppercase block mb-1">Hadir/Telat</span>
                                <span class="text-xl font-black text-emerald-700">{{ $present }}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-indigo-50 rounded-2xl p-4 text-center border border-indigo-100">
                                <span class="text-[9px] font-black text-indigo-600 uppercase block mb-1">Izin</span>
                                <span class="text-xl font-black text-indigo-700">{{ $stats['permit'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-blue-50 rounded-2xl p-4 text-center border border-blue-100">
                                <span class="text-[9px] font-black text-blue-600 uppercase block mb-1">Sakit</span>
                                <span class="text-xl font-black text-blue-700">{{ $stats['sick'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-rose-50 rounded-2xl p-4 text-center border border-rose-100">
                                <span class="text-[9px] font-black text-rose-600 uppercase block mb-1">Alpa</span>
                                <span class="text-xl font-black text-rose-700">{{ $stats['absent'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE LOGS --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-50 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h5 class="text-sm font-black text-slate-800 mb-0">Riwayat Kehadiran Lengkap</h5>
                <div class="badge badge-light py-2 px-4 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    {{ $student->nama_lengkap }}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center" width="80">NO</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">TANGGAL</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">WAKTU</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">STATUS</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">CATATAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $log)
                            @php
                                $badges = [
                                    'present' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'late'    => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'absent'  => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'permit'  => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'sick'    => 'bg-blue-100 text-blue-700 border-blue-200',
                                ];
                                $cls = $badges[$log->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="text-center text-sm font-black text-slate-400 px-8 py-4">{{ $attendances->firstItem() + $index }}</td>
                                <td class="px-4 py-4 text-sm font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($log->date)->translatedFormat('l, d M Y') }}
                                </td>
                                <td class="px-4 py-4 text-xs font-black text-slate-500">
                                    <i class="far fa-clock mr-1 opacity-50"></i> {{ \Carbon\Carbon::parse($log->time)->format('H:i') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $cls }}">
                                        {{ $log->status_label }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-xs font-medium text-slate-500 italic">
                                    {{ $log->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-20">
                                    <i class="fas fa-history text-slate-200 fa-3x mb-3"></i>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada riwayat absensi ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-8 bg-slate-50/50">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.bg-grad-slate { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
/* Pagination styling */
.pagination { margin-bottom: 0; justify-content: center; gap: 8px; }
.page-item .page-link { border: none; background: white; border-radius: 12px; font-weight: 800; font-size: 12px; padding: 10px 16px; color: #64748b; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
.page-item.active .page-link { background: #4f46e5; color: white; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }
</style>
@endsection
