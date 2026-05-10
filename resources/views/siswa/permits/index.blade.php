@extends('layouts.ppdb')

@section('title', 'Riwayat Izin')

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
                        <span class="bg-purple-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg mb-2 inline-block">Administrasi</span>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none">Riwayat Izin</h1>
                    </div>
                </div>
                <button data-toggle="modal" data-target="#modalPengajuanIzin" class="px-8 py-4 bg-white text-indigo-600 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl hover:-translate-y-1 transition-all flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i> Ajukan Izin Baru
                </button>
            </div>
        </div>
        <!-- Animated Background Elements -->
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-purple-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h4 class="text-2xl font-black text-slate-800 mb-1">Daftar Pengajuan</h4>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pantau status izin dan sakit Anda</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest rounded-l-2xl" width="50">NO</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">JENIS & TANGGAL</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">ALASAN / KETERANGAN</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="150">STATUS</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest rounded-r-2xl" width="120">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($permits as $index => $permit)
                            @php
                                $statusLabel = [
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak'
                                ];
                                $statusColor = [
                                    'pending' => 'amber',
                                    'approved' => 'emerald',
                                    'rejected' => 'rose'
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="text-center py-6 font-black text-slate-400 text-sm">{{ $permits->firstItem() + $index }}</td>
                                <td>
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-{{ $statusColor[$permit->status] }}-50 rounded-xl flex items-center justify-center text-{{ $statusColor[$permit->status] }}-500">
                                            <i class="fas fa-{{ $permit->type == 'Izin' ? 'envelope-open-text' : 'file-medical' }} text-xs"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-sm font-black text-slate-700 mb-0">{{ $permit->type }}</h6>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                                {{ $permit->start_date->translatedFormat('d M') }} 
                                                @if($permit->end_date) - {{ $permit->end_date->translatedFormat('d M Y') }} @else {{ $permit->start_date->translatedFormat('Y') }} @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-medium text-slate-500 mb-0 line-clamp-1">{{ $permit->reason }}</p>
                                </td>
                                <td class="text-center">
                                    <span class="bg-{{ $statusColor[$permit->status] }}-50 text-{{ $statusColor[$permit->status] }}-600 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest border border-{{ $statusColor[$permit->status] }}-100 inline-block shadow-sm">
                                        {{ $statusLabel[$permit->status] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($permit->attachment)
                                        <a href="{{ asset('storage/' . $permit->attachment) }}" target="_blank" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-500 transition-all mx-auto border border-slate-100">
                                            <i class="fas fa-paperclip text-xs"></i>
                                        </a>
                                    @else
                                        <span class="text-[9px] font-black text-slate-300 uppercase">Tanpa Berkas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                                        <i class="fas fa-history text-3xl"></i>
                                    </div>
                                    <h5 class="text-lg font-black text-slate-800 mb-1">Belum Ada Riwayat</h5>
                                    <p class="text-xs text-slate-400">Pengajuan izin atau sakit Anda akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-10">
                {{ $permits->links() }}
            </div>
        </div>
    </div>
</div>

@push('modals')
    @include('siswa.dashboard.modals')
@endpush

<style>
    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    .pagination { justify-content: center; }
    .page-item .page-link { border-radius: 12px; margin: 0 5px; border: none; font-weight: 800; color: #64748b; }
    .page-item.active .page-link { background-color: #4f46e5; color: white; }
</style>
@endsection
